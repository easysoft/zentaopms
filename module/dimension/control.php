<?php
/**
 * The control file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dimension
 * @version     $Id: control.php 4157 2022-11-1 10:24:12Z $
 * @link        http://www.zentao.net
 */
class dimension extends control
{
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
    public function ajaxGetDropMenu($currentModule, $currentMethod, $dimensionID, $type = '')
    {
        $this->view->currentModule = $currentModule;
        $this->view->currentMethod = $currentMethod;
        $this->view->dimensionID   = $dimensionID;
        $this->view->type          = $type;
        $this->view->dimensions    = $this->dimension->getList();
        $this->display();
    }
}
