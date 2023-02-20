@extends('layouts.app')

@section('content')
<form action="{{ route('login') }}" method="post" class="login-box">
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
