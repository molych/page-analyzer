<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\Http;

class DomainCheckTest extends TestCase
{
    use RefreshDatabase;

    private $id;
    private $url = 'http://www.google.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->id = DB::table('domains')->insertGetId(
            [
                'name' => $this->url,
                'created_at' =>  Carbon::now(),
                'updated_at' =>  Carbon::now()
            ]
        );
    }

    public function testStore()
    {
        $testHtml = file_get_contents(__DIR__ . '/../fixtures/test.html');

        Http::fake([
            $this->url => Http::response($testHtml, 200)
        ]);

        $response = $this->post(route('domainChecks.store', ['id' => $this->id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domain_checks', [
            'h1' => 'test h1',
            'description' => 'description'
        ]);
    }
}
