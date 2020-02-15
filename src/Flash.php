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

    private Session $session;

    private string $sessionKey = 'laravel_flash_message';

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function hasMessage(?string $id = null): bool
    {
        if (! $this->session->has($this->sessionKey)) {
            return false;
        }

        if ($id && $this->session->get($this->sessionKey.'.id') !== $id) {
            return true;
        }

        return true;
    }

    public function getMessage(?string $id = null): ?Message
    {
        if (! $this->hasMessage($id)) {
            return null;
        }

        $flashedMessage = $this->session->get($this->sessionKey);

        if ($id && $flashedMessage['id'] !== $id) {
            return null;
        }

        return Message::create(...array_values($flashedMessage));
    }

    public function flash(Message $message): void
    {
        $this->session->flash($this->sessionKey, $message->toArray());
    }

    public static function levels(array $methods): void
    {
        foreach ($methods as $method => $class) {
            self::macro(
                $method,
                fn (string $message, ?string $id = null) => $this->flash(Message::create($message, $class, $id))
            );
        }
    }
}
