<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('programs',function(Blueprint $t){$t->id();$t->string('name');$t->string('slug')->unique();$t->text('summary')->nullable();$t->longText('description')->nullable();$t->boolean('is_active')->default(true);$t->unsignedInteger('sort_order')->default(0);$t->timestamps();});
  Schema::create('site_settings',function(Blueprint $t){$t->string('key')->primary();$t->text('value')->nullable();});
  Schema::create('leads',function(Blueprint $t){$t->id();$t->string('name',120);$t->string('phone',20)->index();$t->foreignId('program_id')->nullable()->constrained()->nullOnDelete();$t->string('status',32)->default('baru')->index();$t->string('source',100)->nullable();$t->string('utm_source',100)->nullable();$t->string('utm_medium',100)->nullable();$t->string('utm_campaign',150)->nullable();$t->string('utm_content',150)->nullable();$t->string('landing_page',200)->nullable();$t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();$t->text('notes')->nullable();$t->timestamps();});
  Schema::create('lead_activities',function(Blueprint $t){$t->id();$t->foreignId('lead_id')->constrained()->cascadeOnDelete();$t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();$t->string('action',60);$t->text('details')->nullable();$t->timestamps();});
 }
 public function down():void {Schema::dropIfExists('lead_activities');Schema::dropIfExists('leads');Schema::dropIfExists('site_settings');Schema::dropIfExists('programs');}
};
