<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory;

class DomainTest extends TestCase
{
    /**
     * A domain test.
     *
     * @param  int  $this->id
     * @param string $this->$url
     * @return void
     */

    private $id;
    private $url = 'http://www.google.com';

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

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));
        $response->assertOk();
        $response->assertSee($this->url);
    }

    /**
     * Display the specified resource.
     *
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */

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
}
