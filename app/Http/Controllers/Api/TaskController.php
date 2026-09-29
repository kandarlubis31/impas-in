<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Task::with('creator')->where('status', 'open');

            if ($request->has('min_effort')) {
                $query->where('effort_level', '>=', (int) $request->min_effort);
            }

            if ($request->has('max_effort')) {
                $query->where('effort_level', '<=', (int) $request->max_effort);
            }

            $tasks = $query->latest()->paginate(20);

            return response()->json([
                'data' => $tasks->items(),
                'meta' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('TaskController@index: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $task = Task::with(['creator', 'offers.helper', 'acceptedOffer.helper'])->findOrFail($id);
            return response()->json(['data' => $task]);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('TaskController@show: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'effort_level' => 'required|integer|between:1,5',
                'base_reward' => 'required|integer|min:0',
            ]);

            $task = Task::create(array_merge($validated, [
                'creator_id' => Auth::id(),
                'status' => 'open'
            ]));

            return response()->json(['message' => 'Tugas berhasil dibuat', 'data' => $task], 201);
        } catch (\Throwable $e) {
            Log::error('TaskController@store: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function complete($id)
    {
        try {
            $task = Task::with('acceptedOffer.helper')->findOrFail($id);

            if ($task->creator_id !== Auth::id()) {
                return response()->json(['message' => 'Cuma owner yang bisa selesaiin'], 403);
            }

            if ($task->status !== 'accepted') {
                return response()->json(['message' => 'Belum ada helper yang diterima'], 400);
            }

            $points = $task->base_reward;
            if (Auth::user()->balance < $points) {
                return response()->json(['message' => 'Saldo gak cukup buat bayar reward'], 400);
            }

            if (!$task->acceptedOffer?->helper) {
                return response()->json(['message' => 'Helper tidak ditemukan untuk task ini'], 400);
            }

            DB::transaction(function () use ($task) {
                $task->update(['status' => 'completed']);
                
                $helper = $task->acceptedOffer->helper;
                $points = $task->base_reward;

                Auth::user()->decrement('balance', $points);
                $helper->increment('balance', $points);
                $helper->increment('reputation_score', Task::reputationForEffort($task->effort_level));

                Transaction::create([
                    'from_user' => Auth::id(),
                    'to_user' => $helper->id,
                    'points' => $points,
                    'type' => 'help'
                ]);
            });

            return response()->json(['message' => 'Hutang budi lunas! Saldo & reputasi update.']);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('TaskController@complete: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function cancel($id)
    {
        try {
            $task = Task::findOrFail($id);

            if ($task->creator_id !== Auth::id()) {
                return response()->json(['message' => 'Cuma owner yang bisa cancel task'], 403);
            }

            if (!in_array($task->status, ['open', 'negotiation'])) {
                return response()->json(['message' => 'Task ini udah gak bisa dicancel'], 400);
            }

            DB::transaction(function () use ($task) {
                $task->update(['status' => 'cancelled']);

                Offer::where('task_id', $task->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
            });

            return response()->json(['message' => 'Task berhasil dicancel! Semua tawaran ditolak.']);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('TaskController@cancel: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }
}
