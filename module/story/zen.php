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
     * 为批量创建需求页面设置导航。
     * Set menu for batch create.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function setMenuForBatchCreate(int $productID, string $branch = '', int $executionID = 0): void
    {
        $this->view->hiddenProduct = false;
        $this->view->hiddenPlan    = false;

        /* Set menu. */
        if($this->app->tab == 'project' and $this->config->vision == 'lite')
        {
            $this->project->setMenu($this->session->project);
            return;
        }

        if(empty($executionID))
        {
            $this->product->setMenu($productID, $branch);
            return;
        }

        $execution = $this->dao->findById((int)$executionID)->from(TABLE_EXECUTION)->fetch();
        $this->view->execution = $execution;
        if($execution->type == 'project')
        {
            $this->project->setMenu($executionID);
            $this->lang->navGroup->story = 'project';

            $model = $execution->model == 'waterfallplus' ? 'waterfall' : $execution->model;
            if($execution->model == 'agileplus') $model = 'scrum';
            $this->lang->product->menu = $this->lang->{$model}->menu;
        }
        else
        {
            $this->execution->setMenu($executionID);
            $this->lang->navGroup->story = 'execution';

            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $output      = $this->story->parseExtra($extra);
                $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'story');
                $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->regionID    = $regionID;
                $this->view->laneID      = $laneID;
                $this->view->regionPairs = $regionPairs;
                $this->view->lanePairs   = $lanePairs;
            }
        }

        if($this->app->tab != 'project' && $this->app->tab != 'execution') return;

        /* Hidden some fields of projects without products. */
        $project = $this->dao->findById($executionID)->from(TABLE_PROJECT)->fetch();
        if($project->project)    $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();
        if($project->hasProduct) return;

        $this->view->hiddenProduct = true;
        if($project->model !== 'scrum') $this->view->hiddenPlan = true;
        if(!$project->multiple)         $this->view->hiddenPlan = true;
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
     * @param  int       $storyID
     * @param  int       $bugID
     * @param  int       $todoID
     * @access protected
     * @return object
     */
    protected function initStoryForCreate(int $planID, int $storyID, int $bugID, int $todoID): object
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

        if($storyID > 0) $initStory = $this->getInitStoryByStory($copyStoryID, $initStory);
        if($bugID   > 0) $initStory = $this->getInitStoryByBug($bugID, $initStory);
        if($todoID  > 0) $initStory = $this->getInitStoryByTodo($todoID, $initStory);
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
        list($products, $branches) = $this->getProductsAndBranchesForCreate($productID, $objectID);
        if($objectID)
        {
            $branch    = is_array($branches) && !empty($branches) ? key($branches) : $branch;
            $productID = (!empty($productID) && isset($products[$productID])) ? $productID : key($products);
        }
        $branch = str_contains($branch, ',') ? current(explode(',', $branch)) : $branch;

        $product     = $this->product->getByID($productID);
        $users       = $this->user->getPairs('pdfirst|noclosed|nodeleted');
        $stories     = $this->story->getParentStoryPairs($productID);
        $plans       = $this->loadModel('productplan')->getPairsForStory($productID, $branch == 0 ? '' : $branch, 'skipParent|unexpired|noclosed');
        $plans       = array_map(function($planName){return str_replace(FUTURE_TIME, $this->lang->story->undetermined, $planName);}, $plans);
        $forceReview = $this->story->checkForceReview();
        $needReview  = ($account == $product->PO || $objectID > 0 || $this->config->story->needReview == 0 || !$forceReview);
        $reviewers   = $this->story->getProductReviewers($productID);

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

        if(empty($needReview)) $fields['reviewer']['default']  = $product->PO;
        if($forceReview)       $fields['reviewer']['required'] = true;

        /* 删除不需要的字段。 */
        if(empty($branches)) unset($fields['branch'], $fields['branches'], $fields['modules'], $fields['plans']);

        $this->view->productID   = $productID;
        $this->view->product     = $product;
        $this->view->branch      = $branch;
        $this->view->branches    = $branches;
        $this->view->objectID    = $objectID;
        $this->view->forceReview = $forceReview;
        $this->view->needReview  = $needReview;

        return $fields;
    }

    /**
     * 获取批量创建需求的表单字段。
     * Get form fields for batch create.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function getFormFieldsForBatchCreate(int $productID, string $branch, int $executionID = 0): array
    {
        $product = $this->loadModel('product')->getByID($productID);
        $fields  = $this->config->story->form->batchCreate;

        if($executionID)
        {
            $productBranches = $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $executionID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }
        $branch    = current(explode(',', $branch));
        $modules   = $this->tree->getOptionMenu($productID, 'story', 0, $branch === 'all' ? 0 : $branch);
        $plans     = $this->loadModel('productplan')->getPairsForStory($productID, ($branch === 'all' or empty($branch)) ? '' : $branch, 'skipParent|unexpired|noclosed');
        $users     = $this->user->getPairs('pdfirst|noclosed|nodeleted');
        $reviewers = $this->story->getProductReviewers($productID);

        /* 设置下拉菜单内容。 */
        $fields['branch']['options']   = $branches;
        $fields['module']['options']   = $modules;
        $fields['plan']['options']     = $plans;
        $fields['reviewer']['options'] = $reviewers;

        if($this->story->checkForceReview()) $fields['reviewer']['required'] = true;
        if(empty($branches)) unset($fields['branch']);
        if($this->view->hiddenPlan) unset($fields['plan']);

        $this->view->branchID = $branch;
        return $fields;
    }

    /**
     * 获取变更需求的表单字段。
     * Get form fields for change story.
     *
     * @param  int       $storyID
     * @access protected
     * @return array
     */
    protected function getFormFieldsForChange(int $storyID): array
    {
        $story  = $this->view->story;
        $fields = $this->config->story->form->change;

        foreach($fields as $field => $attr)
        {
            if(!isset($fields[$field]['name']))  $fields[$field]['name']  = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title'] = zget($this->lang->story, $field);
        }

        $reviewer = $this->story->getReviewerPairs($storyID, $story->version);
        $fields['reviewer']['options'] = $this->story->getProductReviewers($story->product, $reviewer);

        $fields['reviewer']['default'] = $reviewer;
        $fields['title']['default']    = $story->title;
        $fields['color']['default']    = $story->color;
        $fields['spec']['default']     = $story->spec;
        $fields['verify']['default']   = $story->verify;
        $fields['status']['default']   = $story->status;

        $forceReview = $this->story->checkForceReview();
        if($forceReview) $fields['reviewer']['required'] = true;

        $this->view->forceReview = $forceReview;
        $this->view->needReview  = ($this->app->user->account == $this->view->product->PO || $this->config->story->needReview == 0 || !$forceReview) && empty($reviewer);

        $fields['comment'] = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'name' => 'comment', 'title' => $this->lang->comment);
        return $fields;
    }

    /**
     * 获取评审需求的表单字段。
     * Get form fields for review story.
     *
     * @param  int       $storyID
     * @access protected
     * @return array
     */
    protected function getFormFieldsForReview(int $storyID): array
    {
        $story      = $this->view->story;
        $fields     = $this->config->story->form->review;
        $users      = $this->loadModel('user')->getPairs('nodeleted|noclosed', "$story->lastEditedBy,$story->openedBy");
        $resultList = $this->lang->story->reviewResultList;
        if($story->status == 'reviewing')
        {
            if($story->version == 1) unset($resultList['revert']);
            if($story->version > 1)  unset($resultList['reject']);
        }

        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            if(!isset($fields[$field]['name']))  $fields[$field]['name']  = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title'] = zget($this->lang->story, $field);
        }
        $fields['result']['options'] = $resultList;

        $fields['reviewedDate']['default'] = helper::now();
        $fields['assignedTo']['default']   = $story->assignedTo;
        $fields['pri']['default']          = $story->pri;
        $fields['estimate']['default']     = $story->estimate;
        $fields['status']['default']       = $story->status;

        $fields['closedReason']['required']   = true;
        $fields['duplicateStory']['required'] = true;

        $fields['comment'] = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'name' => 'comment', 'title' => $this->lang->comment, 'width' => 'full');
        $this->view->users = $users;
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
        $showFields   = trim($this->config->story->custom->createFields, ',');
        foreach($customFields as $field)
        {
            if(!str_contains($showFields, $field)) unset($fields[$field]);
        }

        return $fields;
    }

    /**
     * 根据配置，删除非必要的表单字段配置。
     * Remove form fields for batch create.
     *
     * @param  int       $productID
     * @param  array     $fields
     * @param  string    $productType
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function removeFormFieldsForBatchCreate(int $productID, array $fields, string $productType, string $storyType = 'story'): array
    {
        $product = $this->product->getByID($productID);

        /* Set Custom*/
        foreach(explode(',', $this->config->story->list->customBatchCreateFields) as $field)
        {
            if($productType != 'normal') $customFields[$productType] = $this->lang->product->branchName[$productType];
            $customFields[$field] = $this->lang->story->$field;
        }

        $showFields = $this->config->story->custom->batchCreateFields;
        if($storyType == 'requirement')
        {
            unset($customFields['plan']);
            $showFields = str_replace(',plan,', ',', ",$showFields,");
        }

        foreach($customFields as $field => $fieldName)
        {
            if(!str_contains(",$showFields,", ",$field,")) unset($fields[$field]);
        }

        $this->view->customFields = $customFields;
        return $fields;
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
     * @return object|false
     */
    protected function buildStoryForCreate(int $executionID, int $bugID): object|false
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
     * 构建评审需求数据。
     * Build story for review
     *
     * @param  int       $storyID
     * @access protected
     * @return object|false
     */
    protected function buildStoryForReview(int $storyID): object|false
    {
        $now    = helper::now();
        $fields = $this->config->story->form->review;
        foreach(explode(',', trim($this->config->story->create->requiredFields, ',')) as $field)
        {
            if($field == 'comment' && !$this->post->comment)
            {
                dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
                return false;
            }
            if(isset($fields[$field])) $fields[$field]['required'] = true;
        }
        if($this->post->result == false)
        {
            dao::$errors[] = $this->lang->story->mustChooseResult;
            return false;
        }

        $editorFields = array_keys(array_filter(array_map(function($config){return $config['control'] == 'editor';}, $fields)));
        $result       = $this->post->result;
        $closedReason = $this->post->closedReason;
        $oldStory     = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $storyData    = form::data($fields)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->removeIF($result != 'reject', 'closedReason,duplicateStory,childStories')
            ->removeIF($result == 'reject' && $closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($result == 'reject' && $closedReason != 'subdivided', 'childStories')
            ->get();

        if($oldStory->assignedTo != $storyData->assignedTo) $storyData->assignedDate = $now;
        $storyData->reviewedBy = implode(',', array_unique(explode(',', $oldStory->reviewedBy . ',' . $this->app->user->account)));

        if($result == 'reject' && empty($closedReason)) dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->story->rejectedReason);
        if($result == 'reject' && $closedReason == 'duplicate' && empty($storyData->duplicateStory)) dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->story->duplicateStory);
        if(dao::isError()) return false;

        return $this->loadModel('file')->processImgURL($storyData, $editorFields, $this->post->uid);
    }

    /**
     * 构建批量创建需求数据。
     * Build stories for batch create.
     *
     * @param  int       $productID
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function buildStoriesForBatchCreate(int $productID, string $storyType): array
    {
        $forceReview = $this->story->checkForceReview();
        $fields      = $this->config->story->form->batchCreate;
        $account     = $this->app->user->account;
        $now         = helper::now();
        $saveDraft   = $this->post->status == 'draft';
        if($forceReview) $fields['reviewer']['required'] = true;

        $stories = form::batchData($fields)->get();
        foreach($stories as $i => $story)
        {
            $story->type       = $storyType;
            $story->status     = (empty($story->reviewer) && !$forceReview) ? 'active' : 'reviewing';
            $story->stage      = ($this->app->tab == 'project' || $this->app->tab == 'execution') ? 'projected' : 'wait';
            $story->product    = $productID;
            $story->openedBy   = $account;
            $story->vision     = $this->config->vision;
            $story->openedDate = $now;
            $story->version    = 1;
            if($this->post->status == 'draft') $story->status      = 'draft';
            if($this->post->uploadImage[$i])   $story->uploadImage = $this->post->uploadImage[$i];
        }

        return $stories;
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
     * @param  int       $executionID
     * @access protected
     * @return array|false
     */
    protected function responseAfterCreateInModal(string $message, int $executionID = 0): array|false
    {
        if(!isonlybody()) return false;
        if($this->app->tab != 'execution') return array('result' => 'success', 'message' => $message, 'load' => true, 'closedModal' => true);

        $executionID       = $executionID ? $executionID : $this->session->execution;
        $execution         = $this->execution->getByID($executionID);
        $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
        $executionGroupBy  = $this->session->executionGroupBy  ? $this->session->executionGroupBy  : 'default';

        if($execution->type == 'kanban')
        {
            $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
            $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $executionLaneType, 'id_desc', 0, $executionGroupBy, $rdSearchValue);
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
     * @param  string    $storyType
     * @access protected
     * @return string
     */
    protected function getAfterCreateLocation(int $productID, string $branch, int $objectID, int $storyID, string $storyType): string
    {
        if($this->app->getViewType() == 'xhtml') return $this->createLink('story', 'view', "storyID=$storyID", 'html');

        if($objectID)
        {
            helper::setcookie('storyModuleParam', '0', 0);
            return $this->session->storyList;
        }

        helper::setcookie('storyModule', '0', 0);
        $branchID = $this->post->branch  ? $this->post->branch  : $branch;
        if(!$this->session->storyList) return $this->createLink('product', 'browse', "productID=$productID&branch=$branchID&browseType=&param=0&storyType=$storyType&orderBy=id_desc");
        if(!empty($_POST['branches']) and count($_POST['branches']) > 1) return preg_replace('/branch=(\d+|[A-Za-z]+)/', 'branch=all', $this->session->storyList);
        return $this->session->storyList;
    }

    /**
     * 获取批量创建需求后的跳转地址。
     * Get after batch create location.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $executionID
     * @param  int       $storyID
     * @param  string    $storyType
     * @access protected
     * @return string
     */
    protected function getAfterBatchCreateLocation(int $productID, string $branch, int $executionID, int $storyID, string $storyType): string
    {
        if($storyID)
        {
            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('projectstory', 'view', "storyID=$storyID");
            if($this->app->tab == 'product') $locateLink = $this->inlink('view', "storyID=$storyID&version=0&param=0&storyType=$storyType");
            return $locateLink;
        }

        if($executionID)
        {
            helper::setcookie('storyModuleParam', '0', 0);
            return $this->session->storyList;
        }

        helper::setcookie('storyModule', '0', 0);
        if($this->session->storyList) return $this->session->storyList;
        return $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=unclosed&queryID=0&storyType=$storyType");
    }

    /**
     * 获取变更需求后的跳转地址。
     * Get after change story location.
     *
     * @param  int       $storyID
     * @param  string    $storyType
     * @access protected
     * @return string
     */
    protected function getAfterChangeLocation(int $storyID, string $storyType = 'story'): string
    {
        if($this->app->tab == 'execution') return helper::createLink('execution', 'storyView', "storyID=$storyID");
        if($this->app->tab != 'project') return helper::createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType");

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
            return helper::createLink($module, $module, $params);
        }
    }

    /**
     * 获取评审需求后的跳转地址。
     * Get after review location.
     *
     * @param  int       $storyID
     * @param  string    $storyType
     * @param  string    $from
     * @access protected
     * @return string
     */
    protected function getAfterReviewLocation(int $storyID, string $storyType = 'story', string $from = ''): string
    {
        if($from == 'project') return helper::createLink('projectstory', 'view', "storyID={$storyID}");
        if($from != 'execution') return helper::createLink('story', 'view', "storyID={$storyID}&version=0&param=0&storyType={$storyType}");

        $execution = $this->execution->getByID($this->session->execution);

        $module = 'story';
        $method = 'view';
        $params = "storyID=$storyID&version=0&param={$this->session->execution}&storyType=$storyType";
        if($execution->multiple)
        {
            $module = 'execution';
            $method = 'storyView';
            $params = "storyID=$storyID";
        }
        return helper::createLink($module, $method, $params);
    }

    /**
     * 根据上传图片，批量创建需求时，获取初始化需求数据。
     * Get data from upload images.
     *
     * @param  int       $productID
     * @param  int       $moduleID
     * @param  int       $planID
     * @access protected
     * @return array
     */
    protected function getDataFromUploadImages(int $productID, int $moduleID = 0, int $planID = 0): array
    {
        /* Clear title when switching products and set the session for the current product. */
        if($productID != $this->cookie->preProductID) unset($_SESSION['storyImagesFile']);
        helper::setcookie('preProductID', (string)$productID);

        $initStory = array('title' => '', 'spec' => '', 'module' => $moduleID, 'plan' => $planID, 'pri' => 3, 'estimate' => 0, 'branch' => $this->view->branchID);
        $stories   = array();
        for($i = 0; $i < $this->config->story->batchCreate; $i++) $stories[] = $initStory;

        if(empty($_SESSION['storyImagesFile'])) return $stories;

        $files   = $this->session->storyImagesFile;
        $stories = array();
        foreach($files as $fileName => $file)
        {
            $initStory['title']       = $file['title'];
            $initStory['uploadImage'] = $fileName;

            $stories[] = $initStory;
        }
        return $stories;
    }
}
