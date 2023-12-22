<?php
declare(strict_types=1);
/**
 * The model file of testreport module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        https://www.zentao.net
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
     * @param  int          $reportID
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
                $case->lastRunDate   = $result ? $result->date       : '';
                $case->lastRunResult = $result ? $result->caseResult : '';
                $case->status        = ($result && $result->caseResult == 'blocked') ? 'blocked' : 'normal';
            }
        }

        return $cases;
    }

    /**
     * 获取测试报告的用例列表。
     * Get case id list.
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

        $failResults     = 0;
        $runCasesResults = array();
        foreach($results as $result) $runCasesResults[$result->run] = $result->caseResult;
        foreach($runCasesResults as $lastResult) if($lastResult == 'fail') $failResults++;

        return sprintf($this->lang->testreport->caseSummary, $caseCount, count($runCasesResults), count($results), $failResults);
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
            ->orderBy('value DESC, t1.id ASC')
            ->fetchAll('name');

        if(!$datas) return array();

        /* Set case result language item. */
        $this->app->loadLang('testcase');
        foreach($datas as $result => $data) $data->name = isset($this->lang->testcase->resultList[$result]) ? $this->lang->testcase->resultList[$result] : $this->lang->testtask->unexecuted;

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
            ->orderBy('value DESC, t1.id ASC')
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
            $childBuilds = $this->getChildBuilds($builds);
            foreach($builds as $build)           $bugIdList .= $build->bugs . ',';
            foreach($childBuilds as $childBuild) $bugIdList .= $childBuild->bugs . ',';
        }

        $bugIdList = array_unique(array_filter(explode(',', $bugIdList)));
        return $this->dao->select('*')->from(TABLE_BUG)->where('deleted')->eq(0)
            ->andWhere('product')->in($product)
            ->andWhere('openedDate')->lt("{$begin} 23:59:59")
            ->beginIF(is_array($builds) && $type == 'build')->andWhere('id')->in($bugIdList)->fi()
            ->beginIF(!is_array($builds) && $type == 'build')->andWhere("(resolvedBuild = 'trunk' and resolvedDate >= '{$begin}' and resolvedDate <= '{$end} 23:59:59')")->fi()
            ->beginIF($type == 'project')->andWhere("(id " . helper::dbIN($bugIdList) . " OR (resolvedBuild = 'trunk' and resolvedDate >= '{$begin}' and resolvedDate <= '{$end} 23:59:59'))")->fi()
            ->beginIF($type == 'execution')->andWhere("(id " . helper::dbIN($bugIdList) . " OR (resolvedBuild = 'trunk' and resolvedDate >= '{$begin}' and resolvedDate <= '{$end} 23:59:59'))")->fi()
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
        $childBuilds = $this->getChildBuilds($builds);
        foreach($builds as $build)           $storyIdList .= $build->stories . ',';
        foreach($childBuilds as $childBuild) $storyIdList .= $childBuild->stories . ',';

        $storyIdList = array_unique(array_filter(explode(',', $storyIdList)));
        return $this->dao->select('*')->from(TABLE_STORY)->where('deleted')->eq('0')->andWhere('id')->in($storyIdList)->fetchAll('id');
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

    /**
     * Get child builds.
     *
     * @param  array  $builds
     * @access public
     * @return array
     */
    public function getChildBuilds(array $builds)
    {
        $childBuildIDList = '';
        foreach($builds as $build) $childBuildIDList .= $build->builds . ',';

        $childBuildIDList = array_unique(array_filter(explode(',', $childBuildIDList)));
        if(empty($childBuildIDList)) return array();

        return $this->dao->select('id,name,bugs,stories')->from(TABLE_BUILD)->where('id')->in($childBuildIDList)->fetchAll('id');
    }
}
