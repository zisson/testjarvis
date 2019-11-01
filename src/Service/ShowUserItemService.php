<?php


namespace App\Service;


use App\Dictionnary\ApiEndpointDictionary;
use App\Entity\User;
use App\Interfaces\SendDataInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ShowUserItemService implements SendDataInterface
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
     * @return string
     */
    public function sendUserData(string $baseUrl, User $user): string
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $baseUrl . ApiEndpointDictionary::API_USERS_GET_COLLECTION . '/' . $user->getId()
            );
            return $response->getContent();
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new TransformationFailedException('Response failed Status ' . $e->getMessage());
        } catch (ClientExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        } catch (RedirectionExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        } catch (ServerExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
        return '';
    }
}