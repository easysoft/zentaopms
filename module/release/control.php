<?php
declare(strict_types=1);
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: control.php 4178 2013-01-20 09:32:11Z wwccss $
 * @link        https://www.zentao.net
 */
class release extends control
{
    /**
     * 公共函数，设置产品菜单及页面基础数据。
     * Common action, set the menu and basic data.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function commonAction(int $productID, string $branch = '')
    {
        $this->loadModel('product')->setMenu($productID, $branch);

        $product  = $this->product->getById($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'create'));

        $products = $this->product->getPairs('all', 0, '', 'all');
        $this->product->checkAccess($productID, $products);

        $this->view->product  = $product;
        $this->view->branch   = $branch;
        $this->view->branches = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
    }

    /**
     * 发布列表。
     * Browse releases.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $type       all|normal|terminate
     * @param  string $orderBy
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $from
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function browse(int $productID, string $branch = 'all', string $type = 'all', string $orderBy = 't1.date_desc', string $param = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $from = 'product', int $blockID = 0)
    {
        if($from === 'doc' || $from == 'ai')
        {
            $this->app->loadLang('doc');

            $products = $this->loadModel('product')->getPairs('nodeleted');
            if(empty($products)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noProduct));

            if(!$productID) $productID = key($products);

            $this->view->products = $products;
        }

        $this->commonAction($productID, $branch);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $uri = $this->app->getURI(true);
        $this->session->set('releaseList', $uri, 'product');
        $this->session->set('buildList', $uri);

        $showBranch = $this->view->product->type != 'normal';
        if(!$showBranch)
        {
            unset($this->config->release->dtable->fieldList['branch']);
            unset($this->config->release->search['fields']['branch']);
            unset($this->config->release->search['params']['branch']);
        }

        $sort = $orderBy;
        if(strpos($sort, 'branchName_') !== false) $sort = str_replace('branchName_', 'branch_', $sort);

        $queryID   = $type == 'bySearch' ? (int)$param : 0;
        $actionURL = $this->createLink('release', 'browse', "productID={$productID}&branch={$branch}&type=bySearch&orderBy={$sort}&param=myQueryID&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&from=$from&blockID=$blockID");
        $this->releaseZen->buildSearchForm($queryID, $actionURL, $this->view->product, $branch);

        $releaseQuery    = $type == 'bySearch' ? $this->releaseZen->getSearchQuery($queryID) : '';
        $currentReleases = $this->release->getList($productID, $branch, $type, $sort, $releaseQuery, $pager);
        $children        = implode(',', array_column($currentReleases, 'releases'));

        $childReleases = $this->release->getListByCondition(explode(',', $children), 0, true);
        $releases      = $this->release->processReleaseListData($currentReleases, $childReleases);
        foreach($releases as $release)
        {
            $release->desc       = str_replace('&nbsp;', ' ', strip_tags($release->desc));
            $release->branchName = trim($release->branchName, ',');
            $release->branch     = trim($release->branch, ',');
        }

        $this->view->title         = $this->view->product->name . $this->lang->hyphen . $this->lang->release->browse;
        $this->view->releases      = $releases;
        $this->view->pageSummary   = $this->release->getPageSummary($currentReleases, $type);
        $this->view->type          = $type;
        $this->view->orderBy       = $orderBy;
        $this->view->param         = $param;
        $this->view->pager         = $pager;
        $this->view->showBranch    = $showBranch;
        $this->view->branchPairs   = $this->loadModel('branch')->getPairs($productID);
        $this->view->appList       = $this->loadModel('system')->getPairs();
        $this->view->childReleases = $childReleases;
        $this->view->from          = $from;
        $this->view->blockID       = $blockID;
        $this->view->idList        = '';

        if($from === 'doc')
        {
            $content = $this->loadModel('doc')->getDocBlockContent($blockID);
            $this->view->idList = zget($content, 'idList', '');
        }

        $this->display();
    }

    /**
     * 创建一个发布。
     * Create a release.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $status
     * @access public
     * @return void
     */
    public function create(int $productID, string $branch = 'all', string $status = 'wait')
    {
        if(!empty($_POST))
        {
            $releaseData = $this->releaseZen->buildReleaseForCreate($productID, (int)$branch);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($_FILES['releaseFiles'])) $_FILES['files'] = $_FILES['releaseFiles'];
            unset($_FILES['releaseFiles']);

            $releaseID = $this->release->create($releaseData, $this->post->sync ? true : false);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('release', $releaseID, 'opened');

            $result  = $this->executeHooks($releaseID);
            $message = $result ? $result : $this->lang->saveSuccess;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $releaseID));
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'callback' => 'refreshProductBuild'));

            return $this->send(array('result' => 'success', 'message' => $message, 'load' => inlink('view', "releaseID={$releaseID}")));
        }

        $builds         = $this->loadModel('build')->getBuildPairs(array($productID), $branch, 'notrunk|withbranch|hasproject', 0, 'execution', '', false);
        $releasedBuilds = $this->release->getReleasedBuilds($productID, $branch);
        foreach($releasedBuilds as $build) unset($builds[$build]);

        $this->commonAction($productID, $branch);
        unset($this->lang->release->statusList['fail']);
        unset($this->lang->release->statusList['terminate']);

        $this->view->title       = $this->view->product->name . $this->lang->hyphen . $this->lang->release->create;
        $this->view->productID   = $productID;
        $this->view->builds      = $builds;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->view->lastRelease = $this->release->getLast($productID, (int)$branch);
        $this->view->status      = $status;
        $this->view->appList     = $this->loadModel('system')->getList($productID);

        $this->display();
    }

    /**
     * 编辑一个发布。
     * Edit a release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function edit(int $releaseID)
    {
        $release = $this->release->getByID($releaseID);
        $release->files = $this->loadModel('file')->getByObject('release', $releaseID);

        if(!empty($_POST))
        {
            if($this->post->status == 'wait')
            {
                $this->config->release->edit->requiredFields = str_replace(',releasedDate', '', $this->config->release->edit->requiredFields);
            }
            else
            {
                $this->config->release->edit->requiredFields = str_replace(',date', '', $this->config->release->edit->requiredFields);
            }

            $releaseData = form::data(null, $releaseID)->setIF($this->post->build === false, 'build', 0)->get();
            $releaseData->releases = '';

            $system = $this->loadModel('system')->fetchByID($releaseData->system);
            if($system->integrated == '1')
            {
                $releases = (array)$this->post->releases;

                $releaseData->build    = '';
                $releaseData->releases = trim(implode(',', array_filter($releases)), ',');
                if(!$releaseData->releases) dao::$errors['releases[' . key($releases) . ']'][] = sprintf($this->lang->error->notempty, $this->lang->release->name);
            }
            if(dao::isError()) return $this->sendError(dao::getError());

            $changes = $this->release->update($releaseData, $release);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $result  = $this->executeHooks($releaseID);
            $message = $result ? $result : $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => inlink('view', "releaseID={$releaseID}")));
        }

        /* Get release and build. */
        $this->commonAction($release->product);

        $builds         = $this->loadModel('build')->getBuildPairs(array($release->product), $release->branch, 'notrunk|withbranch|hasproject', 0, 'project', $release->build, false);
        $releasedBuilds = $this->release->getReleasedBuilds($release->product);
        foreach($releasedBuilds as $releasedBuild)
        {
            if(strpos(',' . trim($release->build, ',') . ',', ",{$releasedBuild},") === false) unset($builds[$releasedBuild]);
        }

        $appList = $this->loadModel('system')->getList($release->product);
        if($release->system && !isset($appList[$release->system])) $appList[$release->system] = $this->system->fetchByID($release->system);
        $this->view->title   = $this->view->product->name . $this->lang->hyphen . $this->lang->release->edit;
        $this->view->release = $release;
        $this->view->builds  = $builds;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed');
        $this->view->appList = $appList;

        $this->display();
    }

    /**
     * 查看一个发布。
     * View a release.
     *
     * @param  int    $releaseID
     * @param  string $type       story|bug|leftBug
     * @param  string $link
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $releaseID, string $type = 'story', string $link = 'false', string $param = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        $release = $this->release->getByID($releaseID, true);
        if(!$release) return $this->sendError($this->lang->notFound, $this->createLink('product', 'index'));

        $uri = $this->app->getURI(true);
        if(!empty($release->build)) $this->session->set('buildList', $uri, 'project');
        if($type == 'story') $this->session->set('storyList', $uri, 'product');
        if($type == 'bug' || $type == 'leftBug') $this->session->set('bugList', $uri, 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $sort .= ',buildID_asc';

        $storyPager   = new pager($type == 'story' ? $recTotal : 0, $recPerPage, $type == 'story' ? $pageID : 1);
        $bugPager     = new pager($type == 'bug' ? $recTotal : 0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $leftBugPager = new pager($type == 'leftBug' ? $recTotal : 0, $recPerPage, $type == 'leftBug' ? $pageID : 1);
        $this->releaseZen->assignVarsForView($release, $type, $link, $param, $orderBy, $storyPager, $bugPager, $leftBugPager);

        $this->commonAction($release->product);
        if($this->app->tab == 'project')
        {
            $projectID = (int)$this->session->project;
            $this->loadModel('project')->setMenu($projectID);
            $this->view->project = $this->project->getByID($projectID);
        }

        $this->executeHooks($releaseID);

        $this->view->appList        = $this->loadModel('system')->getPairs();
        $this->view->linkedReleases = $release->releases ? $this->release->getListByCondition(explode(',', $release->releases)) : array();
        $this->view->includedApps   = $this->release->getListByCondition(array(), $release->id);
        $this->display();
    }

    /**
     * Notify for release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function notify(int $releaseID, int $projectID = 0)
    {
        if(strtolower($this->server->request_method) == "post")
        {
            if(isset($_POST['notify']))
            {
                $notify = implode(',', $this->post->notify);
                $this->dao->update(TABLE_RELEASE)->set('notify')->eq($notify)->where('id')->eq($releaseID)->exec();

                $this->release->sendmail($releaseID);
                $this->loadModel('action')->create('release', $releaseID, 'notified');
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        if($this->app->tab == 'project' && $projectID != 0)
        {
            $project = $this->loadModel('project')->getByID($this->session->project);

            if(!$project->hasProduct)
            {
                unset($this->lang->release->notifyList['FB']);
                unset($this->lang->release->notifyList['PO']);
                unset($this->lang->release->notifyList['QD']);
            }

            if(!$project->multiple) unset($this->lang->release->notifyList['ET']);
        }

        $this->view->release = $this->release->getById($releaseID);
        $this->view->actions = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->display();
    }

    /**
     * 删除发布。
     * Delete a release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function delete(int $releaseID)
    {
        $release = $this->release->fetchByID($releaseID);

        $this->release->delete(TABLE_RELEASE, $releaseID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if($release && $release->system) $this->loadModel('system')->setSystemRelease($release->system, $releaseID);
        $message = $this->executeHooks($releaseID) ?: $this->lang->saveSuccess;

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message));

        $locate = $this->session->releaseList ? $this->session->releaseList : $this->createLink($this->app->rawModule, 'browse', "productID={$release->product}");

        return $this->send(array('result' => 'success', 'load' => $locate));
    }

    /**
     * 导出需求列表和Bug列表。
     * Export story list and bug list.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function export(int $releaseID)
    {
        if(!empty($_POST))
        {
            $release  = $this->release->getByID($releaseID, true);
            $type     = $this->post->type;
            $fileName = $this->post->fileName;
            if(empty($fileName)) return $this->sendError(sprintf($this->lang->error->notempty, $this->lang->release->fileName));

            $html = '';
            if($type == 'story' || $type == 'all')   $html .= $this->releaseZen->buildStoryDataForExport($release);
            if($type == 'bug' || $type == 'all')     $html .= $this->releaseZen->buildBugDataForExport($release, 'bug');
            if($type == 'leftbug' || $type == 'all') $html .= $this->releaseZen->buildBugDataForExport($release, 'leftbug');

            $html = "<html><head><meta charset='utf-8'><title>{$fileName}</title><style>table, th, td{font-size:12px; border:1px solid gray; border-collapse:collapse;}</style></head><body>$html</body></html>";
            $this->loadModel('file')->sendDownHeader($fileName, 'html', $html);
        }

        $this->display();
    }

    /**
     * 关联需求。
     * Link stories
     *
     * @param  int    $releaseID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $releaseID = 0, string $browseType = '', int $param = 0, int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->release->linkStory($releaseID, $this->post->stories);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "releaseID={$releaseID}&type=story"), 'closeModal' => true));
        }

        $this->loadModel('story');
        $this->session->set('storyList', $this->createLink($this->app->rawModule, 'view', "releaseID={$releaseID}&type=story&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'product');

        $release = $this->release->getByID($releaseID);
        $this->commonAction($release->product);

        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->releaseZen->buildLinkStorySearchForm($release, $queryID);

        $builds          = $this->loadModel('build')->getByList(explode(',', $release->build));
        $executionIdList = array();
        foreach($builds as $build)
        {
            if(!empty($build->execution)) $executionIdList[$build->execution] = (int)$build->execution;
            if(empty($build->execution) && !empty($build->project)) $executionIdList[$build->project] = (int)$build->project;
        }

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $excludeStoryIdList = $this->releaseZen->getExcludeStoryIdList($release);
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($release->product, $release->branch, $queryID, 'id_desc', $executionIdList, 'story', $excludeStoryIdList, 'draft,reviewing,changing', $pager);
        }
        else
        {
            $allStories = $this->story->batchGetExecutionStories(implode(',', $executionIdList), $release->product, 't1.`order`_desc', 'byBranch', $release->branch, 'story', $excludeStoryIdList, $pager);
        }

        $this->view->allStories = $allStories;
        $this->view->release    = $release;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->grades     = $this->loadModel('story')->getGradePairs('story', 'all');
        $this->view->showGrade  = $this->config->edition == 'ipd';
        $this->view->pager      = $pager;

        if($this->app->tab == 'project') $this->view->projectID = $release->project;

        $this->display();
    }

    /**
     * 移除关联的需求。
     * Unlink a story.
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory(int $releaseID, int $storyID)
    {
        $this->release->unlinkStory($releaseID, $storyID);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "releaseID=$releaseID&type=story")));
    }

    /**
     * 批量解除发布跟需求的关联。
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function batchUnlinkStory(int $releaseID)
    {
        $this->release->batchUnlinkStory($releaseID, (array)$this->post->storyIdList);
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "releaseID={$releaseID}&type=story")));
    }

    /**
     * 发布批量关联Bug。
     * Link bugs.
     *
     * @param  int    $releaseID
     * @param  string $browseType  bug|leftBug|bySearch
     * @param  int    $param
     * @param  string $type        bug|leftBug
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug(int $releaseID = 0, string $browseType = '', int $param = 0, string $type = 'bug', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->release->linkBug($releaseID, $type, (array)$this->post->bugs);
            return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "releaseID={$releaseID}&type={$type}")));
        }

        $this->session->set('bugList', $this->createLink($this->app->rawModule, 'view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');

        /* Set menu. */
        $this->loadModel('bug');
        $release = $this->release->getByID($releaseID);
        $this->commonAction($release->product);

        /* Build the search form. */
        $queryID = $browseType == 'bySearch' ? (int)$param : 0;
        $this->releaseZen->buildLinkBugSearchForm($release, $queryID, $type);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $builds      = $this->loadModel('build')->getByList(explode(',', $release->build));
        $allBugs     = array();
        $releaseBugs = $type == 'bug' ? $release->bugs : $release->leftBugs;
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch('bug', $release->product, $release->branch, 0, 0 , $queryID, $releaseBugs, 'id_desc', $pager);
        }
        else
        {
            $functionName = $type == 'bug' ? 'getReleaseBugs' : 'getProductLeftBugs';
            $allBugs      = $this->bug->$functionName(array_keys($builds), $release->product, $release->branch, $releaseBugs, $pager);
        }

        $this->view->allBugs     = $allBugs;
        $this->view->releaseBugs = empty($releaseBugs) ? array() : $this->bug->getByIdList($releaseBugs);
        $this->view->release     = $release;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->type        = $type;
        $this->view->pager       = $pager;

        if($this->app->tab == 'project') $this->view->projectID = $release->project;

        $this->display();
    }

    /**
     * 移除关联的Bug。
     * Unlink linked bug.
     *
     * @param  int    $releaseID
     * @param  int    $bugID
     * @param  string $type
     * @access public
     * @return void
     */
    public function unlinkBug(int $releaseID, int $bugID, string $type = 'bug')
    {
        $this->release->unlinkBug($releaseID, $bugID, $type);

        /* if ajax request, send result. */
        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError();
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $response['load']    = $this->createLink($this->app->rawModule, 'view', "releaseID={$releaseID}&type={$type}");
        }
        return $this->send($response);
    }

    /**
     * 批量解除发布跟Bug的关联。
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @param  string $type       bug|leftBug
     * @access public
     * @return void
     */
    public function batchUnlinkBug(int $releaseID, string $type = 'bug')
    {
        $this->release->batchUnlinkBug($releaseID, $type, (array)$this->post->bugIdList);
        return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'view', "releaseID={$releaseID}&type={$type}")));
    }

    /**
     * 发布页面。
     * Publish page.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function publish(int $releaseID)
    {
        $release = $this->release->getByID($releaseID);
        if($_POST)
        {
            if(!$this->post->releasedDate)
            {
                dao::$errors['releasedDate'] = sprintf($this->lang->error->notempty, $this->lang->release->releasedDate);
                return $this->sendError(dao::getError());
            }

            $this->release->changeStatus($releaseID, $this->post->status, $this->post->releasedDate);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('release', $releaseID, 'published', $this->post->comment, $this->post->status);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->release = $release;
        $this->display();
    }

    /**
     * 激活/停止维护发布。
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $action    play|pause
     * @access public
     * @return void
     */
    public function changeStatus(int $releaseID, string $action)
    {
        $status = 'normal';
        if($action == 'pause') $status = 'terminate';
        if($action == 'active')
        {
            $hasOld  = false;
            $actions = $this->loadModel('action')->getList('release', $releaseID);
            foreach(array_reverse($actions) as $releaseAction)
            {
                foreach($releaseAction->history as $history)
                {
                    if($history->field == 'status')
                    {
                        $status = $history->old;
                        $hasOld = true;
                        break;
                    }
                }
                if($hasOld) break;
            }
        }
        $this->release->changeStatus($releaseID, $status);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('release', $releaseID, 'changestatus', '', $action);
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 获取生成应用和发布的联动组件。
     * Get system and release combobox.
     *
     * @param  int    $systemID
     * @param  string $linkedRelease
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function ajaxLoadSystemBlock(int $systemID, string $linkedRelease = '', int $releaseID = 0)
    {
        $system   = $this->loadModel('system')->fetchByID($systemID);
        $children = explode(',', $system->children);

        $appList  = $this->system->getByIdList($children);
        $releases = $this->release->getListBySystem($children, $releaseID);

        $this->view->appList       = $appList;
        $this->view->releases      = $releases;
        $this->view->linkedRelease = $linkedRelease ? explode(',', $linkedRelease) : array();
        $this->display();
    }
}
