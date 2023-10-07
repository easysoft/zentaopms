<?php
/**
 * The control file of job of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        if($this->app->methodName != 'browse')
        {
            if(in_array($this->app->methodName, array('create', 'edit')))
            {
                if($this->session->repoID) $this->loadModel('ci')->setMenu();
            }
            else
            {
                $this->loadModel('ci')->setMenu();
            }
        }
        $this->projectID = isset($_GET['project']) ? $_GET['project'] : 0;
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
    public function browse($repoID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('ci');

        if($repoID)
        {
            $repos = $this->loadModel('repo')->getRepoPairs('devops');
            if(empty($repos)) $this->locate($this->repo->createLink('create'));
            $repoID = $this->repo->saveState($repoID);

            /* Set session. */
            $this->ci->setMenu($repoID);
        }
        else
        {
            $this->session->set('repoID', '');
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $products = $this->loadModel('product')->getPairs();

        $this->app->loadLang('compile');
        $jobList = $this->job->getList($repoID, $orderBy, $pager);
        $this->loadModel('gitlab');
        foreach($jobList as $job)
        {
            $job->canExec = true;

            if($job->engine == 'gitlab')
            {
                $pipeline = json_decode($job->pipeline);
                $branch   = $this->gitlab->apiGetSingleBranch($job->server, $pipeline->project, $pipeline->reference);
                if($branch and isset($branch->can_push) and !$branch->can_push) $job->canExec = false;
                /* query buildSpec */
                if(is_numeric($job->pipeline))  $job->pipeline = $this->loadModel('gitlab')->getProjectName($job->server, $job->pipeline);
                if(isset($pipeline->reference)) $job->pipeline = $this->loadModel('gitlab')->getProjectName($job->server, $pipeline->project);
            }
            elseif($job->engine == 'jenkins')
            {
                if(strpos($job->pipeline, '/job/') !== false) $job->pipeline = trim(str_replace('/job/', '/', $job->pipeline), '/');
            }

            $job->lastExec    = $job->lastExec ? $job->lastExec : '';
            $job->triggerType = $this->job->getTriggerConfig($job);
            $job->buildSpec   = urldecode($job->pipeline) . '@' . $job->jenkinsName;
            $job->engine      = zget($this->lang->job->engineList, $job->engine);
            $job->frame       = zget($this->lang->job->frameList, $job->frame);
            $job->productName = zget($products, $job->product, '');
        }

        $this->view->title   = $this->lang->ci->job . $this->lang->colon . $this->lang->job->browse;
        $this->view->repoID  = $repoID;
        $this->view->jobList = $jobList;
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;

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
            $jobID = $this->job->create();
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
                return $this->send(array('result' => 'fail', 'message' => $errors));
            }

            $this->loadModel('action')->create('job', $jobID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "repoID={$this->post->repo}")));
        }

        $this->loadModel('ci');
        $this->app->loadLang('action');
        $repoList    = $this->loadModel('repo')->getList($this->projectID, false);
        $repoPairs   = array(0 => '');
        $gitlabRepos = array(0 => '');
        $repoTypes   = array();
        $gitlabs     = array();

        foreach($repoList as $repo)
        {
            if(empty($repo->synced)) continue;
            $repoPairs[$repo->id] = "[{$repo->SCM}] " . $repo->name;
            $repoTypes[$repo->id] = $repo->SCM;
            if(strtolower($repo->SCM) == 'gitlab') $gitlabRepos[$repo->id] = "[{$repo->SCM}] " . $repo->name;
        }

        $this->view->title       = $this->lang->ci->job . $this->lang->colon . $this->lang->job->create;
        $this->view->repoPairs   = $repoPairs;
        $this->view->gitlabRepos = $gitlabRepos;
        $this->view->repoTypes   = $repoTypes;
        $this->view->products    = array(0 => '') + $this->loadModel('product')->getProductPairsByProject($this->projectID);

        $this->view->jenkinsServerList   = $this->loadModel('jenkins')->getPairs();
        $this->view->sonarqubeServerList = array('') + $this->loadModel('pipeline')->getPairs('sonarqube');

        $this->display();
    }

    /**
     * Edit a job.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function edit($jobID)
    {
        $job = $this->job->getByID($jobID);
        if($_POST)
        {
            $this->job->update($jobID);
            if(dao::isError())
            {
                $errors = dao::getError();
                if($this->post->engine == 'gitlab' and isset($errors['server']))
                {
                    $errors['gitlabRepo'][] = sprintf($this->lang->error->notempty, $this->lang->job->repo);
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
                return $this->send(array('result' => 'fail', 'message' => $errors));
            }

            $this->loadModel('action')->create('job', $jobID, 'edited');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "repoID={$job->repo}")));
        }

        $this->loadModel('ci');
        $repo = $this->loadModel('repo')->getByID($job->repo);
        $this->view->repo = $this->loadModel('repo')->getByID($job->repo);

        if($repo->SCM == 'Gitlab') $this->view->refList = $this->loadModel('gitlab')->getReferenceOptions($repo->gitService, $repo->project);
        if($repo->SCM == 'Subversion' && $job->triggerType == 'tag')
        {
            $dirs = array();
            $path = empty($repo->prefix) ? '/' : $this->repo->decodePath('');
            $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
            if($tags)
            {
                $dirs['/'] = $path;
                foreach($tags as $dirPath => $dirName) $dirs[$dirPath] = $dirPath;
            }
            $this->view->dirs = $dirs;

            foreach($this->lang->job->triggerTypeList as $type => $name)
            {
                if($type == 'tag') $this->lang->job->triggerTypeList[$type] = $this->lang->job->dirChange;
            }
        }

        $repoList             = $this->repo->getList($this->projectID);
        $repoPairs            = array(0 => '', $repo->id => $repo->name);
        $gitlabRepos          = array(0 => '');
        $repoTypes[$repo->id] = $repo->SCM;
        foreach($repoList as $repo)
        {
            if(empty($repo->synced)) continue;
            $repoPairs[$repo->id] = "[{$repo->SCM}] {$repo->name}";
            $repoTypes[$repo->id] = $repo->SCM;
            if(strtolower($repo->SCM) == 'gitlab') $gitlabRepos[$repo->id] = "[{$repo->SCM}] {$repo->name}";
        }

        $products = $this->repo->getProductsByRepo($job->repo);
        if(!isset($products[$job->product]))
        {
            $jobProduct = $this->loadModel('product')->getByID($job->product);
            if($jobProduct and $jobProduct->deleted == 0) $products += array($job->product => $jobProduct->name);
        }

        if($job->frame == 'sonarqube' && $job->sonarqubeServer && $job->projectKey)
        {
            $this->view->sonarqubeProjectPairs = $this->loadModel('sonarqube')->getProjectPairs($job->sonarqubeServer, $job->projectKey);
        }

        $this->view->title               = $this->lang->ci->job . $this->lang->colon . $this->lang->job->edit;
        $this->view->repoPairs           = $repoPairs;
        $this->view->gitlabRepos         = $gitlabRepos;
        $this->view->repoTypes           = $repoTypes;
        $this->view->repoType            = zget($repoTypes, $job->repo, 'Git');
        $this->view->job                 = $job;
        $this->view->products            = array('') + $products;
        $this->view->jenkinsServerList   = $this->loadModel('jenkins')->getPairs();
        $this->view->sonarqubeServerList = array('') + $this->loadModel('pipeline')->getPairs('sonarqube');
        $this->view->pipelines           = $this->jenkins->getTasks($job->server);

        $this->display();
    }

    /**
     * Delete a job.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function delete($jobID)
    {
        $this->job->delete(TABLE_JOB, $jobID);

        $response['load']   = true;
        $response['result'] = 'success';
        return $this->send($response);
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
        $job = $this->job->getById($jobID);

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

        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->job     = $job;
        $this->view->compile = $compile;
        $this->view->repo    = $this->loadModel('repo')->getByID($job->repo);
        $this->view->jenkins = $this->loadModel('jenkins')->getById($job->server);
        $this->view->product = $this->loadModel('product')->getById($job->product);
        $this->display();
    }

    /**
     * Exec a job.
     *
     * @param  int     $jobID
     * @param  string  $showForm
     * @access public
     * @return void
     */
    public function exec($jobID)
    {
        $job = $this->job->getByID($jobID);
        //if(strtolower($job->engine) == 'gitlab' and (!isset($job->reference) or !$job->reference)) return $this->send(array('result' => 'fail', 'message' => $this->lang->job->setReferenceTips, 'locate' => inlink('edit', "id=$jobID")));

        $compile = $this->job->exec($jobID);
        if(dao::isError())
        {
            $errors = '';
            foreach(dao::getError() as $error)
            {
                if(is_array($error))
                {
                    foreach($error as $val)
                    {
                        $errors .= $val . '\n';
                    }
                }
                else
                {
                    $errors .= $error . '\n';
                }
            }
            return $this->sendError($errors);
        }

        $this->app->loadLang('compile');
        $this->loadModel('action')->create('job', $jobID, 'executed');

        $message = sprintf($this->lang->job->sendExec, zget($this->lang->compile->statusList, $compile->status));
        return $this->sendSuccess(array('message' => $message));
    }

    /**
     * AJAX: Get product by repo.
     *
     * @param  int    $repoID
     * @access public
     * @return string
     */
    public function ajaxGetProductByRepo($repoID)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(empty($repo)) return print(json_encode(array(""=>"")));

        $product = $repo->product;
        if(strpos($product, ','))
        {
            /* Do not use `array_intersect()` here. */
            $productList     = explode(',', $product);
            $matchedProducts = array();
            $productPair     = $this->loadModel('product')->getPairs();
            foreach($productList as $productLeft)
            {
                foreach($productPair as $productRight => $productName)
                {
                    if($productLeft == $productRight) $matchedProducts[$productName] = $productRight;
                }
            }
            return print(json_encode($matchedProducts));
        }

        $productName = $this->loadModel('product')->getByID($repo->product)->name;
        echo json_encode(array($productName => $repo->product));
    }

    /**
     * Ajax get reference list function.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetRefList($repoID)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if($repo->SCM == 'Gitlab') $refList = $this->loadModel('gitlab')->getReferenceOptions($repo->gitService, $repo->project);
        if($repo->SCM != 'Gitlab') $refList = $this->repo->getBranches($repo, true);

        $options = array();
        foreach($refList as $branch => $branchName)
        {
            $options[] = array('text' => $branchName, 'value' => $branch);
        }
        $this->send(array('result' => 'success', 'refList' => $options));
    }

    /**
     * Ajax get repo list.
     *
     * @param  int    $engine
     * @access public
     * @return void
     */
    public function ajaxGetRepoList($engine)
    {
        $repoList  = $this->loadModel('repo')->getList($this->projectID);
        $repoPairs = array(0 => '');
        foreach($repoList as $repo)
        {
            if(empty($repo->synced)) continue;
            if($engine == 'gitlab')
            {
                if(strtolower($repo->SCM) == 'gitlab') $repoPairs[$repo->id] = $repo->name;
            }
            else
            {
                $repoPairs[$repo->id] = "[{$repo->SCM}] {$repo->name}";
            }
        }
        echo html::select('repo', $repoPairs, '', "class='form-control chosen'");
    }

    /**
     * Ajax get an repo type.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetRepoType($repoID)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        $this->send(array('result' => 'success', 'type' => strtolower($repo->SCM)));
    }

    /**
     * Ajax check SonarQube linked by repoID.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxCheckSonarqubeLink($repoID, $jobID = 0)
    {
        $repo = $this->loadModel('job')->getSonarqubeByRepo(array($repoID), $jobID, true);
        if(!empty($repo))
        {
            $message = $repo[$repoID]->deleted ? $this->lang->job->jobIsDeleted : sprintf($this->lang->job->repoExists, $repo[$repoID]->id . '-' . $repo[$repoID]->name);
            $this->send(array('result' => 'fail', 'message' => $message));
        }
        $this->send(array('result' => 'success', 'message' => ''));
    }
}
