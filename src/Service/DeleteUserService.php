<?php


namespace App\Service;


use App\Dictionnary\ApiEndpointDictionary;
use App\Entity\User;
use App\Interfaces\SendDataInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DeleteUserService implements SendDataInterface
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
        try {
            $response = $this->httpClient->request(
                'DELETE',
                $baseUrl . ApiEndpointDictionary::API_USERS_GET_COLLECTION . '/' . $user->getId()
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new TransformationFailedException('Response failed Status ' . $e->getMessage());
        }
        return $response->getStatusCode();
    }
}