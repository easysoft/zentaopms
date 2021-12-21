<?php
/**
 * ZenTaoPHP的EndResponseException类。
 * The EndResponseException class file of ZenTaoPHP framework.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
class EndResponseException extends \Exception
{
    /**
     * 响应内容
     *
     * @var string
     */
    private $content;

    /**
     * @param string $content
     * 
     * @return sellf
     */
    public static function create($content = '')
    {
        $exception = new self;
        $exception->content = $content;
        return $exception;
    }

    /**
     * Get 响应内容
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
