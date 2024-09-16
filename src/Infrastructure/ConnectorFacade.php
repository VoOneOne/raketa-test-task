<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;

# Имя класса, namespace не даёт понять, что мы подключаемся к Redis
# В современных фреймворках есть возможность сконфигурировать подключение и передать его явно в конструктор как сервис.

class ConnectorFacade
{
    public string $host;
    public int $port = 6379; # Зачем?
    public ?string $password = null;
    public ?int $dbindex = null;

    public $connector;


    public function __construct($host, $port, $password, $dbindex) # отсутствует описание типов
    {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->dbindex = $dbindex;
    }

    protected function build(): void
    {

        $redis = new Redis();

        try {
            $isConnected = $redis->isConnected();
            if (! $isConnected && $redis->ping('Pong')) {
                $isConnected = $redis->connect(
                    $this->host,
                    $this->port,
                );
            }
        } catch (RedisException) {
        }

        if ($isConnected) {
            $redis->auth($this->password);
            $redis->select($this->dbindex);
            $this->connector = new Connector($redis);
        }
    }
}
