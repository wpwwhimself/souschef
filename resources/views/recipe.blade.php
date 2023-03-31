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
                    <th>Ilość do odjęcia</th>
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
                    <td @if($i->template->unit != "JNO") class="ingredients-amount-validator" @endif>
                        @if ($i->template->unit == "JNO")
                        █
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="1" />
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="0.5" />
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="0.25" />
                        <input type="radio" name="{{ $i->ingredient_template_id }}" value="0" checked />
                        ░
                        @else
                        <input
                            type="number" step="0.05"
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
        <div>
            <x-button action="submit" icon="check" label="Ugotuj z powyższych" id="cook_it" />
            <h2 class="grayed-out" id="ingredients_missing">Brakuje składników</h2>
            <script defer>
            function ingredientsSufficient(){
                let all_sufficient = true;
                document.querySelectorAll(".ingredients-amount-validator").forEach(field => {
                    const [needing, having] = [
                        +field.children[0].value,
                        +field.children[1].textContent.match(/\d+\.?\d*/g)[0]
                    ];
                    console.log(needing, having);
                    if(needing > having){
                        all_sufficient = false;
                        field.closest("tr").classList.add("error");
                    }else{
                        field.closest("tr").classList.remove("error");
                    }
                });

                document.getElementById("cook_it").style.display = (all_sufficient) ? "inline-block" : "none";
                document.getElementById("ingredients_missing").style.display = (!all_sufficient) ? "inline" : "none";
            }

            ingredientsSufficient();
            document.querySelectorAll(".ingredients-amount-validator input").forEach(input => {
                input.addEventListener("change", () => ingredientsSufficient());
            });
            </script>
            <x-button action="{{ route('recipe-mod', ['id' => $recipe->id]) }}" label="Modyfikuj" icon="pencil" :small="true" />              
        </div>
    </form>
</section>

@endsection
