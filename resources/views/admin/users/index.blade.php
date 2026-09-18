@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
<div class="admin-card">
  <h3 style="margin-bottom:16px;">Add Administrator</h3>
  <form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
    @csrf
    <label>Name</label>
    <input type="text" name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Temporary password</label>
    <input type="text" name="password" required minlength="8">
    <label>Role</label>
    <select name="role">
      @foreach($roles as $role)
        <option value="{{ $role->name }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
      @endforeach
    </select>
    <button type="submit" class="submit-btn" style="margin-top:16px;">Create Account</button>
  </form>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th></th></tr></thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
          <form method="POST" action="{{ route('admin.users.update-role', $user) }}" style="display:flex; gap:8px;">
            @csrf @method('PUT')
            <select name="role" onchange="this.form.submit()">
              @foreach($roles as $role)
                <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
        <td style="white-space:nowrap;">
          <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="display:inline;">
            @csrf<button type="submit" class="btn secondary">{{ $user->is_active ? 'Disable' : 'Enable' }}</button>
          </form>
          <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('Delete this admin account?')">
            @csrf @method('DELETE')<button type="submit" class="btn danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
