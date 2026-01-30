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
    public function browse(int $space = 0, int $repoID = 0, string $type = 'space', string $queryID = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->commonAction($space);

        if($repoID)
        {
            $this->pipelineZen->checkRepoEmpty();
            $repoID = $this->loadModel('repo')->saveState($repoID);

            /* Set session. */
            $this->loadModel('ci')->setMenu($repoID);

            unset($this->config->pipeline->search['fields']['repoID']);
            unset($this->config->pipeline->search['params']['repoID']);
        }
        else
        {
            $this->session->set('repoID', '');
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('pipeline', 'browse', "space={$space}&repoID={$repoID}&type=bySearch&queryID=myQueryID&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
        $this->pipelineZen->buildSearchForm($this->config->pipeline->search, $queryID, $actionURL);
        $pipelineQuery = $type == 'bySearch' ? $this->pipelineZen->getPipelineSearchQuery((int)$queryID) : '';

        $pipelineList = $this->pipeline->getList($space, $repoID, $type, $pipelineQuery, $orderBy, $pager);

        $this->view->title        = $this->lang->pipeline->common . $this->lang->hyphen . $this->lang->pipeline->browse;
        $this->view->repoID       = $repoID;
        $this->view->repo         = $this->loadModel('repo')->fetchByID($repoID);
        $this->view->orderBy      = $orderBy;
        $this->view->type         = $type;
        $this->view->queryID      = $queryID;
        $this->view->pager        = $pager;
        $this->view->pipelineList = $pipelineList;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noclosed');

        $this->display();
    }

    /**
     * Create a pipeline.
     *
     * @param  int $spaceID
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function create(int $spaceID = 0, int $repoID = 0)
    {
        if($_POST)
        {
            if($this->post->createType == 'copy' && empty($this->post->existPipeline))
            {
                return $this->sendError(array('existPipeline' => sprintf($this->lang->error->notempty, $this->lang->pipeline->existPipeline)));
            }
            if($repoID) $repo = $this->loadModel('repo')->fetchByID($repoID);
            $pipeline = form::data($this->config->pipeline->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('repoID', $repoID)
                ->add('spaceID', ($repoID && !empty($repo)) ? $repo->space : $spaceID)
                ->add('type', $repoID ? 'repo' : 'space')
                ->add('status', 'draft')
                ->get();

            $pipelineID = $this->pipeline->create($pipeline);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('pipeline', $pipelineID, 'created');

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title          = $this->lang->pipeline->create;
        $this->view->existPipelines = $this->pipelineZen->getExistPipelines($repoID);

        $this->display();
    }

    /**
     * Edit a pipeline.
     *
     * @param  int    $pipelineID
     * @access public
     * @return void
     */
    public function edit(int $id, int $space = 0, int $repoID = 0)
    {
        $this->commonAction($space);
        if($repoID)
        {
            $this->pipelineZen->checkRepoEmpty();
            $repoID = $this->loadModel('repo')->saveState($repoID);

            /* Set session. */
            $this->loadModel('ci')->setMenu($repoID);
        }
        else
        {
            $this->session->set('repoID', '');
        }

        $this->view->title    = $this->lang->pipeline->pipeline . $this->lang->hyphen . $this->lang->pipeline->edit;
        $this->view->pipeline = $this->pipeline->getByID($id);
        $this->view->repoID   = $repoID;
        $this->view->repo     = $this->loadModel('repo')->getByID($repoID);;

        $this->display();
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

    /**
     * AJAX: 获取流水线信息。
     * Get pipeline info.
     *
     * @param  int $pipelineID
     * @access public
     * @return void
     */
    public function ajaxGetPipeline(int $pipelineID)
    {
        $pipeline = $this->pipeline->getByID($pipelineID);
        $this->send(array('result' => 'success', 'data' => $pipeline));
    }

    /**
     * 流水线执行列表。
     * Browse pipeline executions.
     *
     * @param  int    $space
     * @param  int    $repoID
     * @param  string $type
     * @param  int    $pipelineID
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function execution(int $space = 0, int $repoID = 0, string $type = 'space', int $pipelineID = 0, string $queryID = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->commonAction($space);

        if($repoID)
        {
            $this->pipelineZen->checkRepoEmpty();
            $repoID = $this->loadModel('repo')->saveState($repoID);

            /* Set session. */
            $this->loadModel('ci')->setMenu($repoID);

            unset($this->config->pipeline->execution->search['fields']['repoID']);
            unset($this->config->pipeline->execution->search['params']['repoID']);
        }
        else
        {
            $this->session->set('repoID', '');
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID   = $type == 'bySearch' ? $queryID : 0;
        $actionURL = $this->createLink('pipeline', 'execution', "space={$space}&repoID={$repoID}&type=bySearch&pipelineID={$pipelineID}&queryID=myQueryID&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
        $this->pipelineZen->buildSearchForm($this->config->pipeline->execution->search, $queryID, $actionURL);
        $executionQuery = $type == 'bySearch' ? $this->pipelineZen->getPipelineSearchQuery((int)$queryID, 'pipelineexecQuery') : '';

        $executionList = $this->pipeline->getExecutionList($space, $repoID, $type, $pipelineID, $executionQuery, $orderBy, $pager);
        foreach($executionList as $execution) $execution->repo = $execution->repo ? $execution->repo : '';

        $this->view->title         = $this->lang->pipeline->common . $this->lang->hyphen . $this->lang->pipeline->execution;
        $this->view->repoID        = $repoID;
        $this->view->repo          = $this->loadModel('repo')->fetchByID($repoID);
        $this->view->orderBy       = $orderBy;
        $this->view->type          = $type;
        $this->view->queryID       = $queryID;
        $this->view->pager         = $pager;
        $this->view->executionList = $executionList;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->repos         = $this->repo->getRepoPairs('', 0, false);

        $this->display();
    }

    /**
     * 获取流水线信息。
     * Get pipeline info.
     *
     * @param  int $pipelineID
     * @access public
     * @return void
     */
    public function ajaxGetFlowInfo(int $pipelineID)
    {
        $pipeline = $this->pipeline->getById($pipelineID);
        if(!empty($pipeline) && !empty($pipeline->repoID) && $pipeline->type == 'repo')
        {
            $repo = $this->loadModel('repo')->fetchByID($pipeline->repoID);

            $pipeline->repo     = $repo;
            $pipeline->repoName = $repo->name;
        }
        echo json_encode($pipeline);
    }

    /**
     * 修改流水线信息。
     * Edit pipeline info.
     *
     * @param  int $pipelineID
     * @access public
     * @return void
     */
    public function ajaxPostFlowInfo(int $pipelineID)
    {
        $formData = form::data($this->config->pipeline->form->edit)->get();
        $this->pipeline->update($pipelineID, $formData);

        if(dao::isError()) return $this->sendError(dao::getError());

        $this->sendSuccess(array('load' => true));
    }

    /**
     * 获取步骤组。
     * Get step groups.
     *
     * @access public
     * @return void
     */
    public function ajaxGetStepGroups()
    {
        $stepGroups = $this->pipeline->getStepGroups();
        if(dao::isError())
        {
            $error = dao::getError();
            return $this->sendError(zget($error, 'apiMessage', 'api error'));
        }

        $this->send(array('result' => 'success', 'data' => $stepGroups));
    }

    /**
     * 获取步骤定义。
     * Get step schema.
     *
     * @param  string $stepName
     * @access public
     * @return void
     */
    public function ajaxGetStepSchema(string $stepName)
    {
        $stepSchema = $this->pipeline->getStepSchema($stepName);
        if(dao::isError())
        {
            $error = dao::getError();
            return $this->sendError(zget($error, 'apiMessage', 'api error'));
        }
        $this->send(array('result' => 'success', 'data' => $stepSchema));
    }

    public function ajaxPostVars(int $pipelineID)
    {
        $this->send(array('result' => 'success', 'data' => $this->config->pipeline->vars));
    }

    public function ajaxEditPipeline(int $pipeline, string $type = 'draft')
    {
    }
}
