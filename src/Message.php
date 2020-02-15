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

use Illuminate\Support\Str;

class Message
{
    public ?string $id = null;

    public string $message;

    public ?string $class;

    private function __construct(string $message, $class = null, $id = null)
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }

        $this->message = $message;
        $this->class   = $class;
        $this->id      = $id;
    }

    public static function create(...$args): self
    {
        return new static(...$args);
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'class'   => $this->class,
            'id'      => $this->id ?: Str::random(32),
        ];
    }
}
