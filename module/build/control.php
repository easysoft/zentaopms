<?php
declare(strict_types=1);
/**
 * The control file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: control.php 4992 2013-07-03 07:21:59Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class build extends control
{
    /**
     * 公共函数，设置产品型项目属性。
     * Common actions.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function commonActions(int $projectID = 0)
    {
        $hidden  = '';
        if($projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            if(!$project->hasProduct) $hidden = 'hide';

            $this->view->multipleProject = $project->multiple;
        }

        $this->view->hidden    = $hidden;
        $this->view->projectID = $projectID;
    }

    /**
     * 创建一个版本。
     * Create a build.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create(int $executionID = 0, int $productID = 0, int $projectID = 0)
    {
        if(!empty($_POST))
        {
            $build = form::data()->get();
            if(dao::isError()) return $this->sendError(dao::getError());
            if(commonModel::isTutorialMode()) return $this->sendSuccess(array('closeModal' => true)); // Fix bug #21095.

            $buildID = $this->build->create($build);
            if(dao::isError()) return $this->sendError(dao::getError());

            $message = $this->executeHooks($buildID);
            if($message) $this->lang->saveSuccess = $message;
            if(in_array($this->app->tab, array('execution', 'project')) && helper::isAjaxRequest('modal')) return $this->sendSuccess(array('closeModal' => true, 'callback' => 'loadExecutionBuilds()'));
            return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID"), 'id' => $buildID));
        }

        $status = empty($this->config->CRProduct) ? 'noclosed' : '';
        $this->loadModel('execution');
        $this->loadModel('project');
        $this->app->loadLang('artifactrepo');

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->project->setMenu($projectID);
        }
        elseif(in_array($this->app->tab, array('execution', 'qa')))
        {
            $execution = $this->execution->getByID($executionID);
            $projectID = $execution ? $execution->project : 0;
        }

        if($this->app->tab == 'execution') $this->execution->setMenu($executionID);
        if(in_array($this->app->tab, array('execution', 'project'))) $this->session->set('project', $projectID);

        $this->buildZen->assignCreateData($productID, $executionID, $projectID, $status);
    }

    /**
     * 编辑一个版本。
     * Edit a build.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function edit(int $buildID)
    {
        if(!empty($_POST))
        {
            $build = form::data()->get();
            $changes = $this->build->update($buildID, $build);
            if(dao::isError()) return $this->sendError(dao::getError());

            $files = $this->loadModel('file')->saveUpload('build', $buildID);
            $change[$buildID] = $changes;
            $this->unlinkOldBranch($change);

            if($changes || $files)
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('build', $buildID, 'Edited', $fileAction);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($buildID);
            if($message) $this->lang->saveSuccess = $message;

            return $this->sendSuccess(array('locate' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID") . "#app={$this->app->tab}"));
        }

        $this->loadModel('execution');
        $this->loadModel('product');
        $build = $this->build->getById($buildID);

        /* Set menu. */
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($build->project);
        if($this->app->tab == 'execution')
        {
            $this->execution->setMenu((int)$build->execution);
            $this->view->executionID = $build->execution;
        }

        $this->commonActions($build->project);
        $this->buildZen->assignEditData($build);
    }

    /**
     * 版本详情。
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
    public function view(int $buildID, string $type = 'story', string $link = 'false', string $param = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        $build = $this->build->getByID($buildID, true);
        if(!$build)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->sendError($this->lang->notFound, $this->createLink('execution', 'all'));
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false && $type == 'story') $sort = str_replace('pri_', 'priOrder_', $sort);

        $bugPager          = new pager($type == 'bug' ? $recTotal : 0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $generatedBugPager = new pager($type == 'generatedBug' ? $recTotal : 0, $recPerPage, $type == 'generatedBug' ? $pageID : 1);
        $this->buildZen->assignBugVarsForView($build, $type, $sort, $param, $bugPager, $generatedBugPager);

        $storyPager = new pager($type == 'story' ? $recTotal : 0, $recPerPage, $type == 'story' ? $pageID : 1);
        $this->buildZen->assignProductVarsForView($build, $type, $sort, $storyPager);

        /* Set menu. */
        $this->buildZen->setMenuForView($build);
        $this->commonActions($build->project);
        $this->executeHooks($buildID);

        /* Assign. */
        $this->view->canBeChanged  = common::canBeChanged('build', $build); // Determines whether an object is editable.
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->build         = $build;
        $this->view->actions       = $this->loadModel('action')->getList('build', $buildID);
        $this->view->link          = $link;
        $this->view->orderBy       = $orderBy;
        $this->view->execution     = $this->loadModel('execution')->getByID((int)$build->execution);
        $this->view->childBuilds   = empty($build->builds) ? array() : $this->build->getByList(explode(',', $build->builds));

        $this->display();
    }

    /**
     * 删除一个版本。
     * Delete a build.
     *
     * @param  int    $buildID
     * @param  string $from    execution|project
     * @access public
     * @return void
     */
    public function delete(int $buildID, string $from = 'execution')
    {
        $build = $this->build->getById($buildID);
        $this->build->delete(TABLE_BUILD, $buildID);

        $message = $this->executeHooks($buildID);
        if($message) $this->lang->saveSuccess = $message;

        if(dao::isError()) return $this->sendError(dao::getError());

        $moduleName = $from == 'project' ? 'projectbuild' : $from;
        $methodName = $from == 'project' ? 'browse' : 'build';
        return $this->sendSuccess(array('load' => $this->createLink($moduleName, $methodName, "executionID={$build->$from}")));
    }

    /**
     * 重构获取产品下的版本下拉列表。
     * AJAX: get builds of a product in html select.
     *
     * @param  int    $productID
     * @param  string $varName      the name of the select object to create
     * @param  string $build        build to selected
     * @param  string $branch
     * @param  string $type         get all builds or some builds belong to normal releases and executions are not done.
     * @access public
     * @return string
     */
    public function ajaxGetProductBuilds(int $productID, string $varName, string $build = '', string $branch = 'all', string $type = 'normal')
    {
        $isJsonView = $this->app->getViewType() == 'json';
        if($varName == 'openedBuild' )
        {
            $params = $type == 'all' ? 'noempty,withbranch,noreleased' : 'noempty,noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs(array($productID), $branch, $params, 0, 'project', $build);
            if($isJsonView) return print(json_encode($builds));

            $items = array();
            foreach($builds as $buildID => $buildName) $items[] = array('text' => $buildName, 'value' => $buildID, 'keys' => $buildName);
            return print(json_encode($items));
        }
        if($varName == 'openedBuilds' )
        {
            $builds    = $this->build->getBuildPairs(array($productID), $branch, 'noempty,noreleased', 0, 'project', $build);
            $buildList = array();
            foreach($builds as $buildID => $buildName) $buildList[] = array('value' => $buildID, 'text' => $buildName);
            return $this->send($buildList);
        }
        if($varName == 'resolvedBuild')
        {
            $params = $type == 'all' ? 'withbranch,noreleased' : 'noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs(array($productID), $branch, $params, 0, 'project', $build);
            if($isJsonView) return print(json_encode($builds));

            $items = array();
            foreach($builds as $buildID => $buildName) $items[] = array('text' => $buildName, 'value' => $buildID, 'keys' => $buildName);
            return print(json_encode($items));
        }

        $builds = $this->build->getBuildPairs(array($productID), $branch, $type, 0, 'project', $build, false);
        if($isJsonView) return print(json_encode($builds));

        $items = array();
        foreach($builds as $buildID => $buildName) $items[] = array('text' => $buildName, 'value' => $buildID);
        return print(json_encode($items));
    }

    /**
     * 获取项目下的版本下拉列表。
     * AJAX: get builds of a project in html select.
     *
     * @param  int        $projectID
     * @param  string     $varName    the name of the select object to create
     * @param  string     $build      build to selected
     * @param  string|int $branch
     * @param  bool       $needCreate if need to append the link of create build
     * @param  string     $type       get all builds or some builds belong to normal releases and executions are not done.
     * @access public
     * @return string
     */
    public function ajaxGetProjectBuilds(int $projectID, int $productID, string $varName, string $build = '', string|int $branch = 'all', $needCreate = false, $type = 'normal')
    {
        $isJsonView = $this->app->getViewType() == 'json';
        if($varName == 'openedBuild')
        {
            if(empty($projectID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $type);

            $params = $type == 'all' ? 'noempty,withbranch,noreleased' : 'noempty,noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs(array($productID), $branch, $params, $projectID, 'project', $build);
            if($isJsonView) return print(json_encode($builds));

            $items = array();
            foreach($builds as $id => $name) $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
            return print(json_encode($items));
        }
        if($varName == 'resolvedBuild')
        {
            if(empty($projectID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $type);

            $params = ($type == 'all') ? 'withbranch,noreleased' : 'noterminate,nodone,withbranch,noreleased';
            $builds = $this->build->getBuildPairs(array($productID), $branch, $params, $projectID, 'project', $build);
            if($isJsonView) return print(json_encode($builds));

            $items = array();
            foreach($builds as $id => $name) $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
            return print(json_encode($items));
        }

        if(empty($projectID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $type);

        $builds = $this->build->getBuildPairs(array($productID), $branch, $type, $projectID, 'project', $build, false);
        if($isJsonView) return print(json_encode($builds));

        $items = array();
        foreach($builds as $id => $name) $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        return print(json_encode($items));
    }

    /**
     * 获取执行下的版本下拉列表。
     * AJAX: get builds of an execution in html select.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $varName      the name of the select object to create
     * @param  string $build        build to selected
     * @param  string $branch
     * @param  bool   $needCreate   if need to append the link of create build
     * @param  string $type         get all builds or some builds belong to normal releases and executions are not done.
     * @access public
     * @return string
     */
    public function ajaxGetExecutionBuilds(int $executionID, int $productID, string $varName, string $build = '', string $branch = 'all', bool $needCreate = false, string $type = 'normal')
    {
        $isJsonView = $this->app->getViewType() == 'json';
        if($varName == 'openedBuild')
        {
            if(empty($executionID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $type);

            $params = ($type == 'all') ? 'noempty,noreleased' : 'noempty,noterminate,nodone,noreleased';
            $builds = $this->build->getBuildPairs(array($productID), $branch, $params, $executionID, 'execution', $build);
            if($isJsonView) return print(json_encode($builds));

            $items = array();
            foreach($builds as $buildID => $buildName) $items[] = array('text' => $buildName, 'value' => $buildID, 'keys' => $buildName);
            return print(json_encode($items));
        }
        if($varName == 'openedBuilds')
        {
            if(empty($executionID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $type);

            $builds    = $this->build->getBuildPairs(array($productID), $branch, 'noempty,noreleased', $executionID, 'execution', $build);
            $buildList = array();
            foreach($builds as $buildID => $buildName) $buildList[] = array('value' => $buildID, 'text' => $buildName);
            return $this->send($buildList);
        }
        if($varName == 'resolvedBuild')
        {
            if(empty($executionID)) return $this->ajaxGetProductBuilds($productID, $varName, $build, $branch, $type);

            $params = ($type == 'all') ? ',noreleased' : 'noterminate,nodone,noreleased';
            $builds = $this->build->getBuildPairs(array($productID), $branch, $params, $executionID, 'execution', $build);
            if($isJsonView) return print(json_encode($builds));
            return print(html::select($varName, $builds, $build, "class='form-control'"));
        }
        if($varName == 'testTaskBuild')
        {
            $builds = $this->build->getBuildPairs(array($productID), $branch, 'noempty,notrunk', $executionID, 'execution', '', false);
            if($isJsonView) return print(json_encode($builds));

            $items = array();
            foreach($builds as $buildID => $buildName) $items[] = array('text' => $buildName, 'value' => $buildID);
            return print(json_encode($items));
        }
        if($varName == 'dropdownList')
        {
            $builds = $this->build->getBuildPairs(array($productID), $branch, 'noempty,notrunk', $executionID, 'execution');
            if($isJsonView) return print(json_encode($builds));

            $list  = "<div class='list-group'>";
            foreach($builds as $buildID => $buildName) $list .= html::a(inlink('view', "buildID={$buildID}"), $buildName);
            $list .= '</div>';
            return print($list);
        }
    }

    /**
     * 获取最后一次创建的版本。
     * Ajax get last build.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function ajaxGetLastBuild(int $projectID, int $executionID)
    {
        $lastBuild = $this->build->getLast($executionID, $projectID);
        if(!$lastBuild) return print('');

        echo "<div class='help-block'> &nbsp; " . $this->lang->build->last . ": <a class='code label light rounded-full' id='lastBuildBtn'>" . $lastBuild->name . "</a></div>";
    }

    /**
     * 版本关联需求。
     * Link stories to build.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $buildID = 0, string $browseType = '', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            if($this->post->stories) $this->build->linkStory($buildID, $this->post->stories);
            return $this->send(array('result' => 'success', 'load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=story")));
        }

        $this->session->set('storyList', $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=story&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), $this->app->tab);

        $build   = $this->build->getById($buildID);
        $product = $this->loadModel('product')->getById($build->product);
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($build->project);
            $this->view->projectID = $build->project;
        }
        if($build->execution) $this->loadModel('execution')->setMenu((int)$build->execution);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->buildZen->buildLinkStorySearchForm($build, $browseType == 'bySearch' ? (int)$param : 0, isset($product->type) ? $product->type : 'normal');

        $this->loadModel('story');
        $executionID = $build->execution ? (int)$build->execution : (int)$build->project;
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($build->product, $build->branch, (int)$param, $orderBy, $executionID, 'story', $build->allStories, $pager);
        }
        else
        {
            $allStories = $this->story->getExecutionStories($executionID, $build->product, $orderBy, 'byBranch', $build->branch, 'story', $build->allStories, $pager);
        }

        $this->view->allStories   = $allStories;
        $this->view->build        = $build;
        $this->view->buildStories = empty($build->stories) ? array() : $this->story->getByList($build->stories);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * 解除需求关联。
     * Unlink story.
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function unlinkStory(int $buildID, int $storyID)
    {
        $this->build->unlinkStory($buildID, $storyID);

        $this->loadModel('action')->create($this->app->rawModule, $buildID, 'unlinkstory', '', $storyID);
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=story")));
    }

    /**
    * 检查并输出移除移除需求和Bug分支的提示信息。
    * AJAX: Get unlinkBranch story and bug.
    *
    * @param  int    $buildID
    * @param  int    $newBranch
    * @access public
    * @return void
    */
    public function ajaxGetBranch(int $buildID, int $newBranch)
    {
        $build = $this->build->getByID($buildID);
        if(!$build) return '';

        $buildStories = $build->allStories ? $this->loadModel('story')->getByList($build->allStories) : array();
        $buildBugs    = $build->allBugs ? $this->loadModel('bug')->getByIdList($build->allBugs) : array();
        $branchPairs  = $this->loadModel('branch')->getPairs($build->product);
        $typeName     = $this->lang->product->branchName[$build->productType];

        $removeBranches = '';
        foreach(explode(',', $build->branch) as $oldBranchID)
        {
            if($oldBranchID && strpos(",$newBranch,", ",$oldBranchID,") === false) $removeBranches .= "{$branchPairs[$oldBranchID]},";
        }

        $unlinkStoryCounts = 0;
        $unlinkBugCounts   = 0;
        if($build->branch)
        {
            foreach($buildStories as $storyID => $story)
            {
                if($story->branch && strpos(",$newBranch,", ",$story->branch,") === false) $unlinkStoryCounts ++;
            }

            foreach($buildBugs as $bugID => $bug)
            {
                if($bug->branch && strpos(",$newBranch,", ",$bug->branch,") === false) $unlinkBugCounts ++;
            }
        }

        if($unlinkStoryCounts && $unlinkBugCounts)
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
     * 批量解除需求关联。
     * Batch unlink story.
     *
     * @param  int    $buildID
     * @access public
     * @return bool
     */
    public function batchUnlinkStory(int $buildID)
    {
        if($this->post->storyIdList) $this->build->batchUnlinkStory($buildID, $this->post->storyIdList);
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=story")));
    }

    /**
     * 编辑版本时，解除跟未关联分支的需求和Bug的关联。
     * Unlink story and bug when edit branch of build.
     *
     * @param  array $changes
     * @access pulic
     * @return void
     */
    public function unlinkOldBranch(array $changes)
    {
        foreach($changes as $buildID => $changeList)
        {
            $oldBranch = '';
            $newBranch = '';
            foreach($changeList as $change)
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
            $planBugs    = $build->allBugs ? $this->loadModel('bug')->getByIdList($build->allBugs) : '';
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
     * 版本关联Bug。
     * Link bug.
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
    public function linkBug(int $buildID = 0, string $browseType = '', int $param = 0, int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->build->linkBug($buildID, $this->post->bugs, (array)$this->post->resolvedBy);
            return $this->send(array('result' => 'success', 'load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=bug")));
        }

        $this->session->set('bugList', $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=bug&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');

        /* Set menu. */
        $build   = $this->build->getByID($buildID);
        $product = $this->loadModel('product')->getByID($build->product);
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($build->project);
            $this->view->projectID = $build->project;
        }
        if($build->execution) $this->loadModel('execution')->setMenu($build->execution);

        /* Build the search form. */
        $queryID = $browseType == 'bySearch' ? $param : 0;
        $this->buildZen->buildLinkBugSearchForm($build, $queryID, isset($product->type) ? $product->type : 'normal');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('bug');
        $executionID = $build->execution ? $build->execution : $build->project;
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch('bug', $build->product, $build->branch, $build->project, $build->execution, $queryID, $build->allBugs, 'id_desc', $pager);
        }
        else
        {
            $allBugs = $this->bug->getExecutionBugs($executionID, 0, 'all', array($buildID), 'noclosed', 0, 'status_desc,id_desc', $build->allBugs, $pager);
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
     * 解除Bug跟版本的关联关系。
     * Unlink bug.
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug(int $buildID, int $bugID)
    {
        $this->build->unlinkBug($buildID, $bugID);
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=bug")));
    }

    /**
     * 批量解除Bug跟版本的关联关系。
     * Batch unlink bugs.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkBug(int $buildID)
    {
        if($this->post->bugIdList) $this->build->batchUnlinkBug($buildID, $this->post->bugIdList);
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "buildID=$buildID&type=bug")));
    }
}
