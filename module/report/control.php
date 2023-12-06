<?php
/**
 * The control file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class report extends control
{
    /**
     * 项目ID。
     * The projectID.
     *
     * @var float
     * @access public
     */
    public $projectID = 0;

    /**
     * 构造函数。
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
     * 报告主页，跳转到年度数据。
     * The index of report, goto aunnual data.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('annualData'));
    }

    /**
     * 发送每日提醒邮件。
     * Send daily reminder mail.
     *
     * @access public
     * @return void
     */
    public function remind()
    {
        /* Check mail turnon, if the system doesn't turn on the e-mail function, return the tip. */
        $this->loadModel('mail');
        if(!$this->config->mail->turnon)
        {
            echo "You should turn on the Email feature first.\n";
            return false;
        }

        /* Get reminder, and send email. */
        $reminder = $this->reportZen->getReminder();
        foreach($reminder as $user => $mail)
        {
            /* Reset $this->output. */
            $this->clear();

            $mailTitle  = $this->lang->report->mailTitle->begin;
            $mailTitle .= isset($mail->bugs)      ? sprintf($this->lang->report->mailTitle->bug,      count($mail->bugs))      : '';
            $mailTitle .= isset($mail->tasks)     ? sprintf($this->lang->report->mailTitle->task,     count($mail->tasks))     : '';
            $mailTitle .= isset($mail->todos)     ? sprintf($this->lang->report->mailTitle->todo,     count($mail->todos))     : '';
            $mailTitle .= isset($mail->testTasks) ? sprintf($this->lang->report->mailTitle->testTask, count($mail->testTasks)) : '';
            $mailTitle  = rtrim($mailTitle, ',');

            /* Get email content and title.*/
            $this->view->mail      = $mail;
            $this->view->mailTitle = $mailTitle;

            $oldViewType = $this->viewType;
            if($oldViewType == 'json') $this->viewType = 'html';
            $mailContent    = $this->parse('report', 'dailyreminder');
            $this->viewType = $oldViewType;

            /* Send email.*/
            echo date('Y-m-d H:i:s') . " sending to {$user}, ";
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
     * 展示年度数据。
     * Show annual data.
     *
     * @param  string $year
     * @param  string $dept
     * @param  string $account
     * @access public
     * @return void
     */
    public function annualData(string $year = '', string $dept = '', string $account = '')
    {
        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');

        /* Assign annual data. */
        $this->reportZen->assignAnnualReport($year, $dept, $account);

        $this->view->account = $account;
        $this->display();
    }
}
