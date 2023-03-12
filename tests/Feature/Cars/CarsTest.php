<?php
declare(strict_types=1);

it('shows a list of cars', function() {
    client()->request('GET', '/');
    $this->assertResponseStatusCodeSame(200);
});

it('it shows cars create form', function() {
    client()->request('GET', '/create');
    $this->assertResponseStatusCodeSame(200);
});