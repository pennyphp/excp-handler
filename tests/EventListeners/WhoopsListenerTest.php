<?php
namespace PennyTest\ExcpHandler;

use RuntimeException;
use Whoops\Handler\JsonResponseHandler;
use Penny\ExcpHandler\EventListener\WhoopsListener;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Assert;
use Penny\Event\PennyEventInterface;

class WhoopsListenerTest extends PHPUnit_Framework_TestCase
{
    public function testCostructorSupportsHandler()
    {
        $handler = new WhoopsListener("html");
        $this->assertSame("html", PHPUnit_Framework_Assert::readAttribute($handler, 'handler'));
    }

    public function testDefaultHandler()
    {
        $handler = new WhoopsListener();
        $this->assertSame("html", PHPUnit_Framework_Assert::readAttribute($handler, 'handler'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUseUnsupportedHandler()
    {
        $mock = $this->getMock(PennyEventInterface::class);
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
