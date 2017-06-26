<?php
/**
 * The control file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
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
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
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
     * @param  int    $branch 
     * @param  int    $moduleID 
     * @param  int    $storyID 
     * @param  int    $projectID 
     * @param  int    $bugID 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function create($productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $projectID = 0, $bugID = 0, $planID = 0)
    {
        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $storyResult = $this->story->create($projectID, $bugID);
            if(!$storyResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $storyID = $storyResult['id'];
            if($storyResult['status'] == 'exists')
            {
                $response['message'] = sprintf($this->lang->duplicate, $this->lang->story->common);
                if($projectID == 0)
                {
                    $response['locate'] = $this->createLink('story', 'view', "storyID={$storyID}");
                }
                else
                {
                    $response['locate'] = $this->createLink('project', 'story', "projectID=$projectID");
                }
                $this->send($response);
            }

            $action   = $bugID == 0 ? 'Opened' : 'Frombug';
            $extra    = $bugID == 0 ? '' : $bugID;
            $actionID = $this->action->create('story', $storyID, $action, '', $extra);
            $this->story->sendmail($storyID, $actionID);

            if($this->post->newStory)
            {
                $response['message'] = $this->lang->story->successSaved . $this->lang->story->newStory;
                $response['locate']  = $this->createLink('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&story=0&projectID=$projectID&bugID=$bugID");
                $this->send($response);
            }

            $response['locate'] = $this->createLink('project', 'story', "projectID=$projectID");
            if($projectID == 0) $response['locate'] = $this->createLink('story', 'view', "storyID=$storyID");
            $this->send($response);
        }

        /* Set products, users and module. */
        if($projectID != 0)
        {
            $products = $this->product->getProductsByProject($projectID);
            $product  = $this->product->getById(($productID and array_key_exists($productID, $products)) ? $productID : key($products));
        }
        else
        {
            $products = $this->product->getPairs('noclosed');
            $product  = $this->product->getById($productID ? $productID : key($products));
            if(!isset($products[$product->id])) $products[$product->id] = $product->name;
        }

        $users = $this->user->getPairs('nodeleted|pdfirst|noclosed');
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch);

        /* Set menu. */
        $this->product->setMenu($products, $product->id, $branch);

        /* Init vars. */
        $source     = '';
        $sourceNote = '';
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

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customCreateFields) as $field) $customFields[$field] = $this->lang->story->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->story->custom->createFields;

        $this->view->title            = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID&branch=$branch"), $product->name);
        $this->view->position[]       = $this->lang->story->common;
        $this->view->position[]       = $this->lang->story->create;
        $this->view->products         = $products;
        $this->view->users            = $users;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID, $branch, 'unexpired');
        $this->view->planID           = $planID;
        $this->view->source           = $source;
        $this->view->sourceNote       = $sourceNote;
        $this->view->pri              = $pri;
        $this->view->branch           = $branch;
        $this->view->branches         = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID) : array();
        $this->view->productID        = $productID;
        $this->view->product          = $product;
        $this->view->projectID        = $projectID;
        $this->view->estimate         = $estimate;
        $this->view->storyTitle       = $title;
        $this->view->spec             = $spec;
        $this->view->verify           = $verify;
        $this->view->keywords         = $keywords;
        $this->view->mailto           = $mailto;
        $this->view->needReview       = ($this->app->user->account == $product->PO || $projectID > 0 || $this->config->story->needReview == 0) ? "checked='checked'" : "";

        $this->display();
    }
    
    /**
     * Create a batch stories.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0, $branch = 0, $moduleID = 0, $storyID = 0)
    {
        if(!empty($_POST))
        {
            $mails = $this->story->batchCreate($productID, $branch);
            if(dao::isError()) die(js::error(dao::getError()));

            foreach($mails as $mail)
            {
                if($mail->actionID) $this->story->sendmail($mail->storyID, $mail->actionID);
            }

            /* If storyID not equal zero, subdivide this story to child stories and close it. */
            if($storyID)
            {
                $actionID = $this->story->subdivide($storyID, $mails);
                if(dao::isError()) die(js::error(dao::getError()));
                $this->story->sendmail($storyID, $actionID);
                if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
                die(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
            }

            die(js::locate($this->createLink('product', 'browse', "productID=$productID&branch=$branch"), 'parent'));
        }

        /* Set products and module. */
        $product  = $this->product->getById($productID);
        $products = $this->product->getPairs();
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch);

        /* Set menu. */
        $this->product->setMenu($products, $product->id, $branch);

        /* Init vars. */
        $planID     = 0;
        $pri        = 0;
        $estimate   = '';
        $title      = '';
        $spec       = '';

        /* Process upload images. */
        if($this->session->storyImagesFile)
        {
            $files = $this->session->storyImagesFile;
            foreach($files as $fileName => $file)
            {
                $title = $file['title'];
                $titles[$title] = $fileName;
            }
            krsort($titles);
            $this->view->titles = $titles;
        }

        $moduleOptionMenu['ditto'] = $this->lang->story->ditto;
        $plans = $this->loadModel('productplan')->getPairs($productID, $branch, 'unexpired');
        $plans['ditto']      = $this->lang->story->ditto;
        $priList             = (array)$this->lang->story->priList;
        $priList['ditto']    = $this->lang->story->ditto;
        $sourceList          = (array)$this->lang->story->sourceList;
        $sourceList['ditto'] = $this->lang->story->ditto;

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->story->$field;
        }
        $showFields = $this->config->story->custom->batchCreateFields;
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->view->title            = $product->name . $this->lang->colon . ($storyID ? $this->lang->story->subdivide : $this->lang->story->batchCreate);
        $this->view->productName      = $product->name;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID&branch=$branch"), $product->name);
        $this->view->position[]       = $this->lang->story->common;
        $this->view->position[]       = $storyID ? $this->lang->story->subdivide : $this->lang->story->batchCreate;
        $this->view->storyID          = $storyID;
        $this->view->products         = $products;
        $this->view->product          = $product;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $plans; 
        $this->view->priList          = $priList;
        $this->view->sourceList       = $sourceList;
        $this->view->planID           = $planID;
        $this->view->pri              = $pri;
        $this->view->productID        = $productID;
        $this->view->estimate         = $estimate;
        $this->view->storyTitle       = $title;
        $this->view->spec             = $spec;
        $this->view->branch           = $branch;
        $this->view->branches         = $this->loadModel('branch')->getPairs($productID);
        $this->view->needReview       = ($this->app->user->account == $product->PO || $this->config->story->needReview == 0) ? 0 : 1;

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
        $moduleOptionMenu = $this->tree->getOptionMenu($product->id, $viewType = 'story', 0, $story->branch);

        /* Set menu. */
        $this->product->setMenu($products, $product->id, $story->branch);

        /* Assign. */
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[]       = $this->lang->story->common;
        $this->view->product          = $product;
        $this->view->products         = $products;
        $this->view->story            = $story;
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
                $this->story->sendmail($storyID, $actionID);
            }
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);
  
        /* Assign. */
        $story   = $this->story->getById($storyID, 0, true);
        $product = $this->loadModel('product')->getById($story->product);
        $this->view->title      = $this->lang->story->edit . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->position[] = $this->lang->story->edit;
        $this->view->story      = $story;
        $this->view->users      = $this->user->getPairs('nodeleted|pofirst', "$story->assignedTo,$story->openedBy,$story->closedBy");
        $this->view->product    = $product;
        $this->view->branches   = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($story->product);
        $this->display();
    }

    /**
     * Batch edit story.
     * 
     * @param  int    $productID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $projectID = 0, $branch = 0)
    {
        /* Load model. */
        $this->loadModel('productplan');

        if($this->post->titles)
        {
            $allChanges = $this->story->batchUpdate();

            if($allChanges)
            {
                foreach($allChanges as $storyID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->action->create('story', $storyID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                    $this->story->sendmail($storyID, $actionID);
                }
            }
            die(js::locate($this->session->storyList, 'parent'));
        }

        $storyIDList = $this->post->storyIDList ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));

        /* Get edited stories. */
        $stories = $this->story->getByList($storyIDList);

        /* The stories of a product. */
        if($productID)
        {
            $this->product->setMenu($this->product->getPairs('nodeleted'), $productID, $branch);
            $product = $this->product->getByID($productID);
            $branchProduct = $product->type == 'normal' ? false : true;

            /* Set modules and productPlans. */
            $modules      = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch);
            $modules      = array('ditto' => $this->lang->story->ditto) + $modules;
            $productPlans = $this->productplan->getPairs($productID, $branch);
            $productPlans = array('' => '', 'ditto' => $this->lang->story->ditto) + $productPlans;


            $this->view->modules      = $modules;
            $this->view->branches     = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
            $this->view->productPlans = $productPlans;
            $this->view->position[]   = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$branch"), $product->name);
            $this->view->title        = $product->name . $this->lang->colon . $this->lang->story->batchEdit;

        }
        /* The stories of a project. */
        elseif($projectID)
        {
            $this->lang->story->menu = $this->lang->project->menu;
            $this->project->setMenu($this->project->getPairs('nodeleted'), $projectID);
            $this->lang->set('menugroup.story', 'project');
            $this->lang->story->menuOrder = $this->lang->project->menuOrder;

            $project = $this->project->getByID($projectID);

            $branchProduct = false;
            $linkedProducts = $this->project->getProducts($projectID);
            foreach($linkedProducts as $linkedProduct)
            {
                if($linkedProduct->type != 'normal')
                {
                    $branchProduct = true;
                    break;
                }
            }

            $this->view->position[] = html::a($this->createLink('project', 'story', "project=$project->id"), $project->name);
            $this->view->title      = $project->name . $this->lang->colon . $this->lang->story->batchEdit;
        }
        /* The stories of my. */
        else
        {
            $this->lang->story->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.story', 'my');
            $this->lang->story->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();

            $branchProduct = false;
            $productIdList = array();
            foreach($stories as $story) $productIdList[$story->product] = $story->product;
            $products = $this->product->getByIdList($productIdList);
            foreach($products as $storyProduct)
            {
                if($storyProduct->type != 'normal')
                {
                    $branchProduct = true;
                    break;
                }
            }

            $this->view->position[] = html::a($this->createLink('my', 'story'), $this->lang->my->story);
            $this->view->title      = $this->lang->story->batchEdit;
        }

        /* Set ditto option for users. */
        $users          = $this->loadModel('user')->getPairs('nodeleted');
        $users = array('' => '', 'ditto' => $this->lang->story->ditto) + $users;

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->story->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->story->custom->batchEditFields;

        /* Judge whether the editedStories is too large and set session. */
        $countInputVars  = count($stories) * (count(explode(',', $this->config->story->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $this->view->position[]        = $this->lang->story->common;
        $this->view->position[]        = $this->lang->story->batchEdit;
        $this->view->users             = $users;
        $this->view->priList           = array('0' => '', 'ditto' => $this->lang->story->ditto) + $this->lang->story->priList;
        $this->view->sourceList        = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->sourceList;
        $this->view->reasonList        = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->reasonList;
        $this->view->stageList         = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->stageList;
        $this->view->productID         = $productID;
        $this->view->branchProduct     = $branchProduct;
        $this->view->storyIDList       = $storyIDList;
        $this->view->branch            = $branch;
        $this->view->stories           = $stories;
        $this->view->productName       = isset($product) ? $product->name : '';
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
                $this->story->sendmail($storyID, $actionID);
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
        $this->view->title      = $this->lang->story->change . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->users      = $this->user->getPairs('nodeleted|pofirst', $this->view->story->assignedTo);
        $this->view->position[] = $this->lang->story->change;
        $this->view->needReview = ($this->app->user->account == $this->view->product->PO || $this->config->story->needReview == 0) ? "checked='checked'" : "";
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
            $this->story->sendmail($storyID, $actionID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);

        /* Assign. */
        $this->view->title      = $this->lang->story->activate . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->users      = $this->user->getPairs('nodeleted|pofirst', $this->view->story->closedBy);
        $this->view->position[] = $this->lang->story->activate;
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
    public function view($storyID, $version = 0, $from = 'product', $param = 0)
    {
        $storyID = (int)$storyID;
        $story   = $this->story->getById($storyID, $version, true);
        if(!$story) die(js::error($this->lang->notFound) . js::locate('back'));

        $story->files = $this->loadModel('file')->getByObject('story', $storyID);
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, type')->fetch();
        $plan         = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $bugs         = $this->dao->select('id,title')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $fromBug      = $this->dao->select('id,title')->from(TABLE_BUG)->where('toStory')->eq($storyID)->fetch();
        $cases        = $this->dao->select('id,title')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $modulePath   = $this->tree->getParents($story->module);
        $users        = $this->user->getPairs('noletter');

        /* Set the menu. */
        $this->product->setMenu($this->product->getPairs(), $product->id, $story->branch);

        if($from == 'project')
        {
            $project = $this->loadModel('project')->getById($param);
            if($project->status == 'done') $from = '';
        }

        $title      = "STORY #$story->id $story->title - $product->name";
        $position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $position[] = $this->lang->story->common;
        $position[] = $this->lang->story->view;

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->product    = $product;
        $this->view->branches   = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->plan       = $plan;
        $this->view->bugs       = $bugs;
        $this->view->fromBug    = $fromBug;
        $this->view->cases      = $cases;
        $this->view->story      = $story;
        $this->view->users      = $users;
        $this->view->projects   = $this->loadModel('project')->getPairs('nocode');
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->modulePath = $modulePath;
        $this->view->version    = $version == 0 ? $story->version : $version;
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('story', $storyID);
        $this->view->from       = $from;
        $this->view->param      = $param;
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
            $this->story->sendmail($storyID, $actionID);
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
        $this->product->setMenu($this->product->getPairs(), $product->id, $story->branch);

        /* Set the review result options. */
        if($story->status == 'draft' and $story->version == 1) unset($this->lang->story->reviewResultList['revert']);
        if($story->status == 'changed') unset($this->lang->story->reviewResultList['reject']);

        $this->view->title      = $this->lang->story->review . "STORY" . $this->lang->colon . $story->title;
        $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[] = $this->lang->story->common;
        $this->view->position[] = $this->lang->story->review;

        $this->view->product = $product;
        $this->view->story   = $story;
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted', "$story->lastEditedBy,$story->openedBy");

        /* Get the affcected things. */
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('project');

        $this->display();
    }

    /**
     * Batch review stories.
     * 
     * @param  string $result 
     * @param  string $reason 
     * @access public
     * @return void
     */
    public function batchReview($result, $reason = '')
    {
        $storyIDList = $this->post->storyIDList ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));
        $actions     = $this->story->batchReview($storyIDList, $result, $reason);

        if(dao::isError()) die(js::error(dao::getError()));
        foreach($actions as $storyID => $actionID) $this->story->sendmail($storyID, $actionID);
        die(js::locate($this->session->storyList, 'parent'));
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
            $actionID = $this->action->create('story', $storyID, 'Closed', $this->post->comment, ucfirst($this->post->closedReason) . ($this->post->duplicateStory ? ':' . (int)$this->post->duplicateStory : ''));
            $this->action->logHistory($actionID, $changes);
            $this->story->sendmail($storyID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
        }

        /* Get story and product. */
        $story   = $this->story->getById($storyID);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        /* Set menu. */
        $this->product->setMenu($this->product->getPairs(), $product->id, $story->branch);

        /* Set the closed reason options. */
        if($story->status == 'draft') unset($this->lang->story->reasonList['cancel']);

        $this->view->title      = $this->lang->story->close . "STORY" . $this->lang->colon . $story->title;
        $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[] = $this->lang->story->common;
        $this->view->position[] = $this->lang->story->close;

        $this->view->product = $product;
        $this->view->story   = $story;
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Batch close story.
     * 
     * @param  int    $productID 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function batchClose($productID = 0, $projectID = 0)
    {
        if($this->post->comments)
        {
            $allChanges = $this->story->batchClose();

            if($allChanges)
            {
                foreach($allChanges as $storyID => $changes)
                {
                    $actionID = $this->action->create('story', $storyID, 'Closed', htmlspecialchars($this->post->comments[$storyID]), ucfirst($this->post->closedReasons[$storyID]) . ($this->post->duplicateStoryIDList[$storyID] ? ':' . (int)$this->post->duplicateStoryIDList[$storyID] : ''));
                    $this->action->logHistory($actionID, $changes);
                    $this->story->sendmail($storyID, $actionID);
                }
            }
            die(js::locate($this->session->storyList, 'parent'));
        }

        $storyIDList = $this->post->storyIDList ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));

        /* Get edited stories. */
        $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($storyIDList)->fetchAll('id');
        foreach($stories as $story)
        {
            if($story->status == 'closed') unset($stories[$story->id]);
        }
        if(empty($stories)) die(js::alert($this->lang->story->notice->closed) . js::locate($this->session->storyList, 'parent'));

        /* The stories of a product. */
        if($productID)
        {
            $this->product->setMenu($this->product->getPairs('nodeleted'), $productID);
            $product = $this->product->getByID($productID);
            $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
            $this->view->title      = $product->name . $this->lang->colon . $this->lang->story->batchClose;
        }
        /* The stories of a project. */
        elseif($projectID)
        {
            $this->lang->story->menu      = $this->lang->project->menu;
            $this->lang->story->menuOrder = $this->lang->project->menuOrder;
            $this->project->setMenu($this->project->getPairs('nodeleted'), $projectID);
            $this->lang->set('menugroup.story', 'project');
            $project = $this->project->getByID($projectID);
            $this->view->position[] = html::a($this->createLink('project', 'story', "project=$project->id"), $project->name);
            $this->view->title      = $project->name . $this->lang->colon . $this->lang->story->batchClose;
        }
        /* The stories of my. */
        else
        {
            $this->lang->story->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.story', 'my');
            $this->lang->story->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();
            $this->view->position[] = html::a($this->createLink('my', 'story'), $this->lang->my->story);
            $this->view->title      = $this->lang->story->batchEdit;
        }

        /* Judge whether the editedStories is too large and set session. */
        $countInputVars  = count($stories) * $this->config->story->batchClose->columns;
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $this->view->position[]       = $this->lang->story->common;
        $this->view->position[]       = $this->lang->story->batchClose;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->storyIDList      = $storyIDList;

        $this->display();
    }

    /**
     * Batch change the module of story.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        $storyIDList = !empty($_POST['storyIDList']) ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));
        $allChanges  = $this->story->batchChangeModule($storyIDList, $moduleID);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $this->story->sendmail($storyID, $actionID);
        }
        die(js::reload('parent'));
    }

    /**
     * Batch change the plan of story.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function batchChangePlan($planID, $oldPlanID = 0)
    {
        $storyIDList = !empty($_POST['storyIDList']) ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));
        $allChanges  = $this->story->batchChangePlan($storyIDList, $planID, $oldPlanID);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $this->story->sendmail($storyID, $actionID);
        }
        die(js::reload('parent'));
    }

    /**
     * Batch change branch.
     * 
     * @param  int    $branchID 
     * @access public
     * @return void
     */
    public function batchChangeBranch($branchID)
    {
        $storyIDList = !empty($_POST['storyIDList']) ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));
        $allChanges  = $this->story->batchChangeBranch($storyIDList, $branchID);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $this->story->sendmail($storyID, $actionID);
        }
        die(js::reload('parent'));
    }

    /**
     * Batch change the stage of story.
     * 
     * @param  string    $stage 
     * @access public
     * @return void
     */
    public function batchChangeStage($stage)
    {
        $storyIDList = $this->post->storyIDList ? $this->post->storyIDList : die(js::locate($this->session->storyList, 'parent'));
        $allChanges  = $this->story->batchChangeStage($storyIDList, $stage);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $this->story->sendmail($storyID, $actionID);
        }
        die(js::locate($this->session->storyList, 'parent'));
    }

    /**
     * Batch assign to.
     * 
     * @access public
     * @return void
     */
    public function batchAssignTo()
    {
        if(!empty($_POST) && isset($_POST['storyIDList']))
        {
            $allChanges  = $this->story->batchAssignTo();
            if(dao::isError()) die(js::error(dao::getError()));
            foreach($allChanges as $storyID => $changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Edited');
                $this->action->logHistory($actionID, $changes);
                $this->story->sendmail($storyID, $actionID);
            }
        }
        die(js::locate($this->session->storyList));
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
        $this->view->tasks = $this->task->getStoryTasks($storyID, $projectID);
        $this->view->users = $this->user->getPairs('noletter');
        $this->display();
    }

    /**
     * Bugs of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function bugs($storyID)
    {
        $this->loadModel('bug');
        $this->view->bugs  = $this->bug->getStoryBugs($storyID);
        $this->view->users = $this->user->getPairs('noletter');
        $this->display();
    }

    /**
     * Cases of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function cases($storyID)
    {
        $this->loadModel('testcase');
        $this->view->cases      = $this->testcase->getStoryCases($storyID);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->resultList = array('' => '') + $this->lang->testcase->resultList;
        $this->display();
    }

    /**
     * Show zero case story.
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function zeroCase($productID, $orderBy = 'id_desc')
    {
        $this->session->set('productList', $this->app->getURI(true));
        $products = $this->loadModel('product')->getPairs();

        $this->lang->set('menugroup.story', 'qa');
        $this->lang->story->menu      = $this->lang->testcase->menu;
        $this->lang->story->menuOrder = $this->lang->testcase->menuOrder;
        $this->lang->story->menu->testcase['subModule'] = 'story';
        $this->loadModel('testcase')->setMenu($products, $productID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $this->view->title      = $this->lang->story->zeroCase;
        $this->view->position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $products[$productID]);
        $this->view->position[] = $this->lang->story->zeroCase;

        $this->view->stories   = $this->story->getZeroCase($productID, $sort);
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->productID = $productID;
        $this->view->orderBy   = $orderBy;
        $this->view->suiteList = $this->loadModel('testsuite')->getSuites($productID);
        $this->display();
    }

    /**
     * If type is linkStories, link related stories else link child stories.
     *
     * @param  int    $storyID
     * @param  string $type
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return void
     */
    public function linkStory($storyID, $type = 'linkStories', $browseType = '', $param = 0)
    {
        $this->commonAction($storyID);

        /* Get story, product, products, and queryID. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs();
        $queryID  = ($browseType == 'bySearch') ? (int)$param : 0;

        /* Link stories. */
        if(!empty($_POST))
        {
            $this->story->linkStories($storyID, $type);
            if(isonlybody()) die(js::closeModal('parent.parent', '', "function(){parent.parent.loadLinkedStories('$storyID', '$type')}"));
            die(js::locate($this->createLink('story', 'edit', "storyID=$storyID"), 'parent'));
        }

        /* Build search form. */
        $actionURL = $this->createLink('story', 'linkStory', "storyID=$storyID&type=$type&browseType=bySearch&queryID=myQueryID", '', true);
        $this->loadModel('product')->buildSearchForm($story->product, $products, $queryID, $actionURL);

        /* Get stories to link. */
        $stories2Link = $this->story->getStories2Link($storyID, $type, $browseType, $queryID);

        /* Assign. */
        $this->view->title        = $this->lang->story->linkStory . "STORY" . $this->lang->colon .$this->lang->story->linkStory;
        $this->view->position[]   = $this->lang->story->linkStory;
        $this->view->type         = $type;
        $this->view->stories2Link = $stories2Link;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * AJAX: if type is linkStories, get related stories else get child stories.
     *
     * @param  int    $storyID
     * @param  string $type
     * @access public
     * @return string
     */
    public function ajaxGetLinkedStories($storyID, $type = '')
    {
        /* Get linked stories. */
        $stories = $this->story->getLinkedStories($storyID, $type);

        /* Build linked stories list. */
        $output = '';
        foreach($stories as $storyId => $storyTitle)
        {
            $output .= '<li>';
            $output .= html::a(inlink('view', "storyID=$storyId"), "#$storyId " . $storyTitle, '_blank');
            $output .= html::a("javascript:unlinkStory($storyID, \"$type\", $storyId)", '<i class="icon-remove"></i>', '', "title='{$this->lang->unlink}' style='float:right'");
            $output .= '</li>';
        }

        die($output);
    }

    /**
     * Unlink story.
     *
     * @param  int    $storyID
     * @param  string $type
     * @param  int    $story2Unlink
     * @access public
     * @return string
     */
    public function unlinkStory($storyID, $type = '', $story2Unlink = 0)
    {
        /* Unlink related story if type is linkStories else unlink child story. */
        $this->story->unlinkStory($storyID, $type, $story2Unlink);

        die('success');
    }

    /**
     * AJAX: get stories of a project in html select.
     * 
     * @param  int    $projectID 
     * @param  int    $productID 
     * @param  int    $storyID 
     * @param  string $number
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetProjectStories($projectID, $productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $number = '', $type= 'full')
    {
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }
        $stories   = $this->story->getProjectStoryPairs($projectID, $productID, $branch, $moduleID, $type);
        if($this->app->getViewType() === 'json')
        {
            die(json_encode($stories));
        }
        elseif($this->app->getViewType() == 'mhtml')
        {
            die(html::select('story', empty($stories) ? array('' => '') : $stories, $storyID, 'onchange=setStoryRelated()'));
        }
        else
        {
            $storyName = $number === '' ? 'story' : "story[$number]";
            die(html::select($storyName, empty($stories) ? array('' => '') : $stories, $storyID, 'class=form-control onchange=setStoryRelated(' . $number . ');'));
        }
    }

    /**
     * AJAX: get stories of a product in html select.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @param  int    $storyID 
     * @param  string $onlyOption 
     * @param  string $status 
     * @param  int    $limit 
     * @access public
     * @return void
     */
    public function ajaxGetProductStories($productID, $branch = 0, $moduleID = 0, $storyID = 0, $onlyOption = 'false', $status = '', $limit = 0)
    {
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }

        $storyStatus = '';
        if($status == 'noclosed')
        {
            $storyStatus = $this->lang->story->statusList;
            unset($storyStatus['closed']);
            $storyStatus = array_keys($storyStatus);
        }

        $stories = $this->story->getProductStoryPairs($productID, $branch ? "0,$branch" : $branch, $moduleID, $storyStatus, 'id_desc', $limit);
        $select  = html::select('story', empty($stories) ? array('' => '') : $stories, $storyID, "class='form-control'");

        /* If only need options, remove select wrap. */
        if($onlyOption == 'true') die(substr($select, strpos($select, '>') + 1, -10));
        die($select);
    }

    /**
     * AJAX: search stories of a product as json
     * 
     * @param  string $key 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @param  int    $storyID 
     * @param  string $status 
     * @param  int    $limit 
     * @access public
     * @return void
     */
    public function ajaxSearchProductStories($key, $productID, $branch = 0, $moduleID = 0, $storyID = 0, $status = 'noclosed', $limit = 50)
    {
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }

        $storyStatus = '';
        if($status == 'noclosed')
        {
            $storyStatus = $this->lang->story->statusList;
            unset($storyStatus['closed']);
            $storyStatus = array_keys($storyStatus);
        }

        $stories = $this->story->getProductStoryPairs($productID, $branch, $moduleID, $storyStatus, 'id_desc');
        $result = array();
        $i = 0;
        foreach ($stories as $id => $story)
        {
            if($limit > 0 && $i > $limit) break;
            if(('#' . $id) === $key || stripos($story,  $key) !== false)
            {
                $result[$id] = $story;
                $i++;
            }
        }
        if($i < 1)
        {
            $result['info'] = $this->lang->noResultsMatch;
        }

        die(json_encode($result));
    }

    /**
     * AJAX: get spec and verify of a story. for web app.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function ajaxGetDetail($storyID)
    {
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->story   = $this->story->getByID($storyID);
        $this->display();
    }

    /**
     * AJAX: get module of a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return string 
     */
    public function ajaxGetModule($storyID)
    {
        $story = $this->story->getByID($storyID); 
        echo $story->module;
    }

    /**
     * The report page.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  int    $branchID
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function report($productID, $browseType, $branchID, $moduleID)
    {
        $this->loadModel('report');
        $this->view->charts   = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->story->$chartFunc();
                $chartOption = $this->lang->story->report->$chart;
                $this->story->mergeChartOption($chart);


                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }
        $this->products = $this->product->getPairs();
        $this->product->setMenu($this->products, $productID, $branchID);

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->story->reportChart;
        $this->view->position[]    = $this->products[$productID];
        $this->view->position[]    = $this->lang->story->reportChart;
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
            $this->loadModel('file');
            $this->loadModel('branch');
            $storyLang   = $this->lang->story;
            $storyConfig = $this->config->story;

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $storyConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($storyLang->$fieldName) ? $storyLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get stories. */
            $stories = array();
            if($this->session->storyOnlyCondition)
            {
                $stories = $this->dao->select('*')->from(TABLE_STORY)->where($this->session->storyQueryCondition)
                    ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                    ->orderBy($orderBy)->fetchAll('id');
            }
            else
            {
                $stmt = $this->dbh->query($this->session->storyQueryCondition . ($this->post->exportType == 'selected' ? " AND t2.id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
                while($row = $stmt->fetch()) $stories[$row->id] = $row;
            }

            /* Get users, products and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $products = $this->loadModel('product')->getPairs('nocode');

            /* Get related objects id lists. */
            $relatedProductIdList = array();
            $relatedModuleIdList  = array();
            $relatedStoryIdList   = array();
            $relatedPlanIdList    = array();
            $relatedBranchIdList  = array();

            foreach($stories as $story)
            {
                $relatedProductIdList[$story->product] = $story->product;
                $relatedModuleIdList[$story->module]   = $story->module;
                $relatedPlanIdList[$story->plan]       = $story->plan;
                $relatedBranchIdList[$story->branch]   = $story->branch;

                /* Process related stories. */
                $relatedStories = $story->childStories . ',' . $story->linkStories . ',' . $story->duplicateStory;
                $relatedStories = explode(',', $relatedStories);
                foreach($relatedStories as $storyID)
                {
                    if($storyID) $relatedStoryIdList[$storyID] = trim($storyID);
                }
            }

            /* Get related objects title or names. */
            $productsType   = $this->dao->select('id, type')->from(TABLE_PRODUCT)->where('id')->in($relatedProductIdList)->fetchPairs();
            $relatedModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedPlans   = $this->dao->select('id, title')->from(TABLE_PRODUCTPLAN)->where('id')->in(join(',', $relatedPlanIdList))->fetchPairs();
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->in(@array_keys($stories))->andWhere('extra')->ne('editor')->fetchGroup('objectID');
            $relatedSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('`story`')->in(@array_keys($stories))->orderBy('version desc')->fetchGroup('story');
            $relatedBranch  = array('0' => $this->lang->branch->all) + $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($relatedBranchIdList)->fetchPairs();

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
                    $story->spec = str_replace('&nbsp;', ' ', $story->spec);

                    $story->verify = htmlspecialchars_decode($story->verify);
                    $story->verify = str_replace("<br />", "\n", $story->verify);
                    $story->verify = str_replace('"', '""', $story->verify);
                    $story->verify = str_replace('&nbsp;', ' ', $story->verify);
                }
                /* fill some field with useful value. */
                if(isset($products[$story->product]))       $story->product = $products[$story->product] . "(#$story->product)";
                if(isset($relatedModules[$story->module]))  $story->module  = $relatedModules[$story->module] . "(#$story->module)";
                if(isset($relatedBranch[$story->branch]))   $story->branch  = $relatedBranch[$story->branch] . "(#$story->branch)";
                if(isset($story->plan))
                {
                    $plans = '';
                    foreach(explode(',', $story->plan) as $planID)
                    {
                        if(empty($planID)) continue;
                        if(isset($relatedPlans[$planID]))$plans .= $relatedPlans[$planID] . "(#$planID) ";
                    }
                    $story->plan = $plans;
                }
                if(isset($relatedStories[$story->duplicateStory])) $story->duplicateStory = $relatedStories[$story->duplicateStory];

                if(isset($storyLang->priList[$story->pri]))             $story->pri          = $storyLang->priList[$story->pri];
                if(isset($storyLang->statusList[$story->status]))       $story->status       = $storyLang->statusList[$story->status];
                if(isset($storyLang->stageList[$story->stage]))         $story->stage        = $storyLang->stageList[$story->stage];
                if(isset($storyLang->reasonList[$story->closedReason])) $story->closedReason = $storyLang->reasonList[$story->closedReason];
                if(isset($storyLang->sourceList[$story->source]))       $story->source       = $storyLang->sourceList[$story->source];

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
                $story->files = '';
                if(isset($relatedFiles[$story->id]))
                {
                    foreach($relatedFiles[$story->id] as $file)
                    {
                        $fileURL = common::getSysURL() . $this->file->webPath . $this->file->getRealPathName($file->pathname);
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

            if(!(in_array('platform', $productsType) or in_array('branch', $productsType))) unset($fields['branch']);// If products's type are normal, unset branch field.

            $this->post->set('fields', $fields);
            $this->post->set('rows', $stories);
            $this->post->set('kind', 'story');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->view->allExportFields = $this->config->story->list->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }
}
