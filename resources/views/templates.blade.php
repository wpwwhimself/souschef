@extends('layouts.app', compact("title"))

@section('content')

<div class="grid-2">
    <section>
        <p>
            Poniżej znajduje się lista składników,
            z których można przygotowywać przepisy.
            Są to etykiety dla pozycji,
            które znajdują się w lodówce i szafce.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Nazwa składnika</th>
                    <th>Kategoria</th>
                    <th @popper(Minimalna ilość)>Min.</th>
                    <th>g/jedn.</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                <tr class="clickable" data-id="{{ $template->id }}">
                    <td>{{ $template->name }}</td>
                    <td data-id="{{ $template->category->id }}">{{ $template->category->name }}</td>
                    <td data-value="{{ $template->minimum_amount }}" data-unit="{{ $template->unit }}"
                        @if ($template->minimum_amount === null) class="ghost" @endif
                        >
                        @if($template->minimum_amount === null)
                        bd.
                        @else
                        <x-amount :id="$template->id" :template="true" />
                        @endif
                    </td>
                    <td data-value="{{ $template->mass }}">{{ $template->mass }} g</td>
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
        <form action="{{ route("ingredient-template-add") }}" method="post">
            @csrf
            <h2>Dodaj wzorzec</h2>
            <div class="flex-right">
                <x-input type="text" name="name" label="Nazwa" autofocus />
                <x-input type="number" name="minimum_amount" label="Minimalna ilość" step="0.01" />
                <x-input type="text" name="unit" label="Jednostka" placeholder="JNO" />
                <x-input type="number" name="mass" label="Masa/jedn. [g]" placeholder="0" step="0.1" />
                <input type="hidden" name="id" id="id" value="" />
                <x-select name="ingredient_category_id" label="Kategoria" :options="$categories" />
            </div>
            <x-button action="submit" icon="check" label="Potwierdź" />
            <div class="flex-right" id="delete-template" style="display: none;">
                <x-button action="#/" icon="trash" label="Usuń" :small="true" :danger="true" />
            </div>
        </form>
    </section>
</div>

<script>
document.querySelectorAll("tr.clickable").forEach(el => {
    el.addEventListener("click", ev => {
        const row = ev.target.closest("tr");
        document.getElementById("id").value = row.getAttribute("data-id");
        document.getElementById("name").value = row.children[0].innerHTML;
        document.getElementById("minimum_amount").value = row.children[2].getAttribute("data-value");
        document.getElementById("unit").value = row.children[2].getAttribute("data-unit");
        document.getElementById("mass").value = row.children[3].getAttribute("data-value");
        document.getElementById("ingredient_category_id").value = row.children[1].getAttribute("data-id");
        // deleting button
        document.querySelector("#delete-template a.submit").href = `{{ route('ingredient-template-delete') }}/${row.getAttribute("data-id")}`;
        document.querySelector("#delete-template").style.display = "block";
    });
});
</script>

@endsection
