<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A home test example.
     *
     * @return void
     */

    public function testHome()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
