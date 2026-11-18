<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Presensi QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">📍 Presensi QR</a>
            <div class="ms-auto">
                <span class="text-white me-3">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5>Total Karyawan</h5>
                        <h2>{{ $totalEmployees }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5>Hadir Hari Ini</h5>
                        <h2>{{ $presentToday }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5>Terlambat</h5>
                        <h2>{{ $lateToday }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5>Total Absen</h5>
                        <h2>{{ $todayAttendances }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Presensi Hari Ini</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Nama</th><th>Masuk</th><th>Keluar</th></tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $att)
                                <tr>
                                    <td>{{ $att->user->name ?? 'User Tidak Ditemukan'}}</td>
                                    <td>{{ $att->check_in ?? '-' }}</td>
                                    <td>{{ $att->check_out ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Aksi Cepat</div>
                    <div class="card-body">
                        <a href="{{ route('qr.generate', Auth::id()) }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-qrcode"></i> Generate QR Code
                        </a>
                        <a href="{{ route('rekap') }}" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-list"></i> Lihat Rekap
                        </a>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('employees.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-users"></i> Kelola Karyawan
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
