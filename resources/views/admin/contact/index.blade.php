@extends('layouts.admin')
@section('title', 'Enquiries')
@section('content')
<div class="admin-card">
  <table class="admin-table">
    <thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Status</th><th></th></tr></thead>
    <tbody>
      @forelse($messages as $message)
      <tr>
        <td>{{ $message->name }}<br><span style="color:var(--ink-soft); font-size:13px;">{{ $message->email }}</span></td>
        <td>{{ $message->subject }}</td>
        <td>{{ \Illuminate\Support\Str::limit($message->message, 80) }}</td>
        <td><span class="badge {{ $message->status === 'resolved' ? 'approved' : ($message->status === 'unread' ? 'pending' : '') }}">{{ ucfirst($message->status) }}</span></td>
        <td>
          @if($message->status !== 'resolved')
          <form method="POST" action="{{ route('admin.contact.resolve', $message) }}">
            @csrf<button type="submit" class="btn">Mark resolved</button>
          </form>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="5">No enquiries yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination" style="margin-top:20px;">{{ $messages->links() }}</div>
</div>
@endsection
