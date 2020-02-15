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

use KodeKeep\Flash\Facades\Flash;
use KodeKeep\Flash\FlashServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Flash::levels([
            'success' => ['class' => 'green'],
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [FlashServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['Flash' => Flash::class];
    }
}
