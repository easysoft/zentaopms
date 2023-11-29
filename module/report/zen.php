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
}

