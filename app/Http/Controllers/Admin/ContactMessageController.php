<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.contact.index', [
            'messages' => ContactMessage::latest()->paginate(20),
        ]);
    }

    public function markResolved(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['status' => 'resolved']);
        AuditLog::record('contact.resolved', $contactMessage);

        return back()->with('status', 'Marked as resolved.');
    }

    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
        }

        return back();
    }
}
