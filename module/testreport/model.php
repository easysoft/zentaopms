<?php
/**
 * The model file of testreport module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class testreportModel extends model
{
    /**
     * 创建一个测试报告。
     * Create a test report.
     *
     * @param  object    $testreport
     * @access public
     * @return int|false
     */
    public function create(object $testreport): int|false
    {
        $this->dao->insert(TABLE_TESTREPORT)->data($testreport)->autocheck()
             ->batchCheck($this->config->testreport->create->requiredFields, 'notempty')
             ->batchCheck('begin,end', 'notempty')
             ->check('end', 'ge', $testreport->begin)
             ->exec();

        if(dao::isError()) return false;

        $reportID = $this->dao->lastInsertID();
        $this->loadModel('file')->updateObjectID($this->post->uid, $reportID, 'testreport');
        $this->file->saveUpload('testreport', $reportID);

        return $reportID;
    }

    /**
     * 更新一个测试报告。
     * Update a report.
     *
     * @param  object     $report
     * @param  object     $oldReport
     * @access public
     * @return array|bool
     */
    public function update(object $report, object $oldReport): array|bool
    {
        $this->dao->update(TABLE_TESTREPORT)->data($report)->autocheck()
             ->batchCheck($this->config->testreport->edit->requiredFields, 'notempty')
             ->batchCheck('begin,end', 'notempty')
             ->check('end', 'ge', $report->begin)
             ->where('id')->eq($report->id)
             ->exec();
        if(dao::isError()) return false;

        $this->loadModel('file')->processFile4Object('testreport', $oldReport, $report);

        return common::createChanges($oldReport, $report);
    }

    /**
     * 通过 id 获取测试报告。
     * Get report by id.
     *
     * @param  int    $reportID
     * @access public
     * @return object|false
     */
    public function getById(int $reportID): object|false
    {
        $report = $this->dao->select('*')->from(TABLE_TESTREPORT)->where('id')->eq($reportID)->fetch();
        if(!$report) return false;

        $report = $this->loadModel('file')->replaceImgURL($report, 'report');
        $report->files = $this->file->getByObject('testreport', $reportID);
        return $report;
    }

    /**
     * 获取报告列表。
     * Get report list.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  int    $extra
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(int $objectID, string $objectType, int $extra = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_TESTREPORT)
            ->where('deleted')->eq(0)
            ->beginIF($objectType == 'execution')->andWhere('execution')->eq($objectID)->fi()
            ->beginIF($objectType == 'project')->andWhere('project')->eq($objectID)->fi()
            ->beginIF($objectType == 'product' && $extra)->andWhere('objectID')->eq($extra)->andWhere('objectType')->eq('testtask')->fi()
            ->beginIF($objectType == 'product' && !$extra)->andWhere('product')->eq($objectID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get bug info and summary.
     *
     * @param  array  $tasks
     * @param  array  $productIdList
     * @param  string $begin
     * @param  string $end
     * @param  array  $builds
     * @access public
     * @return array
     */
    public function getBug4Report($tasks, $productIdList, $begin, $end, $builds)
    {
        $generatedBugs = $this->dao->select('*')->from(TABLE_BUG)->where('product')->in($productIdList)->andWhere('openedDate')->ge($begin)->andWhere('openedDate')->le("$end 23:59:59")->andWhere('deleted')->eq(0)->fetchAll();
        $resolvedBugs  = $this->dao->select('*')->from(TABLE_BUG)->where('product')->in($productIdList)->andWhere('resolvedDate')->ge($begin)->andWhere('resolvedDate')->le("$end 23:59:59")->andWhere('deleted')->eq(0)->fetchAll();
        $foundBugs     = array();
        $legacyBugs    = array();
        $activatedBugs = array();
        $byCaseNum     = 0;
        $buildIdList   = array_keys($builds);
        $taskIdList    = array_keys($tasks);

        $severityGroups = $statusGroups = $openedByGroups = $resolvedByGroups = $resolutionGroups = $moduleGroups = $typeGroups = $stageGoups = $handleGroups = array();

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

        $buildBugs = array();
        $allBugs   = $this->dao->select('*')->from(TABLE_BUG)->where('product')->in($productIdList)->andWhere('deleted')->eq(0)->fetchAll('id');

        foreach($allBugs as $bug)
        {
            $intersect = array_intersect(explode(',', $bug->openedBuild), $buildIdList);
            if(!empty($intersect)) $buildBugs[$bug->id] = $bug;
        }

        /* Get bug reactivated actions during the testreport. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('action')->eq('activated')
            ->andWhere('date')->ge($begin)
            ->andWhere('date')->le($end . ' 23:59:59')
            ->andWhere('objectID')->in(array_keys($buildBugs))
            ->fetchGroup('objectID', 'id');

        $actionIdList = array();
        foreach($actions as $bugID => $action) $actionIdList = array_merge($actionIdList, array_keys($action));

        $histories = $this->loadModel('action')->getHistory($actionIdList);
        foreach($actions as $bugID => $actionList)
        {
            foreach($actionList as $actionID => $action)
            {
                $action->history = zget($histories, $actionID, array());
            }
        }

        foreach($buildBugs as $bug)
        {
            $bugActions = zget($actions, $bug->id, array());
            foreach($bugActions as $action)
            {
                foreach($action->history as $history)
                {
                    if($history->field == 'openedBuild' and !in_array($history->new, $buildIdList)) continue;
                    $activatedBugs[$bug->id] = $bug;
                }
            }
        }

        /* Get the resolved bug data. */
        foreach($resolvedBugs as $bug)
        {
            if(array_intersect(explode(',', $bug->openedBuild), $buildIdList))
            {
                $resolvedDate = date('m-d', strtotime($bug->resolvedDate));
                $stageGroups[$bug->pri]['resolved']      += 1;
                $handleGroups['resolved'][$resolvedDate] += 1;
            }
        }

        /* Get the generated and leagcy bug data. */
        foreach($generatedBugs as $bug)
        {
            if(array_intersect(explode(',', $bug->openedBuild), $buildIdList))
            {
                $openedDate = date('m-d', strtotime($bug->openedDate));

                $foundBugs[$bug->id] = $bug;
                $stageGroups[$bug->pri]['generated']    += 1;
                $handleGroups['generated'][$openedDate] += 1;

                if($bug->status == 'active' or $bug->resolvedDate > "$end 23:59:59")
                {
                    $legacyBugs[$bug->id] = $bug;
                    $stageGroups[$bug->pri]['legacy'] += 1;

                    $beginTimeStamp = strtotime($begin);
                    $endTimeStamp   = strtotime($end);
                    for($i = $beginTimeStamp; $i <= $endTimeStamp; $i += 86400)
                    {
                        $dateTime = date('Y-m-d 23:59:59', $i);
                        if($bug->openedDate <= $dateTime and (helper::isZeroDate($bug->resolvedDate) or $bug->resolvedDate > $dateTime))
                        {
                            $date = date('m-d', $i);
                            $handleGroups['legacy'][$date] += 1;
                        }
                    }
                }
                if($bug->case and !empty($bug->testtask) and in_array($bug->testtask, $taskIdList)) $byCaseNum ++;
            }
        }

        $resolvedBugs   = 0;
        foreach($foundBugs as $bug)
        {
            $severityGroups[$bug->severity] = isset($severityGroups[$bug->severity]) ? $severityGroups[$bug->severity] + 1 : 1;
            $typeGroups[$bug->type]         = isset($typeGroups[$bug->type])         ? $typeGroups[$bug->type]         + 1 : 1;
            $statusGroups[$bug->status]     = isset($statusGroups[$bug->status])     ? $statusGroups[$bug->status]     + 1 : 1;
            $openedByGroups[$bug->openedBy] = isset($openedByGroups[$bug->openedBy]) ? $openedByGroups[$bug->openedBy] + 1 : 1;
            $moduleGroups[$bug->module]     = isset($moduleGroups[$bug->module])     ? $moduleGroups[$bug->module]     + 1 : 1;

            if($bug->resolvedBy) $resolvedByGroups[$bug->resolvedBy] = isset($resolvedByGroups[$bug->resolvedBy]) ? $resolvedByGroups[$bug->resolvedBy] + 1 : 1;
            if($bug->resolution) $resolutionGroups[$bug->resolution] = isset($resolutionGroups[$bug->resolution]) ? $resolutionGroups[$bug->resolution] + 1 : 1;
            if($bug->status == 'resolved' or $bug->status == 'closed') $resolvedBugs ++;
        }

        $bugSummary['foundBugs']           = count($foundBugs);
        $bugSummary['legacyBugs']          = $legacyBugs;
        $bugSummary['activatedBugs']       = count($activatedBugs);
        $bugSummary['countBugByTask']      = $byCaseNum;
        $bugSummary['bugConfirmedRate']    = empty($resolvedBugs) ? 0 : round((zget($resolutionGroups, 'fixed', 0) + zget($resolutionGroups, 'postponed', 0)) / $resolvedBugs * 100, 2);
        $bugSummary['bugCreateByCaseRate'] = empty($byCaseNum) ? 0 : round($byCaseNum / count($foundBugs) * 100, 2);
        $bugInfo['bugStageGroups']         = $stageGroups;
        $bugInfo['bugHandleGroups']        = $handleGroups;

        $this->app->loadLang('bug');
        $users = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted');
        $data  = array();
        foreach($severityGroups as $severity => $count)
        {
            $data[$severity] = new stdclass();
            $data[$severity]->name  = zget($this->lang->bug->severityList, $severity);
            $data[$severity]->value = $count;
        }
        $bugInfo['bugSeverityGroups'] = $data;

        $data = array();
        foreach($typeGroups as $type => $count)
        {
            $data[$type] = new stdclass();
            $data[$type]->name  = zget($this->lang->bug->typeList, $type);
            $data[$type]->value = $count;
        }
        $bugInfo['bugTypeGroups'] = $data;

        $data = array();
        foreach($statusGroups as $status => $count)
        {
            $data[$status] = new stdclass();
            $data[$status]->name  = zget($this->lang->bug->statusList, $status);
            $data[$status]->value = $count;
        }
        $bugInfo['bugStatusGroups'] = $data;

        $data = array();
        foreach($resolutionGroups as $resolution => $count)
        {
            $data[$resolution] = new stdclass();
            $data[$resolution]->name  = zget($this->lang->bug->resolutionList, $resolution);
            $data[$resolution]->value = $count;
        }
        $bugInfo['bugResolutionGroups'] = $data;

        $data = array();
        foreach($openedByGroups as $openedBy => $count)
        {
            $data[$openedBy] = new stdclass();
            $data[$openedBy]->name  = zget($users, $openedBy);
            $data[$openedBy]->value = $count;
        }
        $bugInfo['bugOpenedByGroups'] = $data;

        $this->loadModel('tree');
        $modules = array();
        $data    = array();
        if(!is_array($productIdList)) $productIdList = explode(',', $productIdList);
        foreach($productIdList as $productID) $modules += $this->tree->getOptionMenu($productID, $viewType = 'bug');
        foreach($moduleGroups as $moduleID => $count)
        {
            $data[$moduleID] = new stdclass();
            $data[$moduleID]->name  = zget($modules, $moduleID);
            $data[$moduleID]->value = $count;
        }
        $bugInfo['bugModuleGroups'] = $data;

        $data = array();
        foreach($resolvedByGroups as $resolvedBy => $count)
        {
            $data[$resolvedBy] = new stdclass();
            $data[$resolvedBy]->name  = zget($users, $resolvedBy);
            $data[$resolvedBy]->value = $count;
        }
        $bugInfo['bugResolvedByGroups'] = $data;

        return array($bugInfo, $bugSummary);
    }

    /**
     * 获取测试单的用例列表。
     * Get task cases.
     *
     * @param  array  $tasks
     * @param  string $begin
     * @param  string $end
     * @param  string $idList
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTaskCases(array $tasks, string $begin, string $end, string $idList = '', object $pager = null): array
    {
        $cases = $this->dao->select('t2.*, t1.task, t1.assignedTo, t1.status')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->in(array_keys($tasks))
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($idList)->andWhere('t2.id')->in($idList)->fi()
            ->page($pager)
            ->fetchGroup('task', 'id');

        $results = $this->dao->select('t1.*, t2.task')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.run = t2.id')
            ->where('t2.task')->in(array_keys($tasks))
            ->andWhere('t1.date')->ge($begin)
            ->andWhere('t1.date')->le($end . " 23:59:59")
            ->orderBy('date')
            ->fetchGroup('task', 'case');

        foreach($cases as $taskID => $caseList)
        {
            $results = zget($results, $taskID, array());

            foreach($caseList as $caseID => $case)
            {
                $result = zget($results, $caseID, '');

                $case->lastRunner    = $result ? $result->lastRunner : '';
                $case->lastRunDate   = $result ? $result->date : '';
                $case->lastRunResult = $result ? $result->caseResult : '';
                $case->status        = ($result && $result->caseResult == 'blocked') ? 'blocked' : 'normal';
            }
        }

        return $cases;
    }

    /**
     * 获取测试报告的用例列表。
     * Get caseID list.
     *
     * @param  int    $reportID
     * @access public
     * @return array
     */
    public function getCaseIdList(int $reportID): array
    {
        return $this->dao->select('`case`')->from(TABLE_TESTREPORT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.tasks = t2.task')
            ->leftJoin(TABLE_CASE)->alias('t3')->on('t2.case = t3.id')
            ->where('t1.id')->eq($reportID)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->fetchPairs('case');
    }

    /**
     * 获取报告概况。
     * Get report summary.
     *
     * @param  array    $tasks
     * @param  array    $cases
     * @param  string   $begin
     * @param  string   $end
     * @access public
     * @return string
     */
    public function getResultSummary(array $tasks, array $cases, string $begin, string $end): string
    {
        $caseCount = 0;
        $caseIdList = array();
        foreach($cases as $caseList)
        {
            foreach($caseList as $caseID => $case)
            {
                $caseIdList[] = $caseID;

                $caseCount++;
            }
        }

        $results = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.run = t2.id')
            ->where('t2.task')->in(array_keys($tasks))
            ->andWhere('t1.`case`')->in($caseIdList)
            ->andWhere('t1.date')->ge($begin)
            ->andWhere('t1.date')->le($end . " 23:59:59")
            ->orderBy('date')
            ->fetchAll('id');

        $failResults = 0;
        $runCasesNum = array();
        foreach($results as $result) $runCasesNum[$result->run] = $result->caseResult;
        foreach($runCasesNum as $lastResult) if($lastResult == 'fail') $failResults++;

        return sprintf($this->lang->testreport->caseSummary, $caseCount, count($runCasesNum), count($results), $failResults);
    }

    /**
     * 获取测试报告的用例执行结果。
     * Get per run result for testreport.
     *
     * @param  array        $tasks
     * @param  array|string $cases
     * @param  string       $begin
     * @param  string       $end
     * @access public
     * @return string
     */
    public function getPerCaseResult4Report(array $tasks, array|string $cases, string $begin, string $end): array
    {
        /* Get case result. */
        $datas = $this->dao->select("t1.caseResult AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')
            ->on('t1.run= t2.id')
            ->where('t2.task')->in(array_keys($tasks))
            ->andwhere('t1.date = t2.lastRunDate')
            ->andWhere('t1.`case`')->in($cases)
            ->andWhere('t1.date')->ge($begin)
            ->andWhere('t1.date')->le($end . " 23:59:59")
            ->groupBy('t1.caseResult')
            ->orderBy('value DESC')
            ->fetchAll('name');

        if(!$datas) return array();

        /* Set case result language item. */
        $this->app->loadLang('testcase');
        foreach($datas as $result => $data) $data->name = isset($this->lang->testcase->resultList[$result])? $this->lang->testcase->resultList[$result] : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * 获取测试报告的用例执行人。
     * Get per case runner for testreport.
     *
     * @param  array        $tasks
     * @param  array|string $cases
     * @param  string       $begin
     * @param  string       $end
     * @access public
     * @return array
     */
    public function getPerCaseRunner4Report(array $tasks, array|string $cases, string $begin, string $end): array
    {
        /*  Get the last runner and the number of runs. */
        $datas = $this->dao->select("t1.lastRunner AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')
            ->on('t1.run= t2.id')
            ->where('t2.task')->in(array_keys($tasks))
            ->andwhere('t1.date = t2.lastRunDate')
            ->andWhere('t1.`case`')->in($cases)
            ->andWhere('t1.date')->ge($begin)
            ->andWhere('t1.date')->le($end . " 23:59:59")
            ->groupBy('t1.lastRunner')
            ->orderBy('value DESC')
            ->fetchAll('name');

        if(!$datas) return array();

        /* Set the realname of the last runner. */
        $users = $this->loadModel('user')->getPairs('noclosed|noletter');
        foreach($datas as $result => $data) $data->name = $result ? zget($users, $result, $result) : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * 为测试报告获取 bugs。
     * Get bugs for test
     *
     * @param  array|bool  $builds
     * @param  int|array   $product
     * @param  string      $begin
     * @param  string      $end
     * @param  string      $type
     * @access public
     * @return array|false
     */
    public function getBugs4Test(array|bool $builds, int|array $product, string $begin, string $end, string $type = 'build'): array|false
    {
        $bugIdList = '';
        if(is_array($builds))
        {
            foreach($builds as $build) $bugIdList .= $build->bugs . ',';
        }
        return $this->dao->select('*')->from(TABLE_BUG)->where('deleted')->eq(0)
            ->andWhere('product')->in($product)
            ->andWhere('openedDate')->lt("{$begin} 23:59:59")
            ->beginIF(is_array($builds) && $type == 'build')->andWhere('id')->in(trim($bugIdList, ','))->fi()
            ->beginIF(!is_array($builds) && $type == 'build')->andWhere("(resolvedBuild = 'trunk' and resolvedDate >= '{$begin}' and resolvedDate <= '{$end} 23:59:59')")->fi()
            ->beginIF($type == 'project')->andWhere("(id " . helper::dbIN(trim($bugIdList, ',')) . " OR (resolvedBuild = 'trunk' and resolvedDate >= '{$begin}' and resolvedDate <= '{$end} 23:59:59'))")
            ->beginIF($type == 'execution')->andWhere("(id " . helper::dbIN(trim($bugIdList, ',')) . " OR (resolvedBuild = 'trunk' and resolvedDate >= '{$begin}' and resolvedDate <= '{$end} 23:59:59'))")
            ->fetchAll('id');
    }

    /**
     * 获取测试报告的需求。
     * Get stories for test.
     *
     * @param  array  $builds
     * @access public
     * @return array
     */
    public function getStories4Test(array $builds): array
    {
        $storyIdList = '';
        foreach($builds as $build) $storyIdList .= $build->stories . ',';

        return $this->dao->select('*')->from(TABLE_STORY)->where('deleted')->eq('0')->andWhere('id')->in(trim($storyIdList, ','))->fetchAll('id');
    }

    /**
     * 获取测试报告键对。
     * Get pairs.
     *
     * @param  int    $productID
     * @param  int    $appendID
     * @access public
     * @return array
     */
    public function getPairs(int $productID = 0, int $appendID = 0): array
    {
        return $this->dao->select('id,title')->from(TABLE_TESTREPORT)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($appendID)->orWhere('id')->eq($appendID)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * 判断操作是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $report
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $report, string $action): bool
    {
        return true;
    }
}
