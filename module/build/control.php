<?php
/**
 * The control file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: control.php 4992 2013-07-03 07:21:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class build extends control
{
    /**
     * Common actions.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function commonActions($projectID = 0)
    {
        $hidden  = '';
        if($projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            if(!$project->hasProduct) $hidden = 'hide';

            $this->view->multipleProject = $project->multiple;
        }

        $this->view->hidden = $hidden;
    }

    /**
     * Create a build.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($executionID = 0, $productID = 0, $projectID = 0)
    {
        /* Load these models. */
        $this->loadModel('execution');
        $this->loadModel('user');

        if(!empty($_POST))
        {
            if(empty($executionID) && $this->app->tab == 'execution' && (!defined('RUN_MODE') || RUN_MODE != 'api')) dao::$errors['execution'] = $this->lang->build->emptyExecution;
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(defined('TUTORIAL')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true)); // Fix bug #21095.

            $buildID = $this->build->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('build', $buildID, 'opened');

            $message = $this->executeHooks($buildID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $buildID));
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.loadExecutionBuilds($executionID, $buildID)")); // Code for task #5126.
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('build', 'view', "buildID=$buildID") . "#app={$this->app->tab}"));
        }

        $_GET['zin'] = '0';

        $status = empty($this->config->CRProduct) ? 'noclosed' : '';
        $this->loadModel('product');
        $this->loadModel('project');
        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->project->setMenu($projectID);
            $this->session->set('project', $projectID);
        }
        elseif($this->app->tab == 'execution')
        {
            $execution     = $this->execution->getByID($executionID);
            $projectID     = $execution->project;
            $this->execution->setMenu($executionID);
            $this->session->set('project', $execution->project);
        }
        elseif($this->app->tab == 'qa')
        {
            $execution     = $this->execution->getByID($executionID);
            $projectID     = $execution ? $execution->project : 0;
        }

        $executions    = $this->execution->getPairs($projectID, 'all', 'stagefilter|leaf|order_asc');
        $executionID   = empty($executionID) ? key($executions) : $executionID;
        $productGroups = $executionID ? $this->product->getProducts($executionID, $status) : array();
        $branchGroups  = $executionID ? $this->project->getBranchesByProject($executionID) : array();

        $this->commonActions($projectID);

        $productID     = $productID ? $productID : key($productGroups);
        $branchPairs   = $this->loadModel('branch')->getPairs($productID, 'active');
        $branches      = array();
        $products      = array();

        /* Set branches and products. */
        if(isset($productGroups[$productID]) and $productGroups[$productID]->type != 'normal' and isset($branchGroups[$productID]))
        {
            foreach($branchGroups[$productID] as $branchID => $branch)
            {
                if(isset($branchPairs[$branchID])) $branches[$branchID] = $branchPairs[$branchID];
            }
        }

        foreach($productGroups as $product) $products[$product->id] = $product->name;

        if(!$this->view->hidden && $products)
        {
            $this->loadModel('artifactrepo');
            $productArtifactRepos = array();
            foreach($products as $ID => $productName)
            {
                $productArtifactRepos[$ID] = $this->artifactrepo->getReposByProduct($ID);
            }
            $this->view->productArtifactRepos = $productArtifactRepos;
        }

        $this->view->title      = $this->lang->build->create;
        $this->view->position[] = $this->lang->build->create;

        $this->view->product       = isset($productGroups[$productID]) ? $productGroups[$productID] : '';
        $this->view->branches      = $branches;
        $this->view->executionID   = $executionID;
        $this->view->products      = $products;
        $this->view->projectID     = $projectID;
        $this->view->executions    = $executions;
        $this->view->lastBuild     = $this->build->getLast($executionID, $projectID);
        $this->view->productGroups = $productGroups;
        $this->view->users         = $this->user->getPairs('nodeleted|noclosed');
        $this->display();
    }

    /**
     * Edit a build.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function edit($buildID)
    {
        if(!empty($_POST))
        {
            $changes = $this->build->update($buildID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $files = $this->loadModel('file')->saveUpload('build', $buildID);
            $change[$buildID] = $changes;
            $this->unlinkOldBranch($change);

            if($changes or $files)
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('build', $buildID, 'Edited', $fileAction);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($buildID);
            if($message) $this->lang->saveSuccess = $message;

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('build', 'view', "buildID=$buildID") . "#app={$this->app->tab}"));
        }

        $this->loadModel('execution');
        $this->loadModel('product');
        $build = $this->build->getById((int)$buildID);
        $oldBranch = array($buildID => $build->branch);

        /* Set menu. */
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($build->project);
        if($this->app->tab == 'execution') $this->execution->setMenu($build->execution);

        $builds = array();
        $status = empty($this->config->CRProduct) ? 'noclosed' : '';
        if($build->execution)
        {
            $productGroups = $this->product->getProducts($build->execution, $status);
            $branches      = $this->loadModel('branch')->getList($build->product, $build->execution, 'all');
        }
        else
        {
            $productGroups = $this->product->getProducts($build->project, $status);
            $branches      = $this->loadModel('branch')->getList($build->product, $build->project, 'all');
            $builds        = $this->build->getBuildPairs($build->product, 'all', 'noempty,notrunk,singled,separate', $build->project, 'project', $build->builds, false);
        }

        $executions    = $this->product->getExecutionPairsByProduct($build->product, $build->branch, 'id_desc', $this->session->project, 'stagefilter');
        $executionType = $build->execution ? $this->execution->getByID($build->execution) : '';
        if($build->execution and !isset($executions[$build->execution])) $executions[$build->execution] = $this->loadModel('execution')->getById($build->execution)->name;

        /* Get stories and bugs. */
        $orderBy = 'status_asc, stage_asc, id_desc';

        if(!isset($productGroups[$build->product]))
        {
            $product = $this->product->getById($build->product);
            $product->branch = $build->branch;
            $productGroups[$build->product] = $product;
        }

        /* Display status of branch. */
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }
        foreach(explode(',', $build->branch) as $buildBranch)
        {
            if(!isset($branchTagOption[$buildBranch])) $branchTagOption[$buildBranch] = $this->branch->getById($buildBranch, 0, 'name');
        }

        foreach($productGroups as $product) $products[$product->id] = $product->name;

        $this->commonActions($build->project);

        $this->view->title           = $build->name . $this->lang->colon . $this->lang->build->edit;
        $this->view->position[]      = $this->lang->build->edit;
        $this->view->product         = isset($productGroups[$build->product]) ? $productGroups[$build->product] : '';
        $this->view->branchTagOption = $branchTagOption;
        $this->view->orderBy         = $orderBy;
        $this->view->oldBranch       = $oldBranch;
        $this->view->executions      = $executions;
        $this->view->executionType   = (!empty($executionType) and $executionType->type == 'stage') ? 1 : 0;
        $this->view->productGroups   = $productGroups;
        $this->view->products        = $products;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter', $build->builder);
        $this->view->build           = $build;
        $this->view->builds          = $builds;
        $this->view->testtaskID      = $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($build->id)->andWhere('deleted')->eq(0)->fetch('id');
        $this->display();
    }

    /**
     * View a build.
     *
     * @param  int    $buildID
     * @param  string $type
     * @param  string $link
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view($buildID, $type = 'story', $link = 'false', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $buildID = (int)$buildID;
        $build   = $this->build->getByID($buildID, true);
        if(!$build)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'all')));
        }
        $this->session->project = $build->project;

        $this->loadModel('story');
        $this->loadModel('bug');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        /* Get product and bugs. */
        $product = $this->loadModel('product')->getById($build->product);
        if($product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $bugPager = new pager($type == 'bug' ? $recTotal : 0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('id')->in($build->allBugs)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'bug')->orderBy($sort)->fi()
            ->page($bugPager)
            ->fetchAll();

        /* Get stories and stages. */
        $storyPager = new pager($type == 'story' ? $recTotal : 0, $recPerPage, $type == 'story' ? $pageID : 1);
        $stories    = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('id')->in($build->allStories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'story')->orderBy($sort)->fi()
            ->page($storyPager)
            ->fetchAll('id');

        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in(array_keys($stories))->andWhere('branch')->eq($build->branch)->fetchPairs('story', 'stage');
        foreach($stages as $storyID => $stage) $stories[$storyID]->stage = $stage;

        /* Set menu. */
        $objectType = 'execution';
        $objectID   = $build->execution;
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($build->project);
            $objectType = 'project';
            $objectID   = $build->project;
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($build->execution);
            $objectType = 'execution';
            $objectID   = $build->execution;
        }

        $executions    = $this->loadModel('execution')->getPairs($this->session->project, 'all', 'empty');
        $executionType = $build->execution ? $this->execution->getByID($build->execution) : '';

        $this->commonActions($build->project);

        $this->view->title      = "BUILD #$build->id $build->name" . (isset($executions[$build->execution]) ? " - " . $executions[$build->execution] : '');
        $this->view->stories    = $stories;
        $this->view->storyPager = $storyPager;

        $generatedBugPager = new pager($type == 'generatedBug' ? $recTotal : 0, $recPerPage, $type == 'generatedBug' ? $pageID : 1);
        $this->view->generatedBugs     = $this->bug->getExecutionBugs($build->execution, $build->product, 'all', "$build->id,{$build->builds}", $type, $param, $type == 'generatedBug' ? $sort : 'status_desc,id_desc', '', $generatedBugPager);
        $this->view->generatedBugPager = $generatedBugPager;

        $this->executeHooks($buildID);
        $branchName = '';
        $this->loadModel('branch');
        if($build->productType != 'normal')
        {
            foreach(explode(',', $build->branch) as $buildBranch)
            {
                $branchName .= $buildBranch == 0 ? $this->lang->branch->main : $this->branch->getById($buildBranch);
                $branchName .= ',';
            }
            $branchName = trim($branchName, ',');
        }

        /* Assign. */
        $this->view->canBeChanged = common::canBeChanged('build', $build); // Determines whether an object is editable.
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->build         = $build;
        $this->view->buildPairs    = $this->build->getBuildPairs(0, 'all', 'noempty,notrunk', $objectID, $objectType);
        $this->view->builds        = $this->build->getByList(array_keys($this->view->buildPairs));
        $this->view->executions    = $executions;
        $this->view->actions       = $this->loadModel('action')->getList('build', $buildID);
        $this->view->link          = $link;
        $this->view->param         = $param;
        $this->view->orderBy       = $orderBy;
        $this->view->bugs          = $bugs;
        $this->view->type          = $type;
        $this->view->bugPager      = $bugPager;
        $this->view->branchName    = empty($branchName) ? $this->lang->branch->main : $branchName;
        $this->view->childBuilds   = empty($build->builds) ? array() : $this->dao->select('id,name,bugs,stories')->from(TABLE_BUILD)->where('id')->in($build->builds)->fetchAll();
        $this->view->executionType = (!empty($executionType) and $executionType->type == 'stage') ? 1 : 0;

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->storyPager);
            unset($this->view->generatedBugPager);
            unset($this->view->bugPager);
        }

        $this->display();
    }

    /**
     * Delete a build.
     *
     * @param  int    $buildID
     * @param  string $confirm  yes|noe
     * @access public
     * @return void
     */
    public function delete($buildID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->build->confirmDelete, $this->createLink('build', 'delete', "buildID=$buildID&confirm=yes")));
        }
        else
        {
            $build = $this->build->getById($buildID);
            $this->build->delete(TABLE_BUILD, $buildID);

            $message = $this->executeHooks($buildID);
            if($message) $response['message'] = $message;

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                return $this->send($response);
            }

            $link = $this->app->tab == 'project' ? $this->createLink('projectbuild', 'browse',  "projectID=$build->project") : $this->createLink('execution', 'build', "executionID=$build->execution");
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::locate($link, 'parent'));
        }
    }

    /**
     * AJAX: get builds of a product in html select.
     *
     * @param  int        $productID
     * @param  string     $varName      the name of the select object to create
     * @param  string     $build        build to selected
     * @param  string|int $branch
     * @param  int        $index        the index of batch create bug.
     * @param  string     $type         get all builds or some builds belong to normal releases and executions are not done.
     * @param  string     $extra
     * @access public
     * @return string
     */
    public function ajaxGetProductBuilds($productID, $varName, $build = '', $branch = 'all', $index = 0, $type = 'normal', $extra = '')
    {
        $this->app->loadLang('bug');
        $loadAllBtn = '<span class="input-group-btn"> <button type="button" class="btn" onclick="loadAllBuilds(this)" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;">' . $this->lang->bug->allBuilds . '</button></span>';
        $isJsonView = $this->app->getViewType() == 'json';
        if($varName == 'openedBuild' )
        {
            $params = ($type == 'all') ? 'noempty,withbranch,noreleased' : 'noempty,noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs($productID, $branch, $params, 0, 'project', $build);
            if($isJsonView) return print(json_encode($builds));
            return print(html::select($varName . '[]', $builds, $build, 'size=4 class=form-control multiple'));
        }
        if($varName == 'openedBuilds' )
        {
            $builds = $this->build->getBuildPairs($productID, $branch, 'noempty,noreleased', 0, 'project', $build);
            if($isJsonView) return print(json_encode($builds));
            return print(html::select($varName . "[$index][]", $builds, $build, 'size=4 class=form-control multiple'));
        }
        if($varName == 'resolvedBuild')
        {
            $params = ($type == 'all') ? 'withbranch,noreleased' : 'noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs($productID, $branch, $params, 0, 'project', $build);
            if($isJsonView) return print(json_encode($builds));
            return print(html::select($varName, $builds, $build, "class='form-control'") . $loadAllBtn);
        }

        $builds = $this->build->getBuildPairs($productID, $branch, $type, 0, 'project', $build, false);
        if(strpos($extra, 'multiple') !== false) $varName .= '[]';
        if($isJsonView) return print(json_encode($builds));
        return print(html::select($varName, $builds, $build, "class='form-control chosen' $extra"));
    }

    /**
     * AJAX: get builds of a project in html select.
     *
     * @param  int        $projectID
     * @param  string     $varName      the name of the select object to create
     * @param  string     $build        build to selected
     * @param  string|int $branch
     * @param  int        $index        the index of batch create bug.
     * @param  bool       $needCreate   if need to append the link of create build
     * @param  string     $type         get all builds or some builds belong to normal releases and executions are not done.
     * @param  string     $extra
     * @access public
     * @return string
     */
    public function ajaxGetProjectBuilds($projectID, $productID, $varName, $build = '', $branch = 'all', $index = 0, $needCreate = false, $type = 'normal', $extra = '')
    {
        $isJsonView = $this->app->getViewType() == 'json';
        if($varName == 'openedBuild')
        {
            if(empty($projectID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $index, $type);

            $params = ($type == 'all') ? 'noempty,withbranch,noreleased' : 'noempty,noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs($productID, $branch, $params, $projectID, 'project', $build);
            if($isJsonView)  return print(json_encode($builds));
            return print(html::select($varName . '[]', $builds , '', 'size=4 class=form-control multiple'));
        }
        if($varName == 'resolvedBuild')
        {
            if(empty($projectID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $index, $type);

            $params = ($type == 'all') ? 'withbranch,noreleased' : 'noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs($productID, $branch, $params, $projectID, 'project', $build);
            if($isJsonView)  return print(json_encode($builds));
            return print(html::select($varName, $builds, $build, "class='form-control'"));
        }

        if(empty($projectID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $index, $type, $extra);
        $builds = $this->build->getBuildPairs($productID, $branch, $type, $projectID, 'project', $build, false);
        if(strpos($extra, 'multiple') !== false) $varName .= '[]';
        if($isJsonView) return print(json_encode($builds));
        return print(html::select($varName, $builds, $build, "class='form-control chosen' $extra"));
    }

    /**
     * AJAX: get builds of an execution in html select.
     *
     * @param  int        $executionID
     * @param  int        $productID
     * @param  string     $varName      the name of the select object to create
     * @param  string     $build        build to selected
     * @param  string|int $branch
     * @param  int        $index        the index of batch create bug.
     * @param  bool       $needCreate   if need to append the link of create build
     * @param  string     $type         get all builds or some builds belong to normal releases and executions are not done.
     * @param  int        $number
     * @access public
     * @return string
     */
    public function ajaxGetExecutionBuilds($executionID, $productID, $varName, $build = '', $branch = 'all', $index = 0, $needCreate = false, $type = 'normal', $number = '')
    {
        $this->app->loadLang('bug');
        $isJsonView = $this->app->getViewType() == 'json';
        $loadAllBtn = '<span class="input-group-btn"> <button type="button" class="btn" onclick="loadAllBuilds(this)" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;">' . $this->lang->bug->allBuilds . '</button></span>';
        if($varName == 'openedBuild')
        {
            if(empty($executionID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $index, $type);

            $params = ($type == 'all') ? 'noempty,noreleased' : 'noempty,noterminate,nodone,noreleased';
            $builds = $this->build->getBuildPairs($productID, $branch, $params, $executionID, 'execution', $build);
            if($isJsonView) return print(json_encode($builds));

            $varName = $number === '' ? $varName : $varName . "[$number]";
            return print(html::select($varName . '[]', $builds , '', 'size=4 class=form-control multiple') . $loadAllBtn);
        }
        if($varName == 'openedBuilds')
        {
            if(empty($executionID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $index, $type);

            $builds = $this->build->getBuildPairs($productID, $branch, 'noempty,noreleased', $executionID, 'execution', $build);
            if($isJsonView) return print(json_encode($builds));
            return print(html::select($varName . "[$index][]", $builds , $build, 'size=4 class=form-control multiple'));
        }
        if($varName == 'resolvedBuild')
        {
            if(empty($executionID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $index, $type);

            $params = ($type == 'all') ? ',noreleased' : 'noterminate,nodone,noreleased';
            $builds = $this->build->getBuildPairs($productID, $branch, $params, $executionID, 'execution', $build);
            if($isJsonView) return print(json_encode($builds));
            return print(html::select($varName, $builds, $build, "class='form-control'"));
        }
        if($varName == 'testTaskBuild')
        {
            $builds = $this->build->getBuildPairs($productID, $branch, 'noempty,notrunk', $executionID, 'execution', '', false);
            if($isJsonView) return print(json_encode($builds));

            if(empty($builds))
            {
                $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');

                $html  = html::a($this->createLink('build', 'create', "executionID=$executionID&productID=$productID&projectID=$projectID", '', $onlybody = true), $this->lang->build->create, '', "data-toggle='modal' data-type='iframe'");
                $html .= '&nbsp; ';
                $html .= html::a("javascript:loadExecutionBuilds($executionID)", $this->lang->refresh);
                return print($html);
            }
            return print(html::select('build', array('') + $builds, $build, "class='form-control'"));
        }
        if($varName == 'dropdownList')
        {
            $builds = $this->build->getBuildPairs($productID, $branch, 'noempty,notrunk', $executionID, 'execution');
            if($isJsonView) return print(json_encode($builds));

            $list  = "<div class='list-group'>";
            foreach($builds as $buildID => $buildName) $list .= html::a(inlink('view', "buildID={$buildID}"), $buildName);
            $list .= '</div>';

            return print($list);
        }
    }

    /**
     * Ajax get last build.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function ajaxGetLastBuild($projectID, $executionID)
    {
        $lastBuild = $this->build->getLast($executionID, $projectID);
        if($lastBuild)
        {
            echo "<div class='help-block'> &nbsp; " . $this->lang->build->last . ": <a class='code label label-badge label-light' id='lastBuildBtn'>" . $lastBuild->name . "</a></div>";
        }
        else
        {
            echo '';
        }
    }

    /**
     * Link stories.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($buildID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->build->linkStory($buildID);
            return print(js::locate(inlink('view', "buildID=$buildID&type=story"), 'parent'));
        }

        $this->session->set('storyList', inlink('view', "buildID=$buildID&type=story&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), $this->app->tab);

        $build   = $this->build->getById($buildID);
        $product = $this->loadModel('product')->getById($build->product);

        if($build->execution) $this->loadModel('execution')->setMenu($build->execution);
        $this->loadModel('story');
        $this->loadModel('tree');
        $this->loadModel('product');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        unset($this->config->product->search['fields']['product']);
        unset($this->config->product->search['fields']['project']);
        $this->config->product->search['actionURL'] = $this->createLink('build', 'view', "buildID=$buildID&type=story&link=true&param=" . helper::safe64Encode("&browseType=bySearch&queryID=myQueryID"));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values']   = $this->loadModel('productplan')->getPairsForStory($build->product, $build->branch, 'skipParent');
        $this->config->product->search['params']['module']['values'] = $this->tree->getOptionMenu($build->product, 'story', 0, $build->branch);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        if($build->project)
        {
            $project = $this->loadModel('project')->getByID($build->project);
            if(!$project->hasProduct and $project->model != 'scrum')
            {
                unset($this->config->product->search['fields']['plan']);
            }
            else if(!$project->hasProduct and !$project->multiple)
            {
                unset($this->config->product->search['fields']['plan']);
            }
        }

        if($product->type == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $branchPairs = $this->loadModel('branch')->getPairs($build->product, 'noempty');
            $branchAll   = sprintf($this->lang->build->branchAll, $this->lang->product->branchName[$product->type]);
            $branches    = array('' => $branchAll) + array(BRANCH_MAIN => $this->lang->branch->main);
            if($build->branch)
            {
                foreach(explode(',', $build->branch) as $branchID)
                {
                    if($branchID == '0') continue;
                    $branches += array($branchID => $branchPairs[$branchID]);
                }
            }

            $this->config->product->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        $executionID = $build->execution ? $build->execution : $build->project;
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($build->product, $build->branch, $queryID, 'id', $executionID, 'story', $build->allStories, 'draft,reviewing,changing', $pager);
        }
        else
        {
            $allStories = $this->story->getExecutionStories($executionID, $build->product, 0, 't1.`order`_desc', 'byBranch', $build->branch, 'story', $build->allStories, 'draft,reviewing,changing', $pager);
        }

        $this->view->allStories   = $allStories;
        $this->view->build        = $build;
        $this->view->buildStories = empty($build->stories) ? array() : $this->story->getByList($build->stories);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->pager        = $pager;
        $this->display();
    }

    /**
     * Unlink story
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkStory($buildID, $storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->build->confirmUnlinkStory, inlink('unlinkstory', "buildID=$buildID&storyID=$storyID&confirm=yes")));
        }
        else
        {
            $this->build->unlinkStory($buildID, $storyID);
            $this->loadModel('action')->create('build', $buildID, 'unlinkstory', '', $storyID);
            return print(js::reload('parent'));
        }
    }

    /**
    * AJAX: Get unlinkBranch story and bug.
    *
    * @param  int    $buildID
    * @param  int    $newBranch
    * @access public
    * @return void
    */
    public function ajaxGetBranch($buildID, $newBranch)
    {
        $build        = $this->build->getByID($buildID);
        $oldBranch    = $build->branch;
        $buildStories = $build->allStories ? $this->loadModel('story')->getByList($build->allStories) : array();
        $buildBugs    = $build->allBugs ? $this->loadModel('bug')->getByList($build->allBugs) : array();
        $branchPairs  = $this->loadModel('branch')->getPairs($build->product);
        $typeName     = $this->lang->product->branchName[$build->productType];

        $removeBranches = '';
        foreach(explode(',', $oldBranch) as $oldBranchID)
        {
            if($oldBranchID and strpos(",$newBranch,", ",$oldBranchID,") === false) $removeBranches .= "{$branchPairs[$oldBranchID]},";
        }

        $unlinkStoryCounts = 0;
        $unlinkBugCounts   = 0;
        if($oldBranch)
        {
            foreach($buildStories as $storyID => $story)
            {
                if($story->branch and strpos(",$newBranch,", ",$story->branch,") === false) $unlinkStoryCounts ++;
            }

            foreach($buildBugs as $bugID => $bug)
            {
                if($bug->branch and strpos(",$newBranch,", ",$bug->branch,") === false) $unlinkBugCounts ++;
            }
        }

        if($unlinkStoryCounts and $unlinkBugCounts)
        {
            printf($this->lang->build->confirmChangeBuild, $typeName, trim($removeBranches, ','), $typeName, $unlinkStoryCounts, $unlinkBugCounts);
        }
        elseif($unlinkStoryCounts)
        {
            printf($this->lang->build->confirmRemoveStory, $typeName, trim($removeBranches, ','), $typeName, $unlinkStoryCounts);
        }
        elseif($unlinkBugCounts)
        {
            printf($this->lang->build->confirmRemoveBug, $typeName, trim($removeBranches, ','), $typeName, $unlinkBugCounts);
        }
    }

    /**
     * Batch unlink story.
     *
     * @param  int   $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($buildID)
    {
        $this->build->batchUnlinkStory($buildID);
        return print(js::locate($this->createLink('build', 'view', "buildID=$buildID&type=story"), 'parent'));
    }

    /**
     * Unlink story and bug when edit branch of build.
     * @param  array    $changes
     * @access protected
     * @return void
     */
    protected function unlinkOldBranch($changes)
    {
        foreach($changes as $buildID => $changeList)
        {
            $oldBranch = '';
            $newBranch = '';
            foreach($changeList as $changeId => $change)
            {
                if($change['field'] == 'branch')
                {
                    $oldBranch = $change['old'];
                    $newBranch = $change['new'];
                    break;
                }
            }
            $build       = $this->build->getByID($buildID);
            $planStories = $build->allStories ? $this->loadModel('story')->getByList($build->allStories) : '';
            $planBugs    = $build->allBugs ? $this->loadModel('bug')->getByList($build->allBugs) : '';
            if($oldBranch)
            {
                foreach($planStories as $storyID => $story)
                {
                    if($story->branch and strpos(",$newBranch,", ",$story->branch,") === false) $this->build->unlinkStory($buildID, $storyID);
                }

                foreach($planBugs as $bugID => $bug)
                {
                    if($bug->branch and strpos(",$newBranch,", ",$bug->branch,") === false) $this->build->unlinkBug($buildID, $bugID);
                }
            }
        }
    }

    /**
     * Link bugs.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug($buildID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->build->linkBug($buildID);
            return print(js::locate(inlink('view', "buildID=$buildID&type=bug"), 'parent'));
        }

        $this->session->set('bugList', inlink('view', "buildID=$buildID&type=bug&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');
        /* Set menu. */
        $build   = $this->build->getByID($buildID);
        $product = $this->loadModel('product')->getByID($build->product);
        if($build->execution) $this->loadModel('execution')->setMenu($build->execution);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Build the search form. */
        $this->loadModel('bug');
        $this->config->bug->search['actionURL'] = $this->createLink('build', 'view', "buildID=$buildID&type=bug&link=true&param=" . helper::safe64Encode("&browseType=bySearch&queryID=myQueryID"));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairsForStory($build->product, $build->branch, 'skipParent');
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($build->product, 'bug', 0, $build->branch);
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($build->product, $build->branch, 'id_desc', $this->session->project);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->build->getBuildPairs($build->product, $branch = 'all', $params = 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['params']['product']);
        unset($this->config->bug->search['fields']['project']);
        unset($this->config->bug->search['params']['project']);

        if($build->project)
        {
            $project = $this->loadModel('project')->getByID($build->project);
            if(!$project->hasProduct and $project->model != 'scrum')
            {
                unset($this->config->bug->search['fields']['plan']);
            }
            else if(!$project->hasProduct and !$project->multiple)
            {
                unset($this->config->bug->search['fields']['plan']);
            }
        }

        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $buildBranch = array();
            $branchList  = $this->loadModel('branch')->getPairs($build->product, '', $build->execution);
            $branchAll   = sprintf($this->lang->build->branchAll, $this->lang->product->branchName[$product->type]);
            $branches    = array('' => $branchAll, BRANCH_MAIN => $this->lang->branch->main);
            if(strpos($build->branch, ',') !== false) $buildBranch = explode(',', $build->branch);
            foreach($buildBranch as $buildKey) $branches += array($buildKey => zget($branchList, $buildKey));


            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $executionID = $build->execution ? $build->execution : $build->project;
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($build->product, $build->branch, $queryID, 'id_desc', $build->allBugs, $pager, $build->project);
        }
        else
        {
            $allBugs = $this->bug->getExecutionBugs($executionID, 0, 'all', $buildID, 'noclosed', 0, 'status_desc,id_desc', $build->allBugs, $pager);
        }

        $this->view->allBugs    = $allBugs;
        $this->view->build      = $build;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Unlink story
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($buildID, $bugID)
    {
        $this->build->unlinkBug($buildID, $bugID);

        /* if ajax request, send result. */
        if($this->server->ajax)
        {
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
            }
            else
            {
                $response['result']  = 'success';
                $response['message'] = '';
            }
            return $this->send($response);
        }
        return print(js::reload('parent'));
    }

    /**
     * Batch unlink story.
     *
     * @param  int $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkBug($buildID)
    {
        $this->build->batchUnlinkBug($buildID);
        return print(js::locate($this->createLink('build', 'view', "buildID=$buildID&type=bug"), 'parent'));
    }
}
