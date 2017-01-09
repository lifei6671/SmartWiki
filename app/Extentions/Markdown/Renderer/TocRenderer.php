<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/4 0004
 * Time: 15:32
 */

namespace SmartWiki\Extentions\Markdown\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;

class TocRenderer implements BlockRendererInterface
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

        if($wholeDoc !== ''){
            $preNode = null;

            $tree = [];

            foreach ($block->children() as $node){
                if($node instanceof Heading){
                    $level = $node->getLevel();
                    $text = trim(strip_tags($htmlRenderer->renderInlines($node->children())));

                    if(empty($tree)){
                        $tree[] = ['parent' => 0,'item' => $text ,'id' => count($tree)+1 , 'level' => $node->getLevel()];

                    }elseif ($level > end($tree)['level']){
                        $tree[] = ['parent' => end($tree)['id'], 'item' => $text,'id' => count($tree)+1, 'level' => $node->getLevel()];
                    }elseif ($level < end($tree)['level']){
                        $then = [];
                        for ($i = count($tree); $i > 0;$i--){
                            $current = $tree[$i-1];
                            if($current['level'] === $level){
                                $then = ['parent' => $current['parent'], 'item' => $text,'id' => count($tree)+1, 'level' => $node->getLevel()];
                                break;
                            }elseif ($current['level'] < $level){
                                $then = ['parent' => $current['id'], 'item' => $text,'id' => count($tree)+1, 'level' => $node->getLevel()];
                                break;
                            }
                        }

                        if(empty($then)){
                            $then = ['parent' => 0,'item' => $text ,'id' => count($tree)+1 , 'level' => $node->getLevel()];
                        }
                        $tree[] = $then;
                    }elseif ($level === end($tree)['level']){
                        $tree[] = ['parent' => end($tree)['parent'], 'item' => $text,'id' => count($tree)+1, 'level' => $node->getLevel()];
                    }
                }
            }

            if(empty($tree) === false){
                $list = $this->tocRecursion(0,$tree);

                $tocList = '<div class="markdown-toc editormd-markdown-toc"><ul class="markdown-toc-list">'. substr($list,4) . '</div>';
                $wholeDoc = str_replace('<p>[TOC]</p>',$tocList,$wholeDoc);

                //$wholeDoc .= preg_replace('/^\[TOC\]$/',$tocList,$wholeDoc);


            }
        }
        return $wholeDoc === '' ? '' : $wholeDoc . "\n";
    }

    protected function tocRecursion($parent, array $values)
    {
        global $_markdown_toc;

        $_markdown_toc .= '<ul>';

        foreach ($values as $item){
            if($item['parent'] == $parent) {

                $_markdown_toc .= '<li><a href="#'. $item['item'] .'" title="' . htmlspecialchars($item['item']) . '" level="'. $item['level'] .'"  class="toc-level-'.$item['level'].'">' . $item['item'] .'</a>';

                $key = array_search($item['id'], array_column($values, 'parent'));

                if ($key !== false) {
                    $this->tocRecursion($item['id'], $values);
                }
                $_markdown_toc .= '</li>';
            }
        }
        $_markdown_toc .= '</ul>';
        return $_markdown_toc;
    }
}