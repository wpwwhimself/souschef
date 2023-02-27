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
                <tr>
                    <td>{{ $i->template->name }}</td>
                    <td>{{ $i->template->category->name }}</td>
                    <td>
                        <input
                            type="number"
                            name="" value="{{ $i->amount }}"
                            />
                        {{ $i->template->unit }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <x-button action="submit" icon="check" label="Ugotuj z powyższych" />
    </form>

</section>

@endsection
