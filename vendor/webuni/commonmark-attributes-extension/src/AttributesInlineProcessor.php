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

use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Delimiter\Delimiter;
use League\CommonMark\Delimiter\DelimiterStack;
use League\CommonMark\Inline\Processor\InlineProcessorInterface;

class AttributesInlineProcessor implements InlineProcessorInterface
{
    public function processInlines(DelimiterStack $delimiterStack, Delimiter $stackBottom = null)
    {
        $delimiter = $delimiterStack->getTop();

        while ($delimiter !== null) {
            $node = $delimiter->getInlineNode();
            if (!$node instanceof InlineAttributes) {
                $delimiter = $delimiter->getPrevious();
                continue;
            }

            if ($node->isBlock()) {
                $target = $node->parent();
                if (($parent = $target->parent()) instanceof ListItem && $parent->parent() instanceof ListBlock && $parent->parent()->isTight()) {
                    $target = $parent;
                }
            } else {
                $target = $node->previous();
            }

            $target->data['attributes'] = AttributesUtils::merge($node->getAttributes(), $target);

            $node->detach();

            $delimiter = $delimiter->getPrevious();
        }
    }
}
