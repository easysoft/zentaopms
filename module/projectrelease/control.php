<?php
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: control.php 4178 2013-01-20 09:32:11Z wwccss $
 * @link        http://www.zentao.net
 */
class projectrelease extends control
{
    public $products = array();

    /**
     * Construct function, load module auto.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $products = array();
        $this->loadModel('product');
        $this->loadModel('release');
        $this->loadModel('project');
    }

    /**
     * Common actions.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function commonAction($projectID = 0, $productID = 0, $branch = 0)
    {
        $this->lang->product->switcherMenu = $this->product->getSwitcher($productID);

        /* Get product and product list by project. */
        $this->products = $this->product->getProductPairsByProject($projectID);
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=projectrelease&projectID=' . $projectID)));
        if(!$productID) $productID = key($this->products);
        $product = $this->product->getById($productID);

        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = (isset($product->type) and $product->type == 'normal') ? array() : $this->loadModel('branch')->getPairs($productID);
        $this->view->branch   = $branch;
        $this->view->project  = $this->project->getByID($projectID);
    }

    /**
     * Browse releases.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $type
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $executionID = 0, $type = 'all')
    {
        $this->session->set('releaseList', $this->app->getURI(true), 'project');
        $project   = $this->project->getById($projectID);
        $execution = $this->loadModel('execution')->getById($executionID);

        if($projectID) $this->project->setMenu($projectID);
        if($executionID) $this->loadModel('execution')->setMenu($executionID, $this->app->rawModule, $this->app->rawMethod);

        $objectName = isset($project->name) ? $project->name : $execution->name;

        $this->view->title       = $objectName . $this->lang->colon . $this->lang->release->browse;
        $this->view->position[]  = $this->lang->release->browse;
        $this->view->execution   = $execution;
        $this->view->project     = $project;
        $this->view->products    = $this->loadModel('product')->getProducts($projectID);
        $this->view->releases    = $this->projectrelease->getList($projectID, $type);
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->type        = $type;
        $this->view->from        = $this->app->openApp;
        $this->display();
    }

    /**
     * Create a release.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        $this->app->loadConfig('release');
        $this->config->projectrelease->create = $this->config->release->create;

        if(!empty($_POST))
        {
            $releaseID = $this->projectrelease->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('release', $releaseID, 'opened');

            $this->executeHooks($releaseID);
            if($this->viewType == 'json') $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $releaseID));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }

        /* Get the builds that can select. */
        $productPairs  = $this->loadModel('product')->getProductPairsByProject($projectID);
        $productIdList = array_keys($productPairs);
        $builds        = $this->loadModel('build')->getProductBuildPairs($productIdList, 0, 0, 'notrunk');
        $releaseBuilds = $this->projectrelease->getReleaseBuilds($projectID);
        foreach($releaseBuilds as $build) unset($builds[$build]);
        unset($builds['trunk']);

        $this->project->setMenu($projectID);
        $this->commonAction($projectID);

        $this->view->title       = $this->view->project->name . $this->lang->colon . $this->lang->release->create;
        $this->view->position[]  = $this->lang->release->create;
        $this->view->builds      = $builds;
        $this->view->lastRelease = $this->projectrelease->getLast($projectID);
        $this->display();
    }

    /**
     * Edit a release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function edit($releaseID)
    {
        $this->app->loadConfig('release');
        $this->config->projectrelease->create = $this->config->release->create;

        if(!empty($_POST))
        {
            $changes = $this->projectrelease->update($releaseID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $files = $this->loadModel('file')->saveUpload('release', $releaseID);
            if($changes or $files)
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'Edited', $fileAction);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($releaseID);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('build');

        /* Get release and build. */
        $release = $this->projectrelease->getById((int)$releaseID);
        $this->commonAction($release->project, $release->product, $release->branch);
        $build = $this->build->getById($release->build);

        /* Set project menu. */
        $this->project->setMenu($release->project);

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->release->edit;
        $this->view->position[] = $this->lang->release->edit;
        $this->view->release    = $release;
        $this->view->build      = $build;
        $this->view->builds     = $this->loadModel('build')->getProjectBuildPairs($release->project, $release->product, $release->branch, 'notrunk|withbranch');
        $this->display();
    }

    /**
     * View a release.
     *
     * @param  int    $releaseID
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
    public function view($releaseID, $type = 'story', $link = 'false', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->session->set('buildList', $this->app->getURI(true), 'execution');
        $this->session->set('storyList', $this->app->getURI(true), $this->app->openApp);

        $this->loadModel('story');
        $this->loadModel('bug');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $release = $this->projectrelease->getByID((int)$releaseID, true);
        if(!$release) die(js::error($this->lang->notFound) . js::locate('back'));

        $storyPager = new pager($type == 'story' ? $recTotal : 0, $recPerPage, $type == 'story' ? $pageID : 1);
        $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($release->stories)->andWhere('deleted')->eq(0)
                ->beginIF($type == 'story')->orderBy($orderBy)->fi()
                ->page($storyPager)
                ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');
        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($release->stories)->andWhere('branch')->eq($release->branch)->fetchPairs('story', 'stage');
        foreach($stages as $storyID => $stage)$stories[$storyID]->stage = $stage;

        $bugPager = new pager($type == 'bug' ? $recTotal : 0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->bugs)->andWhere('deleted')->eq(0)
            ->beginIF($type == 'bug')->orderBy($orderBy)->fi()
            ->page($bugPager)
            ->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'linkedBug');

        $leftBugPager = new pager($type == 'leftBug' ? $recTotal : 0, $recPerPage, $type == 'leftBug' ? $pageID : 1);
        $leftBugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->leftBugs)->andWhere('deleted')->eq(0)
            ->beginIF($type == 'leftBug')->orderBy($orderBy)->fi()
            ->page($leftBugPager)
            ->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'leftBugs');

        $this->commonAction($release->project, $release->product);
        $product = $this->product->getById($release->product);

        /* Set menu. */
        $this->project->setMenu($release->project);

        $this->executeHooks($releaseID);

        $this->view->title        = "RELEASE #$release->id $release->name/" . $product->name;
        $this->view->position[]   = $this->lang->release->view;
        $this->view->release      = $release;
        $this->view->stories      = $stories;
        $this->view->bugs         = $bugs;
        $this->view->leftBugs     = $leftBugs;
        $this->view->actions      = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->type         = $type;
        $this->view->link         = $link;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->view->branchName   = $release->productType == 'normal' ? '' : $this->loadModel('branch')->getById($release->branch);
        $this->view->storyPager   = $storyPager;
        $this->view->bugPager     = $bugPager;
        $this->view->leftBugPager = $leftBugPager;
        $this->view->projectID    = $release->project;
        $this->display();
    }

    /**
     * Delete a release.
     *
     * @param  int    $releaseID
     * @param  string $confirm      yes|no
     * @access public
     * @return void
     */
    public function delete($releaseID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->release->confirmDelete, $this->createLink('projectrelease', 'delete', "releaseID=$releaseID&confirm=yes")));
        }
        else
        {
            $this->release->delete(TABLE_RELEASE, $releaseID);

            $release = $this->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq((int)$releaseID)->fetch();
            $build   = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->eq((int)$release->build)->fetch();
            if(empty($build->execution)) $this->loadModel('build')->delete(TABLE_BUILD, $build->id);

            $this->executeHooks($releaseID);

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
                    $release = $this->release->getById($releaseID);
                    $this->dao->update(TABLE_BUILD)->set('deleted')->eq(1)->where('id')->eq($release->build)->andWhere('name')->eq($release->name)->exec();
                }
                $this->send($response);
            }
            die(js::locate($this->session->releaseList, 'parent'));
        }
    }

    /**
     * Export the stories of release to HTML.
     *
     * @access public
     * @return void
     */
    public function export()
    {
        if(!empty($_POST))
        {
            $type = $this->post->type;
            $html = '';

            if($type == 'story' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->stories}</h3>";
                $this->loadModel('story');

                $stories = $this->dao->select('id, title')->from(TABLE_STORY)->where($this->session->storyQueryCondition)
                    ->beginIF($this->session->storyOrderBy != false)->orderBy($this->session->storyOrderBy)->fi()
                    ->fetchAll('id');

                foreach($stories as $story) $story->title = "<a href='" . common::getSysURL() . $this->createLink('story', 'view', "storyID=$story->id") . "' target='_blank'>$story->title</a>";

                $fields = array('id' => $this->lang->story->id, 'title' => $this->lang->story->title);
                $rows   = $stories;

                $html .= '<table><tr>';
                foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
                $html .= '</tr>';
                foreach($rows as $row)
                {
                    $html .= "<tr valign='top'>\n";
                    foreach($fields as $fieldName => $fieldLabel)
                    {
                        $fieldValue = isset($row->$fieldName) ? $row->$fieldName : '';
                        $html .= "<td><nobr>$fieldValue</nobr></td>\n";
                    }
                    $html .= "</tr>\n";
                }
                $html .= '</table>';
            }

            if($type == 'bug' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->bugs}</h3>";
                $this->loadModel('bug');

                $bugs = $this->dao->select('id, title')->from(TABLE_BUG)->where($this->session->linkedBugQueryCondition)
                    ->beginIF($this->session->bugOrderBy != false)->orderBy($this->session->bugOrderBy)->fi()
                    ->fetchAll('id');

                foreach($bugs as $bug) $bug->title = "<a href='" . common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id") . "' target='_blank'>$bug->title</a>";

                $fields = array('id' => $this->lang->bug->id, 'title' => $this->lang->bug->title);
                $rows   = $bugs;

                $html .= '<table><tr>';
                foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
                $html .= '</tr>';
                foreach($rows as $row)
                {
                    $html .= "<tr valign='top'>\n";
                    foreach($fields as $fieldName => $fieldLabel)
                    {
                        $fieldValue = isset($row->$fieldName) ? $row->$fieldName : '';
                        $html .= "<td><nobr>$fieldValue</nobr></td>\n";
                    }
                    $html .= "</tr>\n";
                }
                $html .= '</table>';
            }

            if($type == 'leftbug' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->generatedBugs}</h3>";

                $bugs = $this->dao->select('id, title')->from(TABLE_BUG)->where($this->session->leftBugsQueryCondition)
                    ->beginIF($this->session->bugOrderBy != false)->orderBy($this->session->bugOrderBy)->fi()
                    ->fetchAll('id');

                foreach($bugs as $bug) $bug->title = "<a href='" . common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id") . "' target='_blank'>$bug->title</a>";

                $fields = array('id' => $this->lang->bug->id, 'title' => $this->lang->bug->title);
                $rows   = $bugs;

                $html .= '<table><tr>';
                foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
                $html .= '</tr>';
                foreach($rows as $row)
                {
                    $html .= "<tr valign='top'>\n";
                    foreach($fields as $fieldName => $fieldLabel)
                    {
                        $fieldValue = isset($row->$fieldName) ? $row->$fieldName : '';
                        $html .= "<td><nobr>$fieldValue</nobr></td>\n";
                    }
                    $html .= "</tr>\n";
                }
                $html .= '</table>';
            }

            $html = "<html><head><meta charset='utf-8'><title>{$this->post->fileName}</title><style>table, th, td{font-size:12px; border:1px solid gray; border-collapse:collapse;}</style></head><body>$html</body></html>";
            die($this->fetch('file', 'sendDownHeader', array('fileName' => $this->post->fileName, 'html', $html)));
        }

        $this->display();
    }

    /**
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
    public function linkStory($releaseID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->projectrelease->linkStory($releaseID);
            die(js::locate(inlink('view', "releaseID=$releaseID&type=story"), 'parent'));
        }
        $this->session->set('storyList', inlink('view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), $this->app->openApp);

        $release = $this->projectrelease->getByID($releaseID);
        $build   = $this->loadModel('build')->getByID($release->build);
        $this->commonAction($release->project, $release->product);
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
        $this->config->product->search['actionURL'] = $this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getForProducts(array($release->product => $release->product));
        $this->config->product->search['params']['module']['values']  = $this->tree->getOptionMenu($release->product, $viewType = 'story', $startModuleID = 0);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = $this->lang->product->branch;
            $branches = array('' => '') + $this->loadModel('branch')->getPairs($release->product, 'noempty');
            if($release->branch) $branches = array('' => '', $release->branch => $branches[$release->branch]);
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($release->product, $release->branch, $queryID, 'id', $build->execution ? $build->execution : '', 'story', $release->stories, $pager);
        }
        else
        {
            $allStories = $this->story->getExecutionStories($build->execution, 0, 0, 't1.`order`_desc', 'byProduct', $release->product, 'story', $release->stories, $pager);
        }

        $this->view->allStories     = $allStories;
        $this->view->release        = $release;
        $this->view->releaseStories = empty($release->stories) ? array() : $this->story->getByList($release->stories);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->pager          = $pager;
        $this->view->projectID      = $release->project;
        $this->display();
    }

    /**
     * Unlink story
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($releaseID, $storyID)
    {
        $this->projectrelease->unlinkStory($releaseID, $storyID);

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
            $this->send($response);
        }
        die(js::reload('parent'));
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($releaseID)
    {
        $this->loadModel('release')->batchUnlinkStory($releaseID);
        die(js::locate($this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=story"), 'parent'));
    }

    /**
     * Link bugs.
     *
     * @param  int    $releaseID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug($releaseID = 0, $browseType = '', $param = 0, $type = 'bug', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->projectrelease->linkBug($releaseID, $type);
            die(js::locate(inlink('view', "releaseID=$releaseID&type=$type"), 'parent'));
        }

        $this->session->set('bugList', inlink('view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');
        /* Set menu. */
        $release = $this->projectrelease->getByID($releaseID);
        $build   = $this->loadModel('build')->getByID($release->build);
        $this->commonAction($release->project, $release->product);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('bug');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        unset($this->config->bug->search['fields']['product']);
        $this->config->bug->search['actionURL'] = $this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array($release->product => $release->product));
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($release->product, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($release->product, 0, 'id_desc', $release->project);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($release->product, $branch = 0, $params = '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = $this->lang->product->branch;
            $branches = array('' => '') + $this->loadModel('branch')->getPairs($release->product, 'noempty');
            if($release->branch) $branches = array('' => '', $release->branch => $branches[$release->branch]);
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $allBugs     = array();
        $releaseBugs = $type == 'bug' ? $release->bugs : $release->leftBugs;
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($release->product, $release->branch, $queryID, 'id_desc', $releaseBugs, $pager);
        }
        elseif($build->execution)
        {
            if($type == 'bug')
            {
                $allBugs = $this->bug->getReleaseBugs($build->id, $release->product, $release->branch, $releaseBugs, $pager);
            }
            elseif($type == 'leftBug')
            {
                $allBugs = $this->bug->getProductLeftBugs($build->id, $release->product, $release->branch, $releaseBugs, $pager);
            }
        }

        $this->view->allBugs     = $allBugs;
        $this->view->releaseBugs = empty($releaseBugs) ? array() : $this->bug->getByList($releaseBugs);
        $this->view->release     = $release;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->type        = $type;
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * Unlink story
     *
     * @param  int    $releaseID
     * @param  int    $bugID
     * @param  string $type
     * @access public
     * @return void
     */
    public function unlinkBug($releaseID, $bugID, $type = 'bug')
    {
        $this->loadModel('release')->unlinkBug($releaseID, $bugID, $type);

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
            $this->send($response);
        }
        die(js::reload('parent'));
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchUnlinkBug($releaseID, $type = 'bug')
    {
        $this->loadModel('release')->batchUnlinkBug($releaseID, $type);
        die(js::locate($this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=$type"), 'parent'));
    }

    /**
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeStatus($releaseID, $status)
    {
        $this->loadModel('release')->changeStatus($releaseID, $status);
        if(dao::isError()) die(js::error(dao::getError()));
        $actionID = $this->loadModel('action')->create('release', $releaseID, 'changestatus', '', $status);
        die(js::reload('parent'));
    }
}
