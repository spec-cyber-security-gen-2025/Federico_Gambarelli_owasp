<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpService
{
    protected $client;
    protected $allowedDomains = ['localhost','127.0.0.1','newsapi.org'];
    protected $allowedProtocols = ['http', 'https'];
    protected $refererHeader; // Intestazione Referer

    public function __construct()
    {
        $this->refererHeader = config('app.url');
        $this->client = new Client();
    }

    public function getRequest($url)
    {
        $parsedUrl = parse_url($url);

        // Validate protocol
        if (!in_array($parsedUrl['scheme'], $this->allowedProtocols)) {
            return 'Protocol not allowed';
        }
       
        // Validate domain
        if (!isset($parsedUrl['host']) || !in_array($parsedUrl['host'], $this->allowedDomains)) {
            return 'Domain not allowed';
        }

        // Aggiungi l'intestazione Referer per le richieste al server locale
        $options = [];
        if ($parsedUrl['host'] === 'localhost') {
            $options['headers'] = ['Referer' => $this->refererHeader];
        }

        try {
            $response = $this->client->request('GET', $url, $options);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return 'Something went wrong: ' . $e->getMessage();
        }
    }
}
