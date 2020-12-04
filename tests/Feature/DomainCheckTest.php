<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DomainCheckTest extends TestCase
{

    /**
     * A domain check test.
     *
     *
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



    public function testStore()
    {
        $testHtml = file_get_contents(__DIR__ . '/../fixtures/test.html');
        if ($testHtml === false) {
            throw new \Exception('Wrong fixtures file');
        }
        Http::fake([
            $this->url => Http::response($testHtml, 200)
        ]);
        $response = $this->post(route('domains.checks.store', $this->id));
        $response->assertRedirect(route('domains.show', $this->id));
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $this->id,
            'status_code' => 200,
            'h1' => 'test h1',
            'keywords' => 'test keywords',
            'description' => 'description'
        ]);
    }
}
