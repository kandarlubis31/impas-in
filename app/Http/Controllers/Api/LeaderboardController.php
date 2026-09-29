<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $topReputation = User::select('id', 'name', 'reputation_score')
                ->orderBy('reputation_score', 'desc')
                ->paginate(20, ['*'], 'reputation_page');

            $topBalance = User::select('id', 'name', 'balance')
                ->orderBy('balance', 'desc')
                ->paginate(20, ['*'], 'balance_page');

            return response()->json([
                'status' => 'success',
                'data' => [
                    'top_helpers' => $topReputation->items(),
                    'top_creditors' => $topBalance->items(),
                ],
                'meta' => [
                    'top_helpers' => [
                        'current_page' => $topReputation->currentPage(),
                        'last_page' => $topReputation->lastPage(),
                        'per_page' => $topReputation->perPage(),
                        'total' => $topReputation->total(),
                    ],
                    'top_creditors' => [
                        'current_page' => $topBalance->currentPage(),
                        'last_page' => $topBalance->lastPage(),
                        'per_page' => $topBalance->perPage(),
                        'total' => $topBalance->total(),
                    ],
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('LeaderboardController@index: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }
}