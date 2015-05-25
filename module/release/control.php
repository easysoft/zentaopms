<?php
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
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
     * @access public
     * @return void
     */
    public function commonAction($productID)
    {
        $this->loadModel('product');
        $this->view->product = $this->product->getById($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID);
    }

    /**
     * Browse releases.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function browse($productID)
    {
        $this->commonAction($productID);
        $products                  = $this->product->getPairs();
        $this->session->set('releaseList', $this->app->getURI(true));
        $this->view->title      = $products[$productID] . $this->lang->colon . $this->lang->release->browse;
        $this->view->position[] = $this->lang->release->browse;
        $this->view->releases   = $this->release->getList($productID);
        $this->display();
    }

    /**
     * Create a release.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function create($productID)
    {
        if(!empty($_POST))
        {
            $releaseID = $this->release->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('release', $releaseID, 'opened');
            die(js::locate(inlink('view', "releaseID=$releaseID"), 'parent'));
        }

        $builds        = $this->loadModel('build')->getProductBuildPairs($productID);
        $releaseBuilds = $this->release->getReleaseBuilds($productID);
        foreach($releaseBuilds as $build) unset($builds[$build]);
        unset($builds['trunk']);

        $this->commonAction($productID);
        $this->view->title       = $this->view->product->name . $this->lang->colon . $this->lang->release->edit;
        $this->view->position[]  = $this->lang->release->create;
        $this->view->builds      = $builds;
        $this->view->productID   = $productID;
        $this->view->lastRelease = $this->release->getLast($productID);
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
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('release', $releaseID);
            if($changes or $files)
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'Edited', $fileAction);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "releaseID=$releaseID"), 'parent'));
        }
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('build');

        /* Get release and build. */
        $release = $this->release->getById((int)$releaseID);
        $this->commonAction($release->product);
        $build = $this->build->getById($release->build);

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->release->edit;
        $this->view->position[] = $this->lang->release->edit;
        $this->view->release    = $release;
        $this->view->build      = $build;
        $this->view->builds     = $this->loadModel('build')->getProductBuildPairs($release->product, 'notrunk');
        $this->display();
    }
                                                          
    /**
     * View a release.
     * 
     * @param  int    $releaseID 
     * @access public
     * @return void
     */
    public function view($releaseID, $type = 'story', $link = 'false', $param = '')
    {
        if($type == 'story') $this->session->set('storyList', $this->app->getURI(true));
        if($type == 'bug')    $this->session->set('bugList', $this->app->getURI(true));

        $this->loadModel('story');
        $this->loadModel('bug');

        $release = $this->release->getById((int)$releaseID, true);
        if(!$release) die(js::error($this->lang->notFound) . js::locate('back'));

        $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($release->stories)->andWhere('deleted')->eq(0)->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');

        $bugs    = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->bugs)->andWhere('deleted')->eq(0)->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'linkedBug');

        $build = $this->loadModel('build')->getById($release->build);
        $generatedBugs = array();
        if($build->project)
        {
            $generatedBugs = $this->dao->select('*')->from(TABLE_BUG)
                ->where('project')->eq((int)$build->project)
                ->beginIF($build->id != 0)->andWhere('openedBuild')->eq($build->id)->fi()
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->eq('active')
                ->orderBy('id_desc')
                ->fetchAll();
            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'newBugs');
        }
        

        $this->commonAction($release->product);
        $products = $this->product->getPairs();

        $this->view->title         = "RELEASE #$release->id $release->name/" . $products[$release->product];
        $this->view->position[]    = $this->lang->release->view;
        $this->view->release       = $release;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->generatedBugs = empty($build->project) ? array() : $this->bug->getProjectBugs($build->project, 'id_desc', null, $build->id);
        $this->view->actions       = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->type          = $type;
        $this->view->link          = $link;
        $this->view->param         = $param;
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
            die(js::confirm($this->lang->release->confirmDelete, $this->createLink('release', 'delete', "releaseID=$releaseID&confirm=yes")));
        }
        else
        {
            $this->release->delete(TABLE_RELEASE, $releaseID);

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
            die(js::locate($this->session->releaseList, 'parent'));
        }
    }

    /**
     * Export the stories of release to HTML.
     * 
     * @param  string $type story | bug
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

            if($type == 'newbug' or $type == 'all')
            {
                $html .= "<h3>{$this->lang->release->generatedBugs}</h3>";

                $bugs = $this->dao->select('id, title')->from(TABLE_BUG)->where($this->session->newBugsQueryCondition)
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
     * @access public
     * @return void
     */
    public function linkStory($releaseID = 0, $browseType = '', $param = 0)
    {
        if(!empty($_POST['stories']))
        {
            $this->release->linkStory($releaseID);
            die(js::locate(inlink('view', "releaseID=$releaseID&type=story"), 'parent'));
        }

        $release = $this->release->getById($releaseID);
        $build   = $this->loadModel('build')->getByID($release->build); 
        $this->commonAction($release->product);
        $this->loadModel('story');
        $this->loadModel('tree');
        $this->loadModel('product');

        /* Build search form. */
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        unset($this->config->product->search['fields']['product']);
        unset($this->config->product->search['fields']['project']);
        $this->config->product->search['actionURL'] = $this->createLink('release', 'view', "releaseID=$releaseID&type=story&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getForProducts(array($release->product => $release->product));
        $this->config->product->search['params']['module']['values']  = $this->tree->getOptionMenu($release->product, $viewType = 'story', $startModuleID = 0);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($release->product, $queryID, 'id', null, $build->project ? $build->project : '');
        }
        else
        {
            $allStories = $this->story->getProjectStories($build->project);
        }

        $this->view->allStories     = $allStories;
        $this->view->release        = $release;
        $this->view->releaseStories = empty($release->stories) ? array() : $this->story->getByList($release->stories);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
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
        $this->release->batchUnlinkStory($releaseID);
        die(js::locate($this->createLink('release', 'view', "releaseID=$releaseID&type=story"), 'parent'));
    }

    /**
     * Link bugs.
     * 
     * @param  int    $releaseID 
     * @param  string $browseType 
     * @param  int    $param 
     * @access public
     * @return void
     */
    public function linkBug($releaseID = 0, $browseType = '', $param = 0)
    {
        if(!empty($_POST['bugs']))
        {
            $this->release->linkBug($releaseID);
            die(js::locate(inlink('view', "releaseID=$releaseID&type=bug"), 'parent'));
        }

        /* Set menu. */
        $release = $this->release->getByID($releaseID);
        $build   = $this->loadModel('build')->getByID($release->build); 
        $this->commonAction($release->product);

        /* Build the search form. */
        $this->loadModel('bug');
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        unset($this->config->bug->search['fields']['product']);
        $this->config->bug->search['actionURL'] = $this->createLink('release', 'view', "releaseID=$releaseID&type=bug&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array($release->product => $release->product));
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($release->product, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['project']['values']       = $this->loadModel('product')->getProjectPairs($release->product);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($release->product);
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($release->product, $queryID, 'id_desc');
        }
        else
        {
            $allBugs = empty($build->project) ? array() : $this->bug->getReleaseBugs($build->id, $release->product);
        }

        $this->view->allBugs     = $allBugs;
        $this->view->releaseBugs = empty($release->bugs) ? array() : $this->bug->getByList($release->bugs);
        $this->view->release     = $release;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->display();
    }

    /**
     * Unlink story 
     * 
     * @param  int    $releaseID
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function unlinkBug($releaseID, $bugID)
    {
        $this->release->unlinkBug($releaseID, $bugID);

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
     * @param  int $releaseID 
     * @access public
     * @return void
     */
    public function batchUnlinkBug($releaseID)
    {
        $this->release->batchUnlinkBug($releaseID);
        die(js::locate($this->createLink('release', 'view', "releaseID=$releaseID&type=bug"), 'parent'));
    }
}
