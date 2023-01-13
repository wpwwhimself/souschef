@extends('layouts.app')

@section('content')
<form action="{{ route('login') }}" method="post">
    @csrf
    <x-input type="text" name="name" label="Kto gotuje?" />
    <x-input type="password" name="password" label="Hasło" />
    <x-button action="submit" icon="forward" label="Potwierdź" />
</form>
@endsection
