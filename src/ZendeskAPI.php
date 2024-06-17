<?php

require 'vendor/autoload.php';
class ZendeskAPI
{
    private $client;
    private $subdomain;
    private $email;
    private $token;

    public function __construct($subdomain, $email, $token)
    {
        $this->subdomain = $subdomain;
        $this->email = $email;
        $this->token = $token;

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => "https://{$this->subdomain}.zendesk.com/api/v2/",
            'auth' => [$this->email . '/token', $this->token],
        ]);
    }

    public function getTickets()
    {
        $response = $this->client->request('GET', 'tickets.json', [
            'query' => [
                'query' => 'type:ticket status<solved',
            ],
        ]);

        $tickets = json_decode($response->getBody()->getContents(), true);

        return $tickets['tickets'];
    }
}