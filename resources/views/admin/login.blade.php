<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Superadmin login</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
  <div class="login-wrap">
    <form class="card login-card" method="post" action="{{ route('admin.login.submit') }}">
      @csrf
      <h1>Poombarai superadmin</h1>
      <p>Manage stays, cars, packages, photos and the public site.</p>
      @if($errors->any())
        <div class="errors">{{ $errors->first() }}</div>
      @endif
      <div class="grid">
        <div>
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username">
        </div>
        <div>
          <label for="password">Password</label>
          <input id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <label class="checks"><input type="checkbox" name="remember" value="1"> Keep me signed in on this browser</label>
        <button class="btn" type="submit">Log in</button>
      </div>
    </form>
  </div>
</body>
</html>
