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
use Symfony\Contracts\HttpClient\ResponseInterface;

class UpdateUserService implements SendDataInterface
{
    /** @var HttpClientInterface $httpClient */
    private $httpClient;
    /** @var LoggerInterface $logger */
    private $logger;

    /** @param HttpClientInterface $httpClient */
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
        $updateDate = $dt->format('Y-m-d H:i:s');
        try {
            $response = $this->userPutData(
                $baseUrl . ApiEndpointDictionary::API_USERS_GET_COLLECTION . '/' . $user->getId(),
                $user,
                $updateDate
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new TransformationFailedException('Response Failed to update ' . $e->getMessage());
        }
        return $response->getStatusCode();
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
}