<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Notifications\ContactMessageReplyNotification;
use Illuminate\Http\Request;

class TechnicalSupportController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::with('user')->latest()->paginate(15);

        return view('dashboard.technical-support.index', compact('messages'));
    }

    public function mails()
    {
        $messages = ContactMessage::with('user')->latest()->paginate(15);

        return view('dashboard.mails.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->load('user', 'repliedBy');

        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('dashboard.technical-support.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:5000'],
        ]);

        $contactMessage->update([
            'admin_reply' => $validated['admin_reply'],
            'replied_at' => now(),
            'replied_by' => $request->user('admin')->id,
            'status' => 'replied',
        ]);

        if ($contactMessage->user) {
            $contactMessage->user->notify(new ContactMessageReplyNotification($contactMessage));
        } elseif ($contactMessage->email) {
            \Illuminate\Support\Facades\Notification::route('mail', $contactMessage->email)
                ->notify(new ContactMessageReplyNotification($contactMessage, $contactMessage->name));
        }

        return redirect()->route('dashboard.technical-support.show', $contactMessage)
            ->with('success', __('تم إرسال الرد بنجاح.'));
    }
}
