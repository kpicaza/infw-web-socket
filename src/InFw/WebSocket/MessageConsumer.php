<?php

declare(strict_types=1);

namespace InFw\WebSocket;

use Ratchet\ConnectionInterface;

class MessageConsumer implements MessageConsumerInterface
{
    /**
     * @var array
     */
    protected $connections = [];

    /**
     * A new web socket connection
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->connections[] = $conn;
        $conn->send('..:: Hello from the Notification Center ::..');
        echo "New connection \n";
    }

    /**
     * Handle message sending
     *
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg, callable $beforeSend = null): void
    {
        if (empty($msg)) {
            //error
            $from->send("You're not able to do that!");
            $from->close();
        }

        if ($beforeSend) {
            $beforeSend($from, $msg);
            $from->send($msg);
        } else {
            $from->send($msg);
        }

        echo 'Message: ' . $msg . ' was dispatched.';
    }

    /**
     * A connection is closed
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn): void
    {
        foreach ($this->connections as $key => $connElement) {
            if ($conn === $connElement) {
                unset($this->connections[$key]);
                break;
            }
        }
    }

    /**
     * Error handling
     *
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $conn->send("Error : " . $e->getMessage());
        $conn->close();
    }
}
