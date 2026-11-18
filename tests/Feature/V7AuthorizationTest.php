<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
class V7AuthorizationTest extends TestCase {
 use RefreshDatabase;
 public function test_guest_cannot_access_admin():void {$this->get('/admin/leads')->assertRedirect('/admin/login');}
 public function test_viewer_cannot_access_leads():void {$user=User::factory()->create(['role'=>'viewer']);$this->actingAs($user)->get('/admin/leads')->assertForbidden();}
 public function test_editor_cannot_access_tracking():void {$user=User::factory()->create(['role'=>'editor']);$this->actingAs($user)->get('/admin/tracking')->assertForbidden();}
 public function test_director_can_access_audit():void {$user=User::factory()->create(['role'=>'director']);$this->actingAs($user)->get('/admin/audit')->assertOk();}
}
