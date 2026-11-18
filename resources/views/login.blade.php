<!DOCTYPE html>
<html>
<head>
    <title>Login - Presensi QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { width: 400px; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .login-box h3 { text-align: center; margin-bottom: 20px; color: #1a73e8; }
        .login-box .btn-primary { width: 100%; }
        .demo-info { font-size: 12px; color: #6c757d; text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h3>📍 Presensi QR</h3>
        <p class="text-center text-muted">PT EquityworldFutures</p>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="demo-info">
            <p>Demo: admin@equityworld.com / password</p>
        </div>
    </div>
</body>
</html>
