<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('content_pages',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->string('title');$t->string('meta_description',160)->nullable();$t->longText('body')->nullable();$t->boolean('published')->default(false);$t->timestamps();});
  Schema::create('articles',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->string('title');$t->string('excerpt',500)->nullable();$t->longText('body')->nullable();$t->boolean('published')->default(false);$t->timestamp('published_at')->nullable();$t->timestamps();});
  Schema::create('follow_ups',function(Blueprint $t){$t->id();$t->foreignId('lead_id')->constrained()->cascadeOnDelete();$t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();$t->dateTime('due_at');$t->string('status',20)->default('pending');$t->text('note')->nullable();$t->timestamps();});
 }
 public function down():void {Schema::dropIfExists('follow_ups');Schema::dropIfExists('articles');Schema::dropIfExists('content_pages');}
};