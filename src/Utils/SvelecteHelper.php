<?php declare(strict_types=1);

namespace Mskocik\Forms\Utils;

use Nette\StaticClass;

class SvelecteHelper
{
    use StaticClass;

    public static function simpleItemMapper($opts): array
    {
        $out = [];
        foreach ($opts as $id => $text) {
            $out[] = ['value' => $id, 'text' => ucfirst($text)];
        }

        return $out;
    }

    public static function simpleGroupItemMapper($opts, $groupMapper = null): array
    {
        $out = [];
        foreach ($opts as $key => $arrayOrItem) {
            if (!is_array($arrayOrItem)) {
                $out[] = ['value' => $key, 'text' => ucfirst($arrayOrItem)];
                continue;
            }
            $out[] = [
                'label' => $key,
                'options' => $groupMapper ? $groupMapper($arrayOrItem) : static::simpleItemMapper($arrayOrItem),
            ];
        }

        return  $out;
    }

    public static function useGroupItemMapper($groupMapper = null): callable
    {
        return fn($opts) => static::simpleGroupItemMapper($opts, $groupMapper);
    }
}
