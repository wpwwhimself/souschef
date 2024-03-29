@extends('layouts.app', compact("title"))

@section('content')

<section>
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-scroll"></i>
            Lista przepisów
            @if ($ingredient)
            zawierających {{ $ingredient->name }}
            @endif
        </h1>
    </div>

    <div class="grid-2">
        @forelse ($recipes as $recipe)
        <a href="{{ route('recipe-view', ['id' => $recipe->id]) }}"
            class="section-like recipe
            @unless($recipe->canBeCooked()) ghost @endunless
            "
            >
            <h3>
                <span>
                    {{ $recipe->name }}
                    @if ($recipe->for_dinner) <i class="fa-solid fa-sun accent" @popper(na obiad)></i> @endif
                    @if ($recipe->for_supper) <i class="fa-solid fa-moon accent" @popper(na kolację)></i> @endif
                </span>
                <span>
                    @if ($recents->contains($recipe->withoutRelations())) <i class="fa-solid fa-clock-rotate-left success" @popper(niedawno gotowane)></i> @endif
                    @unless ($recipe->ingredientsSufficient()) <i class="fa-solid fa-basket-shopping error" @popper(brakuje składników)></i> @endunless
                    @unless (count($recipe->ingredients)) <i class="fa-solid fa-triangle-exclamation error" @popper(pusty przepis)></i> @endunless
                </span>
            </h3>
            {{ Illuminate\Mail\Markdown::parse($recipe->desc) }}
            <span class="ghost">{{ $recipe->totalMass() }} g</span>
        </a>
        @empty
        <p class="grayed-out">Nie mam żadnych przepisów</p>
        @endforelse
    </div>
</section>

<x-a href="{{ route('recipe-add') }}">Dodaj nowy przepis</x-a>

@endsection
