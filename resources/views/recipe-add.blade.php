@extends('layouts.app', compact("title"))

@section('content')

<form action="{{ route('recipe-process') }}" method="post" class="section-like">
    @csrf
    <div class="section-header">
        <h1>
            @isset($recipe)
            <i class="fa-solid fa-pencil"></i>
            Co trzeba poprawić?
            @else
            <i class="fa-solid fa-plus"></i>
            Co nowego tworzymy?
            @endisset
        </h1>
    </div>
    <x-input type="text" name="name" label="Nazwa dania" value="{{ isset($recipe) ? $recipe->name : '' }}" />
    <x-input type="TEXT" name="desc" label="Opis/przepis/dopis" value="{{ isset($recipe) ? $recipe->desc : '' }}" />

    <div class="flex-right">
        <x-input type="checkbox" name="for_dinner" label="Obiad" value="{{ isset($recipe) && $recipe->for_dinner }}" />
        <x-input type="checkbox" name="for_supper" label="Kolacja" value="{{ isset($recipe) && $recipe->for_supper }}" />
    </div>

    <h2>Składniki</h2>
    <div id="recipe-ingredients">
        <div>
            <b>Składnik</b>
            <b style='grid-column: 2 / span 2'>Ilość</b>
        </div>
        @isset($recipe)
        @foreach ($recipe->ingredients as $ingredient)
            <div>
                <x-select name="ingredient_template_id[]" label="" :options="$templates" :empty-option="true" :small="true" value="{{ $ingredient->ingredient_template_id }}" />
                <x-input type="number" name="amount[]" label="" :small="true" step="0.01" value="{{ $ingredient->amount }}" />
                <span>{{ $ingredient->template->unit }}</span>
            </div>
        @endforeach
        @else
        <div>
            <x-select name="ingredient_template_id[]" label="" :options="$templates" :empty-option="true" :small="true" />
            <x-input type="number" name="amount[]" label="" :small="true" step="0.01" />
            <span></span>
        </div>
        @endisset
        <div class="ghost">
            <x-select name="ingredient_template_id[]" label="" :options="$templates" :empty-option="true" :small="true" />
            <x-input type="number" name="amount[]" label="" :small="true" step="0.01" />
            <span></span>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $(document).on('change', ".ghost input, .ghost select", function(){
            const row = $("#recipe-ingredients .ghost");

            row.clone().appendTo($("#recipe-ingredients"));
            row.removeClass("ghost");
        });
        $(document).on('change', "select[name^='ingredient_template_id']", function(){
            const unit_cont = $(this).parent().siblings("span");
            $.ajax({
                type: "get",
                url: "{{ route('ajax_ingredient_unit') }}",
                data: {
                    ing_id: $(this).val(),
                    csrf_token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    unit_cont.text(res);
                }
            });
        });
    });
    </script>
    @isset($recipe)
    <input type="hidden" name="recipe_id" value="{{ $recipe->id }}" />
    <x-button action="submit" icon="check" label="Popraw" />
    @else
    <x-button action="submit" icon="plus" label="Dodaj" />
    @endisset
</form>

@endsection
