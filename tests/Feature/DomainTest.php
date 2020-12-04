<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory;

class DomainTest extends TestCase
{
    private int $id;
    private string $url = 'http://www.google.com';
    private array $domain = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->id = DB::table('domains')->insertGetId(
            [
                'name' => $this->url,
                'created_at' =>  Carbon::now()->toDateTimeString(),
                'updated_at' =>  Carbon::now()->toDateTimeString()
            ]
        );
    }

     /**
     * Display the specified resource.
     *
     * @return void
     */

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

     /**
     * Display the specified resource.
     *
     * @return void
     */

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));
        $response->assertOk();
        $response->assertSee($this->url);
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */

    public function testStore()
    {
        $this->domain['name'] = Factory::create()->url;
        $parsedName = parse_url($this->domain['name']);
        $domain['domain']['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $response = $this->post(route('domains.store'), $domain);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', $domain['domain']);
    }
}
