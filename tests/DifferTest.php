<?php


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
            'stylish format json' => [
                'format' => 'stylish',
                'expected' => 'result.stylish',
                'file1' => 'file1.json',
                'file2' => 'file2.json'
            ],
            'plain format json' => [
                'format' => 'plain',
                'expected' => 'result.plain',
                'file1' => 'file1.json',
                'file2' => 'file2.json'
            ],
            'plain format yaml' => [
                'format' => 'plain',
                'expected' => 'result.plain',
                'file1' => 'file.yml',
                'file2' => 'file1.yml'
            ],
            'plain format combo' => [
                'format' => 'plain',
                'expected' => 'result.plain',
                'file1' => 'file1.json',
                'file2' => 'file1.yml'
            ],
            'json format json' => [
                'format' => 'json',
                'expected' => 'result.json',
                'file1' => 'file1.json',
                'file2' => 'file2.json'
            ],
            'json format yaml' => [
                'format' => 'json',
                'expected' => 'result.json',
                'file1' => 'file.yml',
                'file2' => 'file1.yml'
            ],
            'json format combo' => [
                'format' => 'json',
                'expected' => 'result.json',
                'file1' => 'file1.json',
                'file2' => 'file1.yml'
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
