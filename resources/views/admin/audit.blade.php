@extends('layouts.admin')
@section('content')
<h1>Riwayat Aktivitas Admin</h1>
<p>Catatan tindakan administratif; data sensitif tidak disimpan di kolom aktivitas.</p>
<table><thead><tr><th>Waktu</th><th>Petugas ID</th><th>Aksi</th><th>Objek</th></tr></thead><tbody>
@foreach($logs as $log)<tr><td>{{ $log->created_at }}</td><td>{{ $log->user_id }}</td><td>{{ $log->action }}</td><td>{{ $log->subject_type }} #{{ $log->subject_id }}</td></tr>@endforeach
</tbody></table>{{ $logs->links() }}
@endsection
