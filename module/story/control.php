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
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
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
    public function create($productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $objectID = 0, $bugID = 0, $planID = 0, $todoID = 0, $extra = '', $storyType = 'story')
    {
        $originProduct = $productID;    // Log the origin product id and use it to create the redirect url.

        /* Whether there is a object to transfer story, for example feedback. */
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if($productID == 0 and $objectID == 0) $this->locate($this->createLink('product', 'create'));

        /* Get product id according to the project id when lite vision todo transfer story */
        if($this->config->vision == 'lite' and $productID == 0)
        {
            $product = $this->loadModel('product')->getProductPairsByProject($objectID);
            if(!empty($product)) $productID = key($product);
        }

        $this->story->replaceURLang($storyType);
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
        }
        elseif($this->app->tab == 'project')
        {
            $objects = $this->project->getPairsByProgram();

            if(empty($objectID)) $objectID = $this->session->project;

            $projectID = $objectID;
            if(!$this->session->multiple)
            {
                $projectID = $this->session->project;
                $objectID  = $this->execution->getNoMultipleID($projectID);
            }
            $projectID = isset($objects[$projectID]) ? $projectID : $this->session->project;
            $projectID = $this->project->saveState($projectID, $objects);
            $this->project->setMenu($projectID);
        }
        else if($this->app->tab == 'execution')
        {
            $objectID = empty($objectID) ? $this->session->execution : $objectID;
            $this->execution->setMenu($objectID);
            $execution = $this->dao->findById((int)$objectID)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $regionPairs = $this->kanban->getRegionPairs($execution->id, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'story');
                $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->executionType = $execution->type;
                $this->view->regionID      = $regionID;
                $this->view->laneID        = $laneID;
                $this->view->regionPairs   = $regionPairs;
                $this->view->lanePairs     = $lanePairs;
            }
        }

        foreach($output as $paramKey => $paramValue)
        {
            if(isset($this->config->story->fromObjects[$paramKey]))
            {
                $fromObjectIDKey  = $paramKey;
                $fromObjectID     = $paramValue;
                $fromObjectName   = $this->config->story->fromObjects[$fromObjectIDKey]['name'];
                $fromObjectAction = $this->config->story->fromObjects[$fromObjectIDKey]['action'];
                break;
            }
        }

        /* If there is a object to transfer story, get it by getById function and set objectID,object in views. */
        if(isset($fromObjectID))
        {
            $fromObject = $this->loadModel($fromObjectName)->getById($fromObjectID);
            if(!$fromObject) return print(js::error($this->lang->notFound) . js::locate('back', 'parent'));

            $this->view->$fromObjectIDKey = $fromObjectID;
            $this->view->$fromObjectName  = $fromObject;
        }

        $copyStoryID = $storyID;

        if(!empty($_POST))
        {
            $response['result'] = 'success';

            setcookie('lastStoryModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);

            $storyResult = $this->story->create($objectID, $bugID, $from = isset($fromObjectIDKey) ? $fromObjectIDKey : '', $extra);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $storyID   = $storyResult['id'];
            $productID = $this->post->product ? $this->post->product : $productID;

            if($storyResult['status'] == 'exists')
            {
                $response['message'] = sprintf($this->lang->duplicate, $this->lang->story->common);
                if($objectID == 0)
                {
                    $response['locate'] = $this->createLink('story', 'view', "storyID={$storyID}&version=0&param=0&storyType=$storyType");
                }
                else
                {
                    $execution          = $this->dao->findById((int)$objectID)->from(TABLE_EXECUTION)->fetch();
                    $moduleName         = $execution->type == 'project' ? 'projectstory' : 'execution';
                    $param              = $execution->type == 'project' ? "projectID=$objectID&productID=$originProduct" : "executionID=$objectID";
                    $response['locate'] = $this->createLink($moduleName, 'story', $param);
                }
                return $this->send($response);
            }

            $action = $bugID == 0 ? 'Opened' : 'Frombug';
            $extra  = $bugID == 0 ? '' : $bugID;
            /* Record related action, for example FromFeedback. */
            if(isset($fromObjectID))
            {
                $action = $fromObjectAction;
                $extra  = $fromObjectID;
            }
            /* Create actions. */
            $storyIds = $storyResult['ids'];
            foreach($storyIds as $idItem) $actionID = $this->action->create('story', $idItem, $action, '', $extra);

            /* Record submit review action. */
            $story = $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
            if($story->status == 'reviewing')
            {
                foreach($storyIds as $idItem) $this->action->create('story', $idItem, 'submitReview');
            }

            if($objectID != 0)
            {
                $object = $this->dao->findById((int)$objectID)->from(TABLE_PROJECT)->fetch();
                if($object->type != 'project')
                {
                    foreach($storyIds as $idItem)
                    {
                        $this->action->create('story', $idItem, 'linked2project', '', $object->project);

                        $actionType = $object->type == 'kanban' ? 'linked2kanban' : 'linked2execution';
                        if($object->multiple) $this->action->create('story', $idItem, $actionType, '', $objectID);
                    }
                }
                else
                {
                    foreach($storyIds as $idItem) $this->action->create('story', $idItem, 'linked2project', '', $objectID);
                }
            }

            if($todoID > 0)
            {
                $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
                $this->action->create('todo', $todoID, 'finished', '', "STORY:$storyID");

                if($this->config->edition == 'biz' || $this->config->edition == 'max')
                {
                    $todo = $this->dao->select('type, idvalue')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
                    if($todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, 'done');
                }
            }

            $message = $this->executeHooks($storyID);
            if($message) $this->lang->saveSuccess = $message;
            $response['message'] = $this->post->status == 'draft' ? $this->lang->story->saveDraftSuccess : $this->lang->saveSuccess;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $storyID));

            /* If link from no head then reload. */
            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);

                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, 0)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
                }
            }

            if($this->post->newStory)
            {
                $response['message'] = $this->lang->story->successSaved . $this->lang->story->newStory;
                $response['locate']  = $this->createLink('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&story=0&objectID=$objectID&bugID=$bugID");
                return $this->send($response);
            }

            $moduleID = $this->post->module ? $this->post->module : 0;
            if($objectID == 0)
            {
                setcookie('storyModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                $branchID  = $this->post->branch  ? $this->post->branch  : $branch;
                $response['locate'] = $this->createLink('product', 'browse', "productID=$productID&branch=$branchID&browseType=&param=0&storyType=$storyType&orderBy=id_desc");
                if($this->session->storyList)
                {
                    /* When copying story in the product plan, return to different pages for story#32949. */
                    if($copyStoryID and strpos($this->session->storyList, 'productplan') !== false)
                    {
                        $storyInfo = $this->story->getByList(array($storyID, $copyStoryID));
                        if($storyInfo[$storyID]->plan == $storyInfo[$copyStoryID]->plan or $storyInfo[$storyID]->product != $storyInfo[$copyStoryID]->product) $response['locate'] = $this->session->storyList;
                    }
                    else
                    {
                        $sessionStoryList = $this->session->storyList;
                        if(!empty($_POST['branches']) and count($_POST['branches']) > 1) $sessionStoryList = preg_replace('/branch=(\d+|[A-Za-z]+)/', 'branch=all', $this->session->storyList);
                        $response['locate'] = $sessionStoryList;
                    }
                }
            }
            else
            {
                setcookie('storyModuleParam', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
                $response['locate'] = $this->session->storyList;
            }
            if($this->app->getViewType() == 'xhtml') $response['locate'] = $this->createLink('story', 'view', "storyID=$storyID", 'html');
            return $this->send($response);
        }

        /* Set products, users and module. */
        if($objectID != 0)
        {
            $onlyNoClosed    = empty($this->config->CRProduct) ? 'noclosed' : '';
            $products        = $this->product->getProductPairsByProject($objectID, $onlyNoClosed);
            $productID       = empty($productID) ? key($products) : $productID;
            $product         = $this->product->getById(($productID and array_key_exists($productID, $products)) ? $productID : key($products));
            $productBranches = $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = (int)key($branches);
        }
        else
        {
            $products = array();
            $productList = $this->product->getOrderedProducts('noclosed');
            foreach($productList as $product) $products[$product->id] = $product->name;
            $product = $this->product->getById($productID ? $productID : key($products));
            if(!isset($products[$product->id])) $products[$product->id] = $product->name;
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        $users = $this->user->getPairs('pdfirst|noclosed|nodeleted');

        $branchData = explode(',', $branch);
        $branch     = current($branchData);
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch === 'all' ? 0 : $branch);

        if(empty($moduleOptionMenu)) return print(js::locate(helper::createLink('tree', 'browse', "productID=$productID&view=story")));

        /* Init vars. */
        $source     = '';
        $sourceNote = '';
        $pri        = 3;
        $estimate   = '';
        $title      = '';
        $spec       = '';
        $verify     = '';
        $keywords   = '';
        $mailto     = '';
        $color      = '';

        if($storyID > 0)
        {
            $story       = $this->story->getByID($storyID);
            $planID      = $story->plan;
            $source      = $story->source;
            $sourceNote  = $story->sourceNote;
            $color       = $story->color;
            $pri         = $story->pri;
            $productID   = $story->product;
            $moduleID    = $story->module;
            $estimate    = $story->estimate;
            $title       = $story->title;
            $spec        = htmlSpecialString($story->spec);
            $verify      = htmlSpecialString($story->verify);
            $keywords    = $story->keywords;
            $mailto      = $story->mailto;
            $category    = $story->category;
            $feedbackBy  = $story->feedbackBy;
            $notifyEmail = $story->notifyEmail;
        }

        if($bugID > 0)
        {
            $oldBug    = $this->loadModel('bug')->getById($bugID);
            $productID = $oldBug->product;
            $source    = 'bug';
            $title     = $oldBug->title;
            $keywords  = $oldBug->keywords;
            $spec      = $oldBug->steps;
            $pri       = !empty($oldBug->pri) ? $oldBug->pri : '3';
            if($oldBug->mailto and strpos($oldBug->mailto, $oldBug->openedBy) === false)
            {
                $mailto = $oldBug->mailto . $oldBug->openedBy . ',';
            }
            else
            {
                $mailto = $oldBug->mailto;
            }
        }

        if($todoID > 0)
        {
            $todo   = $this->loadModel('todo')->getById($todoID);
            $source = 'todo';
            $title  = $todo->name;
            $spec   = $todo->desc;
            $pri    = $todo->pri;
        }

        /* Replace the value of story that needs to be replaced with the value of the object that is transferred to story. */
        if(isset($fromObject))
        {
            if(isset($this->config->story->fromObjects[$fromObjectIDKey]['source']))
            {
                $sourceField = $this->config->story->fromObjects[$fromObjectIDKey]['source'];
                $sourceUser  = $this->loadModel('user')->getById($fromObject->{$sourceField});
                $source      = $sourceUser->role;
                $sourceNote  = $sourceUser->realname;
            }
            else
            {
                $source      = $fromObjectName;
                $sourceNote  = $fromObjectID;
            }

            foreach($this->config->story->fromObjects[$fromObjectIDKey]['fields'] as $storyField => $fromObjectField)
            {
                $storyField = $fromObject->{$fromObjectField};
            }
        }

        /* Get block id of assinge to me. */
        $blockID = 0;
        if(isonlybody())
        {
            $blockID = $this->dao->select('id')->from(TABLE_BLOCK)
                ->where('block')->eq('assingtome')
                ->andWhere('module')->eq('my')
                ->andWhere('account')->eq($this->app->user->account)
                ->orderBy('order_desc')
                ->fetch('id');
        }

        /* Get reviewers. */
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Hidden some fields of projects without products. */
        $this->view->hiddenProduct = false;
        $this->view->hiddenParent  = false;
        $this->view->hiddenPlan    = false;
        $this->view->hiddenURS     = false;
        $this->view->teamUsers     = array();

        if($this->app->tab === 'project' || $this->app->tab === 'execution')
        {
            $project = $this->dao->findById((int)$objectID)->from(TABLE_PROJECT)->fetch();
            if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

            if(empty($project->hasProduct))
            {
                $this->view->teamUsers     = $this->project->getTeamMemberPairs($project->id);
                $this->view->hiddenProduct = true;
                $this->view->hiddenParent  = true;

                if($project->model !== 'scrum')  $this->view->hiddenPlan = true;
                if(!$project->multiple)          $this->view->hiddenPlan = true;
                if($project->model === 'kanban') $this->view->hiddenURS  = true;
            }
        }

        /* Get the module's children id list. */
        $moduleID     = $moduleID ? $moduleID : (int)$this->cookie->lastStoryModule;
        $moduleID     = isset($moduleOptionMenu[$moduleID]) ? $moduleID : 0;
        $moduleIdList = $this->tree->getAllChildId($moduleID);

        /* Set Custom. */
        foreach(explode(',', $this->config->story->list->customCreateFields) as $field) $customFields[$field] = $this->lang->story->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->story->custom->createFields;

        $requirementStatus = strpos($product->vision, 'or') !== false ? 'launched' : 'active';

        $this->view->URS              = $storyType == 'story' ? $this->story->getProductStoryPairs($productID, $branch, $moduleIdList, $requirementStatus, 'id_desc', 0, '', 'requirement') : '';
        $this->view->title            = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID&branch=$branch"), $product->name);
        $this->view->position[]       = $this->lang->story->common;
        $this->view->position[]       = $this->lang->story->create;
        $this->view->gobackLink       = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('product', 'browse', "productID=$productID") : '';
        $this->view->products         = $products;
        $this->view->users            = $users;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = str_replace('2030-01-01', $this->lang->story->undetermined, $this->loadModel('productplan')->getPairsForStory($productID, $branch == 0 ? '' : $branch, 'skipParent|unexpired|noclosed'));
        $this->view->planID           = $planID;
        $this->view->source           = $source;
        $this->view->sourceNote       = $sourceNote;
        $this->view->color            = $color;
        $this->view->pri              = $pri;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;
        $this->view->stories          = $this->story->getParentStoryPairs($productID);
        $this->view->productID        = $productID;
        $this->view->product          = $product;
        $this->view->reviewers        = $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);
        $this->view->objectID         = $objectID;
        $this->view->estimate         = $estimate;
        $this->view->storyTitle       = $title;
        $this->view->spec             = $spec;
        $this->view->verify           = $verify;
        $this->view->keywords         = $keywords;
        $this->view->mailto           = $mailto;
        $this->view->blockID          = $blockID;
        $this->view->needReview       = ($this->app->user->account == $product->PO or $objectID > 0 or $this->config->story->needReview == 0 or !$this->story->checkForceReview()) ? "checked='checked'" : "";
        $this->view->type             = $storyType;
        $this->view->category         = !empty($category) ? $category : 'feature';
        $this->view->feedbackBy       = !empty($feedbackBy) ? $feedbackBy : '';
        $this->view->notifyEmail      = !empty($notifyEmail) ? $notifyEmail : '';
        $this->view->showFeedbackBox  = in_array($source, $this->config->story->feedbackSource);

        $this->display();
    }

    /**
     * Create a batch stories.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $executionID
     * @param  int    $plan
     * @param  string $storyType requirement|story
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $executionID = 0, $plan = 0, $storyType = 'story', $extra = '')
    {
        $this->view->hiddenProduct = false;
        $this->view->hiddenPlan    = false;
        /* Set menu. */
        if($this->app->tab == 'project' and $this->config->vision == 'lite')
        {
            $this->project->setMenu($this->session->project);
        }
        elseif($executionID)
        {
            $execution = $this->dao->findById((int)$executionID)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'project')
            {
                $model = in_array($execution->model, array('waterfallplus', 'ipd')) ? 'waterfall' : $execution->model;
                $model = $execution->model == 'agileplus' ? 'scrum' : $model;
                $this->project->setMenu($executionID);
                $this->lang->navGroup->story = 'project';
                $this->lang->product->menu   = $this->lang->{$model}->menu;
            }
            else
            {
                if($execution->type == 'kanban')
                {
                    $this->loadModel('kanban');
                    $extra = str_replace(array(',', ' '), array('&', ''), $extra);
                    parse_str($extra, $output);

                    $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
                    $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                    $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'story');
                    $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

                    $this->view->regionID    = $regionID;
                    $this->view->laneID      = $laneID;
                    $this->view->regionPairs = $regionPairs;
                    $this->view->lanePairs   = $lanePairs;
                }

                $this->execution->setMenu($executionID);
                $this->lang->navGroup->story = 'execution';
            }
            $this->view->execution = $execution;

            /* Hidden some fields of projects without products. */
            if($this->app->tab == 'project' or $this->app->tab == 'execution')
            {
                $project = $this->dao->findById((int)$executionID)->from(TABLE_PROJECT)->fetch();
                if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

                if(empty($project->hasProduct))
                {
                    $this->view->hiddenProduct = true;

                    if($project->model !== 'scrum') $this->view->hiddenPlan = true;
                    if(!$project->multiple)         $this->view->hiddenPlan = true;
                }
            }
        }
        else
        {
            $this->product->setMenu($productID, $branch);
        }

        /* Clear title when switching products and set the session for the current product. */
        if($productID != $this->cookie->preProductID) unset($_SESSION['storyImagesFile']);
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $this->story->replaceURLang($storyType);

        /* Check can subdivide or not. */
        $product = $this->product->getById($productID);
        if($storyID)
        {
            $story     = $this->story->getById($storyID);
            $storyType = 'story';
            if(($story->status != 'active' or (empty($product->shadow) && $story->type == 'story' && $story->stage != 'wait') or (!empty($product->shadow) && $story->stage != 'projected') or $story->parent > 0) and $this->config->vision != 'lite' and !in_array($story->status, array('launched', 'developing'))) return print(js::alert($this->lang->story->errorNotSubdivide) . js::locate('back'));
        }

        if(!empty($_POST))
        {
            if($executionID)
            {
                $requiredFields = ',' . $this->config->story->create->requiredFields . ',';
                if(strpos($requiredFields, ',plan,') !== false)
                {
                    /* Create a project with no execution, remove plan required check. */
                    $project = $this->dao->findById((int)$executionID)->from(TABLE_PROJECT)->fetch();
                    if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

                    if(empty($project->hasProduct))
                    {
                        if($project->model !== 'scrum' or !$project->multiple) $requiredFields = str_replace(',plan,', ',', $requiredFields);
                    }
                }

                $this->config->story->create->requiredFields = trim($requiredFields, ',');
            }

            $mails = $this->story->batchCreate($productID, $branch, $storyType);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $stories = array();
            foreach($mails as $mail) $stories[] = $mail->storyID;

            $lanes = array();
            if(isset($_POST['lanes']))
            {
                foreach($mails as $i => $mail) $lanes[$mail->storyID] = $_POST['lanes'][$i];
            }

            /* Project or execution linked stories. */
            if($executionID)
            {
                $products = array();
                foreach($mails as $story) $products[$story->storyID] = $productID;
                if($executionID != $this->session->project) $this->execution->linkStory($this->session->project, $stories, $products);
                $this->execution->linkStory($executionID, $stories, $products, $extra, $lanes);
            }

            /* If storyID not equal zero, subdivide this story to child stories and close it. */
            if($storyID and !empty($mails))
            {
                $this->story->subdivide($storyID, $stories);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $stories));

            if(isonlybody())
            {
                $executionID = $executionID ? $executionID : $this->session->execution;
                $execution   = $this->execution->getByID($executionID);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, 0)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'reloadByAjaxForm()'));
                }
            }

            if($storyID)
            {
                if($this->app->tab == 'product')
                {
                    $locateLink = $this->inlink('view', "storyID=$storyID&version=0&param=0&storyType=$storyType");
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
                }
                else
                {
                    /* Lite. */
                    $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('projectstory', 'view', "storyID=$storyID");
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
                }
            }
            elseif($executionID)
            {
                setcookie('storyModuleParam', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->storyList));
            }
            else
            {
                setcookie('storyModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=unclosed&queryID=0&storyType=$storyType");
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
            }
        }

        /* Set branch and module. */
        $products = $this->product->getPairs();
        if($product) $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        if($executionID != 0)
        {
            $productBranches = $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $executionID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = (int)key($branches);
        }
        else
        {
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        $branchData = explode(',', $branch);
        $branch     = current($branchData);

        $moduleOptionMenu          = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch === 'all' ? 0 : $branch);
        $moduleOptionMenu['ditto'] = $this->lang->story->ditto;

        /* Get reviewers. */
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Init vars. */
        $planID   = $plan;
        $pri      = 3;
        $estimate = '';
        $title    = '';
        $spec     = '';

        /* Process upload images. */
        if($this->session->storyImagesFile)
        {
            $files = $this->session->storyImagesFile;
            foreach($files as $fileName => $file)
            {
                $title = $file['title'];
                $titles[$title] = $fileName;
            }
            $this->view->titles = $titles;
        }
        $plans          = $this->loadModel('productplan')->getPairsForStory($productID, ($branch === 'all' or empty($branch)) ? '' : $branch, 'skipParent|unexpired|noclosed');
        $plans['ditto'] = $this->lang->story->ditto;

        $priList          = (array)$this->lang->story->priList;
        $priList['ditto'] = $this->lang->story->ditto;

        $sourceList          = (array)$this->lang->story->sourceList;
        $sourceList['ditto'] = $this->lang->story->ditto;

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->story->$field;
        }

        if($this->view->hiddenPlan) unset($customFields['plan']);

        if($product->type != 'normal')
        {
            $this->config->story->custom->batchCreateFields = sprintf($this->config->story->custom->batchCreateFields, $product->type);
        }
        else
        {
            $this->config->story->custom->batchCreateFields = trim(sprintf($this->config->story->custom->batchCreateFields, ''), ',');
        }

        $showFields = $this->config->story->custom->batchCreateFields;
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }
        if($storyType == 'requirement')
        {
            unset($customFields['plan']);
            $showFields = str_replace('plan', '', $showFields);
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
        $this->view->reviewers        = $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);
        $this->view->users            = $this->user->getPairs('pdfirst|noclosed|nodeleted');
        $this->view->priList          = $priList;
        $this->view->sourceList       = $sourceList;
        $this->view->planID           = $planID;
        $this->view->pri              = $pri;
        $this->view->productID        = $productID;
        $this->view->estimate         = $estimate;
        $this->view->storyTitle       = isset($story->title) ? $story->title : '';
        $this->view->spec             = $spec;
        $this->view->type             = $storyType;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;
        /* When the user is product owner or add story in project or not set review, the default is not to review. */
        $this->view->needReview       = ($this->app->user->account == $product->PO or $executionID > 0 or $this->config->story->needReview == 0 or !$this->story->checkForceReview()) ? 0 : 1;
        $this->view->forceReview      = $this->story->checkForceReview();
        $this->view->executionID      = $executionID;

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
        if($product->shadow)
        {
            $products = $this->product->getPairs('', 0, '', 'all');
        }
        else
        {
            $products = $this->product->getPairs();
        }
        $moduleOptionMenu = $this->tree->getOptionMenu($product->id, $viewType = 'story', 0, $story->branch);

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        elseif($this->app->tab == 'product')
        {
            $this->product->setMenu($product->id, $story->branch);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($this->session->execution);
        }

        $this->story->replaceURLang($story->type);

        /* Assign. */
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[]       = $this->lang->story->common;
        $this->view->product          = $product;
        $this->view->products         = $products;
        $this->view->story            = $story;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($product->id, 0, '', true);
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

        if($story->status != 'draft' and $story->status != 'changing') $this->config->story->edit->requiredFields = '';

        if(!empty($_POST))
        {
            $this->story->update($storyID);
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return print(js::reload('parent.parent'));
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));
            $params = $this->app->rawModule == 'story' ? "storyID=$storyID&version=0&param=0&storyType=$storyType" : "storyID=$storyID";
            return print(js::locate($this->createLink($this->app->rawModule, 'view', $params), 'parent'));
        }

        /* Sort products. */
        $myProducts     = array();
        $othersProducts = array();
        $products       = $this->loadModel('product')->getList();

        foreach($products as $product)
        {
            if($product->status != 'closed' and $product->PO == $this->app->user->account) $myProducts[$product->id] = $product->name;
            if($product->status != 'closed' and !($product->PO == $this->app->user->account)) $othersProducts[$product->id] = $product->name;
            if($product->status == 'closed') continue;
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

        $branch         = $product->type == 'branch' ? ($story->branch > 0 ? $story->branch : '0') : 'all';
        $productStories = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $story->type);

        if($story->type == 'requirement') $this->lang->story->notice->reviewerNotEmpty = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->notice->reviewerNotEmpty);

        $this->view->title            = $this->lang->story->edit . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->position[]       = $this->lang->story->edit;
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
    public function batchEdit($productID = 0, $executionID = 0, $branch = 0, $storyType = 'story', $from = '')
    {
        $this->story->replaceURLang($storyType);

        $this->view->hiddenPlan = false;
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
        }
        else if($this->app->tab == 'project')
        {
            $project = $this->dao->findByID($executionID)->from(TABLE_PROJECT)->fetch();
            if($project->type == 'project')
            {
                if((in_array($project->model, array('waterfallplus', 'waterfall')) and !$project->hasProduct) or !$project->multiple) $this->view->hiddenPlan = true;
                $this->project->setMenu($executionID);
            }
            else
            {
                if(!$project->hasProduct and !$project->multiple) $this->view->hiddenPlan = true;
                $this->execution->setMenu($executionID);
            }
        }
        else if($this->app->tab == 'execution')
        {
            $this->execution->setMenu($executionID);
        }
        else if($this->app->tab == 'qa')
        {
            $this->loadModel('qa')->setMenu('', $productID);
        }
        else if($this->app->tab == 'my')
        {
            $this->loadModel('my');
            if($from == 'work')       $this->lang->my->menu->work['subModule']       = 'story';
            if($from == 'contribute') $this->lang->my->menu->contribute['subModule'] = 'story';
        }

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
                }
            }
            return print(js::locate($this->session->storyList, 'parent'));
        }

        if(!$this->post->storyIdList) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);

        /* Get edited stories. */
        $stories = $this->story->getByList($storyIdList);

        /* Filter twins. */
        $twins = '';
        foreach($stories as $id => $story)
        {
            if(empty($story->twins)) continue;
            $twins .= "#$id ";
            unset($stories[$id]);
        }
        if(!empty($twins)) echo js::alert(sprintf($this->lang->story->batchEditTip, $twins));
        if(empty($stories)) return print(js::locate($this->session->storyList));

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
        $users = array('' => '', 'ditto' => $this->lang->story->ditto) + $users;

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
        if(!$branchProduct) unset($customFields['branch']);
        if($this->view->hiddenPlan) unset($customFields['plan']);
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        /* Judge whether the editedStories is too large and set session. */
        $countInputVars  = count($stories) * (count(explode(',', $this->config->story->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

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
                $branch       = $storyProduct->type == 'branch' ? ($story->branch > 0 ? $story->branch : '0') : 'all';
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
        $this->view->priList           = array('0' => '', 'ditto' => $this->lang->story->ditto) + $this->lang->story->priList;
        $this->view->sourceList        = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->sourceList;
        $this->view->reasonList        = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->reasonList;
        $this->view->stageList         = array('ditto' => $this->lang->story->ditto) + $this->lang->story->stageList;
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
    public function change($storyID, $from = '', $storyType = 'story')
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

            $this->executeHooks($storyID);

            $module = $this->app->tab == 'project' ? 'projectstory' : 'story';

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.parent.updateKanban($kanbanData)"));
                    }
                    if($from == 'taskkanban')
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'reloadByAjaxForm()'));
                }
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

            if($this->app->tab == 'project')
            {
                $module  = 'projectstory';
                $method  = 'view';
                $params  = "storyID=$storyID";
                if(!$this->session->multiple)
                {
                    $module  = 'story';
                    $params .= "&version=0&param={$this->session->project}&storyType=$storyType";
                }
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink($module, $method, $params)));
        }

        $this->commonAction($storyID);
        $story = $this->view->story;
        if(!in_array($story->status, array('active', 'launched', 'developing'))) return print(js::locate($this->session->storyList, 'parent'));
        $this->story->getAffectedScope($story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('execution');

        $reviewer = $this->story->getReviewerPairs($storyID, $story->version);
        $product  = $this->loadModel('product')->getByID($story->product);

        /* Get users in team. */
        $productReviewers = $product->reviewer;
        if(!$productReviewers and $product->acl != 'open') $productReviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Assign. */
        $this->view->title            = $this->lang->story->change . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->twins            = empty($story->twins) ? array() : $this->story->getByList($story->twins);
        $this->view->branches         = $this->loadModel('branch')->getPairs($story->product);
        $this->view->users            = $this->user->getPairs('pofirst|nodeleted|noclosed', $this->view->story->assignedTo);
        $this->view->position[]       = $this->lang->story->change;
        $this->view->needReview       = (($this->app->user->account == $this->view->product->PO or $this->config->story->needReview == 0 or !$this->story->checkForceReview()) and empty($reviewer)) ? "checked='checked'" : "";
        $this->view->reviewer         = implode(',', array_keys($reviewer));
        $this->view->productReviewers = $this->user->getPairs('noclosed|nodeleted', $reviewer, 0, $productReviewers);
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
            if(dao::isError()) return print(js::error(dao::getError()));

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
                    $execLaneType    = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $taskSearchValue);
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this'));
                }
            }
            return print(js::locate($this->createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType"), 'parent'));
        }

        $this->commonAction($storyID);

        /* Assign. */
        $this->view->title      = $this->lang->story->activate . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->users      = $this->user->getPairs('pofirst|nodeleted|noclosed', $this->view->story->closedBy);
        $this->view->position[] = $this->lang->story->activate;
        $this->display();
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  int    $param
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function view($storyID, $version = 0, $param = 0, $storyType = 'story')
    {
        $story = $this->story->getById($storyID, $version, true);

        $linkModuleName = $this->config->vision == 'lite' ? 'project' : 'product';
        if(!$story)
        {
            $story = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
            if($story)
            {
                if(strpos($story->vision, $this->config->vision) === false && $story->status == 'active') return print(js::alert($this->lang->story->storyUnlinkRoadmap) . js::reload('parent'));
            }
            return print(js::error($this->lang->notFound) . js::locate($this->createLink($linkModuleName, 'all')));
        }

        $uri     = $this->app->getURI(true);
        $tab     = $this->app->tab;
        $storyID = (int)$storyID;
        $product = $this->loadModel('product')->getByID($story->product);

        if(!(defined('RUN_MODE') && RUN_MODE == 'api') and $tab == 'product' and !empty($product->shadow))
        {
            $backLink = $this->session->productList ? $this->session->productList : inlink('product', 'all');
            $js       = js::start();
            $js      .= "setTimeout(\"parent.$.apps.open('$uri#app=project')\", 100)";
            $js      .= js::end();
            return print(js::refresh($backLink . '#app=product', 'self', '10') . $js);
        }

        $buildApp   = $tab == 'product' ?   'project' : $tab;
        $releaseApp = $tab == 'execution' ? 'product' : $tab;
        $this->session->set('productList', $uri . "#app={$tab}", 'product');
        $this->session->set('productPlanList', $uri . "#app={$tab}", 'product');
        if(!isonlybody()) $this->session->set('buildList', $uri, $buildApp);
        $this->app->loadLang('bug');

        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$story->product,") === false) return print(js::error($this->lang->product->accessDenied) . js::locate('back'));
        if(!empty($story->fromBug)) $this->session->set('bugList', $uri, 'qa');

        $version = empty($version) ? $story->version : $version;
        $story   = $this->story->mergeReviewer($story, true);

        $this->story->replaceURLang($story->type);

        $plan          = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $bugs          = $this->dao->select('id,title,status,pri,severity')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $fromBug       = $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->eq($story->fromBug)->fetch();
        $cases         = $this->dao->select('id,title,status,pri')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $linkedMRs     = $this->loadModel('mr')->getLinkedMRPairs($storyID, 'story');
        $linkedCommits = $this->loadModel('repo')->getCommitsByObject($storyID, 'story');
        $modulePath    = $this->tree->getParents($story->module);
        $storyModule   = empty($story->module) ? '' : $this->tree->getById($story->module);
        $linkedStories = isset($story->linkStoryTitles) ? array_keys($story->linkStoryTitles) : array();
        $storyProducts = $this->dao->select('id,product')->from(TABLE_STORY)->where('id')->in($linkedStories)->fetchPairs();

        /* Set the menu. */
        $from = $this->app->tab;
        if($from == 'execution')
        {
            $result = $this->execution->setMenu($param);
            if($result) return;
        }
        elseif($from == 'project')
        {
            $projectID = $param ? $param : $this->session->project;
            if(!$projectID) $projectID = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->fetch('project');
            $this->loadModel('project')->setMenu($projectID);
        }
        elseif($from == 'qa')
        {
            $products = $this->product->getProductPairsByProject(0, 'noclosed');
            $this->loadModel('qa')->setMenu($products, $story->product);
        }
        else
        {
            $this->product->setMenu($story->product, $story->branch);
        }

        $this->view->hiddenPlan = false;
        $this->view->hiddenURS  = false;
        if(!empty($product->shadow))
        {
            $projectInfo = $this->dao->select('t2.model, t2.multiple, t2.id')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.product')->eq($product->id)
                ->andWhere('t2.type')->eq('project')
                ->fetch();

            if($projectInfo->model == 'waterfall')
            {
                $this->view->hiddenPlan = true;
            }
            elseif($projectInfo->model == 'kanban')
            {
                $this->view->hiddenPlan = true;
                $this->view->hiddenURS  = true;
            }

            if(!$projectInfo->multiple) $this->view->hiddenPlan = true;
            $this->loadModel('project')->setMenu($projectInfo->id);
        }

        if($product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $reviewers  = $this->story->getReviewerPairs($storyID, $story->version);
        $reviewedBy = trim($story->reviewedBy, ',');

        $this->executeHooks($storyID);

        if($this->config->edition == 'ipd')
        {
            $this->view->roadmaps = $this->loadModel('roadmap')->getPairs($story->product);
        }

        $title      = "STORY #$story->id $story->title - $product->name";
        $position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $position[] = $this->lang->story->common;
        $position[] = $this->lang->story->view;

        $execution = empty($story->execution) ? array() : $this->dao->findById($story->execution)->from(TABLE_EXECUTION)->fetch();
        $project   = $param ? $this->dao->findById($param)->from(TABLE_PROJECT)->fetch() : array();

        $this->view->title         = $title;
        $this->view->position      = $position;
        $this->view->product       = $product;
        $this->view->branches      = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->twins         = !empty($story->twins) ? $this->story->getByList($story->twins) : array();
        $this->view->plan          = $plan;
        $this->view->bugs          = $bugs;
        $this->view->fromBug       = $fromBug;
        $this->view->cases         = $cases;
        $this->view->story         = $story;
        $this->view->linkedMRs     = $linkedMRs;
        $this->view->linkedCommits = $linkedCommits;
        $this->view->track         = $this->story->getTrackByID($story->id);
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->reviewers     = $reviewers;
        $this->view->relations     = $this->story->getStoryRelation($story->id, $story->type);
        $this->view->executions    = $this->execution->getPairs(0, 'all', 'nocode');
        $this->view->execution     = $execution;
        $this->view->project       = $project;
        $this->view->actions       = $this->action->getList('story', $storyID);
        $this->view->storyModule   = $storyModule;
        $this->view->modulePath    = $modulePath;
        $this->view->storyProducts = $storyProducts;
        $this->view->version       = $version;
        $this->view->preAndNext    = $this->loadModel('common')->getPreAndNextObject('story', $storyID);
        $this->view->from          = $from;
        $this->view->param         = $param;
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
        if($story->parent < 0) return print(js::alert($this->lang->story->cannotDeleteParent));

        if($confirm == 'no')
        {
            if($storyType == 'requirement') $this->lang->story->confirmDelete = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->confirmDelete);
            return print(js::confirm($this->lang->story->confirmDelete, $this->createLink('story', 'delete', "story=$storyID&confirm=yes&from=$from&storyType=$storyType")));
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
                $execLaneType    = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return print(js::closeModal('parent', '', "parent.updateKanban(\"story\", $kanbanData)"));
            }

            if(isonlybody()) return print(js::reload('parent.parent'));

            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
            return print(js::locate($locateLink, 'parent'));
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
    public function review($storyID, $from = 'product', $storyType = 'story')
    {
        if(!empty($_POST))
        {
            $this->story->review($storyID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution')
                {
                    $this->loadModel('kanban')->updateLane($this->session->execution, 'story', $storyID);

                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                    }
                    if($from == 'taskkanban')
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
                }
            }

            if(defined('RUN_MODE') and RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));

            if($from == 'project')
            {
                $module = 'projectstory';
                $method = 'view';
                $params = "storyID=$storyID";
            }
            elseif($from == 'execution')
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($execution->multiple)
                {
                    $module = 'execution';
                    $method = 'storyView';
                    $params = "storyID=$storyID";
                }
                else
                {
                    $module = 'story';
                    $method = 'view';
                    $params = "storyID=$storyID&version=0&param={$this->session->execution}&storyType=$storyType";
                }
            }
            else
            {
                $module = 'story';
                $method = 'view';
                $params = "storyID=$storyID&version=0&param=0&storyType=$storyType";
            }
            return print(js::locate($this->createLink($module, $method, $params), 'parent'));
        }

        /* Get story and product. */
        $story   = $this->story->getById($storyID);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        $this->story->replaceURLang($story->type);

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        elseif($this->app->tab == 'product')
        {
            $this->product->setMenu($product->id, $story->branch);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($this->session->execution);
        }

        /* Set the review result options. */
        $reviewers = $this->story->getReviewerPairs($storyID, $story->version);
        $this->lang->story->resultList = $this->lang->story->reviewResultList;

        if($story->status == 'reviewing')
        {
            if($story->version == 1) unset($this->lang->story->resultList['revert']);
            if($story->version > 1)  unset($this->lang->story->resultList['reject']);
        }

        $this->view->title      = $this->lang->story->review . "STORY" . $this->lang->colon . $story->title;
        $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[] = $this->lang->story->common;
        $this->view->position[] = $this->lang->story->review;

        $this->view->product   = $product;
        $this->view->story     = $story;
        $this->view->actions   = $this->action->getList('story', $storyID);
        $this->view->users     = $this->loadModel('user')->getPairs('nodeleted|noclosed', "$story->lastEditedBy,$story->openedBy");
        $this->view->reviewers = $reviewers;
        $this->view->isLastOne = count(array_diff(array_keys($reviewers), explode(',', $story->reviewedBy))) == 1 ? true : false;

        /* Get the affcected things. */
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('execution');

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
    public function batchReview($result, $reason = '', $storyType = 'story')
    {
        if(!$this->post->storyIdList) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);
        $this->story->batchReview($storyIdList, $result, $reason);

        if(dao::isError()) return print(js::error(dao::getError()));
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::reload('parent');
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
            return print(js::confirm($confirmTips, $this->createLink('story', 'recall', "storyID=$storyID&from=$from&confirm=yes&storyType=$storyType")));
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
                return print(js::locate($this->createLink($module, $method, $params), 'parent'));
            }

            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
            return print(js::locate($locateLink, 'parent'));
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

            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType"), 'parent'));
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
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }
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
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $this->loadModel('kanban')->updateLane($this->session->execution, 'story', $storyID);

                    $kanbanData = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"story\", $kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
                }
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $storyID));
            }
            else
            {
                return print(js::locate(inlink('view', "storyID=$storyID&version=0&param=0&storyType=$storyType"), 'parent'));
            }
        }

        /* Get story and product. */
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, `type`')->fetch();

        $this->story->replaceURLang($story->type);

        /* Set the closed reason options and remove subdivided options. */
        $reasonList = $this->lang->story->reasonList;
        if($story->status == 'draft') unset($reasonList['cancel']);
        unset($reasonList['subdivided']);

        $branch         = $product->type == 'branch' ? ($story->branch > 0 ? $story->branch : '0') : 'all';
        $productStories = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $storyType);

        $this->view->title      = $this->lang->story->close . "STORY" . $this->lang->colon . $story->title;
        $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[] = $this->lang->story->common;
        $this->view->position[] = $this->lang->story->close;

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
    public function batchClose($productID = 0, $executionID = 0, $storyType = 'story', $from = '')
    {
        $this->app->loadLang('bug');
        if(!$this->post->storyIdList) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);

        $this->story->replaceURLang($storyType);

        /* Get edited stories. */
        $stories = $this->story->getByList($storyIdList);
        $productList      = array();
        $ignoreTwins      = array();
        $twinsCount       = array();
        foreach($stories as $story)
        {
            if(!empty($ignoreTwins) and isset($ignoreTwins[$story->id]))
            {
                unset($stories[$story->id]);
                continue;
            }

            if($story->parent == -1)
            {
                $skipStory[] = $story->id;
                unset($stories[$story->id]);
            }
            if($story->status == 'closed')
            {
                $closedStory[] = $story->id;
                unset($stories[$story->id]);
            }

            $storyProduct = isset($productList[$story->product]) ? $productList[$story->product] : $this->product->getByID($story->product);
            $branch       = $storyProduct->type == 'branch' ? ($story->branch > 0 ? $story->branch : '0') : 'all';

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

        if($this->post->comments)
        {
            $data       = fixer::input('post')->get();
            $allChanges = $this->story->batchClose();

            if($allChanges)
            {
                foreach($allChanges as $storyID => $changes)
                {
                    $preStatus = $stories[$storyID]->status;
                    $isChanged = $stories[$storyID]->changedBy ? true : false;
                    if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';

                    $actionID = $this->action->create('story', $storyID, 'Closed', htmlSpecialString($this->post->comments[$storyID]), ucfirst($this->post->closedReasons[$storyID]) . ($this->post->duplicateStoryIDList[$storyID] ? ':' . (int)$this->post->duplicateStoryIDList[$storyID] : '') . "|$preStatus");
                    $this->action->logHistory($actionID, $changes);

                    if(!empty($stories[$storyID]->twins)) $this->story->syncTwins($storyID, $stories[$storyID]->twins, $changes, 'Closed');
                }

                $this->dao->update(TABLE_STORY)->set('assignedTo')->eq('closed')->where('id')->in(array_keys($allChanges))->exec();
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->storyList, 'parent'));
        }

        $errorTips = '';
        if(isset($closedStory)) $errorTips .= sprintf($this->lang->story->closedStory, join(',', $closedStory));
        if(isset($skipStory))   $errorTips .= sprintf($this->lang->story->skipStory, join(',', $skipStory));
        if(isset($skipStory) || isset($closedStory)) echo js::alert($errorTips);

        /* The stories of a product. */
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
            $product = $this->product->getByID($productID);
            $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
            $this->view->title      = $product->name . $this->lang->colon . $this->lang->story->batchClose;
        }
        /* The stories of a execution. */
        elseif($executionID)
        {
            $this->lang->story->menu      = $this->lang->execution->menu;
            $this->lang->story->menuOrder = $this->lang->execution->menuOrder;
            $this->execution->setMenu($executionID);
            $execution = $this->execution->getByID($executionID);
            $this->view->position[] = html::a($this->createLink('execution', 'story', "executionID=$execution->id"), $execution->name);
            $this->view->title      = $execution->name . $this->lang->colon . $this->lang->story->batchClose;
        }
        else
        {
            if($this->app->tab == 'project')
            {
                $this->project->setMenu($this->session->project);
                $this->view->title = $this->lang->story->batchEdit;
            }
            else
            {
                $this->lang->story->menu      = $this->lang->my->menu;
                $this->lang->story->menuOrder = $this->lang->my->menuOrder;

                if($from == 'work')       $this->lang->my->menu->work['subModule']       = 'story';
                if($from == 'contribute') $this->lang->my->menu->contribute['subModule'] = 'story';

                $this->view->title      = $this->lang->story->batchEdit;
            }
        }

        /* Judge whether the editedStories is too large and set session. */
        $countInputVars  = count($stories) * $this->config->story->batchClose->columns;
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        unset($this->lang->story->reasonList['subdivided']);

        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->storyIdList      = $storyIdList;
        $this->view->storyType        = $storyType;
        $this->view->reasonList       = $this->lang->story->reasonList;
        $this->view->twinsCount       = $twinsCount;

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
        if(empty($_POST['storyIdList'])) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);
        $allChanges  = $this->story->batchChangeModule($storyIdList, $moduleID);
        if(dao::isError()) return print(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::locate($this->session->storyList, 'parent');
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
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $tasks));
            return print(js::locate($this->createLink('execution', 'task', "executionID=$executionID"), 'parent'));
        }

        if(!$this->post->storyIdList) return print(js::locate($this->session->storyList, 'parent'));

        $stories    = $this->story->getByList($_POST['storyIdList']);
        $storyGroup = array();
        foreach($stories as $story)
        {
            if(strpos('draft,reviewing,changing,closed', $story->status) !== false)
            {
                unset($stories[$story->id]);
                continue;
            }

            if(isset($storyGroup[$story->module])) continue;
            $storyGroup[$story->module] = $this->story->getExecutionStoryPairs($executionID, 0, 'all', $story->module, 'short', 'active');
        }

        if(empty($stories)) return print(js::error($this->lang->story->noStoryToTask) . js::locate($this->session->storyList));

        $this->view->title          = $this->lang->story->batchToTask;
        $this->view->executionID    = $executionID;
        $this->view->syncFields     = empty($_POST['fields']) ? array() : $_POST['fields'];
        $this->view->hourPointValue = empty($_POST['hourPointValue']) ? 0 : $_POST['hourPointValue'];
        $this->view->taskType       = empty($_POST['type']) ? '' : $_POST['type'];
        $this->view->stories        = $stories;
        $this->view->storyGroup     = $storyGroup;
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
        if(empty($_POST['storyIdList'])) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);
        $allChanges  = $this->story->batchChangePlan($storyIdList, $planID, $oldPlanID);
        if(dao::isError()) return print(js::error(dao::getError()));
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::locate($this->session->storyList, 'parent');
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
        $storyIdList = $this->post->storyIdList;
        if(empty($storyIdList)) return print(js::locate($this->session->storyList, 'parent'));

        $storyIdList = array_unique($storyIdList);
        $allChanges  = $this->story->batchChangeStage($storyIdList, $stage);
        if(dao::isError()) return print(js::error(dao::getError()));

        $action = $stage == 'verified' ? 'Verified' : 'Edited';
        foreach($allChanges as $storyID => $changes)
        {
            $actionID = $this->action->create('story', $storyID, $action);
            $this->action->logHistory($actionID, $changes);
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::locate($this->session->storyList, 'parent');
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
            if(dao::isError()) return print(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('story', $storyID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution    = $this->execution->getByID($this->session->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban('$execLaneType', $kanbanData)"));
                }
                else
                {
                    return print(js::reload('parent.parent'));
                }
            }
            return print(js::locate($this->createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType"), 'parent'));
        }

        /* Get story and product. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs();
        $product  = $this->product->getById($story->product);

        $this->view->title      = zget($products, $story->product, '') . $this->lang->colon . $this->lang->story->assign;
        $this->view->position[] = $this->lang->story->assign;
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
    public function batchAssignTo($storyType = 'story')
    {
        if(!empty($_POST) && isset($_POST['storyIdList']))
        {
            $allChanges = $this->story->batchAssignTo();
            if(dao::isError()) return print(js::error(dao::getError()));

            $assignedTwins = array();
            $oldStories       = $this->story->getByList($this->post->storyIdList);
            foreach($allChanges as $storyID => $changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Assigned', '', $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);

                /* Sync twins. */
                if(!empty($oldStories[$storyID]->twins))
                {
                    $twins = $oldStories[$storyID]->twins;
                    foreach(explode(',', $twins) as $twinID)
                    {
                        if(in_array($twinID, $this->post->storyIdList) or isset($assignedTwins[$twinID])) $twins = str_replace(",$twinID,", ',', $twins);
                    }
                    $this->story->syncTwins($storyID, trim($twins, ','), $changes, 'Assigned');
                    foreach(explode(',', trim($twins, ',')) as $assignedID) $assignedTwins[$assignedID] = $assignedID;
                }
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::locate($this->session->storyList, 'parent');
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
        $tasks = $this->task->getStoryTasks($storyID, $executionID);
        $this->view->tasks   = $tasks;
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->summary = $this->execution->summary($tasks);
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
    public function linkStory($storyID, $type = 'linkStories', $linkedStoryID = 0, $browseType = '', $queryID = 0, $storyType = 'story')
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

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::closeModal('parent.parent', 'this'));
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
        $actionURL = $this->createLink('story', 'linkStory', "storyID=$storyID&type=$type&linkedStoryID=$linkedStoryID&browseType=bySearch&queryID=myQueryID&storyType=$storyType", '', true);
        $this->product->buildSearchForm($story->product, $products, $queryID, $actionURL);

        /* Get stories to link. */
        $storyType    = $story->type;
        $stories2Link = $this->story->getStories2Link($storyID, $type, $browseType, $queryID, $storyType);

        /* Assign. */
        $this->view->title        = $this->lang->story->linkStory . "STORY" . $this->lang->colon .$this->lang->story->linkStory;
        $this->view->position[]   = $this->lang->story->linkStory;
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
        $this->app->loadClass('pager', $static = true);
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
        $actionURL = $this->createLink('story', $method, "storyID=$storyID&browseType=bySearch&excludeStories=$excludeStories&queryID=myQueryID", '', true);
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
     * @param  string $number
     * @param  string $type full
     * @param  string $status all|unclosed
     * @param  string $from bug
     * @access public
     * @return void
     */
    public function ajaxGetExecutionStories($executionID, $productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $number = '', $type = 'full', $status = 'all', $from = '')
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
            return print(html::select('story', empty($stories) ? array('' => '') : $stories, $storyID, 'onchange=setStoryRelated()'));
        }
        else
        {
            $storyName = $number === '' ? 'story' : "story[$number]";
            $misc      = $from   === 'bug' ? 'class=form-control' : 'class=form-control onchange=setStoryRelated(' . $number . ');';
            return print(html::select($storyName, empty($stories) ? array('' => '') : $stories, $storyID, $misc));
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
     * @param  int    $objectID projectID || executionID
     * @param  int    $number
     * @access public
     * @return void
     */
    public function ajaxGetProductStories($productID, $branch = 0, $moduleID = 0, $storyID = 0, $onlyOption = 'false', $status = '', $limit = 0, $type = 'full', $hasParent = 1, $objectID = 0, $number = '')
    {
        $hasParent = (bool)$hasParent;
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }

        $storyStatus = $this->story->getStatusList($status);

        if($objectID)
        {
            $stories = $this->story->getExecutionStoryPairs($objectID, $productID, $branch, $moduleID, $type);
        }
        else
        {
            $stories = $this->story->getProductStoryPairs($productID, $branch, $moduleID, $storyStatus, 'id_desc', $limit, $type, 'story', $hasParent);
        }

        if(!in_array($this->app->tab, array('execution', 'project')) and empty($stories)) $stories = $this->story->getProductStoryPairs($productID, $branch, 0, $storyStatus, 'id_desc', $limit, $type, 'story', $hasParent);

        $storyID = isset($stories[$storyID]) ? $storyID : 0;
        $select  = html::select('story' . $number, empty($stories) ? array('' => '') : $stories, $storyID, "class='form-control'");

        /* If only need options, remove select wrap. */
        if($onlyOption == 'true') return print(substr($select, strpos($select, '>') + 1, -10));
        echo $select;
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
     * @access public
     * @return string
     */
    public function ajaxGetInfo($storyID)
    {
        $story = $this->story->getByID($storyID);
        if(empty($story)) return;

        $storyInfo['moduleID'] = $story->module;
        $storyInfo['estimate'] = $story->estimate;
        $storyInfo['pri']      = $story->pri;
        $storyInfo['spec']     = html_entity_decode($story->spec, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
        $storyInfo['status']   = $story->status;

        echo json_encode($storyInfo);
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
        return print(html::select($labelName, $stories, 0, 'class="form-control"'));
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
        $this->view->position[]    = $this->products[$productID];
        $this->view->position[]    = $this->lang->story->reportChart;
        $this->view->productID     = $productID;
        $this->view->branchID      = $branchID;
        $this->view->browseType    = $browseType;
        $this->view->storyType     = $storyType;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
        $this->view->projectID     = $projectID;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
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

        $filterFields = array();
        if(isset($project->hasProduct) && !$project->hasProduct)
        {
            $filterFields[] = ', product,';
            $filterFields[] = ', branch,';

            if($project->model != 'scrum') $filterFields[] = ', plan,';
        }

        if($storyType == 'requirement')
        {
            $filterFields[] = ', stage,';
            $filterFields[] = ', plan,';
        }
        if(!empty($filterFields)) $this->config->story->exportFields = str_replace($filterFields, ',', $this->config->story->exportFields);

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

        if($id) return print(html::select("stories[$id]", $stories, '', 'class="form-control"'));
        echo html::select('story', $stories, '', 'class=form-control');
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
     * AJAX: Get product user stories.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $requirementList
     * @access public
     * @return string
     */
    public function ajaxGetProductUserStories($productID, $branchID = 0, $requirementList = 0)
    {
        $URS     = $this->story->getProductStoryPairs($productID, $branchID, 0, 'active,launched', 'id_desc', 0, '', 'requirement');
        $project = $this->loadModel('project')->getById($this->session->project);

        /* 获取ipd项目下研发中的需求。*/
        /* Get ipd project developing requirements.*/
        if($project->model == 'ipd' and $this->app->tab == 'project')
        {
            $developingURS = $this->story->getExecutionStoryPairs($this->session->project, $productID, $branchID, 0, '', 'developing', 'requirement');
            $URS = $developingURS + $URS;
        }
        return print(html::select('URS[]', $URS, $requirementList, "class='form-control chosen' multiple"));
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

        return print html::select("duplicateStoryIDList[$storyID]", $stories, '', "class='form-control' placeholder='{$this->lang->bug->duplicateTip}'");
    }
}
