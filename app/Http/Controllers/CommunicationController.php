<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Mail\BulkMessageMail;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CommunicationController extends Controller
{
    public function create(): View
    {
        $streams = Stream::with('classLevel')->get();
        return view('communications.create', compact('streams'));
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'recipients' => ['required', 'string'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $recipients = User::query();

        switch ($request->recipients) {
            case 'all_teachers':
                $recipients->where('role', Role::TEACHER);
                break;
            case 'all_parents':
                $recipients->where('role', Role::PARENT);
                break;
            case 'all_students':
                $recipients->where('role', Role::STUDENT);
                break;
            default:
                // Assume it's a stream ID
                if (str_starts_with($request->recipients, 'stream_')) {
                    $streamId = substr($request->recipients, 7);
                    $recipients->whereHas('streams', fn($q) => $q->where('streams.id', $streamId));
                }
                break;
        }

        // Use BCC to send to all recipients without revealing email addresses
        Mail::bcc($recipients->pluck('email')->all())
            ->queue(new BulkMessageMail($request->subject, $request->message));

        return redirect()->route('communications.create')->with('success', 'Message has been queued for sending.');
    }
}
