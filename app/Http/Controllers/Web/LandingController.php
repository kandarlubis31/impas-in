<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
        // User yang sudah login langsung diarahkan ke dashboard
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        try {
            $stats = [
                'members'         => User::count(),
                'tasks_total'     => Task::count(),
                'tasks_completed' => Task::where('status', 'completed')->count(),
                'tasks_active'    => Task::whereIn('status', ['open', 'negotiation'])->count(),
                'offers_count'    => Offer::count(),
                'transactions'    => Transaction::count(),
                'points_total'    => User::sum('balance'),
            ];

            // Kasus bantuan yang masih butuh dibantu (open / negotiation)
            $activeCases = Task::with('creator')
                ->withCount('offers')
                ->whereIn('status', ['open', 'negotiation'])
                ->latest()
                ->take(6)
                ->get();

            // Log kebaikan: transaksi terakhir (social proof real-time)
            $goodDeeds = Transaction::with(['sender', 'receiver'])
                ->latest()
                ->take(5)
                ->get();
        } catch (\Throwable $e) {
            // DB belum migrate / koneksi gagal -> landing page tetap tampil
            Log::error('LandingController@index: '.$e->getMessage());
            $stats = ['members' => 0, 'tasks_total' => 0, 'tasks_completed' => 0,
                      'tasks_active' => 0, 'offers_count' => 0,
                      'transactions' => 0, 'points_total' => 0];
            $activeCases = collect();
            $goodDeeds = collect();
        }

        return view('welcome', [
            'stats'       => $stats,
            'activeCases' => $activeCases,
            'goodDeeds'   => $goodDeeds,
        ]);
    }
}
