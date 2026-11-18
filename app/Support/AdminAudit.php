<?php
namespace App\Support;
use Illuminate\Support\Facades\DB;
class AdminAudit {
 public static function record(string $action,?object $subject=null):void {
  DB::table('admin_audit_logs')->insert(['user_id'=>auth()->id(),'action'=>$action,'subject_type'=>$subject?get_class($subject):null,'subject_id'=>$subject?(string)$subject->getKey():null,'ip_address'=>request()->ip(),'created_at'=>now(),'updated_at'=>now()]);
 }
}
