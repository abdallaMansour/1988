<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('support-tickets.view')) {
            return abort(403, 'ليس لديك صلاحية لعرض التذاكر');
        }

        $query = SupportTicket::with('user')->latest();

        if ($request->user('admin')) {
            // Admin sees all tickets
        } else {
            // User sees only their tickets
            $query->where('user_id', $request->user()->id);
        }

        $tickets = $query->paginate(15);

        return view('dashboard.support-tickets.index', compact('tickets'));
    }

    public function create()
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('support-tickets.manage')) {
            return abort(403, 'ليس لديك صلاحية لإنشاء تذكرة');
        }

        return view('dashboard.support-tickets.create');
    }

    public function store(Request $request)
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('support-tickets.manage')) {
            return abort(403, 'ليس لديك صلاحية لإنشاء تذكرة');
        }

        $rules = [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];

        if ($request->user('admin')) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        $userId = $request->user('admin')
            ? $validated['user_id']
            : $request->user()->id;

        $ticket = SupportTicket::create([
            'user_id' => $userId,
            'subject' => $validated['subject'],
            'status' => 'open',
        ]);

        SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $userId,
            'admin_id' => null,
            'message' => $validated['message'],
        ]);

        return redirect()->route('dashboard.support-tickets.show', $ticket)
            ->with('success', __('تم إنشاء التذكرة بنجاح.'));
    }

    public function show(Request $request, SupportTicket $supportTicket)
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('support-tickets.view')) {
            return abort(403, 'ليس لديك صلاحية لعرض التذكرة');
        }

        $this->authorizeView($request, $supportTicket);

        $supportTicket->load(['replies.user', 'replies.admin', 'user']);

        return view('dashboard.support-tickets.show', compact('supportTicket'));
    }

    public function reply(Request $request, SupportTicket $supportTicket)
    {
        if (auth('admin')->check() && !auth('admin')->user()->hasPermission('support-tickets.manage')) {
            return abort(403, 'ليس لديك صلاحية لإرسال الرد');
        }

        $this->authorizeView($request, $supportTicket);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($request->user('admin')) {
            SupportTicketReply::create([
                'support_ticket_id' => $supportTicket->id,
                'user_id' => null,
                'admin_id' => $request->user('admin')->id,
                'message' => $validated['message'],
            ]);
            // Optionally update status when admin replies
            if ($supportTicket->status === 'open') {
                $supportTicket->update(['status' => 'in_progress']);
            }
        } else {
            SupportTicketReply::create([
                'support_ticket_id' => $supportTicket->id,
                'user_id' => $request->user()->id,
                'admin_id' => null,
                'message' => $validated['message'],
            ]);
        }

        return redirect()->route('dashboard.support-tickets.show', $supportTicket)
            ->with('success', __('تم إرسال الرد بنجاح.'));
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        if (! $request->user('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:open,in_progress,resolved,closed'],
        ]);

        $supportTicket->update(['status' => $validated['status']]);

        return redirect()->route('dashboard.support-tickets.show', $supportTicket)
            ->with('success', __('تم تحديث الحالة بنجاح.'));
    }

    private function authorizeView(Request $request, SupportTicket $supportTicket): void
    {
        if ($request->user('admin')) {
            return;
        }
        if ($supportTicket->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
