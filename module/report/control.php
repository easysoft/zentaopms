<?php
/**
 * The control file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class report extends control
{
    /**
     * The index of report, goto project deviation.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('productinfo')); 
    }
    
    /**
     * Project deviation report.
     * 
     * @access public
     * @return void
     */
    public function projectDeviation()
    {
        $this->view->title      = $this->lang->report->projectDeviation;
        $this->view->position[] = $this->lang->report->projectDeviation;
        $this->view->projects   = $this->report->getProjects();
        $this->view->submenu    = 'project';
        $this->display();
    }

    /**
     * Product information report.
     * 
     * @access public
     * @return void
     */
    public function productInfo()
    {
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->app->loadLang('story');
        $this->view->title      = $this->lang->report->productInfo;
        $this->view->position[] = $this->lang->report->productInfo;
        $this->view->products   = $this->report->getProducts();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->submenu    = 'product';
        $this->display();
    }

    /**
     * Bug summary report.
     * 
     * @param  int    $begin 
     * @param  int    $end 
     * @access public
     * @return void
     */
    public function bugSummary($begin = 0, $end = 0)
    {
        $this->app->loadLang('bug');
        if($begin == 0) 
        {
            $begin = date('Y-m-d', strtotime('last month'));
        }
        else
        {
            $begin = date('Y-m-d', strtotime($begin));
        }
        if($end == 0)
        {
            $end = date('Y-m-d', strtotime('now'));
        }
        else
        {
            $end = date('Y-m-d', strtotime($end));
        }
        $this->view->title      = $this->lang->report->bugSummary;
        $this->view->position[] = $this->lang->report->bugSummary;
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->bugs       = $this->report->getBugs($begin, $end);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->submenu    = 'test';
        $this->display(); 
    }

    /**
     * Bug assign report.
     * 
     * @access public
     * @return void
     */
    public function bugAssign()
    {
        $this->view->title      = $this->lang->report->bugAssign;
        $this->view->position[] = $this->lang->report->bugAssign;
        $this->view->submenu    = 'test';
        $this->view->assigns    = $this->report->getBugAssign();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->display(); 
    }

    /**
     * Workload report.
     * 
     * @access public
     * @return void
     */
    public function workload()
    {
        $this->view->title      = $this->lang->report->workload;
        $this->view->position[] = $this->lang->report->workload;
        $this->view->workload   = $this->report->getWorkload();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->submenu    = 'staff';
        $this->display();
    }

    /**
     * Send daily reminder mail.
     * 
     * @access public
     * @return void
     */
    public function remind()
    {
        if($this->config->report->dailyreminder->bug)  $bugs  = $this->report->getUserBugs();
        if($this->config->report->dailyreminder->task) $tasks = $this->report->getUserTasks();
        if($this->config->report->dailyreminder->todo) $todos = $this->report->getUserTodos();
        
        $reminder = array();

        $users = array_unique(array_merge(array_keys($bugs), array_keys($tasks), array_keys($todos)));
        if(!empty($users)) foreach($users as $user) $reminder[$user] = new stdclass();

        if(!empty($bugs))  foreach($bugs as $user => $bug)   $reminder[$user]->bugs  = $bug;
        if(!empty($tasks)) foreach($tasks as $user => $task) $reminder[$user]->tasks = $task;
        if(!empty($todos)) foreach($todos as $user => $todo) $reminder[$user]->todos = $todo;

        $this->loadModel('mail');

        /* Check mail turnon.*/
        if(!$this->config->mail->turnon) die("You should turn on the Email feature first.\n");

        foreach($reminder as $user => $mail)
        {
            /* Reset $this->output. */
            $this->clear();

            /* Get email content and title.*/
            $this->view->mail = $mail;
            $mailContent = $this->parse('report', 'dailyreminder');
            $mailTitle   = $this->lang->report->mailtitle->begin;
            $mailTitle  .= isset($mail->bugs)  ? sprintf($this->lang->report->mailtitle->bug,  count($mail->bugs))  : '';
            $mailTitle  .= isset($mail->tasks) ? sprintf($this->lang->report->mailtitle->task, count($mail->tasks)) : '';
            $mailTitle  .= isset($mail->todos) ? sprintf($this->lang->report->mailtitle->todo, count($mail->todos)) : '';
            $mailTitle   = rtrim($mailTitle, ',');
            
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
}
