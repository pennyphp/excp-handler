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

    private $current;

    public function __construct($current = "html", $handlers = [])
    {
        $this->current = $current;
        $this->handlers = $handlers;
        if (count($this->handlers) == 0) {
            $this->handlers['html'] = new Handler\PrettyPageHandler();
        }
    }

    public function addHandler($name, $handler)
    {
        $this->handlers[$name] = $handler;
    }

    public function onError(PennyEventInterface $event)
    {
        if (!in_array($this->current, $this->handlers)) {
            throw new RuntimeException(
                "{$this->current} is not supported. Add it use addHandler({$this->current}, <className>) func."
            );
        }

        if (!$event->getException() instanceof Exception) {
            return $event;
        }

        $whoops = new Run();

        $whoops->pushHandler($this->handlers[$this->current]);

        $whoops->pushHandler(function ($exception, $inspector, $run) use ($event) {
            $run->sendHttpCode($exception->getCode());
        });
        $whoops->register();

        throw $event->getException();
    }
}
