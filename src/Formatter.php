<?php

namespace Differ\Format;

use Exception;

use function Differ\Formatters\Plain\format as formatPlain;
use function Differ\Formatters\Json\format as formatJson;
use function Differ\Formatters\Stylish\format as formatStylish;

function format(array $diff, string $format): string
{
    if ($format == 'stylish') {
        return formatStylish($diff);
    }
    if ($format == 'json') {
        return formatJson($diff);
    }
    if ($format == 'plain') {
        return formatPlain($diff);
    } else {
        throw new Exception("$format is not found.");
    }
}
