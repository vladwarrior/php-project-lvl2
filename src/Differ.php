<?php

namespace Differ\Differ;

use Exception;
use SplFileInfo;

use function Funct\Collection\union;
use function Differ\Format\format;
use function Differ\Parsers\parse;

//use function Functional\sort;

function genDiff(string $pathOne, string $pathTwo, string $format = 'stylish'): string
{
    $fileDataRawOne = getFileData($pathOne);
    $fileDataOne = parse($fileDataRawOne['data_file'], $fileDataRawOne['format']);

    $fileDataRawTwo = getFileData($pathTwo);
    $fileDataTwo = parse($fileDataRawTwo['data_file'], $fileDataRawTwo['format']);

    $tree = buildDiff($fileDataOne, $fileDataTwo);
    //print_r($tree);
    return format($tree, $format);
}


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
    $keysSecond = array_keys($dataTwo);
    $allUnionKeys = union($keysFirst, $keysSecond);
    sort($allUnionKeys);

    $result = array_map(function ($key) use ($dataOne, $dataTwo) {
        $valueOne = $dataOne[$key] ?? null;
        print_r($valueOne . "\n");
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
    }, $allUnionKeys);
    return array_values($result);
}
