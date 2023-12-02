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
     * 节假日主页，跳转至列表。
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
     * 节假日列表。
     * Holiday list.
     *
     * @param  string $year
     * @access public
     * @return void
     */
    public function browse(string $year = '')
    {
        if(empty($year)) $year = date('Y');

        $holidays = $this->holiday->getList($year);
        $yearList = $this->holiday->getYearPairs();

        foreach($holidays as $holiday) $holiday->holiday = formatTime($holiday->begin, DT_DATE1) . ' ~ ' . formatTime($holiday->end, DT_DATE1);

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
     * 创建一个节假日。
     * Create a holiday.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $holiday = form::data($this->config->holiday->form->create)->get();
            $holiday->year = substr($holiday->begin, 0, 4);

            if($holiday->year && helper::isZeroDate($holiday->year)) dao::$errors['begin'][] = sprintf($this->lang->error->date, $this->lang->holiday->begin);
            if($holiday->end && helper::isZeroDate($holiday->end))  dao::$errors['end'][]   = sprintf($this->lang->error->date, $this->lang->holiday->end);
            if($holiday->begin && $holiday->end && $holiday->begin > $holiday->end) dao::$errors['end'][] = sprintf($this->lang->error->ge, $this->lang->holiday->end, $this->lang->holiday->begin);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $holidayID = $this->holiday->create($holiday);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('holiday', $holidayID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
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
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->view->title   = $this->lang->holiday->edit;
        $this->view->holiday = $holiday;
        $this->display();
    }

    /**
     * Delete holiday.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
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
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
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

        if(!empty($_POST))
        {
            $this->holiday->batchCreate($holidays);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        foreach($holidays as $holiday) $holiday->holiday = formatTime($holiday->begin, DT_DATE1) . ' ~ ' . formatTime($holiday->end, DT_DATE1);

        $this->view->holidays = $holidays;
        $this->display();
    }
}
