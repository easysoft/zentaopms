<?php
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class bug extends control
{
    /**
     * 所有产品。
     * All products.
     *
     * @var    array
     * @access public
     */
    public $products = array();

    /**
     * 当前项目编号。
     * Project id.
     *
     * @var    int
     * @access public
     */
    public $projectID = 0;

    /**
     * 构造函数
     *
     * 1.加载其他模块model类。
     * 2.获取产品，并输出到视图
     *
     * The construct function.
     *
     * 1. Load model of other modules.
     * 2. Get products and assign to view.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('task');
        $this->loadModel('qa');

        /* Get product data. */
        $products = array();
        if(!isonlybody())
        {
            $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
            $mode     = (strpos(',create,edit,', ",{$this->app->methodName},") !== false and empty($this->config->CRProduct)) ? 'noclosed' : '';
            $objectID = ($tab == 'project' or $tab == 'execution') ? $this->session->{$tab} : 0;
            if($tab == 'project' or $tab == 'execution')
            {
                $products = $this->product->getProducts($objectID, $mode, $orderBy = '', $withBranch = false);
            }
            else
            {
                $products = $this->product->getPairs($mode, $programID = 0, $append = '', $shadow = 'all');
            }

            if(empty($products) and !helper::isAjaxRequest()) return print($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=bug&objectID=$objectID")));
        }
        else
        {
            $mode     = empty($this->config->CRProduct) ? 'noclosed' : '';
            $products = $this->product->getPairs($mode, 0, '', 'all');
        }

        $this->view->products = $this->products = $products;
    }

    /**
     * Bug 列表。
     * Browse bugs.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $productID, string $branch = '', string $browseType = '', int $param = 0, string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $productID  = $this->product->saveVisitState($productID, $this->products);
        $product    = $this->product->getByID($productID);
        $branch     = $this->bugZen->getBrowseBranch($branch, $product->type);
        $browseType = $browseType ? strtolower($browseType) : 'unclosed';

        /* 设置排序字段。*/
        /* Set the order field. */
        if(!$orderBy) $orderBy = 'id_desc';
        if($this->cookie->qaBugOrder) $orderBy = $this->cookie->qaBugOrder;

        /* 设置导航。*/
        $this->qa->setMenu($this->products, $productID, $branch);

        $this->bugZen->setBrowseCookie($product, $branch, $browseType, $param, $orderBy);

        $this->bugZen->setBrowseSession($browseType);

        /* 处理列表页面的参数。*/
        /* Processing browse params. */
        list($moduleID, $queryID, $realOrderBy, $pager) = $this->bugZen->prepareBrowseParams($browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID);

        $this->bugZen->buildBrowseSearchForm($productID, $branch, $queryID);

        $executions = $this->loadModel('execution')->getPairs($this->projectID, 'all', 'empty|withdelete|hideMultiple');
        $bugs       = $this->bugZen->getBrowseBugs($product->id, $branch, $browseType, array_keys($executions), $moduleID, $queryID, $realOrderBy, $pager);

        $this->bugZen->buildBrowseView($bugs, $product, $branch, $browseType, $param, $moduleID, $executions, $orderBy, $pager);
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
    public function report($productID, $browseType, $branchID, $moduleID, $chartType = 'default')
    {
        $this->loadModel('report');
        $this->view->charts = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->bug->$chartFunc();
                $chartOption = $this->lang->bug->report->$chart;
                if(!empty($chartType) and $chartType != 'default') $chartOption->type = $chartType;
                $this->bug->mergeChartOption($chart);

                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $project = $this->loadModel('project')->getByShadowProduct($productID);
        if(!empty($project) and !$project->multiple) unset($this->lang->bug->report->charts['bugsPerExecution']);

        $this->qa->setMenu($this->products, $productID, $branchID);
        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common . $this->lang->colon . $this->lang->bug->reportChart;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->reportChart;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->branchID      = $branchID;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
        $this->display();
    }

    /**
     * 创建一个bug。
     * Create a bug.
     *
     * @param  string $productID
     * @param  string $branch
     * @param  string $extras       Other params, for example, executionID=10,moduleID=10.
     * @access public
     * @return void
     */
    public function create(string $productID, string $branch = '', string $extras = '')
    {
        $productID = (int)$productID;
        if($branch === '') $branch = (int)$this->cookie->preBranch;

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        extract($output);

        $from = isset($output['from']) ? $output['from'] : '';

        if(!empty($_POST))
        {
            $data = form::data($this->config->bug->form->create);
            $bug  = $this->bugZen->prepareCreateExtras($data, $this->post->uid);

            $checkExist = $this->bugZen->checkExistBug($bug);
            if($checkExist['status'] == 'exists') $this->send(array('result' => 'success', 'id' => $checkExist['id'], 'message' => sprintf($this->lang->duplicate, $this->lang->bug->common), 'locate' => $this->createLink('bug', 'view', "bugID={$checkExist['id']}")));

            $bugID = $this->bug->create($bug);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Set from param if there is a object to transfer bug. */
            helper::setcookie('lastBugModule', (string)$data->data->module);

            $bug = $this->bug->getByID($bugID);

            $this->bugZen->updateFileAfterCreate($bugID, $data->rawdata);
            list($laneID, $columnID) = $this->bugZen->getKanbanVariable($data->rawdata, $output);
            $this->bugZen->updateKanbanAfterCreate($bug, $laneID, $columnID, $from);

            $this->bugZen->addAction4Create($bug, $output, $from);

            $message = $this->executeHooks($bugID);
            if($message) $this->lang->saveSuccess = $message;

            $executionID = $bug->execution ? $bug->execution : zget($output, 'executionID', $this->session->execution);
            $response = $this->bugZen->responseAfterCreate($bugID, (int)$executionID, $output);
            return $this->send($response);
        }

        $productID      = $this->product->saveVisitState($productID, $this->products);
        $currentProduct = $this->product->getByID($productID);
        $this->bugZen->setMenu4Create($productID, $branch, $output);

        /* 初始化一个bug对象，尽可能把属性都绑定到bug对象上，extract() 出来的变量除外。 */
        /* Init bug, give bug as many variables as possible, except for extract variables. */
        $fields = array('productID' => $productID, 'branch' => $branch, 'title' => ($from == 'sonarqube' ? $_COOKIE['sonarqubeIssue'] : ''), 'assignedTo' => (isset($currentProduct->QD) ? $currentProduct->QD : ''));
        $bug = $this->bugZen->initBug($fields);

        $bug = $this->bugZen->setOptionMenu($bug, $currentProduct);

        /* 处理复制bug，从用例、测试单、日志转bug。 */
        /* Handle copy bug, bug from case, testtask, todo. */
        $bug = $this->bugZen->extractObjectFromExtras($bug, $output);

        /* 获取分支、版本、需求、项目、执行、产品、项目的模式，构造$this->view。*/
        /* Get branches, builds, stories, project, projects, executions, products, project model and build create form. */
        $this->bugZen->buildCreateForm($bug, $output, $from);
    }

    /**
     * Batch create.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $moduleID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function batchCreate($productID, $branch = '', $executionID = 0, $moduleID = 0, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            /* Batch create bugs. */
            $actions = $this->bug->batchCreate($productID, $branch, $extra);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Return bug id list when call the API. */
            if($this->viewType == 'json')
            {
                $bugIDList = array_keys($actions);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $bugIDList));
            }

            /* Set cookie. */
            helper::setcookie('bugModule', 0, 0);

            /* If link from no head then reload. */
            if(isonlybody() && $executionID) $this->bugZen->responseInModal($executionID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('bug', 'browse', "productID={$productID}&branch=$branch&browseType=unclosed&param=0&orderBy=id_desc"), 'parent'));
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveVisitState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->qa->setMenu($this->products, $productID, $branch);

        /* If executionID is setted, get builds and stories of this execution. */
        if($executionID)
        {
            $builds    = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased', $executionID, 'execution');
            $stories   = $this->story->getExecutionStoryPairs($executionID);
            $execution = $this->loadModel('execution')->getById($executionID);
            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'bug');
                $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->executionType = $execution->type;
                $this->view->regionID      = $regionID;
                $this->view->laneID        = $laneID;
                $this->view->regionPairs   = $regionPairs;
                $this->view->lanePairs     = $lanePairs;
            }
        }
        else
        {
            $builds  = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased');
            $stories = $this->story->getProductStoryPairs($productID, $branch);
        }

        /* Get titles from uploaded images. */
        if($this->session->bugImagesFile)
        {
            $files = $this->session->bugImagesFile;
            foreach($files as $fileName => $file)
            {
                $title = $file['title'];
                $titles[$title] = $fileName;
            }
            $this->view->titles = $titles;
        }

        /* Set custom. */
        $product = $this->product->getById($productID);
        foreach(explode(',', $this->config->bug->list->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->bug->$field;
        }

        /* If type of the product is branch or platform, append type of the product to the custom fields. */
        if($product->type != 'normal')
        {
            $this->config->bug->custom->batchCreateFields = sprintf($this->config->bug->custom->batchCreateFields, $product->type);
        }
        else
        {
            $this->config->bug->custom->batchCreateFields = trim(sprintf($this->config->bug->custom->batchCreateFields, ''), ',');
        }

        /* Set display fields. */
        $showFields = $this->config->bug->custom->batchCreateFields;
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        /* Get project information and set language item base on project model. */
        $projectID = isset($execution) ? $execution->project : 0;
        $projectID = $this->lang->navGroup->bug == 'project' ? $this->session->project : $projectID;
        $project   = $this->loadModel('project')->getByID($projectID);
        if(isset($project->model) && $project->model == 'kanban') $customFields['execution'] = $this->lang->bug->kanban;

        /* Get branches. */
        if($executionID)
        {
            $productBranches = $product->type != 'normal' ? $this->execution->getBranchByProduct($productID, $executionID) : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;

        $this->view->project          = $project;
        $this->view->product          = $product;
        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->builds           = $builds;
        $this->view->users            = $this->user->getPairs('devfirst|noclosed');
        $this->view->projects         = array('' => '') + $this->product->getProjectPairsByProduct($productID, $branch ? "0,$branch" : 0);
        $this->view->projectID        = $projectID;
        $this->view->executions       = array('' => '') + $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : 0, 'id_desc', $projectID, 'multiple,stagefilter');
        $this->view->executionID      = $executionID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $branch === 'all' ? 0 : $branch);
        $this->view->moduleID         = $moduleID;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;

        $this->display();
    }

    /**
     * 查看一个bug。
     * View a bug.
     *
     * @param  string $bugID
     * @param  string $form
     * @access public
     * @return void
     */
    public function view(string $bugID, string $from = 'bug')
    {
        /* Judge bug exits or not. */
        $bugID = (int)$bugID;
        $bug   = $this->bug->getById($bugID, true);
        if(!$bug) return print(js::error($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));

        $this->session->set('storyList', '', 'product');
        $this->session->set('projectList', $this->app->getURI(true) . "#app={$this->app->tab}", 'project');
        $this->bugZen->checkBugExecutionPriv($bug);

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        if(!isonlybody()) $this->bugZen->setMenu4View($bug);
        $this->bugZen->setView4View($bug, $from);
        $this->display();
    }

    /**
     * 更新 bug 信息。
     * Edit a bug.
     *
     * @param  string $bugID
     * @param  bool   $comment true|false
     * @param  string $kanbanGroup
     * @access public
     * @return void
     */
    public function edit(string $bugID, bool $comment = false, string $kanbanGroup = 'default')
    {
        $bugID = (int)$bugID;

        if(!empty($_POST))
        {
            $oldBug   = $this->bug->getByID($bugID);
            $formData = form::data($this->config->bug->form->edit);
            $bug      = $this->bugZen->prepareEditExtras($formData, $oldBug);
            if(!$bug) return $this->send($this->bugZen->errorEdit());

            $changes = array();
            if(!$comment)
            {
                $changes = $this->bug->update($bug, $oldBug);
                if($changes === false) return $this->send($this->bugZen->errorEdit());
            }

            $this->bugZen->processAfterEdit($bugID, $this->post->comment, $changes);

            $this->executeHooks($bugID);

            /* Get response after editing bug. */
            return $this->send($this->bugZen->responseAfterOperate($bugID, $changes, $kanbanGroup));
        }

        $bug = $this->bug->getByID($bugID);

        $this->bugZen->checkBugExecutionPriv($bug);

        $this->bugZen->setEditMenu($bug);

        $this->bugZen->buildEditForm($bug);
    }

    /**
     * Batch edit bug.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $branch = 0)
    {
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
        }

        if($this->post->titles)
        {
            $allChanges = $this->bug->batchUpdate();

            foreach($allChanges as $bugID => $changes)
            {
                if(empty($changes)) continue;

                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);

                $bug = $this->bug->getById($bugID);
                if($bug->toTask != 0)
                {
                    foreach($changes as $change)
                    {
                        if($change['field'] == 'status')
                        {
                            $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                            $cancelURL  = $this->server->HTTP_REFERER;
                            return print(js::confirm(sprintf($this->lang->bug->remindTask, $bug->task), $confirmURL, $cancelURL, 'parent', 'parent'));
                        }
                    }
                }
            }
            return print(js::locate($this->session->bugList, 'parent'));
        }

        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));
        /* Initialize vars.*/
        $bugIDList = array_unique($this->post->bugIDList);
        $bugs      = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIDList)->fetchAll('id');

        /* The bugs of a product. */
        if($productID)
        {
            $product = $this->product->getByID($productID);
            $branchProduct = $product->type == 'normal' ? false : true;

            /* Set plans. */
            foreach($bugs as $bug)
            {
                $projectID  = $bug->project;
                $plans      = $this->loadModel('productplan')->getPairs($productID, $bug->branch, '', true);
                $plans      = array('' => '', 'ditto' => $this->lang->bug->ditto) + $plans;
                $bug->plans = $plans;
            }

            /* Set branches and modules. */
            $branches        = 0;
            $branchTagOption = array();
            $modules         = array();
            if($product->type != 'normal')
            {
                $branches = $this->loadModel('branch')->getList($productID, 0 ,'all');
                foreach($branches as $branchInfo) $branchTagOption[$productID][$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
                $branches = array_keys($branches);
            }

            $modulePairs = $this->tree->getOptionMenu($productID, 'bug', 0, $branches);
            $modules[$productID] = $product->type != 'normal' ? $modulePairs : array(0 => $modulePairs);

            /* Set product menu. */
            $this->qa->setMenu($this->products, $productID, $branch);

            $project = $this->loadModel('project')->getByID($projectID);
            if($product->shadow and isset($project) and empty($project->multiple))
            {
                $this->config->bug->custom->batchEditFields = str_replace('productplan', '', $this->config->bug->custom->batchEditFields);
                $this->config->bug->list->customBatchEditFields = str_replace(',productplan,', ',', $this->config->bug->list->customBatchEditFields);
            }

            $this->view->title      = $product->name . $this->lang->colon . "BUG" . $this->lang->bug->batchEdit;
            $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
            $this->view->plans      = $plans;
        }
        /* The bugs of my. */
        else
        {
            $branchProduct   = false;
            $productIdList   = array();
            $branchTagOption = array();
            foreach($bugs as $bug) $productIdList[$bug->product] = $bug->product;
            $productList = $this->product->getByIdList($productIdList);
            foreach($productList as $product)
            {
                $branches = 0;
                if($product->type != 'normal')
                {
                    $branches = $this->loadModel('branch')->getList($product->id, 0 ,'all');
                    foreach($branches as $branchInfo) $branchTagOption[$product->id][$branchInfo->id] = '/' . $product->name . '/' . $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');

                    $branches      = array_keys($branches);
                    $branchProduct = true;
                }

                $modulePairs = $this->tree->getOptionMenu($product->id, 'bug', 0, $branches);
                $modules[$product->id] = $product->type != 'normal' ? $modulePairs : array(0 => $modulePairs);
            }

            $this->app->loadLang('my');
            $this->lang->task->menu = $this->lang->my->menu->work;
            $this->lang->my->menu->work['subModule'] = 'bug';

            $this->view->position[]  = html::a($this->createLink('my', 'bug'), $this->lang->my->bug);
            $this->view->title       = "BUG" . $this->lang->bug->batchEdit;
            $this->view->plans       = $this->loadModel('productplan')->getPairs($product->id, $branch);
            $this->view->productList = $productList;
        }

        /* Judge whether the editedBugs is too large and set session. */
        $countInputVars  = count($bugs) * (count(explode(',', $this->config->bug->custom->batchEditFields)) + 2);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Set Custom. */
        foreach(explode(',', $this->config->bug->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->bug->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->bug->custom->batchEditFields;

        $branchIdList    = array();
        $projectIdList   = array();
        $executionIdList = array();
        $productBugList  = array();
        foreach($bugs as $bug)
        {
            $projectIdList[$bug->project]     = $bug->project;
            $executionIdList[$bug->execution] = $bug->execution;

            $branchIdList[$bug->product][$bug->branch] = $bug->branch;

            if(!isset($modules[$bug->product][$bug->branch]) and isset($modules[$bug->product])) $modules[$bug->product][$bug->branch] = $modules[$bug->product][0] + $this->tree->getModulesName($bug->module);

            $bugProduct  = isset($productList) ? $productList[$bug->product] : $product;
            $branch      = $bug->branch > 0 ? $bug->branch . ',0' : '0';
            $branch      = $bugProduct->type == 'branch' ? $branch : '';
            if(!isset($productBugList[$bug->product][$bug->branch])) $productBugList[$bug->product][$bug->branch] = $this->bug->getProductBugPairs($bug->product, $branch);
        }

        /* Get assigned to member. */
        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $this->loadModel('project');
            $this->loadModel('execution');

            $productMembers   = array();
            $projectMembers   = array();
            $executionMembers = array();
            if($productID)
            {
                $branchList = zget($branchIdList, $productID, array());
                foreach($branchList as $branchID)
                {
                    $members = $this->bug->getProductMemberPairs($productID, $branchID);
                    $productMembers[$productID][$branchID] = array_filter($members);
                }
            }
            else
            {
                foreach($productIdList as $id)
                {
                    $branchList = zget($branchIdList, $id, array());
                    foreach($branchList as $branchID)
                    {
                        $members = $this->bug->getProductMemberPairs($id, $branchID);
                        $productMembers[$id][$branchID] = array_filter($members);
                    }
                }
            }

            $projectMemberGroup = $this->project->getTeamMemberGroup($projectIdList);
            $projectMembers     = array();
            foreach($projectIdList as $projectID)
            {
                $projectTeam = zget($projectMemberGroup, $projectID, array());
                if(empty($projectTeam)) $projectMembers[$projectID] = array();
                foreach($projectTeam as $user)
                {
                    $projectMembers[$projectID][$user->account] = $user->realname;
                }
            }

            $executionMemberGroup = $this->execution->getMembersByIdList($executionIdList);
            $executionMembers     = array();
            foreach($executionIdList as $executionID)
            {
                $executionTeam = zget($executionMemberGroup, $executionID, array());
                if(empty($executionTeam)) $executionMemberGroup[$executionID] = array();
                foreach($executionTeam as $user)
                {
                    $executionMembers[$executionID][$user->account] = $user->realname;
                }
            }

            $this->view->productMembers   = $productMembers;
            $this->view->projectMembers   = $projectMembers;
            $this->view->executionMembers = $executionMembers;
        }

        /* Set users. */
        $users = $this->user->getPairs('devfirst');
        $users = array('' => '', 'ditto' => $this->lang->bug->ditto) + $users;

        /* Assign. */
        $this->view->productID        = $productID;
        $this->view->branchProduct    = $branchProduct;
        $this->view->severityList     = array('ditto' => $this->lang->bug->ditto) + $this->lang->bug->severityList;
        $this->view->typeList         = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->typeList;
        $this->view->priList          = array('0' => '', 'ditto' => $this->lang->bug->ditto) + $this->lang->bug->priList;
        $this->view->resolutionList   = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->resolutionList;
        $this->view->statusList       = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->statusList;
        $this->view->bugs             = $bugs;
        $this->view->branch           = $branch;
        $this->view->users            = $users;
        $this->view->modules          = $modules;
        $this->view->branchTagOption  = $branchTagOption;
        $this->view->productBugList   = $productBugList;

        $this->display();
    }

    /**
     * 指派bug。
     * Update assign of bug.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function assignTo(int $bugID)
    {
        /* Get old bug, and check privilege of the execution. */
        $bug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($bug);

        /* Set menu. */
        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        if(!empty($_POST))
        {
            /* Init bug data. */
            $bug = form::data($this->config->bug->form->assignTo)
                ->add('id', $bugID)
                ->get();

            $this->bug->assign($bug, $this->post->comment);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($bugID);

            /* Get response after assigning bug. */
            return $this->send($this->bugZen->responseAfterOperate($bugID, $changes));
        }

        /* Get assigned to member. */
        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $users = $this->bugZen->getAssignedToPairs($bug);
        }
        else
        {
            $users = $this->loadModel('user')->getPairs('devfirst|noclosed');
        }

        /* Show the variables associated. */
        $this->view->title   = $this->products[$bug->product] . $this->lang->colon . $this->lang->bug->assignedTo;
        $this->view->users   = $users;
        $this->view->bug     = $bug;
        $this->view->bugID   = $bugID;
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
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
        if($this->post->bugIDList)
        {
            $bugIdList     = $this->post->bugIDList;
            $bugIdList     = array_unique($bugIdList);
            $oldBugs       = $this->bug->getByIdList($bugIdList);
            $skipBugIdList = '';
            unset($_POST['bugIDList']);

            /* Remove condition mismatched bugs. */
            foreach($bugIdList as $key => $bugID)
            {
                $oldBug = $oldBugs[$bugID];
                if($branchID == $oldBug->branch)
                {
                    unset($bugIdList[$key]);
                }
                elseif($branchID != $oldBug->branch and !empty($oldBug->module))
                {
                    $skipBugIdList .= '[' . $bugID . ']';
                    unset($bugIdList[$key]);
                }
            }

            if(!empty($skipBugIdList))
            {
                echo js::alert(sprintf($this->lang->bug->noSwitchBranch, $skipBugIdList));
            }

            $allChanges = $this->bug->batchChangeBranch($bugIdList, $branchID, $oldBugs);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Batch change the module of bug.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);
            unset($_POST['bugIDList']);
            $allChanges = $this->bug->batchChangeModule($bugIDList, $moduleID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Batch change the plan of bug.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function batchChangePlan($planID)
    {
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);
            unset($_POST['bugIDList']);
            $allChanges = $this->bug->batchChangePlan($bugIDList, $planID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Batch update assign of bug.
     *
     * @param  int     $objectID  projectID|executionID
     * @param  string  $type      execution|project|product|my
     * @access public
     * @return void
     */
    public function batchAssignTo($objectID, $type = 'execution')
    {
        if(!empty($_POST) && isset($_POST['bugIDList']))
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);
            unset($_POST['bugIDList']);
            foreach($bugIDList as $bugID)
            {
                $this->loadModel('action');
                $changes = $this->bug->assign($bugID);
                if(dao::isError()) return print(js::error(dao::getError()));
                $actionID = $this->action->create('bug', $bugID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        if($type == 'product' || $type == 'my') return print(js::reload('parent'));
        if($type == 'execution') return print(js::locate($this->createLink('execution', 'bug', "executionID=$objectID")));
        if($type == 'project')   return print(js::locate($this->createLink('project', 'bug', "projectID=$objectID")));
    }

    /**
     * confirm a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function confirmBug($bugID, $extra = '')
    {
        $bug = $this->bug->getById($bugID);
        if(!empty($_POST))
        {
            $changes = $this->bug->confirm($bugID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'bugConfirmed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($bugID);

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            if(isonlybody())
            {
                $regionID = zget($output, 'regionID', 0);
                $this->bugZen->responseInModal($bug->execution, '', $regionID);
            }

            return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $productID = $bug->product;
        $this->bugZen->checkBugExecutionPriv($bug);
        $this->qa->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->confirmBug;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->confirmBug;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noclosed', $bug->assignedTo);
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Batch confirm bugs.
     *
     * @access public
     * @return void
     */
    public function batchConfirm()
    {
        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIDList = array_unique($this->post->bugIDList);
        $this->bug->batchConfirm($bugIDList);
        if(dao::isError()) return print(js::error(dao::getError()));
        foreach($bugIDList as $bugID) $this->action->create('bug', $bugID, 'bugConfirmed');
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * 解决bug。
     * Resolve a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function resolve(int $bugID, string $extra = '')
    {
        /* Get old bug, and check privilege of the execution. */
        $bug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($bug);

        if(!empty($_POST))
        {
            /* Parse extra, and get variables. */
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            /* Init bug data. */
            $bug = $this->bugZen->buildBugForResolve($bug, (int)$this->post->uid);

            $changes = $this->bug->resolve($bug, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($bug->id);

            /* Get response after resolving. */
            $regionID = zget($output, 'regionID', 0);
            return $this->send($this->bugZen->responseAfterOperate($bugID, $changes, '', $regionID));
        }

        /* Get users who is not closed and get assigned person. */
        $users      = $this->user->getPairs('noclosed');
        $assignedTo = $bug->openedBy;
        if(!isset($users[$assignedTo])) $assignedTo = $this->bug->getModuleOwner($bug->module, $bug->product);

        /* Remove 'Convert to story' from the solution list. */
        unset($this->lang->bug->resolutionList['tostory']);

        /* Set menu. */
        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        /* Show the variables associated. */
        $this->view->title          = $this->products[$bug->product] . $this->lang->colon . $this->lang->bug->resolve;
        $this->view->bug            = $bug;
        $this->view->users          = $users;
        $this->view->assignedTo     = $assignedTo;
        $this->view->executions     = $this->loadModel('product')->getExecutionPairsByProduct($bug->product, $bug->branch ? "0,{$bug->branch}" : 0, 'id_desc', $bug->project, 'stagefilter');
        $this->view->builds         = $this->loadModel('build')->getBuildPairs($bug->product, $bug->branch, 'withbranch,noreleased');
        $this->view->actions        = $this->loadModel('action')->getList('bug', $bugID);
        $this->view->execution      = $bug->execution ? $this->loadModel('execution')->getByID($bug->execution) : '';
        $this->display();
    }

    /**
     * Batch resolve bugs.
     *
     * @param  string    $resolution
     * @param  string    $resolvedBuild
     * @access public
     * @return void
     */
    public function batchResolve($resolution, $resolvedBuild = '')
    {
        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIDList = array_unique($this->post->bugIDList);
        $changes   = $this->bug->batchResolve($bugIDList, $resolution, $resolvedBuild);
        if(dao::isError()) return print(js::error(dao::getError()));

        foreach($changes as $bugID => $bugChanges)
        {
            $actionID = $this->action->create('bug', $bugID, 'Resolved', '', $resolution);
            $this->action->logHistory($actionID, $bugChanges);
        }

        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Activate a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function activate($bugID, $extra = '')
    {
        if(!empty($_POST))
        {
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            /* Activate bug. */
            $formData = form::data($this->config->bug->form->activate);
            $bug      = $this->bugZen->prepareActivate($formData, $bugID);
            $changes  = $this->bug->activate($bug, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Save attachments and record logs. */
            $files      = $this->loadModel('file')->saveUpload('bug', $bugID);
            $fileAction = !empty($files) ? $this->lang->addFiles . implode(',', $files) . "\n" : '';
            $actionID   = $this->action->create('bug', $bugID, 'Activated', $fileAction . $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($bugID);

            if(isonlybody())
            {
                $regionID = zget($output, 'regionID', 0);
                $bug      = $this->bug->getById($bugID);
                $this->bugZen->responseInModal($bug->execution, '', $regionID);
            }

            return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('task', 'view', "bugID={$bugID}"), 'closeModal' => true);
        }

        $this->bugZen->buildActivateForm($bugID);
    }

    /**
     * 关闭一个bug。
     * Close a bug.
     *
     * @param  string $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function close(string $bugID, string $extra = '')
    {
        $oldBug = $this->bug->getByID((int)$bugID);

        if(!empty($_POST))
        {
            $data = form::data($this->config->bug->form->close);

            $bug = $this->bugZen->prepareCloseExtras($data, $bugID);
            $this->bug->close($bug, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));
            $this->bug->afterClose($bug, $oldBug);

            $this->executeHooks($bugID);

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            $regionID = zget($output, 'regionID', 0);

            return $this->send($this->bugZen->responseAfterOperate($bugID, $changes, $kanbanGroup));
        }

        $this->bugZen->checkBugExecutionPriv($oldBug);
        $this->bugZen->buildCloseForm($oldBug);
    }

    /**
     * Link related bugs.
     *
     * @param  int    $bugID
     * @param  string $browseType
     * @param  string $excludeBugs
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs($bugID, $browseType = '', $excludeBugs = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get bug and queryID. */
        $bug     = $this->bug->getById($bugID);
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->bugZen->checkBugExecutionPriv($bug);

        /* Set the menu. */
        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        /* Hide plan and product in no product project. */
        if($bug->project and $this->app->tab != 'qa')
        {
            $project = $this->loadModel('project')->getByID($bug->project);
            if(!$project->hasProduct)
            {
                unset($this->config->bug->search['fields']['product']);
                if(!$project->multiple)
                {
                    unset($this->config->bug->search['fields']['execution']);
                    unset($this->config->bug->search['fields']['plan']);
                }
            }
        }

        /* Build the search form. */
        $actionURL = $this->createLink('bug', 'linkBugs', "bugID=$bugID&browseType=bySearch&excludeBugs=$excludeBugs&queryID=myQueryID", '', true);
        $this->bug->buildSearchForm($bug->product, $this->products, $queryID, $actionURL);

        /* Get bugs to link. */
        $bugs2Link = $this->bug->getBugs2Link($bugID, $browseType, $queryID, $pager, $excludeBugs);

        /* Assign. */
        $this->view->title      = $this->lang->bug->linkBugs . "BUG #$bug->id $bug->title - " . $this->products[$bug->product];
        $this->view->position[] = html::a($this->createLink('product', 'view', "productID=$bug->product"), $this->products[$bug->product]);
        $this->view->position[] = html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bug->title);
        $this->view->position[] = $this->lang->bug->linkBugs;
        $this->view->bug        = $bug;
        $this->view->bugs2Link  = $bugs2Link;
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->display();
    }

    /**
     * Batch close bugs.
     *
     * @param  int    $releaseID
     * @param  string $viewType
     * @access public
     * @return void
     */
    public function batchClose($releaseID = '', $viewType = '')
    {
        if($releaseID) $this->post->bugIDList = $this->post->unlinkBugs;
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);

            /* Reset $_POST. Do not unset that because the function of close need that in model. */
            $_POST = array();

            $closedBugs = array();
            $bugs = $this->bug->getByIdList($bugIDList);
            foreach($bugs as $bugID => $bug)
            {
                if($bug->status != 'resolved')
                {
                    if($bug->status != 'closed') $skipBugs[$bugID] = $bugID;
                    continue;
                }

                $changes = $this->bug->close($bugID);

                $actionID = $this->action->create('bug', $bugID, 'Closed');
                $this->action->logHistory($actionID, $changes);
                $closedBugs[] = $bugID;
            }

            $this->dao->update(TABLE_BUG)->set('assignedTo')->eq('closed')->where('id')->in($closedBugs)->exec();

            $this->loadModel('score')->create('ajax', 'batchOther');
            if(isset($skipBugs)) echo js::alert(sprintf($this->lang->bug->skipClose, implode(',', $skipBugs)));
            if($viewType)
            {
                return print(js::locate($this->createLink($viewType, 'view', "releaseID=$releaseID&type=bug"), 'parent'));
            }
        }
        return print(js::reload('parent'));
    }

    /**
     * Batch activate bugs.
     *
     * @access public
     * @return void
     */
    public function batchActivate($productID, $branch = 0)
    {
        if($this->post->statusList)
        {
            $activateBugs = $this->bug->batchActivate();
            foreach($activateBugs as $bugID => $bug) $this->action->create('bug', $bugID, 'Activated', $bug['comment']);
            $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->bugList, 'parent'));
        }

        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIDList = array_unique($this->post->bugIDList);
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIDList)->fetchAll('id');

        $this->qa->setMenu($this->products, $productID, $branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchActivate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->batchActivate;

        $this->view->bugs    = $bugs;
        $this->view->users   = $this->user->getPairs();
        $this->view->builds  = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased');

        $this->display();
    }

    /**
     * Confirm story change.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function confirmStoryChange($bugID)
    {
        $bug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($bug);
        $this->dao->update(TABLE_BUG)->set('storyVersion')->eq($bug->latestStoryVersion)->where('id')->eq($bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'confirmed', '', $bug->latestStoryVersion);
        return print(js::reload('parent'));
    }

    /**
     * 删除 bug。
     * Delete a bug.
     *
     * @param  string $bugID
     * @param  string $confirm yes|no
     * @param  string $from    taskkanban
     * @access public
     * @return void
     */
    public function delete(string $bugID, string $confirm = 'no', string $from = '')
    {
        if($confirm == 'no') return $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->bug->confirmDelete, 'confirmed' =>inlink('delete', "bugID=$bugID&confirm=yes&from=$from"))));

        $bug = $this->bug->getByID($bugID);

        $this->bug->delete(TABLE_BUG, $bugID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        /* 如果 bug 转任务，删除 bug 时确认是否更新任务状态。*/
        /* If the bug has been transfered to a task, confirm to update task when delete the bug. */
        if($bug->toTask)
        {
            $result = $this->bugZen->confirm2UpdateTask($bugID, $bug->toTask);
            if(is_array($result)) return $this->send($result);
        }

        $this->executeHooks($bugID);

        return $this->send($this->bugZen->responseAfterDelete($bug, $from));
    }

    /**
     * AJAX: get bugs of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id       the id of the select control.
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserBugs($userID = '', $id = '' , $appendID = 0)
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;
        $bugs    = $this->bug->getUserBugPairs($account, true, 0, '', '', $appendID);

        if($id) return print(html::select("bugs[$id]", $bugs, '', 'class="form-control"'));
        return print(html::select('bug', $bugs, '', 'class=form-control'));
    }

    /**
     * AJAX: Get bug owner of a module.
     *
     * @param  int    $moduleID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function ajaxGetModuleOwner($moduleID, $productID = 0)
    {
        $account  = $this->bug->getModuleOwner($moduleID, $productID);
        $realName = '';
        if(!empty($account))
        {
            $user        = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($account)->fetch();
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $realName = $firstLetter . ($user->realname ? $user->realname : $account);
        }
        return print(json_encode(array($account, $realName)));
    }

    /**
     * AJAX: get team members of the executions as assignedTo list.
     *
     * @param  int    $executionID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxLoadAssignedTo($executionID, $selectedUser = '')
    {
        $executionMembers = $this->user->getTeamMemberPairs($executionID, 'execution');

        $execution = $this->loadModel('execution')->getByID($executionID);
        if(empty($selectedUser)) $selectedUser = $execution->QD;

        return print(html::select('assignedTo', $executionMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get team members of the latest executions of a product as assignedTo list.
     *
     * @param  int    $productID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxLoadExecutionTeamMembers($productID, $selectedUser = '')
    {
        $productMembers = $this->bug->getProductMemberPairs($productID);

        return print(html::select('assignedTo', $productMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get all users as assignedTo list.
     *
     * @param  string $selectedUser
     * @param  string $params   noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname|outside|inside|all, can be sets of theme
     * @access public
     * @return string
     */
    public function ajaxLoadAllUsers($selectedUser = '', $params = 'devfirst|noclosed')
    {
        $allUsers = $this->loadModel('user')->getPairs($params);

        return print(html::select('assignedTo', $allUsers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get actions of a bug. for web app.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function ajaxGetDetail($bugID)
    {
        $this->view->actions = $this->loadModel('action')->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Get data to export
     *
     * @param  string $productID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $browseType = '', $executionID = 0)
    {
        if($_POST)
        {
            $this->loadModel('transfer');
            $this->session->set('bugTransferParams', array('productID' => $productID, 'executionID' => $executionID, 'branch' => 'all'));
            if(!$productID or $browseType == 'bysearch')
            {
                $this->config->bug->datatable->fieldList['module']['dataSource']['method'] = 'getAllModulePairs';
                $this->config->bug->datatable->fieldList['module']['dataSource']['params'] = 'bug';

                if($executionID)
                {
                    $object    = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
                    $projectID = $object->type == 'project' ? $object->id : $object->parent;
                    $this->config->bug->datatable->fieldList['project']['dataSource']   = array('module' => 'project', 'method' => 'getPairsByIdList', 'params' => $projectID);
                    $this->config->bug->datatable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs', 'params' => $projectID);
                }
            }

            $this->transfer->export('bug');
            $this->fetch('file', 'export2' . $_POST['fileType'], $_POST);
        }
        $product = $this->loadModel('product')->getByID($productID);
        if(isset($product->type) and $product->type == 'normal') $this->config->bug->exportFields = str_replace('branch,', '', $this->config->bug->exportFields);

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            if(empty($execution->multiple)) $this->config->bug->exportFields = str_replace('execution,', '', $this->config->bug->exportFields);
            if(!empty($product->shadow)) $this->config->bug->exportFields = str_replace('product,', '', $this->config->bug->exportFields);
        }

        $fileName = $this->lang->bug->common;
        if($executionID)
        {
            $executionName = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch('name');
            $fileName      = $executionName . $this->lang->dash . $fileName;
        }
        else
        {
            $productName = !empty($product->name) ? $product->name : '';
            $browseType  = isset($this->lang->bug->featureBar['browse'][$browseType]) ? $this->lang->bug->featureBar['browse'][$browseType] : zget($this->lang->bug->moreSelects, $browseType, '');

            $fileName = $productName . $this->lang->dash . $browseType . $fileName;
        }

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->bug->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * Ajax get bug by ID.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function ajaxGetByID($bugID)
    {
        $bug = $this->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
        $realname = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($bug->assignedTo)->fetch('realname');

        $bug->assignedTo = $bug->assignedTo == 'closed' ? 'Closed' : $bug->assignedTo;
        $bug->assignedTo = $realname ?: $bug->assignedTo;
        return print(json_encode($bug));
    }

    /**
     * Ajax get bug field options for auto test.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetBugFieldOptions($productID, $executionID = 0)
    {
        $modules  = $this->loadModel('tree')->getOptionMenu($productID, 'bug');
        $builds   = $this->loadModel('build')->getBuildPairs($productID, 'all', 'noreleased', $executionID, 'execution');
        $type     = $this->lang->bug->typeList;
        $pri      = $this->lang->bug->priList;
        $severity = $this->lang->bug->severityList;

        return print(json_encode(array('modules' => $modules, 'categories' => $type, 'versions' => $builds, 'severities' => $severity, 'priorities' => $pri)));
    }

    /**
     * Drop menu page.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($productID, $module, $method, $extra = '')
    {
        $products = array();
        if(!empty($extra)) $products = $this->product->getProducts($extra, 'all', 'program desc, line desc, ');

        if($this->config->systemMode == 'ALM')
        {
            $this->view->programs = $this->loadModel('program')->getPairs(true);
            $this->view->lines    = $this->product->getLinePairs();
        }

        $this->view->link      = $this->product->getProductLink($module, $method, $extra);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;
        $this->view->products  = $products;
        $this->view->projectID = $this->session->project;
        $this->display();
    }

    /**
     * Ajax get product members.
     *
     * @param  int    $productID
     * @param  string $selectedUser
     * @param  int    $branchID
     * @access public
     * @return string
     */
    public function ajaxGetProductMembers(int $productID, string $selectedUser = '', int $branchID = 0)
    {
        $productMembers = $this->bug->getProductMemberPairs($productID, $branchID);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->loadModel('user')->getPairs('devfirst|noclosed');

        return print(html::select('assignedTo', $productMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * Ajax get product bugs.
     *
     * @param  int     $productID
     * @param  int     $bugID
     * @access public
     * @return string
     */
    public function ajaxGetProductBugs($productID, $bugID)
    {
        $product     = $this->loadModel('product')->getById($productID);
        $bug         = $this->bug->getById($bugID);
        $branch      = $bug->branch > 0 ? $bug->branch . ',0' : '0';
        $branch      = $product->type == 'branch' ? $branch : '';
        $productBugs = $this->bug->getProductBugPairs($productID, $branch);
        unset($productBugs[$bugID]);

        return print(html::select('duplicateBug', $productBugs, '', "class='form-control' placeholder='{$this->lang->bug->duplicateTip}'"));
    }

    /**
     * Ajax get project team members.
     *
     * @param  int    $projectID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxGetProjectTeamMembers($projectID, $selectedUser = '')
    {
        $users       = $this->loadModel('user')->getPairs('noclosed|all');
        $teamMembers = empty($projectID) ? array() : $this->loadModel('project')->getTeamMemberPairs($projectID);
        foreach($teamMembers as $account => $member) $teamMembers[$account] = $users[$account];

        return print(html::select('assignedTo', $teamMembers, $selectedUser, 'class="form-control"'));
    }


    /**
     * Ajax get execution lang.
     *
     * @param  int  $projectID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionLang($projectID)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if($project->model == 'kanban') return print($this->lang->bug->kanban);
        return print($this->lang->bug->execution);
    }

    /**
     * Ajax get released builds.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return string
     */
    public function ajaxGetReleasedBuilds($productID, $branch = 'all')
    {
        $releasedBuilds = $this->loadModel('release')->getReleasedBuilds($productID, $branch);

        return print(helper::jsonEncode($releasedBuilds));
    }
}
