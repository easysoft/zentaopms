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
            $item = array();
            $item['id']   = $dimension->id;
            $item['text'] = $dimension->name;
            $item['keys'] = zget(common::convert2Pinyin(array($dimension->name)), $dimension->name, '');
            $items[] = $item;
        }

        $this->view->link        = $this->createLink($module, $method, 'dimensionID={id}');
        $this->view->items       = $items;
        $this->view->dimensionID = $dimensionID;
        $this->display();
    }

    /**
     * Drop menu page, type is used for tree-browsegroup link.
     *
     * @access public
     * @param  string currentModule
     * @param  string currentMethod
     * @param  int    dimensionID
     * @param  string type          screen|pivot|chart
     * @return void
     */
    public function ajaxGetOldDropMenu($currentModule, $currentMethod, $dimensionID, $type = '')
    {
        $this->view->currentModule = $currentModule;
        $this->view->currentMethod = $currentMethod;
        $this->view->dimensionID   = $dimensionID;
        $this->view->type          = $type;
        $this->view->dimensions    = $this->dimension->getList();
        $this->display();
    }
}
