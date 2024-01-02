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
     * @return object|false
     */
    protected function prepareCreateExtras(object $postData): object|false
    {
        $project = $postData->setDefault('status', 'wait')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->delta == 999, 'days', 0)
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->setIF(!isset($_POST['multiple']), 'multiple', '1')
            ->setIF($this->post->model == 'ipd', 'stageBy', 'project')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', $this->post->name)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('days', '0')
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->join('auth', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->get();

        if(!isset($this->config->setCode) || $this->config->setCode == 0) unset($project->code);

        /* Lean mode relation defaultProgram. */
        if($this->config->systemMode == 'light') $project->parent = $this->config->global->defaultProgram;

        if(!$this->checkProductAndBranch($project, (object)$_POST))  return false;
        if(!$this->checkDaysAndBudget($project, (object)$_POST))     return false;
        if(!$this->checkProductNameUnqiue($project, (object)$_POST)) return false;

        return $project;
    }

    /**
     * Check work days legtimate.
     *
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function checkWorkdaysLegtimate($project): bool
    {
        $workdays = helper::diffDate($project->end, $project->begin) + 1;
        if(isset($project->days) && $project->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }
        return true;
    }

    /**
     * Validate $postData and prepare $project for update.
     *
     * @param  object       $postData
     * @param  int          $hasProduct
     * @access protected
     * @return object|false
     */
    protected function prepareProject(object $postData, int $hasProduct): object|false
    {
        $project = $postData->setDefault('team', $this->post->name)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('days', '0')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->delta == 999, 'days', 0)
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round((float)$this->post->budget, 2))
            ->join('whitelist', ',')
            ->join('auth', ',')
            ->stripTags($this->config->project->editor->edit['id'], $this->config->allowedTags)
            ->remove('products,plans,branch')
            ->get();

        if($hasProduct)
        {
            /* Check if products not empty. */
            if(!$this->post->products || !count(array_filter($this->post->products)))
            {
                dao::$errors['products[0]'] = $this->app->rawMethod == 'create' ? $this->lang->project->error->productNotEmpty : $this->lang->project->errorNoProducts;
                return false;
            }

            $project->parent = (int)$project->parent;
            /* Check if products and branch valid. */
            if(!$this->project->checkBranchAndProduct($project->parent, (array)$this->post->products, (array)$this->post->branch)) return false;
        }

        /* Check if work days legtimate. */
        if(!$this->checkWorkdaysLegtimate($project)) return false;

        if(!isset($this->config->setCode) || $this->config->setCode == 0) unset($project->code);

        /* Lean mode relation defaultProgram. */
        if($this->config->systemMode == 'light') $project->parent = $this->config->global->defaultProgram;

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

        if(!empty($rawdata->products))
        {
            $topProgramID     = (int)$this->loadModel('program')->getTopByID((int)$project->parent);
            $multipleProducts = $this->loadModel('product')->getMultiBranchPairs($topProgramID);
            foreach($rawdata->products as $index => $productID)
            {
                if(isset($multipleProducts[$productID]) && empty($rawdata->branch[$index]))
                {
                    dao::$errors['branch[0]'] = $this->lang->project->error->emptyBranch;
                    return false;
                }
            }
        }

        /* Judge products not empty. */
        if($project->parent && $project->hasProduct && empty($linkedProductsCount) && !isset($rawdata->newProduct))
        {
            dao::$errors['products[0]'] = $this->lang->project->error->productNotEmpty;
            return false;
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
        if(isset($project->days) && $project->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        /* 如果没有选择长期，则判断计划结束日期不能为空. */
        if($this->post->delta != 999 and !$project->end)
        {
            dao::$errors['end'] = $this->lang->project->copyProject->endTips;
            return false;
        }

        if(!empty($project->budget))
        {
            if(!is_numeric($project->budget))
            {
                dao::$errors['budget'] = sprintf($this->lang->project->error->budgetNumber);
                return false;
            }
            elseif(is_numeric($project->budget) && ($project->budget < 0))
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

        if($this->app->tab == 'program' && $programID)                   common::setMenuVars('program', $programID);
        if($this->app->tab == 'product' && !empty($output['productID'])) $this->loadModel('product')->setMenu($output['productID']);
        if($this->app->tab == 'doc') unset($this->lang->doc->menu->project['subMenu']);

        if($copyProjectID) $copyProject = $this->getCopyProject((int)$copyProjectID);
        $shadow = $copyProjectID && empty($copyProject->hasProduct) ? 1 : 0;

        if($this->view->globalDisableProgram) $programID = $this->config->global->defaultProgram;

        $programID    = (int)$programID;
        $topProgramID = $this->program->getTopByID($programID);

        if($model == 'kanban')
        {
            $this->lang->project->aclList    = $this->lang->project->kanbanAclList;
            $this->lang->project->subAclList = $this->lang->project->kanbanSubAclList;
        }

        $withProgram   = $this->config->systemMode == 'ALM';
        $allProducts   = $this->program->getProductPairs($programID, 'all', 'noclosed', '', $shadow, $withProgram);
        $parentProgram = $this->loadModel('program')->getByID($programID);

        $this->view->title               = $this->lang->project->create;
        $this->view->gobackLink          = (isset($output['from']) && $output['from'] == 'global') ? $this->createLink('project', 'browse') : '';
        $this->view->model               = $model;
        $this->view->pmUsers             = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users               = $this->user->getPairs('noclosed|nodeleted');
        $this->view->programID           = $programID;
        $this->view->productID           = isset($output['productID']) ? $output['productID'] : 0;
        $this->view->branchID            = isset($output['branchID']) ? $output['branchID'] : 0;
        $this->view->allProducts         = $allProducts;
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs((int)$topProgramID);
        $this->view->copyProjects        = $this->project->getPairsByModel($model);
        $this->view->copyPinyinList      = common::convert2Pinyin($this->view->copyProjects);
        $this->view->copyProjectID       = $copyProjectID;
        $this->view->parentProgram       = $parentProgram;
        $this->view->programList         = $this->program->getParentPairs();
        $this->view->URSRPairs           = $this->loadModel('custom')->getURSRPairs();
        $this->view->availableBudget     = $parentProgram ? $this->program->getBudgetLeft($parentProgram) : 0;
        $this->view->budgetUnitList      = $this->project->getBudgetUnitList();

        $this->display();
    }

    /**
     * Send variables to edit page.
     *
     * @param  int       $projectID
     * @param  object    $project
     * @param  string    $from
     * @param  int       $programID
     * @access protected
     * @return void
     */
    protected function buildEditForm(int $projectID, object $project, string $from = '', int $programID = 0): void
    {
        $productPlans     = array();
        $linkedBranchList = array();
        $parentProject    = $this->loadModel('program')->getByID($project->parent);
        $projectStories   = $this->project->getStoriesByProject($projectID);
        $branches         = $this->project->getBranchesByProject($projectID);
        $linkedProducts   = $this->loadModel('product')->getProducts($projectID, 'all', '', true);
        $projectBranches  = $this->project->getBranchGroup($projectID, array_keys($linkedProducts));
        $plans            = $this->loadModel('productplan')->getGroupByProduct(array_keys($linkedProducts), 'skipparent|unexpired');
        $withProgram      = $this->config->systemMode == 'ALM';
        $allProducts      = $this->program->getProductPairs($project->parent, 'all', 'noclosed', '', 0, $withProgram);

        $unmodifiableProducts     = array();
        $unmodifiableMainBranches = array();
        $unmodifiableBranches     = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->name;
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranchList[$branchID] = $branchID;

                if(!isset($productPlans[$productID]))
                {
                    $productPlans[$productID] = array();
                    if(isset($plans[$productID][BRANCH_MAIN]))
                    {
                        foreach($plans[$productID][BRANCH_MAIN] as $plan) $productPlans[$productID][$plan->id] = array('text' => $plan->title, 'value' => $plan->id);
                    }
                }

                if(!empty($plans[$productID][$branchID]))
                {
                    foreach($plans[$productID][$branchID] as $plan) $productPlans[$productID][$plan->id] = array('text' => $plan->title, 'value' => $plan->id);
                }

                if(!empty($projectStories[$productID][$branchID]) || !empty($projectBranches[$productID][$branchID]))
                {
                    if($branchID == BRANCH_MAIN) $unmodifiableMainBranches[$productID] = $branchID;
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                }
            }
            if(!empty($linkedProduct->plans))
            {
                foreach($linkedProduct->plans as $linkedPlans)
                {
                    $planList = explode(',', $linkedPlans);
                    $planList = array_filter($planList);
                    foreach($planList as $planID)
                    {
                        if(isset($plans[$productID][$planID])) continue;
                        $productPlans[$productID][$planID] = array('text' => '', 'value' => $planID);
                    }
                }
            }
        }

        $productPlansOrder = array();
        foreach($productPlans as $productID => $plan)
        {
            $orderPlans    = $this->loadModel('productplan')->getByIDList(array_keys($plan));
            $orderPlans    = $this->productplan->relationBranch($orderPlans);
            $orderPlansMap = array_keys($orderPlans);
            foreach($orderPlansMap as $planMapID)
            {
                if(empty($plan['title'])) $productPlans[$productID][$planMapID]['text'] = $orderPlans[$planMapID]->title;
                $productPlansOrder[$productID][$planMapID] = $productPlans[$productID][$planMapID];
            }
        }

        $this->view->title = $this->lang->project->edit;

        $this->view->PMUsers                  = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->users                    = $this->user->getPairs('noclosed|nodeleted');
        $this->view->project                  = $project;
        $this->view->programList              = $this->program->getParentPairs();
        $this->view->program                  = $this->program->getByID($project->parent);
        $this->view->projectID                = $projectID;
        $this->view->allProducts              = $allProducts;
        $this->view->multiBranchProducts      = $this->product->getMultiBranchPairs();
        $this->view->productPlans             = array_filter($productPlansOrder);
        $this->view->linkedProducts           = $linkedProducts;
        $this->view->branches                 = $branches;
        $this->view->executions               = $this->loadModel('execution')->getPairs($projectID);
        $this->view->unmodifiableProducts     = $unmodifiableProducts;
        $this->view->unmodifiableBranches     = $unmodifiableBranches;
        $this->view->unmodifiableMainBranches = $unmodifiableMainBranches;
        $this->view->branchGroups             = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), 'noclosed', $linkedBranchList);
        $this->view->URSRPairs                = $this->loadModel('custom')->getURSRPairs();
        $this->view->parentProject            = $parentProject;
        $this->view->parentProgram            = $this->program->getByID($project->parent);
        $this->view->availableBudget          = $parentProject ? $this->program->getBudgetLeft($parentProject) + (float)$project->budget : $project->budget;
        $this->view->budgetUnitList           = $this->project->getBudgetUnitList();
        $this->view->model                    = $project->model;
        $this->view->disableModel             = $this->project->checkCanChangeModel($projectID, $project->model) ? '' : 'disabled';
        $this->view->teamMembers              = $this->user->getTeamMemberPairs($projectID, 'project');
        $this->view->from                     = $from;
        $this->view->programID                = $programID;

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
     * 为项目的bug列表准备分支数据。
     * Prepare branches for project bug list.
     *
     * @param  object    $product
     * @param  int       $productID
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function prepareBranchForBug($product, int $productID, int $projectID)
    {
        $branchOption    = array();
        $branchTagOption = array();
        if($product and $product->type != 'normal')
        {
            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, $projectID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
    }

    /**
     * 为项目bug列表准备模块数据。
     * Prepare modules for project bug list.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $build
     * @param  string $branchID
     * @param  array  $products
     * @access protected
     * @return void
     */
    protected function prepareModuleForBug(int $productID, int $projectID, string $type, int $param, string $orderBy, int $build, string $branchID, array $products)
    {
        $moduleID = $type != 'bysearch' ? $param : 0;
        $modules  = $this->loadModel('tree')->getAllModulePairs('bug');

        /* Get module tree.*/
        $extra = array('projectID' => $projectID, 'orderBy' => $orderBy, 'type' => $type, 'build' => $build, 'branchID' => $branchID);
        if($projectID and empty($productID) and count($products) > 1)
        {
            $moduleTree = $this->tree->getBugTreeMenu($projectID, $productID, 0, array('treeModel', 'createBugLink'), $extra);
        }
        elseif(!empty($products))
        {
            $productID  = empty($productID) ? reset($products)->id : $productID;
            $moduleTree = $this->tree->getTreeMenu($productID, 'bug', 0, array('treeModel', 'createBugLink'), $extra + array('productID' => $productID, 'branchID' => $branchID), $branchID);
        }
        else
        {
            $moduleTree = array();
        }

        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $showModule = !empty($this->config->project->bug->showModule) ? $this->config->project->bug->showModule : '';

        $this->view->moduleTree  = $moduleTree;
        $this->view->modules     = $modules;
        $this->view->moduleID    = $moduleID;
        $this->view->moduleName  = !empty($tree->name) ? $tree->name : $this->lang->tree->all;
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();
    }

    /**
     * 处理项目bug列表的搜索表单。
     * Process search form for project bug list.
     *
     * @param  object     $project
     * @param  string     $type
     * @param  int        $param
     * @param  int        $projectID
     * @param  int        $productID
     * @param  string     $branchID
     * @param  string     $orderBy
     * @param  int        $build
     * @param  array      $products
     * @access protected
     * @return void
     */
    protected function processBugSearchParams(object $project, string $type, int $param, int $projectID, int $productID, string $branchID, string $orderBy, int $build, array $products)
    {
        if(!$project->multiple) unset($this->config->bug->datatable->fieldList['execution']);
        if(!$project->hasProduct)
        {
            unset($this->config->bug->search['fields']['product']);
            if($project->model != 'scrum') unset($this->config->bug->search['fields']['plan']);
        }
        if(!$project->multiple and !$project->hasProduct) unset($this->config->bug->search['fields']['plan']);

        $queryID   = ($type == 'bysearch') ? (int)$param : 0;

        /* Build the search form. */
        $actionURL = $this->createLink('project', 'bug', "projectID=$projectID&productID=$productID&branchID=$branchID&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->loadModel('execution')->buildBugSearchForm($products, $queryID, $actionURL, 'project');
    }

    /**
     * 处理项目版本列表的搜索表单。
     * Process search form for project build list.
     *
     * @param  object    $project
     * @param  object    $product
     * @param  array     $products
     * @param  string    $type
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function processBuildSearchParams(object $project, object $product, array $products, string $type, int $param)
    {
        /* 为无迭代项目和多分支产品处理搜索表单的参数。 */
        if($project->multiple)
        {
            $executionPairs = $this->loadModel('execution')->getByProject($project->id, 'all', '', true, $project->model == 'waterfall');
            $this->config->build->search['fields']['execution'] = zget($this->lang->project->executionList, $project->model);
            $this->config->build->search['params']['execution'] = array('operator' => '=', 'control' => 'select', 'values' => $executionPairs);
        }
        if(!$project->hasProduct) unset($this->config->build->search['fields']['product']);

        if(!empty($product->type) && $product->type != 'normal')
        {
            $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($product->id, '', $projectID);
            $this->config->build->search['fields']['branch'] = sprintf($this->lang->build->branchName, $this->lang->product->branchName[$product->type]);
            $this->config->build->search['params']['branch'] = array('operator' => '=', 'control' => 'select', 'values' => $branches);
        }

        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = $type == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, "projectID={$project->id}&type=bysearch&queryID=myQueryID");
        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL, 'project', $project);
    }

    /**
     * 处理项目权限分组可勾选的项。
     * Process group privs for project.
     *
     * @param  object    $project
     * @access protected
     * @return void
     */
    protected function processGroupPrivs(object $project)
    {
        if($project->hasProduct)
        {
            if($this->config->URAndSR) unset($this->lang->resource->requirement);
            unset($this->lang->resource->productplan);
            unset($this->lang->resource->tree);
        }
        else
        {
            $this->lang->productplan->common  = $this->lang->productplan->plan;
            $this->lang->projectstory->common = $this->lang->projectstory->storyCommon;
            $this->lang->projectstory->story  = $this->lang->projectstory->storyList;
            $this->lang->projectstory->view   = $this->lang->projectstory->storyView;
            unset($this->lang->resource->project->manageProducts);
            unset($this->lang->resource->projectstory->linkStory);
            unset($this->lang->resource->projectstory->importplanstories);
            unset($this->lang->resource->projectstory->unlinkStory);
            unset($this->lang->resource->projectstory->batchUnlinkStory);
            unset($this->lang->resource->story->view);
            if($this->config->URAndSR) unset($this->lang->resource->requirement->view);
            if($this->config->URAndSR) unset($this->lang->resource->requirement->batchChangeBranch);
            unset($this->lang->resource->tree->browseTask);
            unset($this->lang->resource->tree->browsehost);
            unset($this->lang->resource->tree->editHost);
            unset($this->lang->resource->tree->fix);
        }

        if($project->model == 'waterfall' or $project->model == 'waterfallplus')
        {
            unset($this->lang->resource->productplan);
            unset($this->lang->resource->projectplan);
        }
        if($project->model == 'scrum') unset($this->lang->resource->projectstory->track);

        if(!$project->multiple and !$project->hasProduct)
        {
            unset($this->lang->resource->story->batchChangePlan);
            unset($this->lang->resource->execution->importplanstories);
        }
    }

    /**
     * 为bug列表视图构造必要的变量。
     *
     * @param  int       $productID
     * @param  int       $projectID
     * @param  object    $project
     * @param  string    $type
     * @param  int       $param
     * @param  string    $orderBy
     * @param  int       $build
     * @param  string    $branchID
     * @param  array     $products
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return void
     */
    protected function buildBugView(int $productID, int $projectID, object $project, string $type, int $param, string $orderBy, int $build, string $branchID, array $products, int $recTotal, int $recPerPage, int $pageID)
    {
        $this->loadModel('bug');
        $this->loadModel('user');

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = common::appendOrder($orderBy);

        $bugs = $this->bug->getProjectBugs($projectID, $productID, $branchID, $build, $type, $param, $sort, '', $pager);
        $bugs = $this->bug->processBuildForBugs($bugs);
        $bugs = $this->bug->batchAppendDelayedDays($bugs);

        /* Get story and task id list. */
        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }

        $storyList = $storyIdList ? $this->loadModel('story')->getByList($storyIdList) : array();
        $taskList  = $taskIdList  ? $this->loadModel('task')->getByIdList($taskIdList) : array();

        $this->view->title            = $project->name . $this->lang->colon . $this->lang->bug->common;
        $this->view->bugs             = $bugs;
        $this->view->build            = $this->loadModel('build')->getById($build);
        $this->view->buildID          = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager            = $pager;
        $this->view->orderBy          = $orderBy;
        $this->view->type             = $type;
        $this->view->param            = $param;
        $this->view->productID        = $productID;
        $this->view->project          = $project;
        $this->view->branchID         = empty($this->view->build->branch) ? $branchID : $this->view->build->branch;
        $this->view->builds           = $this->loadModel('build')->getBuildPairs(array($productID));
        $this->view->users            = $this->user->getPairs('noletter');
        $this->view->executions       = $this->loadModel('execution')->getPairs($projectID, 'all', 'empty|withdelete');
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID ? $productID : array_keys($products));
        $this->view->stories          = $storyList;
        $this->view->tasks            = $taskList;
        $this->view->projectPairs     = $this->project->getPairsByProgram();
        $this->view->switcherParams   = "projectID={$projectID}&productID={$productID}&currentMethod=bug";
        $this->view->switcherText     = isset($products[$productID]) ? $products[$productID]->name : $this->lang->product->all;
        $this->view->switcherObjectID = $productID;

        $this->display();
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
        if($comment != '' || !empty($changes))
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
        if($comment != '' || !empty($changes))
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
        if($comment != '' || !empty($changes))
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
        $dateTime = (int)(strtotime($project->end) + $dateDiff * 24 * 3600);
        $newEnd   = date('Y-m-d', $dateTime);

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
     *
     * @access protected
     * @return void
     */
    protected function responseAfterActivate(int $projectID, array $changes): void
    {
        if($this->post->comment != '' || !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('project', $projectID, 'Activated', $this->post->comment);
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
        $rawdata      = $postData->rawdata;
        $oldProject   = $this->project->getByID($projectID);
        $editorIdList = $this->config->project->editor->activate['id'];

        return $postData->add('id', $projectID)
            ->setDefault('realEnd', $oldProject->realEnd)
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF($rawdata->begin == '0000-00-00', 'begin', '')
            ->setIF($rawdata->end   == '0000-00-00', 'end', '')
            ->setIF(!helper::isZeroDate($oldProject->realBegan), 'realBegan', helper::today())
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->get();
    }

    /**
     * 从项目中删除所有关联的执行。
     * removes all associated executions from the be deleted project
     *
     * @param  array $executionIdList
     *
     * @access protected
     * @return void
     */
    protected function removeAssociatedExecutions(array $executionIdList): void
    {
        $this->project->deleteByTableName(TABLE_EXECUTION, array_keys($executionIdList));
        foreach($executionIdList as $executionID => $execution) $this->loadModel('action')->create('execution', $executionID, 'deleted', '', actionModel::CAN_UNDELETED);
        $this->loadModel('user')->updateUserView($executionIdList, 'sprint');
    }

    /**
     * 从项目中删除所有关联的产品。
     * removes all associated products from the be deleted project
     *
     * @param  object $project
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
            $this->project->deleteByTableName('zt_product', $productID);
        }
    }

    /**
     * 管理项目的关联产品:构建数据，更新产品变动，记录日志。
     * Manage project related products: build data, update product changes, and record actions.
     *
     * @param  int       $projectID
     * @param  object    $project
     * @param  array     $IdList
     * @access protected
     * @return bool
     */
    protected function updateLinkedProducts(int $projectID, object $project, array $IdList): bool
    {
        /* 获取旧产品数据，并更新。*/
        /* Get old product data and update it. */
        $formerProducts = $this->loadModel('product')->getProducts($projectID);
        $this->project->updateProducts($projectID);
        if(dao::isError()) return false;

        /* 如果关联产品新增或删除，记录动态。*/
        /* If add or delete associated products and record their dynamics. */
        $currentProducts = $this->product->getProducts($projectID);
        $formerIds       = array_keys($formerProducts);
        $currentIds      = array_keys($currentProducts);
        $changes         = array_merge(array_diff($formerIds, $currentIds), array_diff($currentIds, $formerIds));
        if($changes) $this->loadModel('action')->create('project', $projectID, 'Managed', '', !empty($this->post->products) ? implode(',', $this->post->products) : '');

        $this->loadModel('execution');

        /* 如果是无迭代项目，更新关联产品。*/
        /* IF multiple is 0 Update associated products. */
        if(empty($project->multiple))
        {
            $executionID = $this->execution->getNoMultipleID($projectID);
            if($executionID) $this->execution->updateProducts($executionID, $_POST);
        }

        /* 如果是瀑布项目单套阶段，更新关联产品。*/
        /* If it is a single stage of the waterfall project, update associated products. */
        if($project->stageBy == 'project')
        {
            foreach($IdList as $executionID)
            {
                $this->execution->updateProducts($executionID, $_POST);
                if($changes) $this->loadModel('action')->create('execution', $executionID, 'Managed', '', implode(',', $currentIds));
            }
        }

        /* 如果有迭代并且是非瀑布项目，记录关联产品执行到action表。*/
        /* If it is multiple project and model isn't waterfall project, record to table action. */
        if($project->multiple && $project->model != 'waterfall' && $project->model != 'waterfallplus')
        {
            $this->recordExecutionsOfUnlinkedProducts($formerProducts, $currentIds, $IdList);
        }

        return true;
    }

    /**
     * 记录取消关联产品的执行到action表。
     * Record the execution of disassociated products to the action table.
     *
     * @param  array     $formerProducts
     * @param  array     $selectedIds
     * @param  array     $executionIdList
     * @access protected
     * @return void
     */
    protected function recordExecutionsOfUnlinkedProducts(array $formerProducts, array $selectedIds, array $executionIdList): void
    {
        $executionProductGroup = $this->loadModel('project')->getExecutionProductGroup($executionIdList); //项目下所有执行对应的关联产品。
        $unlinkedProductIds    = array_diff(array_keys($formerProducts), $selectedIds);                   //取消关联的产品。
        if(!empty($unlinkedProductIds))
        {
            $unlinkedProductPairs = array(); //取消关联的产品id->name键值对。
            foreach($unlinkedProductIds as $productID) $unlinkedProductPairs[$productID] = $formerProducts[$productID]->name;

            $executions = array(); //取消关联产品的执行
            foreach($executionProductGroup as $executionID => $products) //遍历执行对应关联产品键值对。
            {
                $unlinkedExecutionProducts = array_intersect_key($unlinkedProductPairs, $products); //获取执行中解除的关联产品。
                if($unlinkedExecutionProducts) $executions[$executionID] = $unlinkedExecutionProducts;
            }

            foreach($executions as $executionID => $unlinkedExecutionProducts)
            {
                $this->loadModel('action')->create('execution', $executionID, 'unlinkproduct', '', implode(',', $unlinkedExecutionProducts));
            }
        }
    }

    /**
     * 提取关联用户故事无法移除的项目产品
     * Extract related stories cannot be removed product.
     *
     * @param  int    $projectID
     * @param  object $project
     *
     * @access protected
     * @return void
     */
    protected function extractUnModifyForm(int $projectID, object $project): void
    {
        $linkedBranches      = array();
        $linkedBranchIdList  = array();
        $branches            = $this->project->getBranchesByProject($projectID);
        $linkedProductIdList = empty($branches) ? array() : array_keys($branches);
        $allProducts         = $this->loadModel('program')->getProductPairs($project->parent, 'all', 'noclosed', implode(',', $linkedProductIdList));
        $linkedProducts      = $this->loadModel('product')->getProducts($projectID, 'all', '', true, $linkedProductIdList);
        $projectStories      = $this->project->getStoriesByProject($projectID);
        $projectBranches     = $this->project->getBranchGroup($projectID, array_keys($linkedProducts));

        /* If the story of the product which linked the project,don't allow to remove the product. */
        $unmodifiableProducts     = array();
        $unmodifiableBranches     = array();
        $unmodifiableMainBranches = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $linkedBranches[$productID] = array();
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranches[$productID][$branchID] = $branchID;
                $linkedBranchIdList[$branchID] = $branchID;

                if(!empty($projectStories[$productID][$branchID]) || !empty($projectBranches[$productID][$branchID]))
                {
                    if($branchID == BRANCH_MAIN) $unmodifiableMainBranches[$productID] = $branchID;
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                }
            }
        }

        /* Build the product from other linked products. */
        if($this->config->systemMode == 'ALM') $this->buildProductForm($project, $allProducts, $linkedBranchIdList, $linkedBranches, $linkedProducts);

        $this->view->linkedBranches           = $linkedBranches;
        $this->view->linkedProducts           = $linkedProducts;
        $this->view->unmodifiableProducts     = $unmodifiableProducts;
        $this->view->unmodifiableBranches     = $unmodifiableBranches;
        $this->view->unmodifiableMainBranches = $unmodifiableMainBranches;
        $this->view->allProducts              = $allProducts;
        $this->view->allBranches              = $this->loadModel('branch')->getByProducts(array_keys($allProducts), 'ignoreNormal');
    }

    /**
     * 初始化项目集下其他关联产品中当前产品
     * Build the current product under the projectprogram.
     *
     * @param  object $project
     * @param  array  $allProducts
     * @param  array  $linkedBranchIdList
     * @param  array  $linkedBranches
     * @param  array  $linkedProducts
     *
     * @access protected
     * @return void
     */
    protected function buildProductForm(object $project, array $allProducts, array $linkedBranchIdList, array $linkedBranches, array $linkedProducts): void
    {
        $branchGroups           = $this->loadModel('branch')->getByProducts(array_keys($allProducts), 'ignoreNormal|noclosed', $linkedBranchIdList);
        $topProgramID           = $project->parent ? $this->program->getTopByPath($project->path) : 0;
        $productsGroupByProgram = $this->loadModel('product')->getProductsGroupByProgram();

        $currentProducts = array();
        $otherProducts   = array();
        foreach($productsGroupByProgram as $programID => $programProducts)
        {
            if($programID != $topProgramID)
            {
                $otherProducts = $this->getOtherProducts($programProducts, $branchGroups, $linkedBranches, $linkedProducts);
            }
            else
            {
                $currentProducts += $programProducts;
            }
        }

        $this->view->currentProducts = $currentProducts;
        $this->view->otherProducts   = $otherProducts;
        $this->view->branchGroups    = $branchGroups;
        $this->view->linkedBranches  = $linkedBranches;
        $this->view->linkedProducts  = $linkedProducts;
    }

    /**
     * 获取其他的关联产品
     * Get other products under the projectprogram.
     *
     * @param  array     $programProducts
     * @param  array     $branchGroups
     * @param  array     $linkedBranches
     * @param  array     $linkedProducts
     *
     * @access protected
     * @return array
     */
    protected function getOtherProducts(array $programProducts, array $branchGroups, array $linkedBranches, array $linkedProducts): array
    {
        $otherProducts = array();
        foreach($programProducts as $productID => $productName)
        {
            if(!empty($branchGroups[$productID]))
            {
                foreach($branchGroups[$productID] as $branchID => $branchName)
                {
                    if(isset($linkedProducts[$productID]) && isset($linkedBranches[$productID][$branchID])) continue;
                    $otherProducts["{$productID}_{$branchID}"] = $productName . '_' . $branchName;
                }
            }
            else
            {
                if(isset($linkedProducts[$productID])) continue;
                $otherProducts[$productID] = $productName;
            }
        }

        return $otherProducts;
    }

    /**
     * 根据当前所在模块更新二级菜单
     * set project menu
     *
     * @param  int $projectID
     * @param  int $project
     * @access protected
     * @return void
     */
    protected function setProjectMenu(int $projectID, int $projectParent): void
    {
        if($this->app->tab == 'program')
        {
            common::setMenuVars('program', $projectParent);
        }
        elseif($this->app->tab == 'project')
        {
            $this->project->setMenu($projectID);
        }
    }

    /**
     * 处理项目列表展示数据。
     * Process project list display data.
     *
     * @param  array     $projectList
     * @access protected
     * @return array
     */
    protected function processProjectListData(array $projectList): array
    {
        $userList = $this->dao->select('account,realname,avatar')->from(TABLE_USER)->fetchAll('account');

        $this->loadModel('story');
        $this->loadModel('execution');
        foreach($projectList as $project)
        {
            $project = $this->project->formatDataForList($project, $userList);

            $projectStories = $this->story->getExecutionStoryPairs($project->id);
            $project->storyCount = count($projectStories);

            $executions = $this->execution->getStatData($project->id, 'all', 0, 0, false, 'skipParent');
            $project->executionCount = count($executions);

            $project->from    = 'project';
            $project->actions = $this->project->buildActionList($project);
        }

        return array_values($projectList);
    }

    /**
     * 处理版本列表展示数据。
     * Process build list display data.
     *
     * @param  array     $buildList
     * @param  int       $projectID
     * @access protected
     * @return object[]
     */
    protected function processBuildListData(array $buildList, int $projectID): array
    {
        $this->loadModel('build');
        $this->loadModel('branch');

        $showBranch    = false;
        $productIdList = array();
        foreach($buildList as $build) $productIdList[$build->product] = $build->product;

        /* Get branch name. */
        $branchGroups = $this->branch->getByProducts($productIdList);
        $builds       = array();
        $project      = $this->project->getByID($projectID);
        foreach($buildList as $build)
        {
            $build->branchName = '';
            if(isset($branchGroups[$build->product]))
            {
                $showBranch  = true;
                $branchPairs = $branchGroups[$build->product];
                foreach(explode(',', trim($build->branch, ',')) as $branchID)
                {
                    if(isset($branchPairs[$branchID])) $build->branchName .= "{$branchPairs[$branchID]},";
                }
                $build->branchName = trim($build->branchName, ',');

                if(empty($build->branchName) and empty($build->builds)) $build->branchName = $this->lang->branch->main;
            }
            $build->actions = $this->build->buildActionList($build, 0, 'projectbuild');

            if($project->multiple && empty($build->execution))
            {
                $rowspan     = $build->scmPath && $build->filePath ? 2 : 1;
                $buildCount  = count($build->builds);
                $rowspan     = $buildCount > $rowspan ? $buildCount : $rowspan;
                $pathRowspan = $build->scmPath && $build->filePath ? floor($rowspan/2) : $rowspan;

                if($buildCount >= 2)
                {
                    $i = 1;
                    foreach($build->builds as $childBuild)
                    {
                        $buildInfo = clone $build;
                        $buildInfo->executionName = $childBuild->executionName;
                        $buildInfo->rowspan       = $rowspan;
                        if($i <= $pathRowspan)
                        {
                            $buildInfo->pathRowspan = $pathRowspan;
                            $buildInfo->pathType    = empty($build->scmPath) ? 'filePath' : 'scmPath';
                            $buildInfo->path        = empty($build->scmPath) ? $build->filePath : $build->scmPath;
                        }
                        elseif($i > $pathRowspan)
                        {
                            $buildInfo->pathRowspan = $rowspan - $pathRowspan;
                            $buildInfo->pathType    = 'filePath';
                            $buildInfo->path        = $build->filePath;
                        }
                        $builds[] = $buildInfo;

                        $i ++;
                    }
                }
                else
                {
                    $childBuild = !empty($build->builds) ? current($build->builds) : array();
                    $build->executionName    = !empty($childBuild) ? $childBuild->executionName : '';

                    if($build->scmPath && $build->filePath)
                    {
                        $build->rowspan          = 2;
                        $build->executionRowspan = 2;

                        $buildInfo = clone $build;
                        $buildInfo->pathType = 'scmPath';
                        $buildInfo->path     = $build->scmPath;
                        $builds[]  = $buildInfo;

                        $buildInfo = clone $build;
                        $buildInfo->pathType = 'filePath';
                        $buildInfo->path     = $build->filePath;
                        $builds[]  = $buildInfo;
                    }
                    else
                    {
                        $build->pathType = empty($build->scmPath) ? 'filePath' : 'scmPath';
                        $build->path     = empty($build->scmPath) ? $build->filePath : $build->scmPath;

                        $builds[] = $build;
                    }
                }

            }
            else
            {
                if($build->scmPath && $build->filePath)
                {
                    $build->rowspan          = 2;
                    $build->executionRowspan = 2;

                    $buildInfo = clone $build;
                    $buildInfo->pathType = 'scmPath';
                    $buildInfo->path     = $build->scmPath;
                    $builds[]  = $buildInfo;

                    $buildInfo = clone $build;
                    $buildInfo->pathType = 'filePath';
                    $buildInfo->path     = $build->filePath;
                    $builds[]  = $buildInfo;
                }
                else
                {
                    $build->pathType = empty($build->scmPath) ? 'filePath' : 'scmPath';
                    $build->path     = empty($build->scmPath) ? $build->filePath : $build->scmPath;

                    $builds[] = $build;
                }
            }
        }

        /* Set data table column. */
        if(!$project->hasProduct) unset($this->config->build->dtable->fieldList['product']);
        if(!$showBranch || !$project->hasProduct) unset($this->config->build->dtable->fieldList['branch']);
        if(!$project->multiple) unset($this->config->build->dtable->fieldList['execution']);
        $this->config->build->dtable->fieldList['name']['link'] = helper::createLink('projectbuild', 'view', 'buildID={id}');
        $this->config->build->dtable->fieldList['execution']['title'] = zget($this->lang->project->executionList, $project->model);

        return $builds;
    }

    /**
     * 构建项目团队成员信息。
     * Build project team member information.
     *
     * @param  array  $currentMembers
     * @param  array  $members2Import
     * @param  array  $deptUsers
     * @param  int    $days
     * @access protected
     * @return array
     */
    protected function buildMembers(array $currentMembers, array $members2Import, array $deptUsers, int $days): array
    {
        $teamMembers = array();
        foreach($currentMembers as $account => $member)
        {
            $member->memberType = 'default';
            $teamMembers[$account] = $member;
        }

        $roles = $this->loadModel('user')->getUserRoles(array_keys($deptUsers));
        foreach($deptUsers as $deptAccount => $userName)
        {
            if(isset($currentMembers[$deptAccount]) || isset($members2Import[$deptAccount])) continue;

            $deptMember = new stdclass();
            $deptMember->memberType = 'dept';
            $deptMember->account    = $deptAccount;
            $deptMember->role       = zget($roles, $deptAccount, '');
            $deptMember->days       = $days;
            $deptMember->hours      = $this->config->execution->defaultWorkhours;
            $deptMember->limited    = 'no';

            $teamMembers[$deptAccount] = $deptMember;
        }

        foreach($members2Import as $account => $member2Import)
        {
            $member2Import->memberType = 'import';
            $member2Import->days       = $days;
            $member2Import->limited    = 'no';
            $teamMembers[$account] = $member2Import;
        }

        for($j = 0; $j < 5; $j ++)
        {
            $newMember = new stdclass();
            $newMember->memberType = 'add';
            $newMember->account    = '';
            $newMember->role       = '';
            $newMember->days       = $days;
            $newMember->hours      = $this->config->execution->defaultWorkhours;
            $newMember->limited    = 'no';

            $teamMembers[] = $newMember;
        }

        return $teamMembers;
    }

    /**
     * 构造用户键值对和列表。
     * Build user pairs and user list.
     *
     * @access protected
     * @return array
     */
    protected function buildUsers(): array
    {
        $userList  = array();
        $userPairs = array();
        $users     = $this->loadModel('user')->getList('all');

        foreach($users as $user)
        {
            $userList[$user->account]  = $user;
            $userPairs[$user->account] = $user->realname;
        }

        return array($userPairs, $userList);
    }

    /**
     * 格式化导出的项目数据。
     * Format the export project data.
     *
     * @param  string    $status
     * @param  string    $orderBy
     * @access protected
     * @return array
     */
    protected function formatExportProjects(string $status, string $orderBy): array
    {
        $this->loadModel('product');

        $projects = $this->project->getList($status, $orderBy);
        $users    = $this->loadModel('user')->getPairs('noletter');
        foreach($projects as $i => $project)
        {
            $projectBudget = $project->budget === '' ? '0' : $project->budget;

            $project->PM     = zget($users, $project->PM);
            $project->status = $this->processStatus('project', $project);
            $project->model  = zget($this->lang->project->modelList, $project->model);
            $project->budget = $project->budget != 0 ? $projectBudget . zget($this->lang->project->unitList, $project->budgetUnit) : $this->lang->project->future;
            $project->parent = $project->parentName;

            $linkedProducts = $this->product->getProducts($project->id, 'all', '', false);
            $project->linkedProducts = implode('，', $linkedProducts);

            if(!$project->hasProduct) $project->linkedProducts = '';
            $project->hasProduct = zget($this->lang->project->projectTypeList, $project->hasProduct);

            if($this->post->exportType == 'selected')
            {
                $checkedItem = $this->cookie->checkedItem;
                if(strpos(",$checkedItem,", ",{$project->id},") === false) unset($projects[$i]);
            }
        }

        return $projects;
    }

    /**
     * 遍历执行列表及子阶段，返回ID列表。
     * Expand executions, return id list.
     *
     * @param  object $stats
     * @access protected
     * @return array
     */
    protected function expandExecutionIdList($stats)
    {
        $executionIdList = array();
        foreach($stats as $execution)
        {
            $executionIdList[$execution->id] = $execution->id;
            if(empty($execution->children)) continue;

            foreach($execution->children as $child)
            {
                $childrenIdList = $this->expandExecutionIdList(array($child));
                foreach($childrenIdList as $childID) $executionIdList[$childID] = $childID;
            }
        }

        return $executionIdList;
    }

    /**
     * 展示测试单的相关变量。
     * Show the testtask related variables.
     *
     * @param  array     $tasks
     * @access protected
     * @return void
     */
    protected function assignTesttaskVars(array $tasks): void
    {
        /* Compute rowspan. */
        $productGroup = array();
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        foreach($tasks as $task)
        {
            $productGroup[$task->product][] = $task;
            if($task->status == 'wait')    $waitCount ++;
            if($task->status == 'doing')   $testingCount ++;
            if($task->status == 'blocked') $blockedCount ++;
            if($task->status == 'done')    $doneCount ++;
            if($task->build == 'trunk' || empty($task->buildName)) $task->buildName = $this->lang->trunk;
        }

        $lastProduct = '';
        foreach($tasks as $taskID => $task)
        {
            $task->rowspan = 0;
            if($lastProduct !== $task->product)
            {
                $lastProduct = $task->product;
                if(!empty($productGroup[$task->product])) $task->rowspan = count($productGroup[$task->product]);
            }
        }

        $this->view->waitCount    = $waitCount;
        $this->view->testingCount = $testingCount;
        $this->view->blockedCount = $blockedCount;
        $this->view->doneCount    = $doneCount;
        $this->view->tasks        = $tasks;
    }

    /**
     * Get kanban data.
     *
     * @access protected
     * @return void
     */
    protected function getKanbanData()
    {
        list($kanbanGroup, $latestExecutions) = $this->project->getStats4Kanban();
        $programPairs = array(0 => $this->lang->project->noProgram) + $this->loadModel('program')->getPairs(true, 'order_asc');

        $kanbanList = array();
        $regionData = array();
        foreach($kanbanGroup as $regionKey => $region)
        {
            if(!$region) continue;

            $lanes       = array();
            $items       = array();
            $columnCards = array();
            foreach($region as $laneKey => $laneData)
            {
                $lanes[] = array('name' => $laneKey, 'title' => zget($programPairs, $laneKey));
                $columns = array();
                foreach(array('wait', 'doing', 'closed') as $columnKey)
                {
                    $columns[] = array('name' => $columnKey, 'title' => $columnKey != 'doing' ? $this->lang->project->{$columnKey . 'Projects'} : $this->lang->project->statusList[$columnKey]);
                    if($columnKey == 'doing')
                    {
                        $columns[] = array('name' => 'doingProjects',   'parentName' => 'doing', 'title' => $this->lang->project->doingProjects,   'order' => 1);
                        $columns[] = array('name' => 'doingExecutions', 'parentName' => 'doing', 'title' => $this->lang->project->doingExecutions, 'order' => 2);
                    }

                    $cardList = !empty($laneData[$columnKey]) ? $laneData[$columnKey] : array();
                    foreach($cardList as $card)
                    {
                        $columnKey = $columnKey == 'doing' ? 'doingProjects' : $columnKey;
                        $items[$laneKey][$columnKey][] = array('id' => $card->id, 'name' => $card->id, 'title' => $card->name, 'status' => $card->status, 'type' => 'project', 'delay' => !empty($card->delay) ? $card->delay : 0, 'progress' => $card->progress);

                        if(!isset($columnCards[$columnKey])) $columnCards[$columnKey] = 0;
                        $columnCards[$columnKey] ++;

                        if($columnKey == 'doingProjects')
                        {
                            if(!empty($latestExecutions[$card->id]))
                            {
                                $columnKey = 'doingExecutions';
                                $execution = $latestExecutions[$card->id];
                                $items[$laneKey][$columnKey][] = array('id' => $execution->id, 'name' => $execution->id, 'title' => $execution->name, 'status' => $execution->status, 'type' => 'execution', 'delay' => !empty($execution->delay) ? $execution->delay : 0, 'progress' => $execution->progress);

                                if(!isset($columnCards[$columnKey])) $columnCards[$columnKey] = 0;
                                $columnCards[$columnKey] ++;
                            }
                        }
                    }
                }
            }

            foreach($columns as $key => $column) $columns[$key]['cards'] = !empty($columnCards[$column['name']]) ? $columnCards[$column['name']] : 0;
            $groupData['key']           = $regionKey;
            $groupData['data']['lanes'] = $lanes;
            $groupData['data']['cols']  = $columns;
            $groupData['data']['items'] = $items;
            $kanbanList[] = array('items' => array($groupData), 'key' => $regionKey, 'heading' => array('title' => $this->lang->project->typeList[$regionKey]));
        }

        return $kanbanList;
    }
}
