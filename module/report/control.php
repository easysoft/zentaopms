<?php
/**
 * The control file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class report extends control
{
    /**
     * The projectID.
     *
     * @var float
     * @access public
     */
    public $projectID = 0;

    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The index of report, goto project deviation.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('annualData'));
    }

    /**
     * Send daily reminder mail.
     *
     * @access public
     * @return void
     */
    public function remind()
    {
        $bugs = $tasks = $todos = $testTasks = array();
        if($this->config->report->dailyreminder->bug)      $bugs  = $this->report->getUserBugs();
        if($this->config->report->dailyreminder->task)     $tasks = $this->report->getUserTasks();
        if($this->config->report->dailyreminder->todo)     $todos = $this->report->getUserTodos();
        if($this->config->report->dailyreminder->testTask) $testTasks = $this->report->getUserTestTasks();

        $reminder = array();

        $users = array_unique(array_merge(array_keys($bugs), array_keys($tasks), array_keys($todos), array_keys($testTasks)));
        if(!empty($users)) foreach($users as $user) $reminder[$user] = new stdclass();

        if(!empty($bugs))  foreach($bugs as $user => $bug)   $reminder[$user]->bugs  = $bug;
        if(!empty($tasks)) foreach($tasks as $user => $task) $reminder[$user]->tasks = $task;
        if(!empty($todos)) foreach($todos as $user => $todo) $reminder[$user]->todos = $todo;
        if(!empty($testTasks)) foreach($testTasks as $user => $testTask) $reminder[$user]->testTasks = $testTask;

        $this->loadModel('mail');

        /* Check mail turnon.*/
        if(!$this->config->mail->turnon)
        {
            echo "You should turn on the Email feature first.\n";
            return false;
        }

        foreach($reminder as $user => $mail)
        {
            /* Reset $this->output. */
            $this->clear();

            $mailTitle  = $this->lang->report->mailTitle->begin;
            $mailTitle .= isset($mail->bugs)  ? sprintf($this->lang->report->mailTitle->bug,  count($mail->bugs))  : '';
            $mailTitle .= isset($mail->tasks) ? sprintf($this->lang->report->mailTitle->task, count($mail->tasks)) : '';
            $mailTitle .= isset($mail->todos) ? sprintf($this->lang->report->mailTitle->todo, count($mail->todos)) : '';
            $mailTitle .= isset($mail->testTasks) ? sprintf($this->lang->report->mailTitle->testTask, count($mail->testTasks)) : '';
            $mailTitle  = rtrim($mailTitle, ',');

            /* Get email content and title.*/
            $this->view->mail      = $mail;
            $this->view->mailTitle = $mailTitle;

            $oldViewType = $this->viewType;
            if($oldViewType == 'json') $this->viewType = 'html';
            $mailContent = $this->parse('report', 'dailyreminder');
            $this->viewType == $oldViewType;

            /* Send email.*/
            echo date('Y-m-d H:i:s') . " sending to $user, ";
            $this->mail->send($user, $mailTitle, $mailContent, '', true);
            if($this->mail->isError())
            {
                echo "fail: \n" ;
                a($this->mail->getError());
            }
            echo "ok\n";
        }
    }

    /**
     * Show annual data.
     *
     * @param  string $year
     * @param  string $dept
     * @param  string $account
     * @access public
     * @return void
     */
    public function annualData($year = '', $month = '', $dept = '', $account = '')
    {
        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->loadModel('dept');
        $this->loadModel('user');

        $super = common::hasPriv('screen', 'allAnnualData');

        $firstAction = $this->dao->select('*')->from(TABLE_ACTION)->orderBy('id')->limit(1)->fetch();
        $currentYear = date('Y');
        $firstYear   = empty($firstAction) ? $currentYear : substr($firstAction->date, 0, 4);

        /* Get years for use zentao. */
        $years = array();
        for($thisYear = $firstYear; $thisYear <= $currentYear; $thisYear ++) $years[$thisYear] = $thisYear;

        /* Init year when year is empty. */
        if(empty($year))
        {
            $year  = date('Y');
            $month = date('n');
            if($month <= $this->config->report->annualData['minMonth'])
            {
                $year -= 1;
                if(!isset($years[$year])) $year += 1;
            }
        }

        /* Get users and depts. */
        if($account)
        {
            $user = $this->user->getByID($account);
            $dept = $user->dept;
        }

        $userPairs = $this->dept->getDeptUserPairs($dept);
        $accounts  = !empty($user) ? array($user->account) : array_keys($userPairs);
        $users     = array('' => $this->lang->report->annualData->allUser) + $userPairs;

        $noDepartment = array('0' => '/' . $this->lang->dept->noDepartment);
        $depts        = $this->dept->getOptionMenu();
        if(!$super)
        {
            $depts = ($dept and isset($depts[$dept])) ? array($dept => $depts[$dept]) : $noDepartment;
        }
        else
        {
            $depts = array('' => $this->lang->report->annualData->allDept) + $depts;

            unset($depts[0]);
            $depts += $noDepartment;
        }

        /* Get annual data. */
        $data = array();
        if(!$account)
        {
            $data['users'] = $dept ? count($accounts) : (count($users) - 1);
        }
        else
        {
            $data['logins'] = $this->report->getUserYearLogins($accounts, $year);
        }

        $data['actions']       = $this->report->getUserYearActions($accounts, $year);
        $data['todos']         = $this->report->getUserYearTodos($accounts, $year);
        $data['contributions'] = $this->report->getUserYearContributions($accounts, $year);
        $data['executionStat'] = $this->report->getUserYearExecutions($accounts, $year);
        $data['productStat']   = $this->report->getUserYearProducts($accounts, $year);
        $data['storyStat']     = $this->report->getYearObjectStat($accounts, $year, 'story');
        $data['taskStat']      = $this->report->getYearObjectStat($accounts, $year, 'task');
        $data['bugStat']       = $this->report->getYearObjectStat($accounts, $year, 'bug');
        $data['caseStat']      = $this->report->getYearCaseStat($accounts, $year);

        $yearEfforts = $this->report->getUserYearEfforts($accounts, $year);
        $data['consumed'] = $yearEfforts->consumed;

        if(empty($dept) and empty($account)) $data['statusStat'] = $this->report->getAllTimeStatusStat();

        $contributionGroups = array();
        $maxCount           = 0;
        $contributions      = 0;
        foreach($years as $yearValue)
        {
            $contributionList  = $this->report->getUserYearContributions($accounts, $yearValue);
            $contributionCount = 0;
            $max               = 0;
            $radarData         = array('product' => 0, 'execution' => 0, 'devel' => 0, 'qa' => 0, 'other' => 0);
            foreach($contributionList as $objectType => $objectContributions)
            {
                $sum = array_sum($objectContributions);
                if($sum > $max) $max = $sum;
                $contributionCount += $sum;

                foreach($objectContributions as $actionName => $count)
                {
                    $radarTypes = isset($this->config->report->annualData['radar'][$objectType][$actionName]) ? $this->config->report->annualData['radar'][$objectType][$actionName] : array('other');
                    foreach($radarTypes as $radarType) $radarData[$radarType] += $count;
                }
                $contributionGroups[$yearValue] = $radarData;
            }

            if($yearValue == $year)
            {
                $maxCount      = $max;
                $contributions = $contributionCount;
            }
        }

        $this->view->title  = sprintf($this->lang->report->annualData->title, ($account ? zget($users, $account, '') : (($dept !== '') ? substr($depts[$dept], strrpos($depts[$dept], '/') + 1) : '')), $year);
        $this->view->data               = $data;
        $this->view->year               = $year;
        $this->view->users              = $users;
        $this->view->depts              = $depts;
        $this->view->years              = $years;
        $this->view->dept               = $dept;
        $this->view->account            = $account;
        $this->view->months             = $this->report->getYearMonths($year);
        $this->view->contributionGroups = $contributionGroups;
        $this->view->radarData          = $contributionGroups[$year];
        $this->view->maxCount           = $maxCount;
        $this->view->contributions      = $contributions;

        $this->display();
    }
}
