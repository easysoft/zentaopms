<?php
/**
 * The control file of holiday module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     holiday
 * @version     $Id
 * @link        http://www.zentao.net
 */
class holiday extends control
{
    /**
     * Holiday list.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * Holiday list.
     *
     * @param  string $year
     * @access public
     * @return void
     */
    public function browse($year = '')
    {
        $this->lang->menugroup->holiday  = ''; 
        $this->lang->holiday->menu = $this->lang->subject->menu;
        $this->lang->holiday->menuOrder = $this->lang->subject->menuOrder;

        $holidays = $this->holiday->getList($year);
        $yearList = $this->holiday->getYearPairs();

        $this->view->title       = $this->lang->holiday->browse;
        $this->view->holidays    = $holidays;
        $this->view->yearList    = $yearList;
        $this->view->currentYear = $year;
        $this->display();
    }

    /**
     * Create a holiday.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $holidayID = $this->holiday->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $actionID = $this->loadModel('action')->create('holiday', $holidayID, 'created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title = $this->lang->holiday->create;
        $this->display();
    }

    /**
     * Edit holiday.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $holiday = $this->holiday->getById($id);
        if($_POST)
        {
            $this->holiday->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title   = $this->lang->holiday->edit;
        $this->view->holiday = $holiday;
        $this->display();
    }

    /**
     * Delete holiday.
     *
     * @param  int    $id
     * @param  int    $confirm
     * @access public
     * @return void
     */
    public function delete($id, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->holiday->confirmDelete, inLink('delete', "id=$id&confirm=yes")));
        }
        else
        {
            $holidayInformation = $this->dao->select('begin, end')->from(TABLE_HOLIDAY)->where('id')->eq($id)->fetch();
            $this->dao->delete()->from(TABLE_HOLIDAY)->where('id')->eq($id)->exec();

            /* Update project. */
            $this->holiday->updateProjectPlanDuration($holidayInformation->begin, $holidayInformation->end);
            $this->holiday->updateProjectRealDuration($holidayInformation->begin, $holidayInformation->end);

            /* Update task. */
            $this->holiday->updateTaskPlanDuration($holidayInformation->begin, $holidayInformation->end);
            $this->holiday->updateTaskRealDuration($holidayInformation->begin, $holidayInformation->end);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            die(js::reload('parent'));
        }
    }
}
