<?php
declare(strict_types=1);
/**
 * The zen file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testreport
 * @link        https://www.zentao.net
 */
class testreportZen extends testreport
{
    /**
     * 检查相关权限，并且设置相关菜单。
     * Check related access and set menu.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return int
     */
    protected function commonAction(int $objectID, string $objectType = 'product'): int
    {
        if($objectType == 'product')
        {
            $productID = $this->product->checkAccess($objectID, $this->products);
            $this->loadModel('qa')->setMenu($productID);
            $this->view->productID = $productID;
            return $productID;
        }

        if($objectType == 'execution')
        {
            $executions  = $this->execution->getPairs();
            $executionID = $this->execution->checkAccess($objectID, $executions);
            $this->execution->setMenu($executionID);
            $this->view->executionID = $executionID;
            return $executionID;
        }

        if($objectType == 'project')
        {
            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->checkAccess($objectID, $projects);
            $this->project->setMenu($projectID);
            $this->view->projectID = $projectID;
            return $projectID;
        }
    }

    /**
     * 为浏览页面获取报告
     * Get reports for browse.
     *
     * @param  int       $objectID
     * @param  string    $objectType
     * @param  int       $extra
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return array
     */
    protected function getReportsForBrowse(int $objectID = 0, string $objectType = 'product', int $extra = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $reports = $this->testreport->getList($objectID, $objectType, $extra, $orderBy, $pager);

        if(strpos('|project|execution|', $objectType) !== false && ($extra || isset($_POST['taskIdList'])))
        {
            $taskIdList = isset($_POST['taskIdList']) ? $_POST['taskIdList'] : array($extra);
            foreach($reports as $reportID => $report)
            {
                $tasks = explode(',', $report->tasks);
                if(count($tasks) != count($taskIdList) || array_diff($tasks, $taskIdList)) unset($reports[$reportID]);
            }
            $pager->setRecTotal(count($reports));
        }

        $this->view->pager = $pager;
        return $reports;
    }

    /**
     * 为创建获取任务键值。
     * Get task pairs for creation.
     *
     * @param  int       $objectID
     * @param  int       $extra
     * @access protected
     * @return array
     */
    protected function assignTaskParisForCreate(int $objectID = 0, int $extra = 0): array
    {
        if(!$objectID && $extra) $productID = $extra;
        if($objectID)
        {
            $task      = $this->testtask->getByID($objectID);
            $productID = $this->commonAction($task->product, 'product');
        }

        $taskPairs = array();
        $tasks     = $this->testtask->getProductTasks($productID, empty($objectID) ? 'all' : $task->branch, 'local,totalStatus', '', '', 'id_desc', null);
        foreach($tasks as $testTask)
        {
            if($testTask->build != 'trunk') $taskPairs[$testTask->id] = $testTask->name;
        }
        if(!$taskPairs) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->testreport->noTestTask, 'confirmed' => $this->createLink('testtask', 'create', "proudctID={$productID}"), 'canceled' => inlink('browse', "proudctID={$productID}"))));

        if(!$objectID)
        {
            $objectID  = key($taskPairs);
            $task      = $this->testtask->getByID($objectID);
            $productID = $this->commonAction($task->product, 'product');
        }

        $this->view->taskPairs = $taskPairs;
        $this->view->productID = $productID;
        return array($objectID, $task, $productID);
    }

    /**
     * 获取测试单的测试报告数据。
     * Get testtask report data.
     *
     * @param  int       $objectID
     * @param  string    $begin
     * @param  string    $end
     * @param  int       $productID
     * @param  object    $task
     * @param  string    $method
     * @access protected
     * @return array
     */
    protected function assignTesttaskReportData(int $objectID, string $begin = '', string $end = '', int $productID = 0, object $task = null, string $method = 'create'): array
    {
        $begin = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $task->begin;
        $end   = !empty($end) ? date("Y-m-d", strtotime($end)) : $task->end;

        $productIdList[$productID] = $productID;

        $build  = $this->build->getById((int)$task->build);
        $builds = !empty($build->id) ? array($build->id => $build) : array();
        $bugs   = $this->testreport->getBugs4Test($builds, $productID, $begin, $end);

        $tasks     = array($task->id => $task);
        $owner     = $task->owner;
        $stories   = empty($build->stories) ? array() : $this->story->getByList($build->stories);
        $execution = $this->execution->getById($task->execution);

        $this->setChartDatas($objectID);

        if($method == 'create')
        {
            if($this->app->tab == 'execution') $this->execution->setMenu($task->execution);
            if($this->app->tab == 'project') $this->project->setMenu($task->project);

            $this->view->title       = $task->name . $this->lang->testreport->create;
            $this->view->reportTitle = date('Y-m-d') . " TESTTASK#{$task->id} {$task->name} {$this->lang->testreport->common}";
        }
        $this->app->loadLang('bug');
        $this->app->loadLang('story');

        $reportData = array('begin' => $begin, 'end' => $end, 'builds' => $builds, 'tasks' => $tasks, 'owner' => $owner, 'stories' => $stories, 'bugs' => $bugs, 'execution' => $execution, 'productIdList' => $productIdList);
        return $reportData;
    }

    /**
     * 获取创建项目 / 执行的测试报告数据。
     * Get project or execution report data for creation.
     *
     * @param  int       $objectID
     * @param  string    $objectType
     * @param  int       $extra
     * @param  string    $begin
     * @param  string    $end
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function assignProjectReportDataForCreate(int $objectID, string $objectType, int $extra, string $begin = '', string $end = '', int $executionID = 0): array
    {
        $owners        = array();
        $buildIdList   = array();
        $productIdList = array();
        $tasks         = $this->testtask->getExecutionTasks($executionID, $objectType);
        foreach($tasks as $i => $task)
        {
            if($extra && strpos(",{$extra},", ",{$task->id},") === false)
            {
                unset($tasks[$i]);
                continue;
            }

            $owners[$task->owner] = $task->owner;
            $productIdList[$task->product] = $task->product;
            $this->setChartDatas($task->id);
            if($task->build != 'trunk') $buildIdList[$task->build] = $task->build;
        }

        $task      = $objectID ? $this->testtask->getByID($extra) : key($tasks);
        $begin     = !empty($begin) ? date("Y-m-d", strtotime($begin)) : (string)$task->begin;
        $end       = !empty($end) ? date("Y-m-d", strtotime($end)) : (string)$task->end;
        $builds    = $this->build->getByList($buildIdList);
        $bugs      = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'execution');
        $execution = $this->execution->getById($executionID);
        $stories   = !empty($builds) ? $this->testreport->getStories4Test($builds) : $this->story->getExecutionStories($execution->id);;
        $owner     = current($owners);

        if($this->app->tab == 'qa')
        {
            $productID = $this->product->checkAccess(key($productIdList), $this->products);
            $this->loadModel('qa')->setMenu($productID);
        }
        elseif($this->app->tab == 'project')
        {
            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->checkAccess($execution->id, $projects);
            $this->project->setMenu($projectID);
        }

        $this->view->title       = $execution->name . $this->lang->testreport->create;
        $this->view->reportTitle = date('Y-m-d') . ' ' . strtoupper($objectType) . "#{$execution->id} {$execution->name} {$this->lang->testreport->common}";

        return array('begin' => $begin, 'end' => $end, 'builds' => $builds, 'tasks' => $tasks, 'owner' => $owner, 'stories' => $stories, 'bugs' => $bugs, 'execution' => $execution, 'productIdList' => $productIdList);
    }

    /**
     * 获取编辑项目 / 执行的测试报告数据。
     * Get project or execution report data for edit.
     *
     * @param  object    $report
     * @param  string    $begin
     * @param  string    $end
     * @access protected
     * @return array
     */
    protected function assignProjectReportDataForEdit(object $report, string $begin = '', string $end = ''): array
    {
        $begin = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $report->begin;
        $end   = !empty($end) ? date("Y-m-d", strtotime($end)) : $report->end;

        $productIdList[$report->product] = $report->product;

        $tasks = $this->testtask->getByList(explode(',', $report->tasks));
        foreach($tasks as $task) $this->setChartDatas($task->id);

        $execution = $this->execution->getById($report->execution);
        $builds    = $this->build->getByList(explode(',', $report->builds));
        $stories   = !empty($builds) ? $this->testreport->getStories4Test($builds) : $this->story->getExecutionStories($report->execution);
        $bugs      = $this->testreport->getBugs4Test($builds, $productIdList, $begin, $end, 'execution');

        return array('begin' => $begin, 'end' => $end, 'builds' => $builds, 'tasks' => $tasks, 'stories' => $stories, 'bugs' => $bugs, 'execution' => $execution, 'productIdList' => $productIdList);
    }

    /**
     * 展示测试报告数据。
     * Assign report data.
     *
     * @param  array     $reportData
     * @param  string    $method
     * @access protected
     * @return void
     */
    protected function assignReportData(array $reportData, string $method, object $pager = null): void
    {
        foreach($reportData as $key => $value)
        {
            if(strpos(',productIdList,tasks,', ",{$key},") !== false)
            {
                $this->view->{$key} = join(',', array_keys($value));
            }
            else
            {
                $this->view->{$key} = $value;
            }
        }

        if($method == 'create')
        {
            /* Get testtasks members. */
            $tasks       = $reportData['tasks'];
            $taskMembers = '';
            foreach($tasks as $testtask)
            {
                if(!empty($testtask->members)) $taskMembers .= ',' . (string)$testtask->members;
            }
            $taskMembers = explode(',', $taskMembers);
            $members     = $this->dao->select('DISTINCT lastRunner')->from(TABLE_TESTRUN)->where('task')->in(array_keys($tasks))->fetchPairs('lastRunner', 'lastRunner');
            $this->view->members = array_merge($members, $taskMembers);
        }

        $this->view->storySummary = $this->product->summary($reportData['stories']);
        $this->view->users        = $this->user->getPairs('noletter|noclosed|nodeleted');

        $cases = $method != 'view' ? $this->testreport->getTaskCases($reportData['tasks'], $reportData['begin'], $reportData['end']) : $this->testreport->getTaskCases($reportData['tasks'], $reportData['begin'], $reportData['end'], $reportData['cases'], $pager);
        $this->view->cases        = $cases;
        $this->view->caseSummary  = $this->testreport->getResultSummary($reportData['tasks'], $cases, $reportData['begin'], $reportData['end']);

        $caseList = array();
        foreach($cases as $casesList)
        {
            foreach($casesList as $caseID => $case) $caseList[$caseID] = $case;
        }
        $this->view->caseList = $caseList;

        $caseIdList = isset($reportData['cases']) ? $reportData['cases'] : array_keys($caseList);
        $perCaseResult = $this->testreport->getPerCaseResult4Report($reportData['tasks'], $caseIdList, $reportData['begin'], $reportData['end']);
        $perCaseRunner = $this->testreport->getPerCaseRunner4Report($reportData['tasks'], $caseIdList, $reportData['begin'], $reportData['end']);
        $this->view->datas['testTaskPerRunResult'] = $this->loadModel('report')->computePercent($perCaseResult);
        $this->view->datas['testTaskPerRunner']    = $this->report->computePercent($perCaseRunner);

        list($bugInfo, $bugSummary) = $this->buildReportBugData($reportData['tasks'], $reportData['productIdList'], $reportData['begin'], $reportData['end'], $reportData['builds']);
        if($method == 'view') $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $this->view->bugInfo    = $bugInfo;
        $this->view->legacyBugs = $bugSummary['legacyBugs'];
        $this->view->bugSummary = $bugSummary;

        if($method == 'view') $this->view->pager = $pager;
    }

    /**
     * 为创建查看测试报告数据构建报告数据。
     * Build testreport data for view.
     *
     * @param  object    $report
     * @access protected
     * @return array
     */
    protected function buildReportDataForView(object $report): array
    {
        $reportData = array();
        $reportData['begin']         = $report->begin;
        $reportData['end']           = $report->end;
        $reportData['cases']         = $report->cases;
        $reportData['productIdList'] = array($report->product);
        $reportData['execution']     = $this->execution->getById($report->execution);
        $reportData['stories']       = $report->stories ? $this->story->getByList($report->stories)  : array();
        $reportData['tasks']         = $report->tasks   ? $this->testtask->getByList(explode(',', $report->tasks)) : array();
        $reportData['builds']        = $report->builds  ? $this->build->getByList(explode(',', $report->builds))   : array();
        $reportData['bugs']          = $report->bugs    ? $this->bug->getByIdList($report->bugs)     : array();
        $reportData['report']        = $report;
        return $reportData;

    }

    /**
     * 为创建准备测试报告数据。
     * Prepare testreport data for creation.
     *
     * @access protected
     * @return object
     */
    protected function prepareTestreportForCreate(): object
    {
        /* Build testreport. */
        $execution  = $this->execution->getByID((int)$this->post->execution);
        $testreport = form::data($this->config->testreport->form->create)
            ->setDefault('project', empty($execution) ? 0 : ($execution->type == 'project' ? $execution->id : $execution->project))
            ->get();
        $testreport = $this->loadModel('file')->processImgURL($testreport, $this->config->testreport->editor->create['id'], $this->post->uid);
        $testreport->members = trim($testreport->members, ',');

        /* Check reuqired. */
        $reportErrors = array();
        foreach(explode(',', $this->config->testreport->create->requiredFields) as $field)
        {
            $field = trim($field);
            if($field && empty($testreport->{$field}))
            {
                $fieldName = $this->config->testreport->form->create[$field]['type'] != 'array' ? "{$field}" : "{$field}[]";
                $reportErrors[$fieldName][] = sprintf($this->lang->error->notempty, $this->lang->testreport->{$field});
            }
         }
        if($testreport->end < $testreport->begin) $reportErrors['end'][] = sprintf($this->lang->error->ge, $this->lang->testreport->end, $testreport->begin);
        if(!empty($reportErrors)) dao::$errors = $reportErrors;

        return $testreport;
    }

    /**
     * 为编辑准备测试报告数据。
     * Prepare testreport data for edit.
     *
     * @param  int       $reportID
     * @access protected
     * @return object
     */
    protected function prepareTestreportForEdit(int $reportID): object
    {
        /* Build testreport. */
        $testreport = form::data($this->config->testreport->form->edit)->add('id', $reportID)->get();
        $testreport = $this->loadModel('file')->processImgURL($testreport, $this->config->testreport->editor->edit['id'], $this->post->uid);
        $testreport->members = trim($testreport->members, ',');

        /* Check reuqired. */
        $reportErrors = array();
        foreach(explode(',', $this->config->testreport->edit->requiredFields) as $field)
        {
            $field = trim($field);
            if($field && empty($testreport->{$field}))
            {
                $fieldName = $this->config->testreport->form->edit[$field]['type'] != 'array' ? "{$field}" : "{$field}[]";
                $reportErrors[$fieldName][] = sprintf($this->lang->error->notempty, $this->lang->testreport->{$field});
            }
         }
        if($testreport->end < $testreport->begin) $reportErrors['end'][] = sprintf($this->lang->error->ge, $this->lang->testreport->end, $testreport->begin);
        if(!empty($reportErrors)) dao::$errors = $reportErrors;

        return $testreport;
    }

    /**
     * 构建测试报告的 bug 信息和汇总信息。
     * build bug info and summary of test report.
     *
     * @param  array  $tasks
     * @param  array  $productIdList
     * @param  string $begin
     * @param  string $end
     * @param  array  $builds
     * @access public
     * @return array
     */
    protected function buildReportBugData(array $tasks, array $productIdList, string $begin, string $end, array $builds): array
    {
        /* Get activated bugs. */
        $buildIdList   = array_keys($builds) + array_keys($this->testreport->getChildBuilds($builds));
        $activatedBugs = $this->bug->getActivatedBugs($productIdList, $begin, $end, $buildIdList);

        /* Get stage and handle groups. */
        list($stageGroups, $handleGroups) = $this->getStageAndHandleGroups($productIdList, $begin, $end, $buildIdList);

        /* Get the generated and leagcy bug data, and its groups. */
        list($foundBugs, $legacyBugs, $stageGroups, $handleGroups, $byCaseNum) = $this->getGeneratedAndLegacyBugData(array_keys($tasks), $productIdList, $begin, $end, $buildIdList, $stageGroups, $handleGroups);

        /* Get the found bug's groups. */
        list($severityGroups, $typeGroups, $statusGroups, $openedByGroups, $moduleGroups, $resolvedByGroups, $resolutionGroups, $resolvedBugs) = $this->getFoundBugGroups($foundBugs);

        /* Set bug summary. */
        $bugSummary['foundBugs']           = count($foundBugs);
        $bugSummary['legacyBugs']          = $legacyBugs;
        $bugSummary['activatedBugs']       = count($activatedBugs);
        $bugSummary['countBugByTask']      = $byCaseNum;
        $bugSummary['bugConfirmedRate']    = empty($resolvedBugs) ? 0 : round((zget($resolutionGroups, 'fixed', 0) + zget($resolutionGroups, 'postponed', 0)) / $resolvedBugs * 100, 2);
        $bugSummary['bugCreateByCaseRate'] = empty($byCaseNum) ? 0 : round($byCaseNum / count($foundBugs) * 100, 2);

        /* Set bug info. */
        $bugInfo = $this->buildBugInfo($stageGroups, $handleGroups, $severityGroups, $typeGroups, $statusGroups, $openedByGroups, $moduleGroups, $resolvedByGroups, $resolutionGroups, $productIdList);

        return array($bugInfo, $bugSummary);
    }

    /**
     * 构建 bug 信息。
     * Build bug info.
     *
     * @param  array     $stageGroups
     * @param  array     $handleGroups
     * @param  array     $severityGroups
     * @param  array     $typeGroups
     * @param  array     $statusGroups
     * @param  array     $openedByGroups
     * @param  array     $moduleGroups
     * @param  array     $resolvedByGroups
     * @param  array     $resolutionGroups
     * @param  array     $productIdList
     * @access protected
     * @return array
     */
    protected function buildBugInfo(array $stageGroups, array $handleGroups, array $severityGroups, array $typeGroups, array $statusGroups, array $openedByGroups, array $moduleGroups, array $resolvedByGroups, array $resolutionGroups, array $productIdList): array
    {
        $bugInfo['bugStageGroups']  = $stageGroups;
        $bugInfo['bugHandleGroups'] = $handleGroups;

        $this->app->loadLang('bug');
        $fields = array('severityGroups' => 'severityList', 'typeGroups' => 'typeList', 'statusGroups' => 'statusList', 'resolutionGroups' => 'resolutionList', 'openedByGroups' => 'openedBy', 'resolvedByGroups' => 'resolvedBy');
        $users  = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted');
        foreach($fields as $variable => $fieldType)
        {
            $data = array();
            foreach(${$variable} as $type => $count)
            {
                $data[$type] = new stdclass();
                $data[$type]->name  = strpos($fieldType, 'By') === false ? zget($this->lang->bug->{$fieldType}, $type) : zget($users, $type);
                $data[$type]->value = $count;
            }
            $bugInfo['bug' . ucfirst($variable)] = $data;
        }

        $this->loadModel('tree');
        $modules = array();
        if(!is_array($productIdList)) $productIdList = explode(',', $productIdList);
        foreach($productIdList as $productID) $modules += $this->tree->getOptionMenu($productID, 'bug');

        $data = array();
        foreach($moduleGroups as $moduleID => $count)
        {
            $data[$moduleID] = new stdclass();
            $data[$moduleID]->name  = zget($modules, $moduleID);
            $data[$moduleID]->value = $count;
        }
        $bugInfo['bugModuleGroups'] = $data;

        return $bugInfo;
    }


    /**
     * 获取阶段和状态分组。
     * Get stage and handle groups.
     *
     * @param  array     $productIdList
     * @param  string    $begin
     * @param  string    $end
     * @access protected
     * @return array
     */
    protected function getStageAndHandleGroups(array $productIdList, string $begin, string $end, array $buildIdList): array
    {
        /* Init stageGroups. */
        $stageGroups = array();
        foreach($this->lang->bug->priList as $priKey => $priValue)
        {
            $stageGroups[$priKey]['generated'] = 0;
            $stageGroups[$priKey]['legacy']    = 0;
            $stageGroups[$priKey]['resolved']  = 0;
        }

        /* Init handleGroups. */
        $handleGroups   = array();
        $beginTimeStamp = strtotime($begin);
        $endTimeStamp   = strtotime($end);
        for($i = $beginTimeStamp; $i <= $endTimeStamp; $i += 86400)
        {
            $date = date('m-d', $i);
            $handleGroups['generated'][$date] = 0;
            $handleGroups['legacy'][$date]    = 0;
            $handleGroups['resolved'][$date]  = 0;
        }

        /* Get the resolved bug data. */
        $resolvedBugs = $this->bug->getProductBugs($productIdList, 'resolved', $begin, $end);
        foreach($resolvedBugs as $bug)
        {
            if(array_intersect(explode(',', $bug->openedBuild), $buildIdList))
            {
                $resolvedDate = date('m-d', strtotime($bug->resolvedDate));
                $stageGroups[$bug->pri]['resolved']      += 1;
                $handleGroups['resolved'][$resolvedDate] += 1;
            }
        }

        return array($stageGroups, $handleGroups);
    }

    /**
     * 获取产生和遗留的 bug。
     * Get generated and legacy bugs.
     *
     * @param  array     $taskIdList
     * @param  array     $productIdList
     * @param  string    $begin
     * @param  string    $end
     * @param  array     $buildIdList
     * @param  array     $stageGroups
     * @param  array     $handleGroups
     * @access protected
     * @return array
     */
    protected function getGeneratedAndLegacyBugData($taskIdList, $productIdList, $begin, $end, $buildIdList, $stageGroups, $handleGroups): array
    {
        $byCaseNum      = 0;
        $foundBugs      = $legacyBugs = array();
        $beginTimeStamp = strtotime($begin);
        $endTimeStamp   = strtotime($end);
        $generatedBugs  = $this->bug->getProductBugs($productIdList, 'opened', $begin, $end);
        foreach($generatedBugs as $bug)
        {
            if(array_intersect(explode(',', $bug->openedBuild), $buildIdList))
            {
                $openedDate = date('m-d', strtotime($bug->openedDate));

                /* Set generated bugs. */
                $foundBugs[$bug->id] = $bug;
                $stageGroups[$bug->pri]['generated']    += 1;
                $handleGroups['generated'][$openedDate] += 1;

                /* Set legacy bugs. */
                if($bug->status == 'active' || $bug->resolvedDate > "{$end} 23:59:59")
                {
                    $legacyBugs[$bug->id] = $bug;
                    $stageGroups[$bug->pri]['legacy'] += 1;

                    for($currentTimeStamp = $beginTimeStamp; $currentTimeStamp <= $endTimeStamp; $currentTimeStamp += 86400)
                    {
                        $dateTime = date('Y-m-d 23:59:59', $currentTimeStamp);
                        if($bug->openedDate <= $dateTime && (helper::isZeroDate($bug->resolvedDate) || $bug->resolvedDate > $dateTime))
                        {
                            $date = date('m-d', $currentTimeStamp);
                            $handleGroups['legacy'][$date] += 1;
                        }
                    }
                }
                if($bug->case && !empty($bug->testtask) && in_array($bug->testtask, $taskIdList)) $byCaseNum ++;
            }
        }
        return array($foundBugs, $legacyBugs, $stageGroups, $handleGroups, $byCaseNum);
    }

    /**
     * 获取产生的 bug 分组数据。
     * Get found bug groups.
     *
     * @param  array     $foundBugs
     * @access protected
     * @return array
     */
    protected function getFoundBugGroups($foundBugs): array
    {
        $resolvedBugs   = 0;
        $severityGroups = $typeGroups = $statusGroups = $openedByGroups = $moduleGroups = $resolvedByGroups = $resolutionGroups = array();
        foreach($foundBugs as $bug)
        {
            $severityGroups[$bug->severity] = isset($severityGroups[$bug->severity]) ? $severityGroups[$bug->severity] + 1 : 1;
            $typeGroups[$bug->type]         = isset($typeGroups[$bug->type])         ? $typeGroups[$bug->type]         + 1 : 1;
            $statusGroups[$bug->status]     = isset($statusGroups[$bug->status])     ? $statusGroups[$bug->status]     + 1 : 1;
            $openedByGroups[$bug->openedBy] = isset($openedByGroups[$bug->openedBy]) ? $openedByGroups[$bug->openedBy] + 1 : 1;
            $moduleGroups[$bug->module]     = isset($moduleGroups[$bug->module])     ? $moduleGroups[$bug->module]     + 1 : 1;

            if($bug->resolvedBy) $resolvedByGroups[$bug->resolvedBy] = isset($resolvedByGroups[$bug->resolvedBy]) ? $resolvedByGroups[$bug->resolvedBy] + 1 : 1;
            if($bug->resolution) $resolutionGroups[$bug->resolution] = isset($resolutionGroups[$bug->resolution]) ? $resolutionGroups[$bug->resolution] + 1 : 1;
            if($bug->status == 'resolved' || $bug->status == 'closed') $resolvedBugs ++;
        }
        return array($severityGroups, $typeGroups, $statusGroups, $openedByGroups, $moduleGroups, $resolvedByGroups, $resolutionGroups, $resolvedBugs);
    }

    /**
     * 设置用例的报告数据。
     * Set chart datas of cases.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    protected function setChartDatas(int $taskID): void
    {
        $this->loadModel('report');
        foreach($this->lang->testtask->report->charts as $chart => $title)
        {
            if(strpos($chart, 'testTask') === false) continue;

            $chartFunc   = 'getDataOf' . $chart;
            $chartData   = $this->testtask->$chartFunc($taskID);
            $chartOption = $this->config->testtask->report->options;

            $this->view->charts[$chart] = $chartOption;

            if(isset($this->view->datas[$chart]))
            {
                $existDatas = $this->view->datas[$chart];
                $sum        = 0;
                foreach($chartData as $key => $data)
                {
                    if(isset($existDatas[$key]))
                    {
                        $data->value += $existDatas[$key]->value;
                        unset($existDatas[$key]);
                    }
                    $sum += $data->value;
                }
                foreach($existDatas as $key => $data)
                {
                    $chartData[$key] = $data;
                    $sum += $data->value;
                }

                if($sum)
                {
                    foreach($chartData as $data) $data->percent = round($data->value / $sum, 2);
                }

                ksort($chartData);
                $this->view->datas[$chart] = $chartData;
            }
            else
            {
                $this->view->datas[$chart] = $this->report->computePercent($chartData);
            }
        }
    }
}
