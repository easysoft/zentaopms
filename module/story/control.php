<?php
/**
 * The control file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
     * @param  string $type requirement|story
     * @access public
     * @return void
     */
    public function create($productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $objectID = 0, $bugID = 0, $planID = 0, $todoID = 0, $extra = '', $type = 'story')
    {
        /* Whether there is a object to transfer story, for example feedback. */
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if($productID == 0 and $objectID == 0) $this->locate($this->createLink('product', 'create'));

        /* Get product id according to the project id when lite vision todo transfer story */
        if($this->config->vision == 'lite' and $productID == 0)
        {
            $product = $this->loadModel('product')->getProductPairsByProject($objectID);
            if(!empty($project)) $productID = key($product);
        }

        $this->story->replaceURLang($type);
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
        }
        else if($this->app->tab == 'project')
        {
            $objectID = empty($objectID) ? $this->session->project : $objectID;
            $objects  = $this->project->getPairsByProgram();
            $objectID = $this->project->saveState($objectID, $objects);
            $this->project->setMenu($objectID);
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

        if(!empty($_POST))
        {
            $response['result'] = 'success';

            setcookie('lastStoryModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $storyResult = $this->story->create($objectID, $bugID, $from = isset($fromObjectIDKey) ? $fromObjectIDKey : '', $extra);
            if(!$storyResult or dao::isError())
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
                    $response['locate'] = $this->createLink('story', 'view', "storyID={$storyID}");
                }
                else
                {
                    $execution          = $this->dao->findById((int)$objectID)->from(TABLE_EXECUTION)->fetch();
                    $moduleName         = $execution->type == 'project' ? 'projectstory' : 'execution';
                    $param              = $execution->type == 'project' ? "projectID=$objectID&productID=$productID" : "executionID=$objectID";
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
            $actionID = $this->action->create('story', $storyID, $action, '', $extra);

            if($objectID != 0)
            {
                $object = $this->dao->findById((int)$objectID)->from(TABLE_PROJECT)->fetch();
                if($object->type != 'project')
                {
                    $actionType = $object->type == 'kanban' ? 'linked2kanban' : 'linked2execution';
                    $this->loadModel('action')->create('story', $storyID, $actionType, '', $objectID);
                }
                else
                {
                    $this->loadModel('action')->create('story', $storyID, 'linked2project', '', $objectID);
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
            $response['message'] = $this->lang->saveSuccess;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $storyID));

            /* If link from no head then reload. */
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));

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
                $response['locate'] = $this->createLink('product', 'browse', "productID=$productID&branch=$branchID&browseType=&param=0&type=$type&orderBy=id_desc");
                if($this->session->storyList) $response['locate'] = $this->session->storyList;
            }
            else
            {
                setcookie('storyModuleParam', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
                $execution          = $this->dao->findById((int)$objectID)->from(TABLE_EXECUTION)->fetch();
                $moduleName         = $execution->type == 'project' ? 'projectstory' : 'execution';
                $param              = $execution->type == 'project' ? "projectID=$objectID&productID=$productID" : "executionID=$objectID&orderBy=order_desc&browseType=unclosed";
                $response['locate'] = $this->createLink($moduleName, 'story', $param);
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
            $branch          = key($branches);
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
            $story      = $this->story->getByID($storyID);
            $planID     = $story->plan;
            $source     = $story->source;
            $sourceNote = $story->sourceNote;
            $color      = $story->color;
            $pri        = $story->pri;
            $productID  = $story->product;
            $moduleID   = $story->module;
            $estimate   = $story->estimate;
            $title      = $story->title;
            $spec       = htmlSpecialString($story->spec);
            $verify     = htmlSpecialString($story->verify);
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
            $pri       = !empty($oldBug->pri) ? $oldBug->pri : '3';
            if(strpos($oldBug->mailto, $oldBug->openedBy) === false)
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
                $$storyField = $fromObject->{$fromObjectField};
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

        /* Set Custom. */
        foreach(explode(',', $this->config->story->list->customCreateFields) as $field) $customFields[$field] = $this->lang->story->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->story->custom->createFields;

        $this->view->title            = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID&branch=$branch"), $product->name);
        $this->view->position[]       = $this->lang->story->common;
        $this->view->position[]       = $this->lang->story->create;
        $this->view->gobackLink       = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('product', 'browse', "productID=$productID") : '';
        $this->view->products         = $products;
        $this->view->users            = $users;
        $this->view->moduleID         = $moduleID ? $moduleID : (int)$this->cookie->lastStoryModule;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = str_replace('2030-01-01', $this->lang->story->undetermined, $this->loadModel('productplan')->getPairsForStory($productID, $branch, 'skipParent|unexpired|noclosed'));
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
        $this->view->URS              = $type == 'story' ? $this->story->getRequirements($productID) : '';
        $this->view->needReview       = ($this->app->user->account == $product->PO || $objectID > 0 || $this->config->story->needReview == 0) ? "checked='checked'" : "";
        $this->view->type             = $type;

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
     * @param  string $type requirement|story
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0, $branch = 0, $moduleID = 0, $storyID = 0, $executionID = 0, $plan = 0, $type = 'story', $extra = '')
    {
        /* Set menu. */
        if($executionID)
        {
            $execution = $this->dao->findById((int)$executionID)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'project')
            {
                $this->project->setMenu($executionID);
                $this->app->rawModule = 'projectstory';
                $this->lang->navGroup->story = 'project';
                $this->lang->product->menu = $this->lang->{$execution->model}->menu;
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
                $this->app->rawModule = 'execution';
                $this->lang->navGroup->story = 'execution';
            }
            $this->view->execution = $execution;
        }
        else
        {
            $this->product->setMenu($productID, $branch);
        }

        /* Clear title when switching products and set the session for the current product. */
        if($productID != $this->cookie->preProductID) unset($_SESSION['storyImagesFile']);
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $this->story->replaceURLang($type);

        /* Check can subdivide or not. */
        if($storyID)
        {
            $story = $this->story->getById($storyID);
            if(($story->status != 'active' or $story->stage != 'wait' or $story->parent > 0) and $this->config->vision != 'lite') return print(js::alert($this->lang->story->errorNotSubdivide) . js::locate('back'));
        }

        if(!empty($_POST))
        {
            $mails = $this->story->batchCreate($productID, $branch, $type);
            if(dao::isError()) return print(js::error(dao::getError()));

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
                $this->execution->linkStory($executionID, $stories, $products, $extra, $lanes);
                if($executionID != $this->session->project) $this->execution->linkStory($this->session->project, $stories, $products);
            }

            /* If storyID not equal zero, subdivide this story to child stories and close it. */
            if($storyID and !empty($mails))
            {
                $this->story->subdivide($storyID, $stories);
                if(dao::isError()) return print(js::error(dao::getError()));
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $stories));

            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));

            if($storyID)
            {
                return print(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
            }
            elseif($executionID)
            {
                setcookie('storyModuleParam', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                $moduleName = $execution->type == 'project' ? 'projectstory' : 'execution';
                $param      = $execution->type == 'project' ? "projectID=$executionID&productID=$productID" : "executionID=$executionID&orderBy=id_desc&browseType=unclosed";
                $link       = $this->createLink($moduleName, 'story', $param);
                return print(js::locate($link, 'parent'));
            }
            else
            {
                setcookie('storyModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=unclosed&queryID=0&type=$type");
                return print(js::locate($locateLink, 'parent'));
            }
        }

        /* Set branch and module. */
        $product  = $this->product->getById($productID);
        $products = $this->product->getPairs();
        if($product) $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        if($executionID != 0)
        {
            $productBranches = $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $executionID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch === 'all' ? 0 : $branch);
        $moduleOptionMenu['ditto'] = $this->lang->story->ditto;

        /* Get reviewers. */
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Init vars. */
        $planID   = $plan;
        $pri      = 0;
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
        $plans          = $this->loadModel('productplan')->getPairsForStory($productID, ($branch === 'all' or !in_array($branch, array_keys($branches))) ? 0 : $branch, 'skipParent|unexpired|noclosed');
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
        if($type == 'requirement')
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
        $this->view->type             = $type;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;
        /* When the user is product owner or add story in project or not set review, the default is not to review. */
        $this->view->needReview       = ($this->app->user->account == $product->PO || $executionID > 0 || $this->config->story->needReview == 0) ? 0 : 1;
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
        $products = $this->product->getPairs();
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
     * @access public
     * @return void
     */
    public function edit($storyID, $kanbanGroup = 'default')
    {
        if(!empty($_POST))
        {
            $changes = $this->story->update($storyID);
            if(dao::isError()) return print(js::error(dao::getError()));
            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
                if(isset($_POST['reviewer'])) $this->story->recordReviewAction($story);
            }

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $this->session->execLaneType ? $this->session->execLaneType : 'all', 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                else
                {
                    return print(js::reload('parent.parent'));
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));
            return print(js::locate($this->createLink($this->app->rawModule, 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);

        /* Sort products. */
        $myProducts     = array();
        $othersProducts = array();
        $products       = $this->loadModel('product')->getList();

        foreach($products as $product)
        {
            if($product->status == 'normal' and $product->PO == $this->app->user->account) $myProducts[$product->id] = $product->name;
            if($product->status == 'normal' and !($product->PO == $this->app->user->account)) $othersProducts[$product->id] = $product->name;
            if($product->status == 'closed') continue;
        }
        $products = $myProducts + $othersProducts;

        /* Assign. */
        $story   = $this->story->getById($storyID, 0, true);
        $product = $this->product->getById($story->product);
        $stories = $this->story->getParentStoryPairs($story->product, $story->parent);
        if(isset($stories[$storyID])) unset($stories[$storyID]);

        /* Get users. */
        $users = $this->user->getPairs('pofirst|nodeleted|noclosed', "$story->assignedTo,$story->openedBy,$story->closedBy");

        $isShowReviewer = false;
        $reviewerList   = $this->story->getReviewerPairs($story->id, $story->version);
        $reviewerList   = array_keys($reviewerList);
        $reviewedBy     = explode(',', trim($story->reviewedBy, ','));
        if(array_diff($reviewerList, $reviewedBy) and strpos('draft,changed', $story->status) !== false) $isShowReviewer = true;

        $reviewedReviewer = array();
        foreach($reviewedBy as $reviewer) $reviewedReviewer[] = zget($users, $reviewer);

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $objectID  = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            $products  = $this->product->getProductPairsByProject($objectID);
            $this->view->objectID = $objectID;
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

        /* Get product reviewers. */
        $productReviewers = $product->reviewer;
        if(!$productReviewers and $product->acl != 'open') $productReviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Process the module when branch products are switched to normal products. */
        if($product->type == 'normal' and !empty($story->branch)) $this->view->moduleOptionMenu += $this->tree->getModulesName($story->module);

        $this->view->title            = $this->lang->story->edit . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->position[]       = $this->lang->story->edit;
        $this->view->story            = $story;
        $this->view->stories          = $stories;
        $this->view->users            = $users;
        $this->view->product          = $product;
        $this->view->plans            = $this->loadModel('productplan')->getPairsForStory($story->product, $story->branch == 0 ? 'all' : $story->branch, 'skipParent');
        $this->view->products         = $products;
        $this->view->branchOption     = $branchOption;
        $this->view->branchTagOption  = $branchTagOption;
        $this->view->reviewers        = implode(',', $reviewerList);
        $this->view->reviewedReviewer = $reviewedReviewer;
        $this->view->productReviewers = $this->user->getPairs('noclosed|nodeleted', $reviewerList, 0, $productReviewers);
        $this->view->isShowReviewer   = $isShowReviewer;
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

        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
        }
        else if($this->app->tab == 'project')
        {
            $this->project->setMenu($executionID);
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
            $plans           = array($productID => $this->productplan->getBranchPlanPairs($productID, '', true));
            $products        = array($productID => $product);
            $branchTagOption = array($productID => $branchTagOption);
        }
        else
        {
            $branchProduct   = false;
            $modules         = array();
            $branchTagOption = array();
            $products        = array();

            if($executionID)
            {
                /* The stories of project or execution. */
                $execution = $this->execution->getByID($executionID);
                $products  = $this->loadModel('product')->getProducts($executionID);
            }
            else
            {
                /* The stories of my. */
                $productIdList = array();
                foreach($stories as $story) $productIdList[$story->product] = $story->product;
                $products = $this->product->getByIdList($productIdList);
            }

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

                $plans[$storyProduct->id] = $this->productplan->getBranchPlanPairs($storyProduct->id, $branches, true);
                if(empty($plans[$storyProduct->id])) $plans[$storyProduct->id][0] = $plans[$storyProduct->id];

                if($storyProduct->type != 'normal') $branchProduct = true;
            }
        }

        /* Set ditto option for users. */
        $users = $this->loadModel('user')->getPairs('nodeleted');
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
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        /* Judge whether the editedStories is too large and set session. */
        $countInputVars  = count($stories) * (count(explode(',', $this->config->story->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Append module when change product type. */
        $moduleList = array(0 => '/');
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
        }

        $this->view->position[]        = $this->lang->story->common;
        $this->view->position[]        = $this->lang->story->batchEdit;
        $this->view->title             = $this->lang->story->batchEdit;
        $this->view->users             = $users;
        $this->view->priList           = array('0' => '', 'ditto' => $this->lang->story->ditto) + $this->lang->story->priList;
        $this->view->sourceList        = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->sourceList;
        $this->view->reasonList        = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->reasonList;
        $this->view->stageList         = array('' => '',  'ditto' => $this->lang->story->ditto) + $this->lang->story->stageList;
        $this->view->productID         = $productID;
        $this->view->products          = $products;
        $this->view->branchProduct     = $branchProduct;
        $this->view->storyIdList       = $storyIdList;
        $this->view->branch            = $branch;
        $this->view->plans             = array('' => '') + $plans;
        $this->view->storyType         = $storyType;
        $this->view->stories           = $stories;
        $this->view->executionID       = $executionID;
        $this->view->branchTagOption   = $branchTagOption;
        $this->view->moduleList        = $moduleList;
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
            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }
            $story   = $this->story->getByID($storyID);
            $version = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch('version');
            $files   = $this->loadModel('file')->saveUpload($story->type, $storyID, $version);

            if(empty($files) and $this->post->uid != '' and isset($_SESSION['album']['used'][$this->post->uid])) $files = $this->file->getPairs($_SESSION['album']['used'][$this->post->uid]);

            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = (!empty($changes) or !empty($files)) ? 'Changed' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('story', $storyID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($storyID);

            $module = $this->app->tab == 'project' ? 'projectstory' : 'story';

            if(isonlybody()) return print(js::reload('parent.parent'));

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::locate($this->createLink($module, 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('execution');

        $story    = $this->story->getById($storyID);
        $reviewer = $this->story->getReviewerPairs($storyID, $story->version);
        $product  = $this->loadModel('product')->getByID($story->product);

        /* Get product reviewers. */
        $productReviewers = $product->reviewer;
        if(!$productReviewers and $product->acl != 'open') $productReviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');

        /* Assign. */
        $this->view->title            = $this->lang->story->change . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->users            = $this->user->getPairs('pofirst|nodeleted|noclosed', $this->view->story->assignedTo);
        $this->view->position[]       = $this->lang->story->change;
        $this->view->needReview       = ($this->app->user->account == $this->view->product->PO || $this->config->story->needReview == 0) ? "checked='checked'" : "";
        $this->view->reviewer         = implode(',', array_keys($reviewer));
        $this->view->productReviewers = $this->user->getPairs('noclosed|nodeleted', $reviewer, 0, $productReviewers);
        $this->view->files            = $this->loadModel('file')->getByObject($story->type, $storyID);

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
                    $execLaneType  = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy   = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this'));
                }
            }
            return print(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);

        /* Assign. */
        $this->view->title      = $this->lang->story->activate . "STORY" . $this->lang->colon . $this->view->story->title;
        $this->view->users      = $this->user->getPairs('pofirst|nodeleted', $this->view->story->closedBy);
        $this->view->position[] = $this->lang->story->activate;
        $this->display();
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  int    $param
     * @access public
     * @return void
     */
    public function view($storyID, $version = 0, $param = 0)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri . "#app={$this->app->tab}", 'product');
        $this->session->set('productPlanList', $uri, 'product');

        $storyID = (int)$storyID;
        $story   = $this->story->getById($storyID, $version, true);
        $linkModuleName = $this->config->vision == 'lite' ? 'project' : 'product';
        if(!$story) return print(js::error($this->lang->notFound) . js::locate($this->createLink($linkModuleName, 'index')));

        $story = $this->story->mergeReviewer($story, true);

        $this->story->replaceURLang($story->type);

        $story->files = $this->loadModel('file')->getByObject($story->type, $storyID);
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, type, status')->fetch();
        $plan         = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $bugs         = $this->dao->select('id,title,status,pri,severity')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $fromBug      = $this->dao->select('id,title')->from(TABLE_BUG)->where('toStory')->eq($storyID)->fetch();
        $cases        = $this->dao->select('id,title,status,pri')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $linkedMRs    = $this->loadModel('mr')->getLinkedMRPairs($storyID, 'story');
        $modulePath   = $this->tree->getParents($story->module);
        $storyModule  = empty($story->module) ? '' : $this->tree->getById($story->module);

        /* Set the menu. */
        $from = $this->app->tab;
        if($from == 'execution')
        {
            $this->execution->setMenu($param);
        }
        elseif($from == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
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

        if($product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $reviewers  = $this->story->getReviewerPairs($storyID, $story->version);
        $reviewedBy = trim($story->reviewedBy, ',');

        $this->executeHooks($storyID);

        $title      = "STORY #$story->id $story->title - $product->name";
        $position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $position[] = $this->lang->story->common;
        $position[] = $this->lang->story->view;

        $this->view->title              = $title;
        $this->view->position           = $position;
        $this->view->product            = $product;
        $this->view->branches           = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->plan               = $plan;
        $this->view->bugs               = $bugs;
        $this->view->fromBug            = $fromBug;
        $this->view->cases              = $cases;
        $this->view->story              = $story;
        $this->view->linkedMRs          = $linkedMRs;
        $this->view->track              = $this->story->getTrackByID($story->id);
        $this->view->users              = $this->user->getPairs('noletter');
        $this->view->reviewers          = $reviewers;
        $this->view->relations          = $this->story->getStoryRelation($story->id, $story->type);
        $this->view->executions         = $this->execution->getPairs(0, 'all', 'nocode');
        $this->view->execution          = empty($story->execution) ? array() : $this->dao->findById($story->execution)->from(TABLE_EXECUTION)->fetch();
        $this->view->actions            = $this->action->getList('story', $storyID);
        $this->view->storyModule        = $storyModule;
        $this->view->modulePath         = $modulePath;
        $this->view->version            = $version == 0 ? $story->version : $version;
        $this->view->preAndNext         = $this->loadModel('common')->getPreAndNextObject('story', $storyID);
        $this->view->from               = $from;
        $this->view->param              = $param;

        $this->display();
    }

    /**
     * Delete a story.
     *
     * @param  int    $storyID
     * @param  string $confirm  yes|no
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function delete($storyID, $confirm = 'no', $from = '')
    {
        $story = $this->story->getById($storyID);
        if($story->parent < 0) return print(js::alert($this->lang->story->cannotDeleteParent));

        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->story->confirmDelete, $this->createLink('story', 'delete', "story=$storyID&confirm=yes&from=$from"), ''));
        }
        else
        {
            $this->dao->update(TABLE_STORY)->set('deleted')->eq(1)->where('id')->eq($storyID)->exec();
            $this->loadModel('action')->create($story->type, $storyID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);

            if($story->parent > 0)
            {
                $this->story->updateParentStatus($story->id);
                $this->loadModel('action')->create('story', $task->parent, 'deleteChildrenStory', '', $storyID);
            }

            $this->executeHooks($storyID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

            if($this->app->tab == 'execution' and $from == 'taskkanban') return print(js::reload('parent'));

            if(isonlybody()) return print(js::reload('parent.parent'));

            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
            return print(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * Review a story.
     *
     * @param  int    $storyID
     * @param  string $from product|project
     * @access public
     * @return void
     */
    public function review($storyID, $from = 'product')
    {
        if(!empty($_POST))
        {
            $changes = $this->story->review($storyID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes)
            {
                $story    = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
                $actionID = $this->story->recordReviewAction($story, $this->post->result, $this->post->closedReason);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($storyID);

            if(isonlybody()) return print(js::reload('parent.parent'));

            $module = $from == 'project' ? 'projectstory' : 'story';
            if(defined('RUN_MODE') and RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $storyID));
            return print(js::locate($this->createLink($module, 'view', "storyID=$storyID"), 'parent'));
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

        if($story->status == 'draft' and $story->version == 1) unset($this->lang->story->resultList['revert']);

        if($story->status == 'changed') unset($this->lang->story->resultList['reject']);

        if(count($reviewers) > 1) unset($this->lang->story->resultList['revert']);

        $this->view->title      = $this->lang->story->review . "STORY" . $this->lang->colon . $story->title;
        $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[] = $this->lang->story->common;
        $this->view->position[] = $this->lang->story->review;

        $this->view->product   = $product;
        $this->view->story     = $story;
        $this->view->actions   = $this->action->getList('story', $storyID);
        $this->view->users     = $this->loadModel('user')->getPairs('nodeleted|noletter', "$story->lastEditedBy,$story->openedBy");
        $this->view->reviewers = $reviewers;

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
     * @access public
     * @return void
     */
    public function batchReview($result, $reason = '')
    {
        if(!$this->post->storyIdList) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);
        $actions     = $this->story->batchReview($storyIdList, $result, $reason);

        if(dao::isError()) return print(js::error(dao::getError()));
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::reload('parent');
    }

    /**
     * Recall the story review.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function recall($storyID)
    {
        $story = $this->story->getById($storyID);
        $this->story->recall($storyID);
        $this->loadModel('action')->create('story', $storyID, 'Recalled');

        $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('product', 'browse', "productID={$story->product}");
        echo js::locate($locateLink, 'parent');
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
            if(dao::isError()) return print(js::error(dao::getError()));
            $this->story->closeParentRequirement($storyID);

            if($changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Closed', $this->post->comment, ucfirst($this->post->closedReason) . ($this->post->duplicateStory ? ':' . (int)$this->post->duplicateStory : ''));
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($storyID);

            if(isonlybody())
            {
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $this->loadModel('kanban')->updateLane($this->session->execution, 'story', $storyID);
                    $execLaneType  = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy   = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
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
                return print(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
            }
        }

        /* Get story and product. */
        $story   = $this->story->getById($storyID);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        $this->story->replaceURLang($story->type);

        /* Set menu. */
        $this->product->setMenu($product->id, $story->branch);

        /* Set the closed reason options and remove subdivided options. */
        if($story->status == 'draft') unset($this->lang->story->reasonList['cancel']);
        unset($this->lang->story->reasonList['subdivided']);

        $this->view->title      = $this->lang->story->close . "STORY" . $this->lang->colon . $story->title;
        $this->view->position[] = html::a($this->createLink('product', 'browse', "product=$product->id&branch=$story->branch"), $product->name);
        $this->view->position[] = $this->lang->story->common;
        $this->view->position[] = $this->lang->story->close;

        $this->view->product    = $product;
        $this->view->story      = $story;
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->reasonList = $this->lang->story->reasonList;
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
        if($this->post->comments)
        {
            $data       = fixer::input('post')->get();
            $allChanges = $this->story->batchClose();

            if($allChanges)
            {
                foreach($allChanges as $storyID => $changes)
                {
                    $actionID = $this->action->create('story', $storyID, 'Closed', htmlSpecialString($this->post->comments[$storyID]), ucfirst($this->post->closedReasons[$storyID]) . ($this->post->duplicateStoryIDList[$storyID] ? ':' . (int)$this->post->duplicateStoryIDList[$storyID] : ''));
                    $this->action->logHistory($actionID, $changes);
                }
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->storyList, 'parent'));
        }

        if(!$this->post->storyIdList) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
        $storyIdList = array_unique($storyIdList);

        /* Get edited stories. */
        $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchAll('id');
        foreach($stories as $story)
        {
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

                $this->view->position[] = html::a($this->createLink('my', 'story'), $this->lang->my->story);
                $this->view->title      = $this->lang->story->batchEdit;
            }
        }

        /* Judge whether the editedStories is too large and set session. */
        $countInputVars  = count($stories) * $this->config->story->batchClose->columns;
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        unset($this->lang->story->reasonList['subdivided']);

        $this->view->position[]       = $this->lang->story->common;
        $this->view->position[]       = $this->lang->story->batchClose;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'story');
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->storyIdList      = $storyIdList;
        $this->view->storyType        = $storyType;
        $this->view->reasonList       = $this->lang->story->reasonList;

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
     * @access public
     * @return void
     */
    public function batchToTask($executionID = 0)
    {
        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->story->successToTask;

            $tasks = $this->story->batchToTask($executionID, $this->session->project);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $tasks));
            $response['locate'] = $this->createLink('execution', 'task', "executionID=$executionID");
            return $this->send($response);
        }
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
     * @access public
     * @return void
     */
    public function batchChangeBranch($branchID, $confirm = '', $storyIdList = '')
    {
        if(empty($_POST['storyIdList'])) return print(js::locate($this->session->storyList, 'parent'));
        $storyIdList = $this->post->storyIdList;
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
                $confirmURL        = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID&confirm=yes&storyIdList=$storyIdList");
                $cancelURL         = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID&confirm=no&storyIdList=$normalStotyIdList");
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
     * @access public
     * @return void
     */
    public function assignTo($storyID, $kanbanGroup = 'default')
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
                $execution = $this->execution->getByID($this->session->execution);
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    $execLaneType  = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $execLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this', 'function(){parent.parent.$(\'[data-ride="searchList"]\').searchList();}'));
                }
            }
            return print(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        /* Get story and product. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs();

        /* Set menu. */
        $this->product->setMenu($story->product, $story->branch);

        $this->view->title      = zget($products, $story->product, '') . $this->lang->colon . $this->lang->story->assign;
        $this->view->position[] = $this->lang->story->assign;
        $this->view->story      = $story;
        $this->view->actions    = $this->action->getList('story', $storyID);
        $this->view->users      = ($this->config->vision == 'lite') ? $this->loadModel('user')->getTeamMemberPairs($this->session->project) : $this->loadModel('user')->getPairs('nodeleted|noclosed|pofirst|noletter');
        $this->display();
    }

    /**
     * Batch assign to.
     *
     * @access public
     * @return void
     */
    public function batchAssignTo()
    {
        if(!empty($_POST) && isset($_POST['storyIdList']))
        {
            $allChanges  = $this->story->batchAssignTo();
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $storyID => $changes)
            {
                $actionID = $this->action->create('story', $storyID, 'Assigned', '', $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo js::locate($this->session->storyList, 'parent');
    }

    /**
     * Story track.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $projectID
     * @param  int         $recTotal
     * @param  int         $recPerPage
     * @param  int         $pageID
     * @access public
     * @return void
     */
    public function track($productID, $branch = '', $projectID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $branch = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;
        setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Set menu. The projectstory module does not execute. */
        if(!$projectID)
        {
            $products  = $this->product->getPairs();
            $productID = $this->product->saveState($productID, $products);
            $this->product->products = $this->product->saveState($productID, $products);
            $this->product->setMenu($productID, $branch);
        }

        /* Save session. */
        $this->session->set('storyList',    $this->app->getURI(true), 'product');
        $this->session->set('taskList',     $this->app->getURI(true), 'execution');
        $this->session->set('designList',   $this->app->getURI(true), 'project');
        $this->session->set('bugList',      $this->app->getURI(true), 'qa');
        $this->session->set('caseList',     $this->app->getURI(true), 'qa');
        $this->session->set('revisionList', $this->app->getURI(true), 'repo');

        /* Load pager and get tracks. */
        $this->app->loadClass('pager', $static = true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);
        $tracks = $this->story->getTracks($productID, $branch, $projectID, $pager);

        if($projectID)
        {
            $this->loadModel('project')->setMenu($projectID);
            $projectProducts = $this->product->getProducts($projectID);
        }

        $this->view->title      = $this->lang->story->track;
        $this->view->position[] = $this->lang->story->track;

        $this->view->tracks          = $tracks;
        $this->view->pager           = $pager;
        $this->view->productID       = $productID;
        $this->view->projectProducts = isset($projectProducts) ? $projectProducts : array();
        $this->display();
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
     * Show zero case story.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function zeroCase($productID = 0, $branchID = 0, $orderBy = 'id_desc', $projectID = 0)
    {
        $orderBy = empty($orderBy) ? 'id_desc' : $orderBy;
        $this->session->set('storyList', $this->app->getURI(true) . '#app=' . $this->app->tab, 'product');
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);

        $this->loadModel('testcase');
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
            $this->app->rawModule = 'qa';
            $this->lang->project->menu->qa['subMenu']->testcase['subModule'] = 'story';
            $products  = $this->product->getProducts($this->session->project, 'all', '', false);
            $productID = $this->product->saveState($productID, $products);
            $this->lang->modulePageNav = $this->product->select($products, $productID, 'story', 'zeroCase', "projectID=$projectID", $branchID);
        }
        else
        {
            $products  = $this->product->getPairs();
            $productID = $this->product->saveState($productID, $products);
            $this->loadModel('qa');
            $this->app->rawModule = 'testcase';
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->qa->setMenu($products, $productID, $branchID);
        }

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $this->view->title      = $this->lang->story->zeroCase;
        $this->view->position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $products[$productID]);
        $this->view->position[] = $this->lang->story->zeroCase;

        $this->view->stories    = $this->story->getZeroCase($productID, $branchID, $sort);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projectID  = $projectID;
        $this->view->productID  = $productID;
        $this->view->branchID   = $branchID;
        $this->view->orderBy    = $orderBy;
        $this->view->suiteList  = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->browseType = '';
        $this->view->product    = $this->product->getByID($productID);
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
    public function linkStory($storyID, $type = 'linkStories', $linkedStoryID = 0, $browseType = '', $queryID = 0)
    {
        $this->commonAction($storyID);

        if($type == 'remove')
        {
            $result = $this->story->unlinkStory($storyID, $linkedStoryID);
            return print(js::reload('parent'));
        }

        if($_POST)
        {
            $this->story->linkStories($storyID);

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::closeModal('parent.parent', 'this'));
        }

        /* Get story, product, products, and queryID. */
        $story    = $this->story->getById($storyID);
        $products = $this->product->getPairs();
        $queryID  = 0;

        /* Change for requirement story title. */
        if($story->type == 'story')
        {
            $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->create);
            $this->config->product->search['fields']['title'] = $this->lang->story->title;
            unset($this->config->product->search['fields']['plan']);
            unset($this->config->product->search['fields']['stage']);
        }

        /* Build search form. */
        $actionURL = $this->createLink('story', 'linkStory', "storyID=$storyID&type=$type&linkedStoryID=$linkedStoryID&browseType=bySearch&queryID=myQueryID", '', true);
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

        $this->display();
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
     * @param  int    $executionID
     * @param  int    $number
     * @access public
     * @return void
     */
    public function ajaxGetProductStories($productID, $branch = 0, $moduleID = 0, $storyID = 0, $onlyOption = 'false', $status = '', $limit = 0, $type = 'full', $hasParent = 1, $executionID = 0, $number = '')
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

        if($executionID)
        {
            $stories = $this->story->getExecutionStoryPairs($executionID, $productID, $branch, $moduleID, $type);
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

        echo json_encode($storyInfo);
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
     * @access public
     * @return void
     */
    public function report($productID, $branchID, $storyType = 'story', $browseType = 'unclosed', $moduleID = 0, $chartType = 'pie')
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
        $this->products = $this->product->getPairs();
        $this->product->setMenu($productID, $branchID);

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->story->reportChart;
        $this->view->position[]    = $this->products[$productID];
        $this->view->position[]    = $this->lang->story->reportChart;
        $this->view->productID     = $productID;
        $this->view->branchID      = $branchID;
        $this->view->browseType    = $browseType;
        $this->view->storyType     = $storyType;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
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
     * @param  string $type requirement|story
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $executionID = 0, $browseType = '', $type = 'story')
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
                if($this->post->exportType == 'selected')
                {
                    $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($this->cookie->checkedItem)->orderBy($orderBy)->fetchAll('id');
                }
                else
                {
                    $stories = $this->dao->select('*')->from(TABLE_STORY)->where($this->session->storyQueryCondition)->orderBy($orderBy)->fetchAll('id');
                }
            }
            else
            {
                $field = $executionID ? 't2.id' : 't1.id';
                if($this->post->exportType == 'selected')
                {
                    $stmt  = $this->dbh->query("SELECT * FROM " . TABLE_STORY . "WHERE `id` IN({$this->cookie->checkedItem})" . " ORDER BY " . strtr($orderBy, '_', ' '));
                }
                else
                {
                    $stmt  = $this->dbh->query($this->session->storyQueryCondition . " ORDER BY " . strtr($orderBy, '_', ' '));
                }
                while($row = $stmt->fetch()) $stories[$row->id] = $row;
            }
            $storyIdList = array_keys($stories);

            if($stories)
            {
                $children = array();
                foreach($stories as $story)
                {
                    if($story->parent > 0 and isset($stories[$story->parent]))
                    {
                        $children[$story->parent][$story->id] = $story;
                        unset($stories[$story->id]);
                    }
                }

                if(!empty($children))
                {
                    $reorderStories = array();
                    foreach($stories as $story)
                    {
                        $reorderStories[$story->id] = $story;
                        if(isset($children[$story->id]))
                        {
                            foreach($children[$story->id] as $childrenID => $childrenStory)
                            {
                                $reorderStories[$childrenID] = $childrenStory;
                            }
                        }
                        unset($stories[$story->id]);
                    }
                    $stories = $reorderStories;
                }
            }

            /* Get users, products and relations. */
            $users     = $this->loadModel('user')->getPairs('noletter');
            $products  = $this->product->getPairs('nocode');
            $relations = $this->story->getStoryRelationByIds($storyIdList, $type);

            /* Get related objects id lists. */
            $relatedProductIdList = array();
            $relatedStoryIdList   = array();
            $relatedPlanIdList    = array();
            $relatedBranchIdList  = array();
            $relatedStoryIds      = array();

            foreach($stories as $story)
            {
                $relatedProductIdList[$story->product] = $story->product;
                $relatedPlanIdList[$story->plan]       = $story->plan;
                $relatedBranchIdList[$story->branch]   = $story->branch;
                $relatedStoryIds[$story->id]           = $story->id;

                if(isset($relations[$story->id])) $story->childStories = $story->childStories . ',' . $relations[$story->id];

                /* Process related stories. */
                $relatedStories = $story->childStories . ',' . $story->linkStories . ',' . $story->duplicateStory;
                $relatedStories = explode(',', $relatedStories);
                foreach($relatedStories as $storyID)
                {
                    if($storyID) $relatedStoryIdList[$storyID] = trim($storyID);
                }
            }

            $storyTasks = $this->loadModel('task')->getStoryTaskCounts($relatedStoryIds);
            $storyBugs  = $this->loadModel('bug')->getStoryBugCounts($relatedStoryIds);
            $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($relatedStoryIds);

            /* Get related objects title or names. */
            $productsType   = $this->dao->select('id, type')->from(TABLE_PRODUCT)->where('id')->in($relatedProductIdList)->fetchPairs();
            $relatedPlans   = $this->dao->select('id, title')->from(TABLE_PRODUCTPLAN)->where('id')->in(join(',', $relatedPlanIdList))->fetchPairs();
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq($type)->andWhere('objectID')->in($storyIdList)->andWhere('extra')->ne('editor')->fetchGroup('objectID');
            $relatedSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('`story`')->in($storyIdList)->orderBy('version desc')->fetchGroup('story');
            $relatedBranch  = array('0' => $this->lang->branch->main) + $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($relatedBranchIdList)->fetchPairs();
            $relatedModules = $this->loadModel('tree')->getAllModulePairs();

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
                if(isset($products[$story->product]))      $story->product = $this->post->fileType == 'word' ? $products[$story->product] : $products[$story->product] . "(#$story->product)";
                if(isset($relatedModules[$story->module])) $story->module  = $this->post->fileType == 'word' ? $relatedModules[$story->module] : $relatedModules[$story->module] . "(#$story->module)";
                if(isset($relatedBranch[$story->branch]))  $story->branch  = $relatedBranch[$story->branch] . "(#$story->branch)";
                if(isset($story->plan))
                {
                    $plans = '';
                    foreach(explode(',', $story->plan) as $planID)
                    {
                        if(empty($planID)) continue;
                        if(isset($relatedPlans[$planID])) $plans .= $this->post->fileType == 'word' ? $relatedPlans[$planID] : $relatedPlans[$planID] . "(#$planID)";
                    }
                    $story->plan = $plans;
                }
                if(isset($relatedStories[$story->duplicateStory]) and $story->closedReason != 'duplicate') $story->duplicateStory = $relatedStories[$story->duplicateStory];

                if(isset($storyLang->priList[$story->pri]))             $story->pri          = $storyLang->priList[$story->pri];
                if(isset($storyLang->statusList[$story->status]))       $story->status       = $this->processStatus('story', $story);
                if(isset($storyLang->stageList[$story->stage]))         $story->stage        = $storyLang->stageList[$story->stage];
                if(isset($storyLang->reasonList[$story->closedReason])) $story->closedReason = $storyLang->reasonList[$story->closedReason];
                if(isset($storyLang->sourceList[$story->source]))       $story->source       = $storyLang->sourceList[$story->source];
                if(isset($storyLang->sourceList[$story->sourceNote]))   $story->sourceNote   = $storyLang->sourceList[$story->sourceNote];

                if(isset($users[$story->openedBy]))     $story->openedBy     = $users[$story->openedBy];
                if(isset($users[$story->assignedTo]))   $story->assignedTo   = $users[$story->assignedTo] . "(#$story->assignedTo)";
                if(isset($users[$story->lastEditedBy])) $story->lastEditedBy = $users[$story->lastEditedBy];
                if(isset($users[$story->closedBy]))     $story->closedBy     = $users[$story->closedBy];

                if(isset($storyTasks[$story->id]))     $story->taskCountAB = $storyTasks[$story->id];
                if(isset($storyBugs[$story->id]))      $story->bugCountAB  = $storyBugs[$story->id];
                if(isset($storyCases[$story->id]))     $story->caseCountAB = $storyCases[$story->id];

                $story->openedDate     = substr($story->openedDate, 0, 10);
                $story->assignedDate   = substr($story->assignedDate, 0, 10);
                $story->lastEditedDate = substr($story->lastEditedDate, 0, 10);
                $story->closedDate     = substr($story->closedDate, 0, 10);

                if($story->linkStories)
                {
                    $tmpLinkStories    = array();
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
                        if(empty($childStoryID)) continue;

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
                        $fileURL = common::getSysURL() . helper::createLink('file', 'download', "fileID=$file->id");
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
                $story->mailto = rtrim($story->mailto, ',');

                $story->reviewedBy = trim(trim($story->reviewedBy), ',');
                $reviewedBys = explode(',', $story->reviewedBy);
                $story->reviewedBy = '';
                foreach($reviewedBys as $reviewedBy)
                {
                    $reviewedBy = trim($reviewedBy);
                    if(isset($users[$reviewedBy])) $story->reviewedBy .= $users[$reviewedBy] . ',';
                }
                $story->reviewedBy = rtrim($story->reviewedBy, ',');

                /* Set child story title. */
                if($story->parent > 0 && strpos($story->title, htmlentities('>', ENT_COMPAT | ENT_HTML401, 'UTF-8')) !== 0) $story->title = '>' . $story->title;
            }

            if($executionID)
            {
                $header = new stdclass();
                $header->name      = 'execution';
                $header->tableName = TABLE_EXECUTION;

                $this->post->set('header', $header);
            }
            if(!(in_array('platform', $productsType) or in_array('branch', $productsType))) unset($fields['branch']);// If products's type are normal, unset branch field.

            if($this->config->edition != 'open') list($fields, $stories) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $stories);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $stories);
            $this->post->set('kind', 'story');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName = $type == 'requirement' ? $this->lang->URCommon : $this->lang->SRCommon;
        if($executionID)
        {
            $executionName = $this->dao->findById($executionID)->from(TABLE_PROJECT)->fetch('name');
            $fileName      = $executionName . $this->lang->dash . $fileName;
        }
        else
        {
            $productName = $productID ? $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch('name') : $this->lang->product->all;
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

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->story->list->exportFields;
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
            if($params['changed'] and $oldStory->status == 'active' and empty($params['needNotReview']))  $status = 'changed';
            if($params['changed'] and $oldStory->status == 'active' and $this->story->checkForceReview()) $status = 'changed';
            if($params['changed'] and $oldStory->status == 'draft' and $params['needNotReview']) $status = 'active';
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
        $users = $this->loadModel('user')->getPairs('noletter|noclosed');

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
}
