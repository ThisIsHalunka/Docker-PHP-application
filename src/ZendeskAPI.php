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

        $this->client = new GuzzleHttp\Client([
            'base_uri' => "https://{$this->subdomain}.zendesk.com/api/v2/",
            'auth' => [$this->email . '/token', $this->token],
        ]);
    }

    public function getTickets()
    {
        $tickets = [];
        $page = 1;
        $perPage = 100;

        while (true) {
            $response = $this->client->request('GET', 'tickets.json', [
                'query' => [
                    'query' => 'type:ticket status<solved',
                    'page' => $page,
                    'per_page' => $perPage,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $tickets = array_merge($tickets, $data['tickets']);

            if (count($data['tickets']) < $perPage) {
                break;
            }

            $page++;
        }

        return $tickets;
    }
}