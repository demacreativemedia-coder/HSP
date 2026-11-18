<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Models\SiteSetting;
class LeadController extends Controller {
 public function store(Request $request){
  $data=$request->validate(['name'=>'required|string|min:2|max:120','phone'=>'required|string|max:25','program_id'=>'nullable|integer|exists:programs,id','utm_source'=>'nullable|string|max:100','utm_medium'=>'nullable|string|max:100','utm_campaign'=>'nullable|string|max:150','utm_content'=>'nullable|string|max:150','website'=>'present|size:0']);
  $phone=preg_replace('/\D+/','',$data['phone']);if(str_starts_with($phone,'0'))$phone='62'.substr($phone,1);
  if(!preg_match('/^62[0-9]{8,13}$/',$phone))return back()->withErrors(['phone'=>'Masukkan nomor WhatsApp Indonesia yang valid.'])->withInput();
  $lead=DB::transaction(function()use($request,$data,$phone){
   $lead=Lead::create(['name'=>$data['name'],'phone'=>$phone,'program_id'=>$data['program_id']??null,'source'=>'web','utm_source'=>$data['utm_source']??null,'utm_medium'=>$data['utm_medium']??null,'utm_campaign'=>$data['utm_campaign']??null,'utm_content'=>$data['utm_content']??null,'landing_page'=>Str::limit($request->headers->get('referer',''),200,'')]);
   $lead->activities()->create(['action'=>'created','details'=>'Formulir konsultasi berhasil disimpan']);return $lead;
  });
  $wa=preg_replace('/\D+/','',SiteSetting::val('whatsapp',''));
  if (!preg_match('/^62[0-9]{8,13}$/',$wa)) return redirect()->route('consultation.received')->with('reference','HIK-'.$lead->id);
  $message='Halo LPK Hikari Sumut, saya '.$lead->name.' ingin konsultasi program kerja ke Jepang. Referensi: HIK-'.$lead->id;
  return redirect()->away('https://wa.me/'.$wa.'?text='.rawurlencode($message));
 }
}
