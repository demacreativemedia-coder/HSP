<?php
namespace App\Http\Controllers;
use App\Models\{Lead,Registration};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RegistrationController extends Controller {
 public function index(){return view('admin.registrations',['registrations'=>Registration::with(['lead','program'])->latest()->paginate(20),'leads'=>Lead::whereDoesntHave('registration')->latest()->limit(100)->get()]);}
 public function store(Request $request){
  $data=$request->validate(['lead_id'=>'required|integer|exists:leads,id','program_id'=>'nullable|integer|exists:programs,id','admin_notes'=>'nullable|string|max:2000']);
  DB::transaction(function()use($data,$request){
   $lead=Lead::query()->lockForUpdate()->findOrFail($data['lead_id']);
   if(Registration::where('lead_id',$lead->id)->exists())abort(422,'Lead sudah memiliki pendaftaran.');
   $registration=Registration::create(['lead_id'=>$lead->id,'program_id'=>$data['program_id']??$lead->program_id,'registration_code'=>'HIK-REG-'.str_pad((string)$lead->id,7,'0',STR_PAD_LEFT),'admin_notes'=>$data['admin_notes']??null,'status'=>'pending']);
   $lead->activities()->create(['action'=>'registration_created','user_id'=>$request->user()->id,'details'=>'Pendaftaran '.$registration->registration_code.' dibuat; belum diverifikasi']);
  });
  return back()->with('ok','Pendaftaran dibuat, menunggu verifikasi.');
 }
 public function update(Request $request,Registration $registration){
  $data=$request->validate(['status'=>'required|in:pending,verified,rejected,cancelled','admin_notes'=>'nullable|string|max:2000']);
  DB::transaction(function()use($request,$registration,$data){
   $previous=$registration->status;
   // A verified registration cannot be silently changed to pending.
   if ($previous==='verified' && $data['status']==='pending') abort(422,'Pendaftaran terverifikasi tidak dapat dikembalikan ke pending.');
   $registration->update(['status'=>$data['status'],'admin_notes'=>$data['admin_notes']??null,'verified_by'=>$data['status']==='verified'?$request->user()->id:null,'registered_on'=>$data['status']==='verified'?($registration->registered_on??now()->toDateString()):null]);
   $registration->lead->activities()->create(['action'=>'registration_status','user_id'=>$request->user()->id,'details'=>$previous.' → '.$data['status']]);
  });
  return back()->with('ok','Status pendaftaran diperbarui.');
 }
}
