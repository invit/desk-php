<?php

namespace Desk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class Client
{
    const VERSION = 'v2';

    public $httpClient;

    public function __construct($domain, $email = null, $password = null, $consumerKey = null, $consumerSecret = null, $token = null, $tokenSecret = null)
    {
        $this->httpClient = new GuzzleClient([
            'base_url' => [ 'https://{account}.desk.com', ['account' => $domain] ],
        ]);

        if (null !== $email) {
            $this->httpClient->setDefaultOption('auth', [$email, $password]);
        } else {
            $this->httpClient->setDefaultOption('auth', 'oauth');
            $oauth = new Oauth1([
                'consumer_key'    => $consumerKey,
                'consumer_secret' => $consumerSecret,
                'token'           => $token,
                'token_secret'    => $tokenSecret
            ]);
            $this->httpClient->getEmitter()->attach($oauth);
        }
    }

    /**
     * @param string $responder
     */
    public function get($url, $params = [], $responder = null)
    {
        return $this->request('GET', $url, $params, $responder);
    }

    /**
     * @param string $url
     * @param string $responder
     */
    public function post($url, $data = [], $responder = null)
    {
        return $this->request('POST', $url, $data, $responder);
    }

    /**
     * @param string $responder
     */
    public function patch($url, $data, $responder = null)
    {
        return $this->request('PATCH', $url, $data, $responder);
    }

    /**
     * @param string $method
     */
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
