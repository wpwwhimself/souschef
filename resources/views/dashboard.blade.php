@extends('layouts.app', compact("title"))

@section('content')
Witaj, {{ Auth::user()->name }}
@endsection