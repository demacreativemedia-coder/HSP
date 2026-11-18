<?php
namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
class HikariAuthorizationServiceProvider extends ServiceProvider {
 public function boot():void {
  Gate::before(function($user,$ability){return $user->role==='super_admin'?true:null;});
  $rules=[
   'dashboard.view'=>['director','marketing','enrollment','editor','viewer'],
   'leads.view'=>['director','marketing','enrollment'],
   'leads.edit'=>['enrollment'],
   'leads.export'=>['director','marketing','enrollment'],
   'registrations.manage'=>['enrollment'],
   'content.manage'=>['editor'],
   'media.manage'=>['editor'],
   'tracking.manage'=>['marketing'],
   'settings.manage'=>[],
   'audit.view'=>['director'],
  ];
  foreach($rules as $ability=>$roles){Gate::define($ability,fn($user)=>in_array($user->role,$roles,true));}
 }
}
