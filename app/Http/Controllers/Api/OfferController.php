<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OfferController extends Controller
{
    public function store(Request $request, $taskId)
    {
        try {
            $task = Task::findOrFail($taskId);

            if ($task->creator_id === Auth::id()) {
                return response()->json(['message' => 'Gak bisa bantu tugas sendiri bro'], 403);
            }

            if (!in_array($task->status, ['open', 'negotiation'])) {
                return response()->json(['message' => 'Task ini udah gak bisa ditawar bro'], 400);
            }

            $request->validate([
                'conditions' => 'nullable|array|max:3',
                'extra_fee' => 'nullable|numeric|min:0',
            ]);

            $offer = Offer::create([
                'task_id' => $taskId,
                'helper_id' => Auth::id(),
                'conditions' => $request->conditions,
                'extra_fee' => $request->extra_fee ?? 0,
                'status' => 'pending'
            ]);

            $task->update(['status' => 'negotiation']);

            return response()->json(['message' => 'Penawaran dikirim', 'data' => $offer], 201);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('OfferController@store: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function accept($id)
    {
        try {
            $offer = Offer::with('task')->findOrFail($id);

            if ($offer->task->creator_id !== Auth::id()) {
                return response()->json(['message' => 'Bukan tugas lu nih'], 403);
            }

            if ($offer->status !== 'pending') {
                return response()->json(['message' => 'Tawaran ini udah gak pending bro'], 400);
            }

            $offer->update(['status' => 'accepted']);
            $offer->task->update(['status' => 'accepted']);
            
            Offer::where('task_id', $offer->task_id)
                ->where('id', '!=', $id)
                ->update(['status' => 'rejected']);

            return response()->json(['message' => 'Penawaran diterima! Siap-siap dapet bantuan']);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('OfferController@accept: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }
}
