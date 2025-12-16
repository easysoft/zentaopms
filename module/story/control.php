<?php
declare(strict_types=1);
/**
 * The control file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class story extends control
{
    /**
     * The construct function, load product, tree, user auto.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function __construct(string $module = '', string $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('product');
        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
        $this->loadModel('epic');
        $this->loadModel('requirement');

        if($this->app->rawModule == 'projectstory') $this->app->tab = 'project';
    }

    /**
     * Create a story.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $objectID  projectID|executionID
     * @param  int    $bugID
     * @param  int    $planID
     * @param  int    $todoID
     * @param  string $extra for example feedbackID=0
     * @param  string $storyType requirement|story
     * @access public
     * @return void
     */
    public function create(int $productID = 0, string $branch = '', int $moduleID = 0, int $storyID = 0, int $objectID = 0, int $bugID = 0, int $planID = 0, int $todoID = 0, string $extra = '', string $storyType = 'story')
    {
        /* Set menu. */
        $this->story->replaceURLang($storyType);
        $copyStoryID = $storyID;
        list($productID, $objectID) = $this->storyZen->setMenuForCreate($productID, $objectID, $extra);
        if($productID == 0 && $objectID == 0) return $this->locate($this->createLink('product', 'create'));
        if($productID == 0 && $objectID != 0) return $this->sendError($this->lang->execution->errorNoLinkedProducts, $this->createLink('execution', 'manageproducts', "executionID=$objectID"));

        if(!empty($_POST))
        {
            if(isset($_POST['module']))  $moduleID = $this->post->module;
            if(isset($_POST['modules'])) $moduleID = reset($_POST['modules']);
            helper::setcookie('lastStoryModule', $moduleID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);

            /* API will post projectID or executionID. */
            if($this->post->project)   $objectID = (int)$this->post->project;
            if($this->post->execution) $objectID = (int)$this->post->execution;

            /* Get story data from post. */
            $storyData = $this->storyZen->buildStoryForCreate($objectID, $bugID, $storyType);
            if(!$storyData) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Insert story data. */
            $createFunction = empty($storyData->branches) ? 'create' : 'createTwins';
            $storyID        = $this->story->{$createFunction}($storyData, $objectID, $bugID, $extra, $todoID);
            if(empty($storyID) || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($_POST['fileList']))
            {
                $fileList = $this->post->fileList;
                if($fileList) $fileList = json_decode($fileList, true);
                $this->loadModel('file')->saveDefaultFiles($fileList, 'story', $storyID, 1);
            }

            $productID = $this->post->product ? $this->post->product : $productID;
            $message   = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->post->status == 'draft' ? $this->lang->story->saveDraftSuccess : $this->lang->saveSuccess;
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $storyID));

            /* Get response when create in modal. */
            $response = $this->storyZen->getResponseInModal($message);
            if($response) return $this->send($response);

            $response = array('result' => 'success', 'message' => $message);
            if($this->post->newStory)
            {
                $response['message'] = $message . $this->lang->story->newStory;
                $response['load']  = $this->createLink('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&story=$copyStoryID&objectID=$objectID&bugID=$bugID&planID=$planID&todoID=$todoID&extra=$extra&storyType=$storyType");
                return $this->send($response);
            }

            $response['load'] = $this->storyZen->getAfterCreateLocation((int)$productID, $branch, $objectID, $storyID, $storyType, $extra);
            return $this->send($response);
        }

        /* Init vars. */
        $initStory = $this->storyZen->initStoryForCreate($planID, $copyStoryID, $bugID, $todoID, $extra);

        /* Get form fields. */
        $this->storyZen->setViewVarsForKanban($objectID, $this->story->parseExtra($extra), $storyType);
        $fields = $this->storyZen->getFormFieldsForCreate($productID, $branch, $objectID, $initStory, $storyType);
        $fields = $this->storyZen->setModuleField($fields, $moduleID);
        $fields = $this->storyZen->removeFormFieldsForCreate($fields, $storyType);

        $this->view->title     = $this->view->product->name . $this->lang->hyphen . $this->lang->story->create;
        $this->view->fields    = $fields;
        $this->view->blockID   = $this->storyZen->getAssignMeBlockID();
        $this->view->type      = $storyType;
        $this->view->initStory = $initStory;

        $this->display();
    }

    /**
     * Create a batch stories.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $executionID projectID|executionID
     * @param  int    $plan
     * @param  string $storyType requirement|story
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function batchCreate(int $productID = 0, string $branch = '', int $moduleID = 0, int $storyID = 0, int $executionID = 0, int $plan = 0, string $storyType = 'story', string $extra = '')
    {
        $this->story->replaceURLang($storyType);

        if(!empty($_POST))
        {
            $stories = $this->storyZen->buildStoriesForBatchCreate($productID, $storyType);
            if(empty($stories)) return $this->sendError($this->lang->story->errorEmptyStory, true);
            if(dao::isError())  return $this->sendError(dao::getError());

            $storyIdList = $this->story->batchCreate($stories);
            if(dao::isError()) return $this->sendError(dao::getError(), true);

            /* Project or execution linked stories. */
            if($executionID)
            {
                $lanes    = array();
                $products = array();
                foreach($storyIdList as $i => $newStoryID)
                {
                    if(isset($stories[$i]) && !empty($stories[$i]->lane)) $lanes[$newStoryID] = $stories[$i]->lane;
                    $products[$newStoryID] = $productID;
                }

                if($this->session->project && $executionID != $this->session->project) $this->execution->linkStory($this->session->project, $storyIdList);
                $this->execution->linkStory($executionID, $storyIdList, $extra, $lanes, $storyType);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $stories));
            if(isInModal()) return $this->send($this->storyZen->getResponseInModal($this->lang->saveSuccess));

            $locateLink = $this->storyZen->getAfterBatchCreateLocation($productID, $branch, $executionID, $storyID, $storyType);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $this->storyZen->setMenuForBatchCreate($productID, $branch, $executionID, $extra, $storyType);

        if($productID == 0 && $executionID != 0) return $this->sendError($this->lang->execution->errorNoLinkedProducts, $this->createLink('execution', 'manageproducts', "executionID=$executionID"));

        $product = $this->product->getByID($productID);
        if($product) $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        /* The 'batchCreateFields' of global variable $config will be changed and used by the following business logic. */
        $customFields = $this->storyZen->getCustomFields($this->config, $storyType, $this->view->hiddenPlan, $product);
        $showFields   = $this->storyZen->getShowFields($this->config->{$storyType}->custom->batchCreateFields, $storyType, $product);

        $fields = $this->storyZen->getFormFieldsForBatchCreate($productID, $branch, $executionID, $storyType);
        $fields = $this->storyZen->removeFormFieldsForBatchCreate($fields, $this->view->hiddenPlan, isset($this->view->execution) ? $this->view->execution->type : '', $executionID);

        if($storyID)
        {
            $story = $this->story->getByID($storyID);
            if($story)
            {
                if(isset($fields['parent'])) $fields['parent']['default'] = $storyID;
                $gradeOptions = $this->story->getGradeOptions($story, $storyType);
                if(empty($gradeOptions)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->story->errorNoGradeSplit, 'locate' => $this->session->storyList)));
                $fields['grade']['options'] = $gradeOptions;
                $fields['grade']['default'] = key($gradeOptions);
            }

            $this->view->story      = $story;
            $this->view->storyTitle = isset($story->title) ? $story->title : '';

            /* Check can subdivide or not. */
            if(!$this->story->checkCanSubdivide($story, !empty($product->shadow))) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->story->errorNotSubdivide)));

            /* Check can split requirement/story or not. */
            if($storyType != $story->type && !$this->story->checkCanSplit($story)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->story->errorCannotSplit)));
        }

        $this->view->title         = $product->name . $this->lang->hyphen . ($storyID ? $this->lang->story->subdivide : $this->lang->story->batchCreate);
        $this->view->customFields  = $customFields;
        $this->view->showFields    = $showFields;
        $this->view->product       = $product;
        $this->view->branch        = $branch;
        $this->view->productID     = $productID;
        $this->view->storyID       = $storyID;
        $this->view->moduleID      = $moduleID;
        $this->view->executionID   = $executionID;
        $this->view->type          = $storyType;
        $this->view->fields        = $fields;
        $this->view->planID        = $plan;
        $this->view->maxGradeGroup = $this->story->getMaxGradeGroup('all');
        $this->view->stories       = $this->storyZen->getDataFromUploadImages($productID, $moduleID, $plan);
        $this->view->storyTitle    = isset($story->title) ? $story->title : '';
        $this->view->forceReview   = $this->story->checkForceReview($storyType);

        $this->display();
    }

    /**
     * The common action when edit or change a story.
     *
     * @param  int    $storyID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function commonAction(int $storyID, int $projectID = 0)
    {
        /* Get datas. */
        $story = $this->story->getByID($storyID);
        $this->story->replaceURLang($story->type);

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            if(empty($projectID)) $projectID = $this->session->project;
            $this->loadModel('project')->setMenu($projectID);
            $this->view->projectID = $projectID;
        }
        elseif($this->app->tab == 'execution')
        {
            if(empty($projectID)) $projectID = $this->session->execution;
            $this->loadModel('execution')->setMenu($projectID);
            $this->view->executionID = $projectID;
        }
        elseif($this->app->tab == 'qa')
        {
            $this->loadModel('qa')->setMenu($story->product);
        }
        else
        {
            $this->product->setMenu($story->product, $story->branch);
        }

        $product = $this->product->getByID($story->product);

        if($product->shadow)
        {
            $products = $this->product->getPairs('', 0, '', 'all');
        }
        else
        {
            $products = $this->product->getPairs();
        }

        /* Assign. */
        $this->view->product          = $product;
        $this->view->productID        = $this->view->product->id;
        $this->view->products         = $products;
        $this->view->story            = $story;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($story->product, 'story', 0, (string)$story->branch);
        $this->view->plans            = $this->loadModel('productplan')->getPairs($story->product, 0, '', true);
        $this->view->actions          = $this->action->getList('story', $storyID);
    }

    /**
     * Edit a story.
     *
     * @param  int    $storyID
     * @param  string $kanbanGroup
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function edit(int $storyID, string $kanbanGroup = 'default', string $storyType = 'story')
    {
        $this->loadModel('file');
        $this->app->loadLang('bug');
        $this->commonAction($storyID);
        $this->story->getAllChildId($storyID);

        if(!empty($_POST))
        {
            $storyData = $this->storyZen->buildStoryForEdit($storyID);
            if(!$storyData) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->storyZen->processDataForEdit($storyID, $storyData);
            $this->story->update($storyID, $storyData, $this->post->comment);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->lang->saveSuccess;
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            $response = $this->storyZen->getResponseInModal($message);
            if($response) return $this->send($response);

            $response = array('result' => 'success', 'message' => $message);
            $response['load'] = $this->storyZen->getAfterEditLocation($storyID, $storyType);
            return $this->send($response);
        }

        $story = $this->story->getByID($storyID);
        if($story->type == 'requirement') $this->lang->story->notice->reviewerNotEmpty = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->notice->reviewerNotEmpty);

        $fields = $this->storyZen->getFormFieldsForEdit($storyID);
        $fields = $this->storyZen->hiddenFormFieldsForEdit($fields, $storyType);

        $this->view->title        = $this->lang->story->edit . "STORY" . $this->lang->hyphen . $this->view->story->title;
        $this->view->story        = $story;
        $this->view->showGrade    = !empty($this->config->showStoryGrade);
        $this->view->twins        = empty($story->twins) ? array() : $this->story->getByList($story->twins);
        $this->view->fields       = $fields;
        $this->view->branches     = $this->view->product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($story->product);
        $this->view->lastReviewer = $this->story->getLastReviewer($story->id);

        $this->display();
    }

    /**
     * Batch edit story.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $branch
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit(int $productID = 0, int $executionID = 0, string $branch = '', string $storyType = 'story', string $from = '')
    {
        $this->story->replaceURLang($storyType);
        $this->storyZen->setMenuForBatchEdit($productID, $executionID, $storyType, $from);

        if($this->config->vision != 'or') unset($this->config->story->form->batchEdit['roadmap']);

        /* Load model. */
        $this->loadModel('productplan');
        if($this->post->title)
        {
            $stories = $this->storyZen->buildStoriesForBatchEdit();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->story->batchUpdate($stories);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->storyList));
        }

        $stories = $this->storyZen->getStoriesByChecked();
        if(!$stories) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->story->batchEditError, 'locate' => $this->session->storyList)));

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->story->$field;

        $product = $this->product->getByID($productID);
        if($product && $product->type == 'normal') unset($customFields['branch']);
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->{$storyType}->custom->batchEditFields;

        $this->storyZen->setFormOptionsForBatchEdit($productID, $executionID, $stories);

        foreach($stories as $story) $story->branch = 'branch' . $story->branch;

        $this->view->title       = $this->lang->story->batchEdit;
        $this->view->productID   = $productID;
        $this->view->branch      = $branch;
        $this->view->storyType   = $storyType;
        $this->view->stories     = $stories;
        $this->view->executionID = $executionID;
        $this->view->from        = $from;
        $this->display();
    }

    /**
     * Change a story.
     *
     * @param  int    $storyID
     * @param  string $from
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function change(int $storyID, string $from = '', string $storyType = 'story')
    {
        if(!empty($_POST))
        {
            $storyData = $this->storyZen->buildStoryForChange($storyID);
            if(!$storyData) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->story->change($storyID, $storyData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Changed' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                /* Record submit review action. */
                $story = $this->story->fetchByID($storyID);
                if($story->status == 'reviewing') $this->action->create('story', $storyID, 'submitReview');
            }

            $message = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->lang->saveSuccess;
            if(defined('RUN_MODE') and RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            $response = $this->storyZen->getResponseInModal($message);
            if($response) return $this->send($response);

            $location = $this->storyZen->getAfterChangeLocation($storyID, $storyType);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $location));
        }

        $this->commonAction($storyID);
        $story = $this->view->story;
        $this->story->getAffectedScope($story);

        $gradeGroup = array();
        $gradeList  = $this->story->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        /* Assign. */
        $this->view->title        = $this->lang->story->change . "STORY" . $this->lang->hyphen . $story->title;
        $this->view->users        = $this->user->getPairs('pofirst|nodeleted|noclosed', $story->assignedTo);
        $this->view->fields       = $this->storyZen->getFormFieldsForChange($storyID);
        $this->view->gradeGroup   = $gradeGroup;
        $this->view->lastReviewer = $this->story->getLastReviewer($story->id);

        $this->display();
    }

    /**
     * 激活需求。
     * Activate a story.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function activate(int $storyID, string $storyType = 'story')
    {
        if(!empty($_POST))
        {
            $postData = $this->storyZen->buildStoryForActivate($storyID);
            $this->story->activate($storyID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($storyID);

            if(isInModal())
            {
                $execution = $this->execution->getByID((int)$this->session->execution);
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "refreshKanban()", 'closeModal' => true));
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
                }
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->commonAction($storyID);

        /* Assign. */
        $this->view->title      = $this->lang->story->activate . "STORY" . $this->lang->hyphen . $this->view->story->title;
        $this->view->users      = $this->user->getPairs('pofirst|nodeleted|noclosed', $this->view->story->closedBy);
        $this->display();
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  int    $param     executionID|projectID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function view(int $storyID, int $version = 0, int $param = 0, string $storyType = 'story')
    {
        $this->config->morphUpdate = false;

        $uri     = $this->app->getURI(true);
        $tab     = $this->app->tab;
        $story   = $this->story->getById($storyID, $version, true);
        $product = $this->product->getByID((int)$story->product);

        $isAPI = defined('RUN_MODE') && RUN_MODE == 'api';
        if(!isInModal() && $tab == 'product' && !empty($product->shadow) && !$isAPI) return $this->send(array('result' => 'success', 'open' => array('url' => $uri, 'app' => 'project')));

        if(!$story || (isset($story->type) && $story->type != $storyType))
        {
            $locateModule = $this->config->vision == 'lite' ? 'project' : 'product';
            $locateMethod = ($this->config->edition == 'ipd' && $this->config->vision == 'or') ? 'browse' : 'index';
            return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink($locateModule, $locateMethod))));
        }

        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$story->product,") === false) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->product->accessDenied, 'locate' => $this->createLink('my', 'index'))));

        $this->session->set('productList', $uri . "#app={$tab}", 'product');
        if(!empty($story->fromBug)) $this->session->set('bugList', $uri, 'qa');

        $version = empty($version) ? $story->version : $version;
        $story   = $this->story->mergeReviewer($story, true);

        $this->app->loadLang('bug');
        $this->story->replaceURLang($story->type);
        $this->storyZen->getLinkedObjects($story);
        $this->storyZen->setHiddenFieldsForView($product);

        if($product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            if($product->shadow)
            {
                $project   = $this->loadModel('project')->getByShadowProduct($product->id);
                $projectID = $project->id;
            }
            else
            {
                $projectID = $param ? $param : $this->session->project;
                $project   = $this->loadModel('project')->fetchByID((int)$projectID);
            }
            $this->view->projectID = $projectID;
            $this->view->project   = $project;
            if(!$project->multiple)
            {
                $executionID = $param ? $param : $this->session->execution;
                $this->project->setMenu((int)$project->id);
                $this->view->executionID = $executionID;
                $this->view->execution   = $this->loadModel('execution')->fetchByID($executionID);
            }
            else
            {
                $this->project->setMenu((int)$projectID);
            }
        }
        elseif($this->app->tab == 'execution')
        {
            $executionID = $param ? $param : $this->session->execution;
            $this->loadModel('execution')->setMenu($executionID);
            $this->view->executionID = $executionID;
            $this->view->execution   = $this->execution->fetchByID($executionID);
        }
        elseif($this->app->tab == 'qa')
        {
            $this->loadModel('qa')->setMenu($story->product);
        }
        else
        {
            $this->product->setMenu($story->product, $story->branch);
        }

        $gradeGroup = array();
        $gradeList  = $this->story->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        $this->view->title         = "STORY #$story->id $story->title - $product->name";
        $this->view->branches      = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->executions    = $this->execution->getPairs(0, 'all', 'nocode');
        $this->view->version       = $version;
        $this->view->preAndNext    = $this->loadModel('common')->getPreAndNextObject($story->type, $storyID);
        $this->view->builds        = $this->loadModel('build')->getStoryBuilds($storyID);
        $this->view->releases      = $this->loadModel('release')->getStoryReleases($storyID);
        $this->view->story         = $story;
        $this->view->product       = $product;
        $this->view->maxGradeGroup = $this->story->getMaxGradeGroup();
        $this->view->gradePairs    = $this->story->getGradePairs($story->type, 'all');
        $this->view->gradeGroup    = $gradeGroup;
        $this->view->roadmaps      = $this->config->edition == 'ipd' ? array(0 => '') + $this->loadModel('roadmap')->getPairs() : array();
        $this->view->demand        = $this->config->edition == 'ipd' ? $this->loadModel('demand')->getByID($story->demand) : new stdclass();
        $this->view->showGrade     = !empty($this->config->showStoryGrade);
        $this->view->actions       = $this->action->getList('story', $storyID);
        $this->view->branch        = $story->branch;
        $this->view->project       = isset($projectID) ? $this->loadModel('project')->getById($projectID) : null;

        $this->display();
    }

    /**
     * 需求详情，延后鉴权。
     * View a story, delay auth.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function storyView(int $storyID)
    {
        $story = $this->story->fetchByID($storyID);
        if(common::hasPriv($story->type, 'view'))
        {
            echo $this->fetch($story->type, 'view', "storyID=$storyID");
        }
        else
        {
            $vars = "module={$story->type}&method=view";
            if(isset($this->server->http_referer))
            {
                $referer = helper::safe64Encode($this->server->http_referer);
                $vars   .= "&referer=$referer";
            }
            $denyLink = helper::createLink('user', 'deny', $vars);

            return $this->send(array('result' => 'success', 'load' => $denyLink));
        }
    }

    /**
     * Delete a story.
     *
     * @param  int    $storyID
     * @param  string $confirm   yes|no
     * @param  string $from      taskkanban
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function delete(int $storyID, string $confirm = 'no', string $from = '', string $storyType = 'story')
    {
        $story = $this->story->fetchById($storyID);
        if($story->parent < 0) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->story->cannotDeleteParent}');"));

        if($confirm == 'no')
        {
            if($story->type != 'story')
            {
                $replacement = $story->type == 'requirement' ? $this->lang->URCommon : $this->lang->ERCommon;
                $this->lang->story->confirmDelete = str_replace($this->lang->SRCommon, $replacement, $this->lang->story->confirmDelete);
            }
            $confirmURL = $this->createLink($storyType, 'delete', "story=$storyID&confirm=yes&from=$from&storyType=$storyType");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->story->confirmDelete}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }
        else
        {
            $this->dao->update(TABLE_STORY)->set('deleted')->eq(1)->where('id')->eq($storyID)->exec();
            $this->loadModel('action')->create($story->type, $storyID, 'deleted', '', actionModel::CAN_UNDELETED);

            if($story->parent > 0)
            {
                $this->story->updateParentStatus($story->id);
                $this->action->create('story', $story->parent, 'deleteChildrenStory', '', $storyID);
            }

            $this->story->setStage($story->id);

            $this->executeHooks($storyID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            if($this->app->tab == 'execution' and $from == 'taskkanban') return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
            $locateLink = isInModal() ? true : $locateLink;
            return $this->send(array('result' => 'success', 'load' => $locateLink, 'closeModal' => true));
        }
    }

    /**
     * Review a story.
     *
     * @param  int    $storyID
     * @param  string $from      product|project
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function review(int $storyID, string $from = 'product', string $storyType = 'story')
    {
        if(!empty($_POST))
        {
            $storyData = $this->storyZen->buildStoryForReview($storyID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->story->review($storyID, $storyData, (string)$this->post->comment);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->lang->saveSuccess;

            if(isInModal())
            {
                if($this->app->tab == 'execution') $this->loadModel('kanban')->updateLane($this->session->execution, 'story', $storyID);
                return $this->send($this->storyZen->getResponseInModal($message));
            }
            if(defined('RUN_MODE') and RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            $location = $this->storyZen->getAfterReviewLocation($storyID, $storyType, $from);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $location));
        }

        $this->commonAction($storyID);
        $story = $this->view->story;
        if(!empty($reviewers[$this->app->user->account]))
        {
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: '{$this->lang->hasReviewed}'}).then((res) => {loadCurrentPage()});"));
        }

        $reviewers = $this->story->getReviewerPairs($storyID, $story->version);
        $this->story->getAffectedScope($story);

        $gradeGroup = array();
        $gradeList  = $this->story->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        $this->view->title      = $this->lang->story->review . "STORY" . $this->lang->hyphen . $story->title;
        $this->view->fields     = $this->storyZen->getFormFieldsForReview($storyID);
        $this->view->reviewers  = $reviewers;
        $this->view->gradeGroup = $gradeGroup;
        $this->view->isLastOne  = count(array_diff(array_keys($reviewers), explode(',', $story->reviewedBy))) <= 1;

        $this->display();
    }

    /**
     * Batch review stories.
     *
     * @param  string $result
     * @param  string $reason
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchReview(string $result, string $reason = '', string $storyType = 'story')
    {
        if(!$this->post->storyIdList) return $this->send(array('result' => 'success', 'load' => $this->session->storyList));

        $storyIdList = $this->storyZen->convertChildID($this->post->storyIdList);
        $message = $this->story->batchReview($storyIdList, $result, $reason);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('ajax', 'batchOther');

        $response = array();
        $response['result'] = 'success';
        $response['load']   = false;
        if($message) $response['callback'] = "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: '{$message}'}).then((res) => {loadCurrentPage()});";
        if(empty($message)) $response['load'] = true;
        return $this->send($response);
    }

    /**
     * Recall the story review or story change.
     *
     * @param  int    $storyID
     * @param  string $from      list
     * @param  string $confirm   no|yes
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function recall(int $storyID, string $from = 'list', string $confirm = 'no', string $storyType = 'story')
    {
        $story = $this->story->fetchById($storyID);

        if($confirm == 'no')
        {
            $confirmTips = $story->status == 'changing' ? $this->lang->story->confirmRecallChange : $this->lang->story->confirmRecallReview;
            $confirmURL  = $this->createLink($story->type, 'recall', "storyID=$storyID&from=$from&confirm=yes&storyType=$storyType");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message:'{$confirmTips}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }

        if($story->status == 'changing')  $this->story->recallChange($storyID);
        if($story->status == 'reviewing') $this->story->recallReview($storyID);

        $action = $story->status == 'changing' ? 'recalledChange' : 'Recalled';
        $this->loadModel('action')->create('story', $storyID, $action);

        if($from == 'modal') return $this->send(array('result' => 'success', 'load' => 'modal'));

        $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
        if($from == 'view')
        {
            $module = $story->type;
            $method = 'view';
            $params = "storyID=$storyID&version=0&param=0&storyType=$storyType";
            if($this->app->tab == 'project')
            {
                $module = 'projectstory';
                $method = 'view';
                $params = "storyID=$storyID";
            }
            elseif($this->app->tab == 'execution')
            {
                $module = 'execution';
                $method = 'storyView';
                $params = "storyID=$storyID";
            }
            $locateLink = $this->createLink($module, $method, $params);
            if(strpos(',qa,doc,', ",{$this->app->tab},") !== false) $locateLink = true;
        }

        return $this->send(array('result' => 'success', 'load' => $locateLink, 'closeModal' => true));
    }

    /**
     * Submit review.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function submitReview(int $storyID, string $storyType = 'story')
    {
        if($_POST)
        {
            $storyData = $this->storyZen->buildStoryForSubmitReview($storyID);
            if(!$storyData) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->story->submitReview($storyID, $storyData);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('story', $storyID, 'submitReview');
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'loadCurrentPage()'));

            $module = $this->app->tab == 'project' ? 'projectstory' : 'story';
            $params = $this->app->tab == 'project' ? "storyID=$storyID&project={$this->session->project}" : "storyID=$storyID&version=0&param=0&storyType=$storyType";
            return $this->send(array('result' => 'success', 'load' => $this->createLink($module, 'view', $params), 'message' => $this->lang->saveSuccess));
        }

        /* Get story and product. */
        $story   = $this->story->fetchById($storyID);
        $product = $this->product->getById($story->product);

        /* Get reviewers. */
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->loadModel('user')->getProductViewListUsers($product);

        /* Get story reviewer. */
        $reviewerList    = $this->story->getReviewerPairs($story->id, $story->version);
        $story->reviewer = array_keys($reviewerList);

        $this->view->story        = $story;
        $this->view->actions      = $this->action->getList('story', $storyID);
        $this->view->reviewers    = $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);
        $this->view->users        = $this->user->getPairs('noclosed|noletter');
        $this->view->needReview   = (($this->app->user->account == $product->PO or $this->config->{$storyType}->needReview == 0 or !$this->story->checkForceReview($storyType)) and empty($story->reviewer)) ? "checked='checked'" : "";
        $this->view->lastReviewer = $this->story->getLastReviewer($story->id);

        $this->display();
    }

    /**
     * 关闭需求。
     * Close the story.
     *
     * @param  int    $storyID
     * @param  string $from        taskkanban
     * @param  string $storyType   story|requirement
     * @access public
     * @return void
     */
    public function close(int $storyID, string $from = '', string $storyType = 'story')
    {
        $story = $this->story->getById($storyID);
        $this->commonAction($storyID);

        if(!empty($_POST))
        {
            $postData = form::data($this->config->story->form->close, $storyID)
                ->stripTags($this->config->story->editor->close['id'], $this->config->allowedTags)
                ->removeIF($this->post->closedReason != 'duplicate', 'duplicateStory')
                ->get();

            if(strpos($this->config->{$story->type}->change->requiredFields, 'comment') !== false and !$this->post->comment) $this->send(array('result' => 'fail', 'message' => array('comment' => sprintf($this->lang->error->notempty, $this->lang->comment))));
            $postData = $this->loadModel('file')->processImgURL($postData, $this->config->story->editor->close['id'], $this->post->uid);

            $changes = $this->story->close($storyID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($storyID);

            if(isInModal())
            {
                $execution = $this->execution->getByID((int)$this->session->execution);
                if(($this->app->tab == 'execution' && $execution->type == 'kanban') || $from == 'taskkanban')
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "refreshKanban()"));
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => 'table'));
                }
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            $module = $this->app->tab == 'project' ? 'projectstory' : 'story';
            $params = $this->app->tab == 'project' ? "storyID=$storyID&project={$this->session->project}" : "storyID=$storyID&version=0&param=0&storyType=$storyType";
            return $this->send(array('result' => 'success', 'load' => $this->createLink($module, 'view', $params), 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        }

        /* Get story and product. */
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, `type`')->fetch();

        $this->story->replaceURLang($story->type);

        /* Set the closed reason options and remove subdivided options. */
        $reasonList = $this->lang->{$storyType}->reasonList;
        if($story->status == 'draft') unset($reasonList['cancel']);
        unset($reasonList['subdivided']);

        $this->view->title      = $this->lang->story->close . "STORY" . $this->lang->hyphen . $story->title;
        $this->view->product    = $product;
        $this->view->story      = $story;
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->reasonList = $reasonList;
        $this->display();
    }

    /**
     * 批量关闭需求。
     * Batch close the stories.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $storyType   story|requirement
     * @param  string $from        contribute|work
     * @access public
     * @return void
     */
    public function batchClose(int $productID = 0, int $executionID = 0, string $storyType = 'story', string $from = '')
    {
        if(!$this->post->storyIdList) return $this->send(array('result' => 'success', 'load' => $this->session->storyList));
        $storyIdList = $this->storyZen->convertChildID($this->post->storyIdList);

        $this->story->replaceURLang($storyType);

        if($this->post->comment)
        {
            $stories = $this->storyZen->buildStoriesForBatchClose(); /* Get the stories which need to be closed. */
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->story->batchClose($stories);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchOther');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->storyList));
        }

        $this->storyZen->setMenuForBatchClose($productID, $executionID, $storyType, $from);

        /* Get the skipped and already closed stories, and the count of stories which have a twin. */
        $stories      = $this->story->getByList($storyIdList);
        $closedStory  = array();
        $ignoreTwins  = array();
        $twinsCount   = array();
        $storyCount   = count($stories);
        foreach($stories as $story)
        {
            if(!empty($ignoreTwins) and isset($ignoreTwins[$story->id]))
            {
                unset($stories[$story->id]);
                continue;
            }

            if($story->status == 'closed')
            {
                $closedStory[] = $story->id;
                unset($stories[$story->id]);
            }

            if(!empty($story->twins))
            {
                $twinsCount[$story->id] = 0;
                foreach(explode(',', trim($story->twins, ',')) as $twinID)
                {
                    $twinsCount[$story->id] ++;
                    $ignoreTwins[$twinID] = $twinID;
                }
            }
        }

        if($storyCount == count($closedStory)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->story->notice->closed, 'locate' => $this->session->storyList)));

        $errorTips = '';
        if($closedStory) $errorTips .= sprintf($this->lang->story->closedStory, implode(',', $closedStory));

        $this->view->productID  = $productID;
        $this->view->stories    = $stories;
        $this->view->storyType  = $storyType;
        $this->view->twinsCount = $twinsCount;
        $this->view->errorTips  = $errorTips;
        $this->display();
    }

    /**
     * Batch change the module of story.
     *
     * @param  int    $moduleID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchChangeModule(int $moduleID, string $storyType = 'story')
    {
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = $this->storyZen->convertChildID($this->post->storyIdList);
        $allChanges  = $this->story->batchChangeModule($storyIdList, $moduleID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Batch stories convert to tasks.
     *
     * @param  int    $executionID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchToTask(int $executionID = 0, int $projectID = 0)
    {
        if($this->app->tab == 'execution' and $executionID) $this->loadModel('execution')->setMenu($executionID);
        if($this->app->tab == 'project' and $executionID) $this->loadModel('execution')->setMenu($executionID);

        if(!empty($_POST['name']))
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->story->successToTask;

            $tasks = $this->storyZen->buildDataForBatchToTask($executionID, $projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $taskIdList = $this->story->batchToTask($executionID, $tasks);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $taskIdList));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('execution', 'task', "executionID=$executionID")));
        }

        if(!$this->post->storyIdList) return $this->locate($this->session->storyList);

        $stories       = $this->story->getByList($_POST['storyIdList']);
        $activeStories = array();
        $storyPairs    = array();
        $hasParent     = false;
        $hasERUR       = false;
        foreach($stories as $story)
        {
            if(str_contains(',draft,reviewing,changing,closed,', ",{$story->status},")) continue;
            if($story->type != 'story')
            {
                $hasERUR = true;
                continue;
            }
            if($story->isParent == '1')
            {
                $hasParent = true;
                continue;
            }

            $activeStories[$story->id] = $story;
            $storyPairs[$story->id]    = $story->title;
        }

        if(empty($activeStories)) return $this->sendError($this->lang->story->noStoryToTask, $this->session->storyList . "#app={$this->app->tab}");

        $execution = $this->execution->fetchByID($executionID);
        if($execution->multiple)  $manageLink = common::hasPriv('execution', 'manageMembers') ? $this->createLink('execution', 'manageMembers', "execution={$execution->id}") : '';
        if(!$execution->multiple) $manageLink = common::hasPriv('project', 'manageMembers') ? $this->createLink('project', 'manageMembers', "projectID={$execution->project}") : '';

        $this->view->title          = $this->lang->story->batchToTask;
        $this->view->executionID    = $executionID;
        $this->view->projectID      = $projectID;
        $this->view->manageLink     = $manageLink;
        $this->view->syncFields     = empty($_POST['fields'])         ? array() : $_POST['fields'];
        $this->view->hourPointValue = empty($_POST['hourPointValue']) ? 0       : $_POST['hourPointValue'];
        $this->view->taskType       = empty($_POST['type'])           ? ''      : $_POST['type'];
        $this->view->stories        = $activeStories;
        $this->view->storyPairs     = $storyPairs;
        $this->view->hasParent      = $hasParent;
        $this->view->hasERUR        = $hasERUR;
        $this->view->modules        = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 'allModule');
        $this->view->members        = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $this->view->storyTasks     = $this->loadModel('task')->getStoryTaskCounts(array_keys($stories), $executionID);

        $this->display();
    }

    /**
     * Batch change the plan of story.
     *
     * @param  int    $planID
     * @param  int    $oldPlanID
     * @access public
     * @return void
     */
    public function batchChangePlan(int $planID, int $oldPlanID = 0)
    {
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = $this->storyZen->convertChildID($this->post->storyIdList);
        $allChanges  = $this->story->batchChangePlan($storyIdList, $planID, $oldPlanID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Batch change branch.
     *
     * @param  int    $branchID
     * @param  string $confirm  yes|no
     * @param  string $storyIdList
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchChangeBranch(int $branchID, string $confirm = '', string $storyIdList = '', string $storyType = 'story')
    {
        if(empty($storyIdList) and empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        if(!empty($_POST['storyIdList'])) $storyIdList = $this->post->storyIdList;
        if(is_string($storyIdList))       $storyIdList = array_filter(explode(',', $storyIdList));
        $storyIdList = $this->storyZen->convertChildID($storyIdList);

        $plans = $this->loadModel('productplan')->getPlansByStories($storyIdList);
        if(empty($confirm))
        {
            $stories             = $this->story->getByList($storyIdList);
            $normalStotyIdList   = '';
            $conflictStoryIdList = '';
            $conflictStoryArray  = array();

            /* Determine whether there is a conflict between the branch of the story and the linked plan. */
            foreach($storyIdList as $storyID)
            {
                if($stories[$storyID]->branch == $branchID) continue;
                if($branchID == BRANCH_MAIN) continue;
                if(!isset($plans[$storyID])) continue;

                foreach($plans[$storyID] as $plan)
                {
                    if($plan->branch == $branchID) continue;

                    $conflictStoryIdList .= "[{$storyID}]";
                    $conflictStoryArray[] = $storyID;
                    break;
                }
            }

            /* Prompt the user whether to continue to modify the conflicting stories branch. */
            if($conflictStoryIdList)
            {
                $normalStotyIdList = array_diff($storyIdList, $conflictStoryArray);
                $normalStotyIdList = implode(',', $normalStotyIdList);
                $storyIdList       = implode(',', $storyIdList);
                $confirmURL        = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID&confirm=yes&storyIdList=$storyIdList&storyType=$storyType");
                $cancelURL         = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID&confirm=no&storyIdList=$normalStotyIdList&storyType=$storyType");
                return $this->send(array('result' => 'success', 'load' => array('confirm' => sprintf($this->lang->story->confirmChangeBranch, $conflictStoryIdList), 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
            }
        }

        $allChanges  = $this->story->batchChangeBranch($storyIdList, $branchID, $confirm, $plans);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Batch change the grade of story.
     *
     * @param  int    $grade
     * @param  string $storyType story|requirement|epic
     * @access public
     * @return void
     */
    public function batchChangeGrade(int $grade, string $storyType = 'story')
    {
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = array_unique($this->post->storyIdList);
        $message     = $this->story->batchChangeGrade($storyIdList, $grade, $storyType);

        $response = array();
        $response['result'] = 'success';
        $response['load']   = false;
        if($message) $response['callback'] = "zui.Modal.alert('{$message}').then((res) => {loadCurrentPage()});";
        if(empty($message)) $response['load'] = true;
        return $this->send($response);
    }

    /**
     * Batch change the stage of story.
     *
     * @param  string    $stage
     * @access public
     * @return void
     */
    public function batchChangeStage(string $stage)
    {
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = $this->storyZen->convertChildID($this->post->storyIdList);
        $message     = $this->story->batchChangeStage($storyIdList, $stage);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('ajax', 'batchOther');

        $response = array();
        $response['result'] = 'success';
        $response['load']   = false;
        if($message) $response['callback'] = "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: '{$message}'}).then((res) => {loadCurrentPage()});";
        if(empty($message)) $response['load'] = true;
        return $this->send($response);
    }

    /**
     * 批量修改需求的父需求。
     * Batch change the parent of story.
     *
     * @param  int    $productID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchChangeParent(int $productID = 0, string $storyType = 'story')
    {
        if($_POST)
        {
            $storyIdList = $this->cookie->checkedItem;
            $result      = $this->story->batchChangeParent($storyIdList, (int)$this->post->parent, $storyType);
            if($result) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('$result');", 'closeModal' => true, 'load' => true));

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $this->view->parents = $this->story->getParentStoryPairs($productID, '', $storyType);
        $this->display();
    }

    /**
     * 需求的指派给页面。
     * Assign the story to a user.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function assignTo(int $storyID)
    {
        if(!empty($_POST))
        {
            $story = form::data($this->config->story->form->assignTo, $storyID)->get();
            $this->story->assign($storyID, $story);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message  = $this->executeHooks($storyID);
            $response = $this->storyZen->getResponseInModal($message);
            if($response) return $this->send($response);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        /* Get story and product to render the view. */
        $story   = $this->story->getById($storyID);
        $product = $this->product->getByID($story->product);
        $users   = $this->config->vision == 'lite' ? $this->user->getTeamMemberPairs($this->session->project) : $this->user->getPairs('nodeleted|noclosed|pofirst|noletter');

        $this->view->title     = zget($product, 'name', $story->title) . $this->lang->hyphen . $this->lang->story->assign;
        $this->view->story     = $story;
        $this->view->actions   = $this->action->getList('story', $storyID);
        $this->view->users     = $users;
        $this->display();
    }

    /**
     * Batch assign to.
     *
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchAssignTo(string $storyType = 'story', string $assignedTo = '')
    {
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        if(empty($assignedTo)) $assignedTo = $this->post->assignedTo;
        $storyIdList = $this->storyZen->convertChildID($this->post->storyIdList);
        $oldStories  = $this->story->getByList($storyIdList);
        $allChanges  = $this->story->batchAssignTo($storyIdList, $assignedTo);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $ignoreStories = array();
        $assignedTwins = array();
        foreach($allChanges as $storyID => $changes)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'closed') $ignoreStories[] = "#{$storyID}";

            /* Sync twins. */
            if(empty($oldStory->twins)) continue;

            $twins = array_unique(array_filter(explode(',', $oldStory->twins)));
            foreach($twins as $i => $twinID)
            {
                if(in_array($twinID, $storyIdList) || isset($assignedTwins[$twinID])) unset($twins[$i]);
            }
            $this->story->syncTwins($storyID, implode(',', $twins), $changes, 'Assigned');
            foreach($twins as $assignedID) $assignedTwins[$assignedID] = $assignedID;
        }
        $this->loadModel('score')->create('ajax', 'batchOther');

        $response = array('result' => 'success', 'load' => true);
        if($ignoreStories) $response['ignoreMessage'] = sprintf($this->lang->story->ignoreClosedStory, implode(',', $ignoreStories));
        return $this->send($response);
    }

    /**
     * 查看需求的相关任务。
     * Tasks of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function tasks(int $storyID, int $executionID = 0)
    {
        $tasks = $this->loadModel('task')->getListByStory($storyID, $executionID);

        $this->view->tasks   = $tasks;
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->summary = $this->execution->summary($tasks);
        $this->view->story   = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * 查看需求的相关缺陷。
     * Bugs of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function bugs(int $storyID, int $executionID = 0)
    {
        $this->view->bugs  = $this->loadModel('bug')->getStoryBugs($storyID, $executionID);
        $this->view->users = $this->user->getPairs('noletter');
        $this->view->story = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * 查看需求的相关用例。
     * Cases of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function cases(int $storyID)
    {
        $this->view->cases      = $this->loadModel('testcase')->getStoryCases($storyID);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->resultList = $this->lang->testcase->resultList;
        $this->view->story      = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * 展示用户需求关联的软件需求，或软件需求关联的用户需求。
     * Show the URs/SRs of the SR/UR.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function relation(int $storyID, string $storyType = 'story')
    {
        $story        = $this->story->getById($storyID);
        $storyType    = $storyType != $story->type ? $story->type : $storyType;
        $selectFields = array('id', 'pri', 'title', 'plan', 'openedBy', 'assignedTo', 'estimate', 'status');

        $this->view->relation  = $this->story->getStoryRelation($storyID, $storyType, $selectFields);
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->storyType = $storyType;
        $this->view->story     = $story;
        $this->display();
    }

    /**
     * 需求详情页关联需求。
     * Link story in view.
     *
     * @param  int    $storyID
     * @param  string $type          link|remove
     * @param  int    $linkedStoryID
     * @param  string $browseType    ''|bySearch
     * @param  int    $queryID       0|
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $storyID, string $type = 'link', int $linkedStoryID = 0, string $browseType = '', int $queryID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->commonAction($storyID);
        $story = $this->story->getById($storyID);

        if($type == 'remove')
        {
            $this->story->unlinkStory($storyID, $linkedStoryID);
            return $this->send(array('result' => 'success', 'load' => true));
        }

        if($_POST)
        {
            $this->story->linkStories($storyID, $this->post->stories);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'callback' => "const modal = zui.Modal.query('.modal-dialog[data-auto-load=\"story:$storyID\"]'); if(typeof(modal) !== 'undefined') modal.render(); else loadCurrentPage();", 'closeModal' => true));
        }

        /* Get story, product, products, and queryID. */
        $products = $this->product->getPairs('', 0, '', 'all');

        /* Build search form. */
        $actionURL = $this->createLink('story', 'linkStory', "storyID=$storyID&type=$type&linkedStoryID=$linkedStoryID&browseType=bySearch&queryID=myQueryID&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->product->buildSearchForm($story->product, $products, $queryID, $actionURL, 'all', (string)$story->branch);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get stories to link. */
        $stories2Link = $this->story->getStories2Link($storyID, $browseType, $queryID, $pager);

        /* Assign. */
        $this->view->title         = $this->lang->story->linkStory . "STORY" . $this->lang->hyphen .$this->lang->story->linkStory;
        $this->view->type          = $type;
        $this->view->pager         = $pager;
        $this->view->stories2Link  = $stories2Link;
        $this->view->maxGradeGroup = $this->story->getMaxGradeGroup();
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * 需求的父需求变更时，子需求确认变更。
     * Confirm the change of the parent story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function processStoryChange(int $storyID)
    {
        $story = $this->story->fetchByID($storyID);

        if($story->parent <= 0 && $this->config->edition == 'ipd') return $this->fetch('demand', 'processDemandChange', "objectID=$storyID&type=story");

        $parent = $this->story->fetchByID($story->parent);

        $this->dao->update(TABLE_STORY)->set('parentVersion')->eq($parent->version)->where('id')->eq($storyID)->exec();
        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
    }

    /**
     * 获取执行的需求列表信息用于html下拉列表。
     * AJAX: get stories of a execution in html select.
     *
     * @param  int        $executionID
     * @param  int        $productID
     * @param  int|string $branch      0|all|integer
     * @param  int        $moduleID
     * @param  int        $storyID
     * @param  string     $pageType    batch
     * @param  string     $type        full|short
     * @param  string     $status      all|unclosed
     * @access public
     * @return void
     */
    public function ajaxGetExecutionStories(int $executionID, int $productID = 0, int|string $branch = 0, int $moduleID = 0, int $storyID = 0, string $pageType = '', string $type = 'full', string $status = 'all')
    {
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }

        $stories = $this->story->getExecutionStoryPairs($executionID, $productID, $branch, $moduleID, $type, $status, 'story', false);

        if($this->app->getViewType() === 'json')
        {
            return print(json_encode($stories));
        }
        elseif($this->app->getViewType() == 'mhtml')
        {
            return print(html::select('story', empty($stories) ? array() : $stories, $storyID, 'onchange=setStoryRelated()'));
        }
        elseif($pageType == 'batch')
        {
            $storyList = array();
            foreach($stories as $id => $name) $storyList[] = array('value' => $id, 'text' => $name);
            return $this->send($storyList);
        }
        else
        {
            $items = $this->story->addGradeLabel($stories);
            return print(json_encode($items));
        }
    }

    /**
     * 获取产品的需求列表信息用于html下拉列表。
     * AJAX: get the stories of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  string $onlyOption
     * @param  string $status      ''|all|noclosed|changing|active|draft|closed|reviewing
     * @param  int    $limit
     * @param  string $type        full|all
     * @param  int    $hasParent   0|1
     * @param  int    $objectID    projectID|executionID
     * @param  int    $isHTML      0|1
     * @access public
     * @return void
     */
    public function ajaxGetProductStories(int $productID, int $branch = 0, int $moduleID = 0, int $storyID = 0, string $onlyOption = 'false', string $status = '', int $limit = 0, string $type = 'full', int $hasParent = 1, int $objectID = 0, int $isHTML = 1)
    {
        $hasParent = $hasParent >= 1 ? true : false;

        $modules = array();
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $modules  = $this->tree->getAllChildID($moduleID);
        }

        $storyStatus = $this->story->getStatusList($status);
        if($storyStatus == 'active') $storyStatus = 'active,reviewing';

        if($objectID)
        {
            $stories = $this->story->getExecutionStoryPairs($objectID, $productID, $branch, $modules, $type, 'all', 'story', $hasParent);
        }
        else
        {
            $stories = $this->story->getProductStoryPairs($productID, $branch, $modules, $storyStatus, 'id_desc', $limit, $type, 'story', $hasParent);
        }
        if(!in_array($this->app->tab, array('execution', 'project')) and empty($stories)) $stories = $this->story->getProductStoryPairs($productID, $branch, 0, $storyStatus, 'id_desc', $limit, $type, 'story', $hasParent);
        if($storyID && !isset($stories[$storyID]))
        {
            $story = $this->story->fetchByID($storyID);
            if(!$moduleID || in_array($story->module, $modules)) $stories = arrayUnion($stories, array($storyID => $storyID . ':' . $story->title));
        }

        if($isHTML == 0)
        {
            $storyList = array();
            foreach($stories as $storyID => $storyName) $storyList[] = array('value' => $storyID, 'text' => $storyName);
            $storyList = $this->story->addGradeLabel($stories);
            return $this->send($storyList);
        }

        $items = $this->story->addGradeLabel($stories);
        return print(json_encode($items));
    }

    /**
     * AJAX: search stories of a product as json
     *
     * @param  string $key       #storyID|storyID|keyword
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  string $status    noclosed
     * @param  int    $limit     50|0
     * @access public
     * @return void
     */
    public function ajaxSearchProductStories(string $key, int $productID, int $branch = 0, int $moduleID = 0, int $storyID = 0, string $status = 'noclosed', int $limit = 50)
    {
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }

        $storyStatus = array();
        if($status == 'noclosed')
        {
            $storyStatus = $this->lang->story->statusList;
            unset($storyStatus['closed']);
            $storyStatus = array_keys($storyStatus);
        }

        $stories     = $this->story->getProductStoryPairs($productID, $branch, $moduleID, $storyStatus);
        $result      = array();
        $resultCount = 0;
        foreach ($stories as $id => $story)
        {
            if($limit > 0 && $resultCount > $limit) break;
            if(('#' . $id) === $key || stripos($story,  $key) !== false)
            {
                $result[$id] = $story;
                $resultCount++;
            }
        }
        if($resultCount < 1) $result['info'] = $this->lang->noResultsMatch;

        echo json_encode($result);
    }

    /**
     * 获取关闭需求页面重复需求的下拉列表。
     * AJAX: get the duplicated stories of a story.
     *
     * @param  int    $storyID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetDuplicatedStories(int $storyID, int $productID)
    {
        $product     = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fields('name, id, `type`')->fetch();
        $story       = $this->story->fetchByID($storyID);
        $storyBranch = $story->branch > 0 ? $story->branch : '0';
        $branch      = $product->type == 'branch' ? $storyBranch : 'all';

        $productStories = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $story->type);
        $children       = $this->story->getAllChildId($storyID);
        $productStories = array_diff_key($productStories, array_flip($children));
        if(isset($productStories[$storyID])) unset($productStories[$storyID]);

        $items = array();
        foreach($productStories as $storyID => $storyName) $items[] = array('text' => $storyName, 'value' => $storyID);

        return print(json_encode($items));
    }

    /**
     * 获取用例可关联的需求下拉列表。
     * AJAX: get the stories of a case.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  int    $branch
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function ajaxGetCaseStories(int $productID = 0, int $moduleID = 0, int|string $branch = '', int $storyID = 0)
    {
        if($storyID)
        {
            $story = $this->story->fetchByID($storyID);
            if(empty($moduleID)) $moduleID = $story->module;
        }

        $modules = array();
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $modules  = $this->tree->getAllChildID($moduleID);
        }

        $stories = $this->story->getProductStoryPairs($productID, $branch, $modules, 'active,reviewing', 'id_desc', 0, '', 'story', false);
        if($this->app->tab != 'qa' && $this->app->tab != 'product' && $this->app->tab != 'my')
        {
            $projectID = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            if($projectID) $stories = $this->story->getExecutionStoryPairs($projectID, $productID, $branch, $modules, 'full', 'all', 'story', false);
        }
        if(!in_array($this->app->tab, array('execution', 'project')) and empty($stories)) $stories = $this->story->getProductStoryPairs($productID, $branch, 0, 'active,reviewing', 'id_desc', 0, '', 'story', false);

        if($storyID && !isset($stories[$storyID])) $stories = arrayUnion($stories, array($storyID => $storyID . ':' . $story->title));

        $stories = $this->story->addGradeLabel($stories);
        return print(json_encode($stories));
    }

    /**
     * 获取需求详情和操作日志。
     * AJAX: get the actions and detail of the story for web app.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function ajaxGetDetail(int $storyID)
    {
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->story   = $this->story->getByID($storyID);
        $this->display();
    }

    /**
     * 获取需求层级下拉列表及默认值。
     * AJAX: get the grade of a story.
     *
     * @param  int    $storyID
     * @param  string $type
     * @param  int    $grade
     * @access public
     * @return void
     */
    public function ajaxGetGrade(int $storyID, string $type = 'story', int $grade = 0)
    {
        $story = $this->story->fetchByID($storyID);
        $gradeOptions = $this->story->getGradeOptions($story, $type, (array)$grade);

        $items = array();
        foreach($gradeOptions as $grade => $name) $items[] = array('text' => $name, 'value' => $grade);

        return $this->send(array('items' => array_values($items), 'default' => key($gradeOptions)));
    }

    /**
     * 检查需求的等级是否超出系统设置。
     * AJAX: check the grade of a story.
     *
     * @param  int    $storyID
     * @param  int    $newGrade
     * @access public
     * @return bool
     */
    public function ajaxCheckGrade(int $storyID, int $newGrade)
    {
        $oldStory     = $this->story->fetchByID($storyID);
        $story        = clone $oldStory;
        $story->grade = $newGrade;

        if($story->grade > $oldStory->grade)
        {
            if(!$this->story->checkGrade($story, $oldStory)) return print(json_encode(array('result' => false, 'message' => dao::getError())));
        }

        return print(json_encode(array('result' => true)));
    }

    /**
     * 获取需求的信息。
     * AJAX: get module of a story.
     *
     * @param  int    $storyID
     * @param  string $pageType batch
     * @access public
     * @return mixed
     */
    public function ajaxGetInfo(int $storyID, string $pageType = '')
    {
        $story = $this->story->getByID($storyID);
        if(empty($story)) return;

        $storyInfo['moduleID'] = $story->module;
        $storyInfo['estimate'] = $story->estimate;
        $storyInfo['pri']      = $story->pri;
        $storyInfo['spec']     = html_entity_decode($story->spec, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
        $storyInfo['status']   = $story->status;

        if($pageType == 'batch') return $this->send(array('storyInfo' => $storyInfo));

        return print(json_encode($storyInfo));
    }

    /**
     * 获取某产品下的父需求列表。
     * AJAX: get the parent story.
     *
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function ajaxGetParentStory(int $productID, int $storyID = 0)
    {
        if($storyID)
        {
            $story   = $this->story->fetchByID($storyID);
            $stories = $this->story->getParentStoryPairs($story->product, $story->parent, $story->type, $storyID);
        }
        else
        {
            $stories = $this->story->getParentStoryPairs($productID);
        }

        $items = array();
        foreach($stories as $storyID => $storyTitle)
        {
            if(empty($storyID)) continue;
            $items[] = array('text' => $storyTitle, 'value' => $storyID);
        }

        return $this->send($items);
    }

    /**
     * 查看需求的报告。
     * The report page.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $storyType
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  string $chartType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function report(int $productID, int $branchID, string $storyType = 'story', string $browseType = 'unclosed', int $moduleID = 0, string $chartType = 'pie', int $projectID = 0)
    {
        $this->loadModel('report');
        $this->view->charts = array();
        $this->view->datas  = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->story->$chartFunc($storyType);
                $chartOption = $this->lang->story->report->$chart;
                if(!empty($chartType)) $chartOption->type = $chartType;
                $this->story->mergeChartOption($chart);

                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $this->story->replaceURLang($storyType);

        $product = $this->product->getByID($productID);
        if(strpos('project,execution', $this->app->tab) !== false)
        {
            $project = $this->dao->findByID($projectID)->from(TABLE_PROJECT)->fetch();
            if($project->type == 'project')
            {
                $this->loadModel('project')->setMenu($projectID);
                if($project and $project->model == 'waterfall') unset($this->lang->story->report->charts['storiesPerPlan']);
                $this->view->projectID = $projectID;
            }
            else
            {
                $this->loadModel('execution')->setMenu($projectID);
                $this->view->executionID = $projectID;
            }

            if(!$project->hasProduct)
            {
                unset($this->lang->story->report->charts['storiesPerProduct']);

                if(!$project->multiple) unset($this->lang->story->report->charts['storiesPerPlan']);
            }
        }
        else
        {
            $this->product->setMenu($productID, $branchID);
        }

        $this->view->title         = $product->name . $this->lang->hyphen . $this->lang->story->reportChart;
        $this->view->productID     = $productID;
        $this->view->branchID      = $branchID;
        $this->view->browseType    = $browseType;
        $this->view->storyType     = $storyType;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
        $this->view->projectID     = $projectID;
        $this->view->checkedCharts = $this->post->charts ? implode(',', $this->post->charts) : '';
        $this->display();
    }

    /**
     * 导出需求数据。
     * Get the data of the stories to export.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $storyType   requirement|story
     * @access public
     * @return void
     */
    public function export(int $productID, string $orderBy, int $executionID = 0, string $browseType = '', string $storyType = 'story')
    {
        /* format the fields of every story in order to export data. */
        if($_POST)
        {
            $this->loadModel('transfer');
            $postData = form::data($this->config->transfer->form->export)->get();

            $this->session->set("{$storyType}TransferParams", array('productID' => $productID, 'executionID' => $executionID));

            /* 给评审过的人员添加下拉选择，方便再次导入时转换成待评审人员。*/
            /* Add a drop-down selection to the reviewer to facilitate the conversion to the reviewer during import. */
            $this->config->story->dtable->fieldList['reviewedBy']['control']    = 'multiple';
            $this->config->story->dtable->fieldList['reviewedBy']['dataSource'] = array('module' => 'story', 'method' => 'getProductReviewers', 'params' => array('productID' => (int)$productID));

            if($executionID) $this->lang->story->title = $this->lang->story->name;

            /* Create field lists. */
            if(!$productID or $browseType == 'bysearch')
            {
                $this->config->story->dtable->fieldList['branch']['dataSource']           = array('module' => 'branch', 'method' => 'getAllPairs');
                $this->config->story->dtable->fieldList['module']['dataSource']['method'] = 'getAllModulePairs';
                $this->config->story->dtable->fieldList['module']['dataSource']['params'] = 'story';

                $this->config->story->dtable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairsByIdList', 'params' => $executionID);
                $this->config->story->dtable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs', 'params' => $executionID);

                $products      = $this->loadModel('product')->getPairs('all', 0, '', 'all');
                $productIdList = array_keys($products);

                $this->config->story->dtable->fieldList['plan']['dataSource'] = array('module' => 'productplan', 'method' => 'getPairs', 'params' => array($productIdList));
            }

            $this->fetch('transfer', 'export', "model=$storyType");
        }

        $this->story->replaceURLang($storyType);

        $project  = null;
        $hasBranch = false;
        if($executionID)
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            $fileName  = $execution->name . $this->lang->dash . $this->lang->common->story;
            $project   = $execution;
            if($execution->type == 'execution') $project = $this->project->getById($execution->project);
            $this->lang->story->title = $this->lang->story->name;

            $products  = $this->loadModel('product')->getProducts($executionID);
            foreach($products as $product)
            {
                if($product->type != 'normal') $hasBranch = true;
            }
        }
        else
        {
            $productName = $this->lang->product->all;
            if($productID)
            {
                $product     = $this->product->getById($productID);
                $productName = $product->name;

                if($product->shadow) $project = $this->project->getByShadowProduct($productID);
                if($product->type != 'normal') $hasBranch = true;
            }
            if(isset($this->lang->product->featureBar['browse'][$browseType]))
            {
                $browseType = $this->lang->product->featureBar['browse'][$browseType];
            }
            else
            {
                $browseType = isset($this->lang->product->moreSelects[$browseType]) ? $this->lang->product->moreSelects[$browseType] : '';
            }

            $fileName = $productName . $this->lang->dash . $browseType . $this->lang->common->story;
        }

        /* Unset branch field.  */
        if(!$hasBranch) $this->config->story->exportFields = str_replace(', branch', '', $this->config->story->exportFields);

        /* If or vision, unset plan field. */
        if($this->config->vision == 'or') $this->config->story->exportFields = str_replace(', plan,', ',', $this->config->story->exportFields);

        /* Unset product field when in single project.  */
        if(isset($project->hasProduct) && !$project->hasProduct)
        {
            $filterFields = array(', product,', ', branch,');
            if($project->model != 'scrum') $filterFields[] = ', plan,';
            $this->config->story->exportFields = str_replace($filterFields, ',', $this->config->story->exportFields);
        }

        /* Append workflow field. */
        if($this->config->edition != 'open')
        {
            $exportFlowFields = $this->loadModel('workflowfield')->getExportFields($storyType);
            foreach($exportFlowFields as $field => $name)
            {
                $this->config->story->exportFields .= ",{$field}";
                $this->lang->story->{$field} = $name;
            }
        }

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->story->exportFields;
        $this->view->customExport    = true;
        $this->view->storyType       = $storyType;
        $this->display();
    }

    /**
     * 通过AJAX方式获取用户需要处理的需求。
     * AJAX: get stories of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id       the id of the select control.
     * @param  int    $appendID
     * @param  string $storyType
     * @access public
     * @return string
     */
    public function ajaxGetUserStories(int $userID = 0, string $id = '', int $appendID = 0, $storyType = 'story')
    {
        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $stories = $this->story->getUserStoryPairs($user->account, 10, $storyType, '', $appendID);

        $items = array();
        foreach($stories as $storyID => $storyTitle) $items[] = array('text' => $storyTitle, 'value' => $storyID);

        $fieldName = $id ? "stories[$id]" : $storyType;
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * 用AJAX方式获取需求的指派给。
     * AJAX: get story assignee.
     *
     * @param  string        $type      create|review|change
     * @param  int           $storyID
     * @param  array|string  $assignees
     * @access public
     * @return mixed
     */
    public function ajaxGetAssignedTo(string $type = '', int $storyID = 0, array|string $assignees = '')
    {
        $users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        if($type == 'create')
        {
            $selectUser = is_array($assignees) ? current($assignees) : '';

            return print(html::select('assignedTo', $users, $selectUser, "class='from-control picker-select'"));
        }

        if($type == 'review')
        {
            $moduleName      = $this->app->rawModule;
            $story           = $this->story->getByID($storyID);
            $reviewers       = $this->story->getReviewerPairs($storyID, $story->version);
            $isChanged       = $story->changedBy ? true : false;
            $superReviewers  = trim(zget($this->config->{$moduleName}, 'superReviewers', ''), ',');
            $isSuperReviewer = strpos(",{$superReviewers},", ",{$this->app->user->account},") !== false;

            if(count($reviewers) == 1)
            {
                $selectUser = $isChanged ? $story->changedBy : $story->openedBy;
            }
            else
            {
                unset($reviewers[$this->app->user->account]);
                foreach($reviewers as $account => $result)
                {
                    if(empty($result))
                    {
                        $selectUser = $account;
                        break;
                    }
                    else
                    {
                        $selectUser = $isChanged ? $story->changedBy : $story->openedBy;
                    }
                }
            }

            if($isSuperReviewer !== false) $selectUser = $isChanged ? $story->changedBy : $story->openedBy;

            return print(html::select('assignedTo', $users, $selectUser, "class='from-control picker-select'"));
        }

        if($type == 'change')
        {
            $selectUser = is_array($assignees) ? current($assignees) : '';

            return print(html::select('assignedTo', $users, $selectUser, "class='from-control picker-select'"));
        }

        return false;

    }

    /**
     * 移除需求的孪生需求。
     * AJAX: Deleted story twin.
     *
     * @access public
     * @return void
     */
    public function ajaxRelieveTwins()
    {
        $twinID = !empty($_POST['twinID']) ? $_POST['twinID'] : 0;
        $story  = $this->story->getByID((int)$twinID);
        $twins  = explode(',', trim($story->twins, ','));

        if(empty($story->twins)) return $this->send(array('result' => 'fail'));

        $this->story->relieveTwins($story->product, (int)$twinID);

        if(!dao::isError()) $this->loadModel('action')->create('story', (int)$twinID, 'relieved');
        return $this->send(array('result' => 'success', 'twinsCount' => count($twins)-1));
    }

    /**
     * 获取需求ID和需求概要信息键值对的html信息。批量关闭时如果原因是重复了，需要选择重复的需求。
     * AJAX: Get the html with story pairs to choose the duplicated story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function ajaxGetDuplicatedStory(int $storyID)
    {
        $story   = $this->story->getByID($storyID);
        $stories = $this->story->getProductStoryPairs($story->product, $story->branch, 0, 'all', 'id_desc', 0, '', $story->type);

        $items = array();
        foreach($stories as $id => $storyTitle)
        {
            if(empty($id)) continue;
            if($id == $storyID) continue;
            $items[] = array('text' => $storyTitle, 'value' => $id, 'keys' => $storyTitle);
        }
        return print(json_encode($items));
    }

    /**
     * 创建代码分支。
     * Create repo branch.
     *
     * @param  int    $storyID
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function createBranch(int $storyID, int $repoID = 0)
    {
        return print($this->fetch('repo', 'createBranch', array('objectID' => $storyID, 'repoID' => $repoID)));
    }

    /**
     * 取消代码分支的关联。
     * Unlink code branch.
     *
     * @access public
     * @return void
     */
    public function unlinkBranch()
    {
        return print($this->fetch('repo', 'unlinkBranch'));
    }
}
