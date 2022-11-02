<?php
/**
 * The v1 file of tab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Jinyong Zhu <zhujinyong@easycorp.ltd>
 * @package     tab
 * @version     $Id
 * @link        https://www.zentao.net
 */
class tab extends wg
{
    /**
     * Tab content.
     *
     * @var    string
     * @access private
     */
    private $text;

    /**
     * Active status.
     *
     * @var    bool
     * @access private
     */
    private $isActive;

    /**
     * Tab url.
     *
     * @var    string
     * @access private
     */
    private $link;

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
     * Set active status.
     *
     * @param  bool   $isActive
     * @access public
     * @return object
     */
    public function active($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Set tab url.
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
     * Get tab html.
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        $active = $this->isActive ? 'active' : '';
        $label  = "<span class='text'>{$this->text}</span>";
        return "<li class='nav-item'>" . html::a($this->link, $label, '', "class='$active'") . '</li>';
    }
}
