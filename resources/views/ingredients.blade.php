@extends('layouts.app', compact("title"))

@section('content')

@forelse ($ingredients as $ingredient)
    {{ $ingredient->name }}
@empty
    Kuchnia jest pusta!
@endforelse

<form action="{{ route("ingredients-add") }}" method="post">
    @csrf
    <h1>Dodaj składnik</h1>
</form>

@endsection