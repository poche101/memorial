@extends('layouts.admin')
@section('title', 'Memorial Profile')
@section('content')
<div class="admin-card">
  <form method="POST" action="{{ route('admin.memorial.update') }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @method('PUT')
    <label>Full name</label>
    <input type="text" name="name" required value="{{ old('name', $memorial->name) }}">

    <label>Title (e.g. Pastor, Dr, Chief)</label>
    <input type="text" name="title" value="{{ old('title', $memorial->title) }}">

    <label>Birth date</label>
    <input type="date" name="birth_date" value="{{ old('birth_date', $memorial->birth_date?->format('Y-m-d')) }}">

    <label>Death date</label>
    <input type="date" name="death_date" value="{{ old('death_date', $memorial->death_date?->format('Y-m-d')) }}">

    <label>Memorial statement (short verse or quote)</label>
    <input type="text" name="statement" value="{{ old('statement', $memorial->statement) }}">

    <label>Portrait photo</label>
    <input type="file" name="portrait" accept="image/*">

        <label>Memorial brochure / order of service (PDF)</label>
    @if($memorial->brochure_path)
      <p style="font-size:14px; margin:4px 0 10px;">
        Current file: <a href="{{ asset('storage/'.$memorial->brochure_path) }}" target="_blank" style="color:var(--gold);">view brochure</a>
        &middot; <label style="display:inline-flex; gap:6px; align-items:center; font-weight:normal;">
          <input type="checkbox" name="remove_brochure" value="1" style="width:auto;"> remove it
        </label>
      </p>
    @endif
    <input type="file" name="brochure" accept="application/pdf">

    <label>Biography</label>
    <textarea name="biography" rows="10">{{ old('biography', $memorial->biography) }}</textarea>

    <label>Status</label>
    <select name="status">
      <option value="draft" @selected(old('status', $memorial->status) === 'draft')>Draft</option>
      <option value="published" @selected(old('status', $memorial->status) === 'published')>Published</option>
    </select>

    <button type="submit" class="submit-btn" style="margin-top:22px;">Save Memorial Profile</button>
  </form>
</div>
@endsection
