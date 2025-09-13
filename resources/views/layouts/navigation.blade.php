<nav>
    <ul>
        <li><p><a href="/">Home</a></p></li>
        @auth
            <li><p><a href="/user/{{Auth::user()->id}}">My account</a></p></li>    
            <li><p><a href="/logout">Logout</a></p></li>
        @else
            <li><p><a href="/login">Login</a></p></li>    
        @endauth
    </ul>
</nav>