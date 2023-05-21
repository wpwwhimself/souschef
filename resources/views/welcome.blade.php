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
    <x-input type="checkbox" name="remember_box" label="Zapamiętaj mnie" />
    <input type="hidden" name="remember" id="remember" value="0" />
    @if ($errors->any())
    <span class="error">
    @foreach ($errors->all() as $error)
        {{ $error }}
    @endforeach
    </span>
    @endif
    <x-button action="submit" icon="forward" label="Potwierdź" />
</form>
<script defer>
document.getElementById("remember_box").addEventListener("change", (event) => {
    document.getElementById("remember").value = event.target.checked;
});
</script>
@endsection
