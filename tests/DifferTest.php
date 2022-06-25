<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider filesProvider
     * @throws Exception
     */
    public function testGendiff(string $format, string $expected, string $file1, string $file2): void
    {
        $actual = genDiff(
            $this->createFilePath($file1),
            $this->createFilePath($file2),
            $format
        );
        $this->assertEquals(file_get_contents($this->createFilePath($expected)), $actual);
    }

   
    public function filesProvider(): array
    {
        return [
            'json format json' => [
                'format' => 'json',
                'expected' => 'result.json',
                'file1' => 'file1.json',
                'file2' => 'file2.json'
            ]
        ];
    }

    private function createFilePath(string $fileName): string
    {
        return realpath(
            implode(
                '/',
                [
                    __DIR__,
                    'fixtures',
                    $fileName
                ]
            )
        );
    }
}
