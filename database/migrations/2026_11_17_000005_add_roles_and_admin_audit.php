<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::table('users',function(Blueprint $t){$t->string('role',32)->default('viewer')->index();});
  Schema::create('admin_audit_logs',function(Blueprint $t){$t->id();$t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();$t->string('action',100);$t->string('subject_type',100)->nullable();$t->string('subject_id',100)->nullable();$t->ipAddress('ip_address')->nullable();$t->timestamps();$t->index(['action','created_at']);});
 }
 public function down():void {Schema::dropIfExists('admin_audit_logs');Schema::table('users',fn(Blueprint $t)=>$t->dropColumn('role'));}
};
