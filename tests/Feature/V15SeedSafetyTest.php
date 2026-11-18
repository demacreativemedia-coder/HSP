<?php
namespace Tests\Feature;

use App\Models\SiteSetting;
use Database\Seeders\HikariSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class V15SeedSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_does_not_publish_unverified_legal_or_address_data(): void
    {
        $this->seed(HikariSeeder::class);
        $this->assertSame('', SiteSetting::where('key', 'legal_text')->value('value'));
        $this->assertSame('', SiteSetting::where('key', 'address')->value('value'));
        $this->assertSame('', SiteSetting::where('key', 'whatsapp')->value('value'));
    }
}
