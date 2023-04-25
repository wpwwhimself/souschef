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
            <li>
                {{ $item->template->name }} – {{ $item->expiration_date->diffForHumans() }}
                <a href="{{ route('recipes', ['ingredient_template_id' => $item->template->id]) }}">
                    <i class="fa-solid fa-scroll" @popper(Pokaż przepisy z tym składnikiem)></i>
                </a>
            </li>
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
        <div class="grid-2">
            @foreach ($recipe_suggestions as $time => $sug)
            <a href="{{ $sug ? route('recipe-view', ['id' => $sug->id]) : route('recipes') }}">
                @if ($time == "dinner")
                <i class="fa-solid fa-sun accent" @popper(na obiad)></i>
                @else
                <i class="fa-solid fa-moon accent" @popper(na kolację)></i>
                @endif
                {!! $sug->name ?? '<span class="grayed-out">Brak propozycji</span>' !!}
            </a>   
            @endforeach
        </div>
    </section>
</div>

@endsection
