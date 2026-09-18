<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tribute.css') }}">
</head>
<body>
<div class="login-shell">
  <div class="login-card">
    <div class="section-label">Tribute Admin</div>
    <h2 class="section-title" style="font-size:26px; margin-bottom:24px;">Sign in</h2>
    @if($errors->any())<div class="error-banner">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login') }}" class="admin-form">
      @csrf
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
      <label style="display:flex; gap:8px; align-items:center; font-size:14px;">
        <input type="checkbox" name="remember" style="width:auto;"> Remember me
      </label>
      <button type="submit" class="submit-btn" style="margin-top:22px; width:100%;">Sign in</button>
    </form>
  </div>
</div>
</body>
</html>
