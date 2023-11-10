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
        $this->view->title = $this->lang->cron->common;
        $this->view->crons = $this->cron->getCrons();
        $this->display();
    }

    /**
     * Turnon cron.
     *
     * @access public
     * @return void
     */
    public function turnon()
    {
        $turnon = empty($this->config->global->cron) ? '1' : '0';
        $this->loadModel('setting')->setItem('system.common.global.cron', $turnon);
        return $this->sendSuccess(array('load' => inlink('index')));
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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('index'), 'closeModal' => true));
        }

        $this->view->title = $this->lang->cron->create . $this->lang->cron->common;
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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('index'), 'closeModal' => true));
        }

        $this->view->title = $this->lang->cron->edit . $this->lang->cron->common;
        $this->view->cron  = $this->cron->getById($cronID);
        $this->display();
    }

    /**
     * Toggle run cron.
     *
     * @param  int    $cronID
     * @param  string $status
     * @access public
     * @return void
     */
    public function toggle(int $cronID, string $status)
    {
        $this->cron->changeStatus($cronID, $status);
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Delete cron.
     *
     * @param  int    $cronID
     * @access public
     * @return void
     */
    public function delete($cronID)
    {
        $this->dao->delete()->from(TABLE_CRON)->where('id')->eq($cronID)->exec();
        return $this->sendSuccess(array('load' => true));
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
        $settings = $this->loadModel('setting')->getItems('owner=system&module=cron&sestion=run');
        foreach($settings as $setting)
        {
            if($setting->key == 'execId' && $setting->value == $execId) return true;
            if($setting->key == 'lastTime' && $setting->value < date('Y-m-d H:i:s', strtotime('-1 minute'))) return true;
        }

        return false;
    }
}
