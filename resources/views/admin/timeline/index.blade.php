@extends('layouts.admin')
@section('title', 'Timeline Manager')
@section('content')
<div class="admin-card">
  <h3 style="margin-bottom:16px;">Add a Milestone</h3>
  <form method="POST" action="{{ route('admin.timeline.store') }}" class="admin-form">
    @csrf
    <div class="form-grid">
      <div class="form-field">
        <label>Year / label</label>
        <input type="text" name="year_label" required placeholder="e.g. 1964, 1990s, —">
      </div>
      <div class="form-field">
        <label>Exact date (optional, used for sorting)</label>
        <input type="date" name="event_date">
      </div>
      <div class="form-field span-2">
        <label>Title</label>
        <input type="text" name="title" required>
      </div>
      <div class="form-field span-2">
        <label>Description</label>
        <textarea name="description" rows="3"></textarea>
      </div>
    </div>
    <button type="submit" class="submit-btn" style="margin-top:16px;">Add Milestone</button>
  </form>
</div>

<div class="admin-card">
  <h3 style="margin-bottom:16px;">Current Timeline</h3>

  <div class="table-scroll">
    <table class="admin-table">
      <thead><tr><th>Year</th><th>Title</th><th>Description</th><th></th></tr></thead>
      <tbody>
        @forelse($entries as $entry)
        <tr>
          <td data-label="Year">{{ $entry->year_label }}</td>
          <td data-label="Title">{{ $entry->title }}</td>
          <td data-label="Description">{{ \Illuminate\Support\Str::limit($entry->description, 80) }}</td>
          <td class="table-actions" data-label="">
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
</div>

{{-- Edit Modal --}}
<div id="editModal" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <h3>Edit Milestone</h3>
    <form method="POST" id="editForm" class="admin-form">
      @csrf
      @method('PUT')
      <div class="form-grid">
        <div class="form-field">
          <label>Year / label</label>
          <input type="text" name="year_label" id="edit_year_label" required>
        </div>
        <div class="form-field">
          <label>Exact date (optional)</label>
          <input type="date" name="event_date" id="edit_event_date">
        </div>
        <div class="form-field span-2">
          <label>Title</label>
          <input type="text" name="title" id="edit_title" required>
        </div>
        <div class="form-field span-2">
          <label>Description</label>
          <textarea name="description" id="edit_description" rows="3"></textarea>
        </div>
      </div>
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
/* ---- Form ---- */
.form-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
}
.form-field { display: flex; flex-direction: column; }
.form-field label { margin-bottom: 4px; }
.form-field input,
.form-field textarea {
  width: 100%;
  box-sizing: border-box;
}

@media (min-width: 640px) {
  .form-grid { grid-template-columns: 1fr 1fr; }
  .form-field.span-2 { grid-column: 1 / -1; }
}

/* ---- Table ---- */
.table-scroll {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.admin-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 560px; /* keeps columns legible while scrollable on tablets */
}
.table-actions {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}

/* Stacked "card" layout on narrow phones instead of horizontal scroll */
@media (max-width: 480px) {
  .admin-table { min-width: 0; }
  .admin-table thead { display: none; }
  .admin-table, .admin-table tbody, .admin-table tr, .admin-table td {
    display: block;
    width: 100%;
  }
  .admin-table tr {
    border: 1px solid #e2e2e2;
    border-radius: 8px;
    margin-bottom: 12px;
    padding: 10px 12px;
  }
  .admin-table td {
    border: none;
    padding: 6px 0;
    text-align: left;
  }
  .admin-table td[data-label]:not([data-label=""])::before {
    content: attr(data-label);
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 2px;
  }
  .table-actions {
    padding-top: 8px;
  }
  .table-actions .btn {
    flex: 1 1 auto;
  }
}

/* ---- Modals ---- */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, .5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 16px;
  box-sizing: border-box;
}
.modal-box {
  background: #fff;
  border-radius: 8px;
  padding: 24px;
  width: 100%;
  max-width: 420px;
  max-height: 90vh;
  overflow-y: auto;
  box-sizing: border-box;
}
.modal-actions {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  flex-wrap: wrap;
}

@media (max-width: 480px) {
  .modal-box { padding: 16px; }
  .modal-actions { flex-direction: column-reverse; }
  .modal-actions .btn,
  .modal-actions .submit-btn {
    width: 100%;
  }
}
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
