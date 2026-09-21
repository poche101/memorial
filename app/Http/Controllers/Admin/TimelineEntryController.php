<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Memorial;
use App\Models\TimelineEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TimelineEntryController extends Controller
{
    public function index(): View
    {
        $memorial = Memorial::firstOrFail();

        return view('admin.timeline.index', [
            'memorial' => $memorial,
            'entries' => $memorial->timelineEntries()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $memorial = Memorial::firstOrFail();
        $data = $this->validated($request);
        $data['memorial_id'] = $memorial->id;
        $data['sort_order'] = $memorial->timelineEntries()->max('sort_order') + 1;

        $entry = TimelineEntry::create($data);
        AuditLog::record('timeline.created', $entry);

        return back()->with('status', 'Milestone added to the timeline.');
    }

    public function update(Request $request, TimelineEntry $timelineEntry): RedirectResponse
    {
        $this->assertBelongsToMemorial($timelineEntry);

        $timelineEntry->update($this->validated($request));
        AuditLog::record('timeline.updated', $timelineEntry);

        return back()->with('status', 'Milestone updated.');
    }

    public function destroy(TimelineEntry $timelineEntry): RedirectResponse
    {
        $this->assertBelongsToMemorial($timelineEntry);

        AuditLog::record('timeline.deleted', $timelineEntry, $timelineEntry->title);
        $timelineEntry->delete();

        return back()->with('status', 'Milestone removed.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $memorial = Memorial::firstOrFail();
        $data = $request->validate(['order' => ['required', 'array']]);

        $validIds = $memorial->timelineEntries()->pluck('id')->all();

        foreach ($data['order'] as $position => $id) {
            if (in_array((int) $id, $validIds, true)) {
                TimelineEntry::where('id', $id)->update(['sort_order' => $position]);
            }
        }

        return back()->with('status', 'Timeline order updated.');
    }

    protected function assertBelongsToMemorial(TimelineEntry $timelineEntry): void
    {
        $memorial = Memorial::firstOrFail();

        if ($timelineEntry->memorial_id !== $memorial->id) {
            throw new NotFoundHttpException;
        }
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'year_label' => ['required', 'string', 'max:50'],
            'event_date' => ['nullable', 'date'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
