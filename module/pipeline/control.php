<?php
declare(strict_types=1);

/**
 * The control file of pipeline of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     pipeline
 * @version     $Id$
 * @property    pipelineZen   $pipelineZen
 * @property    pipelineModel $pipeline
 * @link        https://www.zentao.net
 */
class pipeline extends control
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
     * 设置页面公共数据。
     * Common actions.
     *
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function commonAction(int $spaceID = 0)
    {
        $this->loadModel('space')->setMenu($spaceID);

        $this->view->spaceID = $spaceID;
        $this->view->inSpace = !empty($spaceID);
    }


    /**
     * 流水线列表。
     * Browse pipeline.
     *
     * @param  int    $space
     * @param  int    $repoID
     * @param  string $type
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $space = 0, int $repoID = 0, string $type = '', string $queryID = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->commonAction($space);
        $this->loadModel('ci');
        $this->app->loadLang('compile');

        if($repoID)
        {
            $this->pipelineZen->checkRepoEmpty();
            $repoID = $this->loadModel('repo')->saveState($repoID);

            /* Set session. */
            $this->ci->setMenu($repoID);

            unset($this->config->pipeline->search['fields']['repo']);
            unset($this->config->pipeline->search['params']['repo']);
        }
        else
        {
            $this->session->set('repoID', '');
        }
        if($space) $this->loadModel('space')->setMenu($space);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('pipeline', 'browse', "space={$space}&repoID={$repoID}&type=bySearch&queryID=myQueryID&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
        $this->pipelineZen->buildSearchForm($this->config->pipeline->search, $queryID, $actionURL);
        $pipelineQuery = $type == 'bySearch' ? $this->pipelineZen->getJobSearchQuery((int)$queryID) : '';

        $pipelineList = $this->pipelineZen->getPipelineList($space, $repoID, $pipelineQuery, $orderBy, $pager);

        $this->view->title   = $this->lang->pipeline->common . $this->lang->hyphen . $this->lang->pipeline->browse;
        $this->view->repoID  = $repoID;
        $this->view->repo    = $this->loadModel('repo')->fetchByID($repoID);
        $this->view->orderBy = $orderBy;
        $this->view->type    = $type;
        $this->view->queryID = $queryID;
        $this->view->pager   = $pager;

        $this->view->pipelineList = $pipelineList;
        $this->view->hasJobServer = true;
        $this->view->spaces       = $this->loadModel('space')->getPairs();

        $this->display();
    }

    /**
     * Create a pipeline.
     *
     * @param  string $repoID
     * @access public
     * @return void
     */
    public function create(int $repoID = 0)
    {
        if($_POST)
        {
            $pipeline = form::data($this->config->pipeline->form->create)
                ->setIF($this->post->frame != 'sonarqube', 'sonarqubeServer', 0)
                ->setIF($this->post->frame != 'sonarqube', 'projectKey', '')
                ->add('createdBy', $this->app->user->account)
                ->get();

            if($pipeline->engine == 'gitlab' && $pipeline->repo)
            {
                $repo    = $this->loadModel('repo')->fetchByID($pipeline->repo);
                $project = $this->loadModel('gitlab')->apiGetSingleProject((int)$repo->serviceHost, (int)$pipeline->repo, false);
                $pipeline->reference = zget($project, 'default_branch', 'master');
            }

            $pipelineID = $this->pipeline->create($pipeline);
            if(!dao::isError()) $this->loadModel('action')->create('pipeline', $pipelineID, 'imported');

            return $this->send($this->pipelineZen->reponseAfterCreateEdit());
        }

        $this->loadModel('ci');
        $spaceID = $this->session->devopsSpace ? $this->session->devopsSpace : 0;

        $this->view->title               = $this->lang->ci->pipeline . $this->lang->hyphen . $this->lang->pipeline->create;
        $this->view->repoList            = $this->loadModel('repo')->getList($this->projectID);
        $this->view->repoID              = $repoID;
        $this->view->repo                = $repoID ? $this->repo->getByID($repoID) : null;
        $this->view->products            = array(0 => '') + $this->loadModel('product')->getProductPairsByProject($this->projectID);
        $this->view->jenkinsServerList   = $this->loadModel('pipeline')->getPairs('jenkins');
        $this->view->sonarqubeServerList = array('' => '') + $this->pipeline->getPairs('sonarqube');
        $this->view->inSpace             = !empty($spaceID);
        $this->view->spaceID             = $spaceID;

        $this->display();
    }

    /**
     * Edit a pipeline.
     *
     * @param  int    $pipelineID
     * @access public
     * @return void
     */
    public function edit(int $pipelineID)
    {
        $pipeline = $this->pipeline->getByID($pipelineID);
        if($_POST)
        {
            $newJob = form::data($this->config->pipeline->form->edit)
                ->setIF(!$this->post->repo, 'repo', $pipeline->repo)
                ->setIF($this->post->triggerType && !in_array('commit',   $this->post->triggerType), 'comment', '')
                ->setIF($this->post->triggerType && !in_array('schedule', $this->post->triggerType), 'atDay', '')
                ->setIF($this->post->triggerType && !in_array('schedule', $this->post->triggerType), 'atTime', '')
                ->setIF($this->post->triggerType && !in_array('tag',      $this->post->triggerType), 'lastTag', '')
                ->setIF($this->post->frame != 'sonarqube', 'sonarqubeServer', 0)
                ->setIF($this->post->frame != 'sonarqube', 'projectKey', '')
                ->add('editedBy', $this->app->user->account)
                ->get();

            $this->pipeline->update($pipelineID, $newJob);
            if(!dao::isError()) $this->loadModel('action')->create('pipeline', $pipelineID, 'edited');

            return $this->send($this->pipelineZen->reponseAfterCreateEdit($pipeline->repo));
        }

        $repo = $this->loadModel('repo')->getByID($pipeline->repo);

        if($repo->SCM == 'Gitlab') $this->view->refList = $this->loadModel('gitlab')->getReferenceOptions($repo->gitService, (int)$repo->serviceProject);
        if($repo->SCM != 'Gitlab') $this->view->refList = $this->repo->getBranches($repo, true);
        $this->pipelineZen->getSubversionDir($repo);

        $products = $this->repo->getProductsByRepo($pipeline->repo);
        if(!isset($products[$pipeline->product]))
        {
            $pipelineProduct = $this->loadModel('product')->getByID($pipeline->product);
            if($pipelineProduct and $pipelineProduct->deleted == 0) $products += array($pipeline->product => $pipelineProduct->name);
        }

        if($pipeline->frame == 'sonarqube' && $pipeline->sonarqubeServer && $pipeline->projectKey)
        {
            $this->view->sonarqubeProjectPairs = $this->loadModel('sonarqube')->getProjectPairs($pipeline->sonarqubeServer, $pipeline->projectKey);
        }

        $this->view->title               = $this->lang->pipeline->pipeline . $this->lang->hyphen . $this->lang->pipeline->edit;
        $this->view->repoList            = $this->loadModel('repo')->getList($this->projectID);
        $this->view->pipeline                 = $pipeline;
        $this->view->repo                = $repo;
        $this->view->products            = $products;
        $this->view->jenkinsServerList   = $this->loadModel('pipeline')->getPairs('jenkins');
        $this->view->sonarqubeServerList = array('' => '') + $this->pipeline->getPairs('sonarqube');

        $this->display();
    }

    /**
     * Delete a pipeline.
     *
     * @param  int    $pipelineID
     * @access public
     * @return void
     */
    public function delete(int $pipelineID)
    {
        $this->pipeline->delete(TABLE_JOB, $pipelineID);

        $response['load']   = true;
        $response['result'] = 'success';
        return $this->send($response);
    }

    /**
     * View pipeline and compile.
     *
     * @param  int    $pipelineID
     * @param  int    $compileID
     * @access public
     * @return void
     */
    public function view(int $pipelineID, int $compileID = 0)
    {
        $pipeline = $this->pipeline->getById($pipelineID);

        $this->loadModel('compile');
        if($compileID)
        {
            $compile = $this->compile->getById($compileID);
        }
        else
        {
            $compile = $this->compile->getLastResult($pipelineID);
        }

        if($compile && $compile->testtask) $this->pipelineZen->getCompileData($compile);

        $this->view->title   = $this->lang->ci->pipeline . $this->lang->hyphen . $this->lang->pipeline->browse;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->pipeline     = $pipeline;
        $this->view->compile = $compile;
        $this->view->repo    = $this->loadModel('repo')->getByID($pipeline->repo);
        $this->view->jenkins = $this->loadModel('pipeline')->getById($pipeline->server);
        $this->view->product = $this->loadModel('product')->getById($pipeline->product);
        $this->display();
    }

    /**
     * 执行流水线。
     * Exec a pipeline.
     *
     * @param  int     $pipelineID
     * @access public
     * @return void
     */
    public function exec(int $pipelineID)
    {
        $compile = $this->pipeline->exec($pipelineID);
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
        $this->loadModel('action')->create('pipeline', $pipelineID, 'executed');

        $message = sprintf($this->lang->pipeline->sendExec, zget($this->lang->compile->statusList, $compile->status));
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
        if($repo->SCM == 'Gitlab') $refList = $this->loadModel('gitlab')->getReferenceOptions($repo->gitService, (int)$repo->serviceProject);
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
    public function ajaxCheckSonarqubeLink(int $repoID, int $pipelineID = 0)
    {
        $repo = $this->loadModel('pipeline')->getSonarqubeByRepo(array($repoID), $pipelineID, true);

        foreach($repo as $linkRepo)
        {
            if($linkRepo->id == $pipelineID) $this->send(array('result' => 'success', 'message' => ''));
        }

        if(!empty($repo))
        {
            $message = $repo[$repoID]->deleted ? $this->lang->pipeline->pipelineIsDeleted : sprintf($this->lang->pipeline->repoExists, $repo[$repoID]->id . '-' . $repo[$repoID]->name);
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
        if($this->pipeline->import($repoID)) $this->sendSuccess();
        $this->sendError(dao::isError() ? dao::getError() : 'fail');
    }

    /**
     * 流水线触发器。
     * Pipeline trigger.
     *
     * @param  int    $pipelineID
     * @access public
     * @return void
     */
    public function trigger(int $pipelineID)
    {
        $this->lang->pipeline->edit = $this->lang->pipeline->trigger;
        $this->edit($pipelineID);
    }
}
