@extends('layouts.app', compact("title"))

@section('content')

<section>
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-cog"></i>
            Ustawienia
        </h1>
    </div>

    {{-- TODO USTAWIACZ SETTINGSÓW --}}
    <form action="" method="post">
        @csrf
        <table id="recipe-table">
            <thead>
                <tr>
                    <th>Nazwa</th>
                    <th>Wartość</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $name => $value)
                <tr>
                    <td>{{ $name }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>

</section>

@endsection
