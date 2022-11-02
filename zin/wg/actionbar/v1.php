<?php
/**
 * The v1 file of actionbar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Jinyong Zhu <zhujinyong@easycorp.ltd>
 * @package     actionbar
 * @version     $Id
 * @link        https://www.zentao.net
 */
class actionbar extends wg
{
    /**
     * Action list.
     *
     * @var    array
     * @access private
     */
    private $actions = array();

    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set action data.
     *
     * @param  object|string $item
     * @access public
     * @return void
     */
    public function append($item)
    {
        $this->actions[] = $item;
    }

    /**
     * Get actionbar html.
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        $html = '<div class="pull-right">';
        foreach($this->actions as $action)
        {
            if(is_string($action))
            {
                $html .= $action;
                continue;
            }

            $html .= $action->toString();
        }
        return $html . '</div>';
    }
}
