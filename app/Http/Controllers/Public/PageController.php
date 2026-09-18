<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Memorial;
use App\Models\Remembrance;
use App\Models\Tribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PageController extends Controller
{
    /**
     * Currently the site is built for a single memorial profile
     * (PRD 18 lists "multiple memorial profiles" as a future enhancement),
     * so we always resolve the one published memorial here.
     */
    protected function memorial(): Memorial
    {
        return Memorial::where('status', 'published')->firstOrFail();
    }

    public function home(): View
    {
        $memorial = $this->memorial();

        return view('public.home', [
            'memorial' => $memorial,
            'events' => $memorial->events()->published()->orderBy('event_date')->get(),
            'featuredTributes' => $memorial->tributes()->approved()->where('is_featured', true)->take(3)->get(),
        ]);
    }

    public function biography(): View
    {
        $memorial = $this->memorial();

        return view('public.biography', ['memorial' => $memorial]);
    }

    public function timeline(): View
    {
        $memorial = $this->memorial();

        return view('public.timeline', [
            'memorial' => $memorial,
            'entries' => $memorial->timelineEntries,
        ]);
    }

    public function gallery(): View
    {
        $memorial = $this->memorial();

        return view('public.gallery', [
            'memorial' => $memorial,
            'albums' => $memorial->albums()->with('media')->get(),
        ]);
    }

    public function events(): View
    {
        $memorial = $this->memorial();

        return view('public.events', [
            'memorial' => $memorial,
            'events' => $memorial->events()->published()->orderBy('event_date')->get(),
        ]);
    }

    public function tributes(): View
    {
        $memorial = $this->memorial();

        return view('public.tributes', [
            'memorial' => $memorial,
            'tributes' => $memorial->approvedTributes()->paginate(10),
        ]);
    }

    public function storeTribute(Request $request): RedirectResponse
    {
        $memorial = $this->memorial();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'relationship' => ['nullable', 'string', 'max:150'],
            'title' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'publication_consent' => ['required', 'accepted'],
            // Honeypot spam field: must stay empty.
            'website' => ['prohibited'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tributes', 'public');
        }

        Tribute::create([
            'memorial_id' => $memorial->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'relationship' => $data['relationship'] ?? null,
            'title' => $data['title'] ?? null,
            'message' => $data['message'],
            'image_path' => $imagePath,
            'publication_consent' => true,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thank you. Your tribute has been submitted and will appear once it has been reviewed by the family.');
    }

    public function storeRemembrance(Request $request): RedirectResponse
    {
        $memorial = $this->memorial();

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:500'],
            'anonymous' => ['nullable', 'boolean'],
        ]);

        Remembrance::create([
            'memorial_id' => $memorial->id,
            'name' => $request->boolean('anonymous') ? null : ($data['name'] ?? null),
            'message' => $data['message'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'A candle has been lit in remembrance.');
    }

    public function downloadBrochure(): StreamedResponse
    {
        $memorial = $this->memorial();

        abort_if(! $memorial->brochure_path, 404);
        abort_unless(Storage::disk('public')->exists($memorial->brochure_path), 404);

        return Storage::disk('public')->download(
            $memorial->brochure_path,
            \Illuminate\Support\Str::slug($memorial->name).'-brochure.pdf'
        );
    }

    public function contact(): View
    {
        return view('public.contact', ['memorial' => $this->memorial()]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $memorial = $this->memorial();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['prohibited'],
        ]);

        ContactMessage::create([
            'memorial_id' => $memorial->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thank you for reaching out. The family office will respond as soon as possible.');
    }
}
