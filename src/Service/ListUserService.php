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
use Symfony\Contracts\HttpClient\ResponseInterface;

class ListUserService implements SendDataInterface
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
     * @return ResponseInterface|null
     */
    public function sendUserData(string $baseUrl, User $user): string
    {
        try {
            $response = $this->httpClient->request('GET', $baseUrl . ApiEndpointDictionary::API_USERS_GET_COLLECTION);
            return $response->getContent();
        } catch (TransportExceptionInterface $e) {
            throw new TransformationFailedException('Response Failed ' . $e->getMessage());
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