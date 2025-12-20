<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\TicketReplyMail;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\TicketReply;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $tickets = Ticket::with('department')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.tickets.index', compact('tickets'));
    }

    public function create(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $departments = TicketDepartment::pluck('name', 'id');

        return view('client.tickets.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:ticket_departments,id'],
            'subject'       => ['required', 'string', 'max:255'],
            'message'       => ['required', 'string'],
            'priority'      => ['nullable', 'in:low,medium,high,urgent'],
        ]);

        $user = Auth::user();

        $ticket = Ticket::create([
            'user_id'      => $user->id,                 // khách
            'department_id'=> $data['department_id'],
            'subject'      => $data['subject'],
            'status'       => 'open',
            'priority'     => $data['priority'] ?? 'medium',
            'created_by'   => $user->id,                 // người tạo
            'assigned_to'  => null,
        ]);

        // tạo reply đầu tiên (nội dung khách gửi)
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'is_staff'  => false,
            'message'   => $data['message'],
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('status', 'Ticket đã được tạo thành công.');
    }

    public function show(Ticket $ticket): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $this->authorizeTicket($ticket);

        $ticket->load(['department', 'replies.user']);

        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorizeTicket($ticket);

        $validated = $request->validate([
            'message' => ['required', 'string'],
        ]);

        // 👉 LƯU reply và GIỮ lại biến $reply
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'is_staff'  => false,
            'message'   => $validated['message'],
        ]);

        // set trạng thái ticket
        $ticket->update(['status' => 'customer-reply']);

        // gửi mail cho staff / department
        $to = [];

        if ($ticket->assignee?->email) {
            $to[] = $ticket->assignee->email;
        }

        if ($ticket->department?->email) {
            $to[] = $ticket->department->email;
        }

        if (empty($to) && setting('support_email')) {
            $to[] = setting('support_email');
        }

        if (!empty($to)) {
            Mail::to($to)->send(
                new TicketReplyMail(
                    $ticket->fresh(['user', 'department', 'assignee']),
                    $reply,
                    false // gửi cho staff
                )
            );
        }

        return back()->with('status', 'Đã gửi trả lời.');
    }

    protected function authorizeTicket(Ticket $ticket): void
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
