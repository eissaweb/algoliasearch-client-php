<?php

namespace Algolia\AlgoliaSearch\Support;

final class Config
{
    const VERSION = '2.0.0';

    private static $httpClientConstructor;

    public static function getHttpClient()
    {
        if (!is_callable(self::$httpClientConstructor)) {
            if (class_exists('\GuzzleHttp\Client')) {
                self::setHttpClient(function () {
                    return new \Algolia\AlgoliaSearch\Http\Guzzle6HttpClient(new \GuzzleHttp\Client());
                });
            } else {
                self::setHttpClient(function () {
                    return new \Algolia\AlgoliaSearch\Http\Php53HttpClient();
                });
            }
        }

        return forward_static_call(self::$httpClientConstructor);
    }

    public static function setHttpClient($httpClientConstructor)
    {
        if (!is_callable($httpClientConstructor)) {
            throw new \InvalidArgumentException(
                'setHttpClient requires a function that build the HttpClient.'
            );
        }

        self::$httpClientConstructor = $httpClientConstructor;
    }
}