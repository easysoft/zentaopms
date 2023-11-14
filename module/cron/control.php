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
                $this->schedule($execId);
                $this->execTasks($execId);

                $restart = false;
                sleep(20);
            }
            else
            {
                $this->execTasks($execId);
                return;  // If no task in queue, executor will exit.
            }
        }
    }

    /**
     * Schedule cron task by RoadRunner.
     *
     * @access public
     * @return void
     */
    public function rrSchedule()
    {
        if('cli' !== PHP_SAPI) return;

        set_time_limit(0);
        session_write_close();

        $this->loadModel('common');

        $execId = mt_rand();
        while(true)
        {
            if(empty($this->config->global->cron) || !$this->canSchedule($execId))
            {
                sleep(60);
                continue;
            }

            $this->schedule($execId);
            sleep(30);
        }
    }

    /**
     * Consume cron task by RoadRunner.
     *
     * @access public
     * @return void
     */
    public function rrConsume()
    {
        if('cli' !== PHP_SAPI) return;

        set_time_limit(0);
        session_write_close();

        $this->loadModel('common');

        while(true)
        {
            if(empty($this->config->global->cron))
            {
                sleep(60);
                continue;
            }

            $this->execTasks(mt_rand());
            sleep(10);
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

    /**
     * 调度生成队列任务.
     * Schedule, push tasks to queue.
     *
     * @param  int    $execId
     * @access public
     * @return bool
     */
    public function schedule($execId)
    {
        $now = date(DT_DATETIME1);

        $this->loadModel('setting')->setItem('system.cron.run.execId', $execId);
        $this->setting->setItem('system.cron.run.lastTime', $now);

        /* Get and parse crons. */
        $tasks = $this->dao->select('cron,MAX(`createdDate`) `datetime`')->from(TABLE_QUEUE)->groupBy('cron')->fetchAll('cron');
        $crons = $this->cron->getCrons('nostop');
        foreach($crons as $index => $cron)
        {
            $cron->datetime = isset($tasks[$cron->id]) ? $tasks[$cron->id]->datetime : '1970-01-01';
        }

        $parsedCrons = $this->cron->parseCron($crons);

        foreach($parsedCrons as $id => $cron)
        {
            $cronInfo = $crons[$id];

            /* Skip empty and stop cron.*/
            if(empty($cronInfo) || $cronInfo->status == 'stop') continue;

            if(!$cron['command'] || !isset($crons[$id])) continue;

            /* Check time. */
            if($now < $cron['time']->format(DT_DATETIME1)) continue;

            /* Push task into queue. */
            $task = new stdclass();
            $task->cron        = $id;
            $task->type        = $crons[$id]->type;
            $task->command     = $cron['command'];
            $task->createdDate = $now;
            $this->dao->insert(TABLE_QUEUE)->data($task)->exec();

            $log = date('G:i:s') . " schedule\ncronId: $id\nexecId: $execId\noutput: push task to queue\n\n";
            $this->cron->logCron($log);
        }
    }

    /**
     * 执行所有定时任务.
     * Execute all tasks.
     *
     * @param  int    $execId
     * @access public
     * @return bool
     */
    public function execTasks($execId)
    {
        while(true)
        {
            $task = $this->dao->select('*')->from(TABLE_QUEUE)->where('status')->eq('wait')->andWhere('command')->ne('')->orderBy('createdDate')->fetch();
            if(!$task) break;
            $this->cron->logCron(strval($task->id) . "\n");

            $this->execTask($execId, $task);
        }
    }

    /**
     * 执行一个定时任务.
     * Execute one task.
     *
     * @param  int    $execId
     * @param  object $task
     * @access public
     * @return bool
     */
    public function execTask($execId, $task)
    {
        /* Other executor may execute the task at the same time，so we mark execId and wait 500ms to check whether we own it. */
        $this->dao->update(TABLE_QUEUE)->set('status')->eq('doing')->set('execId')->eq($execId)->where('id')->eq($task->id)->exec();
        usleep(500);

        $task = $this->dao->select('*')->from(TABLE_QUEUE)->where('id')->eq($task->id)->fetch();
        if($task->execId != $execId) return;

        /* Execution command. */
        $output = '';
        $return = '';

        unset($_SESSION['company']);
        unset($this->app->company);
        $this->common->setCompany();
        $this->common->loadConfigFromDB();

        try
        {
            if($task->type == 'zentao')
            {
                parse_str($task->command, $params);
                if(isset($params['moduleName']) and isset($params['methodName']))
                {
                    $this->app->loadConfig($params['moduleName']);
                    $output = $this->fetch($params['moduleName'], $params['methodName']);
                }
            }
            elseif($task->type == 'system')
            {
                exec($task->command, $out, $return);
                if($out) $output = implode(PHP_EOL, $out);
            }
        }
        catch(EndResponseException $endResponseException)
        {
            $output = $endResponseException->getContent();
        }
        catch(Exception $e)
        {
            $output = $e;
        }

        $this->dao->update(TABLE_QUEUE)->set('status')->eq('done')->where('id')->eq($task->id)->exec();
        $this->dao->update(TABLE_CRON)->set('lastTime')->eq(date(DT_DATETIME1))->where('id')->eq($task->cron)->exec();

        $log = date('G:i:s') . " execute\ncronId: {$task->cron}\nexecId: $execId\noutput: taskId:{$task->id}.\ncommand: {$task->command}.\nreturn : $return.\noutput : $output\n\n";
        $this->cron->logCron($log);

        return true;
    }
}
