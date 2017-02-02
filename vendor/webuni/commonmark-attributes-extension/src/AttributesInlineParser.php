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

use League\CommonMark\Delimiter\Delimiter;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;

class AttributesInlineParser extends AbstractInlineParser
{
    public function getCharacters()
    {
        return [' ', '{'];
    }

    public function parse(InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();
        if ($cursor->getFirstNonSpaceCharacter() !== '{') {
            return false;
        }

        $char = $cursor->getCharacter();
        if ('{' === $char) {
            $char = (string) $cursor->getCharacter($cursor->getPosition() - 1);
        }

        $attributes = AttributesUtils::parse($cursor);
        if (empty($attributes)) {
            return false;
        }

        if ('' === $char) {
            $cursor->advanceToFirstNonSpace();
        }

        $node = new InlineAttributes($attributes, ' ' === $char || '' === $char);
        $inlineContext->getContainer()->appendChild($node);
        $inlineContext->getDelimiterStack()->push(new Delimiter('attributes', 1, $node, false, false));

        return true;
    }
}
