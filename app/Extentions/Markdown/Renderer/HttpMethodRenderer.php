<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/6 0006
 * Time: 10:34
 */

namespace SmartWiki\Extentions\Markdown\Renderer;


use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use SmartWiki\Extentions\Markdown\Element\HttpMethodBlock;

class HttpMethodRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof HttpMethodBlock)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        $caseMethod = strtolower($block->getMethod());

        $text = '<span class="default '. $caseMethod . '">' . $block->getMethod() . '</span>' . $block->getLink();

        $attrs = ['class' => 'http-method'];

        return new HtmlElement('div', $attrs, $text);

    }

}