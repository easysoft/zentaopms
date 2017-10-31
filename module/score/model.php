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
    public static $num = 0;

    /**
     * get user score logs
     *
     * @param $pager
     *
     * @access public
     * @return array|bool
     */
    public function getScores($pager)
    {
        $scores = $this->dao->select('*')->from(TABLE_SCORE)->where('account')->eq($this->app->user->account)->orderBy('time_desc,id_desc')->page($pager)->fetchAll();
        return empty($scores) ? array() : $scores;
    }

    /**
     * add score logs
     *
     * @param string $model
     * @param string $method
     * @param string $param
     * @param string $account
     * @param string $time
     *
     * @access public
     * @return bool
     */
    public function score($model = '', $method = '', $param = '', $account = '', $time = '')
    {
        if(!isset($this->config->score->model[$model][$method]) || empty($this->config->score->model[$model][$method])) return true;
        $rule     = $this->config->score->model[$model][$method];
        $desc     = $this->lang->score->models[$model];
        $user     = empty($account) ? $this->app->user->account : $account;
        $time     = empty($time) ? helper::now() : $time;
        $objectID = is_numeric($param) ? $param : 0;
        switch($model)
        {
            case 'user':
                if($method == 'login') $desc = $this->lang->score->methods[$model][$method];
                if($method == 'changePassword')
                {
                    if(!empty($rule['ext'][$param])) $rule['score'] = $rule['score'] + $rule['ext'][$param];
                    $desc = $this->lang->score->methods[$model][$method];
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
                        $this->saveScore($createUser->openedBy, $newRule, $model, $method, $desc, $objectID, $time);
                        unset($newRule);
                    }
                }
                break;
            case 'task':
                $desc .= 'ID:' . $param;
                if($method == 'finish')
                {
                    $desc = $this->lang->score->methods[$model][$method] . 'ID:' . $param;
                    //每完成一个任务，增加初始积分1 + 工时积分round(工时 /10 * 预计 / 消耗) + 优先级积分(p1 2, p2, 1) 如果任务取消了，没有积分。
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
                if($method == 'saveTplModal') $desc = $this->lang->score->methods[$model][$method] . 'ID:' . $param;
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
                if($method == 'runCase') $desc = $this->lang->score->methods[$model][$method] . 'ID:' . $param;
                break;
            case 'build':
                if($method == 'create') $desc .= 'ID:' . $param;
                break;
            case 'project':
                if($method == 'create') $desc .= 'ID:' . $param;
                if($method == 'close')
                {
                    $desc     = $this->lang->score->methods[$model][$method] . ',' . $desc . 'ID:' . $param->id;
                    $objectID = $param->id;
                    if(!empty($param->PM))
                    {
                        $rule['score'] = $param->end > date('Y-m-d', $time) ? $rule['ext']['manager'][0] + $rule['ext']['manager'][1] : $rule['ext']['manager'][0];
                        $this->saveScore($param->PM, $rule, $model, $method, $desc, $objectID, $time);
                    }
                    $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('project')->eq($param->id)->fetchGroup('account');
                    if(!empty($teams))
                    {
                        $users         = array_keys($teams);
                        $rule['score'] = $param->end > date('Y-m-d', $time) ? $rule['ext']['member'][0] + $rule['ext']['member'][1] : $rule['ext']['member'][0];
                        foreach($users as $user)
                        {
                            if($user != $param->PM) $this->saveScore($user, $rule, $model, $method, $desc, $objectID, $time);
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
                if($method == 'saveQueryAdvanced') $desc = $this->lang->score->methods[$model][$method];
                break;
            case 'ajax':
                $desc = $this->lang->score->methods[$model][$method];
                break;
        }
        $this->saveScore($user, $rule, $model, $method, $desc, $objectID, $time);
    }

    /**
     * save user score
     *
     * @param string $account
     * @param array  $rule
     * @param string $model
     * @param string $method
     * @param string $desc
     * @param int    $objectID
     * @param string $time
     *
     * @access private
     * @return bool
     */
    private function saveScore($account = '', $rule = array(), $model = '', $method = '', $desc = '', $objectID = 0, $time = '')
    {
        if(!empty($rule['num']) || !empty($rule['time']))
        {
            if(empty($rule['time']))
            {
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('model')->eq($model)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
            }
            else
            {
                $timestamp = empty($time) ? time() : strtotime($time);
                $count     = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('time')->between(date('Y-m-d 0:0:0', $timestamp), date('Y-m-d 23:59:59', $timestamp))->andWhere('model')->eq($model)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
            }
        }

        $this->dao->begin();
        try
        {
            $user = $this->loadModel('user')->getById($account);

            $data           = new stdClass();
            $data->account  = $account;
            $data->model    = $model;
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
     * refresh all user score and level score
     *
     * @param int $lastID
     *
     * @access public
     * @return void
     */
    public function refresh($lastID = 0)
    {
        if($lastID == 0)
        {
            $this->dao->query("DROP TABLE IF EXISTS `score_bak`");
            $this->dao->query("RENAME TABLE " . TABLE_SCORE . ' TO score_bak');
            $this->dao->query("CREATE TABLE " . TABLE_SCORE . ' LIKE score_bak');
            $this->dao->query("UPDATE " . TABLE_USER . " SET `score`=0,`scoreLevel`=0");
        }
        $actions = $this->dao->select('*')->from('score_bak')->where('id')->gt($lastID)->orderBy('id_asc')->limit(100)->fetchAll('id');
        if(empty($actions)) return array('num' => 0, 'status' => 'finish');
        foreach($actions as $action)
        {
            $param = $action->objectID;
            if($action->model == 'project' && $action->method == 'close') $param = $this->dao->findById($action->objectID)->from(TABLE_PROJECT)->fetch();
            if($action->model == 'bug' && ($action->method == 'confirmBug' || $action->method == 'resolve')) $param = $this->dao->findById($action->objectID)->from(TABLE_BUG)->fetch();
            $this->score($action->model, $action->method, $param, $action->account, $action->time);
        }
        return array('status' => 'more', 'lastID' => max(array_keys($actions)));
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
            if($action->objectType == 'bug' && ($action->action == 'bugconfirmed' || $action->action == 'resolved')) $param = $this->dao->findById($action->objectID)->from(TABLE_BUG)->fetch();
            $this->score($action->objectType, $this->fix_key($action->action), $param, $action->actor, $action->date);
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
    private function fix_key($string)
    {
        $strings = array('created' => 'create', 'closed' => 'close', 'finished' => 'finish', 'bugconfirmed' => 'confirmBug', 'resolved' => 'resolve');
        return isset($strings[$string]) ? $strings[$string] : $string;
    }
}