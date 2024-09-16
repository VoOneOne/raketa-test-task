<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Domain\Cart;
use Redis;
use RedisException;

class Connector
{
    private Redis $redis;

    public function __construct($redis) # отсутвуте определение типов параметров
    {
        return $this->redis = $redis;
    }

    /**
     * @throws ConnectorException
     */
    // ошибка в типе параметка
    public function get(Cart $key) # отсутвуете определение возвращаемого типа
    {
        try {
            return unserialize($this->redis->get($key));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, Cart $value) # отсутвуете определение возвращаемого типа
    {
        try {
            $this->redis->setex($key, 24 * 60 * 60, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }
    # отсутвует облок throws
    public function has($key): bool
    {
        # отсутвует обработка исключения, как в других методах
        return $this->redis->exists($key);
    }
}
