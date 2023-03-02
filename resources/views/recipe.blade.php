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
                <tr
                    @if($available[$i->ingredient_template_id] < $i->amount)
                        class="error"
                    @elseif($available[$i->ingredient_template_id] == 0)
                        class="warning"
                    @endif
                    >
                    <td>{{ $i->template->name }}</td>
                    <td>{{ $i->template->category->name }}</td>
                    <td>
                        @if ($i->template->unit == "JNO")
                        ░
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="0" />
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="0.25" />
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="0.5" />
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="1" />
                        █
                        @else
                        <input
                            type="number"
                            name="{{ $i->ingredient_template_id }}" value="{{ $i->amount }}"
                            />
                        @endif
                        /
                        <x-amount :id="$i->template->id" :template="true" :force-amount="$available[$i->ingredient_template_id]" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if ($recipe->ingredientsSufficient())
        <x-button action="submit" icon="check" label="Ugotuj z powyższych" />
        @else
        <h2 class="grayed-out">Brakuje składników</h2>
        @endif
    </form>

</section>

@endsection
