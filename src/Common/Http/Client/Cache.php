<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\Common\Http\Client;

use Psr\SimpleCache\CacheInterface;
use SocialConnect\Common\Http\Response;

class Cache extends Client
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var int[]
     */
    protected $statusAccepted = [
        200 => 200,
        203 => 203,
        204 => 204,
        300 => 300,
        301 => 301,
        404 => 404,
        405 => 405,
        410 => 410,
        414 => 414,
        418 => 418,
        501 => 501,
    ];

    /**
     * @param Client $client
     * @param CacheInterface $cache
     */
    public function __construct(Client $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return string|null
     */
    protected function makeCacheKey($url, array $parameters = array())
    {
        $cacheKey = $url;

        if ($parameters) {
            foreach ($parameters as $key => $value) {
                $cacheKey .= $key . '-' . $value;
            }
        }

        return md5($cacheKey);
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $url, array $options = [], array $headers = [], string $method = Client::GET): Response
    {
        if ($method != Client::GET) {
            return $this->client->request($url, $options, $headers, $method);
        }

        $key = $this->makeCacheKey($url, $options);
        if ($key && $this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $response = $this->client->request($url, $options, $headers, $method);

        if (!isset($this->statusAccepted[$response->getStatusCode()])) {
            return $response;
        }

        $noCache = $response->hasHeader('Pragma') && $response->getHeader('Pragma') == 'no-cache';

        if (!$noCache && $response->hasHeader('Expires')) {
            // @link https://tools.ietf.org/html/rfc7234#section-5.3
            $expires = \DateTime::createFromFormat(\DateTime::RFC1123, $response->getHeader('Expires'));
            if ($expires !== false) {
                $lifeTime = $expires->getTimestamp() - time() - 60;

                if ($key) {
                    $this->cache->set($key, $response, $lifeTime);
                }
            }
        }

        return $response;
    }
}