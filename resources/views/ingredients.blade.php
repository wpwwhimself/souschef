@extends('layouts.app', compact("title"))

@section('content')

<div class="grid-2">
    <section>
        <p>Poniżej znajduje się lista składników, z których można przygotowywać przepisy. Są to etykiety dla pozycji, które znajdują się w lodówce.

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa składnika</th>
                    <th>Jedn.</th>
                    <th>Kategoria</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ingredients as $ingredient)
                <tr>
                    <td>{{ $ingredient->id }}</td>
                    <td>{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->unit }}</td>
                    <td>{{ $ingredient->category->name }}</td>
                </tr>
                @empty
                <tr>
                    <td class="grayed-out" colspan=4>
                        Nie mam z czego gotować!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section>
        <form action="{{ route("ingredients-add") }}" method="post">
            @csrf
            <h2>Dodaj wzorzec</h2>
            <div class="flex-right">
                <x-input type="text" name="name" label="Nazwa" />
                <x-input type="text" name="unit" label="Jedn." placeholder="szt." />
                <x-select name="ingredient_category_id" label="Kategoria" :options="$ingredient_categories" />
            </div>
            <x-button action="submit" icon="plus" label="Dodaj" />
        </form>
    </section>
</div>

@endsection
