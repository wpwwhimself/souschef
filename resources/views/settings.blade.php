@extends('layouts.app', compact("title"))

@section('content')

<section>
    <div class="section-header">
        <h1>
            <i class="fa-solid fa-cog"></i>
            Ustawienia
        </h1>
    </div>

    <div class="grid-2">
    @foreach ($settings as $setting)
        <span>{{ $setting->desc }}</span>
        <x-input type="text" :small="true"
            name="{{ $setting->name }}" label="{{ $setting->name }}"
            value="{{ $setting->value }}"
            />
    @endforeach
    </div>
    <script>
    $(document).ready(function(){
        $("input").change(function(){
            $.ajax({
                type: "post",
                url: "{{ route('ajax_settings') }}",
                data: {
                    name: $(this).attr("name"),
                    value: $(this).val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    $(`label[for=${res}]`).addClass("success");
                }
            });
        });
    });
    </script>
</section>

@endsection
