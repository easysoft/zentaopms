<?php
/**
 * The control file of cron of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class cron extends control
{
    /**
     * Index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->title      = $this->lang->cron->common;
        $this->view->position[] = $this->lang->cron->common;

        $this->view->crons = $this->cron->getCrons();
        $this->display();
    }

    /**
     * Turnon cron.
     *
     * @access public
     * @return void
     */
    public function turnon($confirm = 'no')
    {
        $turnon = empty($this->config->global->cron) ? '1' : '0';
        if(!$turnon and $confirm == 'no') return print(js::confirm($this->lang->cron->confirmTurnon, inlink('turnon', "confirm=yes")));
        $this->loadModel('setting')->setItem('system.common.global.cron', $turnon);
        return print(js::reload('parent'));
    }

    /**
     * Open cron process.
     *
     * @access public
     * @return void
     */
    public function openProcess()
    {
        $this->display();
    }

    /**
     * Create cron.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->cron->create();
            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::locate(inlink('index'), 'parent'));
        }
        $this->view->title      = $this->lang->cron->create . $this->lang->cron->common;
        $this->view->position[] = html::a(inlink('index'), $this->lang->cron->common);
        $this->view->position[] = $this->lang->cron->create;

        $this->display();
    }

    /**
     * Edit cron.
     *
     * @param  int    $cronID
     * @access public
     * @return void
     */
    public function edit($cronID)
    {
        if($_POST)
        {
            $this->cron->update($cronID);
            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::locate(inlink('index'), 'parent'));
        }
        $this->view->title      = $this->lang->cron->edit . $this->lang->cron->common;
        $this->view->position[] = html::a(inlink('index'), $this->lang->cron->common);
        $this->view->position[] = $this->lang->cron->edit;

        $this->view->cron = $this->cron->getById($cronID);
        $this->display();
    }

    /**
     * Toggle run cron.
     *
     * @param  int    $cronID
     * @param  int    $status
     * @access public
     * @return void
     */
    public function toggle($cronID, $status)
    {
        $this->cron->changeStatus($cronID, $status);
        return print(js::reload('parent'));
    }

    /**
     * Delete cron.
     *
     * @param  int    $cronID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($cronID, $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->cron->confirmDelete, inlink('delete', "cronID=$cronID&confirm=yes")));

        $this->dao->delete()->from(TABLE_CRON)->where('id')->eq($cronID)->exec();
        return print(js::reload('parent'));
    }

    /**
     * 使用Ajax请求执行定时任务.
     * Ajax execute cron.
     *
     * @param  bool $restart
     * @access public
     * @return void
     */
    public function ajaxExec($restart = false)
    {
        if(empty($this->config->global->cron)) return;

        if('cli' !== PHP_SAPI)
        {
            ignore_user_abort(true);
            set_time_limit(0);
            session_write_close();
        }

        $this->loadModel('common');

        $execId = mt_rand();
        while(true)
        {
            if($restart || $this->canSchedule($execId))
            {
                $this->cron->schedule($execId);
                $this->cron->execTasks($execId);

                $restart = false;
                sleep(20);
            }
            else
            {
                $this->cron->execTasks($execId);
                return;  // If no task in queue, executor will exit.
            }
        }
    }

    /**
     * 检查该execId是否可以进行调度(最近1分钟没有其他进程在调度).
     * Check the execId can schedule(No other execId scheduled 10 minutes ago).
     *
     * @param  int    $execId
     * @access protected
     * @return bool
     */
    protected function canSchedule($execId)
    {
        $settings = $this->dao->select('`key`,`value`')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('cron')->andWhere('section')->eq('run')->fetchPairs();
        if(!isset($settings['execId']) || $settings['execId'] == $execId) return true;
        if(!isset($settings['lastTime']) || $settings['lastTime'] < date('Y-m-d H:i:s', strtotime('-1 minute'))) return true;

        return false;
    }
}
