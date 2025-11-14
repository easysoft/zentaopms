<?php
declare(strict_types=1);
/**
 * The zen file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */

class storyZen extends story
{
    /**
     * 设置创建需求页面的导航。
     * Set menu for create story.
     *
     * @param  int       $productID
     * @param  int       $objectID
     * @param  string    $extra
     * @access protected
     * @return int[]
     */
    protected function setMenuForCreate(int $productID, int $objectID, string $extra = ''): array
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
        if($this->app->tab == 'product')
        {
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            if(!empty($output['from']) && $output['from'] == 'global')
            {
                $product = $this->product->getById($productID);
                if(!$product || $product->deleted || $product->shadow)
                {
                    $newProduct = $this->product->getOrderedProducts('noclosed');
                    if(!empty($newProduct)) $productID = (int) key($newProduct);
                }
            }

            $this->product->setMenu($productID);
        }
        if($this->app->tab == 'execution')
        {
            $this->execution->setMenu($objectID);
            $this->view->executionID = $objectID;
        }
        if($this->app->tab == 'project')
        {
            $projectID = $objectID;
            if(!$this->session->multiple)
            {
                $projectID = $this->session->project;
                $objectID  = $this->execution->getNoMultipleID($projectID);
            }

            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->checkAccess($projectID, $projects);

            $this->view->projectID = $projectID;
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
     * @param  string    $extra
     * @param  string    $storyType
     * @access protected
     * @return void
     */
    protected function setMenuForBatchCreate(int $productID, string $branch = '', int $executionID = 0, string $extra = '', $storyType = 'story'): void
    {
        $this->view->hiddenProduct = false;
        $this->view->hiddenPlan    = false;

        /* Set menu. */
        if($this->app->tab == 'project' and $this->config->vision == 'lite')
        {
            $this->project->setMenu($this->session->project);
            $this->view->projectID = $this->session->project;
            return;
        }

        if(empty($executionID))
        {
            if($this->app->tab == 'project')
            {
                $this->project->setMenu($this->session->project);
                $this->view->projectID = $this->session->project;
            }
            else
            {
                $this->product->setMenu($productID, $branch);
            }
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

            $this->view->projectID = $executionID;
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
                $lanePairs   = $this->kanban->getLanePairsByRegion((int)$regionID, $storyType);
                $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->regionID    = $regionID;
                $this->view->laneID      = $laneID;
                $this->view->regionPairs = $regionPairs;
                $this->view->lanePairs   = $lanePairs;
            }
            if($this->app->tab == 'execution') $this->view->executionID = $executionID;
            if($this->app->tab == 'project') $this->view->projectID = $executionID;
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
     * 为批量编辑页面设置导航。
     * Set menu for batch edit page.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @param  string    $storyType   story|requirement
     * @param  string    $from        work|contribute
     * @access protected
     * @return void
     */
    protected function setMenuForBatchEdit(int $productID, int $executionID = 0, string $storyType = 'story', string $from = ''): void
    {
        $this->view->hiddenPlan = false;
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
            return;
        }

        if($this->app->tab == 'execution')
        {
            $this->execution->setMenu($executionID);
            $this->view->executionID = $this->session->execution;
            return;
        }

        if($this->app->tab == 'qa')
        {
            $this->loadModel('qa')->setMenu($productID);
            return;
        }

        if($this->app->tab == 'my')
        {
            $this->loadModel('my');
            if($from == 'work' || $from == 'contribute')
            {
                $this->lang->my->menu->{$from}['subModule'] = $storyType;
                $this->lang->my->menu->{$from}['subMenu']->{$storyType}['subModule'] = 'story';
            }
            return;
        }

        if($this->app->tab == 'project')
        {
            $project = $this->dao->findByID($executionID)->from(TABLE_PROJECT)->fetch();
            if($project->type == 'project')
            {
                if(!($project->model == 'scrum' and !$project->hasProduct and $project->multiple)) $this->view->hiddenPlan = true;
                $this->project->setMenu($executionID);
                $this->view->projectID = $this->session->project;
            }
            else
            {
                if(!$project->hasProduct and !$project->multiple) $this->view->hiddenPlan = true;
                $this->execution->setMenu($executionID);
                $this->view->projectID = $this->session->execution;
            }
        }
    }

    /**
     * 设置批量关闭需求页面的导航。
     * Set menu for batch close.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @param  string    $storyType   story|requirement
     * @param  string    $from        work|contribute
     * @access protected
     * @return void
     */
    protected function setMenuForBatchClose(int $productID, int $executionID = 0, string $storyType = 'story', string $from = '')
    {
        /* The stories of a product. */
        if($this->app->tab == 'product' && $productID)
        {
            $this->product->setMenu($productID);
            $product = $this->product->getByID($productID);
            $this->view->title = $product->name . $this->lang->hyphen . $this->lang->story->batchClose;
        }
        /* The stories of a execution. */
        elseif($this->app->tab == 'execution' && $executionID)
        {
            $this->lang->story->menu      = $this->lang->execution->menu;
            $this->lang->story->menuOrder = $this->lang->execution->menuOrder;
            $this->execution->setMenu($executionID);
            $execution = $this->execution->getByID($executionID);
            $this->view->title       = $execution->name . $this->lang->hyphen . $this->lang->story->batchClose;
            $this->view->executionID = $executionID;
        }
        elseif($this->app->tab == 'project')
        {
            $this->project->setMenu(!empty($this->session->project) ? $this->session->project : $executionID);
            $this->view->title     = $this->lang->story->batchClose;
            $this->view->projectID = $this->session->project;
        }
        else
        {
            if($from == 'work' || $from == 'contribute')
            {
                $this->lang->my->menu->{$from}['subModule'] = $storyType;
                $this->lang->my->menu->{$from}['subMenu']->{$storyType}['subModule'] = 'story';
            }

            $this->lang->story->menu      = $this->lang->my->menu;
            $this->lang->story->menuOrder = $this->lang->my->menuOrder;

            $this->view->title = $this->lang->story->batchClose;
        }
    }

    /**
     * 如果是看板执行，设置界面中要用到关于看板的视图变量。
     * Set view vars for kanban.
     *
     * @param  int       $objectID
     * @param  array     $kanbanSetting
     * @param  string    $storyType
     * @access protected
     * @return void
     */
    protected function setViewVarsForKanban(int $objectID, array $kanbanSetting, string $storyType = 'story'): void
    {
        if(empty($objectID)) return;

        $execution = $this->dao->findById($objectID)->from(TABLE_EXECUTION)->fetch();
        if($execution->type != 'kanban') return ;

        /* 如果是看板执行，设置看板的view变量。 */
        $regionPairs = $this->loadModel('kanban')->getRegionPairs($execution->id, 0, 'execution');
        $regionID    = !empty($kanbanSetting['regionID']) ? $kanbanSetting['regionID'] : key($regionPairs);
        $lanePairs   = $this->kanban->getLanePairsByRegion((int)$regionID, $storyType);
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
     * @param  string    $extra feedback扩展使用
     * @access public
     * @return object
     */
    public function initStoryForCreate(int $planID, int $storyID, int $bugID, int $todoID, string $extra = ''): object
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

        if($storyID > 0) $initStory = $this->getInitStoryByStory($storyID, $initStory);
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
        $initStory->plan        = $story->plan;
        $initStory->module      = $story->module;
        $initStory->source      = $story->source;
        $initStory->sourceNote  = $story->sourceNote;
        $initStory->color       = $story->color;
        $initStory->pri         = $story->pri;
        $initStory->estimate    = $story->estimate;
        $initStory->title       = $story->title;
        $initStory->spec        = $story->spec;
        $initStory->grade       = $story->grade;
        $initStory->verify      = $story->verify;
        $initStory->keywords    = $story->keywords;
        $initStory->mailto      = $story->mailto;
        $initStory->category    = $story->category;
        $initStory->feedbackBy  = $story->feedbackBy;
        $initStory->notifyEmail = $story->notifyEmail;
        $initStory->parent      = $story->parent;
        $initStory->files       = $story->files;

        if($this->config->edition != 'open')
        {
            $extendFields = $this->loadModel('flow')->getExtendFields($story->type, 'create');
            foreach($extendFields as $field) $initStory->{$field->field} = $story->{$field->field};
        }

        foreach($initStory->files as $file)
        {
            $file->name = $file->title;
            $file->url  = $this->createLink('file', 'download', "fileID={$file->id}");
        }
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
        if(!empty($bug->files)) $initStory->files = $bug->files;
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
            $product   = $this->product->getById((int)$productID);
            if($product && $product->type != 'normal')
            {
                $productBranches = $this->loadModel('execution')->getBranchByProduct(array($productID), $objectID, 'noclosed|withMain');
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

        $this->product->checkAccess((int)$productID, $products);
        return array($products, $branches);
    }

    /**
     * 获取产品列表，并排序，将我负责的产品排前面。
     * Get products for edit.
     *
     * @access protected
     * @return array
     */
    protected function getProductsForEdit(): array
    {
        $account        = $this->app->user->account;
        $myProducts     = array();
        $othersProducts = array();
        $products       = $this->loadModel('product')->getList();

        foreach($products as $product)
        {
            if($product->status != 'closed' and $product->PO == $account) $myProducts[$product->id]     = $product->name;
            if($product->status != 'closed' and $product->PO != $account) $othersProducts[$product->id] = $product->name;
        }
        $products = $myProducts + $othersProducts;

        return $products;
    }

    /**
     * 获取创建需求的表单字段。
     * Get form fields for create
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $objectID
     * @param  object    $initStory
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function getFormFieldsForCreate(int $productID, string $branch, int $objectID, object $initStory, string $storyType = 'story'): array
    {
        $account = $this->app->user->account;
        $fields  = $this->config->story->form->create;

        /* 准备数据。*/
        list($products, $branches) = $this->getProductsAndBranchesForCreate($productID, $objectID);
        if($objectID)
        {
            $branch    = is_array($branches) && !empty($branches) ? (string)key($branches) : $branch;
            $productID = (!empty($productID) && isset($products[$productID])) ? $productID : key($products);
        }
        $branch = str_contains($branch, ',') ? current(explode(',', $branch)) : $branch;

        $product        = $this->product->getByID($productID);
        $users          = $this->user->getPairs('pdfirst|noclosed|nodeleted');
        $stories        = $this->story->getParentStoryPairs($productID, '', $storyType);
        $grades         = $this->story->getGradePairs($storyType);
        $plans          = $this->loadModel('productplan')->getPairs($productID, $branch == 0 ? '' : $branch, 'unexpired|noclosed', true);
        $plans          = array_map(function($planName){return str_replace(FUTURE_TIME, $this->lang->story->undetermined, $planName);}, $plans);
        $forceReview    = $this->story->checkForceReview($storyType);
        $needReview     = ($account == $product->PO || $objectID > 0 || $this->config->{$storyType}->needReview == 0 || !$forceReview);
        $reviewers      = $this->story->getProductReviewers($productID);
        $requiredFields = $this->config->{$storyType}->create->requiredFields;

        /* 追加字段的name、title属性，展开user数据。 */
        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            if(!isset($fields[$field]['name']))       $fields[$field]['name']     = $field;
            if(!isset($fields[$field]['title']))      $fields[$field]['title']    = zget($this->lang->story, $field);
            if(str_contains($requiredFields, $field)) $fields[$field]['required'] = true;
        }

        /* 设置下拉菜单内容。 */
        $fields['product']['options']  = $products;
        $fields['branch']['options']   = $branches;
        $fields['branches']['options'] = $branches;
        $fields['plan']['options']     = $plans;
        $fields['plans']['options']    = $plans;
        $fields['grade']['options']    = $grades;
        $fields['grade']['default']    = current($grades);
        $fields['reviewer']['options'] = $reviewers;
        $fields['parent']['options']   = array_filter($stories);

        /* 设置默认值。 */
        foreach($initStory as $field => $defaultValue)
        {
            if(isset($fields[$field])) $fields[$field]['default'] = $defaultValue;
        }
        if(empty($fields['product']['default']))  $fields['product']['default']  = $productID;
        if(empty($fields['branch']['default']))   $fields['branch']['default']   = (int)$branch;
        if(empty($fields['branches']['default'])) $fields['branches']['default'] = (int)$branch;
        if(empty($fields['plans']['default']))    $fields['plans']['default']    = zget($initStory, 'plan', 0);

        if(empty($needReview)) $fields['reviewer']['default']  = $product->PO;
        if($forceReview)       $fields['reviewer']['required'] = true;

        /* 删除不需要的字段。 */
        if(empty($branches)) unset($fields['branch'], $fields['branches'], $fields['modules'], $fields['plans']);
        if($this->config->vision == 'or') unset($fields['plan']);

        $this->view->productID   = $productID;
        $this->view->product     = $product;
        $this->view->branch      = $branch;
        $this->view->branches    = $branches;
        $this->view->objectID    = $objectID;
        $this->view->forceReview = $forceReview;
        $this->view->needReview  = $needReview;
        $this->view->gradeRule   = $this->config->{$storyType}->gradeRule;

        return $fields;
    }

    /**
     * 为编辑页面获取字段配置。
     * Get form fields for edit.
     *
     * @param  int       $storyID
     * @access protected
     * @return array
     */
    protected function getFormFieldsForEdit(int $storyID): array
    {
        $fields = $this->config->story->form->edit;

        /* 准备数据。*/
        $story        = $this->view->story;
        $parent       = $this->story->fetchByID($story->parent);
        $grades       = $this->story->getGradeOptions($parent, $story->type, (array)$story->grade);
        $product      = $this->view->product;
        $users        = $this->loadModel('user')->getPairs('pofirst|nodeleted|noclosed', "$story->assignedTo,$story->openedBy,$story->closedBy");
        $stories      = $this->story->getParentStoryPairs($story->product, $story->parent, $story->type, $storyID);
        $plans        = $this->loadModel('productplan')->getPairs($story->product, $story->branch == 0 ? 'all' : $story->branch, '', true);
        $reviewerList = $this->story->getReviewerPairs($story->id, $story->version);

        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->user->getProductViewListUsers($product);
        $reviewers = $this->user->getPairs('noclosed|nodeleted', array_keys($reviewerList), 0, $reviewers);

        $products = $this->getProductsForEdit();
        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $objectID = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            $products = $this->product->getProductPairsByProject($objectID);
            /* 如果是无产品项目，则所属产品显示需求本身的所属产品。*/
            /* If there is no product item, the product shows the product of story itself.*/
            $object = $this->loadModel('execution')->getByID($objectID);
            if(!$object->hasProduct) $products = array($product->id => $product->name);
            $this->view->objectID = $objectID;
        }

        $branches = $this->loadModel('branch')->getList($product->id, isset($objectID) ? $objectID : 0, 'all');
        $branchTagOption = array();
        foreach($branches as $branchInfo) $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');

        $moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($story->product, 'story', 0, (string)$story->branch);
        if($product->type == 'normal' and !empty($story->branch)) $moduleOptionMenu += $this->tree->getModulesName(array($story->module));

        $storyBranch    = $story->branch > 0 ? $story->branch : '0';
        $branch         = $product->type == 'branch' ? $storyBranch : 'all';
        $productStories = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $story->type);

        /* 追加字段的name、title属性，展开user数据。 */
        foreach($fields as $field => $attr)
        {
            if(isset($attr['options']) and $attr['options'] == 'users') $fields[$field]['options'] = $users;
            if(isset($story->$field))            $fields[$field]['default'] = $story->$field;
            if(!isset($fields[$field]['name']))  $fields[$field]['name']    = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title']   = zget($this->lang->story, $field);
        }

        /* 设置下拉菜单内容。 */
        if(isset($stories[$storyID])) unset($stories[$storyID]);
        $fields['product']['options']        = $products;
        $fields['branch']['options']         = $branchTagOption;
        $fields['module']['options']         = $moduleOptionMenu;
        $fields['plan']['options']           = $plans;
        $fields['reviewer']['options']       = $reviewers;
        $fields['parent']['options']         = array_filter($stories);
        $fields['grade']['options']          = $grades;
        $fields['duplicateStory']['options'] = $productStories;
        $fields['assignedTo']['options']    += array('closed' => 'Closed');
        $fields['stage']['options']          = $this->lang->{$story->type}->stageList;

        /* 设置默认值。 */
        if(empty($fields['reviewer']['default'])) $fields['reviewer']['default'] = implode(',', array_keys($reviewerList));

        if($story->type == 'story') unset($fields['stage']['options']['inroadmap'], $fields['stage']['options']['incharter']);

        $this->view->users          = $users;
        $this->view->storyReviewers = array_keys($reviewerList);
        $this->view->gradeRule      = $this->config->{$story->type}->gradeRule;

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
    protected function getFormFieldsForBatchCreate(int $productID, string $branch, int $executionID = 0, string $storyType = ''): array
    {
        $product = $this->loadModel('product')->getByID($productID);
        $fields  = $this->config->story->form->batchCreate;

        foreach(explode(',', trim($this->config->{$storyType}->create->requiredFields, ',')) as $field) $fields[$field]['required'] = true;

        if($executionID)
        {
            $productBranches = $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct(array($productID), $executionID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = empty($branches) ? '' : key($branches);

            if(isset($this->view->execution->type) && $this->view->execution->type == 'kanban')
            {
                $fields['region']['options'] = zget($this->view, 'regionPairs', array());
                $fields['lane']['options']   = zget($this->view, 'lanePairs', array());
                $fields['region']['default'] = zget($this->view, 'regionID', 0);
                $fields['lane']['default']   = zget($this->view, 'laneID', 0);
            }
        }
        else
        {
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }
        $branch    = current(explode(',', (string)$branch));
        $modules   = $this->tree->getOptionMenu($productID, 'story', 0, $branch === 'all' ? '0' : $branch);
        $plans     = $this->loadModel('productplan')->getPairs($productID, ($branch === 'all' or empty($branch)) ? '' : $branch, 'unexpired|noclosed', true);
        $reviewers = $this->story->getProductReviewers($productID);
        $users     = $this->user->getPairs('pdfirst|noclosed|nodeleted');
        $stories   = $this->story->getParentStoryPairs($productID, '', $storyType);
        $grades    = $this->story->getGradePairs($storyType);

        /* 追加字段的label属性。 */
        foreach($fields as $field => $attr)
        {
            if(!isset($attr['label']))
            {
                if(strpos(',region,lane,', ",$field,") !== false)
                {
                    $this->app->loadLang('kanban');
                    $fields[$field]['label'] = zget($this->lang->kanbancard, $field);
                    continue;
                }

                $fields[$field]['label'] = zget($this->lang->story, $field);
            }
        }

        /* 设置下拉菜单内容。 */
        $fields['branch']['options'] = $branches;
        if($product->type == 'normal') unset($fields['branch']);

        $fields['grade']['default']      = key($grades);
        $fields['module']['options']     = $modules;
        $fields['grade']['options']      = $grades;
        $fields['reviewer']['options']   = $reviewers;
        $fields['assignedTo']['options'] = $users;
        $fields['mailto']['options']     = $users;
        $fields['parent']['options']     = array_filter($stories);

        if(!empty($fields['plan'])) $fields['plan']['options'] = $plans;

        if($this->story->checkForceReview($storyType)) $fields['reviewer']['required'] = true;
        if(empty($branches)) unset($fields['branch']);
        if(!empty($this->view->hiddenPlan)) unset($fields['plan']);

        $hiddenGrade = empty($this->config->showStoryGrade);
        if($hiddenGrade && $storyType == 'epic') $fields['parent']['hidden'] = true;

        $this->view->branchID    = $branch;
        $this->view->gradeRule   = $this->config->{$storyType}->gradeRule;
        $this->view->hiddenGrade = $hiddenGrade;
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
        unset($fields['relievedTwins']);
        unset($fields['deleteFiles']);
        unset($fields['renameFiles']);
        unset($fields['docs']);
        unset($fields['oldDocs']);
        unset($fields['docVersions']);

        foreach(array_keys($fields) as $field)
        {
            if(!isset($fields[$field]['name']))  $fields[$field]['name']  = $field;
            if(!isset($fields[$field]['title'])) $fields[$field]['title'] = $field == 'reviewer' ? $this->lang->story->reviewers : zget($this->lang->story, $field);
        }

        $reviewerAndResultPairs = $this->story->getReviewerPairs($storyID, $story->version);
        $reviewer = array_keys($reviewerAndResultPairs);
        $fields['reviewer']['options'] = $this->story->getProductReviewers($story->product, $reviewer);

        $fields['reviewer']['default'] = $reviewer;
        $fields['title']['default']    = $story->title;
        $fields['color']['default']    = $story->color;
        $fields['spec']['default']     = $story->spec;
        $fields['verify']['default']   = $story->verify;
        $fields['status']['default']   = $story->status;

        $forceReview = $this->story->checkForceReview($story->type);
        if($forceReview) $fields['reviewer']['required'] = true;

        $this->view->forceReview = $forceReview;
        $this->view->needReview  = ($this->app->user->account == $this->view->product->PO || $this->config->{$story->type}->needReview == 0 || !$forceReview) && empty($reviewer);

        $fields['comment'] = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'name' => 'comment', 'title' => $this->lang->comment);

        $requiredFields = $this->config->{$story->type}->change->requiredFields;
        if(strpos(",{$requiredFields},", ',comment,') !== false) $fields['comment']['required'] = true;
        if(strpos(",{$requiredFields},", ',spec,')    !== false) $fields['spec']['required']    = true;
        if(strpos(",{$requiredFields},", ',verify,')  !== false) $fields['verify']['required']  = true;

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
        $resultList = $this->lang->{$story->type}->reviewResultList;
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
        $fields['estimate']['default']     = $story->estimate ? $story->estimate : 0;
        $fields['status']['default']       = $story->status;

        $fields['closedReason']['required']   = true;
        $fields['duplicateStory']['required'] = true;

        $fields['comment'] = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'name' => 'comment', 'title' => $this->lang->comment, 'width' => 'full');

        if(strpos($this->config->{$story->type}->review->requiredFields, 'reviewedDate') !== false) $fields['reviewedDate']['required'] = true;
        if(strpos($this->config->{$story->type}->review->requiredFields, 'comment') !== false)      $fields['comment']['required']      = true;

        $this->view->users = $users;
        return $fields;
    }

    /**
     * Set form options for batch edit.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @param  array     $stories
     * @access protected
     * @return void
     */
    protected function setFormOptionsForBatchEdit(int $productID, int $executionID, array $stories)
    {
        $this->loadModel('product');
        $this->loadModel('tree');
        if($productID and !$executionID)
        {
            $product = $this->product->getByID($productID);
            $options = $this->getFormOptionsForSingleProduct($productID, $executionID, $product);
            $branchProduct   = $options['branchProduct'];
            $modules         = $options['modules'];
            $branchTagOption = $options['branchTagOption'];
            $products        = $options['products'];
            $plans           = $options['plans'];
        }
        else
        {
            /* Get product id list by the stories. */
            $branchProduct   = false;
            $branchTagOption = array();
            $plans           = array();
            $modules         = array();
            $productIdList   = array();
            foreach($stories as $story) $productIdList[$story->product] = $story->product;
            $products = $this->product->getByIdList($productIdList);

            foreach($products as $storyProduct)
            {
                $options = $this->getFormOptionsForSingleProduct($storyProduct->id, $executionID, $storyProduct);
                if($options['branchProduct'] && !$branchProduct) $branchProduct = true;
                if($options['branchProduct']) $branchTagOption[$storyProduct->id] = $options['branchTagOption'][$storyProduct->id];
                $modules += $options['modules'];
                $plans   += $options['plans'];
                if(empty($plans[$storyProduct->id])) $plans[$storyProduct->id][0] = $plans[$storyProduct->id];
            }
        }

        /* Append module when change product type. */
        $moduleList       = array(0 => '/');
        $productStoryList = array();
        foreach($stories as $story)
        {
            $moduleList[$story->id] = array();
            if(isset($modules[$story->product][$story->branch])) $moduleList[$story->id] = $modules[$story->product][$story->branch];
            if(empty($moduleList[$story->id]) and isset($modules[$story->product])) $moduleList[$story->id] = zget($modules[$story->product], 0, array()) + $this->tree->getModulesName(array($story->module));

            if($story->status == 'closed')
            {
                $storyBranch  = $story->branch > 0 ? $story->branch : '0';
                $branch       = $products[$story->product]->type == 'branch' ? $storyBranch : 'all';
                if(!isset($productStoryList[$story->product][$story->branch])) $productStoryList[$story->product][$story->branch] = $this->story->getProductStoryPairs($story->product, $branch, 0, 'all', 'id_desc', 0, '', $story->type);
            }

            if(!empty($story->plan))
            {
                foreach(explode(',', $story->plan) as $planID)
                {
                    if(empty($planID)) continue;
                    if(!isset($plans[$story->product][$story->branch][$planID]))
                    {
                        $plan = $this->dao->select('id,title,begin,end')->from(TABLE_PRODUCTPLAN)->where('id')->eq($planID)->fetch();
                        $plans[$story->product][$story->branch][$planID] = $plan->title . ' [' . $plan->begin . '~' . $plan->end . ']';
                    }
                }
            }
        }

        $urStageOptions = array();
        foreach($this->lang->requirement->stageList as $stageKey => $stageLang) $urStageOptions[] = array('text' => $stageLang, 'value' => $stageKey);

        $this->view->users            = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->branchTagOption  = $branchTagOption;
        $this->view->moduleList       = $moduleList;
        $this->view->productStoryList = $productStoryList;
        $this->view->plans            = $plans;
        $this->view->branchProduct    = $branchProduct;
        $this->view->urStageOptions   = $urStageOptions;
    }

    /**
     * Get form options for single product.
     *
     * @param  int     $productID
     * @param  int     $executionID
     * @param  object  $product
     * @access private
     * @return array
     */
    private function getFormOptionsForSingleProduct(int $productID, int $executionID, object $product): array
    {
        $this->loadModel('branch');
        $this->loadModel('productplan');
        $this->loadModel('tree');

        $branchProduct   = $product->type == 'normal' ? false : true;
        $branches        = array(0);
        $branchTagOption = array();
        if($branchProduct)
        {
            $branches = $this->branch->getList($productID, $executionID, 'all');
            foreach($branches as $branchInfo) $branchTagOption['branch' . $branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            $branches = array_keys($branches);
        }

        $modulePairs = $this->tree->getOptionMenu($productID, 'story', 0, $branches);

        $modules         = array($productID => $modulePairs);
        $branchTagOption = array($productID => $branchTagOption);
        $products        = array($productID => $product);
        $plans           = array($productID => $this->productplan->getBranchPlanPairs($productID, $branches, '', true));

        return array('branchProduct' => $branchProduct, 'modules' => $modules, 'branchTagOption' => $branchTagOption, 'products' => $products, 'plans' => $plans);

    }

    /**
     * Get stories by post checked id list.
     *
     * @access protected
     * @return array|false
     */
    protected function getStoriesByChecked(): array|bool
    {
        $storyIdList = $this->post->storyIdList;
        if(empty($storyIdList)) return false;

        /* Get edited stories. */
        $storyIdList = array_unique($storyIdList);
        foreach($storyIdList as $index => $storyID)
        {
            /* 处理选中的子需求的ID，截取-后的子需求ID。*/
            /* Process selected child story ID. */
            if(strpos((string)$storyID, '-') !== false) $storyIdList[$index] = substr($storyID, strpos($storyID, '-') + 1);
        }
        $stories = $this->story->getByList($storyIdList);
        if(empty($stories)) return false;

        /* Filter twins. */
        $twins = '';
        foreach($stories as $id => $story)
        {
            if(empty($story->twins)) continue;
            $twins .= "#$id ";
            unset($stories[$id]);
        }
        if(!empty($twins)) $this->view->twinsTip = sprintf($this->lang->story->batchEditTip, $twins);

        return $stories;
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
        $optionMenu = $this->tree->getOptionMenu($productID, 'story', 0, $branch === 'all' ? '0' : (string)$branch, 'nodeleted');

        $moduleID = $moduleID ? $moduleID : (int)$this->cookie->lastStoryModule;
        $moduleID = $moduleID ? $moduleID : $fields['module']['default'];
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
        $objectID = $this->view->objectID;

        /* Hidden some fields of projects without products. */
        $hiddenProduct = $hiddenParent = $hiddenPlan = false;
        $teamUsers     = array();

        if($this->app->tab === 'project' || $this->app->tab === 'execution')
        {
            $project = $this->dao->findById((int)$objectID)->from(TABLE_PROJECT)->fetch();
            if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

            if(empty($project->hasProduct))
            {
                $teamUsers     = $this->project->getTeamMemberPairs($project->id);
                $hiddenProduct = true;

                if($project->model !== 'scrum' or !$project->multiple) $hiddenPlan = true;
            }
        }
        if($storyType != 'story') unset($fields['branches'], $fields['modules'], $fields['plans']);
        if($hiddenPlan) unset($fields['plan']);
        if($hiddenProduct)
        {
            $fields['product']['control']    = 'hidden';
            $fields['reviewer']['options']   = $teamUsers;
            $fields['assignedTo']['options'] = $teamUsers;
        }

        $hiddenGrade = empty($this->config->showStoryGrade);
        if($hiddenGrade && $storyType == 'epic') $hiddenParent = true;

        $this->view->hiddenParent = $hiddenParent;
        $this->view->hiddenGrade  = $hiddenGrade;
        return $fields;
    }

    /**
     * 隐藏不显示的字段。
     * Hide form fields for edi.
     *
     * @param  array     $fields
     * @param  string    $storyType
     * @access protected
     * @return array
     */
    protected function hiddenFormFieldsForEdit(array $fields, string $storyType): array
    {
        $product = $this->view->product;

        $hiddenProduct = $hiddenPlan = false;
        $teamUsers     = array();
        if($product->shadow)
        {
            $this->loadModel('project');
            $project       = $this->project->getByShadowProduct($product->id);
            $teamUsers     = $this->project->getTeamMemberPairs($project->id);
            $hiddenProduct = true;

            if($project->model !== 'scrum') $hiddenPlan = true;
            if(!$project->multiple)
            {
                $hiddenPlan = true;
                unset($this->lang->story->stageList[''], $this->lang->story->stageList['wait'], $this->lang->story->stageList['planned']);
            }
        }
        if($hiddenPlan) $fields['plan']['className'] = 'hidden';

        if($hiddenProduct)
        {
            $fields['product']['className']  = 'hidden';
            $fields['reviewer']['options']   = $teamUsers;
            $fields['assignedTo']['options'] = $teamUsers;
        }
        if(empty($this->config->showStoryGrade) && $storyType == 'epic') $fields['parent']['className'] = 'hidden';

        return $fields;
    }

    /**
     * 根据配置，删除非必要的表单字段配置。
     * Remove form fields for batch create.
     *
     * @param  array      $fields
     * @param  bool       $hiddenPlan
     * @param  string     $executionType
     * @param  int        $executionID
     * @access protected
     * @return array
     */
    protected function removeFormFieldsForBatchCreate(array $fields, bool $hiddenPlan, string $executionType, int $executionID = 0): array
    {
        if($hiddenPlan) unset($fields['plan']);

        if($executionType != 'kanban')
        {
            unset($fields['region']);
            unset($fields['lane']);
        }

        if($this->app->tab == 'project' || $this->app->tab == 'execution')
        {
            $fields['parent']['hidden'] = true;

            $project = $this->dao->findById((int)$executionID)->from(TABLE_PROJECT)->fetch();
            if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

            if(empty($project->hasProduct))
            {
                $teamUsers = $this->project->getTeamMemberPairs($project->id);
                $fields['reviewer']['options']   = $teamUsers;
                $fields['assignedTo']['options'] = $teamUsers;
            }
        }

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
        return (int)$this->dao->select('id')->from(TABLE_BLOCK)->where('module')->eq('assigntome')
            ->andWhere('module')->eq('my')
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('order_desc')
            ->limit(1)
            ->fetch('id');
    }

    /**
     * 构建创建需求数据。
     * Build story for create
     *
     * @param  int          $executionID
     * @param  int          $bugID
     * @param  string       $storyType
     * @access protected
     * @return object|false
     */
    protected function buildStoryForCreate(int $executionID, int $bugID, string $storyType = 'story'): object|false
    {
        $fields = $this->config->story->form->create;
        foreach(explode(',', trim($this->config->{$storyType}->create->requiredFields, ',')) as $field) $fields[$field]['required'] = true;
        if(!isset($_POST['plan'])) $this->config->{$storyType}->create->requiredFields = str_replace(',plan,', ',', ",{$this->config->story->create->requiredFields},");
        if(!empty($_POST['modules']) && !empty($fields['module']['required']))
        {
            /* Check empty module in the product with multi-branches. */
            $fields['module']['required'] = false;
            $this->config->{$storyType}->create->requiredFields = str_replace(',module,', ',', ",{$this->config->{$storyType}->create->requiredFields},");
            foreach($_POST['modules'] as $key => $moduleID)
            {
                if(empty($moduleID)) dao::$errors["modules[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->story->module);
            }
        }

        if(!empty($_POST['estimate']) && $_POST['estimate'] < 0) dao::$errors['estimate'] = sprintf($this->lang->story->errorRecordMinus, $this->lang->story->estimateAB);

        if(dao::isError()) return false;

        $storyData = form::data($fields)
            ->setIF($this->post->assignedTo, 'assignedDate', helper::now())
            ->setIF($this->post->plan > 0 && $storyType == 'story', 'stage', 'planned')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF($executionID > 0, 'stage', 'projected')
            ->setIF($bugID > 0, 'fromBug', $bugID)
            ->setIF(!$this->post->estimate, 'estimate', 0)
            ->get();

        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors['reviewer'] = sprintf($this->lang->error->notempty, $this->lang->story->reviewer);
            return false;
        }

        /* Need and force review, then set status to reviewing. */
        if($storyData->status != 'draft' and $this->story->checkForceReview($storyType) and !$this->post->needNotReview) $storyData->status = 'reviewing';

        return $this->loadModel('file')->processImgURL($storyData, $this->config->story->editor->create['id'], $this->post->uid);
    }

    /**
     * 构建编辑需求数据。
     * Build story for edit
     *
     * @param  int       $storyID
     * @access protected
     * @return object|false
     */
    protected function buildStoryForEdit(int $storyID): object|false
    {
        $storyPlan = array();
        $oldStory  = $this->story->getByID($storyID);
        if(!$oldStory) return false;

        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate) dao::$errors[] = $this->lang->error->editedByOther;
        if(strpos('draft,changing', $oldStory->status) !== false and $this->story->checkForceReview($oldStory->type) and empty($_POST['reviewer'])) dao::$errors[] = $this->lang->story->notice->reviewerNotEmpty;
        if(!empty($_POST['plan'])) $storyPlan = is_array($_POST['plan']) ? array_filter($_POST['plan']) : array($_POST['plan']);
        if(count($storyPlan) > 1 && $oldStory->type == 'story')
        {
            $oldStoryPlan  = !empty($oldStory->plan) ? array_filter(explode(',', $oldStory->plan)) : array();
            $oldPlanDiff   = array_diff($storyPlan, $oldStoryPlan);
            $storyPlanDiff = array_diff($oldStoryPlan, $storyPlan);
            if(!empty($oldPlanDiff) or !empty($storyPlanDiff)) dao::$errors['plan'] = $this->lang->story->notice->changePlan;
        }
        if(strpos(',draft,changing,', $oldStory->status) !== false)
        {
            if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
            if(!$this->post->needNotReview and empty($_POST['reviewer'])) dao::$errors['reviewer'] = $this->lang->story->errorEmptyReviewedBy;
        }

        if(!empty($_POST['estimate']) && $_POST['estimate'] < 0) dao::$errors['estimate'] = sprintf($this->lang->story->errorRecordMinus, $this->lang->story->estimateAB);

        if(dao::isError()) return false;

        $hasProduct = $this->dao->select('t2.hasProduct')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($oldStory->product)
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('hasProduct');
        $_POST['product'] = (!empty($hasProduct) && !$hasProduct) ? $oldStory->product : $this->post->product;

        $now       = helper::now();
        $fields    = $this->config->story->form->edit;
        $storyData = form::data($fields, $storyID)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('demand', $oldStory->demand)
            ->setDefault('deleteFiles', array())
            ->setDefault('reviewedBy', $oldStory->reviewedBy)
            ->setDefault('renameFiles', array())
            ->setDefault('product', $oldStory->product)
            ->setDefault('branch', $oldStory->branch)
            ->setDefault('stage', $oldStory->stage)
            ->setDefault('stagedBy', $oldStory->stagedBy)
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     && $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason && $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     || $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason && $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->setIF($this->post->stage == 'released', 'releasedDate', $now)
            ->setIF(!$this->post->grade, 'grade', $oldStory->grade)
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF(!empty($_POST['plan'][0]) and $oldStory->stage == 'wait', 'stage', 'planned')
            ->setIF(!isset($_POST['title']), 'title', $oldStory->title)
            ->setIF(!isset($_POST['spec']), 'spec', $oldStory->spec)
            ->setIF(!isset($_POST['verify']), 'verify', $oldStory->verify)
            ->setIF(!isset($_POST['estimate']), 'estimate', $oldStory->estimate)
            ->get();

        if($this->post->linkStories)      $storyData->linkStories      = implode(',', array_unique($this->post->linkStories));
        if($this->post->linkRequirements) $storyData->linkRequirements = implode(',', array_unique($this->post->linkRequirements));

        if($oldStory->product != $storyData->product) $storyData->root = $storyID;

        return $this->loadModel('file')->processImgURL($storyData, $this->config->story->editor->edit['id'], $this->post->uid);
    }

    /**
     * 构建变更需求数据。
     * Build story for change
     *
     * @param  int       $storyID
     * @access protected
     * @return object|false
     */
    protected function buildStoryForChange(int $storyID): object|false
    {
        $oldStory   = $this->story->getByID($storyID);
        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate) dao::$errors[] = $this->lang->error->editedByOther;
        if(strpos($this->config->{$oldStory->type}->change->requiredFields, 'spec') !== false and !$this->post->spec)       dao::$errors['spec'][]    = sprintf($this->lang->error->notempty, $this->lang->story->spec);
        if(strpos($this->config->{$oldStory->type}->change->requiredFields, 'verify') !== false and !$this->post->spec)     dao::$errors['verify'][]  = sprintf($this->lang->error->notempty, $this->lang->story->verify);
        if(strpos($this->config->{$oldStory->type}->change->requiredFields, 'comment') !== false and !$this->post->comment) dao::$errors['comment']   = sprintf($this->lang->error->notempty, $this->lang->comment);

        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer'])) dao::$errors['reviewer'] = $this->lang->story->errorEmptyReviewedBy;
        if(dao::isError()) return false;

        $now    = helper::now();
        $fields = $this->config->story->form->change;
        $story  = form::data($fields, $storyID)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('deleteFiles', array())
            ->setDefault('lastEditedDate', $now)
            ->setDefault('version', $oldStory->version)
            ->get();

        $specChanged        = false;
        $oldStoryReviewers  = array_keys($this->story->getReviewerPairs($storyID, $oldStory->version));
        $_POST['reviewer']  = isset($_POST['reviewer']) ? $_POST['reviewer'] : array();
        $reviewerHasChanged = (array_diff($oldStoryReviewers, $_POST['reviewer']) || array_diff($_POST['reviewer'], $oldStoryReviewers));
        if($story->spec != $oldStory->spec or $story->verify != $oldStory->verify or $story->title != $oldStory->title or $this->loadModel('file')->getCount() or $reviewerHasChanged or !empty($story->deleteFiles)) $specChanged = true;

        $story->reviewerHasChanged = $reviewerHasChanged;
        if($specChanged)
        {
            $story->version      = (int)$oldStory->version + 1;
            $story->reviewedBy   = '';
            $story->changedBy    = $this->app->user->account;
            $story->changedDate  = $now;
            $story->closedBy     = '';
            $story->closedReason = '';
            if($oldStory->reviewedBy) $story->reviewedDate = null;
            if($oldStory->closedBy)   $story->closedDate   = null;
        }
        else
        {
            $story->status = $oldStory->status;
        }

        if(!isset($_POST['relievedTwins'])) unset($story->relievedTwins);
        return $this->loadModel('file')->processImgURL($story, $this->config->story->editor->change['id'], $this->post->uid);
    }

    /**
     * 构建需求转化任务的数据。
     * Build data for batchToTask.
     *
     * @param  int       $executionID
     * @param  int       $projectID
     * @access protected
     * @return array|false
     */
    protected function buildDataForBatchToTask(int $executionID, int $projectID = 0): array|false
    {
        $this->loadModel('task');
        $now    = helper::now();
        $fields = $this->config->story->form->batchToTask;
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";
        foreach(explode(',', trim($requiredFields, ',')) as $field)
        {
            if(isset($fields[$field])) $fields[$field]['required'] = true;
        }

        $syncFields = zget($_POST, 'syncFields', '');
        $stories    = array();
        if(!empty($syncFields)) $stories = empty($_POST['story']) ? array() : $this->story->getByList($_POST['story']);

        $this->lang->story->estimate   = $this->lang->task->estimateAB;
        $this->lang->story->deadline   = $this->lang->task->deadline;
        $this->lang->story->estStarted = $this->lang->task->estStarted;

        $tasks     = form::batchData($fields)->get();
        $taskNames = array();
        foreach($tasks as $task)
        {
            $task->project   = $projectID;
            $task->execution = $executionID;
            $task->left      = $task->estimate;
            if($task->assignedTo) $task->assignedDate = $now;
            if($task->story)
            {
                $story = zget($stories, $task->story, null);
                if($story)
                {
                    $task->storyVersion = $story->version;
                    if(str_contains(",{$syncFields},", ',spec,'))   $task->desc   = $story->spec;
                    if(str_contains(",{$syncFields},", ',mailto,')) $task->mailto = $story->mailto;
                }
            }

            if(in_array($task->name, $taskNames)) dao::$errors['message'][] = sprintf($this->lang->duplicate, $this->lang->task->common) . ' ' . $task->name;
            if(!helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted) dao::$errors['message'][] = $this->lang->task->error->deadlineSmall;
            if($task->estimate and !preg_match("/^[0-9]+(.[0-9]+)?$/", (string)$task->estimate)) dao::$errors['message'][] = $this->lang->task->error->estimateNumber;
            if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($executionID, (string)$task->estStarted, (string)$task->deadline);

            $taskNames[] = $task->name;
        }
        if(dao::isError()) return false;

        return $tasks;
    }

    /**
     * 处理编辑需求数据。
     * Process data for edit.
     *
     * @param  int       $storyID
     * @param  object    $story
     * @access protected
     * @return void
     */
    protected function processDataForEdit(int $storyID, object $story): void
    {
        $oldStory = $this->story->fetchByID($storyID);

        if($oldStory->type == 'story' and !isset($story->linkStories)) $story->linkStories = '';
        if($oldStory->type == 'requirement' and !isset($story->linkRequirements)) $story->linkRequirements = '';
        if($oldStory->status == 'changing' and $story->status == 'draft') $story->status = 'changing';

        if(isset($_POST['plan']) and is_array($_POST['plan'])) $story->plan   = trim(implode(',', $_POST['plan']), ',');
        if(isset($_POST['branch']) and $_POST['branch'] == 0)  $story->branch = 0;
        if(isset($story->stage) and $oldStory->stage != $story->stage) $story->stagedBy = (strpos('tested|verified|rejected|pending|released|closed', $story->stage) !== false) ? $this->app->user->account : '';
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
        $oldStory   = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now        = helper::now();
        $fields     = $this->config->story->form->review;
        foreach(explode(',', trim($this->config->{$oldStory->type}->review->requiredFields, ',')) as $field)
        {
            if($field == 'comment' && !$this->post->comment)
            {
                dao::$errors['comment'][] = sprintf($this->lang->error->notempty, $this->lang->comment);
                return false;
            }
            if($field == 'reviewedDate' && !$this->post->reviewedDate)
            {
                dao::$errors['reviewedDate'] = sprintf($this->lang->error->notempty, $this->lang->story->reviewedDate);
                return false;
            }
            if(isset($fields[$field])) $fields[$field]['required'] = true;
        }
        if($this->post->result == false)
        {
            dao::$errors['result'][] = $this->lang->story->mustChooseResult;
            return false;
        }

        $result       = $this->post->result;
        $closedReason = $this->post->closedReason;
        $storyData    = form::data($fields, $storyID)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->removeIF($result != 'reject', 'closedReason,duplicateStory')
            ->removeIF($result == 'reject' && $closedReason != 'duplicate', 'duplicateStory')
            ->get();

        if($oldStory->assignedTo != $storyData->assignedTo) $storyData->assignedDate = $now;
        $storyData->reviewedBy = implode(',', array_unique(explode(',', $oldStory->reviewedBy . ',' . $this->app->user->account)));

        if($result == 'reject' && empty($closedReason)) dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->story->rejectedReason);
        if($result == 'reject' && $closedReason == 'duplicate' && empty($storyData->duplicateStory)) dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->story->duplicateStory);
        if(!empty($storyData->estimate) && $storyData->estimate < 0) dao::$errors['estimate'] = sprintf($this->lang->story->errorRecordMinus, $this->lang->story->estimate);
        if(dao::isError()) return false;

        return $this->loadModel('file')->processImgURL($storyData, $this->config->story->editor->review['id'], $this->post->uid);
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
        $forceReview = $this->story->checkForceReview($storyType);
        $fields      = $this->config->story->form->batchCreate;
        $account     = $this->app->user->account;
        $now         = helper::now();
        $saveDraft   = $this->post->status == 'draft';
        if($forceReview) $fields['reviewer']['required'] = true;

        $stories = form::batchData($fields)->get();
        foreach($stories as $i => $story)
        {
            if(!empty($story->title) && mb_strlen($story->title) > 255)
            {
                dao::$errors["title[$i]"] = sprintf($this->lang->story->error->length, 255);
            }

            if(!empty($story->estimate) && $story->estimate < 0) dao::$errors["estimate[$i]"] = sprintf($this->lang->story->errorRecordMinus, $this->lang->story->estimate);

            $story->type       = $storyType;
            $story->status     = (empty($story->reviewer) && !$forceReview) ? 'active' : 'reviewing';
            $story->status     = $saveDraft ? 'draft' : $story->status;
            $story->product    = $productID;
            $story->openedBy   = $account;
            $story->vision     = $this->config->vision;
            $story->openedDate = $now;
            $story->version    = 1;

            if(in_array($this->app->tab, array('project', 'execution')))
            {
                $story->stage = 'projected';
            }

            !empty($story->assignedTo) && $story->assignedDate = $now;
            if($this->post->uploadImage && $this->post->uploadImage[$i]) $story->uploadImage = $this->post->uploadImage[$i];
        }

        return $stories;
    }

    /**
     * 构建批量编辑需求数据。
     * Build stories for batch edit page.
     *
     * @access protected
     * @return array
     */
    protected function buildStoriesForBatchEdit(): array
    {
        $fields  = $this->config->story->form->batchEdit;
        $account = $this->app->user->account;
        $now     = helper::now();

        $fields['duplicateStory'] = array('type' => 'int', 'required' => false, 'default' => 0);

        $stories    = form::batchData($fields)->get();
        $oldStories = $this->story->getByList(array_keys($stories));
        if($this->config->edition == 'ipd') $roadmaps = $this->loadModel('roadmap')->getList();
        foreach($stories as $storyID => $story)
        {
            $oldStory = $oldStories[$storyID];
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->stage          = empty($story->stage) ? $oldStory->stage : $story->stage;
            $story->status         = empty($story->status) ? $oldStory->status : $story->status;
            $story->branch         = $story->branch === '' ? $oldStory->branch : str_replace('branch', '', $story->branch);

            if(empty($story->roadmap)) $story->roadmap = $oldStory->roadmap;
            if($oldStory->assignedTo != $story->assignedTo) $story->assignedDate = $now;
            if($oldStory->parent < 0) $story->plan = '';
            if($story->stage != $oldStory->stage) $story->stagedBy = (str_contains('|tested|verified|rejected|pending|released|closed|', "|{$story->stage}|")) ? $account : '';

            if($story->closedBy     && helper::isZeroDate($oldStory->closedDate)) $story->closedDate = $now;
            if($story->closedReason && helper::isZeroDate($oldStory->closedDate)) $story->closedDate = $now;
            if($story->closedBy     || $story->closedReason)    $story->status   = 'closed';
            if($story->closedReason && empty($story->closedBy)) $story->closedBy = $account;

            if($story->closedBy && empty($story->closedReason)) dao::$errors['closedReason'] = sprintf($this->lang->error->notempty, $this->lang->story->closedReason);
            if($story->closedReason == 'done' && empty($story->stage)) dao::$errors['stage'] = sprintf($this->lang->error->notempty, $this->lang->story->stage);
            if($story->closedReason == 'duplicate' && empty($story->duplicateStory)) dao::$errors['duplicateStory'] = sprintf($this->lang->error->notempty, $this->lang->story->duplicateStory);
            if($this->config->vision == 'or' && $this->config->edition == 'ipd')
            {
                if($story->stage == 'wait' && !empty($story->roadmap) && isset($roadmaps[$story->roadmap])) $story->stage = $roadmaps[$story->roadmap]->status == 'launched' ? 'incharter' : 'inroadmap';
                if(empty($story->roadmap)) $story->stage = 'wait';
            }

            if(!empty($story->estimate) && $story->estimate < 0) dao::$errors["estimate[{$storyID}]"] = sprintf($this->lang->story->errorRecordMinus, $this->lang->story->estimate);
        }

        return $stories;
    }

    /**
     * 构建批量关闭需求数据，跳过有子需求的父需求以及已经关闭了的需求。
     * Build stories for batch close.
     *
     * @access protected
     * @return array
     */
    protected function buildStoriesForBatchClose(): array
    {
        $account    = $this->app->user->account;
        $now        = helper::now();
        $data       = form::batchData()->get();
        $oldStories = $this->story->getByList(array_keys($data));
        $stories    = array();
        foreach($data as $storyID => $story)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->parent == -1) continue;       /* Skip the story which has any child story. */
            if($oldStory->status == 'closed') continue; /* Skip the story which has been closed. */

            $story->lastEditedBy   = $account;
            $story->lastEditedDate = $now;
            $story->closedBy       = $account;
            $story->closedDate     = $now;
            $story->assignedTo     = 'closed';
            $story->assignedDate   = $now;
            $story->status         = 'closed';
            $story->stage          = 'closed';

            if($story->closedReason != 'done') $story->plan  = '';
            if($story->closedReason == 'duplicate' && empty($story->duplicateStory)) dao::$errors["duplicateStory[{$storyID}]"] = sprintf($this->lang->error->notempty, $this->lang->story->duplicateStory);

            $stories[$storyID] = $story;
        }

        return $stories;
    }

    /**
     * 如果是在弹窗中打开页面，获取跳转地址。
     * Get response when open in modal.
     *
     * @param  string    $message
     * @access protected
     * @return array|false
     */
    protected function getResponseInModal(string $message = ''): array|false
    {
        if(!isInModal()) return false;

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

    /**
     * 获取编辑需求后的跳转地址。
     * Get location when after edit a story.
     *
     * @param  int       $storyID
     * @param  string    $storyType
     * @access protected
     * @return string
     */
    protected function getAfterEditLocation(int $storyID, string $storyType)
    {
        $module = $storyType;
        $params = "storyID=$storyID&version=0&param=0&storyType=$storyType";
        $method = 'view';
        if($this->app->tab == 'project')
        {
            $project = $this->loadModel('project')->getByID($this->session->project);
            if(empty($project->multiple))
            {
                $module = 'execution';
                $method = 'storyView';
                $params = "storyID=$storyID&project={$this->session->project}";
            }
            else
            {
                $module = 'projectstory';
                $params = "storyID=$storyID&project={$this->session->project}";
            }
        }
        return $this->createLink($module, $method, $params);
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
     * @param  string    $extra feedback扩展使用
     * @access public
     * @return string
     */
    public function getAfterCreateLocation(int $productID, string $branch, int $objectID, int $storyID, string $storyType, string $extra = ''): string
    {
        if($this->app->getViewType() == 'xhtml') return $this->createLink('story', 'view', "storyID=$storyID", 'html');

        if($objectID)
        {
            helper::setcookie('storyModuleParam', '0', 0);
            $object = $this->loadModel('project')->fetchByID($objectID);
            if(empty($_SESSION['storyList'])) return $this->createLink($this->app->tab == 'project' && $object->type == 'project' ? 'projectstory' : 'execution', 'story', "objectID=$objectID");
            return $this->session->storyList;
        }

        helper::setcookie('storyModule', '0', 0);
        $branchID = $this->post->branch  ? $this->post->branch  : $branch;
        if(empty($_SESSION['storyList'])) return $this->createLink('product', 'browse', "productID=$productID&branch=$branchID&browseType=&param=0&storyType=$storyType&orderBy=id_desc");
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
            $locateLink = $this->session->storyList ? $this->session->storyList : $this->createLink('projectstory', 'view', "storyID=$storyID&projectID={$this->app->project}");
            if($this->app->tab == 'product')
            {
                $story = $this->story->fetchById($storyID);
                $locateLink = $this->createLink($story->type, 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType");
            }
            return $locateLink;
        }

        if($executionID)
        {
            helper::setcookie('storyModuleParam', '0', 0);
            return $this->session->storyList;
        }

        if($this->app->tab == 'product')
        {
            return $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=unclosed&queryID=0&storyType=$storyType");
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
        if($this->app->tab != 'project') return helper::createLink($storyType, 'view', "storyID=$storyID&version=0&param=0&storyType=$storyType");

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
            return helper::createLink($module, $method, $params);
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
        if($from == 'project')
        {
            $project = $this->project->getByID($this->session->project);
            if($project && !$project->multiple) return helper::createLink('execution', 'storyView', "storyID=$storyID");
            return helper::createLink('projectstory', 'view', "storyID={$storyID}&projectID={$this->session->project}");
        }

        if($from != 'execution') return helper::createLink($storyType, 'view', "storyID={$storyID}&version=0&param=0&storyType={$storyType}");

        $module = 'execution';
        $method = 'storyView';
        $params = "storyID=$storyID";
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

        $defaultStory = array('title' => '', 'spec' => '', 'module' => $moduleID, 'plan' => $planID, 'pri' => (string)$this->config->story->defaultPriority, 'estimate' => '', 'branch' => $this->view->branchID);
        $batchStories = array();
        $count        = $this->config->story->batchCreate;
        for($batchIndex = 0; $batchIndex < $count; $batchIndex++) $batchStories[] = $defaultStory;

        if(empty($_SESSION['storyImagesFile'])) return $batchStories;

        $files        = $this->session->storyImagesFile;
        $batchStories = array();
        foreach($files as $fileName => $file)
        {
            $defaultStory['title']       = $file['title'];
            $defaultStory['uploadImage'] = $fileName;

            $batchStories[] = $defaultStory;
        }
        return $batchStories;
    }

    /**
     * Get custom fields list.
     *
     * @param  object    $config
     * @param  string    $storyType
     * @param  bool      $hiddenPlan
     * @param  object    $product
     * @access protected
     * @return array
     */
    protected function getCustomFields(object &$config, string $storyType, bool $hiddenPlan, object $product): array
    {
        $customFields = array();

        /* Attach multi-branch or multi-platform field. */
        if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];

        foreach(explode(',', $config->story->list->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->story->$field;

        if($hiddenPlan) unset($customFields['plan']);
        if($this->app->tab == 'project' || $this->app->tab == 'execution') unset($customFields['parent']);

        if($product->type != 'normal')
        {
            $config->{$storyType}->custom->batchCreateFields = sprintf($config->story->custom->batchCreateFields, $product->type);
        }
        else
        {
            $config->{$storyType}->custom->batchCreateFields = trim(sprintf($config->story->custom->batchCreateFields, ''), ',');
        }

        return $customFields;
    }

    /**
     * Get show fields list.
     *
     * @param  string    $fieldListStr
     * @param  bool      $hiddenPlan
     * @param  object    $product
     * @access protected
     * @return array
     */
    protected function getShowFields(string $fieldListStr, string $storyType, object $product): string
    {
        $showFields = $fieldListStr;

        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        return $showFields;
    }

    /**
     * Build story post data for activating the story.
     *
     * @param  int       $storyID
     * @access protected
     * @return object
     */
    protected function buildStoryForActivate(int $storyID): object
    {
        $postData = form::data($this->config->story->form->activate, $storyID)->get();
        $story    = $this->loadModel('file')->processImgURL($postData, $this->config->story->editor->activate['id'], $this->post->uid);
        return $story;
    }

    /**
     * Build story post data for submitReview the story.
     *
     * @param  int       $storyID
     * @access protected
     * @return object|false
     */
    protected function buildStoryForSubmitReview(int $storyID): object|false
    {
        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors['reviewer'] = $this->lang->story->errorEmptyReviewedBy;
            return false;
        }

        return form::data($this->config->story->form->submitReview, $storyID)->get();
    }

    /**
     * Get linked objects. e.g. bugs,cases,linkedMRs,linkedCommits,twins,reviewers,relations.
     *
     * @param  object    $story
     * @access protected
     * @return void
     */
    protected function getLinkedObjects(object $story)
    {
        $linkedStories = isset($story->linkStoryTitles) ? array_keys($story->linkStoryTitles) : array();

        $this->view->bugs          = $this->dao->select('id,title,status,pri,severity')->from(TABLE_BUG)->where('story')->eq($story->id)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->fromBug       = $story->fromBug ? $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->eq($story->fromBug)->fetch() : '';
        $this->view->cases         = $this->dao->select('id,title,status,pri')->from(TABLE_CASE)->where('story')->eq($story->id)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->linkedMRs     = $this->loadModel('mr')->getLinkedMRPairs($story->id, 'story');
        $this->view->linkedCommits = $this->loadModel('repo')->getCommitsByObject($story->id, 'story');
        $this->view->modulePath    = $this->tree->getParents($story->module);
        $this->view->storyModule   = empty($story->module) ? '' : $this->tree->getById($story->module);
        $this->view->storyProducts = $this->dao->select('id,product')->from(TABLE_STORY)->where('id')->in($linkedStories)->fetchPairs();
        $this->view->twins         = !empty($story->twins) ? $this->story->getByList($story->twins) : array();
        $this->view->reviewers     = $this->story->getReviewerPairs($story->id, $story->version);
        $this->view->relations     = $this->story->getStoryRelation($story->id, $story->type);
    }

    /**
     * Set hidden fields for view. like: hiddenPlan.
     *
     * @param  object    $product
     * @access protected
     * @return void
     */
    protected function setHiddenFieldsForView(object $product)
    {
        $this->view->hiddenPlan = false;
        if(empty($product->shadow)) return;

        $projectInfo = $this->dao->select('t2.model, t2.multiple')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($product->id)
            ->andWhere('t2.type')->eq('project')
            ->fetch();

        if($projectInfo->model == 'waterfall') $this->view->hiddenPlan = true;
        if($projectInfo->model == 'kanban')    $this->view->hiddenPlan = true;
        if(!$projectInfo->multiple)            $this->view->hiddenPlan = true;
    }

    /**
     * Convert child ID.
     *
     * @param  array    $storyIdList
     * @access protected
     * @return array
     */
    protected function convertChildID(array $storyIdList)
    {
        $storyIdList = array_unique($storyIdList);
        $storyIdList = array_filter($storyIdList);
        foreach($storyIdList as $index => $storyID)
        {
            /* 处理选中的子需求的ID，截取-后的子需求ID。*/
            /* Process selected child story ID. */
            if(strpos((string)$storyID, '-') !== false) $storyIdList[$index] = substr($storyID, strpos($storyID, '-') + 1);
        }
        return $storyIdList;
    }

    /**
     * 处理过滤条件显示内容。
     * Process filter title.
     *
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return string
     */
    protected function processFilterTitle(string $browseType, int $param): string
    {
        $filter       = '';
        $fieldsType   = 'execution';
        $searchConfig = $this->session->executionStorysearchParams;
        $searchFields = $this->session->executionStoryForm;
        $featureBar   = $this->lang->execution->featureBar['story'];
        if($this->app->tab == 'project' && $this->session->multiple)
        {
            $this->app->loadLang('projectstory');
            $this->app->loadConfig('product');
            $fieldsType   = 'product';
            $searchConfig = $this->session->projectstorysearchParams;
            $searchFields = $this->session->projectstoryForm;
            $featureBar   = array_merge($this->lang->projectstory->featureBar['story'], $this->lang->projectstory->moreSelects['story']['more']);
        }

        $fieldParams = array();
        if($searchConfig && !empty($searchConfig['params'])) $fieldParams = $searchConfig['params'];
        if($browseType != 'bysearch' && $browseType != 'bymodule' && $browseType != 'byproduct')
        {
            $statusName = zget($featureBar, $browseType, '');
            $filter     = sprintf($this->lang->story->report->tpl->feature, $statusName);
            if(!$param) return $filter;

            return $filter . zget($this->lang->search->andor, 'and') . sprintf($this->lang->story->report->tpl->search, $this->config->$fieldsType->search['fields']['module'], '=', zget($fieldParams['module']['values'], $param));
        }

        if($browseType == 'byproduct' && $param)
        {
            $productName = $this->loadModel('product')->getById($param)->name;
            return sprintf($this->lang->story->report->tpl->feature, $productName);
        }

        if($browseType == 'bymodule' && $param)
        {
            if($filter) $filter .= ', ';
            return $filter . sprintf($this->lang->story->report->tpl->search, $this->config->$fieldsType->search['fields']['module'], '=', zget($fieldParams['module']['values'], $param));
        }

        $leftConditions  = array();
        $rightConditions = array();
        $fieldNames      = json_decode($searchConfig['searchFields']);
        if(!$searchFields) return '';

        $this->app->loadLang('search');
        $groupAndOr = 'and';
        $users      = $this->loadModel('user')->getPairs('noletter');
        foreach($searchFields as $index => $field)
        {
            if($index == 6) $groupAndOr = $field['groupAndOr'];
            if(!isset($field['field'])) continue;

            if(isset($field['value']) && $field['value'] === '') continue;
            if(!empty($fieldParams[$field['field']]['values']))
            {
                if($fieldParams[$field['field']]['values'] == 'users') $fieldParams[$field['field']]['values'] = $users;
                $field['value'] = zget($fieldParams[$field['field']]['values'], $field['value']);
            }

            $fieldName = zget($fieldNames, $field['field']);
            $operator  = zget($this->lang->search->operators, $field['operator']);

            if($index == 0 || $index == 3) $field['andOr'] = '';
            if($index < 3)     $leftConditions[]  = zget($this->lang->search->andor, $field['andOr']) . sprintf($this->lang->story->report->tpl->search, $fieldName, $operator, $field['value']);
            elseif($index < 6) $rightConditions[] = zget($this->lang->search->andor, $field['andOr']) . sprintf($this->lang->story->report->tpl->search, $fieldName, $operator, $field['value']);
        }

        if(empty($leftConditions) && empty($rightConditions)) return '';
        if(empty($leftConditions))  return implode('', $rightConditions);
        if(empty($rightConditions)) return implode('', $leftConditions);

        return sprintf($this->lang->story->report->tpl->multi, implode('', $leftConditions), zget($this->lang->search->andor, $groupAndOr), implode('', $rightConditions));
    }
}
