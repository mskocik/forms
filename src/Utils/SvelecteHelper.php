<?php declare(strict_types=1);

namespace Mskocik\Forms\Utils;

use Nette\StaticClass;
use Nette\Utils\Arrays;

class SvelecteHelper
{
    use StaticClass;

    /**
     * Maps typical associative or plain array as svelecte option list
     *
     * @param array $opts array-like argument
     * @return array
     */
    public static function simpleListMapper($opts): array
    {
        $out = [];
        foreach ($opts as $id => $text) {
            $out[] = ['value' => $id, 'text' => ucfirst($text)];
        }

        return $out;
    }

    /**
     * Handles option group with ability to provide custom item mapper
     *
     * @param array $opts
     * @param  callback $itemMapper
     * @return array
     */
    public static function groupListMapper($opts, $itemMapper = null): array
    {
        // defaults to simpleListMapper, argument order is inverted due to use of Arrays::map
        if (!$itemMapper) $itemMapper = fn($text, $id) => ['value' => $id, 'text' => ucfirst($text)];
        
        $out = [];
        foreach ($opts as $key => $arrayOrItem) {
            if (!is_array($arrayOrItem)) {
                $out[] = call_user_func_array($itemMapper, [$arrayOrItem, $key]);
                continue;
            }
            $out[] = [
                'label' => $key,
                'options' => array_values(  // remove keys, because it would conver array to object
                    Arrays::map($arrayOrItem, $itemMapper)
                ),
            ];
        }

        return  $out;
    }

    /**
     * Shorthand to provide custom itemMapper, but preserves optgroup handling
     *
     * @param callable $itemMapper
     * @return callable
     */
    public static function use($itemMapper = null): callable
    {
        return fn($opts) => static::groupListMapper($opts, $itemMapper);
    }
}
