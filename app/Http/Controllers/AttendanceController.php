<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceController extends Controller
{
    public function generateQR($userId)
    {
        $user = User::findOrFail($userId);
        $today = Attendance::where('user_id', $userId)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($today && $today->check_in && $today->check_out) {
            return redirect()->back()->with('error', 'Anda sudah absen hari ini!');
        }

        if ($today && $today->check_in && !$today->check_out) {
            return redirect()->route('qr.checkout', $today->id);
        }

        $token = uniqid() . time() . rand(100, 999);

        $attendance = Attendance::create([
            'user_id' => $userId,
            'date' => now()->toDateString(),
            'token' => $token,
            'status' => 'hadir'
        ]);

        $qr = QrCode::size(300)->generate($token);

        return view('qr-scan', compact('qr', 'user', 'attendance'));
    }

    public function scanQR(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'user_id' => 'required'
        ]);

        $attendance = Attendance::where('token', $request->token)
            ->where('user_id', $request->user_id)
            ->whereNull('check_in')
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'Token tidak valid!'], 400);
        }

        if ((time() - strtotime($attendance->created_at)) > 30) {
            return response()->json(['error' => 'QR Code sudah expired! Maksimal 30 detik'], 400);
        }

        $attendance->check_in = now()->toTimeString();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil!',
            'data' => $attendance
        ]);
    }

    public function checkOut($id)
    {
        $attendance = Attendance::findOrFail($id);

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah check-out!');
        }

        $attendance->check_out = now()->toTimeString();
        $attendance->save();

        return redirect()->route('dashboard')->with('success', 'Check-out berhasil!');
    }

    public function rekap()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $attendances = Attendance::with('user')->orderBy('date', 'desc')->get();
        } else {
            $attendances = Attendance::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        return view('rekap', compact('attendances'));
    }
}
