<?php

namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserControllerTest extends ApiTestCase
{
    /** @var MockObject $user */
    private $user;
    /** @var MockObject $datetime */
    private $datetime;

    protected function setUp(): void
    {
        $this->datetime = $this->createMock(DateTime::class);
        $this->user = $this->createMock(User::class);
        $this->user->method('getFirstname')
            ->willReturn('toto');
        $this->user->method('getLastname')
            ->willReturn('toto Lastname');
        $this->user->method('getupdatedate')
            ->willReturn($this->datetime->setDate(2019, 2, 3));
        $this->user->method('getdatecreate')
            ->willReturn($this->datetime->setDate(2019, 2, 3));

        $data['json'] = [
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'datecreate' => $this->user->getupdatedate(),
            'updatedate' => $this->user->getdatecreate()
        ];
    }

    public function test_show_succes(): void
    {
        try {
            static::createClient()->request('GET', 'api/users');
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function test_show_failed(): void
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
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'datecreate' => $this->user->getupdatedate(),
            'updatedate' => $this->user->getdatecreate()
        ];
        try {
            static::createClient()->request('POST', 'api/users', $data);
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function test_edit_user(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(User::class, ['lastname' => $this->user->getLastname()]);
        try {
            $client->request('PUT', $iri, ['json' => [
                'lastname' => 'toto Lastname',
            ]]);
        } catch (TransportExceptionInterface $e) {
        }
        self::assertResponseIsSuccessful();
    }

    public function test_delete_succes()
    {
        $client = static::createClient();
        $iri = $this->findIriBy(User::class, ['lastname' => $this->user->getLastname()]);
        try {
            $client->request('DELETE', $iri);
        } catch (TransportExceptionInterface $e) {
        }

        self::assertResponseStatusCodeSame(204, '');
    }
}
