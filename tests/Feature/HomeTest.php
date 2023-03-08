<?php

namespace App\Tests\Feature;

it('has a homepage', function() {
    $client = $this::createClient();
    $client->request('GET', '/');

    $this->assertResponseStatusCodeSame(200);
});