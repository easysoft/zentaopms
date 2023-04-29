<?php
declare(strict_types=1);
/**
 * The zen file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectZen extends project
{
    /**
     * Append extras data to post data.
     *
     * @param  object $postData
     * @access protected
     * @return int|object
     */
    protected function prepareCreateExtras(object $postData): object
    {
        $rawdata = $postData->rawdata;
        $project = $postData->setDefault('status', 'wait')
            ->setIF($rawdata->delta == 999, 'end', LONG_TIME)
            ->setIF($rawdata->delta == 999, 'days', 0)
            ->setIF($rawdata->acl   == 'open', 'whitelist', '')
            ->setIF(!isset($rawdata->whitelist), 'whitelist', '')
            ->setIF(!isset($rawdata->multiple), 'multiple', '1')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', $rawdata->name)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('days', '0')
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->join('auth', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->get();

        if(!isset($this->config->setCode) or $this->config->setCode == 0) unset($project->code);

        /* Lean mode relation defaultProgram. */
        if($this->config->systemMode == 'light') $project->parent = $this->config->global->defaultProgram;

        if(!$this->checkProductAndBranch($project, $rawdata))  return false;
        if(!$this->checkDaysAndBudget($project, $rawdata))     return false;
        if(!$this->checkProductNameUnqiue($project, $rawdata)) return false;

        return $project;
    }

    /**
     * Check product and branch not empty.
     *
     * @param  object $project
     * @param  object $rawdata
     * @access protected
     * @return bool
     */
    private function checkProductAndBranch(object $project, object $rawdata): bool
    {
        $linkedProductsCount = $this->project->getLinkedProductsCount($project, $rawdata);

        if($rawdata->products)
        {
            $topProgramID     = $this->loadModel('program')->getTopByID($project->parent);
            $multipleProducts = $this->loadModel('product')->getMultiBranchPairs($topProgramID);
            foreach($rawdata->products as $index => $productID)
            {
                if(isset($multipleProducts[$productID]) and empty($rawdata->branch[$index]))
                {
                    dao::$errors[] = $this->lang->project->error->emptyBranch;
                    return false;
                }
            }
        }

        if($project->parent)
        {
            $program = $this->project->getByID((int)$project->parent);

            /* Judge products not empty. */
            if($project->hasProduct && empty($linkedProductsCount) and !isset($rawdata->newProduct))
            {
                dao::$errors['products0'] = $this->lang->project->error->productNotEmpty;
                return false;
            }
        }

        return true;
    }

    /**
     * Check days and budget by rules.
     *
     * @param  object $project
     * @param  object $rawdata
     * @access protected
     * @return bool
     */
    private function checkDaysAndBudget(object $project, object $rawdata): bool
    {
        /* Judge workdays is legitimate. */
        $workdays = helper::diffDate($project->end, $project->begin) + 1;
        if(isset($project->days) and $project->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        if(!empty($project->budget))
        {
            if(!is_numeric($project->budget))
            {
                dao::$errors['budget'] = sprintf($this->lang->project->error->budgetNumber);
                return false;
            }
            elseif(is_numeric($project->budget) and ($project->budget < 0))
            {
                dao::$errors['budget'] = sprintf($this->lang->project->error->budgetGe0);
                return false;
            }
            else
            {
                $project->budget = round((float)$rawdata->budget, 2);
            }
        }

        return true;
    }

    /**
     * Check product name unique and not empty.
     *
     * @param  object $project
     * @param  object $rawdata
     * @access protected
     * @return bool
     */
    private function checkProductNameUnqiue(object $project, object $rawdata): bool
    {
        /* When select create new product, product name cannot be empty and duplicate. */
        if($project->hasProduct && isset($rawdata->newProduct))
        {
            if(empty($rawdata->productName))
            {
                $this->app->loadLang('product');
                dao::$errors['productName'] = sprintf($this->lang->error->notempty, $this->lang->product->name);
                return false;
            }
            else
            {
                $programID        = isset($project->parent) ? $project->parent : 0;
                $existProductName = $this->dao->select('name')->from(TABLE_PRODUCT)->where('name')->eq($rawdata->productName)->andWhere('program')->eq($programID)->fetch('name');
                if(!empty($existProductName))
                {
                    dao::$errors['productName'] = $this->lang->project->error->existProductName;
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * Send variables to create page.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @access protected
     * @return void
     */
    protected function buildCreateForm(string $model, int $programID, int $copyProjectID, string $extra): void
    {
        $this->loadModel('product');
        $this->loadModel('program');

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if($this->app->tab == 'program' and $programID)                   $this->loadModel('program')->setMenu($programID);
        if($this->app->tab == 'product' and !empty($output['productID'])) $this->loadModel('product')->setMenu($output['productID']);
        if($this->app->tab == 'doc') unset($this->lang->doc->menu->project['subMenu']);

        if($copyProjectID) $copyProject = $this->getCopyProject((int)$copyProjectID);
        $shadow = empty($copyProject->hasProduct) ? 1 : 0;

        if($this->view->globalDisableProgram) $programID = $this->config->global->defaultProgram;
        $topProgramID = $this->program->getTopByID($programID);

        if($model == 'kanban')
        {
            $this->lang->project->aclList    = $this->lang->project->kanbanAclList;
            $this->lang->project->subAclList = $this->lang->project->kanbanSubAclList;
        }

        $withProgram   = $this->config->systemMode == 'ALM' ? true : false;
        $allProducts   = array('0' => '') + $this->program->getProductPairs($programID, 'all', 'noclosed', '', $shadow, $withProgram);
        $parentProgram = $this->loadModel('program')->getByID($programID);

        $this->view->title               = $this->lang->project->create;
        $this->view->gobackLink          = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('project', 'browse') : '';
        $this->view->model               = $model;
        $this->view->pmUsers             = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users               = $this->user->getPairs('noclosed|nodeleted');
        $this->view->programID           = $programID;
        $this->view->productID           = isset($output['productID']) ? $output['productID'] : 0;
        $this->view->branchID            = isset($output['branchID']) ? $output['branchID'] : 0;
        $this->view->allProducts         = $allProducts;
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs($topProgramID);
        $this->view->copyProjects        = $this->project->getPairsByModel($model);
        $this->view->copyProjectID       = $copyProjectID;
        $this->view->parentProgram       = $parentProgram;
        $this->view->programList         = $this->program->getParentPairs();
        $this->view->URSRPairs           = $this->loadModel('custom')->getURSRPairs();
        $this->view->availableBudget     = $this->program->getBudgetLeft($parentProgram);
        $this->view->budgetUnitList      = $this->project->getBudgetUnitList();

        $this->display();
    }

    /**
     * Get copy project and send variables to create page.
     *
     * @param  int $copyProjectID
     * @access protected
     * @return void
     */
    private function getCopyProject(int $copyProjectID): object
    {
        $copyProject = $this->project->getByID($copyProjectID);
        $products    = $this->product->getProducts($copyProjectID);

        foreach($products as $product)
        {
            $branches = implode(',', $product->branches);
            $copyProject->productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id, $branches, 'noclosed', true);
        }

        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noclosed');
        $this->view->products     = $products;
        $this->view->copyProject  = $copyProject;

        return $copyProject;
    }

    /**
     * Link plan's stories after create a project.
     *
     * @param  object $postData
     * @access protected
     * @return void
     */
    protected function linkPlanStories(object $postData)
    {
        $planIdList = array();
        foreach($postData->rawdata->plans as $plans)
        {
            foreach($plans as $planID)
            {
                $planIdList[$planID] = $planID;
            }
        }

        $planStoryGroup = $this->loadModel('story')->getStoriesByPlanIdList($planIdList);
        foreach($planIdList as $planID)
        {
            $planStories = $planProducts = array();
            $planStory   = isset($planStoryGroup[$planID]) ? $planStoryGroup[$planID] : array();
            if(!empty($planStory))
            {
                foreach($planStory as $id => $story)
                {
                    if($story->status == 'draft' or $story->status == 'reviewing')
                    {
                        unset($planStory[$id]);
                        continue;
                    }
                    $planProducts[$story->id] = $story->product;
                }

                $planStories = array_keys($planStory);
                $this->loadModel('execution')->linkStory($projectID, $planStories, $planProducts);
            }
        }
    }

    /**
     * Append extras data to post data.
     *
     * @param  object $postData
     * @access protected
     * @return int|object
     */
    protected function prepareStartExtras(object $postData): object
    {
        return $postData->add('status', 'doing')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->get();
    }

    /**
     * Append extras data to post data.
     *
     * @param  int    $iprojectID
     * @param  object $postData
     *
     * @access protected
     * @return object
     */
    protected function prepareSuspendExtras(int $projectID, object $postData): object
    {
        $editorIdList = $this->config->project->editor->suspend['id'];

        return $postData->add('id', $projectID)
            ->setDefault('status', 'suspended')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('suspendedDate', helper::today())
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->get();
    }

    /**
     * Append extras data to post data.
     *
     * @param  int    $iprojectID
     * @param  object $postData
     *
     * @access protected
     * @return object
     */
    protected function prepareClosedExtras(int $projectID, object $postData): object
    {
        $editorIdList = $this->config->project->editor->suspend['id'];

        return  $postData->add('id', $projectID)
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', helper::now())
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->get();
    }

    /**
     * Send variables to view page.
     *
     * @param  object $project
     * @access protected
     *
     * @return void
     */
    protected function buildStartForm(object $project): void
    {
        $this->view->title   = $this->lang->project->start;
        $this->view->project = $project;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions = $this->loadModel('action')->getList('project', $project->id);
        $this->display();
    }

    /**
     * After starting the project, do other operations.
     *
     * @param  object $project
     * @param  array  $changes
     * @param  string $comment
     *
     * @access protected
     * @return void
     */
    protected function responseAfterStart(object $project, array $changes, string $comment): void
    {
        if($comment != '' or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('project', $project->id, 'Started', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->loadModel('common')->syncPPEStatus($project->id);

        $this->executeHooks($project->id);
    }

    /**
     * After suspending the project, do other operations.
     *
     * @param  int    $projectID
     * @param  array  $changes
     * @param  string $comment
     *
     * @access protected
     * @return void
     */
    protected function responseAfterSuspend(int $projectID, array $changes, string $comment): void
    {
        if($comment != '' or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('project', $projectID, 'Suspended', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->loadModel('common')->syncPPEStatus($projectID);

        $this->executeHooks($projectID);
    }

    /**
     * Send variables to suspend page.
     *
     * @param  int $projectID
     * @access protected
     */
    protected function buildSuspendForm(int $projectID): void
    {
        $this->view->title   = $this->lang->project->suspend;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions = $this->loadModel('action')->getList('project', $projectID);
        $this->view->project = $this->project->getByID($projectID);
        $this->display();
    }

    /**
     * After closing the project, do other operations.
     *
     * @param  int    $projectID
     * @param  array  $changes
     * @param  string $comment
     *
     * @access protected
     * @return void
     */
    protected function responseAfterClose(int $projectID, array $changes, string $comment): void
    {
        if($comment != '' or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('project', $projectID, 'Closed', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->loadModel('common')->syncPPEStatus($projectID);

        $this->executeHooks($projectID);
    }

    /**
     * Send variables to close page.
     *
     * @param  int $projectID
     * @access protected
     *
     * @return void
     */
    protected function buildClosedForm(int $projectID): void
    {
        $this->view->title   = $this->lang->project->close;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->project = $this->project->getByID($projectID);
        $this->view->actions = $this->loadModel('action')->getList('project', $projectID);
        $this->display();
    }

    /**
     * 获取项目下拉选择框
     * Get project drop menu.
     *
     * @param  int $projectID
     * @param  int $module
     * @param  int $method
     * @access protected
     * @return void
     */
    protected function getDropMenu(int $projectID, string $module, string $method) :void
    {
        $this->loadModel('program');

        $programs        = array();
        $orderedProjects = array();

        $projects = $this->project->getListByCurrentUser();
        $programs = $this->program->getPairs(true);
        $link     = $this->project->getProjectLink($module, $method, $projectID);

        foreach($projects as $project)
        {
            $project->parent = $this->program->getTopByID($project->parent);
            $project->parent = isset($programs[$project->parent]) ? $project->parent : $project->id;
            $orderedProjects[$project->parent][] = $project;
            unset($projects[$project->id]);
        }

        $this->view->link      = $link;
        $this->view->projectID = $projectID;
        $this->view->projects  = $orderedProjects;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->programs  = $programs;

        $this->display();
    }

    /**
     * Send variables to activate page.
     *
     * @param  object $project
     * @access protected
     *
     * @return void
     */
    protected function buildActivateForm(object $project): void
    {
        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $project->begin);
        $newEnd   = date('Y-m-d', strtotime($project->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->lang->project->activate;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $project->id);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->view->project    = $project;
        $this->display();
    }

    /**
     * After activateing the project, do other operations.
     *
     * @param  int    $projectID
     * @param  array  $changes
     * @param  string $comment
     *
     * @access protected
     * @return void
     */
    protected function responseAfterActivate(int $projectID, array $changes, string $comment): void
    {
        if($this->post->comment != '' or !empty($changes))
        {
            $actionID = $this->action->create('project', $projectID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->executeHooks($projectID);
    }

    /**
     * Append extras data to post data.
     *
     * @param  int    $iprojectID
     * @param  object $postData
     *
     * @access protected
     * @return object
     */
    protected function prepareActivateExtras(int $projectID, object $postData): object
    {
        $oldProject = $this->project->getByID($projectID);

        $editorIdList = $this->config->project->editor->activate['id'];

        return $postData->add('id', $projectID)
            ->setDefault('realEnd', '')
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF(!helper::isZeroDate($oldProject->realBegan), 'realBegan', helper::today())
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->get();
    }

    /**
     * 从项目中删除所有关联的执行。
     * removes all associated executions from the be deleted project
     *
     * @param  int    $projectID
     * @param  string $from
     *
     * @access protected
     * @return void
     */
    protected function removeAssociatedExecutions(int $projectID, string $from): void
    {
        /* Delete the execution under the project. */
        $executionIdList = $this->loadModel('execution')->getPairs($projectID);
        if(empty($executionIdList))
        {
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            if($from == 'view') return print(js::locate($this->createLink('project', 'browse'), 'parent'));
            return print(js::reload('parent'));
        }

        $this->updateRelatedItemByDelete('zt_execution', array_keys($executionIdList));
        foreach($executionIdList as $executionID => $execution) $this->action->create('execution', $executionID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
        $this->user->updateUserView($executionIdList, 'sprint');
    }

    /**
     * 从项目中删除所有关联的产品。
     * removes all associated products from the be deleted project
     *
     * @param  object $projectID
     *
     * @access protected
     * @return void
     */
    protected function removeAssociatedProducts(object $project): void
    {
        /* Delete shadow product.*/
        if(!$project->hasProduct)
        {
            $productID = $this->loadModel('product')->getProductIDByProject($project->id);
            $this->updateRelatedItemByDelete('zt_product', $productID);
        }
    }
}
