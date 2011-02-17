<?php
/**
 * The control file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
        $this->loadModel('tree');
        $this->loadModel('user');
    }

    /**
     * Create a story.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function create($productID = 0, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $storyID = $this->story->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action');
            $actionID = $this->action->create('story', $storyID, 'Opened', '');
            $this->sendMail($storyID, $actionID);
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        /* Set products, users and module. */
        $product  = $this->product->getById($productID);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs('nodeleted');
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');

        /* Set menu. */
        $this->product->setMenu($products, $product->id);

        $this->view->header->title    = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID"), $product->name);
        $this->view->position[]       = $this->lang->story->create;
        $this->view->product          = $product;
        $this->view->products         = $products;
        $this->view->users            = $users;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID, 'unexpired');
        $this->display();
    }

    /**
     * The common action when edit or change a story.
     * 
     * @param  int    $storyID 
     * @access protected
     * @return void
     */
    protected function commonAction($storyID)
    {
        /* Get datas. */
        $story    = $this->story->getById($storyID);
        $product  = $this->product->getById($story->product);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs('nodeleted');
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
        $this->loadModel('action');
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
        $this->view->users         = $this->user->appendDeleted($this->user->getPairs('nodeleted'), $this->view->story->assignedTo);
        $this->display();
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
        $this->loadModel('action');
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
        $this->loadModel('action');
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
        $this->loadModel('action');
        $storyID = (int)$storyID;
        $story   = $this->story->getById($storyID, $version);
        if(!$story) die(js::error($this->lang->notFound) . js::locate('back'));

        $story->files = $this->loadModel('file')->getByObject('story', $storyID);
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();
        $plan         = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $modulePath   = $this->tree->getParents($story->module);
        $users        = $this->user->getPairs('noletter');

        /* Set the menu. */
        $this->product->setMenu($this->product->getPairs(), $product->id);

        $header['title'] = $product->name . $this->lang->colon . $this->lang->story->view . $this->lang->colon . $story->title;
        $position[]      = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $position[]      = $this->lang->story->view;

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->product    = $product;
        $this->view->plan       = $plan;
        $this->view->story      = $story;
        $this->view->users      = $users;
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->modulePath = $modulePath;
        $this->view->version    = $version == 0 ? $story->version : $version;
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
        $this->loadModel('action');

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
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $this->story->close($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('story', $storyID, 'Closed', $this->post->comment, ucfirst($this->post->closedReason));
            $this->action->logHistory($actionID);
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
     * @access protected
     * @return void
     */
    protected function sendmail($storyID, $actionID)
    {
        /* Get actions. */
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();
        if(strtolower($action->action) == 'opened') $action->comment = $story->spec;

        /* Set toList and ccList. */
        $story  = $this->story->getById($storyID);
        $toList = $story->assignedTo;
        $ccList = str_replace(' ', '', trim($story->mailto, ','));

        /* If the action is changed or reviewed, mail to the project team. */
        if(strtolower($action->action) == 'changed' or strtolower($action->action) == 'reviewed')
        {
            $prjMembers = $this->story->getProjectMembers($storyID);
            if($prjMembers)
            {
                $ccList .= ',' . join(',', $prjMembers);
                $ccList = ltrim(',', $ccList);
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
        $this->view->story  = $story;
        $this->view->action = $action;
        $this->view->users  = $this->user->getPairs('noletter');
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send it. */
        $this->loadModel('mail')->send($toList, 'STORY #' . $story->id . $this->lang->colon . $story->title, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
}
