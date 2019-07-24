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
        return $this->dao->select('*')->from(TABLE_SCORE)->where('account')->eq($account)->orderBy('time_desc, id_desc')->page($pager)->fetchAll();
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
        if(empty($this->config->score->rule->{$module}->{$method}) || empty($this->config->global->scoreStatus)) return true;

        $rule     = $this->config->score->rule->{$module}->{$method};
        $desc     = $this->lang->score->modules[$module];
        $user     = empty($account) ? $this->app->user->account : $account;
        $time     = empty($time) ? helper::now() : $time;
        $extended = isset($this->config->score->ruleExtended[$module][$method]) ? $this->config->score->ruleExtended[$module][$method] : array();

        if(is_numeric($param)) $desc .= 'ID:' . $param;

        switch($module)
        {
            case 'user':
                if($method == 'login') $desc = $this->lang->score->methods[$module][$method];

                if($method == 'changePassword')
                {
                    if(!empty($extended['strength'][$param]))
                    {
                        $rule['score'] = $rule['score'] + $extended['strength'][$param];
                    }
                    $desc = $this->lang->score->methods[$module][$method];
                }
                break;
            case 'story':
                if($method == 'close')
                {
                    $openedBy = $this->dao->findById($param)->from(TABLE_STORY)->fetch('openedBy');

                    if(!empty($openedBy))
                    {
                        $newRule          = $rule;
                        $newRule['score'] = $extended['createID'];
                        $this->saveScore($openedBy, $newRule, $module, $method, $desc, $time);
                        unset($newRule);
                    }
                }
                break;
            case 'task':
                if($method == 'finish')
                {
                    $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

                    /* Check child task. */
                    $parentTask = $this->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($param)->fetch('id');
                    if(!empty($parentTask)) return true;

                    $task = $this->loadModel('task')->getById($param);

                    if(!empty($extended['pri'][$task->pri]))
                    {
                        $rule['score'] = $rule['score'] + $extended['pri'][$task->pri];
                    }

                    if(!empty($task->estimate))
                    {
                        $rule['score'] = $rule['score'] + (empty($task->consumed) ? 0 : round($task->consumed / 10 * $task->estimate / $task->consumed));
                    }
                }
                break;
            case 'bug':
                if($method == 'createFormCase')
                {
                    $desc     = $this->lang->score->modules['testcase'] . 'ID:' . $param;
                    $openedBy = $this->dao->findById($param)->from(TABLE_CASE)->fetch('openedBy');
                    if(!empty($openedBy)) $user = $openedBy;
                }

                if($method == 'saveTplModal') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

                if($method == 'confirmBug')
                {
                    $user  = $param->openedBy;
                    $desc .= 'ID:' . $param->id;
                    if(!empty($extended['severity'][$param->severity]))
                    {
                        $rule['score'] = $rule['score'] + $extended['severity'][$param->severity];
                    }
                }

                if($method == 'resolve' && !empty($extended['severity'][$param->severity]))
                {
                    $rule['score'] = $rule['score'] + $extended['severity'][$param->severity];
                }
                break;
            case 'testTask':
                if($method == 'runCase') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;
                break;
            case 'project':
                if($method == 'close')
                {
                    $desc      = $this->lang->score->methods[$module][$method] . ',' . $desc . 'ID:' . $param->id;
                    $timestamp = empty($time) ? time() : strtotime($time);

                    /* Project PM. */
                    if(!empty($param->PM))
                    {
                        $rule['score'] = $extended['manager']['close'];
                        if($param->end > date('Y-m-d', $timestamp))
                        {
                            $rule['score'] += $extended['manager']['onTime'];
                        }
                        $this->saveScore($param->PM, $rule, $module, $method, $desc, $time);
                    }

                    /* Project team user. */
                    $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->eq($param->id)->andWhere('type')->eq('project')->fetchPairs();
                    if(!empty($teams))
                    {
                        $rule['score'] = $extended['member']['close'];
                        if($param->end > date('Y-m-d', $timestamp))
                        {
                            $rule['score'] += $extended['member']['onTime'];
                        }

                        foreach($teams as $user)
                        {
                            if($user != $param->PM) $this->saveScore($user, $rule, $module, $method, $desc, $time);
                        }
                    }

                    /* When the project is closed, no more user get score. */
                    return true;
                }
                break;
            case 'search':
                if($method == 'saveQueryAdvanced') $desc = $this->lang->score->methods[$module][$method];
                break;
            case 'ajax':
                $desc = $this->lang->score->methods[$module][$method];
                break;
        }
        $this->saveScore($user, $rule, $module, $method, $desc, $time);
    }

    /**
     * Save user score.
     *
     * @param string $account
     * @param array  $rule
     * @param string $module
     * @param string $method
     * @param string $desc
     * @param string $time
     *
     * @access private
     * @return bool
     */
    public function saveScore($account = '', $rule = array(), $module = '', $method = '', $desc = '', $time = '')
    {
        if($rule['score'] == 0) return true;
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

        $user = $this->loadModel('user')->getById($account);
        if(empty($user)) return false;

        $data = new stdClass();
        $data->account  = $account;
        $data->module   = $module;
        $data->method   = $method;
        $data->desc     = $desc;
        $data->before   = $user->score;
        $data->score    = $rule['score'];
        $data->after    = $user->score + $rule['score'];
        $data->time     = empty($time) ? helper::now() : $time;
        $this->dao->insert(TABLE_SCORE)->data($data)->exec();

        $this->dao->update(TABLE_USER)->set("`score`=`score` + " . (int)$rule['score'])->set("`scoreLevel`=`scoreLevel` + " . (int)$rule['score'])->where('account')->eq($account)->exec();
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
            $this->dao->query("UPDATE " . TABLE_USER . " SET `score`=0, `scoreLevel`=0");
            $this->dao->delete()->from(TABLE_SCORE)->exec();
            try
            {
                $this->dbh->exec('ALTER TABLE ' . TABLE_SCORE . ' auto_increment=1');
            }
            catch(Exception $e){}
        }

        $actions = $this->dao->select('*')->from(TABLE_ACTION)->where('id')->gt($lastID)->orderBy('id_asc')->limit(100)->fetchAll('id');
        if(empty($actions)) return array('number' => 0, 'status' => 'finish');

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
     * @access public
     * @return string
     */
    public function fixKey($string)
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
        if(empty($this->config->global->scoreStatus)) return '';
        if(date('Y-m-d', $this->app->user->lastTime) == helper::today()) return '';

        $this->app->user->lastTime = time();

        $score = $this->dao->select("SUM(score) AS score")->from(TABLE_SCORE)
            ->where('time')->between(date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day')))
            ->andWhere('account')->eq($this->app->user->account)
            ->fetch('score');
        if(!$score) return '';

        $notice     = sprintf($this->lang->score->tips, $score, $this->app->user->score);
        $fullNotice = <<<EOT
<div id='noticeAttend' class='alert alert-success with-icon alert-dismissable' style='width:310px; position:fixed; bottom:25px; right:15px; z-index: 9999;' id='planInfo'>
   <i class='icon icon-diamond'>  </i>
   <div class='content'>{$notice}</div>
   <button type="button" class="close" data-dismiss="alert">×</button>
 </div>
EOT;
        return $fullNotice;
    }
}
