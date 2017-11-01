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
     * get user score list
     *
     * @param $account
     * @param $pager
     *
     * @access public
     * @return array|bool
     */
    public function getListByAccount($account, $pager)
    {
        return $this->dao->select('*')->from(TABLE_SCORE)->where('account')->eq($account)->orderBy('time_desc')->page($pager)->fetchAll();
    }

    /**
     * add score logs
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
        if(!isset($this->config->score->$module->$method) || empty($this->config->score->$module->$method)) return true;

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
                    if(!empty($rule['ext'][$param])) $rule['score'] = $rule['score'] + $rule['ext'][$param];
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
                    $createUser = $this->dao->findById($param)->from(TABLE_STORY)->fetch();
                    if(!empty($createUser))
                    {
                        $newRule          = $rule;
                        $newRule['score'] = $rule['ext']['createID'];
                        $this->saveScore($createUser->openedBy, $newRule, $module, $method, $desc, $objectID, $time);
                        unset($newRule);
                    }
                }
                break;
            case 'task':
                $desc .= 'ID:' . $param;

                if($method == 'finish')
                {
                    $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;
                    $task = $this->loadModel('task')->getById($param);

                    if(!empty($rule['ext'][$task->pri])) $rule['score'] = $rule['score'] + $rule['ext'][$task->pri];
                    if(!empty($task->estimate)) $rule['score'] = $rule['score'] + round(($task->consumed / 10 * $task->estimate / $task->consumed), 1);
                }
                break;
            case 'bug':
                if(is_numeric($param)) $desc .= 'ID:' . $param;

                if($method == 'createFormCase')
                {
                    $desc     = $this->lang->score->models['testcase'] . 'ID:' . $param;
                    $caseUser = $this->dao->findById($param)->from(TABLE_CASE)->fetch();
                    if(!empty($caseUser))
                    {
                        $user = $caseUser->openedBy;
                    }
                }

                if($method == 'saveTplModal') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

                if($method == 'confirmBug')
                {
                    $objectID = $param->id;
                    $user     = $param->openedBy;
                    $desc     .= 'ID:' . $param->id;
                    if(!empty($rule['ext'][$param->severity])) $rule['score'] = $rule['score'] + $rule['ext'][$param->severity];
                }

                if($method == 'resolve' && !empty($rule['ext'][$param->severity]))
                {
                    $objectID      = $param->id;
                    $rule['score'] = $rule['score'] + $rule['ext'][$param->severity];
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

                    //project PM
                    if(!empty($param->PM))
                    {
                        $rule['score'] = $param->end > date('Y-m-d', $timestamp) ? $rule['ext']['manager'][0] + $rule['ext']['manager'][1] : $rule['ext']['manager'][0];
                        $this->saveScore($param->PM, $rule, $module, $method, $desc, $objectID, $time);
                    }

                    //project team user
                    $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('project')->eq($param->id)->fetchGroup('account');
                    if(!empty($teams))
                    {
                        $users         = array_keys($teams);
                        $rule['score'] = $param->end > date('Y-m-d', $timestamp) ? $rule['ext']['member'][0] + $rule['ext']['member'][1] : $rule['ext']['member'][0];
                        foreach($users as $user)
                        {
                            if($user != $param->PM) $this->saveScore($user, $rule, $module, $method, $desc, $objectID, $time);
                        }
                    }
                }
                return true;
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
     * save user score
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
        if(!empty($rule['num']) || !empty($rule['time']))
        {
            if(empty($rule['time']))
            {
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('module')->eq($module)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
            }
            else
            {
                $timestamp = empty($time) ? time() : strtotime($time);
                $count     = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('time')->between(date('Y-m-d 0:0:0', $timestamp), date('Y-m-d 23:59:59', $timestamp))->andWhere('module')->eq($module)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
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
     * Score init from action
     *
     * @param int $lastID
     *
     * @access public
     * @return array
     */
    public function init($lastID = 0)
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
            return array('num' => 0, 'status' => 'finish');
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

        return array('status' => 'more', 'lastID' => max(array_keys($actions)));
    }

    /**
     * fix action type for score
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
     * get yesterday's score for user
     *
     * @access public
     * @return string
     */
    public function getNotice()
    {
        if(!isset($this->config->global->score) || empty($this->config->global->score)) return '';
        if($this->cookie->showNotice == strtotime(date('Y-m-d'))) return '';

        setcookie('showNotice', strtotime(date('Y-m-d')), $this->config->cookieLife, $this->config->webRoot);

        $yesterday = $this->dao->select("sum(score) as score")->from(TABLE_SCORE)->where('time')->between(date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day')))->andWhere('account')->eq($this->app->user->account)->fetch();

        $notice = empty($yesterday->score) ? '' : sprintf($this->lang->score->tips, $yesterday->score, $this->app->user->score);

        $notice .= $this->cookie->showNotice;

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
