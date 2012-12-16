<?php
/**
 * The control file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class story extends control
{
    /**
     * The construct function, load product, tree, user auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('project');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
    }

    /**
     * Create a story.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function create($productID = 0, $moduleID = 0, $storyID = 0, $projectID = 0, $bugID = 0)
    {
        if(!empty($_POST))
        {
            $storyID = $this->story->create($projectID, $bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($bugID == 0)
            {
                $actionID = $this->action->create('story', $storyID, 'Opened', '');
            }
            else
            {
                $actionID = $this->action->create('story', $storyID, 'Frombug', '', $bugID);
            }
            $this->sendMail($storyID, $actionID);
            if($this->post->newStory)
            {
                echo js::alert($this->lang->story->successSaved . $this->lang->story->newStory);
                die(js::locate($this->createLink('story', 'create', "productID=$productID&moduleID=$moduleID&story=0&projectID=$projectID&bugID=$bugID"), 'parent'));
            }
            if($projectID == 0)
            {
                die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
            }
            else
            {
                die(js::locate($this->createLink('project', 'story', "projectID=$projectID"), 'parent'));
            }
        }

        /* Set products, users and module. */
        if($productID != 0) 
        {
            $product  = $this->product->getById($productID);
            $products = $this->product->getPairs();
        }
        else
        {
            $products = $this->product->getProductsByProject($projectID); 
            foreach($products as $key => $title)
            {
                $product = $this->product->getById($key);
                break;
            }
        }
        $users = $this->user->getPairs('nodeleted|pdfirst');
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');

        /* Set menu. */
        $this->product->setMenu($products, $product->id);

        /* Init vars. */
        $planID     = 0;
        $source     = '';
        $pri        = 0;
        $estimate   = '';
        $title      = '';
        $spec       = '';
        $verify     = '';
        $keywords   = '';
        $mailto     = '';

        if($storyID > 0)
        {
            $story      = $this->story->getByID($storyID);
            $planID     = $story->plan;
            $source     = $story->source;
            $pri        = $story->pri;
            $productID  = $story->product;
            $moduleID   = $story->module;
            $estimate   = $story->estimate;
            $title      = $story->title;
            $spec       = htmlspecialchars($story->spec);
            $verify     = htmlspecialchars($story->verify);
            $keywords   = $story->keywords;
            $mailto     = $story->mailto;
        }

        if($bugID > 0)
        {
            $oldBug    = $this->loadModel('bug')->getById($bugID);
            $productID = $oldBug->product;
            $source    = 'bug';
            $title     = $oldBug->title;
            $keywords  = $oldBug->keywords;
            $spec      = $oldBug->steps;
            $pri       = $oldBug->pri;
            if(strpos($oldBug->mailto, $oldBug->openedBy) === false) 
            {
                $mailto = $oldBug->mailto . $oldBug->openedBy . ',';
            }
            else
            {
                $mailto = $oldBug->mailto;
            }
        }

        $this->view->header->title    = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID"), $product->name);
        $this->view->position[]       = $this->lang->story->create;
        $this->view->products         = $products;
        $this->view->users            = $users;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID, 'unexpired');
        $this->view->planID           = $planID;
        $this->view->source           = $source;
        $this->view->pri              = $pri;
        $this->view->productID        = $productID;
        $this->view->estimate         = $estimate;
        $this->view->title            = $title;
        $this->view->spec             = $spec;
        $this->view->verify           = $verify;
        $this->view->keywords         = $keywords;
        $this->view->mailto           = $mailto;

        $this->display();
    }
    
    /**
     * Create a batch stories.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $mails = $this->story->batchCreate($productID);
            if(dao::isError()) die(js::error(dao::getError()));

            foreach($mails as $mail)
            {
                $this->sendMail($mail->storyID, $mail->actionID);
            }
            die(js::locate($this->createLink('product', 'browse', "productID=$productID"), 'parent'));
        }

        /* Set products, users and module. */
        $product  = $this->product->getById($productID);
        $products = $this->product->getPairs();
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');

        /* Set menu. */
        $this->product->setMenu($products, $product->id);

        /* Init vars. */
        $planID     = 0;
        $pri        = 0;
        $estimate   = '';
        $title      = '';
        $spec       = '';

        $moduleOptionMenu['same'] = $this->lang->story->same;
        $plans = $this->loadModel('productplan')->getPairs($productID, 'unexpired');
        $plans['same'] = $this->lang->story->same;

        $this->view->header->title    = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID"), $product->name);
        $this->view->position[]       = $this->lang->story->create;
        $this->view->products         = $products;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $plans; 
        $this->view->planID           = $planID;
        $this->view->pri              = $pri;
        $this->view->productID        = $productID;
        $this->view->estimate         = $estimate;
        $this->view->title            = $title;
        $this->view->spec             = $spec;

        $this->display();
    }

    /**
     * The common action when edit or change a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function commonAction($storyID)
    {
        /* Get datas. */
        $story    = $this->story->getById($storyID);
        $product  = $this->product->getById($story->product);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs('nodeleted|pdfirst');
        $moduleOptionMenu = $this->tree->getOptionMenu($product->id, $viewType = 'story');

        /* Set menu. */
        $this->product->setMenu($products, $product->id);

        /* Assign. */
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $this->view->product          = $product;
        $this->view->products         = $products;
        $this->view->story            = $story;
        $this->view->users            = $users;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($product->id);
        $this->view->actions          = $this->action->getList('story', $storyID);
    }

    /**
     * Edit a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function edit($storyID)
    {
        if(!empty($_POST))
        {
            $changes = $this->story->update($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendMail($storyID, $actionID);
            }
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);
  
        /* Assign. */
        $this->view->header->title = $this->view->product->name . $this->lang->colon . $this->lang->story->edit . $this->lang->colon . $this->view->story->title;
        $this->view->position[]    = $this->lang->story->edit;
        $this->view->users         = $this->user->appendDeleted($this->user->getPairs('nodeleted|pofirst'), $this->view->story->assignedTo);
        $this->view->story         = $this->story->getById($storyID, 0, true);
        $this->display();
    }

    /**
     * Batch edit story.
     * 
     * @param  string $from productBrowse|projectStory|storyBatchEdit.
     * @param  int    $productID 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function batchEdit($from = '', $productID = 0, $projectID = 0, $orderBy = '')
    {
        /* Get post data for product-Browse or project-Story. */
        if($from == 'productBrowse' or $from == 'projectStory')
        {
            /* Init vars. */
            $editedStories   = array();
            $storyIDList     = $this->post->storyIDList ? $this->post->storyIDList : array();
            $columns         = 9;
            $showSuhosinInfo = false;

            /* Get all stories. */
            if(!$projectID)
            {
                /* Set menu. */
                $this->product->setMenu($this->product->getPairs('nodeleted'), $productID);
                $allStories = $this->dao->select('*')->from(TABLE_STORY)->where($this->session->storyQueryCondition)->orderBy($orderBy)->fetchAll('id');
            }
            else
            {
                $this->lang->story->menu = $this->lang->project->menu;
                $this->project->setMenu($this->project->getPairs('nodeleted'), $projectID);
                $this->lang->set('menugroup.story', 'project');
                $allStories = $this->story->getProjectStories($projectID, $orderBy);
            }
            if(!$allStories) $allStories = array();

            /* Initialize the stories whose need to edited. */
            foreach($allStories as $story) if(in_array($story->id, $storyIDList)) $editedStories[$story->id] = $story;

            /* Judge whether the editedStories is too large. */
            $showSuhosinInfo = $this->loadModel('common')->judgeSuhosinSetting(count($editedStories), $columns);

            /* Set the sessions. */
            $this->app->session->set('showSuhosinInfo', $showSuhosinInfo);

            /* Assign. */
            if(!$projectID)
            {
                $product = $this->product->getByID($productID);
                $this->view->header->title = $product->name . $this->lang->colon . $this->lang->story->batchEdit;
            }
            else
            {
                $project = $this->project->getByID($projectID);
                $this->view->header->title = $project->name . $this->lang->colon . $this->lang->story->batchEdit;
            }
            if($showSuhosinInfo) $this->view->suhosinInfo = $this->lang->suhosinInfo;
            $this->view->position[]       = $this->lang->story->common;
            $this->view->position[]       = $this->lang->story->batchEdit;
            $this->view->users            = $this->loadModel('user')->getPairs('nodeleted');
            $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');
            $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
            $this->view->productID        = $productID;
            $this->view->editedStories    = $editedStories;

            $this->display();
        }
        /* Get post data for story-batchEdit. */
        elseif($from == 'storyBatchEdit')
        {
            if(!empty($_POST))
            {

                $allChanges = $this->story->batchUpdate();

                if($allChanges)
                {
                    foreach($allChanges as $storyID => $changes)
                    {
                        $actionID = $this->action->create('story', $storyID, 'Edited');
                        $this->action->logHistory($actionID, $changes);
                        $this->sendMail($storyID, $actionID);
                    }
                }
            }
            die(js::locate($this->session->storyList, 'parent'));
        }
    }

    /**
     * Change a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function change($storyID)
    {
        if(!empty($_POST))
        {
            $changes = $this->story->change($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $version = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch('version');
            $files = $this->loadModel('file')->saveUpload('story', $storyID, $version);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = (!empty($changes) or !empty($files)) ? 'Changed' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('story', $storyID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendMail($storyID, $actionID);
            }
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('project');

        /* Assign. */
        $this->view->header->title = $this->view->product->name . $this->lang->colon . $this->lang->story->change . $this->lang->colon . $this->view->story->title;
        $this->view->position[]    = $this->lang->story->change;
        $this->display();
    }

    /**
     * Activate a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function activate($storyID)
    {
        if(!empty($_POST))
        {
            $this->story->activate($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('story', $storyID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
            $this->sendMail($storyID, $actionID);
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);

        /* Assign. */
        $this->view->header->title = $this->view->product->name . $this->lang->colon . $this->lang->story->activate . $this->lang->colon . $this->view->story->title;
        $this->view->position[]    = $this->lang->story->activate;
        $this->display();
    }

    /**
     * View a story.
     * 
     * @param  int    $storyID 
     * @param  int    $version 
     * @access public
     * @return void
     */
    public function view($storyID, $version = 0)
    {
        $storyID = (int)$storyID;
        $story   = $this->story->getById($storyID, $version, true);
        if(!$story) die(js::error($this->lang->notFound) . js::locate('back'));

        $story->files = $this->loadModel('file')->getByObject('story', $storyID);
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();
        $plan         = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $bugs         = $this->dao->select('id,title')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $fromBug      = $this->dao->select('id,title')->from(TABLE_BUG)->where('toStory')->eq($storyID)->fetch();
        $cases        = $this->dao->select('id,title')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $modulePath   = $this->tree->getParents($story->module);
        $users        = $this->user->getPairs('noletter');

        /* Set the menu. */
        $this->product->setMenu($this->product->getPairs(), $product->id);

        $header['title'] = "STORY #$story->id $story->title - $product->name";
        $position[]      = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $position[]      = $this->lang->story->view;

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->product    = $product;
        $this->view->plan       = $plan;
        $this->view->bugs       = $bugs;
        $this->view->fromBug    = $fromBug;
        $this->view->cases      = $cases;
        $this->view->story      = $story;
        $this->view->users      = $users;
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->modulePath = $modulePath;
        $this->view->version    = $version == 0 ? $story->version : $version;
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('story', $storyID);
        $this->display();
    }

    /**
     * Delete a story.
     * 
     * @param  int    $storyID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->story->confirmDelete, $this->createLink('story', 'delete', "story=$storyID&confirm=yes"), '');
            exit;
        }
        else
        {
            $this->story->delete(TABLE_STORY, $storyID);
            die(js::locate($this->session->storyList, 'parent'));
        }
    }

    /**
     * Review a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function review($storyID)
    {
        if(!empty($_POST))
        {
            $this->story->review($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $result = $this->post->result;
            if($this->post->closedReason != '' and strpos('done,postponed,subdivided', $this->post->closedReason) !== false) $result = 'pass';
            $actionID = $this->action->create('story', $storyID, 'Reviewed', $this->post->comment, ucfirst($result));
            $this->action->logHistory($actionID, array());
            $this->sendMail($storyID, $actionID);
            if($this->post->result == 'reject')
            {
                $this->action->create('story', $storyID, 'Closed', '', ucfirst($this->post->closedReason));
            }
            die(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
        }

        /* Get story and product. */
        $story   = $this->story->getById($storyID);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        /* Set menu. */
        $this->product->setMenu($this->product->getPairs(), $product->id);

        /* Set the review result options. */
        if($story->status == 'draft' and $story->version == 1) unset($this->lang->story->reviewResultList['revert']);
        if($story->status == 'changed') unset($this->lang->story->reviewResultList['reject']);

        $this->view->header->title = $product->name . $this->lang->colon . $this->lang->story->view . $this->lang->colon . $story->title;
        $this->view->position[]    = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $this->view->position[]    = $this->lang->story->view;

        $this->view->product = $product;
        $this->view->story   = $story;
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted');

        /* Get the affcected things. */
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('project');

        $this->display();
    }

    /**
     * Close a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function close($storyID)
    {
        if(!empty($_POST))
        {
            $changes = $this->story->close($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('story', $storyID, 'Closed', $this->post->comment, ucfirst($this->post->closedReason));
            $this->action->logHistory($actionID, $changes);
            $this->sendMail($storyID, $actionID);
            die(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
        }

        /* Get story and product. */
        $story   = $this->story->getById($storyID);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        /* Set menu. */
        $this->product->setMenu($this->product->getPairs(), $product->id);

        /* Set the closed reason options. */
        if($story->status == 'draft') unset($this->lang->story->reasonList['cancel']);

        $this->view->header->title = $product->name . $this->lang->colon . $this->lang->close . $this->lang->colon . $story->title;
        $this->view->position[]    = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $this->view->position[]    = $this->lang->close;

        $this->view->product = $product;
        $this->view->story   = $story;
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Batch close story.
     * 
     * @param  string $from productBrowse|projectStory|storyBatchClose
     * @param  int    $productID 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function batchClose($from = '', $productID = 0, $projectID = 0, $orderBy = '')
    {
        /* Get post data for product-Browse or project-Story. */
        if($from == 'productBrowse' or $from == 'projectStory')
        {
            /* Init vars. */
            $editedStories   = array();
            $storyIDList     = $this->post->storyIDList ? $this->post->storyIDList : array();
            $columns         = 4;
            $showSuhosinInfo = false;

            /* Get all stories. */
            if(!$projectID)
            {
                /* Set menu. */
                $this->product->setMenu($this->product->getPairs('nodeleted'), $productID);
                $allStories = $this->dao->select('*')->from(TABLE_STORY)->where($this->session->storyQueryCondition)->orderBy($orderBy)->fetchAll('id');
            }
            else
            {
                $this->lang->story->menu      = $this->lang->project->menu;
                $this->lang->story->menuOrder = $this->lang->project->menuOrder;
                $this->project->setMenu($this->project->getPairs('nodeleted'), $projectID);
                $this->lang->set('menugroup.story', 'project');
                $allStories = $this->story->getProjectStories($projectID, $orderBy);
            }
            if(!$allStories) $allStories = array();

            /* Initialize the stories whose need to edited. */
            foreach($allStories as $story) if(in_array($story->id, $storyIDList)) $editedStories[$story->id] = $story;

            /* Judge whether the editedStories is too large. */
            $showSuhosinInfo = $this->loadModel('common')->judgeSuhosinSetting(count($editedStories), $columns);

            /* Set the sessions. */
            $this->app->session->set('showSuhosinInfo', $showSuhosinInfo);

            /* Assign. */
            if(!$projectID)
            {
                $product = $this->product->getByID($productID);
                $this->view->header->title = $product->name . $this->lang->colon . $this->lang->story->batchClose;
            }
            else
            {
                $project = $this->project->getByID($projectID);
                $this->view->header->title = $project->name . $this->lang->colon . $this->lang->story->batchClose;
            }
            if($showSuhosinInfo) $this->view->suhosinInfo = $this->lang->suhosinInfo;
            $this->view->position[]       = $this->lang->story->common;
            $this->view->position[]       = $this->lang->story->batchClose;
            $this->view->users            = $this->loadModel('user')->getPairs('nodeleted');
            $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');
            $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
            $this->view->productID        = $productID;
            $this->view->editedStories    = $editedStories;

            $this->display();
        }
        /* Get post data for story-batchClose. */
        elseif($from == 'storyBatchClose')
        {
            if(!empty($_POST))
            {

                $allChanges = $this->story->batchClose();

                if($allChanges)
                {
                    foreach($allChanges as $storyID => $changes)
                    {
                        $actionID = $this->action->create('story', $storyID, 'Closed', $this->post->comments[$storyID], ucfirst($this->post->closedReasons[$storyID]));
                        $this->action->logHistory($actionID);
                        $this->sendMail($storyID, $actionID);
                    }
                }
            }
            die(js::locate($this->session->storyList, 'parent'));
        }
    }

    /**
     * Tasks of a story.
     * 
     * @param  int    $storyID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function tasks($storyID, $projectID = 0)
    {
        $this->loadModel('task');
        $this->view->tasks = $this->task->getStoryTaskPairs($storyID, $projectID);
        $this->display();
        exit;
    }

    /**
     * AJAX: get stories of a project in html select.
     * 
     * @param  int    $projectID 
     * @param  int    $productID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function ajaxGetProjectStories($projectID, $productID = 0, $storyID = 0)
    {
        $stories = $this->story->getProjectStoryPairs($projectID, $productID);
        die(html::select('story', $stories, $storyID));
    }

    /**
     * AJAX: get stories of a product in html select.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function ajaxGetProductStories($productID, $moduleID = 0, $storyID = 0)
    {
        $stories = $this->story->getProductStoryPairs($productID, $moduleID);
        die(html::select('story', $stories, $storyID, "class=''"));
    }

    /**
     * Send email.
     * 
     * @param  int    $storyID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($storyID, $actionID)
    {
        $story       = $this->story->getById($storyID);
        $productName = $this->product->getById($story->product)->name;

        /* Get actions. */
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();
        if(strtolower($action->action) == 'opened') $action->comment = $story->spec;

        /* Set toList and ccList. */
        $toList      = $story->assignedTo;
        $ccList      = str_replace(' ', '', trim($story->mailto, ','));

        /* If the action is changed or reviewed, mail to the project team. */
        if(strtolower($action->action) == 'changed' or strtolower($action->action) == 'reviewed')
        {
            $prjMembers = $this->story->getProjectMembers($storyID);
            if($prjMembers)
            {
                $ccList .= ',' . join(',', $prjMembers);
                $ccList = ltrim($ccList, ',');
            }
        }

        if($toList == '')
        {
            if($ccList == '') return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        elseif($toList == 'closed')
        {
            $toList = $story->openedBy;
        }

        /* Get the mail content. */
        if($action->action == 'opened') $action->comment = '';
        $this->view->story  = $story;
        $this->view->action = $action;
        $this->view->users  = $this->user->getPairs('noletter');
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send it. */
        $this->loadModel('mail')->send($toList, $productName . ':' . 'STORY #' . $story->id . $this->lang->colon . $story->title, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
    /**
     * The report page.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function report($productID, $browseType, $moduleID)
    {
        $this->loadModel('report');
        $this->view->charts   = array();
        $this->view->renderJS = '';

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->story->$chartFunc();
                $chartOption = $this->lang->story->report->$chart;
                $this->story->mergeChartOption($chart);

                $chartXML  = $this->report->createSingleXML($chartData, $chartOption->graph);
                $this->view->charts[$chart] = $this->report->createJSChart($chartOption->swf, $chartXML, $chartOption->width, $chartOption->height);
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
            $this->view->renderJS = $this->report->renderJsCharts(count($this->view->charts));
        }
        $this->products = $this->product->getPairs();
        $this->product->setMenu($this->products, $productID);
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->story->common;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->moduleID      = $moduleID;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
        $this->display();
    }
 
    /**
     * get data to export
     * 
     * @param  int $productID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function export($productID, $orderBy)
    { 
        /* format the fields of every story in order to export data. */
        if($_POST)
        {
            $storyLang   = $this->lang->story;
            $storyConfig = $this->config->story;

            /* Create field lists. */
            $fields = explode(',', $storyConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($storyLang->$fieldName) ? $storyLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get stories. */
            $stories = array();
            if($this->session->storyOnlyCondition == 'true')
            {
                $stories = $this->dao->select('*')->from(TABLE_STORY)->where($this->session->storyQueryCondition)
                    ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                    ->orderBy($orderBy)->fetchAll('id', false);
            }
            else
            {
                $stmt = $this->dbh->query($this->session->storyQueryCondition . ($this->post->exportType == 'selected' ? " AND id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
                while($row = $stmt->fetch()) $stories[$row->id] = $row;
            }

            /* Get users, products and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $products = $this->loadModel('product')->getPairs();

            /* Get related objects id lists. */
            $relatedModuleIdList = array();
            $relatedStoryIdList  = array();
            $relatedPlanIdList   = array();

            foreach($stories as $story)
            {
                $relatedModuleIdList[$story->module] = $story->module;
                $relatedPlanIdList[$story->plan]     = $story->plan;

                /* Process related stories. */
                $relatedStories = $story->childStories . ',' . $story->linkStories . ',' . $story->duplicateStory;
                $relatedStories = explode(',', $relatedStories);
                foreach($relatedStories as $storyID)
                {
                    if($storyID) $relatedStoryIdList[$storyID] = trim($storyID);
                }
            }

            /* Get related objects title or names. */
            $relatedModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedPlans   = $this->dao->select('id, title')->from(TABLE_PRODUCTPLAN)->where('id')->in($relatedPlanIdList)->fetchPairs();
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->in(@array_keys($stories))->fetchGroup('objectID');
            $relatedSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('`story`')->in(@array_keys($stories))->orderBy('version desc')->fetchGroup('story');

            foreach($stories as $story)
            {
                $story->spec   = '';
                $story->verify = '';
                if(isset($relatedSpecs[$story->id]))
                {
                    $storySpec     = $relatedSpecs[$story->id][0];
                    $story->title  = $storySpec->title;
                    $story->spec   = $storySpec->spec;
                    $story->verify = $storySpec->verify;
                }

                if($this->post->fileType == 'csv')
                {
                    $story->spec = htmlspecialchars_decode($story->spec);
                    $story->spec = str_replace("<br />", "\n", $story->spec);
                    $story->spec = str_replace('"', '""', $story->spec);

                    $story->verify = htmlspecialchars_decode($story->verify);
                    $story->verify = str_replace("<br />", "\n", $story->verify);
                    $story->verify = str_replace('"', '""', $story->verify);
                }
                /* fill some field with useful value. */
                if(isset($products[$story->product]))              $story->product        = $products[$story->product];
                if(isset($relatedModules[$story->module]))         $story->module         = $relatedModules[$story->module];
                if(isset($relatedPlans[$story->plan]))             $story->plan           = $relatedPlans[$story->plan];
                if(isset($relatedStories[$story->duplicateStory])) $story->duplicateStory = $relatedStories[$story->duplicateStory];

                if(isset($storyLang->priList[$story->pri]))             $story->pri          = $storyLang->priList[$story->pri];
                if(isset($storyLang->statusList[$story->status]))       $story->status       = $storyLang->statusList[$story->status];
                if(isset($storyLang->stageList[$story->stage]))         $story->stage        = $storyLang->stageList[$story->stage];
                if(isset($storyLang->reasonList[$story->closedReason])) $story->closedReason = $storyLang->reasonList[$story->closedReason];

                if(isset($users[$story->openedBy]))     $story->openedBy     = $users[$story->openedBy];
                if(isset($users[$story->assignedTo]))   $story->assignedTo   = $users[$story->assignedTo];
                if(isset($users[$story->lastEditedBy])) $story->lastEditedBy = $users[$story->lastEditedBy];
                if(isset($users[$story->closedBy]))     $story->closedBy     = $users[$story->closedBy]; 

                $story->openedDate     = substr($story->openedDate, 0, 10);
                $story->assignedDate   = substr($story->assignedDate, 0, 10);
                $story->lastEditedDate = substr($story->lastEditedDate, 0, 10);
                $story->closedDate     = substr($story->closedDate, 0, 10);


                if($story->linkStories)
                {
                    $tmpLinkStories = array();
                    $linkStoriesIdList = explode(',', $story->linkStories);
                    foreach($linkStoriesIdList as $linkStoryID)
                    {
                        $linkStoryID = trim($linkStoryID);
                        $tmpLinkStories[] = isset($relatedStories[$linkStoryID]) ? $relatedStories[$linkStoryID] : $linkStoryID;
                    }
                    $story->linkStories = join("; \n", $tmpLinkStories);
                }

                if($story->childStories)
                {
                    $tmpChildStories = array();
                    $childStoriesIdList = explode(',', $story->childStories);
                    foreach($childStoriesIdList as $childStoryID)
                    {
                        $childStoryID = trim($childStoryID);
                        $tmpChildStories[] = isset($relatedStories[$childStoryID]) ? $relatedStories[$childStoryID] : $childStoryID;
                    }
                    $story->childStories = join("; \n", $tmpChildStories);
                }

                /* Set related files. */
                if(isset($relatedFiles[$story->id]))
                {
                    foreach($relatedFiles[$story->id] as $file)
                    {
                        $fileURL = 'http://' . $this->server->http_host . $this->config->webRoot . "data/upload/$story->company/" . $file->pathname;
                        $story->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                    }
                }

                $story->mailto = trim(trim($story->mailto), ',');
                $mailtos = explode(',', $story->mailto);
                $story->mailto = '';
                foreach($mailtos as $mailto)
                {
                    $mailto = trim($mailto);
                    if(isset($users[$mailto])) $story->mailto .= $users[$mailto] . ',';
                }

                $story->reviewedBy = trim(trim($story->reviewedBy), ',');
                $reviewedBys = explode(',', $story->reviewedBy);
                $story->reviewedBy = '';
                foreach($reviewedBys as $reviewedBy)
                {
                    $reviewedBy = trim($reviewedBy);
                    if(isset($users[$reviewedBy])) $story->reviewedBy .= $users[$reviewedBy] . ',';
                }

            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $stories);
            $this->post->set('kind', 'story');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }
}
