@extends('layouts.app', compact("title"))

@section('content')
<h1>Witaj, {{ Auth::user()->name }}</h1>

@if ($shopping_list)
<section>
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-basket-shopping"></i>
            Co trzeba dokupić?
        </h1>
    </div>
    <ul>
        @foreach ($shopping_list as $item)
        <li>{{ $item->template->name }}</li>
        @endforeach
    </ul>
</section>
@endif
@endsection
