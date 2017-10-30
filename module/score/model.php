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
     *
     * @access public
     * @return bool
     */
    public function score($model = '', $method = '', $param = '')
    {
        if(empty($this->config->score->model[$model][$method])) return true;
        $rule = $this->config->score->model[$model][$method];
        $desc = $this->lang->score->models[$model];
        $user = $this->app->user->account;
        switch($model)
        {
            case 'user':
                if($method == 'login') $desc = $this->lang->score->methods[$model][$method] . 'IP:' . helper::getRemoteIp();
                if($method == 'changePassword')
                {
                    if(!empty($rule['other'][$param])) $rule['score'] = $rule['score'] + $rule['other'][$param];
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
                        $newRule['score'] = $rule['other']['createID'];
                        $this->saveScore($createUser->openedBy, $newRule, $model, $method, $desc);
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
                    if(!empty($rule['other'][$task->pri])) $rule['score'] = $rule['score'] + $rule['other'][$task->pri];
                    if(!empty($task->estimate)) $rule['score'] = $rule['score'] + round(($task->consumed / 10 * $task->estimate / $task->consumed), 1);
                }
                break;
            case 'bug':
                $desc .= 'ID:' . $param;
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
                    $user = $param->openedBy;
                    if(!empty($rule['other'][$param->severity])) $rule['score'] = $rule['score'] + $rule['other'][$param->severity];
                }
                if($method == 'resolve' && !empty($rule['other'][$param->severity])) $rule['score'] = $rule['score'] + $rule['other'][$param->severity];
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
                    $desc = $this->lang->score->methods[$model][$method] . ',' . $desc . 'ID:' . $param->id;
                    if(!empty($param->PM))
                    {
                        $rule['score'] = $param->end > date('Y-m-d') ? $rule['other']['manager'][0] + $rule['other']['manager'][1] : $rule['other']['manager'][0];
                        $this->saveScore($param->PM, $rule, $model, $method, $desc);
                    }
                    $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('project')->eq($param->id)->fetchGroup('account');
                    if(!empty($teams))
                    {
                        $users         = array_keys($teams);
                        $rule['score'] = $param->end > date('Y-m-d') ? $rule['other']['member'][0] + $rule['other']['member'][1] : $rule['other']['member'][0];
                        foreach($users as $user)
                        {
                            if($user != $param->PM) $this->saveScore($user, $rule, $model, $method, $desc);
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
        $this->saveScore($user, $rule, $model, $method, $desc);
    }

    /**
     * save user score
     *
     * @param string $account
     * @param array  $rule
     * @param string $model
     * @param string $method
     * @param string $desc
     *
     * @access private
     * @return bool
     */
    private function saveScore($account = '', $rule = array(), $model = '', $method = '', $desc = '')
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
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('time')->between(date('Y-m-d 0:0:0'), date('Y-m-d 23:59:59'))->andWhere('model')->eq($model)->andWhere('method')->eq($method)->count();
                if($count >= $rule['num']) return true;
            }
        }

        $this->dao->begin();
        try
        {
            $user = $this->loadModel('user')->getById($account);

            $data          = new stdClass();
            $data->account = $account;
            $data->model   = $model;
            $data->method  = $method;
            $data->desc    = $desc;
            $data->before  = $user->score;
            $data->score   = $rule['score'];
            $data->after   = $user->score + $rule['score'];
            $data->time    = helper::now();
            $this->dao->insert(TABLE_SCORE)->data($data)->exec();
            $this->dao->query("UPDATE " . TABLE_USER . " SET `score`=`score` + " . $rule['score'] . ",`score_level`=`score_level` + " . $rule['score'] . " WHERE `account`='" . $account . "'");
            $this->dao->commit();
        }
        catch(ErrorException $e)
        {
            $this->dao->rollBack();
        }
    }

    /**
     * reset all user score and level score
     * @access public
     * @return void
     */
    public function resetScore()
    {

    }
}