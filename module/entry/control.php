<?php
/**
 * The control file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class entry extends control
{
    /**
     * Browse entries. 
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $pager = $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->entry->api . $this->lang->colon . $this->lang->entry->list;
        $this->view->entries = $this->entry->getList($orderBy, $pager);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
        $this->display();
    }

    /**
     * Create an entry. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $entryID = $this->entry->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('entry', $entryID, 'created');
            $this->send(array('result' => 'success', 'message' => $this->lang->entry->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->entry->api . $this->lang->colon . $this->lang->entry->create;
        $this->display();
    }

    /**
     * Edit an entry. 
     * 
     * @param  int    $entryID 
     * @access public
     * @return void
     */
    public function edit($entryID)
    {
        if($_POST)
        {
            $changes = $this->entry->update($entryID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('entry', $entryID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->entry->saveSuccess, 'locate' => inlink('browse')));
        }

        $entry = $this->entry->getById($entryID);
        $this->view->title = $this->lang->entry->edit . $this->lang->colon . $entry->name;
        $this->view->entry = $entry;
        $this->display();
    }

    /**
     * Delete an entry. 
     * 
     * @param  int    $entryID 
     * @access public
     * @return void
     */
    public function delete($entryID)
    {
        $this->entry->delete($entryID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Show access logs of entry. 
     * 
     * @param  int    $entryID 
     * @access public
     * @return void
     */
    public function log($entryID)
    {
        $entry = $this->entry->getById($entryID);
        $this->view->title   = $this->lang->entry->log . $this->lang->colon . $entry->name;
        $this->view->actions = $this->loadModel('action')->getList('entry', $entryID);
        $this->display();
    }
}
