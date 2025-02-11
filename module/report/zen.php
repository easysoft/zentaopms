<?php
declare(strict_types=1);
/**
 * The zen file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     report
 * @link        https://www.zentao.net
 */
class reportZen extends report
{
    /**
     * 获取每日提醒邮件的内容。
     * Get the content of daily reminder mail.
     *
     * @access public
     * @return array
     */
    protected function getReminder(): array
    {
        /* Get reminder data. */
        $bugs = $tasks = $todos = $testTasks = array();
        if($this->config->report->dailyreminder->bug)      $bugs      = $this->report->getUserBugs();
        if($this->config->report->dailyreminder->task)     $tasks     = $this->report->getUserTasks();
        if($this->config->report->dailyreminder->todo)     $todos     = $this->report->getUserTodos();
        if($this->config->report->dailyreminder->testTask) $testTasks = $this->report->getUserTestTasks();

        /* Get user who need reminders, and set reminder data to them. */
        $reminder = array();
        $users    = array_unique(array_merge(array_keys($bugs), array_keys($tasks), array_keys($todos), array_keys($testTasks)));
        if(!empty($users))     foreach($users     as $user)              $reminder[$user] = new stdclass();
        if(!empty($bugs))      foreach($bugs      as $user => $bug)      $reminder[$user]->bugs      = $bug;
        if(!empty($tasks))     foreach($tasks     as $user => $task)     $reminder[$user]->tasks     = $task;
        if(!empty($todos))     foreach($todos     as $user => $todo)     $reminder[$user]->todos     = $todo;
        if(!empty($testTasks)) foreach($testTasks as $user => $testTask) $reminder[$user]->testTasks = $testTask;
        return $reminder;
    }

    /**
     * 指派年度报告。
     * Assign annual data.
     *
     * @param  string $year
     * @param  string $dept
     * @param  string $account
     * @access public
     * @return void
     */
    protected function assignAnnualReport(string $year, string $dept, string $account): void
    {
        /* Assign dept, year, years, depts, accounts and users. */
        list($years, $userCount, $accounts, $dept, $year) = $this->assignAnnualBaseData($account, $dept, $year);

        /* Assign annual data. */
        $this->assignAnnualData($year, (int)$dept, $account, $accounts, $userCount);

        $deptEmpty = (int)$dept && empty($accounts);

        /* Get contribution releated data. */
        $contributionGroups = array();
        $maxCount = 0;
        foreach($years as $yearValue)
        {
            $contributionList  = $deptEmpty ? array() : $this->report->getUserYearContributions($accounts, $yearValue);
            $max = 0;
            $radarData         = array('product' => 0, 'execution' => 0, 'devel' => 0, 'qa' => 0, 'other' => 0);

            if(!empty($contributionList))
            {
                foreach($contributionList as $objectType => $objectContributions)
                {
                    $sum = array_sum($objectContributions);
                    if($sum > $max) $max = $sum;

                    foreach($objectContributions as $actionName => $count)
                    {
                        $radarTypes = isset($this->config->report->annualData['radar'][$objectType][$actionName]) ? $this->config->report->annualData['radar'][$objectType][$actionName] : array('other');
                        foreach($radarTypes as $radarType) $radarData[$radarType] += $count;
                    }
                    $contributionGroups[$yearValue] = $radarData;
                }
            }
            else
            {
                $contributionGroups[$yearValue] = $radarData;
            }
            /* If year value is selected, set maxCount. */
            if($yearValue == $year) $maxCount = $max;
        }

        $this->view->dept               = $dept;
        $this->view->year               = $year;
        $this->view->years              = $years;
        $this->view->months             = $this->report->getYearMonths($year);
        $this->view->contributionGroups = $contributionGroups;
        $this->view->radarData          = $contributionGroups[$year];
        $this->view->maxCount           = $maxCount;
        $this->view->contributionCount  = $this->report->getUserYearContributionCount($accounts, $year);
    }

    /**
     * 指派年度报告的基础数据。
     * Assign annual base data.
     *
     * @param  string $account
     * @param  string $dept
     * @param  string $year
     * @access protected
     * @return array
     */
    protected function assignAnnualBaseData(string $account, string $dept, string $year): array
    {
        /* Get users. */
        if($account)
        {
            $user = $this->loadModel('user')->getByID($account);
            $dept = $user->dept;
        }
        $userPairs = $this->loadModel('dept')->getDeptUserPairs((int)$dept);
        $accounts  = !empty($user) ? array($user->account) : array_keys($userPairs);
        if(!(int)$dept && empty($account)) $accounts = array(); // 如果dept=0，且没有选具体的人，置空让数据查所有人，否则离职的人的数据查不到

        $users     = array('' => $this->lang->report->annualData->allUser) + $userPairs;

        $firstAction = $this->loadModel('action')->getFirstAction();
        $currentYear = date('Y');
        $firstYear   = empty($firstAction) ? $currentYear : substr($firstAction->date, 0, 4);

        /* Get years for use zentao. */
        $years = array();
        for($thisYear = $firstYear; $thisYear <= $currentYear; $thisYear ++) $years[$thisYear] = (string)$thisYear;

        /* Init year when year is empty. */
        if(empty($year))
        {
            $year  = date('Y');
            $month = date('n');
            if($month <= $this->config->report->annualData['minMonth'] && isset($years[$year -1])) $year -= 1;
        }

        /* Get depts. */
        $depts        = $this->loadModel('dept')->getOptionMenu();
        $noDepartment = array('0' => '/' . $this->lang->dept->noDepartment);
        if(!common::hasPriv('screen', 'allAnnualData'))
        {
            $depts = $dept && isset($depts[$dept]) ? array($dept => $depts[$dept]) : $noDepartment;
        }
        else
        {
            unset($depts[0]);
            $depts = array('0' => $this->lang->report->annualData->allDept) + $depts;
        }

        $who = '';
        if(isset($depts[$dept]))
        {
            $who = $depts[$dept];
            if(strpos($who, '/') !== false) $who = substr($who, strrpos($who, '/') + 1);
        }
        if($account) $who = zget($users, $account, '');

        $this->view->title = sprintf($this->lang->report->annualData->title, $who, $year);
        $this->view->depts = $depts;
        $this->view->users = $users;
        return array($years, count($users) - 1, $accounts, $dept, (string)$year);
    }

    /**
     * 指派年度数据。
     * Assign annual data.
     *
     * @param  string     $year
     * @param  string|int $dept
     * @param  string     $account
     * @param  array      $accounts
     * @param  int        $userCount
     * @access protected
     * @return void
     */
    protected function assignAnnualData(string $year, string|int $dept, string $account, array $accounts, int $userCount): void
    {
        $data = array();
        if(!$account)
        {
            $data['users'] = $dept ? count($accounts) :  $userCount;
        }
        else
        {
            $data['logins'] = $this->report->getUserYearLogins($accounts, $year);
        }

        $deptEmpty = (int)$dept && empty($accounts);

        $data['actions']       = $deptEmpty ? 0 : $this->report->getUserYearActions($accounts, $year);
        $data['todos']         = $deptEmpty ? (object)array('count' => 0, 'undone' => 0, 'done' => 0) : $this->report->getUserYearTodos($accounts, $year);
        $data['contributions'] = $deptEmpty ? array() : $this->report->getUserYearContributions($accounts, $year);
        $data['executionStat'] = $deptEmpty ? array() : $this->report->getUserYearExecutions($accounts, $year);
        $data['productStat']   = $deptEmpty ? array() : $this->report->getUserYearProducts($accounts, $year);
        $data['storyStat']     = $deptEmpty ? array('statusStat' => array(), 'actionStat' => array()) : $this->report->getYearObjectStat($accounts, $year, 'story');
        $data['taskStat']      = $deptEmpty ? array('statusStat' => array(), 'actionStat' => array()) : $this->report->getYearObjectStat($accounts, $year, 'task');
        $data['bugStat']       = $deptEmpty ? array('statusStat' => array(), 'actionStat' => array()) : $this->report->getYearObjectStat($accounts, $year, 'bug');
        $data['caseStat']      = $deptEmpty ? array('resultStat' => array(), 'actionStat' => array()) : $this->report->getYearCaseStat($accounts, $year);

        $yearEfforts = $this->report->getUserYearEfforts($accounts, $year);
        $data['consumed'] = $deptEmpty ? 0 : $yearEfforts->consumed;

        if(empty($dept) && empty($account)) $data['statusStat'] = $this->report->getAllTimeStatusStat();

        $this->view->data = $data;
    }
}

