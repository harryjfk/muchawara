<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Tests\Dumper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Dumper\MoFileDumper;

class MoFileDumperTest extends TestCase
{
    public function testDump()
    {
        $catalogue = new MessageCatalogue('en');
        $catalogue->add(array('foo' => 'bar'));

        $tempDir = sys_get_temp_dir();
        $dumper = new MoFileDumper();
        $dumper->dump($catalogue, array('path' => $tempDir));
        $this->assertEquals(file_get_contents(__DIR__.'/../fixtures/resources.mo'), file_get_contents($tempDir.'/messages.en.mo'));

        unlink($tempDir.'/messages.en.mo');
    }
}
