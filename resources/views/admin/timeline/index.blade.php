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
        <td class="table-actions">
          <button type="button" class="btn"
                  data-edit-url="{{ route('admin.timeline.update', $entry) }}"
                  data-year-label="{{ $entry->year_label }}"
                  data-event-date="{{ $entry->event_date?->format('Y-m-d') }}"
                  data-title="{{ $entry->title }}"
                  data-description="{{ $entry->description }}"
                  onclick="openEditModal(this)">
            Edit
          </button>
          <button type="button" class="btn danger"
                  data-delete-url="{{ route('admin.timeline.destroy', $entry) }}"
                  data-title="{{ $entry->title }}"
                  onclick="openDeleteModal(this)">
            Delete
          </button>
        </td>
      </tr>
      @empty
      <tr><td colspan="4">No milestones yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <h3>Edit Milestone</h3>
    <form method="POST" id="editForm" class="admin-form">
      @csrf
      @method('PUT')
      <label>Year / label</label>
      <input type="text" name="year_label" id="edit_year_label" required>
      <label>Exact date (optional)</label>
      <input type="date" name="event_date" id="edit_event_date">
      <label>Title</label>
      <input type="text" name="title" id="edit_title" required>
      <label>Description</label>
      <textarea name="description" id="edit_description" rows="3"></textarea>
      <div class="modal-actions" style="margin-top:16px;">
        <button type="button" class="btn" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="submit-btn">Save Changes</button>
      </div>
    </form>
  </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <h3>Remove Milestone</h3>
    <p id="deleteModalText" style="margin:12px 0 20px;"></p>
    <form method="POST" id="deleteForm">
      @csrf
      @method('DELETE')
      <div class="modal-actions">
        <button type="button" class="btn" onclick="closeDeleteModal()">Cancel</button>
        <button type="submit" class="btn danger">Yes, Remove</button>
      </div>
    </form>
  </div>
</div>

<style>
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.5);
  display: flex; align-items: center; justify-content: center; z-index: 1000;
}
.modal-box {
  background: #fff; border-radius: 8px; padding: 24px;
  width: 100%; max-width: 420px;
}
.modal-actions { display: flex; gap: 8px; justify-content: flex-end; }
.table-actions { display: flex; gap: 6px; }
</style>

<script>
function openEditModal(btn) {
  const form = document.getElementById('editForm');
  form.action = btn.dataset.editUrl;
  document.getElementById('edit_year_label').value = btn.dataset.yearLabel;
  document.getElementById('edit_event_date').value = btn.dataset.eventDate || '';
  document.getElementById('edit_title').value = btn.dataset.title;
  document.getElementById('edit_description').value = btn.dataset.description || '';
  document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
}

function openDeleteModal(btn) {
  document.getElementById('deleteModalText').textContent = `Remove "${btn.dataset.title}" from the timeline? This can't be undone.`;
  document.getElementById('deleteForm').action = btn.dataset.deleteUrl;
  document.getElementById('deleteModal').style.display = 'flex';
}
function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
}
</script>
@endsection
