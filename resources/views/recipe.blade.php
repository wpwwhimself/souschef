@extends('layouts.app', compact("title"))

@section('content')

<section>
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-scroll"></i>
            Składniki
        </h1>
    </div>

    <form action="{{ route('recipe-clear', ['id' => $recipe->id]) }}" method="post">
        @csrf
        <table id="recipe-table">
            <thead>
                <tr>
                    <th>Składnik</th>
                    <th>Kategoria</th>
                    <th>Ilość</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recipe->ingredients as $i)
                <tr @if($available[$i->ingredient_template_id] < $i->amount) class="error" @endif>
                    <td>{{ $i->template->name }}</td>
                    <td>{{ $i->template->category->name }}</td>
                    <td>
                        <input
                            type="number"
                            name="{{ $i->ingredient_template_id }}" value="{{ $i->amount }}"
                            />
                        / {{ $available[$i->ingredient_template_id] }}
                        {{ $i->template->unit }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if ($can_cook_recipe)
        <x-button action="submit" icon="check" label="Ugotuj z powyższych" />
        @else
        <h2 class="grayed-out">Niewystarczająca ilość składników</h2>
        @endif
    </form>

</section>

@endsection
