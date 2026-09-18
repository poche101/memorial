@extends('layouts.admin')
@section('title', 'Timeline Manager')
@section('content')
<div class="admin-card">
  <h3 style="margin-bottom:16px;">Add a Milestone</h3>
  <form method="POST" action="{{ route('admin.timeline.store') }}" class="admin-form">
    @csrf
    <label>Year / label</label>
    <input type="text" name="year_label" required placeholder="e.g. 1964, 1990s, —">
    <label>Exact date (optional, used for sorting)</label>
    <input type="date" name="event_date">
    <label>Title</label>
    <input type="text" name="title" required>
    <label>Description</label>
    <textarea name="description" rows="3"></textarea>
    <button type="submit" class="submit-btn" style="margin-top:16px;">Add Milestone</button>
  </form>
</div>

<div class="admin-card">
  <h3 style="margin-bottom:16px;">Current Timeline</h3>
  <table class="admin-table">
    <thead><tr><th>Year</th><th>Title</th><th>Description</th><th></th></tr></thead>
    <tbody>
      @forelse($entries as $entry)
      <tr>
        <td>{{ $entry->year_label }}</td>
        <td>{{ $entry->title }}</td>
        <td>{{ \Illuminate\Support\Str::limit($entry->description, 80) }}</td>
        <td>
          <form method="POST" action="{{ route('admin.timeline.destroy', $entry) }}" onsubmit="return confirm('Remove this milestone?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn danger">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="4">No milestones yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
