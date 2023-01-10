@extends('layouts.app')

@section('content')
<form action="{{ route('register') }}" method="post">
    @csrf
    <x-input type="text" name="name" label="Kto gotuje?" />
    <x-input type="password" name="password" label="Hasło" />
    <x-input type="password" name="password_confirmation" label="Potwierdź hasło" />
    <x-button action="submit" icon="check" label="Nowe konto" />
</form>
{{ $errors }}
@endsection