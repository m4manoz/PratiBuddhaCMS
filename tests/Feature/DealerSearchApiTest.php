<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\Province;
use App\Models\Zone;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealerSearchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dealer_search_returns_filtered_results(): void
    {
        $province = Province::create(['name' => 'Test Province']);
        $zone = Zone::create(['name' => 'Test Zone', 'province_id' => $province->id]);
        $area = Area::create(['name' => 'Test Area', 'zone_id' => $zone->id]);

        Dealer::create([
            'name' => 'Alpha Electronics',
            'contact_number' => '01-111',
            'email' => 'alpha@dealer.com',
            'address' => 'Main Road',
            'latitude' => 27.7000,
            'longitude' => 85.3000,
            'area_id' => $area->id,
            'is_active' => true,
        ]);

        Dealer::create([
            'name' => 'Beta Store',
            'contact_number' => '01-222',
            'email' => 'beta@dealer.com',
            'address' => 'City Center',
            'area_id' => $area->id,
            'is_active' => false,
        ]);

        $response = $this->getJson(route('dealers.search', ['q' => 'Alpha']));

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('data')));
    }
}
