<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NoteController extends Controller
{
    /**
     * Ambil semua notes untuk admin yang login
     */
    public function index()
    {
        $userId = Session::get('loginId');
        $notes = Note::where('created_by', $userId)
            ->orderBy('note_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    /**
     * Simpan note baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'note_date' => 'required|date'
        ]);

        $userId = Session::get('loginId');

        $note = Note::create([
            'content' => $request->input('content'),
            'note_date' => $request->input('note_date'),
            'created_by' => $userId
        ]);

        // Log activity
        logActivity(
            'create',
            'Catatan Baru Ditambahkan',
            "Catatan untuk tanggal {$request->input('note_date')} berhasil ditambahkan",
            $userId
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan!',
            'data' => $note
        ]);
    }

    /**
     * Update note
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'note_date' => 'required|date'
        ]);

        $userId = Session::get('loginId');
        $note = Note::where('id', $id)
            ->where('created_by', $userId)
            ->first();

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan!'
            ], 404);
        }

        $note->update([
            'content' => $request->input('content'),
            'note_date' => $request->input('note_date')
        ]);

        // Log activity
        logActivity(
            'update',
            'Catatan Diperbarui',
            "Catatan untuk tanggal {$request->input('note_date')} berhasil diperbarui",
            $userId
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui!',
            'data' => $note
        ]);
    }

    /**
     * Hapus note
     */
    public function destroy($id)
    {
        $userId = Session::get('loginId');
        $note = Note::where('id', $id)
            ->where('created_by', $userId)
            ->first();

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan!'
            ], 404);
        }

        $noteDate = $note->note_date;
        $note->delete();

        // Log activity
        logActivity(
            'delete',
            'Catatan Dihapus',
            "Catatan untuk tanggal {$noteDate} berhasil dihapus",
            $userId
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil dihapus!'
        ]);
    }
}
