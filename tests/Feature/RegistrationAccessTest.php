<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
class RegistrationAccessTest extends TestCase {
 use RefreshDatabase;
 public function test_guests_cannot_open_registration_dashboard():void {$this->get('/admin/registrations')->assertRedirect('/admin/login');}
 public function test_guests_cannot_open_media_dashboard():void {$this->get('/admin/media')->assertRedirect('/admin/login');}
}
