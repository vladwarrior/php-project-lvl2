<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;
use SplFileInfo;

use function Differ\Format\format;
use function Differ\Parsers\parse;
use function Functional\sort;

/**
 * @throws Exception Стандартное исключение.
 */
function genDiff(string $pathOne, string $pathTwo, string $format = 'stylish'): string
{
    $fileDataRawOne = getFileData($pathOne);
    $fileDataOne = parse($fileDataRawOne['data_file'], $fileDataRawOne['format']);

    $fileDataRawTwo = getFileData($pathTwo);
    $fileDataTwo = parse($fileDataRawTwo['data_file'], $fileDataRawTwo['format']);

    $tree = buildDiff($fileDataOne, $fileDataTwo);
    return format($tree, $format);
}

/**
 * @throws Exception Стандартное исключение.
 */
function getFileData(string $pathToFile): array
{
    if (!file_exists($pathToFile)) {
        throw new Exception("File $pathToFile is not found.");
    }

    $path = new SplFileInfo($pathToFile);
    $format = $path->getExtension();
    return ['data_file' => (string)file_get_contents($pathToFile), 'format' => $format];
}

function buildDiff(array $dataOne, array $dataTwo): array
{
    $keysFirst = array_keys($dataOne);
    $keysLast = array_keys($dataTwo);
    $allKeys = array_unique(array_merge($keysFirst, $keysLast));
    $allKeysSorted = sort(
        $allKeys,
        fn ($left, $right) => $left <=> $right,
        true
    );

    $result = array_map(function ($key) use ($dataOne, $dataTwo) {
        $valueOne = $dataOne[$key] ?? null;
        $valueTwo = $dataTwo[$key] ?? null;

        if (!array_key_exists($key, $dataOne)) {
            return [
                'name' => $key,
                'type' => 'added',
                'value' => $valueTwo,
            ];
        }

        if (!array_key_exists($key, $dataTwo)) {
            return [
                'name' => $key,
                'type' => 'deleted',
                'value' => $valueOne,
            ];
        }

        if (is_array($valueTwo) && is_array($valueOne)) {
            return [
                'name' => $key,
                'type' => 'parent',
                'child' => buildDiff($valueOne, $valueTwo),
            ];
        }

        if ($valueTwo !== $valueOne) {
            return [
                'name' => $key,
                'type' => 'changed',
                'value_two_data' => $valueOne,
                'value_first_data' => $valueTwo
            ];
        }


        return [
            'name' => $key,
            'type' => 'no_change',
            'value' => $valueOne,
        ];
    }, $allKeysSorted);
    return array_values($result);
}
