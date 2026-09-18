@extends('layouts.admin')
@section('title', 'Gallery Manager')
@section('content')
<div class="admin-card">
  <h3 style="margin-bottom:16px;">Create Album</h3>
  <form method="POST" action="{{ route('admin.gallery.albums.store') }}" class="admin-form">
    @csrf
    <label>Album title</label>
    <input type="text" name="title" required>
    <label>Description</label>
    <textarea name="description" rows="2"></textarea>
    <button type="submit" class="submit-btn" style="margin-top:16px;">Create Album</button>
  </form>
</div>

@forelse($albums as $album)
<div class="admin-card">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h3>{{ $album->title }} <span style="color:var(--ink-soft); font-size:14px;">({{ $album->media_count }} items)</span></h3>
    <form method="POST" action="{{ route('admin.gallery.albums.destroy', $album) }}" onsubmit="return confirm('Delete this album and all its media?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn danger">Delete Album</button>
    </form>
  </div>

  <form method="POST" action="{{ route('admin.gallery.media.store', $album) }}" enctype="multipart/form-data" class="admin-form" style="margin-bottom:18px;">
    @csrf
    <label>Type</label>
    <select name="type">
      <option value="photo">Photo</option>
      <option value="video">Video</option>
    </select>
    <label>File upload (photo or video, or leave blank and use a video URL below)</label>
    <input type="file" name="file" accept="image/*,video/*">
    <label>Or video URL (YouTube / Vimeo link)</label>
    <input type="url" name="video_url">
    <label>Caption</label>
    <input type="text" name="caption">
    <button type="submit" class="submit-btn" style="margin-top:14px;">Add Media</button>
  </form>

  <table class="admin-table">
    <thead><tr><th>Type</th><th>Caption</th><th></th></tr></thead>
    <tbody>
      @forelse($album->media as $item)
      <tr>
        <td>{{ ucfirst($item->type) }}</td>
        <td>{{ $item->caption }}</td>
        <td>
          <form method="POST" action="{{ route('admin.gallery.media.destroy', $item) }}" onsubmit="return confirm('Remove this item?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn danger">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="3">No media in this album yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@empty
<p class="note">No albums yet — create one above.</p>
@endforelse
@endsection
