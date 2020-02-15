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

class Message
{
    public string $message;

    public ?string $class;

    public function __construct(string $message, $class = null)
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }

        $this->message = $message;

        $this->class = $class;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'class'   => $this->class,
        ];
    }
}
