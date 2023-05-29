<?php
declare(strict_types=1);
/**
 * The zen file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     story
 * @link        http://www.zentao.net
 */

class storyZen extends story
{
    /**
     * 设置创建需求页面的导航。
     * Set menu for create story.
     *
     * @param  int       $productID
     * @param  int       $objectID
     * @access protected
     * @return int[]
     */
    protected function setMenuForCreate(int $productID, int $objectID): array
    {
        /* Get product id according to the project id when lite vision todo transfer story */
        if($this->config->vision == 'lite' && $productID == 0)
        {
            $products = $this->product->getProductPairsByProject($objectID);
            if(!empty($products)) $productID = key($products);
        }

        /* Get objectID by tab. */
        if(empty($objectID))
        {
            if($this->app->tab == 'project')   $objectID = (int)$this->session->project;
            if($this->app->tab == 'execution') $objectID = (int)$this->session->execution;
        }

        /* Set menu by tab. */
        if($this->app->tab == 'product')   $this->product->setMenu($productID);
        if($this->app->tab == 'execution') $this->execution->setMenu($objectID);
        if($this->app->tab == 'project')
        {
            $projectID = $objectID;
            if(!$this->session->multiple)
            {
                $projectID = $this->session->project;
                $objectID  = $this->execution->getNoMultipleID($projectID);
            }

            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->saveState($projectID, $projects);
            $this->project->setMenu($projectID);
        }

        return array($productID, $objectID);
    }

    /**
     * 如果是看板执行，设置界面中要用到关于看板的视图变量。
     * Set view vars for kanban.
     *
     * @param  int       $objectID
     * @param  array     $kanbanSetting
     * @access protected
     * @return void
     */
    protected function setViewVarsForKanban(int $objectID, array $kanbanSetting): void
    {
        if($this->app->tab != 'execution') return;
        if(empty($objectID)) return;

        $execution = $this->dao->findById($objectID)->from(TABLE_EXECUTION)->fetch();
        if($execution->type != 'kanban') return ;

        /* 如果是看板执行，设置看板的view变量。 */
        $regionPairs = $this->loadModel('kanban')->getRegionPairs($execution->id, 0, 'execution');
        $regionID    = !empty($kanbanSetting['regionID']) ? $kanbanSetting['regionID'] : key($regionPairs);
        $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'story');
        $laneID      = !empty($kanbanSetting['laneID']) ? $kanbanSetting['laneID'] : key($lanePairs);

        $this->view->executionType = 'kanban';
        $this->config->story->form->create['region']['options'] = $regionPairs;
        $this->config->story->form->create['region']['default'] = $regionID;
        $this->config->story->form->create['region']['title']   = $this->lang->kanbancard->region;
        $this->config->story->form->create['lane']['options']   = $lanePairs;
        $this->config->story->form->create['lane']['default']   = $laneID;
        $this->config->story->form->create['lane']['title']     = $this->lang->kanbancard->lane;
    }

    /**
     * 初始化创建需求的一些字段的数据。
     * Init story for create.
     *
     * @param  int       $planID
     * @access protected
     * @return object
     */
    protected function initStoryForCreate(int $planID): object
    {
        $initStory = new stdclass();
        $initStory->source     = '';
        $initStory->sourceNote = '';
        $initStory->pri        = 3;
        $initStory->estimate   = '';
        $initStory->title      = '';
        $initStory->spec       = '';
        $initStory->verify     = '';
        $initStory->keywords   = '';
        $initStory->mailto     = '';
        $initStory->color      = '';
        $initStory->plan       = $planID;
        return $initStory;
    }

    /**
     * 根据复制的需求，初始化创建需求的一些字段数据。
     * Get init story by copied story.
     *
     * @param  int       $storyID
     * @param  object    $initStory
     * @access protected
     * @return object
     */
    protected function getInitStoryByStory(int $storyID, object $initStory): object
    {
        if(empty($storyID)) return $initStory;

        $story = $this->story->getByID($storyID);
        $initStory->product     = $story->product;
        $initStory->plan        = $story->plan;
        $initStory->module      = $story->module;
        $initStory->source      = $story->source;
        $initStory->sourceNote  = $story->sourceNote;
        $initStory->color       = $story->color;
        $initStory->pri         = $story->pri;
        $initStory->estimate    = $story->estimate;
        $initStory->title       = $story->title;
        $initStory->spec        = htmlSpecialString($story->spec);
        $initStory->verify      = htmlSpecialString($story->verify);
        $initStory->keywords    = $story->keywords;
        $initStory->mailto      = $story->mailto;
        $initStory->category    = $story->category;
        $initStory->feedbackBy  = $story->feedbackBy;
        $initStory->notifyEmail = $story->notifyEmail;
        return $initStory;
    }

    /**
     * 根据来源Bug，初始化创建需求的一些字段数据。
     * Get init story by bug.
     *
     * @param  int       $bugID
     * @param  object    $initStory
     * @access protected
     * @return object
     */
    protected function getInitStoryByBug(int $bugID, object $initStory): object
    {
        if(empty($bugID)) return $initStory;

        $bug = $this->loadModel('bug')->getByID($bugID);
        $initStory->product  = $bug->product;
        $initStory->source   = 'bug';
        $initStory->title    = $bug->title;
        $initStory->keywords = $bug->keywords;
        $initStory->spec     = $bug->steps;
        $initStory->pri      = !empty($bug->pri) ? $bug->pri : '3';
        $initStory->mailto   = $bug->mailto;
        if($bug->mailto and !str_contains($bug->mailto, $bug->openedBy)) $initStory->mailto = $bug->mailto . $bug->openedBy . ',';
        return $initStory;
    }

    /**
     * 根据来源待办，初始化创建产品的一些字段数据。
     * Get init story by todo.
     *
     * @param  int       $todoID
     * @param  object    $initStory
     * @access protected
     * @return object
     */
    protected function getInitStoryByTodo(int $todoID, object $initStory): object
    {
        if(empty($todoID)) return $initStory;

        $todo = $this->loadModel('todo')->getByID($todoID);
        $initStory->source = 'todo';
        $initStory->title  = $todo->name;
        $initStory->spec   = $todo->desc;
        $initStory->pri    = $todo->pri;
        return $initStory;
    }

    /**
     * 获取产品和分支列表。
     * Get products and branches for create.
     *
     * @param  int       $productID
     * @param  int       $objectID
     * @access protected
     * @return array
     */
    protected function getProductsAndBranchesForCreate(int $productID, int $objectID): array
    {
        $products  = array();
        $branches  = array();

        if($objectID != 0)
        {
            $onlyNoClosed = empty($this->config->CRProduct) ? 'noclosed' : '';
            $products     = $this->product->getProductPairsByProject($objectID, $onlyNoClosed);

            $productID = (!empty($productID) && isset($products[$productID])) ? $productID : key($products);
            $product   = $this->product->getById($productID);
            if($product->type != 'normal')
            {
                $productBranches = $this->loadModel('execution')->getBranchByProduct($productID, $objectID, 'noclosed|withMain');
                if(isset($productBranches[$productID])) $branches = $productBranches[$productID];
            }
        }
        else
        {
            $productList = $this->product->getOrderedProducts('noclosed');
            foreach($productList as $product) $products[$product->id] = $product->name;

            $product = $this->product->getById($productID);
            if(!isset($products[$product->id])) $products[$product->id] = $product->name;
            if($product->type != 'normal')      $branches = $this->loadModel('branch')->getPairs($productID, 'active');
        }

        return array($products, $branches);
    }

    /**
     * 获取创建需求的表单字段。
     * Get form fields for create
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $objectID
     * @param  object    $initStory
     * @access protected
     * @return array
     */
    protected function getFormFieldsForCreate(int $productID, string $branch, int $objectID, object $initStory): array
    {
        $account = $this->app->user->account;
        $fields  = $this->config->story->form->create;

        /* 准备数据。*/
        list($products, $branches) = $this->storyZen->getProductsAndBranchesForCreate($productID, $objectID);
        if($objectID)
        {
            $branch    = key($branches);
            $productID = (!empty($productID) && isset($products[$productID])) ? $productID : key($products);
        }
        $branch = current(explode(',', $branch));

        $product    = $this->product->getByID($productID);
        $users      = $this->user->getPairs('pdfirst|noclosed|nodeleted');
        $stories    = $this->story->getParentStoryPairs($productID);
        $plans      = $this->loadModel('productplan')->getPairsForStory($productID, $branch == 0 ? '' : $branch, 'skipParent|unexpired|noclosed');
        $plans      = array_map(function($planName){return str_replace(FUTURE_TIME, $this->lang->story->undetermined, $planName);}, $plans);
        $needReview = ($account == $product->PO || $objectID > 0 || $this->config->story->needReview == 0 || !$this->story->checkForceReview());

        $reviewers  = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '', '');
        $reviewers  = $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);

        /* 追加字段的name、title属性，展开user数据。 */
        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            if(!isset($fields[$field]['name']))  $fields[$field]['name']  = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title'] = zget($this->lang->story, $field);
        }

        /* 设置下拉菜单内容。 */
        $fields['product']['options']  = $products;
        $fields['branch']['options']   = $branches;
        $fields['branches']['options'] = $branches;
        $fields['plan']['options']     = $plans;
        $fields['plans']['options']    = $plans;
        $fields['reviewer']['options'] = $reviewers;
        $fields['parent']['options']   = $stories;

        /* 设置默认值。 */
        foreach($initStory as $field => $defaultValue)
        {
            if(isset($fields[$field])) $fields[$field]['default'] = $defaultValue;
        }
        if(empty($fields['product']['default']))  $fields['product']['default']  = $productID;
        if(empty($fields['branch']['default']))   $fields['branch']['default']   = $branch;
        if(empty($fields['branches']['default'])) $fields['branches']['default'] = $branch;
        if(empty($fields['plans']['default']))    $fields['plans']['default']    = zget($initStory, 'plan', 0);
        if(empty($needReview))                    $fields['reviewer']['default'] = $product->PO;

        /* 删除不需要的字段。 */
        if(empty($branches)) unset($fields['branch'], $fields['branches'], $fields['modules'], $fields['plans']);

        $this->view->productID  = $productID;
        $this->view->product    = $product;
        $this->view->branch     = $branch;
        $this->view->branches   = $branches;
        $this->view->objectID   = $objectID;
        $this->view->needReview = $needReview;

        return $fields;
    }

    /**
     * 设置模块字段的表单字段。
     * Set module form field.
     *
     * @param  array     $fields
     * @param  int       $moduleID
     * @access protected
     * @return array
     */
    protected function setModuleField(array $fields, int $moduleID): array
    {
        $productID  = $this->view->productID;
        $branch     = $this->view->branch;
        $optionMenu = $this->tree->getOptionMenu($productID, 'story', 0, $branch === 'all' ? 0 : $branch);

        $moduleID = $moduleID ? $moduleID : (int)$this->cookie->lastStoryModule;
        $moduleID = isset($optionMenu[$moduleID]) ? $moduleID : 0;

        $fields['module']['options']  = $optionMenu;
        $fields['module']['default']  = $moduleID;
        $fields['modules']['options'] = $optionMenu;
        $fields['modules']['default'] = $moduleID;

        $this->view->moduleID = $moduleID;
        return $fields;
    }

    /**
     * 根据配置，删除非必要的表单字段配置。
     * Remove form fields for create.
     *
     * @param  array     $fields
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function removeFormFieldsForCreate(array $fields, string $storyType = 'story'): array
    {
       $productID = $this->view->productID;
       $branch    = $this->view->branch;
       $objectID  = $this->view->objectID;

        /* Hidden some fields of projects without products. */
        $hiddenProduct = $hiddenParent = $hiddenPlan = $hiddenURS = false;
        $teamUsers     = $URS = array();
        $showFeedback  = in_array($fields['source']['default'], $this->config->story->feedbackSource);

        if($storyType == 'story')
        {
            $moduleIdList = $this->tree->getAllChildId($this->view->moduleID);
            $URS          = $this->story->getProductStoryPairs($productID, $branch, $moduleIdList, 'changing,active,reviewing', 'id_desc', 0, '', 'requirement');
        }
        $fields['URS']['options'] = $URS;

        if($this->app->tab === 'project' || $this->app->tab === 'execution')
        {
            $project = $this->dao->findById((int)$objectID)->from(TABLE_PROJECT)->fetch();
            if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

            if(empty($project->hasProduct))
            {
                $teamUsers     = $this->project->getTeamMemberPairs($project->id);
                $hiddenProduct = $hiddenParent = true;

                if($project->model !== 'scrum' or !$project->multiple) $hiddenPlan = true;
                if($project->model === 'kanban') $hiddenURS  = true;
            }
        }
        if($hiddenParent)         unset($fields['parent']);
        if($hiddenPlan)           unset($fields['plan']);
        if(!$showFeedback)        unset($fields['feedbackBy'], $fields['notifyEmail']);
        if($storyType == 'story') unset($fields['branch']);
        if($storyType != 'story') unset($fields['region'], $fields['lane'], $fields['branches'], $fields['modules'], $fields['plans']);
        if($storyType != 'story' || !$this->config->URSR || $hiddenURS) unset($fields['URS']);
        if($hiddenProduct)
        {
            $fields['product']['control']    = 'hidden';
            $fields['reviewer']['options']   = $teamUsers;
            $fields['assignedTo']['options'] = $teamUsers;
        }

        /* Set Custom. */
        $customFields = explode(',', $this->config->story->list->customCreateFields);
        $showFields   = explode(',', trim($this->config->story->custom->createFields, ','));
        foreach($customFields as $field)
        {
            if(!str_contains($showFields, $field)) $fields[$field]['control'] = '';
        }
    }

    /**
     * 获取指派给我的Block编号。
     * Get assign me block id.
     *
     * @access protected
     * @return int
     */
    protected function getAssignMeBlockID(): int
    {
        if(!isonlybody()) return 0;
        return (int)$this->dao->select('id')->from(TABLE_BLOCK)->where('block')->eq('assingtome')
            ->andWhere('module')->eq('my')
            ->andWhere('account')->eq($this->app->user->account)
            ->orderBy('order_desc')
            ->limit(1)
            ->fetch('id');
    }

    /**
     * 构建创建需求数据。
     * Build story for create
     *
     * @param  int       $executionID
     * @param  int       $bugID
     * @access protected
     * @return object
     */
    protected function buildStoryForCreate(int $executionID, int $bugID): object
    {
        $fields       = $this->config->story->form->create;
        $editorFields = array_keys(array_filter(array_map(function($config){return $config['control'] == 'editor';}, $fields)));
        foreach(explode(',', trim($this->config->story->create->requiredFields, ',')) as $field) $fields[$field]['required'] = true;
        if($this->post->type == 'requirement') $fields['plan']['required'] = false;

        $storyData = form::data($fields)
            ->setIF($this->post->assignedTo, 'assignedDate', helper::now())
            ->setIF($this->post->plan > 0, 'stage', 'planned')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF($executionID > 0, 'stage', 'projected')
            ->setIF($bugID > 0, 'fromBug', $bugID)
            ->get();

        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors['reviewer'] = sprintf($this->lang->error->notempty, $this->lang->story->reviewedBy);
            return false;
        }

        if($storyData->status != 'draft' and $this->product->checkForceReview()) $storyData->status = 'reviewing';
        return $this->loadModel('file')->processImgURL($storyData, $editorFields, $this->post->uid);
    }

    /**
     * 检查需求是否重复。
     * Check repeat story.
     *
     * @param  object    $story
     * @param  int       $objectID
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function checkRepeatStory(object $story, int $objectID, string $storyType = 'story'): array
    {
        /* Check repeat story. */
        $result = $this->loadModel('common')->removeDuplicate('story', $story, "product={$story->product}");
        if(empty($result['stop'])) return array();

        $response['result']  = 'success';
        $response['message'] = sprintf($this->lang->duplicate, $this->lang->story->common);
        $response['locate']  = $this->createLink('story', 'view', "storyID={$result['duplicate']}&version=0&param=0&storyType=$storyType");
        if($objectID)
        {
            $execution          = $this->dao->findById((int)$objectID)->from(TABLE_EXECUTION)->fetch();
            $moduleName         = $execution->type == 'project' ? 'projectstory' : 'execution';
            $param              = $execution->type == 'project' ? "projectID=$objectID&productID={$story->product}" : "executionID=$objectID";
            $response['locate'] = $this->createLink($moduleName, 'story', $param);
        }

        return $response;
    }

    /**
     * 如果是在弹窗中创建需求，获取创建后的跳转地址。
     * Get response when after create story in modal.
     *
     * @param  string    $message
     * @access protected
     * @return array|false
     */
    protected function responseAfterCreateInModal(string $message): array|false
    {
        if(!isonlybody()) return false;
        if($this->app->tab != 'execution') return array('result' => 'success', 'message' => $message, 'reload' => true, 'closedModal' => true);

        $execution         = $this->execution->getByID($this->session->execution);
        $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
        $executionGroupBy  = $this->session->executionGroupBy  ? $this->session->executionGroupBy  : 'default';

        if($execution->type == 'kanban')
        {
            $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
            $kanbanData    = $this->loadModel('kanban')->getRDKanban($this->session->execution, $executionLaneType, 'id_desc', 0, $executionGroupBy, $rdSearchValue);
            $kanbanData    = json_encode($kanbanData);
            return array('result' => 'success', 'message' => $message, 'closeModal' => true, 'callback' => "updateKanban($kanbanData, 0)");
        }

        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($execution->id, $executionLaneType, $executionGroupBy, $taskSearchValue);
        $kanbanType      = $executionLaneType == 'all' ? 'story' : key($kanbanData);
        $kanbanData      = $kanbanData[$kanbanType];
        $kanbanData      = json_encode($kanbanData);
        return array('result' => 'success', 'message' => $message, 'closeModal' => true, 'callback' => "updateKanban(\"story\", $kanbanData)");
    }

    /**
     * 获取创建需求后的跳转地址。
     * Get location when after create story.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $objectID
     * @param  int       $storyID
     * @param  int       $copyStoryID
     * @param  string    $storyType
     * @access protected
     * @return string
     */
    protected function getAfterCreateLocation(int $productID, string $branch, int $objectID, int $storyID, int $copyStoryID, string $storyType): string
    {
        if($this->app->getViewType() == 'xhtml') return $this->createLink('story', 'view', "storyID=$storyID", 'html');

        if($objectID)
        {
            helper::setcookie('storyModuleParam', '0', 0);
            return $this->session->storyList;
        }

        helper::setcookie('storyModule', '0', 0);
        $moduleID = $this->post->module ? $this->post->module : 0;
        $branchID = $this->post->branch  ? $this->post->branch  : $branch;
        if(!$this->session->storyList) return $this->createLink('product', 'browse', "productID=$productID&branch=$branchID&browseType=&param=0&storyType=$storyType&orderBy=id_desc");
        if(!empty($_POST['branches']) and count($_POST['branches']) > 1) return preg_replace('/branch=(\d+|[A-Za-z]+)/', 'branch=all', $this->session->storyList);
        return $this->session->storyList;
    }
}
