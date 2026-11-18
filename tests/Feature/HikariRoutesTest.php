<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SiteSetting;
class HikariRoutesTest extends TestCase {
 use RefreshDatabase;
 public function test_public_home_is_available():void{$this->get('/')->assertOk();}
 public function test_admin_requires_login():void{$this->get('/admin')->assertRedirect('/admin/login');}
 public function test_lead_submission_validates_input():void{$this->post('/konsultasi',['name'=>'A','phone'=>'abc','website'=>''])->assertSessionHasErrors();}
}
