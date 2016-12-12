<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/15 0015
 * Time: 17:33
 */

namespace SmartWiki\Exceptions;


/**
 * 数据异常
 * Class DataException
 * @package SmartWiki\Exceptions
 */
class DataException extends \Exception
{
    public function __construct($message, $code, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}