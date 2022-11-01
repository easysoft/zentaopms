<?php
/**
 * The v1 file of button module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Jinyong Zhu <zhujinyong@easycorp.ltd>
 * @package     button
 * @version     $Id
 * @link        https://www.zentao.net
 */
class button extends wg
{
    /**
     * Button url.
     *
     * @var    string
     * @access public
     */
    public $link = '';

    /**
     * Jump mode.
     *
     * @var    string
     * @access public
     */
    public $target = '';

    /**
     * Miscellaneous.
     *
     * @var    string
     * @access public
     */
    public $misc = '';

    /**
     * Whether to wrap.
     *
     * @var    bool
     * @access public
     */
    public $newline = true;

    /**
     * Construct function, init tab data.
     *
     * @param  string $text
     * @access public
     * @return void
     */
    public function __construct($text)
    {
        parent::__construct();
        $this->text = $text;
    }

    /**
     * Set Button url.
     *
     * @param  string $link
     * @access public
     * @return object
     */
    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Set button jump mode.
     *
     * @param  string $target
     * @access public
     * @return object
     */
    public function target($target = '')
    {
        $this->target = $target;
        return $this;
    }

    /**
     * Set attribute.
     *
     * @param  string $misc
     * @access public
     * @return object
     */
    public function misc($misc)
    {
        $this->misc = $misc;
        return $this;
    }

    /**
     * Set button newline.
     *
     * @param  bool   $newline
     * @access public
     * @return object
     */
    public function newline($newline = true)
    {
        $this->newline = $newline;
        return $this;
    }

    public function toString()
    {
        return html::a($this->link, $this->text, $this->target, $this->misc . $this->toHx(), $this->newline);
    }
}
