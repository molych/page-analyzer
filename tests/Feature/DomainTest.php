<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\Http;

class DomainTest extends TestCase
{
    use RefreshDatabase;
   
    private $id;
    private $url = 'http://www.google.com';

    protected function  setUp(): void
    {
        parent::setUp();

        

        $this->id = DB::table('domains')->insertGetId(
            [
                'name' => $this->url,
                'created_at' =>  Carbon::now(),
                'updated_at' =>  Carbon::now()
            ]
        );

        DB::table('domain_checks')->insert(
            [
                'domain_id' => $this->id,
                'status_code' => 200,
                'h1' => null,
                'keywords' => null,
                'description' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }


    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testCreate()
    {
        $response = $this->get(route('domains.create'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));
        $response->assertOk();
    }

    public function testStore()
    {
        $domain['name'] = Factory::create()->url;
        $parsedName = parse_url($domain['name']);
        $domain['domain']['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $response = $this->post(route('domains.store'), $domain);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', $domain['domain']);
    }


    public function testCheck()
    {
        $testHtml = file_get_contents(realpath(__DIR__ . '/../fixtures/test.html'));
        Http::fake([
            $this->url => Http::response($testHtml, 200)
        ]);
        $response = $this->post(route('domains.check', ['id' => $this->id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domain_checks', [
            'h1' => 'test h1',
            'description' => 'test description'
        ]);
    }
}
