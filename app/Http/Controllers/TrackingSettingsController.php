<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
class TrackingSettingsController extends Controller {
 public function edit(){return view('admin.tracking',['settings'=>SiteSetting::whereIn('key',['ga4_id','gtm_id','meta_pixel_id','tiktok_pixel_id'])->pluck('value','key')]);}
 public function update(Request $request){
  $data=$request->validate([
   'ga4_id'=>['nullable','regex:/^G-[A-Z0-9]{6,20}$/'],
   'gtm_id'=>['nullable','regex:/^GTM-[A-Z0-9]{4,15}$/'],
   'meta_pixel_id'=>['nullable','regex:/^[0-9]{8,25}$/'],
   'tiktok_pixel_id'=>['nullable','regex:/^[A-Z0-9]{10,30}$/'],
  ]);
  foreach(['ga4_id','gtm_id','meta_pixel_id','tiktok_pixel_id'] as $key){SiteSetting::updateOrCreate(['key'=>$key],['value'=>$data[$key]??'']);}
  return back()->with('ok','Konfigurasi tracking disimpan. Skrip hanya berjalan setelah persetujuan pengunjung.');
 }
}
