<?php

namespace App\Service;

use App\Dictionnary\ApiEndpointDictionary;
use App\Entity\User;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FrontClientUserService
{
    /** @var HttpClientInterface $httpClient */
    private $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $baseUrl
     * @return ResponseInterface|null
     * @throws TransportExceptionInterface
     */
    public function userGetList(string $baseUrl): ?ResponseInterface
    {
        return  $this->httpClient->request('GET', $baseUrl.ApiEndpointDictionary::API_USERS_GET_LIST);
    }

    /**
     * @param User $user
     * @param string $baseUrl
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function sendDataToCreate(User $user, string $baseUrl): bool
    {
        $dt = new DateTime();
        $createdAt = $dt->format('Y-m-d H:i:s');
        $updateDate = $dt->format('Y-m-d H:i:s');
        $response = $this->userCreateClient(
            $baseUrl.ApiEndpointDictionary::API_USERS_GET_COLLECTION,
            $user,
            $createdAt,
            $updateDate
        );
        return !($response->getStatusCode() !== JsonResponse::HTTP_CREATED);
    }

    public function sendDataToUpdate(User $user, string $baseUrl)
    {
        $dt = new DateTime();
        $updateDate = $dt->format('Y-m-d H:i:s');
        $response = $this->userPutData(
            $baseUrl.ApiEndpointDictionary::API_USERS_GET_COLLECTION.'/'.$user->getId(),
            $user,
            $updateDate
        );
        return !($response->getStatusCode() !== JsonResponse::HTTP_OK);
    }


    public function showUserById(string $baseUrl, User $user): ?ResponseInterface
    {
        dump(ApiEndpointDictionary::API_USERS_GET_COLLECTION.'/'.$user->getId()); die;
        return  $this->httpClient->request(
            'GET',
            ApiEndpointDictionary::API_USERS_GET_COLLECTION.'/'.$user->getId()
        );
    }
    /**
     * @param string $endpoint
     * @param User $user
     * @param string $updated
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function userPutData(
        string $endpoint,
        User $user,
        string $updated
    ): ResponseInterface
    {
        $data['json'] = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'updatedate' => $updated
        ];
        return $this->httpClient->request('PUT', $endpoint, $data);
    }

    /**
     * @param string $endpoint
     * @param User $user
     * @param string $createdAt
     * @param string $updated
     * @return ResponseInterface|null
     * @throws TransportExceptionInterface
     */
    private function userCreateClient(
        string $endpoint,
        User $user,
        string $createdAt,
        string $updated
    ): ?ResponseInterface
    {
        $data['json'] = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'datecreate' => $createdAt,
            'updatedate' => $updated
        ];
        return $this->httpClient->request('POST', $endpoint, $data);
    }
}