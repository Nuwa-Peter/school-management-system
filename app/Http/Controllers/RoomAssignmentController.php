<?php

namespace App\Http\Controllers;

use App\Models\RoomAssignment;
use App\Models\User;
use App\Models\DormitoryRoom;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoomAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $assignments = RoomAssignment::with(['student', 'room.dormitory'])
            ->when($request->dormitory_id, function ($query, $dormitory_id) {
                $query->whereHas('room.dormitory', function ($q) use ($dormitory_id) {
                    $q->where('id', $dormitory_id);
                });
            })
            ->get();

        $dormitories = \App\Models\Dormitory::all();

        return view('hostels.assignments.index', compact('assignments', 'dormitories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $students = User::where('role', 'student')->whereDoesntHave('roomAssignments')->orderBy('last_name')->get();
        $rooms = DormitoryRoom::with('dormitory')->get();

        return view('hostels.assignments.create', compact('students', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:room_assignments,user_id,NULL,id,academic_year,' . $request->academic_year,
            'dormitory_room_id' => 'required|exists:dormitory_rooms,id',
            'academic_year' => 'required|string|max:255',
        ]);

        // Check if room is full
        $room = DormitoryRoom::find($request->dormitory_room_id);
        if ($room->assignments()->where('academic_year', $request->academic_year)->count() >= $room->capacity) {
            return back()->with('error', 'This room is already at full capacity for the selected academic year.');
        }

        RoomAssignment::create($request->all());

        return redirect()->route('room-assignments.index')->with('success', 'Student assigned to room successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomAssignment $roomAssignment): RedirectResponse
    {
        $roomAssignment->delete();
        return redirect()->route('room-assignments.index')->with('success', 'Student assignment deleted successfully.');
    }
}
