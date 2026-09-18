<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Memorial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MemorialController extends Controller
{
    public function edit(): View
    {
        $memorial = Memorial::firstOrNew();

        return view('admin.memorial.edit', ['memorial' => $memorial]);
    }

    public function update(Request $request): RedirectResponse
    {
        $memorial = Memorial::firstOrNew();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'title' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'death_date' => ['nullable', 'date', 'after_or_equal:birth_date'],
            'statement' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'portrait' => ['nullable', 'image', 'max:6144'],
            'brochure' => ['nullable', 'mimes:pdf', 'max:15360'],
            'remove_brochure' => ['nullable', 'boolean'],
        ]);

        if (! $memorial->slug) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('portrait')) {
            $data['portrait_path'] = $request->file('portrait')->store('portraits', 'public');
        }

        if ($request->hasFile('brochure')) {
            $data['brochure_path'] = $request->file('brochure')->store('brochures', 'public');
        } elseif ($request->boolean('remove_brochure')) {
            $data['brochure_path'] = null;
        }

        $memorial->fill($data)->save();

        AuditLog::record('memorial.updated', $memorial);

        return back()->with('status', 'Memorial profile updated.');
    }
}
