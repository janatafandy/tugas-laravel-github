<!DOCTYPE html>
<html>
<head>
    <title>Rekap Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">📍 Presensi QR</a>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Dashboard</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h4>📊 Rekap Presensi</h4>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-4">
                <label>Karyawan</label>
                <select name="employee_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $att->employee->name }}</td>
                    <td>{{ $att->date }}</td>
                    <td>{{ $att->check_in ?? '-' }}</td>
                    <td>{{ $att->check_out ?? '-' }}</td>
                    <td><span class="badge bg-success">{{ ucfirst($att->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
