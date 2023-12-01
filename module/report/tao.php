<?php
declare(strict_types=1);
/**
 * The tao file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     report
 * @link        https://www.zentao.net
 */
class reportTao extends reportModel
{
    /**
     * 获取某年的年度产品数据。
     * Get annual product stat.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    protected function getAnnualProductStat(array $accounts, string $year): array
    {
        /* Get created plans, created stories and closed stories in this year. */
        $planGroups = $this->dao->select('t1.id,t1.product')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.id=t2.objectID and t2.objectType='productplan'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('LEFT(t2.date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t2.actor')->in($accounts)->fi()
            ->andWhere('t2.action')->eq('opened')
            ->fetchGroup('product', 'id');
        $createdStoryStats = $this->dao->select("product,sum(if((type = 'requirement'), 1, 0)) as requirement, sum(if((type = 'story'), 1, 0)) as story")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(openedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('openedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');
        $closedStoryStats = $this->dao->select("product,sum(if((status = 'closed'), 1, 0)) as closed")->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(closedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('closedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');

        /* Get products created or operated in this year. */
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(createdDate, 4)', true)->eq($year)
            ->beginIF($accounts)
            ->andWhere('createdBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->markRight(1)
            ->fi()
            ->orWhere('id')->in(array_merge(array_keys($planGroups), array_keys($createdStoryStats), array_keys($closedStoryStats)))
            ->markRight(1)
            ->andWhere('shadow')->eq(0)
            ->fetchAll('id');

        return array($products, $planGroups, $createdStoryStats, $closedStoryStats);
    }

    /**
     * 获取某年的年度执行数据。
     * Get annual execution stat.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    protected function getAnnualExecutionStat(array $accounts, string $year): array
    {
        /* Get count of finished task and stories. */
        $finishedMultiTasks = $this->dao->select('distinct t1.root')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.root=t2.id')
            ->where('t1.type')->eq('execution')
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->andWhere('t2.multiple')->eq('1')
            ->andWhere('LEFT(`join`, 4)')->eq($year)
            ->fetchPairs();
        $taskStats = $this->dao->select('execution, count(*) as finishedTask, sum(if((story != 0), 1, 0)) as finishedStory')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('(finishedBy', true)->ne('')
            ->andWhere('LEFT(finishedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('finishedBy')->in($accounts)->fi()
            ->markRight(1)
            ->orWhere('id')->in($finishedMultiTasks)
            ->markRight(1)
            ->groupBy('execution')
            ->fetchAll('execution');
        /* Get changed executions in this year. */
        $executions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('deleted')->eq(0)
            ->andwhere('type')->eq('sprint')
            ->andwhere('multiple')->eq('1')
            ->andWhere('LEFT(`begin`, 4)', true)->eq($year)
            ->orWhere('LEFT(`end`, 4)')->eq($year)
            ->markRight(1)
            ->beginIF($accounts)
            ->andWhere('openedBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('PM')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->orWhere('id')->in(array_keys($taskStats))
            ->markRight(1)
            ->fi()
            ->orderBy('`order` desc')
            ->fetchAll('id');
        /* Get resolved bugs in this year. */
        $resolvedBugs = $this->dao->select('t2.execution, count(*) as count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.resolvedBuild=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.execution')->in(array_keys($executions))
            ->andWhere('t1.resolvedBy')->ne('')
            ->andWhere('LEFT(t1.resolvedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t1.resolvedBy')->in($accounts)->fi()
            ->groupBy('t2.execution')
            ->fetchAll('execution');
        return array($executions, $taskStats, $resolvedBugs);
    }

    /**
     * 构建年度报告的用例数据。
     * Build annual case stat.
     *
     * @param  array     $accounts
     * @param  string    $year
     * @param  array     $actionStat
     * @param  array     $resultStat
     * @access protected
     * @return array
     */
    protected function buildAnnualCaseStat(array $accounts, string $year, array $actionStat, array $resultStat): array
    {
        /* Build create case stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('case')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();
        while($action = $stmt->fetch())
        {
            $month = substr($action->date, 0, 7);
            $actionStat['opened'][$month] += 1;
        }

        /* Build testcase result stat and run case stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->query();
        while($testResult = $stmt->fetch())
        {
            if(!isset($resultStat[$testResult->caseResult])) $resultStat[$testResult->caseResult] = 0;
            $resultStat[$testResult->caseResult] += 1;

            $month = substr($testResult->date, 0, 7);
            $actionStat['run'][$month] += 1;
        }

        /* Build testcase create bug stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('t2.case')->ne('0')
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();
        while($action = $stmt->fetch())
        {
            $month = substr($action->date, 0, 7);
            $actionStat['createBug'][$month] += 1;
        }
        return array('resultStat' => $resultStat, 'actionStat' => $actionStat);
    }

    /**
     * 获取要输出的数据。
     * Get output data.
     *
     * @param  array     $accounts
     * @param  string    $year
     * @access protected
     * @return array
     */
    protected function getOutputData(array $accounts, string $year): array
    {
        /* Get output actions. */
        $outputData = $actionGroup = $objectIdList = array();
        $stmt       = $this->dao->select('id,objectType,objectID,action,extra')->from(TABLE_ACTION)
            ->where('objectType')->in(array_keys($this->config->report->outputData))
            ->andWhere('LEFT(date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->query();
        while($action = $stmt->fetch())
        {
            if($action->objectType == 'release' && $action->action == 'changestatus')
            {
                if($action->extra == 'terminate') $action->action = 'stoped';
                if($action->extra == 'normal')    $action->action = 'activated';
            }
            unset($action->extra);

            if(!isset($this->config->report->outputData[$action->objectType][$action->action])) continue;

            if(!isset($outputData[$action->objectType][$action->action])) $outputData[$action->objectType][$action->action] = 0;
            $objectIdList[$action->objectType][$action->objectID] = $action->objectID;
            $actionGroup[$action->objectType][$action->id]        = $action;
        }
        foreach($actionGroup as $objectType => $actions)
        {
            $deletedIdList = $this->dao->select('id')->from($this->config->objectTables[$objectType])->where('deleted')->eq(1)->andWhere('id')->in($objectIdList[$objectType])->fetchPairs('id', 'id');
            foreach($actions as $action)
            {
                if(!isset($deletedIdList[$action->objectID])) $outputData[$action->objectType][$action->action] += 1;
            }
        }

        /* Get output case data. */
        $outputData['case']['createBug'] = $this->dao->select('count(t2.id) as count')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('t2.case')->ne('0')
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->fetch('count');
        $outputData['case']['run'] = $this->dao->select('count(*) as count')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->fetch('count');
        return $outputData;
    }
}
