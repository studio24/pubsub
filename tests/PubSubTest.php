<?php

use studio24\PubSub\PubSub;

class PubSubTest extends PHPUnit_Framework_TestCase {

    /**
     * @requires PHP 7.0
     * @expectedException TypeError
     */
    public function testException()
    {
        $fake = "fake callback";
        PubSub::subscribe('test', $fake);
    }

    public function testMultipleArguments()
    {
        PubSub::subscribe('test1', function($arg1, $arg2, $arg3){
            $this->assertNotEmpty($arg1);
            $this->assertNotEmpty($arg2);
            $this->assertEquals('gouda', $arg1);
            $this->assertNotSame('cheddar', $arg1);
            $this->assertEquals('cheddar', $arg2);
            $this->assertEquals('parmesan', $arg3);
        });
        PubSub::publish('test1', 'gouda', 'cheddar', 'parmesan');
    }

    public function testOrderOfSubscribe()
    {
        $tracker = [];
        PubSub::subscribe('test2', function() use (&$tracker){
            $tracker[] = 2;
            $this->assertEquals([1,2], $tracker);
        }, 2);
        PubSub::subscribe('test2', function() use (&$tracker){
            $tracker[] = 1;
            $this->assertEquals([1], $tracker);
        }, 1);
        PubSub::publish('test2');

        $tracker = [];
        PubSub::subscribe('test3', function() use (&$tracker){
            $tracker[] = 2;
            $this->assertEquals([1,2], $tracker);
        });
        PubSub::subscribe('test3', function() use (&$tracker){
            $tracker[] = 3;
            $this->assertEquals([1,2,3], $tracker);
        });
        PubSub::subscribe('test3', function() use (&$tracker){
            $tracker[] = 1;
            $this->assertEquals([1], $tracker);
        }, 5);
        PubSub::publish('test3');
    }

    public function testNonExistentEvent()
    {
        $var = 1;
        PubSub::subscribe('fake-event1', function() use (&$var) {
            $var = 2;
        });
        PubSub::publish('fake-event2');

        $this->assertEquals(1, $var);
    }

    /**
     * @expectedException \studio24\PubSub\PubSubException
     */
    public function testInvalidEventNameEmptyString()
    {
        PubSub::subscribe('', function(){});
    }

    /**
     * @expectedException \studio24\PubSub\PubSubException
     */
    public function testInvalidEventNameInt()
    {
        PubSub::subscribe(1, function(){});
    }

    /**
     * @expectedException \studio24\PubSub\PubSubException
     */
    public function testInvalidEventNameArray()
    {
        PubSub::subscribe([], function(){});
    }

    /**
     * @expectedException \studio24\PubSub\PubSubException
     */
    public function testInvalidEventNameBool()
    {
        PubSub::subscribe(false, function(){});
    }

    /**
     * @expectedException \studio24\PubSub\PubSubException
     */
    public function testInvalidEventNameEmptyString2()
    {
        PubSub::publish('');
    }

}
