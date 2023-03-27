<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ProductTest extends TestCase
{
    /**
     * Test the product index page.
     *
     * @return void
     */
    public function testProductIndex()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }
}
