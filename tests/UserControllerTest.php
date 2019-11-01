<?php

namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserControllerTest extends ApiTestCase
{
    public function test_Index_succes(): void
    {
        try {
            static::createClient()->request('GET', 'api/users');
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function test_Index_failed(): void
    {
        try {
            static::createClient()->request('GET', 'api/user');
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseHeaderNotSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function test_new_succes()
    {
        $data['json'] = [
            'firstname' => 'toto',
            'lastname' => 'toto',
            'datecreate' => '2019-12-12T22:10:31+00:00',
            'updatedate' => '2019-12-12T22:10:31+00:00'
        ];
        try {
            static::createClient()->request('POST', 'api/user', $data);
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseHeaderNotSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function test_delete_succes()
    {
        try {
            static::createClient()->request('DELETE', 'api/users/2');
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseStatusCodeSame(204, '');
    }
}
