<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Admin: show all rooms.
     */
    public function index()
    {
        $rooms = Room::withCount(['users as player_count' => fn ($q) => $q->where('role', 'player')])
                     ->with('creator')
                     ->latest()
                     ->paginate(20);

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Admin: create a new room.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:80'],
            'school_name' => ['required', 'string', 'max:120'],
            'expires_at'  => ['nullable', 'date', 'after:now'],
        ]);

        Room::create([
            'name'        => $data['name'],
            'school_name' => $data['school_name'],
            'code'        => Room::generateCode(),
            'expires_at'  => $data['expires_at'] ?? null,
            'created_by'  => Auth::id(),
        ]);

        return back()->with('success', 'สร้างห้องเรียบร้อยแล้ว!');
    }

    /**
     * Admin: delete a room (players will have room_id set to null via set null).
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('success', 'ลบห้องเรียบร้อยแล้ว (ผู้เล่นในห้องนี้ยังคงอยู่ในระบบ)');
    }
}
