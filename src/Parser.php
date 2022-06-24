<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @throws Exception Стандартное исключение.
 */
function parse(string $data, string $format): array
{
    if ($format == 'yaml') {
        return Yaml::parse($data);
    }
    if ($format == 'yml') {
        return formatJson($diff);
    }
    if ($format == 'json') {
        return json_decode($data, true);
    }
}