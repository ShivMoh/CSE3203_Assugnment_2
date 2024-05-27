@vite(['resources/css/app.css','resources/css/auth/login.css'])
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <form id="loginForm" method="POST" action="{{ route('login') }}">
        @csrf

        <h3>Login</h3>

        @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <label for="email">Email</label>
        <input type="email" placeholder="example@site.com" id="email" name="email">
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password">
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Log In</button>

        <div class="register">
            <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </form>
</body>