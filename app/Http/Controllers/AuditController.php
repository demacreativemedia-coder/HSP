<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class AuditController extends Controller {
 public function index(){return view('admin.audit',['logs'=>DB::table('admin_audit_logs')->orderByDesc('id')->paginate(30)]);}
}
