<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_company_list()
    {
        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/company');

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_it_creates_new_company()
    {
        $payload = [
            'name' => 'Test Corp',
            'edrpou' => '12345678',
            'address' => 'Kyiv',
        ];

        $response = $this->postJson('/api/v1/company', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['status' => 'created']);

        $this->assertDatabaseHas('companies', [
            'edrpou' => '12345678',
        ]);
    }

    public function test_it_detects_duplicate_on_store()
    {
        Company::create([
            'name' => 'Test Corp',
            'edrpou' => '12345678',
            'address' => 'Kyiv',
        ]);

        $response = $this->postJson('/api/v1/company', [
            'name' => 'Test Corp',
            'edrpou' => '12345678',
            'address' => 'Kyiv',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['status' => 'duplicate']);
    }

    public function test_it_updates_company_and_creates_new_version()
    {
        $company = Company::create([
            'name' => 'Test Corp',
            'edrpou' => '12345678',
            'address' => 'Kyiv',
        ]);

        $oldVersionCount = $company->versions()->count();

        $response = $this->postJson('/api/v1/company', [
            'name' => 'New Name',
            'edrpou' => '12345678',
            'address' => 'Kyiv Updated',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['status' => 'updated']);

        $company->refresh();

        $this->assertEquals(
            $oldVersionCount + 1,
            $company->versions()->count()
        );
    }

    public function test_it_shows_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/v1/company/{$company->id}");

        $response->assertOk()
            ->assertJsonStructure(['data' => ['id', 'name', 'edrpou']]);
    }

    public function test_it_returns_not_found_with_localization()
    {
        $response = $this->getJson('/api/v1/company/999');

        $response->assertNotFound()
            ->assertJsonFragment([
                'message' => __('api.company_not_found'),
            ]);
    }

    public function test_it_deletes_company_and_versions()
    {
        $company = Company::factory()->create();

        $company->versions()->create([
            'version' => 1,
            'data' => [],
        ]);

        $response = $this->deleteJson("/api/v1/company/{$company->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        $this->assertDatabaseMissing('versions', [
            'versionable_id' => $company->id,
            'versionable_type' => Company::class,
        ]);
    }
}
