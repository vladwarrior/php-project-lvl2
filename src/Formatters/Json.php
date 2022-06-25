<?php

declare(strict_types=1);

namespace Differ\Formatters\Json;

function format(array $tree): string
{
    //echo json_encode($tree) . "lkjhnbvcsdcfsrdtyujtyhgbfvcdsacsvfghn";
    return json_encode($tree);
}
