<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/4 0004
 * Time: 15:20
 */

namespace SmartWiki\Extentions\Markdown\Renderer;


use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;

class DocumentRenderer implements BlockRendererInterface
{
    /**
     * @param AbstractBlock|Document   $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return string
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof Document)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }
        $wholeDoc = $htmlRenderer->renderBlocks($block->children());


        return $wholeDoc === '' ? '' : $wholeDoc . "\n";
    }

}