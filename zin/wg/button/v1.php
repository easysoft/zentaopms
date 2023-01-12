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
    public $target = 'self';

    /**
     * Class.
     *
     * @var    string
     * @access public
     */
    public $class = '';

    /**
     * Miscellaneous.
     *
     * @var    string
     * @access public
     */
    public $misc = '';

    /**
     * Construct function, init button data.
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
     * Set class.
     *
     * @param  string $class
     * @access public
     * @return object
     */
    public function addClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Get button html.
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        return html::linkButton($this->text, $this->link, $this->target, $this->misc, $this->class);
    }
}
