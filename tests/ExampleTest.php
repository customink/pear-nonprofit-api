<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/test');

        $this->assertEquals(
            $this->response->getContent(), 'here!!!'
        );
    }
}
