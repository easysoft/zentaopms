<?php
declare(strict_types=1);

/**
 * The control file of job of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @property    jobZen   $jobZen
 * @property    jobModel $job
 * @link        https://www.zentao.net
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

        if(in_array($this->app->methodName, array('create', 'edit', 'trigger')))
        {
            if($this->session->repoID) $this->loadModel('ci')->setMenu();
        }
        elseif($this->app->methodName != 'browse')
        {
            $this->loadModel('ci')->setMenu();
        }

        $this->projectID = isset($_GET['project']) ? $_GET['project'] : 0;
    }

    /**
     * 流水线列表。
     * Browse job.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('ci');
        $this->app->loadLang('compile');

        if($repoID)
        {
            $this->jobZen->checkRepoEmpty();
            $repoID = $this->loadModel('repo')->saveState($repoID);

            /* Set session. */
            $this->ci->setMenu($repoID);
        }
        else
        {
            $this->session->set('repoID', '');
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $jobList = $this->jobZen->getJobList($repoID, $orderBy, $pager);

        $this->view->title   = $this->lang->ci->job . $this->lang->hyphen . $this->lang->job->browse;
        $this->view->repoID  = $repoID;
        $this->view->jobList = $jobList;
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;

        $this->display();
    }

    /**
     * Create a job.
     *
     * @param  string $repoID
     * @access public
     * @return void
     */
    public function create(int $repoID = 0)
    {
        if($_POST)
        {
            $job = form::data($this->config->job->form->create)
                ->setIF($this->post->frame != 'sonarqube', 'sonarqubeServer', 0)
                ->setIF($this->post->frame != 'sonarqube', 'projectKey', '')
                ->add('createdBy', $this->app->user->account)
                ->get();

            if($job->engine == 'gitlab' && $job->repo)
            {
                $repo    = $this->loadModel('repo')->fetchByID($job->repo);
                $project = $this->loadModel('gitlab')->apiGetSingleProject($repo->serviceHost, (int)$job->repo, false);
                $job->reference = zget($project, 'default_branch', 'master');
            }

            $jobID = $this->job->create($job);
            if(!dao::isError()) $this->loadModel('action')->create('job', $jobID, 'imported');

            return $this->send($this->jobZen->reponseAfterCreateEdit());
        }

        $this->loadModel('ci');

        $this->view->title               = $this->lang->ci->job . $this->lang->hyphen . $this->lang->job->create;
        $this->view->repoList            = $this->loadModel('repo')->getList($this->projectID);
        $this->view->repoID              = $repoID;
        $this->view->repo                = $repoID ? $this->repo->getByID($repoID) : null;
        $this->view->products            = array(0 => '') + $this->loadModel('product')->getProductPairsByProject($this->projectID);
        $this->view->jenkinsServerList   = $this->loadModel('pipeline')->getPairs('jenkins');
        $this->view->sonarqubeServerList = array('' => '') + $this->pipeline->getPairs('sonarqube');

        $this->display();
    }

    /**
     * Edit a job.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function edit(int $jobID)
    {
        $job = $this->job->getByID($jobID);
        if($_POST)
        {
            $newJob = form::data($this->config->job->form->edit)
                ->setIF(!$this->post->repo, 'repo', $job->repo)
                ->setIF($this->post->triggerType && !in_array('commit',   $this->post->triggerType), 'comment', '')
                ->setIF($this->post->triggerType && !in_array('schedule', $this->post->triggerType), 'atDay', '')
                ->setIF($this->post->triggerType && !in_array('schedule', $this->post->triggerType), 'atTime', '')
                ->setIF($this->post->triggerType && !in_array('tag',      $this->post->triggerType), 'lastTag', '')
                ->setIF($this->post->frame != 'sonarqube', 'sonarqubeServer', 0)
                ->setIF($this->post->frame != 'sonarqube', 'projectKey', '')
                ->add('editedBy', $this->app->user->account)
                ->get();

            $this->job->update($jobID, $newJob);
            if(!dao::isError()) $this->loadModel('action')->create('job', $jobID, 'edited');

            return $this->send($this->jobZen->reponseAfterCreateEdit($job->repo));
        }

        $repo = $this->loadModel('repo')->getByID($job->repo);

        if($repo->SCM == 'Gitlab') $this->view->refList = $this->loadModel('gitlab')->getReferenceOptions($repo->gitService, $repo->project);
        if($repo->SCM != 'Gitlab') $this->view->refList = $this->repo->getBranches($repo, true);
        $this->jobZen->getSubversionDir($repo);

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

        $this->view->title               = $this->lang->job->pipeline . $this->lang->hyphen . $this->lang->job->edit;
        $this->view->repoList            = $this->loadModel('repo')->getList($this->projectID);
        $this->view->job                 = $job;
        $this->view->repo                = $repo;
        $this->view->products            = $products;
        $this->view->jenkinsServerList   = $this->loadModel('pipeline')->getPairs('jenkins');
        $this->view->sonarqubeServerList = array('' => '') + $this->pipeline->getPairs('sonarqube');

        $this->display();
    }

    /**
     * Delete a job.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function delete(int $jobID)
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
    public function view(int $jobID, int $compileID = 0)
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

        if($compile && $compile->testtask) $this->jobZen->getCompileData($compile);

        $this->view->title   = $this->lang->ci->job . $this->lang->hyphen . $this->lang->job->browse;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->job     = $job;
        $this->view->compile = $compile;
        $this->view->repo    = $this->loadModel('repo')->getByID($job->repo);
        $this->view->jenkins = $this->loadModel('pipeline')->getById($job->server);
        $this->view->product = $this->loadModel('product')->getById($job->product);
        $this->display();
    }

    /**
     * 执行流水线。
     * Exec a job.
     *
     * @param  int     $jobID
     * @access public
     * @return void
     */
    public function exec(int $jobID)
    {
        $compile = $this->job->exec($jobID);
        if(dao::isError())
        {
            $errors = array();
            foreach(dao::getError() as $error)
            {
                if(is_array($error))
                {
                    foreach($error as $val)
                    {
                        $errors[] = $val;
                    }
                }
                else
                {
                    $errors[] = $error;
                }
            }
            return $this->sendError(implode("\n", $errors));
        }

        $this->app->loadLang('compile');
        $this->loadModel('action')->create('job', $jobID, 'executed');

        $message = sprintf($this->lang->job->sendExec, zget($this->lang->compile->statusList, $compile->status));
        return $this->sendSuccess(array('message' => $message));
    }

    /**
     * ajax方式获取产品根据版本库。
     * AJAX: Get product by repo.
     *
     * @param  int    $repoID
     * @access public
     * @return string
     */
    public function ajaxGetProductByRepo(int $repoID)
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
     * ajax方式获取版本库分支列表。
     * Ajax get reference list function.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetRefList(int $repoID)
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
     * ajax方式获取版本库列表根据引擎。
     * Ajax get repo list.
     *
     * @param  int    $engine
     * @access public
     * @return void
     */
    public function ajaxGetRepoList(int $engine)
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
     * ajax方式获取版本库类型。
     * Ajax get an repo type.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxGetRepoType(int $repoID)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        $this->send(array('result' => 'success', 'type' => strtolower($repo->SCM), 'triggerByTag' => true));
    }

    /**
     * ajax检查该版本库是否已关联sonarqube。
     * Ajax check SonarQube linked by repoID.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function ajaxCheckSonarqubeLink(int $repoID, int $jobID = 0)
    {
        $repo = $this->loadModel('job')->getSonarqubeByRepo(array($repoID), $jobID, true);

        foreach($repo as $linkRepo)
        {
            if($linkRepo->id == $jobID) $this->send(array('result' => 'success', 'message' => ''));
        }

        if(!empty($repo))
        {
            $message = $repo[$repoID]->deleted ? $this->lang->job->jobIsDeleted : sprintf($this->lang->job->repoExists, $repo[$repoID]->id . '-' . $repo[$repoID]->name);
            $this->send(array('result' => 'fail', 'message' => $message));
        }
        $this->send(array('result' => 'success', 'message' => ''));
    }

    /**
     * Ajax方式获取项目流水线信息。
     * Get pipelines by ajax.
     *
     * @param  int    $repoID
     * @param  bool   $isAjax
     * @access public
     * @return void
     */
    public function ajaxGetPipelines(int $repoID, bool $isAjax = true)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!$repo) return print(array());

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $pipelines = $scm->pipelines();

        $options = array();
        foreach($pipelines as $pipeline) $options[] = array('text' => $pipeline->uid, 'value' => $pipeline->uid);

        if($isAjax) return $this->send(array('result' => 'success', 'data' => $options));
        return $options;
    }

    /**
     * AJAX: Import the pipeline from Pipeline Server into ZenTaoPMS
     *
     * @param  string|int $repoID
     * @return void
     */
    public function ajaxImportJobs(string|int $repoID)
    {
        if($this->job->import($repoID)) $this->sendSuccess();
        $this->sendError(dao::isError() ? dao::getError() : 'fail');
    }

    /**
     * 流水线触发器。
     * Pipeline trigger.
     *
     * @param  int    $jobID
     * @access public
     * @return void
     */
    public function trigger(int $jobID)
    {
        $this->lang->job->edit = $this->lang->job->trigger;
        $this->edit($jobID);
    }
}
