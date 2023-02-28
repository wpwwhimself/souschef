@extends('layouts.app', compact("title"))

@section('content')

<section>
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-scroll"></i>
            Lista przepisów
        </h1>
    </div>

    <div class="grid-2">
        @forelse ($recipes as $recipe)
        <a href="{{ route('recipe-view', ['id' => $recipe->id]) }}" class="section-like">
            <h2>
                {{ $recipe->name }}
                @if ($recipe->for_dinner) <i class="fa-solid fa-sun" @popper(na obiad)></i> @endif
                @if ($recipe->for_supper) <i class="fa-solid fa-moon" @popper(na kolację)></i> @endif
            </h2>
            {{ Illuminate\Mail\Markdown::parse($recipe->desc) }}
        </a>
        @empty
        <p class="grayed-out">Nie mam żadnych przepisów</p>
        @endforelse
    </div>
</section>

<x-a href="{{ route('recipe-add') }}">Dodaj nowy przepis</x-a>

@endsection