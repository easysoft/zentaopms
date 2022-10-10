<?php
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: control.php 4178 2013-01-20 09:32:11Z wwccss $
 * @link        http://www.zentao.net
 */
class release extends control
{
    /**
     * Common actions.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function commonAction($productID, $branch = 0)
    {
        $this->loadModel('product')->setMenu($productID, $branch);

        $product = $this->product->getById($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'create'));
        $this->view->product  = $product;
        $this->view->branch   = $branch;
        $this->view->branches = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}&branch=$branch"), $this->view->product->name);
    }

    /**
     * Browse releases.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function browse($productID, $branch = 'all', $type = 'all', $orderBy = 't1.date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $this->commonAction($productID, $branch);
        $this->session->set('releaseList', $this->app->getURI(true), 'product');

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->release->browse;
        $this->view->position[] = $this->lang->release->browse;
        $this->view->releases   = $this->release->getList($productID, $branch, $type, $orderBy, $pager);
        $this->view->type       = $type;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Create a release.
     *
     * @param  int        $productID
     * @param  string|int $branch
     * @access public
     * @return void
     */
    public function create($productID, $branch = 'all')
    {
        if(!empty($_POST))
        {
            $releaseID = $this->release->create($productID, $branch);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('release', $releaseID, 'opened');

            $message = $this->executeHooks($releaseID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $releaseID));
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.loadProductBuilds($productID)"));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }

        $builds         = $this->loadModel('build')->getBuildPairs($productID, $branch, 'notrunk|withbranch', 0, 'execution', '', false);
        $releasedBuilds = $this->release->getReleasedBuilds($productID, $branch);
        foreach($releasedBuilds as $build) unset($builds[$build]);
        unset($builds['trunk']);

        /* Get the builds of the linked stories or bugs. */
        $notEmptyBuilds = array();
        $buildList      = $this->build->getByList(array_keys($builds));
        foreach($buildList as $build)
        {
            if(!empty($build->stories) or !empty($build->bugs)) $notEmptyBuilds[$build->id] = $build->id;
        }

        $this->commonAction($productID, $branch);
        $this->view->title          = $this->view->product->name . $this->lang->colon . $this->lang->release->create;
        $this->view->position[]     = $this->lang->release->create;
        $this->view->productID      = $productID;
        $this->view->builds         = $builds;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed');
        $this->view->lastRelease    = $this->release->getLast($productID, $branch);
        $this->view->notEmptyBuilds = $notEmptyBuilds;

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
        if(!empty($_POST))
        {
            $changes = $this->release->update($releaseID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($releaseID);
            if($message) $this->lang->saveSuccess = $message;

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('build');

        /* Get release and build. */
        $release = $this->release->getById((int)$releaseID);
        $this->commonAction($release->product, $release->branch);
        $build = $this->build->getById($release->build);

        $builds         = $this->loadModel('build')->getBuildPairs($release->product, $release->branch, 'notrunk|withbranch', $release->project, 'project', $release->build, false);
        $releasedBuilds = $this->release->getReleasedBuilds($release->product, $release->branch);
        foreach($releasedBuilds as $releasedBuild)
        {
            if($releasedBuild != $build->id) unset($builds[$releasedBuild]);
        }
        unset($builds['trunk']);

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->release->edit;
        $this->view->position[] = $this->lang->release->edit;
        $this->view->release    = $release;
        $this->view->build      = $build;
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
        $releaseID = (int)$releaseID;
        $release   = $this->release->getByID($releaseID, true);
        if(!$release) return print(js::error($this->lang->notFound) . js::locate($this->createLink('product', 'index')));

        if($type == 'story') $this->session->set('storyList', $this->app->getURI(true), 'product');
        if($type == 'bug' or $type == 'leftBug') $this->session->set('bugList', $this->app->getURI(true), 'qa');

        $this->loadModel('story');
        $this->loadModel('bug');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $storyPager = new pager($type == 'story' ? $recTotal : 0, $recPerPage, $type == 'story' ? $pageID : 1);
        $stories = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('id')->in($release->stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'story')->orderBy($sort)->fi()
            ->page($storyPager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');
        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in(array_keys($stories))->andWhere('branch')->eq($release->branch)->fetchPairs('story', 'stage');
        foreach($stages as $storyID => $stage)$stories[$storyID]->stage = $stage;

        $bugPager = new pager($type == 'bug' ? $recTotal : 0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('id')->in($release->bugs)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'bug')->orderBy($sort)->fi()
            ->page($bugPager)
            ->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'linkedBug');

        $leftBugPager = new pager($type == 'leftBug' ? $recTotal : 0, $recPerPage, $type == 'leftBug' ? $pageID : 1);
        if($type == 'leftBug' and strpos($orderBy, 'severity_') !== false) $sort = str_replace('severity_', 'severityOrder_', $sort);

        $leftBugs = $this->dao->select("*, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('id')->in($release->leftBugs)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type == 'leftBug')->orderBy($sort)->fi()
            ->page($leftBugPager)
            ->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'leftBugs');

        $this->commonAction($release->product);
        $product = $this->product->getById($release->product);

        $this->executeHooks($releaseID);

        $this->view->title         = "RELEASE #$release->id $release->name/" . $product->name;
        $this->view->position[]    = $this->lang->release->view;
        $this->view->release       = $release;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->leftBugs      = $leftBugs;
        $this->view->actions       = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->type          = $type;
        $this->view->link          = $link;
        $this->view->param         = $param;
        $this->view->orderBy       = $orderBy;
        $this->view->branchName    = $release->productType == 'normal' ? '' : $this->loadModel('branch')->getById($release->branch);
        $this->view->storyPager    = $storyPager;
        $this->view->bugPager      = $bugPager;
        $this->view->leftBugPager  = $leftBugPager;

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
                $this->loadModel('action')->create('release', $releaseID, 'notified');
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->release = $this->release->getById($releaseID);
        $this->view->actions = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed');
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
            return print(js::confirm($this->lang->release->confirmDelete, $this->createLink('release', 'delete', "releaseID=$releaseID&confirm=yes")));
        }
        else
        {
            $this->release->delete(TABLE_RELEASE, $releaseID);

            $release = $this->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq((int)$releaseID)->fetch();
            $build   = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->eq((int)$release->build)->fetch();
            if(empty($build->execution)) $this->loadModel('build')->delete(TABLE_BUILD, $build->id);

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
                    $release = $this->release->getById($releaseID);
                    $this->dao->update(TABLE_BUILD)->set('deleted')->eq(1)->where('id')->eq($release->build)->andWhere('name')->eq($release->name)->exec();
                }

                return $this->send($response);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));

            $locateLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID={$release->product}");
            return print(js::locate($locateLink, 'parent'));
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
            $this->release->linkStory($releaseID);
            return print(js::locate(inlink('view', "releaseID=$releaseID&type=story"), 'parent'));
        }
        $this->session->set('storyList', inlink('view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'product');

        $release = $this->release->getById($releaseID);
        $build   = $this->loadModel('build')->getByID($release->build);
        $this->commonAction($release->product);
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
        $this->config->product->search['actionURL'] = $this->createLink('release', 'view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getPairsForStory($release->product, $release->branch, 'skipParent|withMainPlan');
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);
        $this->config->product->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($release->product, 'story', 0, $release->branch);;
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = $this->lang->product->branch;
            $branchName = $this->loadModel('branch')->getById($release->branch);
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main, $release->branch => $branchName);
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        if($browseType == 'bySearch' or $build->execution == 0)
        {
            $allStories = $this->story->getBySearch($release->product, "0,{$release->branch}", $queryID, 'id', $build->execution ? $build->execution : '', 'story', $release->stories, $pager);
        }
        else
        {
            $allStories = $this->story->getExecutionStories($build->execution, $build->product, 0, 't1.`order`_desc', 'byBranch', $release->branch, 'story', $release->stories, $pager);
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
    public function unlinkStory($releaseID, $storyID)
    {
        $this->release->unlinkStory($releaseID, $storyID);

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
        echo js::reload('parent');
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
        $this->release->batchUnlinkStory($releaseID);
        echo js::locate($this->createLink('release', 'view', "releaseID=$releaseID&type=story"), 'parent');
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
            $this->release->linkBug($releaseID, $type);
            return print(js::locate(inlink('view', "releaseID=$releaseID&type=$type"), 'parent'));
        }

        $this->session->set('bugList', inlink('view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');
        /* Set menu. */
        $release = $this->release->getByID($releaseID);
        $build   = $this->loadModel('build')->getByID($release->build);
        $this->commonAction($release->product);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('bug');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['fields']['project']);
        $this->config->bug->search['actionURL'] = $this->createLink('release', 'view', "releaseID=$releaseID&type=$type&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairsForStory($release->product, $release->branch, 'skipParent|withMainPlan');
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($release->product, $release->branch);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($release->product, $branch = 'all', $params = '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($release->product, 'bug', 0, $release->branch);
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = $this->lang->product->branch;
            $branchName = $this->loadModel('branch')->getById($release->branch);
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main, $release->branch => $branchName);
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $allBugs     = array();
        $releaseBugs = $type == 'bug' ? $release->bugs : $release->leftBugs;
        if($browseType == 'bySearch' or $build->execution == 0)
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
        $this->release->unlinkBug($releaseID, $bugID, $type);

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
        echo js::reload('parent');
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
        $this->release->batchUnlinkBug($releaseID, $type);
        echo js::locate($this->createLink('release', 'view', "releaseID=$releaseID&type=$type"), 'parent');
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
        $this->release->changeStatus($releaseID, $status);
        if(dao::isError()) return print(js::error(dao::getError()));
        $actionID = $this->loadModel('action')->create('release', $releaseID, 'changestatus', '', $status);
        echo js::reload('parent');
    }
}
