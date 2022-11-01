<?php
/**
 * The v1 file of toolbar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Jinyong Zhu <zhujinyong@easycorp.ltd>
 * @package     toolbar
 * @version     $Id
 * @link        https://www.zentao.net
 */
class toolbar extends wg
{
    /**
     * Tool list.
     *
     * @var    array
     * @access private
     */
    private $tools = array();

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
     * Set tools data.
     *
     * @param  object $item
     * @access public
     * @return void
     */
    public function append($item)
    {
        $this->tools[] = $item;
    }

    /**
     * Get toolbar html.
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        $html = '<div class="btn-toolBar pull-left">';
        foreach($this->tools as $tool)
        {
            if(is_string($tool))
            {
                $html .= $tool;
                continue;
            }
            $html .= $tool->toString();
        }
        return $html . '</div>';
    }
}
