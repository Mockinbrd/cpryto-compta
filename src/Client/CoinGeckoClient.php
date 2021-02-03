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

    /*
     * Give the history of a coin for a given date
     */
    public function history(string $id,\DateTimeImmutable $date): array
    {
        try {
            $response = $this->client->request(Request::METHOD_GET, sprintf('coins/%s/history?date=%s&localization=false', $id, $date->format('d-m-Y')));
        } catch (TransportExceptionInterface $exception) {
            throw new \RuntimeException('Error . ' . $exception->getMessage());
        }
        return $response->toArray(false);
    }



}