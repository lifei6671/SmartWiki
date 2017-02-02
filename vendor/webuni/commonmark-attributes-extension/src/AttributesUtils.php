<?php

/*
 * This is part of the webuni/commonmark-attributes-extension package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 * (c) Webuni s.r.o. <info@webuni.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webuni\CommonMark\AttributesExtension;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Cursor;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Util\RegexHelper;

class AttributesUtils
{
    private static $regexp;

    public static function parse(Cursor $cursor)
    {
        if (null === self::$regexp) {
            $regex = RegexHelper::getInstance();

            self::$regexp = sprintf(
                '/^\s*([.#][_a-z0-9-]+|%s%s)(?<!})\s*/i',
                $regex->getPartialRegex(RegexHelper::ATTRIBUTENAME),
                $regex->getPartialRegex(RegexHelper::ATTRIBUTEVALUESPEC)
            );
        }

        $state = $cursor->saveState();
        $cursor->advanceToFirstNonSpace();
        if ('{' !== $cursor->getCharacter()) {
            $cursor->restoreState($state);

            return [];
        }

        $cursor->advanceBy(1);
        if (':' === $cursor->getCharacter()) {
            $cursor->advanceBy(1);
        }

        $attributes = [];
        while ($attribute = trim($cursor->match(self::$regexp))) {
            if ('#' === $attribute[0]) {
                $attributes['id'] = substr($attribute, 1);
                continue;
            }

            if ('.' === $attribute[0]) {
                $attributes['class'][] = substr($attribute, 1);
                continue;
            }

            list($name, $value) = explode('=', $attribute, 2);
            $first = $value[0];
            $last = substr($value, -1);
            if ((('"' === $first && '"' === $last) || ("'" === $first && "'" === $last)) && strlen($value) > 1) {
                $value = substr($value, 1, -1);
            }

            if ('class' === strtolower(trim($name))) {
                foreach (array_filter(explode(' ', trim($value))) as $class) {
                    $attributes['class'][] = $class;
                }
            } else {
                $attributes[trim($name)] = trim($value);
            }
        }

        if (0 === $cursor->advanceWhileMatches('}')) {
            $cursor->restoreState($state);

            return [];
        }

        if (isset($attributes['class'])) {
            $attributes['class'] = implode(' ', $attributes['class']);
        }

        return $attributes;
    }

    public static function merge($attributes1, $attributes2)
    {
        $attributes = [];
        foreach ([$attributes1, $attributes2] as $arg) {
            if ($arg instanceof AbstractBlock || $arg instanceof AbstractInline) {
                $arg = isset($arg->data['attributes']) ? $arg->data['attributes'] : [];
            }

            $arg = (array) $arg;
            if (isset($arg['class'])) {
                foreach (array_filter(explode(' ', trim($arg['class']))) as $class) {
                    $attributes['class'][] = $class;
                }
                unset($arg['class']);
            }
            $attributes = array_merge($attributes, $arg);
        }

        if (isset($attributes['class'])) {
            $attributes['class'] = implode(' ', $attributes['class']);
        }

        return $attributes;
    }
}
