<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $todayAttendances = Attendance::whereDate('date', now()->toDateString())->count();
        $presentToday = Attendance::whereDate('date', now()->toDateString())->whereNotNull('check_in')->count();
        $lateToday = Attendance::whereDate('date', now()->toDateString())->where('check_in', '>', '08:00:00')->count();
        $recentAttendances = Attendance::with('employee')->whereDate('date', now()->toDateString())->latest()->take(5)->get();

        return view('dashboard', compact('totalEmployees', 'todayAttendances', 'presentToday', 'lateToday', 'recentAttendances'));
    }
}
