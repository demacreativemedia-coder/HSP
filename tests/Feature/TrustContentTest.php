<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class TrustContentTest extends TestCase {
 use RefreshDatabase;
 public function test_guest_cannot_manage_faq():void {$this->post('/admin/faqs',['question'=>'Tes','answer'=>'Jawaban'])->assertRedirect('/admin/login');}
 public function test_admin_can_publish_faq():void {$user=User::factory()->create();$this->actingAs($user)->post('/admin/faqs',['question'=>'Berapa lama?','answer'=>'Tergantung program.','published'=>1])->assertRedirect();$this->assertDatabaseHas('faqs',['question'=>'Berapa lama?','published'=>1]);}
 public function test_testimonial_cannot_be_published_without_verified_consent():void {$user=User::factory()->create();$this->actingAs($user)->post('/admin/testimonials',['display_name'=>'Contoh','quote'=>'Testimoni','published'=>1])->assertRedirect();$this->assertDatabaseHas('testimonials',['display_name'=>'Contoh','published'=>0,'consent_verified'=>0]);}
}
