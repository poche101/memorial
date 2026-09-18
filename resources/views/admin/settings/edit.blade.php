@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="admin-card">
  <form method="POST" action="{{ route('admin.settings.update') }}" class="admin-form">
    @csrf @method('PUT')

    <label>Website name</label>
    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}">

    <label>Contact email</label>
    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">

    <label>Facebook URL</label>
    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}">

    <label>Instagram URL</label>
    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}">

    <label style="display:flex; gap:8px; align-items:center;">
      <input type="checkbox" name="candle_lighting_enabled" value="1" style="width:auto;" @checked($settings['candle_lighting_enabled'])> Enable virtual candle-lighting
    </label>

    <label style="display:flex; gap:8px; align-items:center;">
      <input type="checkbox" name="submission_requires_consent" value="1" style="width:auto;" @checked($settings['submission_requires_consent'] ?? true)> Require publication consent on tribute submissions
    </label>

    <label>Maximum upload size (MB)</label>
    <input type="number" name="max_upload_size_mb" value="{{ old('max_upload_size_mb', $settings['max_upload_size_mb'] ?? 20) }}">

    <button type="submit" class="submit-btn" style="margin-top:18px;">Save Settings</button>
  </form>
</div>
@endsection
