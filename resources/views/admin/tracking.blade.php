@extends('layouts.admin')
@section('content')
<h1>Analytics & Pixel Iklan</h1>
<p class="muted">Kosongkan ID yang belum tersedia. Semua tag dinonaktifkan sebelum pengunjung memberi persetujuan analytics/marketing. Jangan mengirim nama, telepon, atau data peserta ke event tracking.</p>
<form method="post" action="{{ route('admin.tracking.update') }}" class="card">
 @csrf
 @foreach(['ga4_id'=>'Google Analytics 4 (G-...)','gtm_id'=>'Google Tag Manager (GTM-...)','meta_pixel_id'=>'Meta Pixel (angka)','tiktok_pixel_id'=>'TikTok Pixel (huruf/angka)'] as $key=>$label)
 <p><label>{{ $label }}<br><input name="{{ $key }}" value="{{ old($key,$settings[$key]??'') }}" maxlength="30" style="width:100%;max-width:480px"></label></p>
 @endforeach
 <button type="submit">Simpan Tracking</button>
</form>
@if($errors->any())<div class="card" style="color:#b42318">{{ $errors->first() }}</div>@endif
<p class="muted">Catatan: menggunakan GTM bersama tag langsung dapat menggandakan page_view. Pilih konfigurasi yang tidak menduplikasi tag. Uji dengan browser devtools dan mode persetujuan.</p>
@endsection
