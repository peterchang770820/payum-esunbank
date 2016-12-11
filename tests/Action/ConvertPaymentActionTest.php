<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Esunbank\Action\ConvertPaymentAction;

class ConvertPaymentActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_execute()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $request = m::spy('Payum\Core\Request\Convert');
        $source = m::spy('Payum\Core\Model\PaymentInterface');
        $details = new ArrayObject();

        $number = uniqid();
        $totalAmount = 1000;
        $description = 'foo.description';

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getSource')->andReturn($source)
            ->shouldReceive('getTo')->andReturn('array');

        $source
            ->shouldReceive('getDetails')->andReturn($details)
            ->shouldReceive('getNumber')->andReturn($number)
            ->shouldReceive('getTotalAmount')->andReturn($totalAmount);

        $action = new ConvertPaymentAction();
        $action->execute($request);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $request->shouldHaveReceived('getSource')->twice();
        $request->shouldHaveReceived('getTo')->once();
        $source->shouldHaveReceived('getDetails')->once();
        $source->shouldHaveReceived('getNumber')->once();
        $source->shouldHaveReceived('getTotalAmount')->once();
        $request->shouldHaveReceived('setResult')->with([
            'ONO' => strtoupper($number),
            'TA' => $totalAmount,
        ])->once();
    }
}
