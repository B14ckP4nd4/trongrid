<?php

namespace BlackPanda\Trongrid;

use GuzzleHttp\Client;
use function PHPUnit\Framework\isJson;

class TronGridManager
{
    /*
     * set Nodes
     */
    protected $apiBaseUrl = 'https://api.trongrid.io/';

    /*
     * set Client
     */
    protected $api;

    public function __construct()
    {
        $this->api = new Client([
            'base_uri' => $this->apiBaseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Connection' => 'keep-alive',
                'Accept-Encoding' => 'gzip, deflate, br',
                'User-Agent' => 'Mozilla/5.0 (X11; U; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/103.0.5154.180 Chrome/103.0.5154.180 Safari/537.36',
            ],
        ]);
    }

    public function request(string $endPoint , array $params = [],string $method = 'GET')
    {
        $request = $this->api->request($method, $endPoint, [
            'query' => $params,
        ]);

        $result = $request->getBody()->getContents();

        return (isJson($result)) ? \json_decode($result) : $result;
    }
}
