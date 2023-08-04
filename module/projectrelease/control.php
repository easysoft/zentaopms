<?php
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        /* Get product and product list by project. */
        $this->products = $this->product->getProductPairsByProject($projectID);
        if(empty($this->products)) return print($this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=projectrelease&projectID=' . $projectID)));
        if(!$productID) $productID = key($this->products);
        $product = $this->product->getById($productID);

        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = (isset($product->type) and $product->type == 'normal') ? array() : $this->loadModel('branch')->getPairs($productID, 'active', $projectID);
        $this->view->branch   = $branch;
        $this->view->project  = $this->project->getByID($projectID);
    }

    /**
     * Browse releases.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $executionID = 0, $type = 'all', $orderBy = 't1.date_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('releaseList', $uri, 'project');
        $this->session->set('buildList', $uri);

        $project   = $this->project->getById($projectID);
        $execution = $this->loadModel('execution')->getById($executionID);

        if($projectID) $this->project->setMenu($projectID);
        if($executionID) $this->loadModel('execution')->setMenu($executionID, $this->app->rawModule, $this->app->rawMethod);

        $objectName = isset($project->name) ? $project->name : $execution->name;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $releases = $this->projectrelease->getList($projectID, $type, $orderBy, $pager);

        $showBranch = false;
        foreach($releases as $release)
        {
            if($release->productType != 'normal')
            {
                $showBranch = true;
                break;
            }
        }

        $this->view->title       = $objectName . $this->lang->colon . $this->lang->release->browse;
        $this->view->execution   = $execution;
        $this->view->project     = $project;
        $this->view->products    = $this->loadModel('product')->getProducts($projectID);
        $this->view->releases    = $releases;
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->type        = $type;
        $this->view->from        = $this->app->tab;
        $this->view->pager       = $pager;
        $this->view->showBranch  = $showBranch;
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
        /* Load module and config. */
        $this->loadModel('build');
        $this->app->loadConfig('release');
        $this->config->projectrelease->create = $this->config->release->create;
        $this->app->loadLang('release');

        if(!empty($_POST))
        {
            $releaseID = $this->release->create(0, 0, $projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('release', $releaseID, 'opened');

            $message = $this->executeHooks($releaseID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $releaseID));

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }

        $this->project->setMenu($projectID);
        $this->commonAction($projectID);

        /* Get the builds that can select. */
        $builds         = $this->build->getBuildPairs($this->view->product->id, 'all', 'notrunk|withbranch|hasproject', $projectID, 'project', '', false);
        $releasedBuilds = $this->projectrelease->getReleasedBuilds($projectID);
        foreach($releasedBuilds as $build) unset($builds[$build]);

        $this->view->title          = $this->view->project->name . $this->lang->colon . $this->lang->release->create;
        $this->view->projectID      = $projectID;
        $this->view->builds         = $builds;
        $this->view->lastRelease    = $this->projectrelease->getLast($projectID);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed');
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
        /* Load module and config. */
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('build');
        $this->loadModel('release');
        $this->config->projectrelease->create = $this->config->release->create;

        if(!empty($_POST))
        {
            $changes = $this->release->update($releaseID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $files = $this->loadModel('file')->saveUpload('release', $releaseID);
            if($changes or $files)
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'Edited', $fileAction);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($releaseID);
            if($message) $this->lang->saveSuccess = $message;

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }

        /* Get release and build. */
        $release = $this->projectrelease->getById((int)$releaseID);
        if(!$this->session->project)
        {
            $releaseProject = explode(',', $release->project);
            $this->session->set('project', $releaseProject[0], 'project');
        }

        $this->commonAction($this->session->project, $release->product, $release->branch);
        $bindBuilds = $this->build->getByList($release->build);

        /* Get the builds that can select. */
        $builds         = $this->build->getBuildPairs($release->product, $release->branch, 'notrunk|withbranch|hasproject', $this->session->project, 'project', $release->build, false);
        $releasedBuilds = $this->projectrelease->getReleasedBuilds($this->session->project);
        foreach($releasedBuilds as $releasedBuild)
        {
            foreach(explode(',', trim($releasedBuild, ',')) as $bindBuildID)
            {
                if(!isset($bindBuilds[$bindBuildID])) unset($builds[$bindBuildID]);
            }
        }

        /* Set project menu. */
        $this->project->setMenu($this->session->project);

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->release->edit;
        $this->view->position[] = $this->lang->release->edit;
        $this->view->release    = $release;
        $this->view->builds     = $builds;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');

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
        $this->loadModel('story');
        $this->loadModel('bug');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $release = $this->projectrelease->getByID((int)$releaseID, true);
        if(!$release)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate('back'));
        }

        $uri = $this->app->getURI(true);
        if($release->build)  $this->session->set('buildList', $uri, 'project');
        if($type == 'story') $this->session->set('storyList', $uri, $this->app->tab);
        if($type == 'bug' or $type == 'leftBug') $this->session->set('bugList', $uri, $this->app->tab);

        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $sort .= ',buildID_asc';

        $storyPager = new pager($type == 'story' ? $recTotal : 0, $recPerPage, $type == 'story' ? $pageID : 1);
        $stories    = $this->dao->select("t1.*,t2.id as buildID, t2.name as buildName,IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on("FIND_IN_SET(t1.id, t2.stories)")
            ->where('t1.id')->in($release->stories)
            ->andWhere('t1.deleted')->eq(0)
            ->groupBy('t1.id')
            ->beginIF($type == 'story')->orderBy($sort)->fi()
            ->page($storyPager, 't1.id')
            ->fetchAll('id');
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in(array_keys($stories))->andWhere('branch')->eq($release->branch)->fetchPairs('story', 'stage');
        foreach($stages as $storyID => $stage) $stories[$storyID]->stage = $stage;

        $bugPager = new pager($type == 'bug' ? $recTotal : 0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $sort = common::appendOrder($orderBy);
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('id')->in($release->bugs)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'bug')->orderBy($sort)->fi()
            ->page($bugPager)
            ->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'linkedBug');

        $sort = common::appendOrder($orderBy);
        if($type == 'leftBug' and strpos($orderBy, 'severity_') !== false) $sort = str_replace('severity_', 'severityOrder_', $sort);
        $leftBugPager = new pager($type == 'leftBug' ? $recTotal : 0, $recPerPage, $type == 'leftBug' ? $pageID : 1);

        $leftBugs = $this->dao->select("*, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('id')->in($release->leftBugs)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'leftBug')->orderBy($sort)->fi()
            ->page($leftBugPager)
            ->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'leftBugs');

        $this->commonAction($this->session->project, $release->product);
        $product = $this->product->getById($release->product);

        /* Set menu. */
        $this->project->setMenu($this->session->project);

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
        $this->view->builds       = $this->loadModel('build')->getBuildPairs($release->product, 'all', 'withbranch|hasproject|hasdeleted', 0, 'execution', '', true);
        $this->view->summary      = $this->product->summary($stories);
        $this->view->storyCases   = $this->loadModel('testcase')->getStoryCaseCounts(array_keys($stories));

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->storyPager);
            unset($this->view->bugPager);
            unset($this->view->leftBugPager);
        }

        $this->display();
    }

    /**
     * Notify for release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function notify($releaseID)
    {
        if($_POST)
        {
            if(isset($_POST['notify']))
            {
                $notify = implode(',', $this->post->notify);
                $this->dao->update(TABLE_RELEASE)->set('notify')->eq($notify)->where('id')->eq($releaseID)->exec();

                $this->release->sendmail($releaseID);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $release = $this->release->getByID($releaseID);
        $project = $this->loadModel('project')->getByID($this->session->project);

        if(!$project->hasProduct)
        {
            unset($this->lang->release->notifyList['FB']);
            unset($this->lang->release->notifyList['PO']);
            unset($this->lang->release->notifyList['QD']);
        }

        if(!$project->multiple) unset($this->lang->release->notifyList['ET']);

        $this->view->release    = $release;
        $this->view->actions    = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->notifyList = $this->lang->release->notifyList;
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
            return print(js::confirm($this->lang->release->confirmDelete, $this->createLink('projectrelease', 'delete', "releaseID=$releaseID&confirm=yes")));
        }
        else
        {
            $this->loadModel('build');
            $this->release->delete(TABLE_RELEASE, $releaseID);

            $release = $this->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq((int)$releaseID)->fetch();
            $builds  = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');
            $this->loadModel('build')->delete(TABLE_BUILD, $release->shadow);
            foreach($builds as $build)
            {
                if(empty($build->execution) and $build->createdDate == $release->createdDate) $this->build->delete(TABLE_BUILD, $build->id);
            }

            $message = $this->executeHooks($releaseID);
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
            return print(js::locate($this->session->releaseList, 'parent'));
        }
    }

    /**
     * Export the stories of release to HTML.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function export($releaseID)
    {
        if(!empty($_POST))
        {
            $release = $this->loadModel('release')->getByID($releaseID);
            $type    = $this->post->type;
            $html    = '';

            if($type == 'story' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->stories}</h3>";

                if(!empty($release->stories))
                {
                    $this->loadModel('story');
                    $stories = $this->dao->select('id, title')->from(TABLE_STORY)
                        ->where('id')->in($release->stories)
                        ->andWhere('deleted')->eq(0)
                        ->fetchAll();

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
            }

            if($type == 'bug' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->bugs}</h3>";

                if(!empty($release->bugs))
                {
                    $this->loadModel('bug');
                    $bugs = $this->dao->select('id, title')->from(TABLE_BUG)
                        ->where('id')->in($release->bugs)
                        ->andWhere('deleted')->eq(0)
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
            }

            if($type == 'leftbug' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->generatedBugs}</h3>";

                if(!empty($release->leftBugs))
                {
                    $bugs = $this->dao->select('id, title')->from(TABLE_BUG)
                        ->where(id)->in($release->leftBugs)
                        ->andWhere('deleted')->eq(0)
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
            }

            $html = "<html><head><meta charset='utf-8'><title>{$this->post->fileName}</title><style>table, th, td{font-size:12px; border:1px solid gray; border-collapse:collapse;}</style></head><body>$html</body></html>";
            return print($this->fetch('file', 'sendDownHeader', array('fileName' => $this->post->fileName, 'html', $html)));
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
            return print(js::locate(inlink('view', "releaseID=$releaseID&type=story"), 'parent'));
        }
        $this->session->set('storyList', inlink('view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), $this->app->tab);

        $release = $this->projectrelease->getByID($releaseID);
        if(!$this->session->project)
        {
            $releaseProject = explode(',', $release->project);
            $this->session->set('project', $releaseProject[0], 'project');
        }

        $builds  = $this->loadModel('build')->getByList($release->build);
        $project = $this->loadModel('project')->getByID($this->session->project);
        $this->commonAction($this->session->project, $release->product);
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
        if(!$project->hasProduct and !$project->multiple) unset($this->config->product->search['fields']['plan']);
        $this->config->product->search['actionURL'] = $this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getPairsForStory($release->product, $release->branch, 'skipParent|withMainPlan');
        $this->config->product->search['params']['status']         = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        $searchModules = array();
        $moduleGroups  = $this->loadModel('tree')->getOptionMenu($release->product, 'story', 0, explode(',', $release->branch));
        foreach($moduleGroups as $modules) $searchModules += $modules;
        $this->config->product->search['params']['module']['values'] = $searchModules;

        if($release->productType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$release->productType]);
            $allBranchs = $this->loadModel('branch')->getPairs($release->product);
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main);
            foreach(explode(',', trim($release->branch, ',')) as $branchID) $branches[$branchID] = zget($allBranchs, $branchID);
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        if($this->view->project->model == 'waterfall' && empty($this->view->project->hasProduct)) unset($this->config->product->search['fields']['plan']);
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        $executionIdList = array();
        foreach($builds as $build) $executionIdList[] = empty($build->execution) ? $build->project : $build->execution;
        $executionIdList = array_unique($executionIdList);

        $allStories = array();
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($release->product, $release->branch, $queryID, 'id', $executionIdList, 'story', $release->stories, 'draft,reviewing,changing', $pager);
        }
        else
        {
            $allStories = $this->story->getExecutionStories($executionIdList, $release->product, 0, 't1.`order`_desc', 'byBranch', $release->branch, 'story', $release->stories, 'draft,reviewing,changing', $pager);
        }

        $this->view->allStories     = $allStories;
        $this->view->release        = $release;
        $this->view->releaseStories = empty($release->stories) ? array() : $this->story->getByList($release->stories);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->pager          = $pager;
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
    public function unlinkStory($releaseID, $storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->release->confirmUnlinkStory, inlink('unlinkstory', "releaseID=$releaseID&storyID=$storyID&confirm=yes")));
        }
        else
        {
            $this->projectrelease->unlinkStory($releaseID, $storyID);
            return print(js::reload('parent'));
        }
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
        return print(js::locate($this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=story"), 'parent'));
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
            return print(js::locate(inlink('view', "releaseID=$releaseID&type=$type"), 'parent'));
        }

        $this->session->set('bugList', inlink('view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');
        /* Set menu. */
        $release = $this->projectrelease->getByID($releaseID);
        if(!$this->session->project)
        {
            $releaseProject = explode(',', $release->project);
            $this->session->set('project', $releaseProject[0], 'project');
        }

        $builds  = $this->loadModel('build')->getByList($release->build);
        $project = $this->loadModel('project')->getByID($this->session->project);
        $this->commonAction($this->session->project, $release->product);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('bug');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['fields']['project']);
        if(!$project->hasProduct and !$project->multiple) unset($this->config->bug->search['fields']['plan']);
        $this->config->bug->search['actionURL'] = $this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairsForStory($release->product, $release->branch, 'skipParent|withMainPlan');
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($release->product, $release->branch, 'id_desc', $release->project);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($release->product, $branch = 0);
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        $searchModules = array();
        $moduleGroups  = $this->loadModel('tree')->getOptionMenu($release->product, 'bug', 0, explode(',', $release->branch));
        foreach($moduleGroups as $modules) $searchModules += $modules;
        $this->config->bug->search['params']['module']['values'] = $searchModules;

        if($release->productType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$release->productType]);
            $allBranchs = $this->loadModel('branch')->getPairs($release->product);
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main);
            foreach(explode(',', trim($release->branch, ',')) as $branchID) $branches[$branchID] = zget($allBranchs, $branchID);
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        if($this->view->project->model == 'waterfall' && empty($this->view->project->hasProduct)) unset($this->config->bug->search['fields']['plan']);
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $allBugs     = array();
        $releaseBugs = $type == 'bug' ? $release->bugs : $release->leftBugs;
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($release->product, $release->branch, $queryID, 'id_desc', $releaseBugs, $pager);
        }
        else
        {
            if($type == 'bug')
            {
                $allBugs = $this->bug->getReleaseBugs(array_keys($builds), $release->product, $release->branch, $releaseBugs, $pager);
            }
            elseif($type == 'leftBug')
            {
                $allBugs = $this->bug->getProductLeftBugs(array_keys($builds), $release->product, $release->branch, $releaseBugs, $pager);
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
            return $this->send($response);
        }
        return print(js::reload('parent'));
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
        return print(js::locate($this->createLink('projectrelease', 'view', "releaseID=$releaseID&type=$type"), 'parent'));
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
        if(dao::isError()) return print(js::error(dao::getError()));
        $actionID = $this->loadModel('action')->create('release', $releaseID, 'changestatus', '', $status);
        return print(js::reload('parent'));
    }

    /**
     * Ajax load builds.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxLoadBuilds($projectID, $productID)
    {
        $builds         = $this->loadModel('build')->getBuildPairs($productID, 'all', 'notrunk,withbranch,hasproject', $projectID, 'project', '', false);
        $releasedBuilds = $this->projectrelease->getReleasedBuilds($projectID);
        foreach($releasedBuilds as $build) unset($builds[$build]);

        return print(html::select('build[]', $builds, '', "class='form-control chosen' multiple"));
    }
}
