<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Offer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            $tasks = Task::with('creator')
                ->where('creator_id', '!=', $user->id)
                ->where('status', 'open')
                ->latest()
                ->paginate(20, ['*'], 'feed_page');

            $myTasks = Task::with('offers.helper', 'acceptedOffer.helper')
                ->where('creator_id', $user->id)
                ->latest()
                ->paginate(20, ['*'], 'mytasks_page');

            $transactions = Transaction::with(['sender', 'receiver'])
                ->where(function ($q) use ($user) {
                    $q->where('from_user', $user->id)
                      ->orWhere('to_user', $user->id);
                })
                ->latest()
                ->limit(10)
                ->get();
            
            return view('dashboard', compact('user', 'tasks', 'myTasks', 'transactions'));
        } catch (\Throwable $e) {
            Log::error('DashboardController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.');
        }
    }

    public function storeTask(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'effort_level' => 'required|integer|between:1,5',
                'base_reward' => 'required|integer|min:0',
            ]);

            Task::create([
                'creator_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'effort_level' => $request->effort_level,
                'base_reward' => $request->base_reward,
                'status' => 'open'
            ]);

            return back()->with('success', 'Tugas berhasil dipublish!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('DashboardController@storeTask: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.')->withInput();
        }
    }

    public function sendOffer(Request $request, $taskId)
    {
        try {
            $task = Task::findOrFail($taskId);

            if ($task->creator_id === Auth::id()) {
                return back()->with('error', 'Gak bisa bantu tugas sendiri bro.');
            }

            if (!in_array($task->status, ['open', 'negotiation'])) {
                return back()->with('error', 'Task ini sudah tidak bisa ditawar.');
            }

            $request->validate([
                'condition' => 'required|string|max:255',
                'extra_fee' => 'nullable|numeric|min:0',
            ]);

            Offer::create([
                'task_id' => $taskId,
                'helper_id' => Auth::id(),
                'conditions' => [$request->condition],
                'extra_fee' => $request->extra_fee ?? 0,
                'status' => 'pending'
            ]);

            $task->update(['status' => 'negotiation']);

            return back()->with('success', 'Tawaran bantuan dikirim!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('DashboardController@sendOffer: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.')->withInput();
        }
    }

    public function acceptOffer($offerId)
    {
        try {
            $offer = Offer::findOrFail($offerId);
            $task = Task::findOrFail($offer->task_id);

            if ($task->creator_id !== Auth::id()) return back();

            if ($offer->status !== 'pending') {
                return back()->with('error', 'Tawaran ini sudah tidak pending.');
            }

            $offer->update(['status' => 'accepted']);
            $task->update(['status' => 'accepted']);

            Offer::where('task_id', $task->id)->where('id', '!=', $offerId)->update(['status' => 'rejected']);

            return back()->with('success', 'Tawaran diterima!');
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('DashboardController@acceptOffer: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.');
        }
    }

    public function completeTask($taskId)
    {
        try {
            $task = Task::with('acceptedOffer.helper')->findOrFail($taskId);

            if ($task->creator_id !== Auth::id() || $task->status !== 'accepted') return back();

            $points = $task->base_reward;
            if (Auth::user()->balance < $points) {
                return back()->with('error', 'Saldo tidak cukup untuk membayar reward.');
            }

            if (!$task->acceptedOffer?->helper) {
                return back()->with('error', 'Helper tidak ditemukan untuk task ini.');
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

            return back()->with('success', 'Hutang budi lunas!');
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('DashboardController@completeTask: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.');
        }
    }

    public function cancelTask($taskId)
    {
        try {
            $task = Task::findOrFail($taskId);

            if ($task->creator_id !== Auth::id()) {
                return back()->with('error', 'Cuma owner yang bisa cancel task.');
            }

            if (!in_array($task->status, ['open', 'negotiation'])) {
                return back()->with('error', 'Task ini sudah tidak bisa dicancel.');
            }

            DB::transaction(function () use ($task) {
                $task->update(['status' => 'cancelled']);

                Offer::where('task_id', $task->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);
            });

            return back()->with('success', 'Tugas berhasil dicancel! Semua tawaran ditolak.');
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('DashboardController@cancelTask: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.');
        }
    }
}
