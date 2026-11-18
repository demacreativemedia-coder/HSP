<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class V17IntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_and_landing_pages_are_accessible(): void
    {
        $this->get('/')->assertOk();
        $this->get('/lp/kerja-jepang')->assertOk();
    }

    public function test_guest_cannot_access_crm_or_registrations(): void
    {
        $this->get('/admin/leads')->assertRedirect(route('login'));
        $this->get('/admin/registrations')->assertRedirect(route('login'));
    }

    public function test_editor_cannot_view_lead_records(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->get('/admin/leads')->assertForbidden();
    }

    public function test_enrollment_staff_can_view_leads(): void
    {
        $staff = User::factory()->create(['role' => 'enrollment']);
        $this->actingAs($staff)->get('/admin/leads')->assertOk();
    }

    public function test_login_rejects_wrong_password(): void
    {
        User::factory()->create(['email'=>'admin@example.test','password'=>Hash::make('valid-password'),'role'=>'super_admin']);
        $this->post('/admin/login',['email'=>'admin@example.test','password'=>'wrong-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_rejects_unprivileged_account(): void
    {
        User::factory()->create(['email'=>'viewer@example.test','password'=>Hash::make('valid-password'),'role'=>'unassigned']);
        $this->post('/admin/login',['email'=>'viewer@example.test','password'=>'valid-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_valid_consultation_creates_lead(): void
    {
        $this->post('/konsultasi',['name'=>'Peserta Uji','phone'=>'081234567890','website'=>''])
            ->assertRedirect();
        $this->assertDatabaseHas('leads',['phone'=>'6281234567890']);
    }
}
