<?php
namespace App\Http\Controllers;
use App\Models\Lead;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
class FollowUpController extends Controller {
 public function index(){return view('admin.follow-ups',['items'=>FollowUp::with('lead')->orderBy('due_at')->paginate(30),'leads'=>Lead::latest()->limit(200)->get()]);}
 public function store(Request $r){$v=$r->validate(['lead_id'=>'required|exists:leads,id','due_at'=>'required|date','note'=>'nullable|string|max:2000']);$v['user_id']=$r->user()->id;FollowUp::create($v);return back()->with('ok','Follow-up dijadwalkan.');}
 public function complete(FollowUp $followUp){$followUp->update(['status'=>'done']);return back()->with('ok','Follow-up selesai.');}
 public function export():StreamedResponse {return response()->streamDownload(function(){ $f=fopen('php://output','w');fwrite($f,"\xEF\xBB\xBF");fputcsv($f,['ID','Nama','WhatsApp','Program','Status','Sumber','UTM Campaign','Dibuat'],',','"','\\');Lead::with('program')->orderBy('id')->chunk(500,function($rows)use($f){foreach($rows as $l)fputcsv($f,[$l->id,self::csvSafe($l->name),self::csvSafe($l->phone),self::csvSafe($l->program?->name),self::csvSafe($l->status),self::csvSafe($l->utm_source),self::csvSafe($l->utm_campaign),$l->created_at],',','"','\\');});fclose($f);},'hikari-leads-'.date('Ymd').'.csv',['Content-Type'=>'text/csv; charset=UTF-8']);}
 /** Prevent spreadsheet formula execution when CSV is opened in Excel/Sheets. */
 private static function csvSafe($value):string {
  $text=(string)($value??'');
  return preg_match('/^[\x00-\x20]*[=+@\-]/u',$text) ? "'".$text : $text;
 }
}
