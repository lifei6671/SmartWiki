<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/12/29 0029
 * Time: 17:13
 */

namespace SmartWiki\Extentions\Markdown\Renderer;


use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;

/**
 * 实现处理h1-7标签与 editor.md 处理方式相同
 * Class HeadingRenderer
 * @package Minho\Markdown\Renderer
 */
class HeadingRenderer implements BlockRendererInterface
{

    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof Heading)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }
        $tag = 'h' . $block->getLevel();
        $attrs = [];
        foreach ($block->getData('attributes', []) as $key => $value) {
            $attrs[$key] = $htmlRenderer->escape($value, true);
        }
        $text = $htmlRenderer->renderInlines($block->children());

        $sourceText = trim(strip_tags($text));
        if(empty($sourceText) === false) {
            $idText = strtolower(preg_replace('/\s+/i', '-', $sourceText));

            if (preg_match('/([\x80-\xff]*)/i', $idText)) {
                $id = preg_replace('/([\x80-\xff]*)/i', '', $idText);
                if (empty($id)) {
                    $id = $this->utf8_str_to_unicode($idText, 'u');
                } elseif ($id !== $idText) {
                    $id = '-' . trim($id);
                }
            }else{
                $id = $idText;
            }

            $attrs['id'] = $tag . '-' . $id;
        }


        $text = "<a name=\"{$sourceText}\" class=\"reference-link\"></a><span class=\"header-link octicon octicon-link\"></span>" . $text;

        return new HtmlElement($tag, $attrs, $text);
    }

    private function utf8_str_to_unicode($utf8_str,$prefix = '\u')
    {
        $buffer = '';

        for ( $i= 0,$len = mb_strlen($utf8_str);$i<$len;$i++) {
            $str = mb_substr($utf8_str,$i,1);
            $unicode = (ord($str[0]) & 0x1F) << 12;
            $unicode |= (ord($str[1]) & 0x3F) << 6;
            $unicode |= (ord($str[2]) & 0x3F);
            $buffer .= $prefix . dechex($unicode);
        }
        return $buffer;
    }

    protected function unicode_encode($value, $prefix = '\u')
    {
        $name = iconv('UTF-8', 'UCS-2', $value);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2)
        {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0)
            {    // 两个字节的文字
                $str .=  $prefix . strtoupper(base_convert(ord($c), 10, 16) . base_convert(ord($c2), 10, 16));
            }
            else
            {
                $str .= $c2;
            }
        }
        return $str;
    }

}