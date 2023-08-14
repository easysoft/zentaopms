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
     * Ajax exec cron.
     *
     * @param  bool    $restart
     * @access public
     * @return void
     */
    public function ajaxExec($restart = false)
    {
        if ('cli' !== PHP_SAPI)
        {
            ignore_user_abort(true);
            set_time_limit(0);
            session_write_close();
        }
        /* Check cron turnon. */
        if(empty($this->config->global->cron)) return;

        /* Create restart tag file. */
        $restartTag = $this->app->getCacheRoot() . 'restartcron';
        if($restart) touch($restartTag);

        /* make cron status to running. */
        $configID = $this->cron->getConfigID();
        $configID = $this->cron->markCronStatus('running', $configID);
        $this->dao->update(TABLE_CRON)->set('lastTime')->eq(date(DT_DATETIME1))->where('id')->eq(1)->exec();

        /* Get and parse crons. */
        $crons       = $this->cron->getCrons('nostop');
        $parsedCrons = $this->cron->parseCron($crons);

        $this->cron->logCron("The cron process created.\n");
        $this->loadModel('common');
        $startedTime = time();
        while(true)
        {
            dao::$cache = array();

            /* When cron is null then die. */
            if(empty($crons)) break;
            if(empty($parsedCrons)) break;
            if(!$this->cron->getTurnon())
            {
                $this->cron->logCron("The cron process turned off.\n");
                break;
            }

            /* Die old process when restart. */
            if(file_exists($restartTag) and !$restart)
            {
                $this->cron->logCron("The cron process restarted.\n");
                return unlink($restartTag);
            }
            $restart = false;

            /* Run crons. */
            $now = new datetime('now');
            unset($_SESSION['company']);
            unset($this->app->company);
            $this->common->setCompany();
            $this->common->loadConfigFromDB();
            foreach($parsedCrons as $id => $cron)
            {
                $cronInfo = $this->cron->getById($id);
                /* Skip empty and stop cron.*/
                if(empty($cronInfo) or $cronInfo->status == 'stop') continue;
                /* Skip cron that status is running and run time is less than max. */
                if($cronInfo->status == 'running' and (time() - strtotime($cronInfo->lastTime)) < $this->config->cron->maxRunTime) continue;
                /* Skip cron that last time is more than this cron time. */
                if('cli' === PHP_SAPI)
                {
                    if($cronInfo->lastTime >= $cron['time']->format(DT_DATETIME1)) continue;
                }
                else
                {
                    if($cronInfo->lastTime > $cron['time']->format(DT_DATETIME1)) return;
                }

                if($now > $cron['time'])
                {
                    if(!$this->cron->changeStatusRunning($id)) continue;
                    $parsedCrons[$id]['time'] = $cron['cron']->getNextRunDate();

                    /* Execution command. */
                    $output = '';
                    $return = '';
                    if($cron['command'])
                    {
                        /* Save log of executing cron. */
                        $time = $now->format('Y-m-d H:i:s');
                        $log  = "\n$time task " . $id . " executing,\ncommand: $cron[command]\n";
                        $this->cron->logCron($log);

                        if(isset($crons[$id]) and $crons[$id]->type == 'zentao')
                        {
                            parse_str($cron['command'], $params);
                            if(isset($params['moduleName']) and isset($params['methodName']))
                            {
                                $this->app->loadConfig($params['moduleName']);
                                $output = $this->fetch($params['moduleName'], $params['methodName']);
                            }
                        }
                        elseif(isset($crons[$id]) and $crons[$id]->type == 'system')
                        {
                            exec($cron['command'], $output, $return);
                            if($output) $output = join("\n", $output);
                        }

                        /* Save log of executed cron. */
                        $time   = $now->format('Y-m-d H:i:s');
                        $output = $output;

                        $log = "\n$time task " . $id . " executed,\ncommand: $cron[command]\nreturn : $return\noutput : $output\n";
                        $this->cron->logCron($log);
                        unset($log);
                    }

                    /* Revert cron status. */
                    $this->cron->changeStatus($id, 'normal');
                }
            }

            /* Check whether the task change. */
            $newCrons = $this->cron->getCrons('nostop');
            $changed  = $this->cron->checkChange();
            if(count($newCrons) != count($crons) or $changed)
            {
                $crons       = $newCrons;
                $parsedCrons = $this->cron->parseCron($newCrons);
            }

            /* Sleep some seconds. */
            $sleepTime = 60 - ((time() - strtotime($now->format('Y-m-d H:i:s'))) % 60);
            sleep($sleepTime);

            if(((time() - $startedTime) / 3600 / 24) >= $this->config->cron->maxRunDays) break;
        }

        /* Revert cron status to stop. */
        $this->cron->markCronStatus('stop', $configID);
    }
}
