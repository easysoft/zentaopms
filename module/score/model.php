<?php
/**
 * The model file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: model.php $
 * @link        http://www.zentao.net
 */
class scoreModel extends model
{
    /**
     * Get user score list.
     *
     * @param string $account
     * @param object $pager
     *
     * @access public
     * @return array
     */
    public function getListByAccount($account, $pager)
    {
        return $this->dao->select('*')->from(TABLE_SCORE)->where('account')->eq($account)->orderBy('time_desc,id_desc')->page($pager)->fetchAll();
    }

    /**
     * Add score logs.
     *
     * @param string $module
     * @param string $method
     * @param string $param
     * @param string $account
     * @param string $time
     *
     * @access public
     * @return bool
     */
    public function create($module = '', $method = '', $param = '', $account = '', $time = '')
    {
        if(empty($this->config->score->$module->$method)) return true;

        $rule     = $this->config->score->$module->$method;
        $desc     = $this->lang->score->models[$module];
        $user     = empty($account) ? $this->app->user->account : $account;
        $time     = empty($time) ? helper::now() : $time;
        $objectID = is_numeric($param) ? $param : 0;

        switch($module)
        {
            case 'user':
                if($method == 'login') $desc = $this->lang->score->methods[$module][$method];

                if($method == 'changePassword')
                {
                    if(!empty($this->config->score->extended->changePassword['strength'][$param])) $rule['score'] = $rule['score'] + $this->config->score->extended->changePassword['strength'][$param];
                    $desc = $this->lang->score->methods[$module][$method];
                }
                break;
            case 'doc':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'todo':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'story':
                $desc .= 'ID:' . $param;

                if($method == 'close')
                {
                    $openedBy = $this->dao->findById($param)->from(TABLE_STORY)->fetch('openedBy');
                    if(!empty($openedBy))
                    {
                        $newRule          = $rule;
                        $newRule['score'] = $this->config->score->extended->storyClose['createID'];
                        $this->saveScore($openedBy, $newRule, $module, $method, $desc, $objectID, $time);
                        unset($newRule);
                    }
                }
                break;
            case 'task':
                $desc .= 'ID:' . $param;

                if($method == 'finish')
                {
                    $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

                    /* Check child task. */
                    $parentTask = $this->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($param)->fetch('id');
                    if(!empty($parentTask)) return true;

                    $task = $this->loadModel('task')->getById($param);
                    if(!empty($this->config->score->extended->taskFinish['pri'][$task->pri])) $rule['score'] = $rule['score'] + $this->config->score->extended->taskFinish['pri'][$task->pri];
                    if(!empty($task->estimate)) $rule['score'] = $rule['score'] + round(($task->consumed / 10 * $task->estimate / $task->consumed));
                }
                break;
            case 'bug':
                if(is_numeric($param)) $desc .= 'ID:' . $param;

                if($method == 'createFormCase')
                {
                    $desc     = $this->lang->score->models['testcase'] . 'ID:' . $param;
                    $openedBy = $this->dao->findById($param)->from(TABLE_CASE)->fetch('openedBy');
                    if(!empty($openedBy)) $user = $openedBy;
                }

                if($method == 'saveTplModal') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

                if($method == 'confirmBug')
                {
                    $objectID = $param->id;
                    $user     = $param->openedBy;
                    $desc     .= 'ID:' . $param->id;
                    if(!empty($this->config->score->extended->bugConfirmBug['severity'][$param->severity])) $rule['score'] = $rule['score'] + $this->config->score->extended->bugConfirmBug['severity'][$param->severity];
                }

                if($method == 'resolve' && !empty($this->config->score->extended->bugResolve['severity'][$param->severity]))
                {
                    $objectID      = $param->id;
                    $rule['score'] = $rule['score'] + $this->config->score->extended->bugResolve['severity'][$param->severity];
                }
                break;
            case 'testTask':
                if($method == 'runCase') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;
                break;
            case 'build':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'project':
                if($method == 'create') $desc .= 'ID:' . $param;
                if($method == 'close')
                {
                    $desc      = $this->lang->score->methods[$module][$method] . ',' . $desc . 'ID:' . $param->id;
                    $objectID  = $param->id;
                    $timestamp = empty($time) ? time() : strtotime($time);

                    /* Project PM. */
                    if(!empty($param->PM))
                    {
                        $rule['score'] = $this->config->score->extended->projectClose['manager']['close'];
                        if($param->end > date('Y-m-d', $timestamp)) $rule['score'] += $this->config->score->extended->projectClose['manager']['in'];
                        $this->saveScore($param->PM, $rule, $module, $method, $desc, $objectID, $time);
                    }

                    /* Project team user. */
                    $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('project')->eq($param->id)->fetchPairs();
                    if(!empty($teams))
                    {
                        $rule['score'] = $this->config->score->extended->projectClose['member']['close'];
                        if($param->end > date('Y-m-d', $timestamp)) $rule['score'] += $this->config->score->extended->projectClose['member']['in'];
                        foreach($teams as $user)
                        {
                            if($user != $param->PM) $this->saveScore($user, $rule, $module, $method, $desc, $objectID, $time);
                        }
                    }

                    return true; //When the project is closed, no more user get score.
                }
                break;
            case 'productplan':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'release':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'testcase':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'search':
                if($method == 'saveQuery') $desc .= 'ID:' . $param;
                if($method == 'saveQueryAdvanced') $desc = $this->lang->score->methods[$module][$method];
                break;
            case 'ajax':
                $desc = $this->lang->score->methods[$module][$method];
                break;
        }
        $this->saveScore($user, $rule, $module, $method, $desc, $objectID, $time);
    }

    /**
     * Save user score.
     *
     * @param string $account
     * @param array  $rule
     * @param string $module
     * @param string $method
     * @param string $desc
     * @param int    $objectID
     * @param string $time
     *
     * @access private
     * @return bool
     */
    private function saveScore($account = '', $rule = array(), $module = '', $method = '', $desc = '', $objectID = 0, $time = '')
    {
        if(!empty($rule['times']) || !empty($rule['hour']))
        {
            if(empty($rule['hour']))
            {
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('module')->eq($module)->andWhere('method')->eq($method)->count();
                if($count >= $rule['times']) return true;
            }
            else
            {
                $timestamp = empty($time) ? time() : strtotime($time);
                $count     = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)
                             ->andWhere('time')->between(date('Y-m-d 00:00:00', $timestamp), date('Y-m-d 23:59:59', $timestamp))
                             ->andWhere('module')->eq($module)
                             ->andWhere('method')->eq($method)
                             ->count();
                if($count >= $rule['times']) return true;
            }
        }

        $this->dao->begin();
        try
        {
            $user = $this->loadModel('user')->getById($account);

            $data           = new stdClass();
            $data->account  = $account;
            $data->module   = $module;
            $data->method   = $method;
            $data->desc     = $desc;
            $data->objectID = $objectID;
            $data->before   = $user->score;
            $data->score    = $rule['score'];
            $data->after    = $user->score + $rule['score'];
            $data->time     = empty($time) ? helper::now() : $time;
            $this->dao->insert(TABLE_SCORE)->data($data)->exec();

            $this->dao->query("UPDATE " . TABLE_USER . " SET `score`=`score` + " . $rule['score'] . ",`scoreLevel`=`scoreLevel` + " . $rule['score'] . " WHERE `account`='" . $account . "'");

            $this->dao->commit();
        }
        catch(ErrorException $e)
        {
            $this->dao->rollBack();
        }
    }

    /**
     * Score reset.
     *
     * @param int $lastID
     *
     * @access public
     * @return array
     */
    public function reset($lastID = 0)
    {
        if($lastID == 0)
        {
            $this->dao->query("UPDATE " . TABLE_USER . " SET `score`=0,`scoreLevel`=0");
            $this->dao->query("TRUNCATE TABLE " . TABLE_SCORE);
        }

        $actions = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->gt($lastID)->orderBy('id_asc')->limit(100)->fetchAll('id');

        if(empty($actions))
        {
            $this->loadModel('setting')->setItem('system.common.global.scoreInit', 1);
            return array('number' => 0, 'status' => 'finish');
        }

        foreach($actions as $action)
        {
            $param = $action->objectID;
            if($action->objectType == 'project' && $action->action == 'closed') $param = $this->dao->findById($action->objectID)->from(TABLE_PROJECT)->fetch();
            if($action->objectType == 'bug')
            {
                $bug = $this->dao->findById($action->objectID)->from(TABLE_BUG)->fetch();
                if(!empty($bug->case)) $action->action = 'createFormCase';
                if($action->action == 'bugconfirmed' || $action->action == 'resolved') $param = $bug;
            }
            if($action->objectType == 'case') $action->objectType = 'testcase';
            $this->create($action->objectType, $this->fixKey($action->action), $param, $action->actor, $action->date);
        }

        return array('status' => 'more', 'lastID' => max(array_keys($actions)), 'number' => count($actions));
    }

    /**
     * Fix action type for score.
     *
     * @param $string
     *
     * @access private
     * @return mixed
     */
    private function fixKey($string)
    {
        $strings = array('created' => 'create', 'opened' => 'create', 'closed' => 'close', 'finished' => 'finish', 'bugconfirmed' => 'confirmBug', 'resolved' => 'resolve');
        return isset($strings[$string]) ? $strings[$string] : $string;
    }

    /**
     * Get yesterday's score for user.
     *
     * @access public
     * @return string
     */
    public function getNotice()
    {
        if(!isset($this->config->global->score) || empty($this->config->global->score)) return '';
        if(date('Y-m-d', $this->app->user->lastTime) == helper::today()) return '';

        $this->app->user->lastTime = time();

        $score = $this->dao->select("sum(score) as score")->from(TABLE_SCORE)->where('time')->between(date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day')))->andWhere('account')->eq($this->app->user->account)->fetch('score');

        $notice = empty($score) ? '' : sprintf($this->lang->score->tips, $score, $this->app->user->score);

        $fullNotice = <<<EOT
<div id='noticeAttend' class='alert alert-success with-icon alert-dismissable' style='width:280px; position:fixed; bottom:25px; right:15px; z-index: 9999;' id='planInfo'>    
   <i class='icon icon-diamond'>  </i>
   <div class='content'>{$notice}</div>
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
 </div>
EOT;
        return empty($notice) ? '' : $fullNotice;
    }
}
