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
                <tr>
                    <td>{{ $position->ingredient->name }}</td>
                    <td>{{ $position->ingredient->category->name }}</td>
                    <td>{{ $position->amount }} {{ $position->ingredient->unit }}</td>
                    @if (!$position->expiration_date)
                    <td class="ghost">
                    @elseif ($position->expiration_date->lte(now()))
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
    </section>
    @endforeach
</div>

<section>
    <form action="{{ route("positions-add") }}" method="post">
        @csrf
        <h2>Dodaj składnik</h2>
        <div class="flex-right">
            <x-select name="ingredient_id" label="Składnik" :options="$ingredients" />
            <x-input type="number" name="amount" label="Ilość" />
            <x-input type="date" name="expiration_date" label="Termin ważności" />
        </div>
        <x-button action="submit" icon="plus" label="Dodaj" />
    </form>
</section>

@endsection
