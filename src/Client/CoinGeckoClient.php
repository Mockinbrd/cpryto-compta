<?php

namespace App\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGeckoClient {

    private HttpClientInterface $client;
    private SerializerInterface $serializer;

    public function __construct(string $url, SerializerInterface $serializer)
    {
        $this->client = HttpClient::createForBaseUri($url, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $this->serializer = $serializer;
    }

    public function ping(): array
    {
        try {
            $response = $this->client->request(Request::METHOD_GET, 'ping');
        } catch (TransportExceptionInterface $exception) {
            throw new \RuntimeException('Error . ' . $exception->getMessage());
        }
        return $response->toArray(false);
    }

    /*
     * List all available coins
     */
    public function list(): array
    {
        try {
            $response = $this->client->request(Request::METHOD_GET, 'coins/list');
        } catch (TransportExceptionInterface $exception) {
            throw new \RuntimeException('Error . ' . $exception->getMessage());
        }
        return $response->toArray(false);
    }



}