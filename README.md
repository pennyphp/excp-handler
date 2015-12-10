# Exception Handler
This project helps you to manage your exceptions with [whoops](https://github.com/filp/whoops).

## Whoops
> whoops is an error handler base/framework for PHP.
> Out-of-the-box, it provides a pretty error interface that helps
> you debug your web projects,
> but at heart it's a simple yet powerful stacked error handling system.

## Project
This project constains a Penny event listener ready to use in your penny application.

## Install
```
composer require penny/excp-handler
```

## Getting Started

The Penny Event Listener provided is a `Penny\ExcpHandler\EventListener\WhoopsListener`, which contains method `onError($e)` that can pass event that is a `EventInterface` implementation. You can set up [whoops](https://github.com/filp/whoops) handlers by provide `$current` and `$handlers` parameter in contructor:

```php
public function __construct($current = "html", $handlers = [])
```

You may use default implementation by leave them as empty when create a `Penny\ExcpHandler\EventListener\WhoopsListener` instance.

## Usage in Penny Skeleton Application

* composer require as [above](https://github.com/pennyphp/excp-handler#install)
* register in `config/di.php`:

```php
use Penny\ExcpHandler\EventListener\WhoopsListener;

return [
    'event_manager' => \DI\decorate(function($eventManager, $container) {
        $eventManager->attach("dispatch_error", [$container->get(WhoopsListener::class), "onError"]);
        // you may need to apply to '*_error' as well...
        
        // other event here...
        return $eventManager;
    }),
    
    WhoopsListener::class => \DI\object(WhoopsListener::class),
```

That's it. And you're ready to go.

