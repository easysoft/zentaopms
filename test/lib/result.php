<?php
/**
 * The run result of testcase.
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guanxing <guanxiying@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: $
 * @link        http://www.zentao.net
 */
class result
{
    /**
     * Original run result.
     *
     * @var mixed
     * @access private
     */
    private $runResult;

    /**
     * Properties for testing.
     *
     * @var string
     * @access private
     */
    private $properties = '';

    /**
     * Check properties of result.
     *
     * @param  string $fields  If fields is empty, print result.
     * @access public
     */
    public function check($properties)
    {
        $this->properties = $properties;
    }

    public function expect($expect)
    {
        if($this->properties)
        {
        }
    }
}
