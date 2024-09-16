<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

class CartManager extends ConnectorFacade
{
    public $logger;
    # Классу не нужно знать о данных соединения. Это не его область отвественности
    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
        parent::build();
    }

    # Лучше не допускать создание класса в незавершенном виде. Логер можно передать в конструктор
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart)
    {
        try {
            # переменная session_id не передается в метод явно
            $this->connector->set($cart, session_id());
        } catch (Exception $e) {
            $this->logger->error('Error');
        }
    }

    /**
     * @return ?Cart
     */
    public function getCart() # переменная session_id не передается в метод явно
    {
        try {
            return $this->connector->get(session_id());
        } catch (Exception $e) {
            # В методе класса принимается решение о том, что если при выполнение $this->connector->get(session_id());
            # произошла ошибка, то вернуть
            $this->logger->error('Error');
        }

        return new Cart(session_id(), []);
    }
}
