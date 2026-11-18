<?php
namespace Tests\Feature;
use Tests\TestCase;
class TrackingAccessTest extends TestCase {
 public function test_tracking_admin_route_requires_authentication():void {
  $this->get('/admin/tracking')->assertRedirect('/admin/login');
 }
}
