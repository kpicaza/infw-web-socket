<?php

declare(strict_types=1);

namespace InFw\WebSocket;

interface MessageProducerInterface
{
    public function produce(string $msg): void;
}
