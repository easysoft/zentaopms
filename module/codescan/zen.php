<?php
declare(strict_types=1);
/**
 * The zen file of codescan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
class codescanZen extends codescan
{
    /**
     * 构建通用数据。
     * Build common data.
     *
     * @param  string $include
     * @access protected
     * @return void
     */
    protected function commonData($include = '', $usePair = true)
    {
        $configList = array('lang' => 'langs', 'tag' => 'tags', 'plugin' => 'plugins', 'type' => 'types');
        $includes   = empty($include) ? array() : explode('|', $include);
        foreach($configList as $type => $configType)
        {
            if(!empty($includes) && !in_array($type, $includes)) continue;
            $configTypeList = $this->codescan->getScanRulesConfig($configType);
            foreach($configTypeList as $index => $config)
            {
                if(!isset($config->$type) && !isset($config->id)) unset($configTypeList[$index]);
                if($type == 'lang' && (!isset($config->id) || $config->id == 0)) $config->id = 'Custom';
            }
            $this->view->$configType = $configTypeList;

            if($usePair)
            {
                $typePairs = array();
                foreach($configTypeList as $config)
                {
                    if($type === 'lang')
                    {
                        $key = $config->$type;
                    }
                    else
                    {
                        $key = !empty($config->id) ? $config->id : $config->$type;
                    }
                    if($key == 'PHPStan') $key = 'phpstan';
                    if($key == 'qlty')    $config->$type = 'Qlty';
                    if($key == 'phpstan') $config->$type = 'PHPStan';
                    if($type == 'lang' && $key == 'custom')
                    {
                        $config->$type = $this->lang->codescan->general;
                    }

                    $typePairs[$key] = $config->$type;
                }

                $this->view->{$type . 'List'} = $typePairs;
            }
        }
    }

    /**
     * 处理日期。
     * Process date to begin and end.
     *
     * @param  string $query
     * @access public
     * @return array
     */
    public function getDateFilter(string $query): array
    {
        $this->app->loadClass('date');
        $lastWeek  = date::getLastWeek();
        $thisWeek  = date::getThisWeek();
        $lastMonth = date::getLastMonth();
        $thisMonth = date::getThisMonth();
        $yesterday = date::yesterday();
        $today     = date(DT_DATE1);

        $begin = $end = 0;
        switch($query)
        {
        case '$lastWeek':
            $begin = $lastWeek['begin'];
            $end   = $lastWeek['end'];
            break;
        case '$thisWeek':
            $begin = $thisWeek['begin'];
            $end   = $thisWeek['end'];
            break;
        case '$lastMonth':
            $begin = $lastMonth['begin'];
            $end   = $lastMonth['end'];
            break;
        case '$thisMonth':
            $begin = $thisMonth['begin'];
            $end   = $thisMonth['end'];
            break;
        case '$yesterday':
            $begin = $yesterday . ' 00:00:00';
            $end   = $yesterday . ' 23:59:59';
            break;
        case '$today':
            $begin = $today . ' 00:00:00';
            $end   = $today . ' 23:59:59';
            break;
        default:
            $begin = $query . ' 00:00:00';
            $end   = $query . ' 23:59:59';
            break;
        }
        return array('begin' => $begin,  'end' => $end);
    }

    /**
     * 构建查询参数。
     * Build params.
     *
     * @param  string    $type
     * @param  string    $params
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return void
     */
    protected function buildParams(string $type, string $params, int $queryID = 0, string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $method = $this->app->rawMethod;
        if(!$orderBy && in_array($method, array('browse', 'rulesetview', 'linkrule', 'linkset'))) $orderBy = 'id_desc';
        list($sort, $order) = explode('_', $orderBy);
        if(isset($this->config->codescan->remoteFields[$sort])) $sort = $this->config->codescan->remoteFields[$sort];

        $searchForm = 'codeScan' . ucfirst($method) . 'Form';
        if(in_array($method,  array('browse', 'linkrule'))) $searchForm = 'codeScanRuleForm';
        if($method == 'linkset') $searchForm = 'codeScanRulesetForm';

        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query) $this->session->set($searchForm, $query->form);
        }

        $param = array();
        if(!in_array($type, array('all', 'task', 'bySearch'))) $method == 'browse' ? ($param['type'] = $type) : ($param['status'] = $type);
        if($params && $type != 'bySearch')
        {
            parse_str($params, $conditions);
            $param += $conditions;
        }

        $param['sort']     = $sort;
        $param['order']    = $order;
        $param['page']     = $pageID;
        $param['pageSize'] = $recPerPage;

        $codeScanRuleForm = $this->session->$searchForm;
        if($type == 'bySearch' && !empty($codeScanRuleForm))
        {
            foreach($codeScanRuleForm as $search)
            {
                if(isset($search['value']) && $search['value'])
                {
                    if(isset($this->config->codescan->remoteFields[$search['field']])) $search['field'] = $this->config->codescan->remoteFields[$search['field']];
                    if($search['field'] == 'id' && $method == 'task') $search['field'] = 'taskID';
                    if(in_array($search['field'], array('started', 'finished')))
                    {
                        $search['field'] = in_array($search['operator'], array('<', '<=')) ? $search['field'] . 'Lt' : $search['field'] . 'Gt';
                    }

                    if(strpos($search['field'], 'Date'))
                    {
                        $fields     = explode('_', $search['field']);
                        $dateFilter = $this->getDateFilter($search['value']);

                        if($search['operator'] == '=')
                        {
                            $search['value'] = $dateFilter['begin'];
                        }
                        else
                        {
                            $param["{$fields[0]}Gt"] = $dateFilter['begin'];
                            $param["{$fields[0]}Lt"] = $dateFilter['end'];
                            continue;
                        }
                    }
                    if($search['field'] == 'lang' && $search['value'] == 'all') $search['value'] = '';

                    $param[$search['field']] = $search['value'];
                }
            }
        }

        return $param;
    }

    /**
     * 构建搜索表单。
     * Build search form.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access protected
     * @return void
     */
    protected function buildSearchForm(array $searchConfig, string|int $queryID, string $actionURL)
    {
        unset($searchConfig['params']['type']['values']['all']);

        $searchConfig['queryID']   = (int)$queryID;
        $searchConfig['actionURL'] = $actionURL;

        if(isset($searchConfig['params']['lang']))   $searchConfig['params']['lang']['values']   = $this->view->langList   ? $this->view->langList : array();
        if(isset($searchConfig['params']['tag']))    $searchConfig['params']['tag']['values']    = $this->view->tagList    ? $this->view->tagList : array();
        if(isset($searchConfig['params']['plugin'])) $searchConfig['params']['plugin']['values'] = $this->view->pluginList ? $this->view->pluginList : array();
        if(isset($searchConfig['params']['repo']))   $searchConfig['params']['repo']['values']   = $this->view->repoList   ? $this->view->repoList : array();
        if(isset($searchConfig['params']['plan']))   $searchConfig['params']['plan']['values']   = $this->view->planList   ? $this->view->planList : array();

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 处理并返回错误。
     * Process and return error.
     *
     * @param  string|array $errors
     * @access protected
     * @return void
     */
    protected function responseError(string|array $errors = '', string $locate = '')
    {
        if(empty($errors))     $errors = dao::getError();
        if(!is_array($errors)) $errors = array($errors);

        if(count($errors) === 1 && isset($errors['apiMessage']) && is_string($errors['apiMessage']))
        {
            return $this->sendError($errors['apiMessage'], $locate);
        }

        return $this->sendError($errors, $locate);
    }

    /**
     * 获取所有方案和规则集数据。
     * Get all solution and ruleset data.
     *
     * @param  string    $query
     * @param  int       $serviceRepoID
     * @param  int       $taskID
     * @param  string    $status
     * @access protected
     * @return array
     */
    protected function getListByQuery(string $query = 'ruleset', int $serviceRepoID = 0, int $taskID = 0, string $status = ''): array
    {
        $list   = array();
        $page   = 1;
        $method = 'getScan' . ucfirst($query);
        $method .= $query == 'issue' ? 'List' : 's';
        if(!method_exists($this->codescan, $method)) return $list;

        while(true)
        {
            if($query == 'plan')
            {
                $result = $this->codescan->$method($serviceRepoID, array('page' => $page, 'limit' => 100));
            }
            elseif($query == 'task')
            {
                $result = $this->codescan->$method($serviceRepoID, 0, array('page' => $page, 'limit' => 100));
            }
            elseif($query == 'issue')
            {
                $result = $this->codescan->$method($taskID, array('page' => $page, 'limit' => 100));
            }
            else
            {
                $params = array('page' => $page, 'limit' => 100);
                if($status) $params['status'] = $status;
                $result = $this->codescan->$method($params);
            }
            if(empty($result) || empty($result->data)) break;

            $list = array_merge($list, $result->data);

            if(count($result->data) < 100) break;
            $page ++;
        }

        return $list;
    }

    /**
     * 处理结果通过标准。
     * Process result conditions.
     *
     * @param  object     $plan
     * @access protected
     * @return array
     */
    protected function processConditions(object $plan): array
    {
        $severity  = $plan->severity;
        $type      = $plan->type;
        $metric    = $plan->metric;
        $threshold = $plan->threshold;

        $conditions = array();
        foreach($severity as $index => $value)
        {
            if(empty($value)) continue;

            if(empty($type[$index]))      dao::$errors["type[$index]"]      = sprintf($this->lang->error->notempty, $this->lang->codescan->type);
            if(empty($metric[$index]))    dao::$errors["metric[$index]"]    = sprintf($this->lang->error->notempty, $this->lang->codescan->metric);
            if(empty($threshold[$index])) dao::$errors["threshold[$index]"] = sprintf($this->lang->error->notempty, $this->lang->codescan->threshold);
            if($metric[$index] == 'percent' && $threshold[$index] > 100) dao::$errors["threshold[$index]"] = $this->lang->codescan->notice->thresholdError;

            $conditions[] = array(
                'type'      => zget($type, $index, '') == 'all' ? '' : zget($type, $index, ''),
                'priority'  => $value == 'all' ? '' : $value,
                'unit'      => zget($metric, $index, ''),
                'threshold' => (int)zget($threshold, $index, '')
            );
        }

        return $conditions;
    }

    /**
     * 处理扫描计划数据。
     * Process scan plan data.
     *
     * @param  object    $plan
     * @access protected
     * @return object
     */
    protected function processPlanData(object $plan): object
    {
        if(!empty($plan->repo))
        {
            $repo = $this->loadModel('repo')->fetchByID($plan->repo);
            if(empty($repo))
            {
                dao::$errors['repo'] = $this->lang->repo->error->accessDenied;
            }
        }

        if(!empty($plan->branchReg))
        {
            $branches = explode(',', $plan->branchReg);

            $plan->branchReg = '';
            foreach($branches as $branch)
            {
                $branch = trim($branch, '*');
                if($branch) $plan->branchReg .= '**' . trim($branch) . '**,';
            }
            $plan->branchReg = trim($plan->branchReg, ',');
        }

        if(!empty($plan->excludePath))
        {
            $dirs = explode(',', $plan->excludePath);

            $plan->excludePath = '';
            foreach($dirs as $dir)
            {
                $dir = rtrim($dir, '/');
                if($dir)
                {
                    if(substr($dir, 0, 1) != '/' && substr($dir, 0, 1) != '*') $dir = '**/' . $dir;
                    if(substr($dir, -1) != '*') $dir = $dir . '/**';
                    $plan->excludePath .= "$dir,";
                }
            }
            $plan->excludePath = trim($plan->excludePath, ',');
        }

        $solutions = explode(',', $plan->solutions);
        foreach($solutions as &$solution) $solution = (int)$solution;

        $params = new stdclass();
        $params->name        = $plan->name;
        $params->repoID      = $plan->repo;
        $params->scanType    = $plan->scope;
        $params->solutionIDs = $solutions;
        $params->conditions  = $this->processConditions($plan);

        $params->branches = new stdclass();
        $branchesInclude  = array_filter(array_merge(explode(',', $plan->branch), explode(',', $plan->branchReg)));
        $params->branches->include = array_values($branchesInclude);

        $params->files = new stdclass();
        $filesExclude  = array_filter(array_merge(explode(',', $plan->excludePath), explode(',', $plan->excludeFile)));
        $params->files->exclude = array_values($filesExclude);

        return $params;
    }

    /**
     * 验证触发器数据。
     * Validate trigger data.
     *
     * @param  object    $trigger
     * @access protected
     * @return bool
     */
    public function validateTrigger(object &$trigger): bool
    {
        if(empty($trigger->triggerType) || $trigger->triggerType == 'action') return true;

        if($trigger->triggerType == 'cron')
        {
            $trigger->cron = '';
            foreach(array('minute', 'hour', 'day', 'month', 'week') as $field)
            {
                if(isset($trigger->$field))
                {
                    $this->validateField($trigger->$field, $field);

                    $trigger->cron .= $trigger->$field . ' ';
                }
            }

            $trigger->cron = trim($trigger->cron);
        }
        return !dao::isError();
    }

    /**
     * 验证字段。
     * Validate the field.
     *
     * @param  string    $value 要验证的值
     * @param  string    $field 要验证的字段
     * @access protected
     * @return bool
     */
    protected function validateField(string $value, string $field): bool
    {
        // 检查是否包含特殊字符
        if($value !== '*' && strpos($value, ',') === false && !preg_match('/^((\d+(\-\d+)?)|\*)(\/\d+)?$/', $value))
        {
            dao::$errors[$field][] = $this->lang->codescan->notice->{$field . 'Error'};
        }

        // 验证数字范围
        if(strpos($value, '-') !== false || strpos($value, '/') !== false)
        {
            $parts = explode(',', str_replace(['-', '/'], ',', $value));

            $max = 59;
            if($field == 'hour') $max = 23;
            if($field == 'day')  $max = 31;
            if($field == 'month')$max = 12;
            if($field == 'week') $max = 6;
            foreach($parts as $part)
            {
                if($part == '*') continue;

                if(!is_numeric($part) || $part < 0 || $part > $max)
                {
                    dao::$errors[$field][] = $this->lang->codescan->notice->{$field . 'Limit'};
                    break;
                }
            }
        }

        return !dao::isError();
    }

    /**
     * 构建扫描任务数据。
     * build scan task data.
     *
     * @param  object    $task
     * @access protected
     * @return object
     */
    protected function buildPlanData(object $plan): object
    {
        $plan->latestScanTime   = empty($plan->latestRunTime) ? '' : $plan->latestRunTime;
        $plan->solutions        = empty($plan->solutionIDs) ? '' : implode(',', $plan->solutionIDs);
        $plan->latestScanTime   = empty($plan->latestTaskCreated) ? '' : $plan->latestTaskCreated;
        $plan->latestExecStatus = empty($plan->latestTaskStatus) ? '-' : $plan->latestTaskStatus;
        $plan->latestExecResult = empty($plan->latestTaskResult) ? '-' : $plan->latestTaskResult;

        if(!empty($plan->branches) && !empty($plan->branches->include))
        {
            $branchReg = array();
            foreach($plan->branches->include as $index => $branch)
            {
                if(strpos($branch, '**') === 0)
                {
                    if(substr($branch, 0, 2) == '**' && substr($branch, -2) == '**')
                    {
                        $branchReg[] = substr($branch, 2, -2);
                        unset($plan->branches->include[$index]);
                    }
                }
            }
            $plan->branchReg = implode(',', $branchReg);
        }
        $plan->scanBranch = empty($plan->branches->include) ? '' : implode(',', $plan->branches->include);

        return $plan;
    }

    /**
     * 处理扫描任务数据。
     * Process scan task data.
     *
     * @param  object    $task
     * @param  array     $repoList
     * @access protected
     * @return object
     */
    protected function processTaskData(object $task, array $repoList): object
    {
        $task->result      = empty($task->result) ? '-' : $task->result;
        $task->issueCount  = zget($task, 'issueNumber');
        $task->startTime   = empty($task->started) ? '' : date('Y-m-d H:i:s', intval($task->started / 1000));
        $task->endTime     = empty($task->finished) ? '' : date('Y-m-d H:i:s', intval($task->finished / 1000));
        $task->runTime     = empty($task->cost) ? '-' : $this->codescan->formatDuration($task->cost);
        $trigger = zget($task, 'trigger', new stdclass());
        if(!is_object($trigger)) $trigger = new stdclass();

        $task->triggerType = zget($trigger, 'triggerType', '');
        $task->triggerID   = zget($trigger, 'triggerID', 0);
        $task->branch      = str_replace('refs/heads/', '', zget($task, 'executionRef', ''));
        $task->name        = zget($repoList, $task->repoID) . sprintf($this->lang->codescan->scanNo, $task->repoNumber) . '(' . $task->branch . ')';
        $task->triggerName = zget($trigger, 'triggerName', '');
        $task->trigger     = $task->triggerName;
        $task->repo        = $task->repoID;

        return $task;
    }

    /**
     * 处理问题数据。
     * Process issue data.
     *
     * @param  object    $issue
     * @access protected
     * @return object
     */
    protected function processIssueData(object $issue): object
    {
        $issue->content    = zget($issue, 'message', '');
        $issue->file       = zget($issue, 'path', '');
        $issue->priority   = zget($issue, 'rulePriority', '');
        $issue->type       = zget($issue, 'ruleType', '');
        $issue->rulePlugin = zget($issue->payload, 'tool', '');
        return $issue;
    }

    /**
     * 处理执行分支数据。
     * Process execution branch data.
     *
     * @param  int $planID
     * @param  int $repoID
     * @access protected
     * @return array
     */
    protected function processExecBranch(int $planID, int $repoID): array
    {
        $repo = $this->loadModel('repo')->getById($repoID);
        $plan = $this->codescan->getScanPlan($planID, empty($repo) ? 0 : (int)$repo->id);

        $branchList = array();
        $include    = array();
        if(!empty($plan->branches->include))
        {
            foreach($plan->branches->include as $branch)
            {
                if(substr($branch, 0, 2) == '**' && substr($branch, -2) == '**')
                {
                    $include[] = str_replace(array('/', ' ', '**'), array('\/', '', '.*'), $branch);
                }
                else
                {
                    $branchList[$branch] = $branch;
                }
            }
        }
        $branches = array();
        if(!empty($branchList)) $branches = $branchList;

        if(!empty($include) && !empty($repo))
        {
            $scm = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            $repoBranchList = $scm->branch();
            foreach($repoBranchList as $branch)
            {
                foreach($include as $includeBranch)
                {
                    if(isset($branches[$branch])) continue;
                    if(preg_match("/{$includeBranch}/i", $branch)) $branches[$branch] = $branch;
                }
            }
        }
        return $branches;
    }

    /**
     * 通过文件获取问题列表。
     * Get issue list by file.
     *
     * @param  string $file
     * @param  int $serviceRepoID
     * @access protected
     * @return void
     */
    protected function getFileIssueList(string $file, int $serviceRepoID, int $taskID)
    {
        $params = array();
        $params['repoID'] = $serviceRepoID;
        $params['file']   = $file;
        $params['sort']   = 'line';
        $params['order']  = 'asc';
        $params['limit']  = 100;
        $params['page']   = 1;

        $list = array();
        while(true)
        {
            $result = $this->codescan->getScanIssueList($taskID, $params);
            if(empty($result) || empty($result->data)) break;

            $list = array_merge($list, $result->data);
            if(count($result->data) < 100) break;
            $params['page'] = $params['page'] + 1;
        }

        foreach($list as $index => $item) $list[$index] = $this->processIssueData($item);

        return $list;
    }

    /**
     * 处理规则数据。
     * Process rule data.
     *
     * @param  object    $rule
     * @access protected
     * @return object
     */
    protected function processRuleData($rule): object
    {
        if(common::checkNotCN())
        {
            $rule->name        = empty($rule->rulekey)        ? $rule->name        : $rule->rulekey;
            $rule->description = empty($rule->description_en) ? $rule->description : $rule->description_en;
        }

        $rule->priority = zget(array_flip($this->config->codescan->severityMapList), $rule->priority);

        return $rule;
    }

    /**
     * 处理问题的文件树。
     * Process issue file tree.
     *
     * @param  array     $fileTree
     * @param  string    $urlParam
     * @param  array     $params
     * @access protected
     * @return array
     */
    protected function processIssueFileTree(array $fileTree, string $urlParam, array $params = array()): array
    {
        if(isset($params['branch'])) unset($params['branch']);
        if(isset($params['ruleID'])) unset($params['ruleID']);
        $extra = str_replace(array('&', '', '-'), array(',', ' ', '*'), http_build_query($params));

        $treeList = array();
        foreach($fileTree as $file)
        {
            if($file->name == 'root') $file->name = '/';
            $path = $file->path ? $file->path : $file->name;
            $path = str_replace(array('/', '-', '.'), '', $path);
            $file->key = $path;
            $file->id  = $path;
            if(!empty($file->children))
            {
                $file->children = $this->processIssueFileTree($file->children, $urlParam, $params);
            }
            else
            {
                $dirPath = explode('/', $file->path);
                $file->link = sprintf($urlParam, "{$extra},branch={$dirPath[0]}");
            }
            $treeList[] = $file;
        }
        return $treeList;
    }

    /**
     * 处理问题的规则树。
     * Process issue rule tree.
     *
     * @param  array     $ruleTree
     * @param  string    $urlParam
     * @param  array     $params
     * @access protected
     * @return array
     */
    protected function processIssueRuleTree(array $ruleTree, string $urlParam, array $params = array(), int $ruleID = 0): array
    {
        if(isset($params['branch'])) unset($params['branch']);
        if(isset($params['ruleID']))
        {
            $ruleID = (int)$params['ruleID'];
            unset($params['ruleID']);
        }
        $extra = str_replace(array('&', '', '-'), array(',', ' ', '*'), http_build_query($params));

        $treeList = array();
        foreach($ruleTree as $rule)
        {
            if($rule->name == 'root') $rule->name = '/';
            $name = zget($this->view->pluginList, $rule->name);
            $name = zget($this->lang->codescan->typeList, $name);
            $rule->name = $name;
            $rule->key  = $rule->name;
            $rule->id   = empty($rule->ref) ? $rule->name : $rule->ref;
            if(!empty($rule->children))
            {
                $rule->children = $this->processIssueRuleTree($rule->children, $urlParam, $params, $ruleID);
            }
            elseif(!empty($rule->ref))
            {
                if($ruleID == $rule->ref) $this->view->ruleName = $rule->name;
                $rule->url = sprintf($urlParam, "{$extra},ruleID=$rule->ref");
            }
            $treeList[] = $rule;
        }
        return $treeList;
    }

    /**
     * 初始化问题注入数量统计。
     * Initialize issue injection number statistics.
     *
     * @param  array     $committers
     * @access protected
     * @return void
     */
    protected function assignTopIssueInjection(array $committers = array())
    {
        $statistics = array();
        if($committers)
        {
            $users = $this->loadModel('user')->getPairs('noletter|noempty|noclosed');
            foreach($committers as $user) $statistics[zget($users, $user->name)] = (int)$user->value;
        }

        $this->view->injectionList  = $statistics;
    }

    /**
     * 代码库问题规则和文件的排行榜TOP10。
     * Code repo issue rule and file ranking TOP10.
     *
     * @param  array     $metrics
     * @param  string    $type   rule|file
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function assignRepoTopRanking(array $metrics, string $type = 'rule', int $taskID = 0)
    {
        $data = array();
        foreach($metrics as $item) $data[$item->name] = (int)$item->value;
        if($type == 'file')
        {
            $this->view->fileRanking = $data;
        }
        else
        {
            $this->view->ruleRanking = $data;
        }
    }

    /**
     * 获取问题分布数据。
     * Get issue distribution data.
     *
     * @param  object|array $metrics
     * @access protected
     * @return array
     */
    protected function getIssueDistribution(object|array $metrics): array
    {
        $data = array();
        if(empty($metrics)) return $data;

        foreach($metrics as $type => $typeList)
        {
            if($type == 'status')
            {
                $typeMap = $this->lang->codescan->issueStatusList;
            }
            elseif($type == 'priority')
            {
                $typeMap = $this->lang->codescan->severityList;
            }
            else
            {
                $typeMap = $this->lang->codescan->typeList;
            }

            foreach($typeMap as $code => $typeName)
            {
                if($code == 'all') continue;

                $data[$type][] = array('name' => $typeName, 'value' => zget($typeList, $code, 0));
            }
        }

        return $data;
    }

    /**
     * 处理问题趋势数据。
     * Process issue trends data.
     *
     * @param  array     $metrics
     * @param  string    $scope
     * @access protected
     * @return array
     */
    protected function processIssueTrends(array $metrics, string $scope = 'day'): array
    {
        $addedList = array();
        $fixedList = array();
        foreach($metrics as $metric)
        {
            if(empty($metric->metric->name)) continue;

            foreach($metric->values as $count)
            {
                $date = date($scope == 'day' ? 'm-d' : 'Y-m', $count[0] / 1000);
                if($metric->metric->name == 'issue_added') $addedList[$date] = zget($count, '1', 0);
                if($metric->metric->name == 'issue_fixed') $fixedList[$date] = zget($count, '1', 0);
            }
        }

        return array($addedList, $fixedList);
    }

    /**
     * 获取仓库问题排行榜数据。
     * Get repo issue ranking data.
     *
     * @param  array $metrics
     * @param  string $type
     * @access protected
     * @return void
     */
    protected function repoIssueTopRanking(array $metrics, string $type = 'total')
    {
        $data = array();
        if(!empty($metrics))
        {
            $repoPair = array_column($this->loadModel('repo')->getGitFoxRepos(), 'name', 'serviceProject');
            foreach($metrics as $item)
            {
                $data[$item->id] = new stdclass();
                $data[$item->id]->name   = zget($repoPair, $item->id, $item->name);
                $data[$item->id]->total  = $item->total;
                $data[$item->id]->high   = zget($item->values, 'high', 0);
                $data[$item->id]->medium = zget($item->values, 'medium', 0);
                $data[$item->id]->low    = zget($item->values, 'low', 0);
            }
        }

        if($type == 'total')
        {
            $this->view->issueTotalRanking = $data;
        }
        else
        {
            $this->view->issueUnresolvedRanking = $data;
        }
    }

    /**
     * 设置代码库统计数据。
     * Set code repo statistics.
     *
     * @param  array     $repoMetrics
     * @access protected
     * @return void
     */
    protected function assignRepoStatistics(array $repoMetrics)
    {
        $repoList = $this->loadModel('repo')->getGitFoxRepos();
        $repoList = array_column($repoList, null, 'serviceProject');
        foreach($repoMetrics as $index => $repo)
        {
            if(isset($repoList[$repo->id]))
            {
                $repo->name   = $repoList[$repo->id]->name;
                $repo->realID = $repoList[$repo->id]->id;
            }
            else
            {
                unset($repoMetrics[$index]);
            }
        }
        $this->view->repoMetrics = $repoMetrics;
    }

    /**
     * 加载并设置分页器。
     * Load and set pager.
     *
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return object
     */
    protected function setPager(int &$recPerPage = 20, int &$pageID = 1): object
    {
        $this->app->loadClass('pager', true);
        $pager = new pager(0, $recPerPage, $pageID);

        $recPerPage = $pager->recPerPage;
        $pageID     = $pager->pageID;
        return $pager;
    }
}
