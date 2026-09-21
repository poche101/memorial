@extends('layouts.admin')
@section('title', 'Tribute Moderation')
@section('content')

<div class="tribute-toolbar">
  <div class="tribute-filters">
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
  <div class="table-scroll">
    <table class="admin-table tribute-table">
      <thead>
        <tr>
          <th style="width:32px;"><input type="checkbox" id="tribute-select-all" title="Select all for export"></th>
          <th>Name</th><th>Message</th><th>Status</th><th>Featured</th><th>Submitted</th><th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($tributes as $tribute)
        <tr>
          <td data-label="">
            <input type="checkbox" name="tribute_ids[]" value="{{ $tribute->id }}"
                   form="tribute-export-form" class="tribute-export-checkbox">
          </td>
          <td data-label="Name">{{ $tribute->name }}@if($tribute->relationship)<br><span style="color:var(--ink-soft); font-size:13px;">{{ $tribute->relationship }}</span>@endif</td>
          <td data-label="Message">{{ \Illuminate\Support\Str::limit($tribute->message, 90) }}</td>
          <td data-label="Status"><span class="badge {{ $tribute->status }}">{{ ucfirst($tribute->status) }}</span></td>
          <td data-label="Featured">{{ $tribute->is_featured ? 'Yes' : 'No' }}</td>
          <td data-label="Submitted">{{ $tribute->created_at->diffForHumans() }}</td>
          <td class="tribute-actions" data-label="">
            @if($tribute->status !== 'approved')
            <form method="POST" action="{{ route('admin.tributes.approve', $tribute) }}" class="inline-form">
              @csrf<button type="submit" class="btn">Approve</button>
            </form>
            @endif
            @if($tribute->status !== 'rejected')
            <form method="POST" action="{{ route('admin.tributes.reject', $tribute) }}" class="inline-form">
              @csrf<button type="submit" class="btn secondary">Reject</button>
            </form>
            @endif
            <form method="POST" action="{{ route('admin.tributes.feature', $tribute) }}" class="inline-form">
              @csrf<button type="submit" class="btn secondary">{{ $tribute->is_featured ? 'Unfeature' : 'Feature' }}</button>
            </form>
            <form method="POST" action="{{ route('admin.tributes.destroy', $tribute) }}" class="inline-form" onsubmit="return confirm('Delete this tribute?')">
              @csrf @method('DELETE')<button type="submit" class="btn danger">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7">No tributes in this view.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination" style="margin-top:20px;">{{ $tributes->links() }}</div>
</div>

<style>
.tribute-toolbar {
  margin-bottom: 22px;
  display: flex;
  gap: 10px;
  justify-content: space-between;
  flex-wrap: wrap;
}
.tribute-filters {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.table-scroll {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.tribute-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 720px;
}
.tribute-actions {
  white-space: nowrap;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}
.inline-form { display: inline; }

/* Stacked card layout on narrow phones */
@media (max-width: 480px) {
  .tribute-table { min-width: 0; }
  .tribute-table thead { display: none; }
  .tribute-table, .tribute-table tbody, .tribute-table tr, .tribute-table td {
    display: block;
    width: 100%;
  }
  .tribute-table tr {
    border: 1px solid #e2e2e2;
    border-radius: 8px;
    margin-bottom: 12px;
    padding: 10px 12px;
  }
  .tribute-table td {
    border: none;
    padding: 6px 0;
    text-align: left;
  }
  .tribute-table td[data-label]:not([data-label=""])::before {
    content: attr(data-label);
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 2px;
  }
  .tribute-actions {
    white-space: normal;
    padding-top: 8px;
  }
  .tribute-actions .inline-form,
  .tribute-actions .btn {
    flex: 1 1 auto;
  }
  .tribute-actions .btn {
    width: 100%;
  }
}

@media (max-width: 640px) {
  .tribute-toolbar { flex-direction: column; align-items: stretch; }
  .tribute-toolbar > button { width: 100%; }
  .tribute-filters .btn { flex: 1 1 auto; text-align: center; }
}
</style>

<script>
  document.getElementById('tribute-select-all')?.addEventListener('change', function () {
    var checked = this.checked;
    document.querySelectorAll('.tribute-export-checkbox').forEach(function (box) {
      box.checked = checked;
    });
  });
</script>
@endsection
