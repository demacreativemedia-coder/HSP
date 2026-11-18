<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('faqs',function(Blueprint $t){$t->id();$t->string('question',250);$t->text('answer');$t->unsignedInteger('sort_order')->default(0);$t->boolean('published')->default(false);$t->timestamps();});
  Schema::create('testimonials',function(Blueprint $t){$t->id();$t->string('display_name',100);$t->string('role',100)->nullable();$t->text('quote');$t->boolean('consent_verified')->default(false);$t->boolean('published')->default(false);$t->unsignedInteger('sort_order')->default(0);$t->timestamps();});
 }
 public function down():void {Schema::dropIfExists('testimonials');Schema::dropIfExists('faqs');}
};
