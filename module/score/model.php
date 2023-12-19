<?php
declare(strict_types=1);
/**
 * The model file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: model.php $
 * @link        https://www.zentao.net
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
     * 创建积分日志。
     * Add score logs.
     *
     * @param  string            $module
     * @param  string            $method
     * @param  int|string|object $param
     * @param  string            $account
     * @param  string            $time
     * @access public
     * @return bool|object
     */
    public function create(string $module = '', string $method = '', int|string|object $param = '', string $account = '', string $time = ''): bool|object
    {
        if(empty($this->config->score->rule->{$module}->{$method}) || empty($this->config->global->scoreStatus)) return true;

        $rule     = $this->config->score->rule->{$module}->{$method};
        $desc     = $this->lang->score->modules[$module];
        $user     = empty($account) ? $this->app->user->account : $account;
        $time     = empty($time) ? helper::now() : $time;
        $extended = isset($this->config->score->ruleExtended[$module][$method]) ? $this->config->score->ruleExtended[$module][$method] : array();
        if(is_numeric($param)) $desc .= 'ID:' . $param;

        $object = '';
        switch($module)
        {
            case 'user':
                if($method == 'login') $desc = $this->lang->score->methods[$module][$method];
                if($method == 'changePassword')
                {
                    if(!empty($extended['strength'][$param])) $rule['score'] = $rule['score'] + $extended['strength'][$param];
                    $desc = $this->lang->score->methods[$module][$method];
                }
                break;
            case 'story':
                if($method == 'close')
                {
                    $openedBy = $this->dao->findById($param)->from(TABLE_STORY)->fetch('openedBy');
                    $object   = true;
                    if(!empty($openedBy))
                    {
                        $newRule          = $rule;
                        $newRule['score'] = $extended['createID'];
                        $object = $this->saveScore($openedBy, $newRule, $module, $method, $desc, $time);
                        unset($newRule);
                    }
                }
                break;
            case 'task':
                if($method == 'finish')
                {
                    $data = $this->computeTaskScore($module, $method, $param, $rule, $extended);
                    if(!$data) return true;
                    list($desc, $rule) = $data;
                }
                break;
            case 'bug':
                list($rule, $desc, $user) = $this->computeBugScore($module, $method, $param, $rule, $desc, $user, $extended);
                break;
            case 'testTask':
                if($method == 'runCase') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;
                break;
            case 'execution':
                if($method == 'close') list($rule, $desc, $object) = $this->computeExecutionScore($module, $method, $param, $user, $time, $rule, $desc, $extended);
                break;
            case 'search':
                if($method == 'saveQueryAdvanced') $desc = $this->lang->score->methods[$module][$method];
                break;
            case 'ajax':
                $desc = $this->lang->score->methods[$module][$method];
                break;
        }

        return $object === '' ? $this->saveScore($user, $rule, $module, $method, $desc, $time) : $object;
    }

    /**
     * 计算任务积分。
     * Compute task score.
     *
     * @param  string     $module
     * @param  string     $method
     * @param  int        $param
     * @param  array      $rule
     * @param  array      $extended
     * @access public
     * @return array|bool
     */
    public function computeTaskScore(string $module, string $method, int $param, array $rule, array $extended): array|bool
    {
        if($method != 'finish') return true;
        $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

        /* Check child task. */
        $parentTask = $this->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($param)->fetch('id');
        if(!empty($parentTask)) return false;

        $task = $this->loadModel('task')->getByID($param);
        if(!$task) return false;

        if(!empty($extended['pri'][$task->pri]))
        {
            $rule['score'] = $rule['score'] + $extended['pri'][$task->pri];
        }

        if(!empty($task->estimate))
        {
            $rule['score'] = $rule['score'] + (empty($task->consumed) ? 0 : round($task->consumed / 10.0 * $task->estimate / $task->consumed));
        }

        return array($desc, $rule);
    }

    /**
     * 计算Bug积分。
     * Compute bug score.
     *
     * @param  string            $module
     * @param  string            $method
     * @param  string|object|int $param
     * @param  array             $rule
     * @param  string            $desc
     * @param  string            $user
     * @param  array             $extended
     * @access public
     * @return array
     */
    public function computeBugScore(string $module, string $method, string|object|int $param, array $rule, string $desc, string $user, array $extended): array
    {
        if($method == 'createFormCase')
        {
            $desc     = $this->lang->score->modules['testcase'] . 'ID:' . $param;
            $openedBy = $this->dao->findById($param)->from(TABLE_CASE)->fetch('openedBy');
            if(!empty($openedBy)) $user = $openedBy;
        }

        if($method == 'saveTplModal') $desc = $this->lang->score->methods[$module][$method] . 'ID:' . $param;

        if($method == 'confirm')
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

        return array($rule, $desc, $user);
    }

    /**
     * 计算执行积分。
     * Compute execution score.
     *
     * @param  string $module
     * @param  string $method
     * @param  object $param
     * @param  string $user
     * @param  string $time
     * @param  array  $rule
     * @param  string $desc
     * @param  array  $extended
     * @access public
     * @return array
     */
    public function computeExecutionScore(string $module, string $method, object $param, string $user, string $time, array $rule, string $desc, array $extended): array
    {
        if($method != 'close') return array();

        $desc      = $this->lang->score->methods[$module][$method] . ',' . $desc . 'ID:' . $param->id;
        $timestamp = empty($time) ? time() : strtotime($time);
        $object    = true;

        /* Project PM. */
        if(!empty($param->PM))
        {
            $rule['score'] = $extended['manager']['close'];
            if($param->end > date('Y-m-d', $timestamp))
            {
                $rule['score'] += $extended['manager']['onTime'];
            }
            $object = $this->saveScore($param->PM, $rule, $module, $method, $desc, $time);
        }

        /* Project team user. */
        $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->eq($param->id)->andWhere('type')->eq('execution')->fetchPairs();
        if(!empty($teams))
        {
            $rule['score'] = $extended['member']['close'];
            if($param->end > date('Y-m-d', $timestamp))
            {
                $rule['score'] += $extended['member']['onTime'];
            }

            foreach($teams as $user)
            {
                if($user != $param->PM) $object = $this->saveScore($user, $rule, $module, $method, $desc, $time);
            }
        }
        return array($rule, $desc, $object);
    }

    /**
     * 保存用户积分。
     * Save user score.
     *
     * @param  string      $account
     * @param  array       $rule
     * @param  string      $module
     * @param  string      $method
     * @param  string      $desc
     * @param  string      $time
     * @access public
     * @return object|bool
     */
    public function saveScore(string $account = '', array $rule = array(), string $module = '', string $method = '', string $desc = '', string $time = ''): object|bool
    {
        if($rule['score'] == 0) return true;
        if(!empty($rule['times']) || !empty($rule['hour']))
        {
            if(empty($rule['hour']))
            {
                $count = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)->andWhere('module')->eq($module)->andWhere('method')->eq($method)->count();
            }
            else
            {
                $timestamp = empty($time) ? time() : strtotime($time);
                $count     = $this->dao->select('id')->from(TABLE_SCORE)->where('account')->eq($account)
                    ->andWhere('time')->between(date('Y-m-d 00:00:00', $timestamp), date('Y-m-d 23:59:59', $timestamp))
                    ->andWhere('module')->eq($module)
                    ->andWhere('method')->eq($method)
                    ->count();
            }
            if($count >= $rule['times']) return true;
        }

        $user = $this->loadModel('user')->getById($account);
        if(empty($user)) return false;

        $data = new stdClass();
        $data->account = $account;
        $data->module  = $module;
        $data->method  = $method;
        $data->desc    = $desc;
        $data->before  = $user->score;
        $data->score   = (int)$rule['score'];
        $data->after   = $user->score + $rule['score'];
        $data->time    = empty($time) ? helper::now() : $time;

        $this->dao->insert(TABLE_SCORE)->data($data)->exec();
        $this->dao->update(TABLE_USER)->set("`score`=`score` + " . $data->score)->set("`scoreLevel`=`scoreLevel` + " . $data->score)->where('account')->eq($account)->exec();
        if(dao::isError()) return false;

        return $data;
    }

    /**
     * 构建积分规则列表。
     * Build rules for list.
     *
     * @access public
     * @return array
     */
    public function buildRules(): array
    {
        $allRules = array();
        foreach($this->config->score->rule as $module => $moduleRule)
        {
            foreach($moduleRule as $method => $rule)
            {
                $rules['module'] = $this->lang->score->modules[$module];
                $rules['method'] = $this->lang->score->methods[$module][$method];
                $rules['times']  = empty($rule['times']) ? $this->lang->score->noLimit : $rule['times'];
                $rules['hour']   = empty($rule['hour']) ? $this->lang->score->noLimit : $rule['hour'];
                $rules['score']  = $rule['score'];
                $rules['desc']   = '';
                if(!isset($this->lang->score->extended[$module][$method]))
                {
                    $allRules[] = $rules;
                    continue;
                }

                $desc     = $this->lang->score->extended[$module][$method];
                $descRule = explode('##', $desc);
                if(!empty($descRule))
                {
                    foreach($descRule as $key => $value)
                    {
                        if($key % 2 != 1) continue;

                        $match = explode(',', $value);
                        if(count($match) == 2) $score = $this->config->score->ruleExtended[$module][$method][$match[0]][$match[1]];
                        if(count($match) != 2) $score = $this->config->score->ruleExtended[$module][$method][$match[0]];
                        $desc = str_replace('##' . $value . '##', (string)$score, $desc);
                    }
                }

                $rules['desc'] = $desc;
                $allRules[] = $rules;
            }
        }

        return $allRules;
    }

    /**
     * Fix action type for score.
     *
     * @param string $string
     *
     * @access public
     * @return string
     */
    public function fixKey($string)
    {
        $strings = array('created' => 'create', 'opened' => 'create', 'closed' => 'close', 'finished' => 'finish', 'bugconfirmed' => 'confirm', 'resolved' => 'resolve');
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
        if(empty($this->config->global->scoreStatus) or empty($this->app->user->lastTime)) return '';
        if(date('Y-m-d', $this->app->user->lastTime) == helper::today()) return '';

        $this->app->user->lastTime = time();

        $score = $this->dao->select("SUM(score) AS score")->from(TABLE_SCORE)
            ->where('time')->between(date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day')))
            ->andWhere('account')->eq($this->app->user->account)
            ->fetch('score');
        if(!$score) return '';

        return sprintf($this->lang->score->tips, $score, $this->app->user->score);
    }
}
