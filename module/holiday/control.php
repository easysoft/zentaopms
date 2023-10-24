<?php
/**
 * The control file of holiday module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        if(empty($year)) $year = date('Y');

        $holidays = $this->holiday->getList($year);
        $yearList = $this->holiday->getYearPairs();

        $yearAndNext = array(date('Y'), date('Y') + 1);
        foreach($yearAndNext as $date)
        {
            if(!in_array($date, $yearList)) $yearList[$date] = $date;
        }
        krsort($yearList);

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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $actionID = $this->loadModel('action')->create('holiday', $holidayID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
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
            return print(js::confirm($this->lang->holiday->confirmDelete, inLink('delete', "id=$id&confirm=yes")));
        }
        else
        {
            $holidayInformation = $this->dao->select('begin, end')->from(TABLE_HOLIDAY)->where('id')->eq($id)->fetch();
            $this->dao->delete()->from(TABLE_HOLIDAY)->where('id')->eq($id)->exec();

            /* Update project. */
            $this->holiday->updateProgramPlanDuration($holidayInformation->begin, $holidayInformation->end);
            $this->holiday->updateProjectRealDuration($holidayInformation->begin, $holidayInformation->end);

            /* Update task. */
            $this->holiday->updateTaskPlanDuration($holidayInformation->begin, $holidayInformation->end);
            $this->holiday->updateTaskRealDuration($holidayInformation->begin, $holidayInformation->end);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return print(js::reload('parent'));
        }
    }

    /**
     * Import holiday.
     *
     * @param  string $year
     * @access public
     * @return void
     */
    public function import($year = '')
    {
        if(empty($year)) $year = date('Y');

        $holidays = $this->holiday->getHolidayByAPI($year);
        if(helper::isAjaxRequest())
        {
            $this->holiday->batchCreate($holidays);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->holidays = $holidays;
        $this->display();
    }
}
