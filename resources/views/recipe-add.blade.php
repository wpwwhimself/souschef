@extends('layouts.app', compact("title"))

@section('content')

<form action="{{ route('recipe-process') }}" method="post" class="section-like">
    @csrf
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-plus"></i>
            Dodawanie nowego przepisu
        </h1>
    </div>
    <x-input type="text" name="name" label="Nazwa dania" />
    <x-input type="TEXT" name="desc" label="Opis/przepis/dopis" />

    <div class="flex-right">
        <x-input type="checkbox" name="for_dinner" label="Obiad" />
        <x-input type="checkbox" name="for_supper" label="Kolacja" />
    </div>

    <h2>Składniki</h2>
    <div id="recipe-ingredients">
        <div><b>Ilość</b><b>Składnik</b></div>
        <div>
            <x-input type="number" name="amount[]" label="" :small="true" step="0.01" />
            <x-select name="ingredient_template_id[]" label="" :options="$templates" :empty-option="true" :small="true" />
        </div>
        <div class="ghost">
            <x-input type="number" name="amount[]" label="" :small="true" step="0.01" />
            <x-select name="ingredient_template_id[]" label="" :options="$templates" :empty-option="true" :small="true" />
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $(document).on('change', ".ghost input, .ghost select", function(){
            const row = $("#recipe-ingredients .ghost");

            row.clone().appendTo($("#recipe-ingredients"));
            row.removeClass("ghost");
        });
    });
    </script>
    <x-button action="submit" icon="plus" label="Dodaj" />
</form>

@endsection
