<?php
/**
 * The control file of job of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class job extends control
{
    /**
     * Construct 
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse job.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->loadLang('compile');
        $this->view->jobList = $this->job->getList($orderBy, $pager);

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->job->browse;
        $this->view->position[] = $this->lang->ci->job;
        $this->view->position[] = $this->lang->job->browse;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Create a job.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->job->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->job->create;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->ci->job);
        $this->view->position[] = $this->lang->job->create;

        $repoList  = $this->loadModel('repo')->getList();
        $repoPairs = array(0 => '');
        $repoTypes = array();
        foreach($repoList as $repo)
        {
            if(empty($repo->synced)) continue;
            $repoPairs[$repo->id] = $repo->name;
            $repoTypes[$repo->id] = $repo->SCM;
        }
        $this->view->repoPairs  = $repoPairs;
        $this->view->repoTypes  = $repoTypes;
        $this->view->products   = array(0 => '') + $this->loadModel('product')->getPairs();
        $this->view->jkHostList = $this->loadModel('jenkins')->getPairs();

        $this->display();
    }

    /**
     * Edit a job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $job = $this->job->getByID($id);
        if($_POST)
        {
            $this->job->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title       = $this->lang->ci->job . $this->lang->colon . $this->lang->job->edit;
        $this->view->position[]  = html::a(inlink('browse'), $this->lang->ci->job);
        $this->view->position[]  = $this->lang->job->edit;

        $repo = $this->loadModel('repo')->getRepoByID($job->repo);
        $this->view->repo = $this->loadModel('repo')->getRepoByID($job->repo);

        $repoList  = $this->repo->getList();
        $repoPairs = array(0 => '', $repo->id => $repo->name);
        $repoTypes[$repo->id] = $repo->SCM;
        foreach($repoList as $repo)
        {
            if(empty($repo->synced)) continue;
            $repoPairs[$repo->id] = $repo->name;
            $repoTypes[$repo->id] = $repo->SCM;
        }

        $this->view->repoPairs  = $repoPairs;
        $this->view->repoTypes  = $repoTypes;
        $this->view->repoType   = zget($repoTypes, $job->repo, 'Git');
        $this->view->job        = $job;
        $this->view->products   = array(0 => '') + $this->loadModel('product')->getPairs();
        $this->view->jkHostList = $this->loadModel('jenkins')->getPairs();
        $this->view->jkJobs     = $this->jenkins->getTasks($job->jkHost);

        $this->display();
    }

    /**
     * Delete a job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->job->confirmDelete, inlink('delete', "jobID=$id&confirm=yes")));

        $this->job->delete(TABLE_JOB, $id);
        die(js::reload('parent'));
    }

    /**
     * View job and compile.
     * 
     * @param  int    $jobID 
     * @param  int    $compileID 
     * @access public
     * @return void
     */
    public function view($jobID, $compileID = 0)
    {
        $job  = $this->job->getById($jobID);

        $this->loadModel('compile');
        if($compileID)
        {
            $compile = $this->compile->getById($compileID);
        }
        else
        {
            $compile = $this->compile->getLastResult($jobID);
        }

        if($compile and $compile->testtask) 
        {
            $this->app->loadLang('project');
            $taskID = $compile->testtask;
            $task   = $this->loadModel('testtask')->getById($taskID);
            $runs   = $this->testtask->getRuns($taskID, 0, 'id');

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

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->job->browse;
        $this->view->position[] = $this->lang->ci->job;
        $this->view->position[] = $this->lang->job->browse;

        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->job     = $job;
        $this->view->compile = $compile;
        $this->view->repo    = $this->loadModel('repo')->getRepoByID($job->repo);
        $this->view->jenkins = $this->loadModel('jenkins')->getById($job->jkHost);
        $this->view->product = $this->loadModel('product')->getById($job->product);
        $this->display();
    }

    /**
     * Exec a job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function exec($id)
    {
        $status = $this->job->exec($id);
        if(dao::isError()) die(js::error(dao::getError()));

        $this->app->loadLang('compile');
        echo js::alert(sprintf($this->lang->job->sendExec, zget($this->lang->compile->statusList, $status)));
        die(js::reload('parent'));
    }
}
