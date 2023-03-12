<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase
{
    public static function client(array $options = [], array $server = []): KernelBrowser
    {
        return self::createClient($options, $server);
    }
}