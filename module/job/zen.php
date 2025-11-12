<?php
declare(strict_types=1);
/**
 * The zen file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zenggang <zenggang@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 * @property    jobModel $job
 */
class jobZen extends job
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
     * Get job list.
     *
     * @param  int       $repoID
     * @param  string    $jobQuery
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getJobList(int $repoID, string $jobQuery, string $orderBy, object $pager): array
    {
        $this->loadModel('gitlab');

        $products = $this->loadModel('product')->getPairs('nodeleted', 0, '', 'all');
        $jobList  = $this->job->getList($repoID, $jobQuery, $orderBy, $pager);
        foreach($jobList as $job)
        {
            if($job->engine == 'jenkins')
            {
                if(strpos($job->pipeline, '/job/') === 0) $job->pipeline = trim(substr($job->pipeline, 5), '/');
            }
            else
            {
                $job->branch   = empty($job->pipeline) ? '' : zget(json_decode($job->pipeline), 'reference', '');
                $job->pipeline = $job->repoName;
            }

            $job->lastExec    = $job->lastExec ? $job->lastExec : '';
            $job->triggerType = $this->job->getTriggerConfig($job);
            $job->buildSpec   = !empty($job->pipeline) ? urldecode($job->pipeline) . '@' . $job->jenkinsName : $job->jenkinsName;
            $job->engine      = zget($this->lang->job->engineList, $job->engine);
            $job->frame       = zget($this->lang->job->frameList, $job->frame);
            $job->productName = zget($products, $job->product, '');
        }

        return $jobList;
    }

    /**
     * 返回创建或者编辑的响应。
     * Return reponse after create or edit.
     *
     * @param  int       $repoID
     * @access protected
     * @return array
     */
    protected function reponseAfterCreateEdit(int $repoID = 0): array
    {
        if(dao::isError())
        {
            $errors = dao::getError();
            if($this->post->engine == 'gitlab' and isset($errors['server']))
            {
                if(!isset($errors['repo'])) $errors['repo'][] = sprintf($this->lang->error->notempty, $this->lang->job->repoServer);
                unset($errors['server']);
                unset($errors['pipeline']);
            }
            elseif($this->post->engine == 'jenkins')
            {
                if(isset($errors['server']))
                {
                    $errors['jkServer'] = $errors['server'];
                    unset($errors['server']);
                }
                if(isset($errors['pipeline']))
                {
                    $errors['jkTask'] = $errors['pipeline'];
                    unset($errors['pipeline']);
                }
            }
            return array('result' => 'fail', 'message' => $errors);
        }

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse', 'repoID=' . ($repoID ? $repoID : $this->post->repo)));
    }

    /**
     * 获取svn目录。
     * Get subversion dir.
     *
     * @param  object    $repo
     * @access protected
     * @return void
     */
    protected function getSubversionDir(object $repo): void
    {
        if($repo->SCM == 'Subversion')
        {
            $dirs = array();
            $path = empty($repo->prefix) ? '/' : $this->loadModel('repo')->decodePath('');
            $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
            if($tags)
            {
                $dirs['/'] = $path ? $path : '/';
                foreach($tags as $dirPath => $dirName) $dirs[$dirPath] = $dirPath;
            }
            $this->view->dirs = $dirs;

            foreach($this->lang->job->triggerTypeList as $type => $name)
            {
                if($type == 'tag') $this->lang->job->triggerTypeList[$type] = $this->lang->job->dirChange;
            }
        }
    }

    /**
     * 获取流水线执行数据。
     * Get job compile data.
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
        $queryName = 'jobQuery';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set('jobForm', $query->form);
            }
        }
        if($this->session->$queryName === false) $this->session->set($queryName, ' 1 = 1');
        $jobQuery = $this->session->$queryName;
        $jobQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $jobQuery);

        return $jobQuery;
    }
}
