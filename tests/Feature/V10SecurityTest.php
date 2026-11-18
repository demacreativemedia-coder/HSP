<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class V10SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_export_leads(): void
    {
        $this->get('/admin/leads/export')->assertRedirect('/admin/login');
    }

    public function test_unauthenticated_user_cannot_publish_media(): void
    {
        $this->patch('/admin/media/1/publish', ['confirm_review' => '1'])->assertRedirect('/admin/login');
    }

    public function test_lead_honeypot_rejects_spam(): void
    {
        $this->post('/konsultasi', [
            'name' => 'Pengunjung Uji',
            'phone' => '081234567890',
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');
    }

    public function test_invalid_whatsapp_number_is_rejected(): void
    {
        $this->post('/konsultasi', [
            'name' => 'Pengunjung Uji',
            'phone' => '12345',
            'website' => '',
        ])->assertSessionHasErrors('phone');
    }
}
