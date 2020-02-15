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

namespace KodeKeep\Flash;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Traits\Macroable;

class Flash
{
    use Macroable;

    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __get(string $name)
    {
        return $this->getMessage()->$name ?? null;
    }

    public function hasMessage(): bool
    {
        return $this->session->has('laravel_flash_message');
    }

    public function getMessage(): ?Message
    {
        if (! $this->hasMessage()) {
            return null;
        }

        $flashedMessage = $this->session->get('laravel_flash_message');

        return new Message($flashedMessage['message'], $flashedMessage['class']);
    }

    public function flash(Message $message): void
    {
        $this->session->flash('laravel_flash_message', $message->toArray());
    }

    public static function levels(array $methodClasses): void
    {
        foreach ($methodClasses as $method => $classes) {
            self::macro($method, fn (string $message) => $this->flash(new Message($message, $classes)));
        }
    }
}
