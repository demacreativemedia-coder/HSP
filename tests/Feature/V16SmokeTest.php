<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class V16SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_publicly_accessible(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_ad_landing_page_is_publicly_accessible(): void
    {
        $this->get('/lp/kerja-jepang')->assertOk();
    }

    public function test_admin_page_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
