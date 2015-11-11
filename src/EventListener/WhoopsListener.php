<?php
namespace Penny\ExcpHandler\EventListener;

use Exception;
use RuntimeException;
use Whoops\Handler;
use Whoops\Run;
use Penny\Event\PennyEventInterface;

class WhoopsListener
{
    private $handlers = [
        "html" => Handler\PrettyPageHandler::class
    ];

    private $handler;

    public function __construct($handler = "html")
    {
        $this->handler = $handler;
    }

    public function addHandler($name, $className)
    {
        $this->handlers[$name] = $className;
    }

    public function onError(PennyEventInterface $event)
    {
        if (!in_array($this->handler, $this->handlers)) {
            throw new RuntimeException("{$this->handler} is not supported at it!");
        }

        if (!$event->getException() instanceof Exception) {
            return $event;
        }

        $whoops = new Run();

        $whoops->pushHandler(new $this->handlers[$this->handler]());

        $whoops->pushHandler(function ($exception, $inspector, $run) use ($event) {
            $run->sendHttpCode($exception->getCode());
        });
        $whoops->register();

        throw $event->getException();
    }
}
