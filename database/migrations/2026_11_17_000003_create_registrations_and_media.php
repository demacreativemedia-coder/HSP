<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('registrations',function(Blueprint $t){$t->id();$t->foreignId('lead_id')->unique()->constrained()->restrictOnDelete();$t->foreignId('program_id')->nullable()->constrained()->nullOnDelete();$t->string('registration_code',40)->unique();$t->string('status',30)->default('pending');$t->date('registered_on')->nullable();$t->text('admin_notes')->nullable();$t->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();$t->timestamps();});
  Schema::create('media_assets',function(Blueprint $t){$t->id();$t->string('title',150);$t->string('path');$t->string('mime_type',80);$t->unsignedBigInteger('size_bytes');$t->string('alt_text',200)->nullable();$t->boolean('is_public')->default(false);$t->boolean('consent_verified')->default(false);$t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();$t->timestamps();});
 }
 public function down():void {Schema::dropIfExists('media_assets');Schema::dropIfExists('registrations');}
};
