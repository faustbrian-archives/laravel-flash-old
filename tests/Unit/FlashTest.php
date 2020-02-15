<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flash.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\Flash\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use KodeKeep\Flash\Facades\Flash;
use KodeKeep\Flash\Message;

/**
 * @covers \KodeKeep\Flash\Flash
 */
class FlashTest extends TestCase
{
    use ArraySubsetAsserts;

    /** @test */
    public function it_can_set_a_flash_message_with_a_class(): void
    {
        Flash::success('my message');

        $this->assertArraySubset([
            'message' => 'my message',
            'class'   => 'green',
        ], Flash::getMessage()->toArray());
    }

    /** @test */
    public function it_can_set_a_flash_message_with_multiple_classes(): void
    {
        $message = Message::create('my message', ['my-class', 'another-class']);

        Flash::flash($message);

        $this->assertArraySubset([
            'message' => 'my message',
            'class'   => 'my-class another-class',
        ], Flash::getMessage()->toArray());
    }

    /** @test */
    public function the_flash_function_is_macroable(): void
    {
        Flash::macro('info', fn (string $message) => $this->flash(Message::create($message, 'my-info-class')));

        Flash::info('my message');

        $this->assertArraySubset([
            'message' => 'my message',
            'class'   => 'my-info-class',
        ], Flash::getMessage()->toArray());
    }

    /** @test */
    public function multiple_methods_can_be_added_in_one_go(): void
    {
        Flash::levels([
            'warning' => 'my-warning-class',
            'error'   => 'my-error-class',
        ]);

        Flash::warning('my warning');

        $this->assertArraySubset([
            'message' => 'my warning',
            'class'   => 'my-warning-class',
        ], Flash::getMessage()->toArray());

        Flash::error('my error');

        $this->assertArraySubset([
            'message' => 'my error',
            'class'   => 'my-error-class',
        ], Flash::getMessage()->toArray());
    }

    /** @test */
    public function it_can_flash_messages_with_a_given_id(): void
    {
        Flash::levels([
            'warning' => 'my-warning-class',
        ]);

        Flash::warning('my warning', 'unique-id');

        $this->assertSame([
            'message' => 'my warning',
            'class'   => 'my-warning-class',
            'id'      => 'unique-id',
        ], Flash::getMessage()->toArray());

        Flash::warning('my warning', 'unique-id');

        $this->assertSame([
            'message' => 'my warning',
            'class'   => 'my-warning-class',
            'id'      => 'unique-id',
        ], Flash::getMessage('unique-id')->toArray());

        $this->assertNull(Flash::getMessage('unknown-id'));
    }

    /** @test */
    public function it_can_assert_that_a_flash_message_exists(): void
    {
        Flash::levels([
            'warning' => 'my-warning-class',
        ]);

        Flash::warning('my warning');

        $this->assertTrue(Flash::hasMessage());

        $this->assertFalse(Flash::hasMessage('unique-id'));

        Flash::warning('my warning', 'unique-id');

        $this->assertTrue(Flash::hasMessage('unique-id'));
    }

    /** @test */
    public function empty_flash_message_returns_null(): void
    {
        $this->assertNull(Flash::getMessage());
    }
}
