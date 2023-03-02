<nav>
@if (Route::currentRouteName() == "home")
    @guest
    <a href="{{ route("login") }}" class="auth-link"><li><i class="fa-solid fa-circle-user"></i> Zaloguj się</li></a>
    @endguest
    @auth
    <a href="{{ route("dashboard") }}" class="auth-link"><li><i class="fa-solid fa-user"></i> Moje konto</li></a>
    @endauth
@else

    @auth
    <a href="{{ route("dashboard") }}"><li><i class="fa-solid fa-house-chimney-user"></i> Pulpit</li></a>
    <a href="{{ route("ingredients") }}"><li><i class="fa-solid fa-jar"></i> Składniki</li></a>
    <a href="{{ route("recipes") }}"><li><i class="fa-solid fa-scroll"></i> Przepisy</li></a>
    <a href="{{ route("settings") }}"><li><i class="fa-solid fa-cog"></i> Ustawienia</li></a>

    <a
        href="{{ route("logout") }}"
        class="auth-link"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        >
        <li><i class="fa-solid fa-power-off"></i> Wyloguj się</li>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
    @endauth
    @guest
    <a href="{{ route("login") }}" class="auth-link"><li><i class="fa-solid fa-circle-user"></i> Zaloguj się</li></a>
    @endguest
@endif
    <script>
    $('a[href="{{ URL::current() }}"]').addClass("active");
    </script>
</nav>
