@vite(['resources/css/app.css','resources/css/nav.css'])
<nav>
    <div class="direct">
        <div class="nav-content">
            <a href="/courses" class="nav-link">Courses</a>
        </div>
        <div class="nav-content">
            <a href="/group-reports" class="nav-link">Groups</a>
        </div>
        <div class="nav-content">
            <a href="/assignments" class="nav-link">Assignments</a>
        </div>
    </div>
    <div class="logout">
        <div class="nav-content">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <!-- Hidden logout form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
            </form>
        </div>
    </div>
</nav>