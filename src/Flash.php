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

    public function get(?string $id = null): ?Message
    {
        if (! $this->has($id)) {
            return null;
        }

        $flashedMessage = $this->session->pull($this->sessionKey);

        if ($id && $flashedMessage['id'] !== $id) {
            return null;
        }

        return Message::create(...array_values($flashedMessage));
    }

    public function set(Message $message): void
    {
        $this->session->flash($this->sessionKey, $message->toArray());
    }

    public function has(?string $id = null): bool
    {
        if (! $this->session->has($this->sessionKey)) {
            return false;
        }

        if ($id) {
            return $this->session->get($this->sessionKey.'.id') === $id;
        }

        return true;
    }

    public static function levels(array $methods): void
    {
        foreach ($methods as $method => $class) {
            self::macro(
                $method,
                fn (string $message, ?string $id = null) => $this->set(Message::create($message, $class, $id))
            );
        }
    }
}
