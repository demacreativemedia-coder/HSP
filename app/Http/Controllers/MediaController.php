<?php
namespace App\Http\Controllers;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MediaController extends Controller {
 public function index(){return view('admin.media',['assets'=>MediaAsset::latest()->paginate(20)]);}
 public function store(Request $request){
  $data=$request->validate(['title'=>'required|string|max:150','alt_text'=>'nullable|string|max:200','file'=>'required|file|mimes:jpg,jpeg,png,webp|max:5120','consent_verified'=>'accepted']);
  $file=$request->file('file');$path=$file->store('media-private','local');
  MediaAsset::create(['title'=>$data['title'],'alt_text'=>$data['alt_text']??null,'path'=>$path,'mime_type'=>$file->getMimeType(),'size_bytes'=>$file->getSize(),'consent_verified'=>true,'is_public'=>false,'uploaded_by'=>$request->user()->id]);
  return back()->with('ok','Media tersimpan sebagai privat/draft. Publikasi memerlukan pemeriksaan tambahan.');
 }
 public function publish(Request $request,MediaAsset $asset){
  $request->validate(['confirm_review'=>'accepted']);
  abort_unless($asset->consent_verified,422,'Persetujuan belum diverifikasi.');
  if(!$asset->is_public){
   $content=Storage::disk('local')->get($asset->path);
   $publicPath='media-approved/'.basename($asset->path);
   Storage::disk('public')->put($publicPath,$content);
  }else{Storage::disk('public')->delete('media-approved/'.basename($asset->path));}
  $asset->update(['is_public'=>!$asset->is_public]);
  \App\Support\AdminAudit::record('media.publication_changed',$asset);return back()->with('ok','Status publikasi media diperbarui.');
 }
 public function destroy(MediaAsset $asset){Storage::disk('local')->delete($asset->path);Storage::disk('public')->delete('media-approved/'.basename($asset->path));$asset->delete();\App\Support\AdminAudit::record('media.deleted');return back()->with('ok','Media dihapus.');}
}
