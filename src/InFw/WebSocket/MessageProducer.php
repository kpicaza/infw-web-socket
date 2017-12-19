<?php

declare(strict_types=1);

namespace InFw\WebSocket;

use Throwable;
use WebSocket\Client;

class MessageProducer implements MessageProducerInterface
{
    /**
     * @var Client[]
     */
    private $clients;

    public function __construct(array $wsUrls)
    {
        $this->clients = array_map(function ($ws) {
            return new Client($ws);
        }, $wsUrls);
    }

    public function produce(string $msg): void
    {
        array_walk($this->clients, function (Client $client, $key) use ($msg) {
            try {
                $client->send($msg);
            } catch (Throwable $e) {
                throw new MessageProducerException(sprintf(
                    'Error sending message to client %s: %s',
                    $key,
                    $msg
                ));
            }
        });
    }
}
