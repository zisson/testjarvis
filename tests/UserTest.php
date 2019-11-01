<?php

namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        try {
            static::createClient()->request('GET', 'api/users');
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
