<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') &mdash; Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tribute.css') }}">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="brand">Tribute Admin</div>
    <nav>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
      @role('super_admin|memorial_editor')
      <a href="{{ route('admin.memorial.edit') }}" class="{{ request()->routeIs('admin.memorial.*') ? 'active' : '' }}">Memorial Profile</a>
      <a href="{{ route('admin.timeline.index') }}" class="{{ request()->routeIs('admin.timeline.*') ? 'active' : '' }}">Timeline Manager</a>
      <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Gallery Manager</a>
      @endrole
      @role('super_admin|moderator')
      <a href="{{ route('admin.tributes.index') }}" class="{{ request()->routeIs('admin.tributes.*') ? 'active' : '' }}">Tribute Moderation</a>
      <a href="{{ route('admin.contact.index') }}" class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">Enquiries</a>
      @endrole
      @role('super_admin|event_manager')
      <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">Events Manager</a>
      @endrole
      @role('super_admin')
      <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">User Management</a>
      <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Settings</a>
      @endrole
    </nav>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:36px;">
      @csrf
      <button type="submit" class="btn secondary" style="width:100%; background:transparent; color:var(--gold-light); border-color:var(--gold-light);">Log out</button>
    </form>
  </aside>
  <main class="admin-main">
    <div class="admin-topbar">
      <h1>@yield('title', 'Dashboard')</h1>
      <div style="font-size:14px; color:var(--ink-soft);">{{ auth()->user()->name }}</div>
    </div>
    @if(session('status'))<div class="status-banner">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="error-banner">{{ $errors->first() }}</div>@endif
    @yield('content')
  </main>
</div>
</body>
</html>
