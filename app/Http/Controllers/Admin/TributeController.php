<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TributeController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $tributes = Tribute::when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tributes.index', [
            'tributes' => $tributes,
            'status' => $status,
        ]);
    }

    /**
     * Export tributes to CSV. This is read-only: it never changes a
     * tribute's status, so approved (or any other) tributes remain
     * exactly where they are in the admin list afterwards.
     */
    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'tribute_ids' => ['nullable', 'array'],
            'tribute_ids.*' => ['integer'],
            'status' => ['nullable', 'string', 'in:pending,approved,rejected,all'],
        ]);

        $selectedIds = collect($validated['tribute_ids'] ?? [])->filter()->values();

        $tributes = Tribute::query()
            ->when($selectedIds->isNotEmpty(), fn ($q) => $q->whereIn('id', $selectedIds))
            ->when(
                $selectedIds->isEmpty() && ($validated['status'] ?? 'all') !== 'all',
                fn ($q) => $q->where('status', $validated['status'])
            )
            ->latest()
            ->get();

        $filename = 'tributes-export-'.now()->format('Y-m-d-His').'.csv';

        $callback = function () use ($tributes) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name', 'Email', 'Relationship', 'Title', 'Message',
                'Status', 'Featured', 'Publication Consent', 'Submitted At', 'Moderated At',
            ]);

            foreach ($tributes as $tribute) {
                fputcsv($handle, [
                    $tribute->name,
                    $tribute->email,
                    $tribute->relationship,
                    $tribute->title,
                    $tribute->message,
                    ucfirst($tribute->status),
                    $tribute->is_featured ? 'Yes' : 'No',
                    $tribute->publication_consent ? 'Yes' : 'No',
                    optional($tribute->created_at)->format('Y-m-d H:i'),
                    optional($tribute->moderated_at)->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        AuditLog::record('tribute.exported', null, 'Exported '.$tributes->count().' tribute(s)');

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function approve(Tribute $tribute): RedirectResponse
    {
        $tribute->update([
            'status' => 'approved',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        AuditLog::record('tribute.approved', $tribute);

        return back()->with('status', 'Tribute approved and published to the remembrance wall.');
    }

    public function reject(Tribute $tribute): RedirectResponse
    {
        $tribute->update([
            'status' => 'rejected',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        AuditLog::record('tribute.rejected', $tribute);

        return back()->with('status', 'Tribute rejected.');
    }

    public function feature(Tribute $tribute): RedirectResponse
    {
        $tribute->update(['is_featured' => ! $tribute->is_featured]);
        AuditLog::record('tribute.feature_toggled', $tribute);

        return back()->with('status', $tribute->is_featured ? 'Tribute featured on the homepage.' : 'Tribute unfeatured.');
    }

    public function update(Request $request, Tribute $tribute): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $tribute->update($data);
        AuditLog::record('tribute.edited', $tribute);

        return back()->with('status', 'Tribute updated.');
    }

    public function destroy(Tribute $tribute): RedirectResponse
    {
        AuditLog::record('tribute.deleted', $tribute, $tribute->name);
        $tribute->delete();

        return back()->with('status', 'Tribute deleted.');
    }
}
