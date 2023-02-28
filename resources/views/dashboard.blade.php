@extends('layouts.app', compact("title"))

@section('content')
<h1>Witaj, {{ Auth::user()->name }}</h1>

<div class="grid-2">
    @if (count($shopping_list))
    <section>
        <div class="section-header">
            <h1>
                <i class="fa-solid fa-basket-shopping"></i>
                Co trzeba dokupić?
            </h1>
        </div>
        <ul>
            @foreach ($shopping_list as $item)
            <li>
                {{ $item->name }}
            </li>
            @endforeach
        </ul>
        <x-a href="{{ route('ingredients') }}">Przeglądaj składniki</x-a>
    </section>
    @endif

    @if (count($spoiled))
    <section>
        <div class="section-header">
            <h1>
                <i class="fa-solid fa-dumpster"></i>
                Do wyrzucenia
            </h1>
        </div>
        <ul>
            @foreach ($spoiled as $item)
            <li>{{ $item->template->name }} – {{ $item->expiration_date->diffForHumans() }}</li>
            @endforeach
        </ul>
        <x-a href="{{ route('ingredients') }}">Przeglądaj składniki</x-a>
    </section>
    @endif

    <section>
        <div class="section-header">
            <h1>
                <i class="fa-solid fa-lightbulb"></i>
                Propozycje dań
            </h1>
        </div>
        @foreach ($recipe_suggestions as $sug)
            {{ $sug->name }}
        @endforeach
    </section>
</div>

@endsection
