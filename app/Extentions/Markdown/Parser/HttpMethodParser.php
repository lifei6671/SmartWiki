<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/6 0006
 * Time: 9:19
 */

namespace SmartWiki\Extentions\Markdown\Parser;

use Faker\Provider\ar_JO\Text;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\HtmlBlock;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Block\Parser\AbstractBlockParser;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Inline\Element\HtmlInline;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Element\Newline;
use League\CommonMark\Util\RegexHelper;
use SmartWiki\Extentions\Markdown\Element\HttpMethodBlock;


class HttpMethodParser extends AbstractBlockParser
{
    const REGEXP_DEFINITION = '/^(GET|POST|PUT|DELETE|HEAD|OPTIONS|TRACE):(\/[^\s]*$)/';

    public function parse(ContextInterface $context, Cursor $cursor)
    {
        $container = $context->getContainer();

        if(!$container instanceof Document){
            return false;
        }

        $lines = $cursor->getLine();
        if (empty($lines)) {
            return false;
        }

        $match = RegexHelper::matchAll(self::REGEXP_DEFINITION, $cursor->getLine(), $cursor->getFirstNonSpacePosition());

        if (empty($match) || count($match) !== 3) {
            return false;
        }


        $httpMethod = new HttpMethodBlock($match[1],$match[2]);

        $context->addBlock($httpMethod);
        $context->setBlocksParsed(true);

        return true;
    }

}