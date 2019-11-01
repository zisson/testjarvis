<?php


namespace App\Service;


use App\Dictionnary\ApiEndpointDictionary;
use App\Entity\User;
use App\Interfaces\SendDataInterface;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateUserService implements SendDataInterface
{
    /** @var HttpClientInterface $httpClient */
    private $httpClient;
    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * @param HttpClientInterface $httpClient
     * @param LoggerInterface $logger
     */
    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @param string $baseUrl
     * @param User $user
     * @return int
     * @throws TransportExceptionInterface
     */
    public function sendUserData(string $baseUrl, User $user): int
    {
        $dt = new DateTime();
        $createdAt = $dt->format('Y-m-d H:i:s');
        $updateDate = $dt->format('Y-m-d H:i:s');
        $response = $this->userCreateClient(
            $baseUrl . ApiEndpointDictionary::API_USERS_GET_COLLECTION,
            $user,
            $createdAt,
            $updateDate
        );
        return $response;
    }

    /**
     * @param string $endpoint
     * @param User $user
     * @param string $createdAt
     * @param string $updated
     * @return int
     * @throws TransportExceptionInterface
     */
    private function userCreateClient(
        string $endpoint,
        User $user,
        string $createdAt,
        string $updated
    ): int
    {
        $data['json'] = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'datecreate' => $createdAt,
            'updatedate' => $updated
        ];
        try {
            $response = $this->httpClient->request('POST', $endpoint, $data);

        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new TransformationFailedException('Response failed Status ' . $e->getMessage());
        }
        return $response->getStatusCode();
    }
}