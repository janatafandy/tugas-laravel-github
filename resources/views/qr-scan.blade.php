<!DOCTYPE html>
<html>
<head>
    <title>Scan QR Code - Presensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        body { background: #f8f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .qr-box { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; max-width: 500px; width: 100%; margin: 20px; }
        #reader { width: 100%; margin: 20px 0; }
        #reader video { border-radius: 10px; }
        .qr-code { margin: 20px 0; }
        .timer { font-size: 14px; color: #6c757d; }
        .status { margin-top: 15px; }
    </style>
</head>
<body>
    <div class="qr-box">
        <h4>📸 Scan QR Code</h4>
        <p class="text-muted">Karyawan: <strong>{{ $user->name }}</strong></p>
        <p class="text-muted">Email: {{ $user->email }}</p>

        <!-- QR Code yang akan di-scan -->
        <div class="qr-code">
            <p>Scan QR Code ini menggunakan HP:</p>
            {!! $qr !!}
        </div>

        <p class="timer">⏱️ QR Code berlaku <strong>30 detik</strong></p>
        <p class="text-muted" id="timer"></p>

        <!-- Scanner untuk HP -->
        <div id="reader"></div>

        <div id="status" class="status"></div>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm mt-3">← Kembali ke Dashboard</a>
    </div>

    <script>
        const token = '{{ $attendance->token }}';
        const userId = '{{ $user->id }}';
        const statusElement = document.getElementById('status');
        const timerElement = document.getElementById('timer');

        // Timer 30 detik
        let remaining = 30;
        const interval = setInterval(() => {
            remaining--;
            timerElement.innerText = `⏳ Sisa waktu: ${remaining} detik`;

            if (remaining <= 0) {
                clearInterval(interval);
                timerElement.innerText = '⏰ QR Code sudah expired!';
                statusElement.innerHTML = `
                    <div class="alert alert-warning mt-3">
                        Waktu habis! Silakan generate ulang.
                        <br>
                        <a href="{{ route('qr.generate', $user->id) }}" class="btn btn-primary btn-sm mt-2">
                            🔄 Generate Ulang
                        </a>
                    </div>
                `;
                document.querySelector('.qr-code').style.opacity = '0.3';
            }
        }, 1000);

        // === QR CODE SCANNER UNTUK HP ===
        const html5QrCode = new Html5Qrcode("reader");

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Scan result: ${decodedText}`);

            if (decodedText === token) {
                statusElement.innerHTML = `
                    <div class="alert alert-success">✅ QR Code valid! Memproses...</div>
                `;

                fetch('{{ route("scan.qr") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        token: decodedText,
                        user_id: userId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        statusElement.innerHTML = `
                            <div class="alert alert-success">
                                ✅ ${data.message}<br>
                                Jam Masuk: <strong>${data.data.check_in}</strong>
                            </div>
                        `;
                        clearInterval(interval);
                        html5QrCode.stop().then(() => {
                            setTimeout(() => window.location.href = '{{ route("dashboard") }}', 3000);
                        });
                    } else {
                        statusElement.innerHTML = `
                            <div class="alert alert-danger">❌ ${data.error}</div>
                        `;
                    }
                })
                .catch(err => {
                    statusElement.innerHTML = `
                        <div class="alert alert-danger">❌ ${err.message}</div>
                    `;
                });
            } else {
                statusElement.innerHTML = `
                    <div class="alert alert-danger">❌ QR Code tidak valid!</div>
                `;
            }
        }

        function onScanFailure(error) {
            // ignore, keep scanning
        }

        // Mulai scanner otomatis
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.warn("Camera tidak ditemukan:", err);
            statusElement.innerHTML = `
                <div class="alert alert-warning mt-3">
                    ⚠️ Kamera tidak ditemukan. Pastikan HP mendukung.
                    <br>
                    <button class="btn btn-primary btn-sm mt-2" onclick="handleScan()">
                        📱 Gunakan Tombol Testing
                    </button>
                </div>
            `;
        });

        // Fallback: Tombol Testing (kalo kamera gak bisa dipake)
        function handleScan() {
            if (remaining <= 0) {
                alert('QR Code sudah expired! Generate ulang.');
                return;
            }

            fetch('{{ route("scan.qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token: token,
                    user_id: userId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    statusElement.innerHTML = `
                        <div class="alert alert-success mt-3">
                            ✅ ${data.message}<br>
                            Jam Masuk: <strong>${data.data.check_in}</strong>
                        </div>
                    `;
                    clearInterval(interval);
                    setTimeout(() => window.location.href = '{{ route("dashboard") }}', 3000);
                } else {
                    statusElement.innerHTML = `
                        <div class="alert alert-danger mt-3">❌ ${data.error}</div>
                    `;
                }
            })
            .catch(err => {
                statusElement.innerHTML = `
                    <div class="alert alert-danger mt-3">❌ ${err.message}</div>
                `;
            });
        }

        // Biar fungsi handleScan bisa dipanggil dari HTML
        window.handleScan = handleScan;
    </script>

    <!-- Tombol Testing (opsional) -->
    <div style="text-align: center; margin-top: 10px;">
        <button class="btn btn-secondary btn-sm" onclick="handleScan()">📱 Testing (Klik)</button>
        <p class="text-muted mt-1" style="font-size: 11px;">*Klik jika kamera tidak terdeteksi</p>
    </div>
</body>
</html>