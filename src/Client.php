<?php

namespace Desk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Stream\Stream;

class Client {

    const VERSION = 'v2';

    public $httpClient;

    public function __construct($domain, $email, $password)
    {
        $this->httpClient = new GuzzleClient([
            'base_url' => [ 'https://{account}.desk.com', ['account' => $domain] ],
            'defaults' => [
                'auth' => [ $email, $password ]
            ]
        ]);
    }

    public function get($url, $params = [], $responder = null)
    {
        return $this->request('GET', $url, $params, $responder);
    }

    public function post($url, $data = [], $responder = null)
    {
        return $this->request('POST', $url, $data, $responder);
    }

    public function patch($url, $data, $responder = null)
    {
        return $this->request('PATCH', $url, $data, $responder);
    }

    private function request($method, $url, $params, $responder = null)
    {
        $method = strtolower($method);

        if (substr($url, 0, 1) !== '/') {
            $url = '/api/' . self::VERSION . '/' . $url;
        }

        $request = $this->httpClient->createRequest($method, $url);

        if ($method === 'get') {
            foreach ($params as $key => $value) {
                $request->getQuery()->set($key, $value);
            }
        } else {
            $request->setBody(Stream::factory(json_encode($params)));
        }

        $response = $this->httpClient->send($request);

        $data = $response->json();

        if (is_null($responder)) {
            return $data;
        } else {
            return new $responder($data);
        }
    }
}