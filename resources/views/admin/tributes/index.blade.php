@extends('layouts.admin')
@section('title', 'Tribute Moderation')
@section('content')

<div style="margin-bottom:22px; display:flex; gap:10px; justify-content:space-between; flex-wrap:wrap;">
  <div style="display:flex; gap:10px;">
    @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
      <a href="{{ route('admin.tributes.index', ['status' => $key]) }}" class="btn {{ $status === $key ? '' : 'secondary' }}">{{ $label }}</a>
    @endforeach
  </div>
  <button type="submit" form="tribute-export-form" class="btn secondary">Export selected to CSV</button>
</div>

@if(session('status'))
  <div class="admin-alert" style="margin-bottom:16px;">{{ session('status') }}</div>
@endif

{{-- Standalone export form. It has no rows of its own — every checkbox --}}
{{-- below points at it via form="tribute-export-form", so it can sit    --}}
{{-- alongside the per-row approve/reject/feature/delete forms without   --}}
{{-- nesting (HTML forms can't nest). Exporting never changes a          --}}
{{-- tribute's status, so nothing here removes anything from this list. --}}
<form id="tribute-export-form" method="POST" action="{{ route('admin.tributes.export') }}">
  @csrf
  <input type="hidden" name="status" value="{{ $status }}">
</form>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th style="width:32px;"><input type="checkbox" id="tribute-select-all" title="Select all for export"></th>
        <th>Name</th><th>Message</th><th>Status</th><th>Featured</th><th>Submitted</th><th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($tributes as $tribute)
      <tr>
        <td>
          <input type="checkbox" name="tribute_ids[]" value="{{ $tribute->id }}"
                 form="tribute-export-form" class="tribute-export-checkbox">
        </td>
        <td>{{ $tribute->name }}@if($tribute->relationship)<br><span style="color:var(--ink-soft); font-size:13px;">{{ $tribute->relationship }}</span>@endif</td>
        <td>{{ \Illuminate\Support\Str::limit($tribute->message, 90) }}</td>
        <td><span class="badge {{ $tribute->status }}">{{ ucfirst($tribute->status) }}</span></td>
        <td>{{ $tribute->is_featured ? 'Yes' : 'No' }}</td>
        <td>{{ $tribute->created_at->diffForHumans() }}</td>
        <td style="white-space:nowrap;">
          @if($tribute->status !== 'approved')
          <form method="POST" action="{{ route('admin.tributes.approve', $tribute) }}" style="display:inline;">
            @csrf<button type="submit" class="btn">Approve</button>
          </form>
          @endif
          @if($tribute->status !== 'rejected')
          <form method="POST" action="{{ route('admin.tributes.reject', $tribute) }}" style="display:inline;">
            @csrf<button type="submit" class="btn secondary">Reject</button>
          </form>
          @endif
          <form method="POST" action="{{ route('admin.tributes.feature', $tribute) }}" style="display:inline;">
            @csrf<button type="submit" class="btn secondary">{{ $tribute->is_featured ? 'Unfeature' : 'Feature' }}</button>
          </form>
          <form method="POST" action="{{ route('admin.tributes.destroy', $tribute) }}" style="display:inline;" onsubmit="return confirm('Delete this tribute?')">
            @csrf @method('DELETE')<button type="submit" class="btn danger">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="7">No tributes in this view.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination" style="margin-top:20px;">{{ $tributes->links() }}</div>
</div>

<script>
  document.getElementById('tribute-select-all')?.addEventListener('change', function () {
    var checked = this.checked;
    document.querySelectorAll('.tribute-export-checkbox').forEach(function (box) {
      box.checked = checked;
    });
  });
</script>
@endsection
