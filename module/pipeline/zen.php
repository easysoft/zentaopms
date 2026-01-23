<?php
declare(strict_types=1);
/**
 * The zen file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zenggang <zenggang@easycorp.ltd>
 * @package     pipeline
 * @link        https://www.zentao.net
 * @property    pipelineModel $pipeline
 */
class pipelineZen extends pipeline
{
    /**
     * 检测版本库数量。
     * Check repo empty.
     *
     * @access protected
     * @return void
     */
    protected function checkRepoEmpty(): void
    {
        $repos = $this->loadModel('repo')->getRepoPairs('devops');
        if(empty($repos)) $this->locate($this->repo->createLink('create'));
    }

    /**
     * 获取流水线列表。
     * Get pipeline list.
     *
     * @param  int       $spaceID
     * @param  int       $repoID
     * @param  string    $pipelineQuery
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getPipelineList(int $spaceID, int $repoID, string $pipelineQuery, string $orderBy, object $pager): array
    {
        $products     = $this->loadModel('product')->getPairs('nodeleted', 0, '', 'all');
        $pipelineList = $this->pipeline->getList($spaceID, $repoID, $pipelineQuery, $orderBy, $pager);
        foreach($pipelineList as $pipeline)
        {
            $pipeline->lastExec    = '';
            $pipeline->triggerType = '';
            $pipeline->productName = zget($products, $pipeline->product, '');
        }

        return $pipelineList;
    }

    /**
     * 获取流水线执行数据。
     * Get pipeline compile data.
     *
     * @param  object    $compile
     * @access protected
     * @return void
     */
    protected function getCompileData(object $compile): void
    {
        $this->app->loadLang('project');
        $taskID = $compile->testtask;
        $task   = $this->loadModel('testtask')->getById($taskID);
        $runs   = $this->testtask->getRunsForUnitCases($taskID, 'id');

        $cases = array();
        $runs = $this->loadModel('testcase')->appendData($runs, 'testrun');
        foreach($runs as $run) $cases[$run->case] = $run;

        $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('`case`')->in(array_keys($cases))->andWhere('run')->in(array_keys($runs))->fetchAll('run');
        foreach($results as $result)
        {
            $runs[$result->run]->caseResult = $result->caseResult;
            $runs[$result->run]->xml        = $result->xml;
            $runs[$result->run]->duration   = $result->duration;
        }

        $groupCases = $this->dao->select('*')->from(TABLE_SUITECASE)->where('`case`')->in(array_keys($cases))->orderBy('case')->fetchGroup('suite', 'case');
        $summary    = array();
        if(empty($groupCases)) $groupCases[] = $cases;
        foreach($groupCases as $suiteID => $groupCase)
        {
            $caseCount = 0;
            $failCount = 0;
            $duration  = 0;
            foreach($groupCase as $caseID => $suitecase)
            {
                $case = $cases[$caseID];
                $groupCases[$suiteID][$caseID] = $case;
                $duration += $case->duration;
                $caseCount ++;
                if($case->caseResult == 'fail') $failCount ++;
            }
            $summary[$suiteID] = sprintf($this->lang->testtask->summary, $caseCount, $failCount, $duration);
        }

        $suites = $this->loadModel('testsuite')->getUnitSuites($task->product);

        $this->view->groupCases = $groupCases;
        $this->view->suites     = $suites;
        $this->view->summary    = $summary;
        $this->view->taskID     = $taskID;
    }

    /**
     * 构建搜索表单。
     * Build search form.
     *
     * @param  array $searchConfig
     * @param  string|int $queryID
     * @param  string $actionURL
     * @access protected
     * @return void
     */
    protected function buildSearchForm(array $searchConfig, string|int $queryID, string $actionURL)
    {
        $searchConfig['queryID']   = (int)$queryID;
        $searchConfig['actionURL'] = $actionURL;

        if(isset($searchConfig['params']['repo'])) $searchConfig['params']['repo']['values'] = $this->loadModel('repo')->getRepoPairs('');
        $searchConfig['params']['product']['values'] = $this->loadModel('product')->getPairs('nodeleted', 0, '', 'all');

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 获取搜索条件。
     * Get search condition.
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function getJobSearchQuery(int $queryID): string
    {
        $queryName = 'pipelineQuery';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set('pipelineForm', $query->form);
            }
        }
        if($this->session->$queryName === false) $this->session->set($queryName, ' 1 = 1');
        $pipelineQuery = $this->session->$queryName;
        $pipelineQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $pipelineQuery);

        return $pipelineQuery;
    }

    /**
     * 获取现有的流水线列表。
     * getExistPipelines
     *
     * @access public
     * @return void
     */
    public function getExistPipelines()
    {
        $spaces    = $this->loadModel('space')->getPairs($this->app->user->admin ? '' : $this->app->user->account);
        $pipelines = $this->pipeline->getBySpaces(array_keys($spaces));

        $pipelineItems = array();
        foreach($pipelines as $pipeline)
        {
            if(empty($pipeline->spaceID)) continue;
            if(!isset($pipelineItems[$pipeline->spaceID]))
            {
                $pipelineItems[$pipeline->spaceID]['items'] = array();
                $pipelineItems[$pipeline->spaceID]['text']  = $spaces[$pipeline->spaceID];
            }
            $pipelineItems[$pipeline->spaceID]['items'][] = array('value' => $pipeline->id, 'text' => $pipeline->name);
        }
        return $pipelineItems;
    }
}
