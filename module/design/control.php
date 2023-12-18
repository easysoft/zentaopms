<?php
declare(strict_types=1);
/**
 * The control file of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: control.php 5107 2020-09-02 09:46:12Z tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
class design extends control
{
    /**
     * 构造函数，加载通用的模块。
     * Construct function, load module auto.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->loadModel('product');
        $this->loadModel('project');
        $this->loadModel('task');
    }

    /**
     * 设置页面公共的数据以及导航。
     * Design common action.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function commonAction(int $projectID = 0, int $productID = 0, int $designID = 0)
    {
        $products    = $this->product->getProductPairsByProject($projectID);
        $products[0] = $this->lang->product->all;

        ksort($products);

        $productID = $this->product->getAccessibleProductID($productID, $products);

        $this->project->setMenu($projectID);

        $project = $this->project->getByID($projectID);
        if(!$project->hasProduct) $this->config->hasSwitcherModules = array();

        $this->view->products         = $products;
        $this->view->switcherParams   = "projectID={$projectID}&productID={$productID}";
        $this->view->switcherText     = zget($products, $productID);
        $this->view->switcherObjectID = $productID;

        return $productID;
    }

    /**
     * 设计列表页面。
     * Browse designs.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type       all|bySearch|HLDS|DDS|DBDS|ADS
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $projectID = 0, int $productID = 0, string $type = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $productID = $this->commonAction($projectID, $productID);
        $project   = $this->project->getByID($projectID);

        /* Save session for design list. */
        $this->session->set('designList', $this->app->getURI(true), 'project');
        $this->session->set('reviewList', $this->app->getURI(true), 'project');

        $products      = $this->product->getProductPairsByProject($projectID);
        $productIdList = $productID ? $productID : array_keys($products);
        $stories       = $this->loadModel('story')->getProductStoryPairs($productIdList, 'all', 0, 'active', 'id_desc', 0, 'full', 'story', false);
        $queryID       = $type == 'bySearch' ? $param : 0;

        /* Build Search Form. */
        $this->config->design->search['params']['story']['values'] = $stories;
        $this->config->design->search['params']['type']['values']  = $this->lang->design->typeList;

        $this->config->design->search['actionURL'] = $this->createLink('design', 'browse', "projectID={$projectID}&productID={$productID}&type=bySearch&queryID=myQueryID");
        $this->config->design->search['queryID']   = $queryID;
        $this->loadModel('search')->setSearchParams($this->config->design->search);

        /* Init pager and set table field and actions. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        if(isset($project->hasProduct) && !$project->hasProduct) unset($this->config->design->dtable->fieldList['product']);
        if(isset($project->hasProduct) && $project->hasProduct) $this->config->design->dtable->fieldList['product']['map'] = $this->view->products;
        if(!helper::hasFeature('devops')) $this->config->design->dtable->fieldList['actions']['menu'] = array('edit', 'delete');

        $this->view->title     = $this->lang->design->common . $this->lang->colon . $this->lang->design->browse;
        $this->view->designs   = $this->design->getList($projectID, $productID, $type, $queryID, $orderBy, $pager);
        $this->view->projectID = $projectID;
        $this->view->productID = $productID;
        $this->view->type      = $type;
        $this->view->param     = $param;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * 创建一个设计。
     * Create a design.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type      all|bySearch|HLDS|DDS|DBDS|ADS
     * @access public
     * @return void
     */
    public function create(int $projectID = 0, int $productID = 0, string $type = 'all')
    {
        $productID = $this->commonAction($projectID, $productID);

        if($_POST)
        {
            $designData = form::data()
                ->add('project', $projectID)
                ->get();

            $designID = $this->design->create($designData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('design', $designID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('design', 'browse', "projectID={$projectID}&productID={$productID}")));
        }

        $products      = $this->product->getProductPairsByProject($projectID);
        $productIdList = $productID ? $productID : array_keys($products);

        $this->view->title      = $this->lang->design->common . $this->lang->colon . $this->lang->design->create;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->stories    = $this->loadModel('story')->getProductStoryPairs($productIdList, 'all', 0, 'active', 'id_desc', 0, 'full', 'story', false);
        $this->view->productID  = $productID;
        $this->view->projectID  = $projectID;
        $this->view->type       = $type;
        $this->view->project    = $this->loadModel('project')->getByID($projectID);

        $this->display();
    }

    /**
     * 批量创建设计。
     * Batch create designs.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type      all|bySearch|HLDS|DDS|DBDS|ADS
     * @access public
     * @return void
     */
    public function batchCreate(int $projectID = 0, int $productID = 0, string $type = 'all')
    {
        $productID = $this->commonAction($projectID, $productID);

        if($_POST)
        {
            $designs = form::batchData()->get();
            $this->design->batchCreate($projectID, $productID, $designs);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse', "projectID={$projectID}&productID={$productID}")));
        }

        $products      = $this->product->getProductPairsByProject($projectID);
        $productIdList = $productID ? $productID : array_keys($products);

        $project = $this->loadModel('project')->getByID($projectID);

        $this->view->title     = $this->lang->design->common . $this->lang->colon . $this->lang->design->batchCreate;
        $this->view->stories   = $this->loadModel('story')->getProductStoryPairs($productIdList);
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed');
        $this->view->type      = $type;
        $this->view->typeList  = $project->model == 'waterfall' ? $this->lang->design->typeList : $this->lang->design->plusTypeList;
        $this->view->projectID = $projectID;

        $this->display();
    }

    /**
     * 设计详情页面。
     * View a design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function view(int $designID = 0)
    {
        $design = $this->design->getByID($designID);
        $this->commonAction($design->project, $design->product, $designID);

        $this->session->set('revisionList', $this->app->getURI(true));
        $this->session->set('storyList', $this->app->getURI(true), 'product');

        $products      = $this->product->getProductPairsByProject($design->project);
        $productIdList = $design->product ? $design->product : array_keys($products);
        $project       = $this->loadModel('project')->getByID($design->project);

        $this->view->title    = $this->lang->design->common . $this->lang->colon . $this->lang->design->view;
        $this->view->design   = $design;
        $this->view->stories  = $this->loadModel('story')->getProductStoryPairs($productIdList);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions  = $this->loadModel('action')->getList('design', $design->id);
        $this->view->repos    = $this->loadModel('repo')->getRepoPairs('project', $design->project);
        $this->view->project  = $project;
        $this->view->typeList = $project->model == 'waterfall' ? $this->lang->design->typeList : $this->lang->design->plusTypeList;

        $this->display();
    }

    /**
     * 编辑一个设计。
     * Edit a design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function edit(int $designID = 0)
    {
        $design = $this->design->getByID($designID);
        $design = $this->design->getAffectedScope($design);
        $this->commonAction($design->project, $design->product, $designID);

        if($_POST)
        {
            $designData = form::data()->get();
            $changes    = $this->design->update($designID, $designData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('design', $designID, 'changed');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('design', 'view', "id={$designID}")));
        }

        $products      = $this->product->getProductPairsByProject($design->project);
        $productIdList = $design->product ? $design->product : array_keys($products);
        $project       = $this->loadModel('project')->getByID($design->project);

        $this->view->title    = $this->lang->design->common . $this->lang->colon . $this->lang->design->edit;
        $this->view->design   = $design;
        $this->view->project  = $project;
        $this->view->stories  = $this->loadModel('story')->getProductStoryPairs($productIdList);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed');
        $this->view->typeList = $project->model == 'waterfall' ? $this->lang->design->typeList : $this->lang->design->plusTypeList;

        $this->display();
    }

    /**
     * 关联代码提交页面。
     * Design link commits.
     *
     * @param  int    $designID
     * @param  int    $repoID
     * @param  string $begin
     * @param  string $end
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkCommit(int $designID = 0, int $repoID = 0, string $begin = '', string $end = '', int $recTotal = 0, int $recPerPage = 50, int $pageID = 1)
    {
        if($_POST)
        {
            $this->design->linkCommit($designID, $repoID, $_POST['revision']);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $design = $this->design->getByID($designID);
        $this->commonAction($design->project, $design->product, $designID);

        /* Get project and date. */
        $project = $this->loadModel('project')->getByID($design->project);
        $begin   = $begin ? date('Y-m-d', strtotime($begin)) : $project->begin;
        $end     = $end ? date('Y-m-d', strtotime($end)) : helper::today();

        /* Get the repository information through the repoID. */
        $repos     = $this->loadModel('repo')->getRepoPairs('project', $design->project);
        $repoID    = $repoID ? $repoID : key($repos);
        $repo      = $this->loadModel('repo')->getByID((int)$repoID);
        $revisions = $this->repo->getCommits($repo, '', 'HEAD', 'dir', null, $begin, date('Y-m-d 23:59:59', strtotime($end)));

        $this->session->set('designRevisions', $revisions);

        /* Get linked submission. */
        $linkedRevisions = array();
        $relations       = $this->loadModel('common')->getRelations('design', $designID, 'commit');
        foreach($relations as $relation) $linkedRevisions[$relation->BID] = $relation->BID;

        foreach($revisions as $id => $commit)
        {
            if(isset($linkedRevisions[$commit->id])) unset($revisions[$id]);
        }

        /* Init pager. */
        $this->app->loadClass('pager', true);
        $pager          = new pager(count($revisions), $recPerPage, $pageID);
        $chunkRevisions = array_chunk($revisions, $pager->recPerPage);

        $this->config->design->linkcommit->dtable->fieldList['revision']['link'] = sprintf($this->config->design->linkcommit->dtable->fieldList['revision']['link'], $repoID, $design->project);
        if(empty($repo->SCM) || $repo->SCM != 'Git') unset($this->config->design->linkcommit->dtable->fieldList['commit']);

        $this->view->title     = $this->lang->design->common . $this->lang->colon . $this->lang->design->linkCommit;
        $this->view->repos     = $repos;
        $this->view->repoID    = $repoID;
        $this->view->repo      = $repo;
        $this->view->revisions = empty($chunkRevisions) ? $chunkRevisions : $chunkRevisions[$pageID - 1];
        $this->view->designID  = $designID;
        $this->view->begin     = $begin;
        $this->view->end       = $end;
        $this->view->design    = $design;
        $this->view->pager     = $pager;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * 设计解除代码提交关联。
     * Design unlink a commit.
     *
     * @param  int    $designID
     * @param  int    $commitID
     * @access public
     * @return void
     */
    public function unlinkCommit(int $designID = 0, int $commitID = 0)
    {
        $this->design->unlinkCommit($designID, $commitID);

        $link = inlink('viewCommit', "designID={$designID}");
        return $this->sendSuccess(array('callback' => "loadModal(\"$link\", 'viewCommitModal');"));
    }

    /**
     * 查看设计关联的代码提交。
     * View a design's commit.
     *
     * @param  int    $designID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function viewCommit(int $designID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Init pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $recPerPage, $pageID);

        $design = $this->design->getCommit($designID, $pager);
        $this->commonAction($design->project, $design->product, $designID);

        $this->config->design->viewcommit->dtable->fieldList['actions']['list']['unlinkCommit']['url'] = sprintf($this->config->design->viewcommit->actionList['unlinkCommit']['url'], $designID);

        $this->view->title  = $this->lang->design->common . $this->lang->colon . $this->lang->design->submission;
        $this->view->design = $design;
        $this->view->pager  = $pager;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->repos  = $this->loadModel('repo')->getRepoPairs('project', $design->project);

        $this->display();
    }

    /**
     * 单个代码提交记录。
     * A version of the repository.
     *
     * @param  int    $revisionID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function revision(int $revisionID = 0, int $projectID = 0)
    {
        $revision = $this->design->getCommitByID($revisionID);
        $this->locate(helper::createLink('repo', 'revision', "repoID={$revision->repo}&objectID={$projectID}&revistion={$revision->revision}"));
    }

    /**
     * Ajax: 设置2.5级产品下拉菜单。
     * Ajax: Set the dropdown menu of 2.5 level product.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxSwitcherMenu(int $projectID, int $productID)
    {
        $this->view->link      = helper::createLink('design', 'browse', "projectID={$projectID}&productID={id}");
        $this->view->projectID = $projectID;
        $this->view->productID = $productID;
        $this->view->products  = $this->loadModel('product')->getProducts($projectID);

        $this->display();
    }

    /**
     * Ajax：通过产品ID获取产品下的需求。
     * Ajax: Get stories by productID and projectID.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $status
     * @param  string $hasParent
     * @access public
     * @return void
     */
    public function ajaxGetProductStories(int $productID, int $projectID, string $status = 'all', string $hasParent = 'true')
    {
        $products      = $this->product->getProductPairsByProject($projectID);
        $productIdList = $productID ? $productID : array_keys($products);
        $stories       = $this->loadModel('story')->getProductStoryPairs($productIdList, 'all', 0, $status, 'id_desc', 0, 'full', 'story', $hasParent);

        $items = array();
        foreach($stories as $storyID => $storyTitle)
        {
            $items[] = array('value' => $storyID, 'text' => $storyTitle, 'keys' => $storyTitle);
        }
        return print(json_encode($items));
    }

    /**
     * 删除一个设计。
     * Delete a design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function delete(int $designID = 0)
    {
        $design = $this->design->getByID($designID);
        $this->design->delete(TABLE_DESIGN, $designID);
        $this->design->unlinkCommit($designID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => inlink('browse', "projectID={$design->project}")));
    }

    /**
     * 更新设计的指派人。
     * Update assign of design.
     *
     * @param  int    $designID
     * @access public
     * @return void
     */
    public function assignTo(int $designID = 0)
    {
        if($_POST)
        {
            $designData = form::data()->get();
            $changes    = $this->design->assign($designID, $designData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('design', $designID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $design = $this->design->getByID($designID);

        $this->view->title  = $this->lang->design->common . $this->lang->colon . $this->lang->design->assignedTo;
        $this->view->design = $design;
        $this->view->users  = $this->loadModel('project')->getTeamMemberPairs($design->project);
        $this->display();
    }
}
