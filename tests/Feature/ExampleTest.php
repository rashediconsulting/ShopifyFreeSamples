<?php

namespace RashediConsulting\ShopifyFreeSamples\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get(route("free-samples"));

        $response->assertStatus(200);
    }
}
