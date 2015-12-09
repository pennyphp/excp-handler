<?php
namespace PennyTest\ExcpHandler;

use RuntimeException;
use Whoops\Handler\JsonResponseHandler;
use Exception;
use Penny\ExcpHandler\EventListener\WhoopsListener;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Assert;
use Penny\Event\EventInterface;
use stdClass;

class WhoopsListenerTest extends PHPUnit_Framework_TestCase
{
    public function testCostructorSupportsHandler()
    {
        $handler = new WhoopsListener("html");
        $this->assertSame("html", PHPUnit_Framework_Assert::readAttribute($handler, 'current'));
    }

    public function testDefaultHandler()
    {
        $handler = new WhoopsListener();
        $this->assertSame("html", PHPUnit_Framework_Assert::readAttribute($handler, 'current'));
    }
    
    public function testEventGetExceptionNotInstanceOfException()
    {
        $event = $this->prophesize(EventInterface::class);
        $event->getException()
              ->willReturn(new stdClass())
              ->shouldBeCalled();
              
        $handler = new WhoopsListener();
        $error = $handler->onError($event->reveal());
        
        $this->assertSame($event->reveal(), $error);
    }
    
    public function testOnErrorRun()
    {
        $this->setExpectedException(Exception::class);
        
        $event = $this->prophesize(EventInterface::class);
        $event->getException()
              ->willReturn(new Exception())
              ->shouldBeCalled();
              
        $handler = new WhoopsListener();
        $error = $handler->onError($event->reveal());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUseUnsupportedHandler()
    {
        $mock = $this->getMock(EventInterface::class);
        $handler = new WhoopsListener('notexist');
        $handler->onError($mock);
    }

    public function testAddHandler()
    {
        $handler = new WhoopsListener();
        $handler->addHandler("json", JsonResponseHandler::class);
        $this->assertCount(2, PHPUnit_Framework_Assert::readAttribute($handler, 'handlers'));
    }
}
