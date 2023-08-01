<?php
/**
 * The control file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        list($productID, $objectID) = $this->storyZen->setMenuForCreate($productID, $objectID);
        if($productID == 0 && $objectID == 0) return $this->locate($this->createLink('product', 'create'));

        if(!empty($_POST))
        {
            helper::setcookie('lastStoryModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);

            /* Get story data from post. */
            $storyData = $this->storyZen->buildStoryForCreate($objectID, $bugID);
            if(!$storyData) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $response  = $this->storyZen->checkRepeatStory($storyData, $objectID);
            if($response) return $this->send($response);

            /* Insert story data. */
            $createFunction = empty($storyData->branches) ? 'create' : 'createTwins';
            $storyID        = $this->story->{$createFunction}($storyData, $objectID, $bugID, $extra);
            if(empty($storyID) || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $productID = $this->post->product ? $this->post->product : $productID;
            $message   = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->post->status == 'draft' ? $this->lang->story->saveDraftSuccess : $this->lang->saveSuccess;
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $storyID));

            /* Get response when create in modal. */
            $response = $this->storyZen->responseAfterCreateInModal($message);
            if($response) return $this->send($response);

            $response = array('result' => 'success', 'message' => $message);
            if($this->post->newStory)
            {
                $response['message'] = $message . $this->lang->story->newStory;
                $response['locate']  = $this->createLink('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&story=$copyStoryID&objectID=$objectID&bugID=$bugID&planID=$planID&todoID=$todoID&extra=$extra&storyType=$storyType");
                return $this->send($response);
            }

            $response['locate'] = $this->storyZen->getAfterCreateLocation($productID, $branch, $objectID, $storyID, $storyType);
            return $this->send($response);
        }

        /* Init vars. */
        $initStory = $this->storyZen->initStoryForCreate($planID, $copyStoryID, $bugID, $todoID);

        /* Get form fields. */
        $this->storyZen->setViewVarsForKanban($objectID, $this->story->parseExtra($extra));
        $fields = $this->storyZen->getFormFieldsForCreate($productID, $branch, $objectID, $initStory);
        $fields = $this->storyZen->setModuleField($fields, $moduleID);
        $fields = $this->storyZen->removeFormFieldsForCreate($fields, $storyType);

        /* Set Custom. */
        foreach(explode(',', $this->config->story->list->customCreateFields) as $field) $customFields[$field] = $this->lang->story->$field;

        $this->view->title        = $this->view->product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->fields       = $fields;
        $this->view->blockID      = $this->storyZen->getAssignMeBlockID();
        $this->view->type         = $storyType;
        $this->view->customFields = $customFields;

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
            $result = $this->loadModel('common')->removeDuplicate('story', (object)$_POST, "product={$productID}");
            $_POST  = (array)$result['data'];

            $stories = $this->storyZen->buildStoriesForBatchCreate($productID, $storyType);
            if(empty($stories)) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->noData));
            if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $storyIdList = $this->story->batchCreate($stories, $productID, $branch, $storyType, $storyID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Project or execution linked stories. */
            if($executionID)
            {
                $lanes    = array();
                $products = array();
                foreach($storyIdList as $i => $newStoryID)
                {
                    if(isset($_POST['lanes'])) $lanes[$newStoryID] = $_POST['lanes'][$i];
                    $products[$newStoryID] = $productID;
                }

                if($this->session->project && $executionID != $this->session->project) $this->execution->linkStory($this->session->project, $storyIdList);
                $this->execution->linkStory($executionID, $storyIdList, $extra, $lanes);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $stories));
            if(isonlybody()) return $this->send($this->storyZen->responseAfterCreateInModal($this->lang->saveSuccess, $executionID));

            $locateLink = $this->storyZen->getAfterBatchCreateLocation($productID, $branch, $executionID, $storyID, $storyType);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $this->storyZen->setMenuForBatchCreate($productID, $branch, $executionID, $extra);

        /* Check can subdivide or not. */
        $product = $this->product->getByID($productID);
        if($product) $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
        if($storyID)
        {
            $story = $this->story->getByID($storyID);
            $this->view->storyTitle = isset($story->title) ? $story->title : '';
            if(!$this->story->checkCanSubdivide($story, !empty($product->shadow))) return print(js::alert($this->lang->story->errorNotSubdivide) . js::locate('back'));
        }

        /* The 'batchCreateFields' of global variable $config will be changed and used by the following business logic. */
        $customFields = $this->storyZen->getCustomFields($this->config, $storyType, $this->view->hiddenPlan, $product);
        $showFields   = $this->storyZen->getShowFields($this->config->story->custom->batchCreateFields, $storyType, $product);

        $fields = $this->storyZen->getFormFieldsForBatchCreate($productID, $branch, $executionID, $storyType);
        $fields = $this->storyZen->removeFormFieldsForBatchCreate($fields, $this->view->hiddenPlan, isset($this->view->execution) ? $this->view->execution->type : '');

        $this->view->title        = $product->name . $this->lang->colon . ($storyID ? $this->lang->story->subdivide : $this->lang->story->batchCreate);
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;
        $this->view->product      = $product;
        $this->view->productID    = $productID;
        $this->view->storyID      = $storyID;
        $this->view->moduleID     = $moduleID;
        $this->view->executionID  = $executionID;
        $this->view->type         = $storyType;
        $this->view->fields       = $fields;
        $this->view->stories      = $this->storyZen->getDataFromUploadImages($productID, $moduleID, $plan);
        $this->view->storyTitle   = isset($story->title) ? $story->title : '';
        $this->view->forceReview  = $this->story->checkForceReview();

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
        }
        elseif($this->app->tab == 'execution')
        {
            if(empty($projectID)) $projectID = $this->session->execution;
            $this->loadModel('execution')->setMenu($projectID);
        }
        elseif($this->app->tab == 'qa')
        {
            $products = $this->product->getProductPairsByProject(0, 'noclosed');
            $this->loadModel('qa')->setMenu($products, $story->product);
        }
        else
        {
            $this->product->setMenu($story->product, $story->branch);
        }

        /* Assign. */
        $this->view->product          = $this->product->getByID($story->product);
        $this->view->productID        = $this->view->product->id;
        $this->view->products         = $this->product->getPairs();
        $this->view->story            = $story;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($story->product, 'story', 0, $story->branch);
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
    public function edit($storyID, $kanbanGroup = 'default', $storyType = 'story')
    {
        $this->loadModel('file');
        $this->app->loadLang('bug');
        $story = $this->story->getById($storyID, 0, true);
        $this->commonAction($storyID);

        if(!empty($_POST))
        {
            $this->story->update($storyID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution')
                {
                    $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                    $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';

                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $executionLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban($kanbanData)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $executionLaneType, $executionGroupBy, $taskSearchValue);
                        $kanbanType      = $executionLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => true));
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));
            $params = $this->app->rawModule == 'story' ? "storyID=$storyID&version=0&param=0&storyType=$storyType" : "storyID=$storyID";
            return $this->send(array('result' => 'success', 'load' => $this->createLink($this->app->rawModule, 'view', $params), 'closeModal' => true, 'message' => $this->lang->saveSuccess));
        }

        /* Sort products. */
        $myProducts     = array();
        $othersProducts = array();
        $products       = $this->loadModel('product')->getList();

        foreach($products as $product)
        {
            if($product->status == 'normal' and $product->PO == $this->app->user->account) $myProducts[$product->id] = $product->name;
            if($product->status == 'normal' and $product->PO != $this->app->user->account) $othersProducts[$product->id] = $product->name;
        }
        $products = $myProducts + $othersProducts;

        /* Assign. */
        $product = $this->product->getById($story->product);
        $stories = $this->story->getParentStoryPairs($story->product, $story->parent);
        if(isset($stories[$storyID])) unset($stories[$storyID]);

        /* Get users. */
        $users = $this->user->getPairs('pofirst|nodeleted|noclosed', "$story->assignedTo,$story->openedBy,$story->closedBy");

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $objectID  = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            $products  = $this->product->getProductPairsByProject($objectID);
            $this->view->objectID = $objectID;
        }

        /* Hidden some fields of projects without products. */
        $this->view->hiddenProduct = false;
        $this->view->hiddenParent  = false;
        $this->view->hiddenPlan    = false;
        $this->view->hiddenURS     = false;
        $this->view->teamUsers     = array();

        if($product->shadow)
        {
            $project = $this->project->getByShadowProduct($product->id);
            $this->view->teamUsers     = $this->project->getTeamMemberPairs($project->id);
            $this->view->hiddenProduct = true;
            $this->view->hiddenParent  = true;

            if($project->model !== 'scrum')  $this->view->hiddenPlan = true;
            if(!$project->multiple)
            {
                $this->view->hiddenPlan = true;
                unset($this->lang->story->stageList[''], $this->lang->story->stageList['wait'], $this->lang->story->stageList['planned']);
            }
            if($project->model === 'kanban') $this->view->hiddenURS  = true;
        }

        /* Display status of branch. */
        $branches = $this->loadModel('branch')->getList($product->id, isset($objectID) ? $objectID : 0, 'all');
        $branchOption    = array();
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchOption[$branchInfo->id]    = $branchInfo->name;
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        /* Get story reviewers. */
        $reviewedReviewer = array();
        $reviewerList     = $this->story->getReviewerPairs($story->id, $story->version);
        $reviewedBy       = explode(',', trim($story->reviewedBy, ','));
        foreach($reviewedBy as $reviewer) $reviewedReviewer[] = zget($users, $reviewer);

        /* Get product reviewers. */
        $productReviewers = $product->reviewer;
        if(!$productReviewers and $product->acl != 'open') $productReviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Process the module when branch products are switched to normal products. */
        if($product->type == 'normal' and !empty($story->branch)) $this->view->moduleOptionMenu += $this->tree->getModulesName($story->module);

        $storyBranch    = $story->branch > 0 ? $story->branch : '0';
        $branch         = $product->type == 'branch' ? $storyBranch : 'all';
        $productStories = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $story->type);

        if($story->type == 'requirement') $this->lang->story->notice->reviewerNotEmpty = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->notice->reviewerNotEmpty);

        $this->view->title            = $this->lang->story->edit . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->story            = $story;
        $this->view->twins            = empty($story->twins) ? array() : $this->story->getByList($story->twins);
        $this->view->stories          = $stories;
        $this->view->users            = $users;
        $this->view->product          = $product;
        $this->view->plans            = $this->loadModel('productplan')->getPairsForStory($story->product, $story->branch == 0 ? 'all' : $story->branch, 'skipParent');
        $this->view->products         = $products;
        $this->view->productStories   = $productStories;
        $this->view->branchOption     = $branchOption;
        $this->view->branchTagOption  = $branchTagOption;
        $this->view->branches         = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->reviewers        = array_keys($reviewerList);
        $this->view->reviewedReviewer = $reviewedReviewer;
        $this->view->lastReviewer     = $this->story->getLastReviewer($story->id);
        $this->view->productReviewers = $this->user->getPairs('noclosed|nodeleted', array_keys($reviewerList), 0, $productReviewers);

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
        $this->storyZen->setMenuForBatchEdit($productID, $branch, $executionID, $from);

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

        if(!$this->post->storyIdList) return $this->send(array('result' => 'success', 'load' => $this->session->storyList));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);

        /* Get edited stories. */
        $stories = $this->story->getByList($storyIdList);
        if(empty($stories)) return $this->send(array('result' => 'success', 'load' => $this->session->storyList));

        /* Filter twins. */
        $twins = '';
        foreach($stories as $id => $story)
        {
            if(empty($story->twins)) continue;
            $twins .= "#$id ";
            unset($stories[$id]);
        }
        if(!empty($twins)) $this->view->twinsTip = sprintf($this->lang->story->batchEditTip, $twins);

        $this->loadModel('branch');
        if($productID and !$executionID)
        {
            $product       = $this->product->getByID($productID);
            $branchProduct = $product->type == 'normal' ? false : true;

            $branches        = 0;
            $branchTagOption = array();
            if($branchProduct)
            {
                $branches = $this->branch->getList($productID, $executionID, 'all');
                foreach($branches as $branchInfo) $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
                $branches = array_keys($branches);
            }

            $modulePairs = $this->tree->getOptionMenu($productID, 'story', 0, $branches);
            $moduleList  = $branchProduct ? $modulePairs : array(0 => $modulePairs);

            $modules         = array($productID => $moduleList);
            $plans           = array($productID => $this->productplan->getBranchPlanPairs($productID, '', 'unexpired', true));
            $products        = array($productID => $product);
            $branchTagOption = array($productID => $branchTagOption);
        }
        else
        {
            $branchProduct   = false;
            $modules         = array();
            $branchTagOption = array();
            $products        = array();
            $plans           = array();

            /* Get product id list by the stories. */
            $productIdList = array();
            foreach($stories as $story) $productIdList[$story->product] = $story->product;
            $products = $this->product->getByIdList($productIdList);

            foreach($products as $storyProduct)
            {
                $branches = 0;
                if($storyProduct->type != 'normal')
                {
                    $branches = $this->branch->getList($storyProduct->id, $executionID, 'all');
                    foreach($branches as $branchInfo) $branches[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
                    $branchTagOption[$storyProduct->id] = array(BRANCH_MAIN => $this->lang->branch->main) + $branches;

                    $branches = array_keys($branches);
                }

                $modulePairs = $this->tree->getOptionMenu($storyProduct->id, 'story', 0, $branches);
                $modules[$storyProduct->id] = $storyProduct->type != 'normal' ? $modulePairs : array(0 => $modulePairs);

                $plans[$storyProduct->id] = $this->productplan->getBranchPlanPairs($storyProduct->id, $branches, 'unexpired', true);
                if(empty($plans[$storyProduct->id])) $plans[$storyProduct->id][0] = $plans[$storyProduct->id];

                if($storyProduct->type != 'normal') $branchProduct = true;
            }
        }

        /* Set ditto option for users. */
        $users = $this->loadModel('user')->getPairs('nodeleted|noclosed');

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->story->$field;
        $showFields = $this->config->story->custom->batchEditFields;
        if($storyType == 'requirement')
        {
            unset($customFields['plan']);
            unset($customFields['stage']);
            $showFields = str_replace('plan',  '', $showFields);
            $showFields = str_replace('stage', '', $showFields);
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        /* Append module when change product type. */
        $moduleList       = array(0 => '/');
        $productStoryList = array();
        foreach($stories as $story)
        {
            if(isset($modules[$story->product][$story->branch]))
            {
                $moduleList[$story->id] = $modules[$story->product][$story->branch];
            }
            else
            {
                $moduleList[$story->id] = $modules[$story->product][0] + $this->tree->getModulesName($story->module);
            }

            if($story->status == 'closed')
            {
                $storyProduct = $products[$story->product];
                $storyBranch = $story->branch > 0 ? $story->branch : '0';
                $branch      = $storyProduct->type == 'branch' ? $storyBranch: 'all';
                if(!isset($productStoryList[$story->product][$story->branch])) $productStoryList[$story->product][$story->branch] = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $story->type);
            }

            if(!empty($story->plan) and !isset($plans[$story->product][$story->branch][$story->plan]))
            {
                $plan = $this->dao->select('id,title,begin,end')->from(TABLE_PRODUCTPLAN)->where('id')->eq($story->plan)->fetch();
                $plans[$story->product][$story->branch][$story->plan] = $plan->title . ' [' . $plan->begin . '~' . $plan->end . ']';
            }
        }

        $this->view->title             = $this->lang->story->batchEdit;
        $this->view->users             = $users;
        $this->view->productID         = $productID;
        $this->view->products          = $products;
        $this->view->branchProduct     = $branchProduct;
        $this->view->storyIdList       = $storyIdList;
        $this->view->branch            = $branch;
        $this->view->plans             = $plans;
        $this->view->storyType         = $storyType;
        $this->view->stories           = $stories;
        $this->view->executionID       = $executionID;
        $this->view->branchTagOption   = $branchTagOption;
        $this->view->moduleList        = $moduleList;
        $this->view->productStoryList  = $productStoryList;
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
        $this->loadModel('file');
        if(!empty($_POST))
        {
            $changes = $this->story->change($storyID);
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Changed' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                /* Record submit review action. */
                $story = $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
                if($story->status == 'reviewing') $this->action->create('story', $storyID, 'submitReview');
            }

            $message = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->lang->saveSuccess;

            if(isonlybody())
            {
                $response = $this->storyZen->responseAfterCreateInModal($message);
                if($response) return $this->send($response);
            }
            if(defined('RUN_MODE') and RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            $location = $this->storyZen->getAfterChangeLocation($storyID, $storyType);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $location));
        }

        $this->commonAction($storyID);
        $story = $this->view->story;
        $this->story->getAffectedScope($story);

        /* Assign. */
        $this->view->title            = $this->lang->story->change . "STORY" . $this->lang->colon . $story->title;
        $this->view->users            = $this->user->getPairs('pofirst|nodeleted|noclosed', $story->assignedTo);
        $this->view->fields           = $this->storyZen->getFormFieldsForChange($storyID);
        $this->view->lastReviewer     = $this->story->getLastReviewer($story->id);

        $this->display();
    }

    /**
     * Activate a story.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function activate($storyID, $storyType = 'story')
    {
        if(!empty($_POST))
        {
            $changes = $this->story->activate($storyID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                    $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                    $taskSearchValue   = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData        = $this->loadModel('kanban')->getRDKanban($this->session->execution, $executionLaneType, 'id_desc', 0, $executionGroupBy, $taskSearchValue);
                    $kanbanData        = json_encode($kanbanData);

                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "updateKanban($kanbanData)", 'closeModal' => true));
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
        $this->view->title      = $this->lang->story->activate . "STORY" . $this->lang->colon . $this->view->story->title;
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
        $uri     = $this->app->getURI(true);
        $tab     = $this->app->tab;
        $story   = $this->story->getById($storyID, $version, true);
        $product = $this->product->getByID($story->product);

        if($tab == 'product' and !empty($product->shadow)) return $this->send(array('result' => 'success', 'load' => "{$uri}##app=project"));
        if(!$story) return $this->send(array('result' => 'success', 'message' => $this->lang->notFound, 'load' => $this->createLink($this->config->vision == 'lite' ? 'project' : 'product', 'index')));
        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$story->product,") === false) return $this->send(array('result' => 'success', 'message' => $this->lang->product->accessDenied, 'load' => array('back' => true)));

        $this->session->set('productList', $uri . "#app={$tab}", 'product');
        if(!empty($story->fromBug)) $this->session->set('bugList', $uri, 'qa');

        $version = empty($version) ? $story->version : $version;
        $story   = $this->story->mergeReviewer($story, true);

        $this->app->loadLang('bug');
        $this->commonAction($storyID, $param);

        $bugs          = $this->dao->select('id,title,status,pri,severity')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $fromBug       = $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->eq($story->fromBug)->fetch();
        $cases         = $this->dao->select('id,title,status,pri')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $linkedMRs     = $this->loadModel('mr')->getLinkedMRPairs($storyID, 'story');
        $linkedCommits = $this->loadModel('repo')->getCommitsByObject($storyID, 'story');
        $modulePath    = $this->tree->getParents($story->module);
        $storyModule   = empty($story->module) ? '' : $this->tree->getById($story->module);
        $linkedStories = isset($story->linkStoryTitles) ? array_keys($story->linkStoryTitles) : array();
        $storyProducts = $this->dao->select('id,product')->from(TABLE_STORY)->where('id')->in($linkedStories)->fetchPairs();

        $this->view->hiddenPlan = false;
        $this->view->hiddenURS  = false;
        if(!empty($product->shadow))
        {
            $projectInfo = $this->dao->select('t2.model, t2.multiple')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.product')->eq($product->id)
                ->andWhere('t2.type')->eq('project')
                ->fetch();

            if($projectInfo->model == 'waterfall') $this->view->hiddenPlan = true;
            if($projectInfo->model == 'kanban')
            {
                $this->view->hiddenPlan = true;
                $this->view->hiddenURS  = true;
            }
            if(!$projectInfo->multiple) $this->view->hiddenPlan = true;
        }

        if($product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $reviewers = $this->story->getReviewerPairs($storyID, $story->version);
        $execution = empty($story->execution) ? array() : $this->dao->findById($story->execution)->from(TABLE_EXECUTION)->fetch();
        $project   = $param ? $this->dao->findById($param)->from(TABLE_PROJECT)->fetch() : array();

        $this->view->title         = "STORY #$story->id $story->title - $product->name";
        $this->view->branches      = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->twins         = !empty($story->twins) ? $this->story->getByList($story->twins) : array();
        $this->view->bugs          = $bugs;
        $this->view->fromBug       = $fromBug;
        $this->view->cases         = $cases;
        $this->view->story         = $story;
        $this->view->linkedMRs     = $linkedMRs;
        $this->view->linkedCommits = $linkedCommits;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->reviewers     = $reviewers;
        $this->view->relations     = $this->story->getStoryRelation($story->id, $story->type);
        $this->view->executions    = $this->execution->getPairs(0, 'all', 'nocode');
        $this->view->execution     = $execution;
        $this->view->project       = $project;
        $this->view->storyModule   = $storyModule;
        $this->view->modulePath    = $modulePath;
        $this->view->storyProducts = $storyProducts;
        $this->view->version       = $version;
        $this->view->preAndNext    = $this->loadModel('common')->getPreAndNextObject('story', $storyID);
        $this->view->builds        = $this->loadModel('build')->getStoryBuilds($storyID);
        $this->view->releases      = $this->loadModel('release')->getStoryReleases($storyID);

        $this->display();
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
    public function delete($storyID, $confirm = 'no', $from = '', $storyType = 'story')
    {
        $story = $this->story->getById($storyID);
        if($story->parent < 0) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->story->cannotDeleteParent}');"));

        if($confirm == 'no')
        {
            if($story->type == 'requirement') $this->lang->story->confirmDelete = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->confirmDelete);
            $confirmURL = $this->createLink('story', 'delete', "story=$storyID&confirm=yes&from=$from&storyType=$storyType");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->story->confirmDelete}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }
        else
        {
            $this->dao->update(TABLE_STORY)->set('deleted')->eq(1)->where('id')->eq($storyID)->exec();
            $this->loadModel('action')->create($story->type, $storyID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);

            if($story->parent > 0)
            {
                $this->story->updateParentStatus($story->id);
                $this->action->create('story', $story->parent, 'deleteChildrenStory', '', $storyID);
            }

            $this->executeHooks($storyID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

            if($this->app->tab == 'execution' and $from == 'taskkanban')
            {
                $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                $taskSearchValue   = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData        = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $executionLaneType, $executionGroupBy, $taskSearchValue);
                $kanbanType        = $executionLaneType == 'all' ? 'story' : key($kanbanData);
                $kanbanData        = $kanbanData[$kanbanType];
                $kanbanData        = json_encode($kanbanData);
                return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban(\"story\", $kanbanData)"));
            }

            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
            return $this->send(array('result' => 'success', 'load' => array('back' => $locateLink), 'closeModal' => true));
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

            $this->story->review($storyID, $storyData, $this->post->comment);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($storyID);
            if(empty($message)) $message = $this->lang->saveSuccess;

            if(isonlybody())
            {
                if($this->app->tab == 'execution') $this->loadModel('kanban')->updateLane($this->session->execution, 'story', $storyID);

                $response = $this->storyZen->responseAfterCreateInModal($message);
                if($response) return $this->send($response);
            }
            if(defined('RUN_MODE') and RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            $location = $this->storyZen->getAfterReviewLocation($storyID, $storyType, $from);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $location));
        }

        $this->commonAction($storyID);
        $story     = $this->view->story;
        $reviewers = $this->story->getReviewerPairs($storyID, $story->version);
        $this->story->getAffectedScope($story);

        $this->view->title     = $this->lang->story->review . "STORY" . $this->lang->colon . $story->title;
        $this->view->fields    = $this->storyZen->getFormFieldsForReview($storyID);
        $this->view->reviewers = $reviewers;
        $this->view->isLastOne = count(array_diff(array_keys($reviewers), explode(',', $story->reviewedBy))) <= 1;

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

        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);
        $message = $this->story->batchReview($storyIdList, $result, $reason);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('ajax', 'batchOther');

        $response = array();
        $response['result'] = 'success';
        $response['load']   = false;
        if($message) $response['callback'] = "zui.Modal.alert('{$message}').then((res) => {loadCurrentPage()});";
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
    public function recall($storyID, $from = 'list', $confirm = 'no', $storyType = 'story')
    {
        $story = $this->story->getById($storyID);

        if($confirm == 'no')
        {
            $confirmTips = $story->status == 'changing' ? $this->lang->story->confirmRecallChange : $this->lang->story->confirmRecallReview;
            $confirmURL  = $this->createLink('story', 'recall', "storyID=$storyID&from=$from&confirm=yes&storyType=$storyType");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message:'{$confirmTips}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }
        else
        {
            if($story->status == 'changing')  $this->story->recallChange($storyID);
            if($story->status == 'reviewing') $this->story->recallReview($storyID);

            $action = $story->status == 'changing' ? 'recalledChange' : 'Recalled';
            $this->loadModel('action')->create('story', $storyID, $action);

            if($from == 'view')
            {
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
                else
                {
                    $module = 'story';
                    $method = 'view';
                    $params = "storyID=$storyID&version=0&param=0&storyType=$storyType";
                }
                return $this->send(array('result' => 'success', 'load' => $this->createLink($module, $method, $params)));
            }

            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
            return $this->send(array('result' => 'success', 'load' => $locateLink));
        }
    }

    /**
     * Submit review.
     *
     * @param  int    $storyID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function submitReview($storyID, $storyType = 'story')
    {
        if($_POST)
        {
            $changes = $this->story->submitReview($storyID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('story', $storyID, 'submitReview');
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'loadCurrentPage()'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType")));
        }

        /* Get story and product. */
        $story   = $this->story->getById($storyID);
        $product = $this->product->getById($story->product);

        /* Get reviewers. */
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Get story reviewer. */
        $reviewerList = $this->story->getReviewerPairs($story->id, $story->version);
        $story->reviewer = array_keys($reviewerList);

        $this->view->story        = $story;
        $this->view->actions      = $this->action->getList('story', $storyID);
        $this->view->reviewers    = $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);
        $this->view->users        = $this->user->getPairs('noclosed|noletter');
        $this->view->needReview   = (($this->app->user->account == $product->PO or $this->config->story->needReview == 0 or !$this->story->checkForceReview()) and empty($story->reviewer)) ? "checked='checked'" : "";
        $this->view->lastReviewer = $this->story->getLastReviewer($story->id);

        $this->display();
    }

    /**
     * Close a story.
     *
     * @param  int    $storyID
     * @param  string $from      taskkanban
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function close($storyID, $from = '', $storyType = 'story')
    {
        $this->app->loadLang('bug');
        $story = $this->story->getById($storyID);
        $this->commonAction($storyID);

        if(!empty($_POST))
        {
            $changes = $this->story->close($storyID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->story->closeParentRequirement($storyID);

            if($changes)
            {
                $preStatus = $story->status;
                $isChanged = $story->changedBy ? true : false;
                if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';

                $actionID  = $this->action->create('story', $storyID, 'Closed', $this->post->comment, ucfirst($this->post->closedReason) . ($this->post->duplicateStory ? ':' . (int)$this->post->duplicateStory : '') . "|$preStatus");
                $this->action->logHistory($actionID, $changes);
            }

            $this->dao->update(TABLE_STORY)->set('assignedTo')->eq('closed')->where('id')->eq((int)$storyID)->exec();

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution    = $this->execution->getByID($this->session->execution);
                $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $this->loadModel('kanban')->updateLane($this->session->execution, 'story', $storyID);

                    $kanbanData = $this->loadModel('kanban')->getRDKanban($this->session->execution, $executionLaneType, 'id_desc', 0, $executionGroupBy, $rdSearchValue);
                    $kanbanData = json_encode($kanbanData);
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "updateKanban($kanbanData)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $executionLaneType, $executionGroupBy, $taskSearchValue);
                    $kanbanType      = $executionLaneType == 'all' ? 'story' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "updateKanban(\"story\", $kanbanData)"));
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
                }
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "storyID=$storyID&version=0&param=0&storyType=$storyType"), 'closeModal' => true));
        }

        /* Get story and product. */
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, type')->fetch();

        $this->story->replaceURLang($story->type);

        /* Set the closed reason options and remove subdivided options. */
        $reasonList = $this->lang->story->reasonList;
        if($story->status == 'draft') unset($reasonList['cancel']);
        unset($reasonList['subdivided']);

        $storyBranch    = $story->branch > 0 ? $story->branch : '0';
        $branch         = $product->type == 'branch' ? $storyBranch : 'all';
        $productStories = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $storyType);

        $this->view->title      = $this->lang->story->close . "STORY" . $this->lang->colon . $story->title;

        $this->view->product        = $product;
        $this->view->story          = $story;
        $this->view->productStories = $productStories;
        $this->view->actions        = $this->action->getList('story', $storyID);
        $this->view->users          = $this->loadModel('user')->getPairs();
        $this->view->reasonList     = $reasonList;
        $this->display();
    }

    /**
     * Batch close story.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchClose(int $productID = 0, int $executionID = 0, string $storyType = 'story', string $from = '')
    {
        if(!$this->post->storyIdList) return $this->send(array('result' => 'success', 'load' => $this->session->storyList));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);

        $this->app->loadLang('bug');
        $this->story->replaceURLang($storyType);

        if($this->post->comment)
        {
            $stories = $this->storyZen->buildStoriesForBatchClose();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->story->batchClose($stories);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchOther');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->storyList));
        }

        $this->storyZen->setMenuForBatchClose($productID, $executionID, $from);

        /* Get edited stories. */
        $stories     = $this->story->getByList($storyIdList);
        $ignoreTwins = array();
        $twinsCount  = array();
        foreach($stories as $story)
        {
            if(!empty($ignoreTwins) and isset($ignoreTwins[$story->id]))
            {
                unset($stories[$story->id]);
                continue;
            }

            if($story->parent == -1)       $skipStory[]   = $story->id;
            if($story->status == 'closed') $closedStory[] = $story->id;
            if($story->parent == -1 || $story->status == 'closed') unset($stories[$story->id]);

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

        $errorTips = '';
        if(isset($closedStory)) $errorTips .= sprintf($this->lang->story->closedStory, implode(',', $closedStory));
        if(isset($skipStory))   $errorTips .= sprintf($this->lang->story->skipStory, implode(',', $skipStory));

        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->storyType        = $storyType;
        $this->view->twinsCount       = $twinsCount;
        $this->view->errorTips        = $errorTips;

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
    public function batchChangeModule($moduleID, $storyType = 'story')
    {
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = array_unique($this->post->storyIdList);
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
    public function batchToTask($executionID = 0, $projectID = 0)
    {
        if($this->app->tab == 'execution' and $executionID) $this->loadModel('execution')->setMenu($executionID);
        if($this->app->tab == 'project' and $executionID) $this->loadModel('execution')->setMenu($executionID);

        if(!empty($_POST['name']))
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->story->successToTask;

            $tasks = $this->story->batchToTask($executionID, $projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $tasks));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('execution', 'task', "executionID=$executionID")));
        }

        if(!$this->post->storyIdList) return $this->send(array('result' => 'success', 'load' => $this->session->storyList));

        $stories       = $this->story->getByList($_POST['storyIdList']);
        $activeStories = array();
        $storyPairs    = array();
        foreach($stories as $story)
        {
            if(str_contains(',draft,reviewing,changing,closed,', ",{$story->status},")) continue;

            $activeStories[$story->id] = $story;
            $storyPairs[$story->id]    = $story->title;
        }

        if(empty($stories)) return $this->send(array('result' => 'fail', 'message' => $this->lang->story->noStoryToTask, 'load' => $this->session->storyList));

        $this->view->title          = $this->lang->story->batchToTask;
        $this->view->executionID    = $executionID;
        $this->view->syncFields     = empty($_POST['fields']) ? array() : $_POST['fields'];
        $this->view->hourPointValue = empty($_POST['hourPointValue']) ? 0 : $_POST['hourPointValue'];
        $this->view->taskType       = empty($_POST['type']) ? '' : $_POST['type'];
        $this->view->stories        = $activeStories;
        $this->view->storyPairs     = $storyPairs;
        $this->view->modules        = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 0, 'allModule');
        $this->view->members        = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $this->view->storyTasks     = $this->loadModel('task')->getStoryTaskCounts(array_keys($stories), $executionID);

        $this->display();
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
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = array_unique($this->post->storyIdList);
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
    public function batchChangeBranch($branchID, $confirm = '', $storyIdList = '', $storyType = 'story')
    {
        if(empty($storyIdList) and empty($_POST['storyIdList'])) return print(js::locate($this->session->storyList, 'parent'));
        if(!empty($_POST['storyIdList'])) $storyIdList = $this->post->storyIdList;
        $plans       = $this->loadModel('productplan')->getPlansByStories($storyIdList);
        if(empty($confirm))
        {
            $stories             = $this->story->getByList($storyIdList);
            $normalStotyIdList   = '';
            $conflictStoryIdList = '';
            $conflictStoryArray  = array();

            /* Determine whether there is a conflict between the branch of the story and the linked plan. */
            foreach($storyIdList as $storyID)
            {
                if($stories[$storyID]->branch != $branchID and $branchID != BRANCH_MAIN and isset($plans[$storyID]))
                {
                    foreach($plans[$storyID] as $plan)
                    {
                        if($plan->branch != $branchID)
                        {
                            $conflictStoryIdList .= '[' . $storyID . ']';
                            $conflictStoryArray[] = $storyID;
                            break;
                        }
                    }
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
                return print(js::confirm(sprintf($this->lang->story->confirmChangeBranch, $conflictStoryIdList ), $confirmURL, $cancelURL));
            }
        }

        if(is_string($storyIdList)) $storyIdList = array_filter(explode(',', $storyIdList));
        $storyIdList = array_unique($storyIdList);
        $allChanges  = $this->story->batchChangeBranch($storyIdList, $branchID, $confirm, $plans);
        if(dao::isError()) return print(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::reload('parent');
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
        if(empty($_POST['storyIdList'])) return $this->send(array('result' => 'success', 'load' => true));

        $storyIdList = array_unique($this->post->storyIdList);
        $message     = $this->story->batchChangeStage($storyIdList, $stage);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('ajax', 'batchOther');

        $response = array();
        $response['result'] = 'success';
        $response['load']   = false;
        if($message) $response['callback'] = "zui.Modal.alert('{$message}').then((res) => {loadCurrentPage()});";
        if(empty($message)) $response['load'] = true;
        return $this->send($response);

    }

    /**
     * Assign to.
     *
     * @param  int    $storyID
     * @param  string $kanbanGroup
     * @param  string $from taskkanban
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function assignTo($storyID, $kanbanGroup = 'default', $from = '', $storyType = 'story')
    {
        if(!empty($_POST))
        {
            $changes = $this->story->assign($storyID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('story', $storyID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution         = $this->execution->getByID($this->session->execution);
                $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $executionLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "updateKanban($kanbanData)", 'closeModal' => true));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $executionLaneType, $executionGroupBy, $taskSearchValue);
                    $kanbanType      = $executionLaneType == 'all' ? 'story' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "updateKanban('$executionLaneType', $kanbanData)", 'closeModal' => true));
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
                }
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        /* Get story and product. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs();

        $this->view->title      = zget($products, $story->product, '') . $this->lang->colon . $this->lang->story->assign;
        $this->view->story      = $story;
        $this->view->storyType  = $storyType;
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->users      = $this->config->vision == 'lite' ? $this->loadModel('user')->getTeamMemberPairs($this->session->project) : $this->loadModel('user')->getPairs('nodeleted|noclosed|pofirst|noletter');

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

        if(!$assignedTo) $assignedTo = $this->post->assignedTo;
        $storyIdList = array_unique($this->post->storyIdList);
        $oldStories  = $this->story->getByList($storyIdList);

        $allChanges = $this->story->batchAssignTo($storyIdList, $assignedTo);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $ignoreStories = array();
        $assignedTwins = array();
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Assigned', '', $assignedTo);
            $this->action->logHistory($actionID, $changes);

            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'closed') $ignoreStories[] = "#{$storyID}";

            /* Sync twins. */
            if(!empty($oldStory->twins))
            {
                $twins = $oldStory->twins;
                foreach(explode(',', $twins) as $twinID)
                {
                    if(in_array($twinID, $storyIdList) || isset($assignedTwins[$twinID])) $twins = str_replace(",$twinID,", ',', $twins);
                }
                $this->story->syncTwins($storyID, trim($twins, ','), $changes, 'Assigned');
                foreach(explode(',', trim($twins, ',')) as $assignedID) $assignedTwins[$assignedID] = $assignedID;
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');

        $response = array('result' => 'success', 'load' => true);
        if($ignoreStories) $response['ignoreMessage'] = sprintf($this->lang->story->ignoreClosedStory, implode(',', $ignoreStories));
        return $this->send($response);
    }

    /**
     * Tasks of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function tasks($storyID, $executionID = 0)
    {
        $this->loadModel('task');
        $tasks = $this->task->getListByStory($storyID, $executionID);

        $this->view->tasks   = $tasks;
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->summary = $this->execution->summary($tasks);
        $this->view->story   = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * Bugs of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function bugs($storyID, $executionID = 0)
    {
        $this->loadModel('bug');

        $this->view->bugs  = $this->bug->getStoryBugs($storyID, $executionID);
        $this->view->users = $this->user->getPairs('noletter');
        $this->view->story = $this->story->getById($storyID);
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
        $this->view->resultList = $this->lang->testcase->resultList;
        $this->view->story      = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * URS of a story.
     *
     * @param  int    $storyID
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function relation($storyID, $storyType = '')
    {
        $selectFields = array('id', 'pri', 'title', 'plan', 'openedBy', 'assignedTo', 'estimate', 'status');

        $this->view->relation  = $this->story->getStoryRelation($storyID, $storyType, $selectFields);
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->storyType = $storyType;
        $this->view->story     = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * If type is linkStories, link related stories else link child stories.
     *
     * @param  int    $storyID
     * @param  string $type
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function linkStory(int $storyID, string $type = 'linkStories', int $linkedStoryID = 0, string $browseType = '', int $queryID = 0, string $storyType = 'story')
    {
        $this->commonAction($storyID);

        if($type == 'remove')
        {
            $this->story->unlinkStory($storyID, $linkedStoryID);
            helper::end();
        }

        if($_POST)
        {
            $this->story->linkStories($storyID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'callback' => 'loadCurrentPage()', 'closeModal' => true));
        }

        /* Get story, product, products, and queryID. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs('', 0, '', 'all');
        $product  = $this->product->getByID($story->product);

        /* Change for requirement story title. */
        if($story->type == 'story')
        {
            $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->create);
            $this->config->product->search['fields']['title'] = $this->lang->story->title;
            unset($this->config->product->search['fields']['stage']);
        }
        else
        {
            $this->lang->story->title = str_replace($this->lang->URCommon, $this->lang->SRCommon, $this->lang->story->title);
        }

        if(!empty($product->shadow))
        {
            unset($this->config->product->search['fields']['plan']);
            unset($this->config->product->search['fields']['product']);
        }

        /* Build search form. */
        $actionURL = $this->createLink('story', 'linkStory', "storyID=$storyID&type=$type&linkedStoryID=$linkedStoryID&browseType=bySearch&queryID=myQueryID&storyType=$storyType");
        $this->product->buildSearchForm($story->product, $products, $queryID, $actionURL);

        /* Get stories to link. */
        $storyType    = $story->type;
        $stories2Link = $this->story->getStories2Link($storyID, $type, $browseType, $queryID, $storyType);

        /* Assign. */
        $this->view->title        = $this->lang->story->linkStory . "STORY" . $this->lang->colon .$this->lang->story->linkStory;
        $this->view->type         = $type;
        $this->view->stories2Link = $stories2Link;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->storyType    = $storyType;

        $this->display();
    }

    /**
     * Link related stories.
     *
     * @param  int    $storyID
     * @param  string $browseType
     * @param  string $excludeStories
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStories($storyID, $browseType = '', $excludeStories = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get story, product, products, and queryID. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs('', 0, '', 'all');
        $product  = $this->product->getByID($story->product);
        $queryID  = ($browseType == 'bySearch') ? (int)$param : 0;
        $type     = $story->type == 'story' ? 'linkRelateSR' : 'linkRelateUR';
        $method   = $story->type == 'story' ? 'linkStories'  : 'linkRequirements';

        if(!empty($product->shadow))
        {
            unset($this->config->product->search['fields']['product']);
            unset($this->config->product->search['fields']['plan']);
        }

        /* Build search form. */
        $actionURL = $this->createLink('story', $method, "storyID=$storyID&browseType=bySearch&excludeStories=$excludeStories&queryID=myQueryID");
        $this->product->buildSearchForm($story->product, $products, $queryID, $actionURL);

        $this->view->story        = $story;
        $this->view->stories2Link = $this->story->getStories2Link($storyID, $type, $browseType, $queryID, $story->type, $pager, $excludeStories);
        $this->view->products     = $products;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager        = $pager;

        $this->display();
    }

    /**
     * Link related requirements.
     *
     * @param  int    $storyID
     * @param  string $browseType
     * @param  string $excludeStories
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkRequirements($storyID, $browseType = '', $excludeStories = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
        $this->config->product->search['fields']['title'] = $this->lang->story->title;
        unset($this->config->product->search['fields']['plan']);
        unset($this->config->product->search['fields']['stage']);

        echo $this->fetch('story', 'linkStories', "storyID=$storyID&browseType=$browseType&excludeStories=$excludeStories&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Process story change.
     *
     * @param  int    $storyID
     * @param  string $result   yes|no
     * @access public
     * @return void
     */
    public function processStoryChange($storyID, $result = 'yes')
    {
        $this->commonAction($storyID);
        $story = $this->story->getByID($storyID);

        if($result == 'no')
        {
            $this->dao->update(TABLE_STORY)->set('URChanged')->eq(0)->where('id')->eq($storyID)->exec();
            return print(js::reload('parent', 'this'));
        }

        $this->view->changedStories = $this->story->getChangedStories($story);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->storyID        = $storyID;
        $this->display();
    }

    /**
     * AJAX: get stories of a execution in html select.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  string $pageType batch
     * @param  string $type full
     * @param  string $status all|unclosed
     * @access public
     * @return void
     */
    public function ajaxGetExecutionStories($executionID, $productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $pageType = '', $type = 'full', $status = 'all')
    {
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }
        $stories = $this->story->getExecutionStoryPairs($executionID, $productID, $branch, $moduleID, $type, $status);
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
            $items = array();
            foreach($stories as $storyID => $storyName) $items[] = array('text' => $storyName, 'value' => $storyID, 'keys' => $storyName);
            return print(json_encode($items));
        }
    }

    /**
     * AJAX: get stories of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  string $onlyOption
     * @param  string $status
     * @param  int    $limit
     * @param  string $type
     * @param  bool   $hasParent
     * @param  int    $executionID
     * @param  int    $isHTML
     * @access public
     * @return void
     */
    public function ajaxGetProductStories($productID, $branch = 0, $moduleID = 0, $storyID = 0, $onlyOption = 'false', $status = '', $limit = 0, $type = 'full', $hasParent = 1, $executionID = 0, $isHTML = 1)
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

        if($status == 'active') $storyStatus = $status;

        if($executionID)
        {
            $stories = $this->story->getExecutionStoryPairs($executionID, $productID, $branch, $moduleID, $type);
        }
        else
        {
            $stories = $this->story->getProductStoryPairs($productID, $branch, $moduleID, $storyStatus, 'id_desc', $limit, $type, 'story', $hasParent);
        }

        if(!in_array($this->app->tab, array('execution', 'project')) and empty($stories)) $stories = $this->story->getProductStoryPairs($productID, $branch, 0, $storyStatus, 'id_desc', $limit, $type, 'story', $hasParent);

        if($isHTML == 0)
        {
            $storyList = array();
            foreach($stories as $storyID => $storyName) $storyList[] = array('value' => $storyID, 'text' => $storyName);
            return $this->send($storyList);
        }

        $items = array();
        foreach($stories as $storyID => $storyName) $items[] = array('text' => $storyName, 'value' => $storyID, 'keys' => $storyName);
        return print(json_encode($items));
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

        echo json_encode($result);
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
     * @param  string $pageType batch
     * @access public
     * @return string|void
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

        if($pageType == 'batch')
        {
            return $this->send(array('storyInfo' => $storyInfo));
        }
        else
        {
            echo json_encode($storyInfo);
        }
    }

    /**
     * AJAX: get the parent story.
     *
     * @param  int    $productID
     * @param  string $labelName
     * @access public
     * @return string
     */
    public function ajaxGetParentStory($productID, $labelName = '')
    {
        $stories = $this->story->getParentStoryPairs($productID);

        $items = array();
        foreach($stories as $storyID => $storyTitle)
        {
            if(empty($storyID)) continue;
            $items[] = array('text' => $storyTitle, 'value' => $storyID);
        }

        return print(json_encode(array('items' => $items, 'name' => $labelName)));
    }

    /**
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
    public function report($productID, $branchID, $storyType = 'story', $browseType = 'unclosed', $moduleID = 0, $chartType = 'pie', $projectID = 0)
    {
        $this->loadModel('report');
        $this->view->charts = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->story->$chartFunc();
                $chartOption = $this->lang->story->report->$chart;
                if(!empty($chartType)) $chartOption->type = $chartType;
                $this->story->mergeChartOption($chart);

                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $this->story->replaceURLang($storyType);

        $this->products = $this->product->getPairs('', 0, '', 'all');
        if(strpos('project,execution', $this->app->tab) !== false)
        {
            $project = $this->dao->findByID($projectID)->from(TABLE_PROJECT)->fetch();
            if($project->type == 'project')
            {
                $this->loadModel('project')->setMenu($projectID);
                if($project and $project->model == 'waterfall') unset($this->lang->story->report->charts['storysPerPlan']);
            }
            else
            {
                $this->loadModel('execution')->setMenu($projectID);
            }

            if(!$project->hasProduct)
            {
                unset($this->lang->story->report->charts['storysPerProduct']);

                if(!$project->multiple) unset($this->lang->story->report->charts['storysPerPlan']);
            }
        }
        else
        {
            $this->product->setMenu($productID, $branchID);
        }

        if($storyType != 'story') unset($this->lang->story->report->charts['storysPerStage']);

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->story->reportChart;
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
     * get data to export
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $storyType requirement|story
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $executionID = 0, $browseType = '', $storyType = 'story')
    {
        /* format the fields of every story in order to export data. */
        if($_POST)
        {
            $this->session->set('storyTransferParams', array('productID' => $productID, 'executionID' => $executionID));
            /* Create field lists. */
            if(!$productID or $browseType == 'bysearch')
            {
                $this->config->story->datatable->fieldList['branch']['dataSource']           = array('module' => 'branch', 'method' => 'getAllPairs', 'params' => 1);
                $this->config->story->datatable->fieldList['module']['dataSource']['method'] = 'getAllModulePairs';
                $this->config->story->datatable->fieldList['module']['dataSource']['params'] = 'story';

                $this->config->story->datatable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairsByIdList', 'params' => $executionID);
                $this->config->story->datatable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs', 'params' => $executionID);

                $productIdList = implode(',', array_flip($this->session->exportProductList));

                $this->config->story->datatable->fieldList['plan']['dataSource'] = array('module' => 'productplan', 'method' => 'getPairs', 'params' => $productIdList);
            }

            $this->post->set('rows', $this->story->getExportStories($executionID, $orderBy, $storyType));
            $this->fetch('transfer', 'export', 'model=story');
        }

        $fileName = $storyType == 'requirement' ? $this->lang->URCommon : $this->lang->SRCommon;
        $project  = null;
        if($executionID)
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            $fileName  = $execution->name . $this->lang->dash . $fileName;
            $project   = $execution;
            if($execution->type == 'execution') $project = $this->project->getById($execution->project);
        }
        else
        {
            $productName = $this->lang->product->all;
            if($productID)
            {
                $product     = $this->product->getById($productID);
                $productName = $product->name;

                if($product->shadow) $project = $this->project->getByShadowProduct($productID);
            }
            if(isset($this->lang->product->featureBar['browse'][$browseType]))
            {
                $browseType = $this->lang->product->featureBar['browse'][$browseType];
            }
            else
            {
                $browseType = isset($this->lang->product->moreSelects[$browseType]) ? $this->lang->product->moreSelects[$browseType] : '';
            }

            $fileName = $productName . $this->lang->dash . $browseType . $fileName;
        }

        /* Unset product field when in single project.  */
        if(isset($project->hasProduct) && !$project->hasProduct)
        {
            $filterFields = array(', product,', ', branch,');
            if($project->model != 'scrum') $filterFields[] = ', plan,';
            $this->config->story->exportFields = str_replace($filterFields, ',', $this->config->story->exportFields);
        }

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->story->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * AJAX: get stories of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id       the id of the select control.
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserStories($userID = '', $id = '', $appendID = 0)
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $stories = $this->story->getUserStoryPairs($user->account, 10, 'story', '', $appendID);

        $items = array();
        foreach($stories as $storyID => $storyTitle) $items[] = array('text' => $storyTitle, 'value' => $storyID);

        $fieldName = $id ? "stories[$id]" : 'story';
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * Ajax get story status.
     *
     * @param  string $method
     * @param  string $params
     * @access public
     * @return void
     */
    public function ajaxGetStatus($method, $params = '')
    {
        parse_str(str_replace(',', '&', $params), $params);
        $status = '';
        if($method == 'create')
        {
            $status = 'draft';
            if(!empty($params['needNotReview'])) $status = 'active';
            if(!empty($params['project']))       $status = 'active';
            if($this->story->checkForceReview()) $status = 'draft';
        }
        elseif($method == 'change')
        {
            $oldStory = $this->dao->findById((int)$params['storyID'])->from(TABLE_STORY)->fetch();
            $status   = $oldStory->status;
            if($params['changing'] and $oldStory->status == 'active' and empty($params['needNotReview']))  $status = 'changing';
            if($params['changing'] and $oldStory->status == 'active' and $this->story->checkForceReview()) $status = 'changing';
            if($params['changing'] and $oldStory->status == 'draft' and $params['needNotReview']) $status = 'active';
        }
        elseif($method == 'review')
        {
            $oldStory = $this->dao->findById((int)$params['storyID'])->from(TABLE_STORY)->fetch();
            $status   = $oldStory->status;
            if($params['result'] == 'revert') $status = 'active';
        }
        echo $status;
    }

    /**
     * Ajax get story assignee.
     *
     * @param  string $type create|review|change
     * @param  int    $storyID
     * @param  array  $assignees
     *
     * @access public
     * @return void
     */
    public function ajaxGetAssignedTo($type = '', $storyID = 0, $assignees = '')
    {
        $users = $this->loadModel('user')->getPairs('noclosed');

        if($type == 'create')
        {
            $selectUser = is_array($assignees) ? current($assignees) : '';

            return print(html::select('assignedTo', $users, $selectUser, "class='from-control picker-select'"));
        }

        if($type == 'review')
        {
            $story           = $this->story->getByID($storyID);
            $reviewers       = $this->story->getReviewerPairs($storyID, $story->version);
            $isChanged       = $story->changedBy ? true : false;
            $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');

            if(count($reviewers) == 1)
            {
                $selectUser = $isChanged ? $story->changedBy : $story->openedBy;
            }
            else
            {
                unset($reviewers[$this->app->user->account]);
                foreach($reviewers as $account => $result)
                {
                    if(!$reviewers[$account])
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
     * AJAX: Get user requirements.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $moduleID
     * @param  string $requirementList
     * @access public
     * @return string
     */
    public function ajaxGetURS($productID, $branchID = 0, $moduleID = 0, $requirementList = 0)
    {
        $moduleIdList = $this->loadModel('tree')->getAllChildId($moduleID);

        $URS = $this->story->getProductStoryPairs($productID, $branchID, $moduleIdList, 'changing,active,reviewing', 'id_desc', 0, '', 'requirement');

        $items = array();
        foreach($URS as $URID => $URTitle)
        {
            if(empty($URID)) continue;
            $items[] = array('text' => $URTitle, 'value' => $URID);
        }

        return print(json_encode(array('name' => 'URS[]', 'multiple' => true, 'defaultValue' => $requirementList, 'items' => $items)));
    }

    /**
     * AJAX: Deleted story twin.
     *
     * @access public
     * @return void
     */
    public function ajaxRelieveTwins()
    {
        $twinID = !empty($_POST['twinID']) ? $_POST['twinID'] : 0;
        $story  = $this->story->getByID($twinID);
        $twins  = explode(',', trim($story->twins, ','));

        if(empty($story->twins)) return $this->send(array('result' => 'fail'));

        /* batchUnset twinID from twins.*/
        $replaceSql = "UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins,',$twinID,', ',') WHERE `product` = $story->product";
        $this->dbh->exec($replaceSql);

        /* Update twins to empty by twinID and if twins eq ','.*/
        $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($twinID)->orWhere('twins')->eq(',')->exec();

        if(!dao::isError()) $this->loadModel('action')->create('story', $twinID, 'relieved');
        return $this->send(array('result' => 'success', 'silbingsCount' => count($twins)-1));
    }

    /**
     * Ajax get story pairs.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function ajaxGetStoryPairs($storyID)
    {
        $this->app->loadLang('bug');
        $story   = $this->story->getByID($storyID);
        $stories = $this->story->getProductStoryPairs($story->product, $story->branch, 0, 'all', 'id_desc', 0, '', $story->type);

        return print html::select("duplicateStory[$storyID]", $stories, '', "class='form-control' placeholder='{$this->lang->bug->placeholder->duplicate}'");
    }
}
