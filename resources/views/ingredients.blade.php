@extends('layouts.app', compact("title"))

@section('content')

<div class="grid-2">
    @foreach ([
        ["Szafka", "sack-xmark", $cupboard],
        ["Lodówka", "cube", $fridge],
    ] as [$s_name, $s_icon, $s_positions])
    <section>
        <div class="section-header">
            <h1>
                <i class="fa-solid fa-{{ $s_icon }}"></i>
                {{ $s_name }}
            </h1>
        </div>
        <p></p>

        <table>
            <thead>
                <tr>
                    <th>Nazwa składnika</th>
                    <th>Kategoria</th>
                    <th>Ilość</th>
                    <th>Ważność</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($s_positions as $position)
                <tr m-id="{{ $position->template->id }}" m-exp="{{ $position->expiration_date?->format('Y-m-d') }}">
                    <td>{{ $position->template->name }}</td>
                    <td>{{ $position->template->category->name }}</td>
                    <td @if ($position->amount < $position->template->minimum_amount) class="error" @endif>
                        {{ $position->amount }} {{ $position->template->unit }}
                    </td>
                    @if (!$position->expiration_date)
                    <td class="ghost">
                    @elseif ($position->expiration_date?->lte(now()))
                    <td class="error">
                    @else
                    <td>
                    @endif
                        {{ $position->expiration_date?->diffForHumans() ?? "brak" }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="grayed-out" colspan=4>
                        Pusto!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <script>
        $(document).ready(function(){
            $("tr").click(function(){
                const [id, exp] = [$(this).attr("m-id"), $(this).attr("m-exp")];
                $("#ingredient_template_id").val(id);
                $("#expiration_date").val(exp);
                $("#amount").focus();
            });
        });
        </script>
    </section>
    @endforeach
</div>

<section>
    <form action="{{ route("ingredient-add") }}" method="post">
        @csrf
        <h2>Dodaj składnik</h2>
        <div class="flex-right">
            <x-select name="ingredient_template_id" label="Składnik" :options="$templates" />
            <x-input type="number" name="amount" label="Ilość" placeholder="" step="0.01" />
            <x-input type="date" name="expiration_date" label="Termin ważności" />
            <script>
            function ingredient_unit(){
                const ing_id = $("#ingredient_template_id").val();
                $.ajax({
                    type: "get",
                    url: "{{ route('ajax_ingredient_unit') }}",
                    data: {
                        ing_id: ing_id,
                        csrf_token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        $("#amount").attr("placeholder", res);
                    }
                });
            }
            $(document).ready(function(){
                ingredient_unit();
                $("#ingredient_template_id").change(function(){ ingredient_unit(); });
            });
            </script>
        </div>
        <x-button action="submit" icon="plus" label="Dodaj" />
    </form>
    <x-a href="{{ route('ingredient-templates') }}">Lista wzorców</x-a>
</section>

<div class="grid-2">
    <section>
        <div class="section-header">
            <h1>
                <i class="fa-solid fa-clock-rotate-left"></i>
                Ostatnie zmiany
            </h1>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Składnik</th>
                    <th>Zmiana</th>
                </tr>
            </thead>
            <tbody>
            @php $date = "" @endphp
            @foreach ($changes as $change)
                @if($date != $change->date)
                    @php $date = $change->date @endphp
                <tr>
                    <td colspan=2 class="ghost">
                        {{ $change->date->format("d.m") }} – {{ $change->date->diffForHumans() }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td>{{ $change->template->name }}</td>
                    <td class="{{ $change->amount > 0 ? 'success' : 'error' }}">{{ sprintf("%+d", $change->amount) }} {{ $change->template->unit }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
</div>

@endsection
