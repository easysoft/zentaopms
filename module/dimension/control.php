<?php
/**
 * The control file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dimension
 * @version     $Id: control.php 4157 2022-11-1 10:24:12Z $
 * @link        https://www.zentao.net
 */
class dimension extends control
{
    /**
     * 显示切换维度的 1.5 级导航的下拉菜单。
     * Show the drop menu of 1.5 level navigation for switching dimension.
     *
     * @param  int    $dimensionID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $dimensionID, string $module, string $method)
    {
        $items      = array();
        $dimensions = $this->dimension->getList();
        foreach($dimensions as $dimension)
        {
            /* 构造 1.5 级导航的数据。*/
            /* Build the data of 1.5 level navigation. */
            $items[] = array
            (
                'id'    => $dimension->id,
                'text'  => $dimension->name,
                'keys'  => zget(common::convert2Pinyin(array($dimension->name)), $dimension->name, ''),
            );
        }
        $this->view->link        = $this->createLink($module, $method, 'dimensionID={id}');
        $this->view->items       = $items;
        $this->view->dimensionID = $dimensionID;
        $this->display();
    }
}
