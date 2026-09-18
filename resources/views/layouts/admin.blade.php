<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') &mdash; Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600&family=EB+Garamond:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tribute.css') }}">
<style>
/* Responsive Sidebar Layout Enhancements */
:root {
  --sidebar-width: 260px;
}

body {
  margin: 0;
  padding: 0;
}

.admin-shell {
  display: flex;
  min-height: 100vh;
  position: relative;
}

.admin-sidebar {
  width: var(--sidebar-width);
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.3s ease;
  z-index: 1000;
}

.admin-main {
  flex-grow: 1;
  width: calc(100% - var(--sidebar-width));
  min-width: 0; /* Prevents flex children from overflowing */
}

/* Mobile Top Header Bar */
.mobile-header {
  display: none;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
  background: var(--ink-dark, #1a1a1a);
  color: #fff;
  position: sticky;
  top: 0;
  z-index: 999;
}

.mobile-nav-toggle {
  background: transparent;
  border: none;
  color: inherit;
  cursor: pointer;
  padding: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.mobile-nav-toggle svg {
  width: 24px;
  height: 24px;
  fill: currentColor;
}

/* Sidebar Overlay Backdrop */
.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(2px);
  z-index: 999;
  opacity: 0;
  transition: opacity 0.3s ease;
}

/* Media Queries for Mobile/Tablet Views */
@media (max-width: 992px) {
  .mobile-header {
    display: flex;
  }

  .admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    height: 100vh;
    transform: translateX(-100%);
    box-shadow: 2px 0 12px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
  }

  .admin-sidebar.open {
    transform: translateX(0);
  }

  .sidebar-overlay.active {
    display: block;
    opacity: 1;
  }

  .admin-main {
    width: 100%;
  }

  .admin-topbar {
    padding-top: 16px;
  }
}
</style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="mobile-header">
  <div class="brand" style="margin: 0; font-size: 1.2rem;">Tribute Admin</div>
  <button class="mobile-nav-toggle" onclick="toggleSidebar()" aria-label="Toggle Navigation">
    <svg viewBox="0 0 24 24">
      <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
    </svg>
  </button>
</div>

<div class="admin-shell">
  <aside class="admin-sidebar" id="adminSidebar">
    <div>
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
    </div>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 36px; padding-bottom: 20px;">
      @csrf
      <button type="submit" class="btn secondary" style="width:100%; background:transparent; color:var(--gold-light, #d4af37); border-color:var(--gold-light, #d4af37); cursor:pointer;">Log out</button>
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

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  sidebar.classList.toggle('open');
  overlay.classList.toggle('active');
}
</script>

</body>
</html>
