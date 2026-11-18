@extends('layouts.admin')
@section('content')
<h1>Pendaftaran Peserta</h1><p class="muted">Pendaftaran hanya dibuat petugas. Status terverifikasi memerlukan pemeriksaan manual.</p>
<div class="card"><h2>Buat pendaftaran</h2><form method="post" action="{{ route('admin.registrations.store') }}">@csrf<select name="lead_id" required><option value="">Pilih lead belum terdaftar</option>@foreach($leads as $lead)<option value="{{ $lead->id }}">#{{ $lead->id }} — {{ $lead->name }} ({{ $lead->phone }})</option>@endforeach</select><textarea name="admin_notes" placeholder="Catatan administrasi"></textarea><button>Tambah pendaftaran</button></form></div>
@foreach($registrations as $registration)<div class="card"><strong>{{ $registration->registration_code }}</strong> — {{ $registration->lead?->name }} · {{ $registration->program?->name ?? 'Program belum dipilih' }}<p>Status: {{ $registration->status }}</p><form method="post" action="{{ route('admin.registrations.update',$registration) }}">@csrf @method('PATCH')<select name="status">@foreach(['pending','verified','rejected','cancelled'] as $status)<option value="{{ $status }}" @selected($registration->status===$status)>{{ $status }}</option>@endforeach</select><input name="admin_notes" value="{{ $registration->admin_notes }}" placeholder="Catatan"><button>Perbarui</button></form></div>@endforeach
{{ $registrations->links() }}
@endsection
