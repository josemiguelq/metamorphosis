<?php
namespace Tests\Dummies;

use Exception;
use Metamorphosis\Record;
use Metamorphosis\TopicHandler\Consumer\AbstractHandler;

class ConsumerHandlerDummy extends AbstractHandler
{
    public function handle(Record $data): void
    {
    }

    public function failed(Exception $exception): void
    {
    }
}
