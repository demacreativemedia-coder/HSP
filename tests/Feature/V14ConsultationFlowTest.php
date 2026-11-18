<?php
namespace Tests\Feature;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class V14ConsultationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_consultation_is_saved_and_redirects_to_confirmation_without_official_whatsapp(): void
    {
        SiteSetting::updateOrCreate(['key' => 'whatsapp'], ['value' => '']);
        $response = $this->from('/lp/kerja-jepang')->post('/konsultasi', [
            'name' => 'Peserta Pengujian',
            'phone' => '081234567890',
            'website' => '',
            'utm_source' => 'google',
        ]);
        $response->assertRedirect(route('consultation.received'));
        $this->assertDatabaseHas('leads', [
            'name' => 'Peserta Pengujian',
            'phone' => '6281234567890',
            'utm_source' => 'google',
        ]);
        $lead = Lead::where('phone', '6281234567890')->firstOrFail();
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'action' => 'created']);
    }

    public function test_invalid_consultation_does_not_create_lead(): void
    {
        $this->post('/konsultasi', [
            'name' => 'A', 'phone' => '123', 'website' => '',
        ])->assertSessionHasErrors();
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_confirmation_page_requires_session_reference(): void
    {
        $this->get('/konsultasi/diterima')->assertNotFound();
    }

    public function test_valid_official_whatsapp_redirect_contains_reference_not_private_phone(): void
    {
        SiteSetting::updateOrCreate(['key' => 'whatsapp'], ['value' => '628111111111']);
        $response = $this->post('/konsultasi', [
            'name' => 'Peserta Pengujian', 'phone' => '081234567890', 'website' => '',
        ]);
        $location = $response->headers->get('Location', '');
        $this->assertStringStartsWith('https://wa.me/628111111111?text=', $location);
        $this->assertStringContainsString('HIK-', urldecode($location));
        $this->assertStringNotContainsString('6281234567890', $location);
        $this->assertDatabaseCount('leads', 1);
    }
}
