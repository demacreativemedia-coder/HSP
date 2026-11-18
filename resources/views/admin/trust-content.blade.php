@extends('layouts.admin')
@section('content')
<h1>FAQ dan Testimoni</h1><p class="muted">Konten baru tidak tampil sebelum diterbitkan. Testimoni wajib memiliki persetujuan publikasi yang dapat diverifikasi.</p>
@if($errors->any())<div class="card" style="color:#b42318">{{ $errors->first() }}</div>@endif
<div class="card"><h2>Tambah / Ubah FAQ</h2><form method="post" action="{{ route('admin.faqs.save') }}">@csrf
<p><label>ID untuk edit (kosongkan untuk baru)<br><input name="id" type="number" min="1"></label></p>
<p><label>Pertanyaan<br><input name="question" required maxlength="250" style="width:90%"></label></p>
<p><label>Jawaban<br><textarea name="answer" rows="4" required style="width:90%"></textarea></label></p>
<p><label>Urutan <input name="sort_order" type="number" min="0" value="0"></label></p>
<p><label><input type="checkbox" name="published" value="1"> Tampilkan di website</label></p><button>Simpan FAQ</button></form></div>
<div class="card"><h2>FAQ tersimpan</h2><table><thead><tr><th>ID</th><th>Pertanyaan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>@forelse($faqs as $faq)<tr><td>{{ $faq->id }}</td><td>{{ $faq->question }}</td><td>{{ $faq->published?'Publik':'Draft' }}</td><td><form method="post" action="{{ route('admin.faqs.delete',$faq) }}" onsubmit="return confirm('Hapus FAQ ini?')">@csrf @method('DELETE')<button>Hapus</button></form></td></tr>@empty<tr><td colspan="4">Belum ada FAQ.</td></tr>@endforelse</tbody></table></div>
<div class="card"><h2>Tambah / Ubah Testimoni</h2><form method="post" action="{{ route('admin.testimonials.save') }}">@csrf
<p><label>ID untuk edit <input name="id" type="number" min="1"></label></p>
<p><label>Nama tampil <input name="display_name" required maxlength="100"></label></p>
<p><label>Keterangan <input name="role" maxlength="100" placeholder="Alumni / orang tua"></label></p>
<p><label>Isi testimoni<br><textarea name="quote" rows="4" required style="width:90%"></textarea></label></p>
<p><label>Urutan <input name="sort_order" type="number" min="0" value="0"></label></p>
<p><label><input type="checkbox" name="consent_verified" value="1"> Saya telah memverifikasi izin publikasi</label></p>
<p><label><input type="checkbox" name="published" value="1"> Terbitkan (hanya jika izin terverifikasi)</label></p><button>Simpan Testimoni</button></form></div>
<div class="card"><h2>Testimoni tersimpan</h2><table><thead><tr><th>ID</th><th>Nama</th><th>Izin</th><th>Status</th><th>Aksi</th></tr></thead><tbody>@forelse($testimonials as $t)<tr><td>{{ $t->id }}</td><td>{{ $t->display_name }}</td><td>{{ $t->consent_verified?'Ya':'Belum' }}</td><td>{{ $t->published?'Publik':'Draft' }}</td><td><form method="post" action="{{ route('admin.testimonials.delete',$t) }}" onsubmit="return confirm('Hapus testimoni ini?')">@csrf @method('DELETE')<button>Hapus</button></form></td></tr>@empty<tr><td colspan="5">Belum ada testimoni.</td></tr>@endforelse</tbody></table></div>
@endsection
