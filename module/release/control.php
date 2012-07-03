<?php
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
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
        $this->session->set('releaseList', $this->app->getURI(true));
        $this->view->header->title = $this->lang->release->browse;
        $this->view->position[]    = $this->lang->release->browse;
        $this->view->releases      = $this->release->getList($productID);
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
        $this->view->header->title = $this->lang->release->create;
        $this->view->position[]    = $this->lang->release->create;
        $this->view->builds        = $builds;
        $this->view->productID     = $productID;
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
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'edited');
                $this->action->logHistory($actionID, $changes);
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

        /* Get stories and bugs. */
        $orderBy = 'status_asc, stage_asc, id_desc';
        $stories = $this->story->getProjectStories($build->project, $orderBy);
        $bugs    = $this->bug->getProjectBugs($build->project); 

        $this->view->header->title = $this->lang->release->edit;
        $this->view->position[]    = $this->lang->release->edit;
        $this->view->release       = $release;
        $this->view->build         = $build;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->builds        = $this->loadModel('build')->getProductBuildPairs($release->product);
        unset($this->view->builds['trunk']);
        $this->display();
    }
                                                          
    /**
     * View a release.
     * 
     * @param  int    $releaseID 
     * @access public
     * @return void
     */
    public function view($releaseID)
    {
        $this->loadModel('story');
        $this->loadModel('bug');

        $release = $this->release->getById((int)$releaseID, true);
        if(!$release) die(js::error($this->lang->notFound) . js::locate('back'));

        $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($release->stories)->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');

        $bugs    = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->bugs)->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        $this->commonAction($release->product);

        $this->view->header->title = $this->lang->release->view;
        $this->view->position[]    = $this->lang->release->view;
        $this->view->release       = $release;
        $this->view->stories       = $stories;
        $this->view->bugs          = $bugs;
        $this->view->actions       = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
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
            die(js::locate($this->session->releaseList, 'parent'));
        }
    }

    /**
     * Ajax get stories and bugs 
     * 
     * @param  int    $buildID 
     * @access public
     * @return void
     */
    public function ajaxGetStoriesAndBugs($buildID, $productID)
    {
        $orderBy = 'status_asc,stage_asc,id_desc';
        $stories = array();
        $bugs    = array();
        $this->loadModel('bug');
        $this->loadModel('story');
        $build = $this->dao->select('project')->from(TABLE_BUILD)->where('id')->eq($buildID)->fetch(); 
        if(!empty($build))
        {
            $stories = $this->story->getProjectStories($build->project, $orderBy);
            $bugs    = $this->bug->getProjectBugs($build->project);
        }
        $this->view->productID = $productID;
        $this->view->stories   = $stories;
        $this->view->bugs      = $bugs;
        $this->view->orderBy   = $orderBy;
        die($this->display());
    }

    /**
     * Export the stories of release to HTML.
     * 
     * @param  string $type story | bug
     * @access public
     * @return void
     */
    public function export($type)
    {
        if(!empty($_POST))
        {
            if($type == 'story')
            {
                $this->loadModel('story');

                $stories = $this->dao->select('id, title')->from(TABLE_STORY)->where($this->session->storyQueryCondition)
                    ->beginIF($this->session->storyOrderBy != false)->orderBy($this->session->storyOrderBy)->fi()
                    ->fetchAll('id');

                foreach($stories as $story)
                {
                    $story->title = "<a href='" . common::getSysURL() . $this->createLink('story', 'view', "storyID=$story->id") . "' target='_blank'>$story->title</a>";
                }

                $this->post->set('fields', array('id' => $this->lang->story->id, 'title' => $this->lang->story->title));
                $this->post->set('rows', $stories);
                $this->fetch('file', 'export2HTML', $_POST);
            }
            else if($type == 'bug')
            {
                $this->loadModel('bug');

                $bugs = $this->dao->select('id, title')->from(TABLE_BUG)->where($this->session->bugQueryCondition)
                    ->beginIF($this->session->bugOrderBy != false)->orderBy($this->session->bugOrderBy)->fi()
                    ->fetchAll('id');

                foreach($bugs as $bug)
                {
                    $bug->title = "<a href='" . common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id") . "' target='_blank'>$bug->title</a>";
                }

                $this->post->set('fields', array('id' => $this->lang->bug->id, 'title' => $this->lang->bug->title));
                $this->post->set('rows', $bugs);
                $this->fetch('file', 'export2HTML', $_POST);
            }
        }

        $this->display();
    }
}
