<?php

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $response = $this->json('GET', '/products');

        $response
            ->assertStatus(200)
            ->assertJson([
                'created_at' => true,
            ]);
    }
}