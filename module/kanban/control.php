<?php
/**
 * The control file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     kanban
 * @version     $Id: control.php 4460 2021-10-26 11:03:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class kanban extends control
{
    /**
     * Set WIP.
     *
     * @param  int    $columnID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setWIP($columnID, $executionID = 0)
    {
        if($_POST)
        {
            $this->kanban->setWIP($columnID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('wip', $columnID, 'Edited');
            die(js::reload('parent.parent'));
        }

        $this->app->loadLang('story');

        $column = $this->kanban->getColumnById($columnID);
        if(!$column) die(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'kanban', "executionID=$executionID")));

        $status = zget($this->config->kanban->{$column->laneType . 'ColumnStatusList'}, $column->type);
        $title  = isset($column->parentName) ? $column->parentName . '/' . $column->name : $column->name;

        $this->view->title  = $title . $this->lang->colon . $this->lang->kanban->setWIP . '(' . $this->lang->kanban->WIP . ')';
        $this->view->column = $column;
        $this->view->status = $status;
        $this->display();
    }
}
