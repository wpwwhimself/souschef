@extends('layouts.app')

@section('content')

<h1>Witaj w naszej kuchni</h1>
<p>
    {{
        collect([
            "Pomocnik kucharza gotowy do działania!",
            "Co dobrego upichcimy dzisiaj?",
            "Kuchnia jest do Twojej dyspozycji!",
        ])->random()
    }}
</p>

<form action="{{ route('login') }}" method="post" class="login-box">
    <h2>Zaloguj się</h2>
    @csrf
    <x-input type="text" name="name" label="Kto gotuje?" />
    <x-input type="password" name="password" label="Hasło" />
    @if ($errors->any())
    <span class="error">
    @foreach ($errors->all() as $error)
        {{ $error }}
    @endforeach
    </span>
    @endif
    <x-button action="submit" icon="forward" label="Potwierdź" />
</form>
@endsection
