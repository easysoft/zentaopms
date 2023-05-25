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
     * 查看一个bug。
     * View a bug.
     *
     * @param  int    $bugID
     * @param  string $form
     * @access public
     * @return void
     */
    public function view(int $bugID, string $from = 'bug')
    {
        /* Judge bug exits or not. */
        $bug = $this->bug->getById($bugID, true);
        if(!$bug) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->notFound, 'confirmed' => $this->createLink('qa', 'index'))));

        $this->session->set('storyList', '', 'product');
        $this->session->set('projectList', $this->app->getURI(true) . "#app={$this->app->tab}", 'project');
        $this->bugZen->checkBugExecutionPriv($bug);

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        if(!isonlybody()) $this->bugZen->setMenu4View($bug);

        $bugID     = $bug->id;
        $productID = $bug->product;
        $product   = $this->product->getByID($productID);
        $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);

        $projects = $this->product->getProjectPairsByProduct($productID, (string)$bug->branch);
        $this->session->set("project", key($projects), 'project');

        $this->executeHooks($bugID);

        $this->view->title       = "BUG #$bug->id $bug->title - " . $product->name;
        $this->view->product     = $product;
        $this->view->project     = $this->loadModel('project')->getByID($bug->project);
        $this->view->bug         = $bug;
        $this->view->bugModule   = empty($bug->module) ? '' : $this->tree->getById($bug->module);
        $this->view->modulePath  = $this->loadModel('tree')->getParents($bug->module);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->branches    = $branches;
        $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $bug->branch, '');
        $this->view->builds      = $this->loadModel('build')->getBuildPairs($productID, 'all');
        $this->view->linkCommits = $this->loadModel('repo')->getCommitsByObject($bugID, 'bug');
        $this->view->actionList  = $this->loadModel('bug')->buildOperateMenu($bug, 'view');
        $this->display();
    }

    /**
     * 创建一个bug。
     * Create a bug.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $extras       Other params, for example, executionID=10,moduleID=10.
     * @access public
     * @return void
     */
    public function create(int $productID, string $branch = '', string $extras = '')
    {
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

            $this->bugZen->updateFileAfterCreate($bugID, $data->data);
            list($laneID, $columnID) = $this->bugZen->getKanbanVariable($data->data, $output);
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
     * 更新 bug 信息。
     * Edit a bug.
     *
     * @param  int    $bugID
     * @param  bool   $comment true|false
     * @param  string $kanbanGroup
     * @access public
     * @return void
     */
    public function edit(int $bugID, bool $comment = false, string $kanbanGroup = 'default')
    {
        if(!empty($_POST))
        {
            $oldBug   = $this->bug->getByID($bugID);
            $formData = form::data($this->config->bug->form->edit);
            $bug      = $this->bugZen->prepareEditExtras($formData, $oldBug);
            if(!$bug) return $this->send($this->bugZen->errorEdit());

            $changes = array();
            if(!$comment)
            {
                $changes = $this->bug->update($bug);
                if($changes === false) return $this->send($this->bugZen->errorEdit());
                $this->bug->afterUpdate($bug, $oldBug);
                if(dao::isError()) $this->send($this->bugZen->errorEdit());
            }

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
     * 确认 bug。
     * confirm a bug.
     *
     * @param  int    $bugID
     * @param  string $kanbanData fromColID=,toColID=,fromLaneID=,toLaneID=,regionID=
     * @access public
     * @return void
     */
    public function confirm(int $bugID, string $kanbanParams = '')
    {
        if(!empty($_POST))
        {
            /* 处理看板相关的参数。*/
            /* Process the params related to kanban. */
            $kanbanParams = str_replace(array(',', ' '), array('&', ''), $kanbanParams);
            parse_str($kanbanParams, $kanbanData);

            /* 构造 bug 的表单数据。*/
            /* Structure the bug form data. */
            $bug = form::data($this->config->bug->form->confirm)->add('id', $bugID)->setDefault('confirmed', 1)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->confirm['id'], $this->post->uid);

            $this->bug->confirm($bug, $kanbanData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 执行工作流的扩展动作。*/
            /* Execute extend actions.*/
            $message = $this->executeHooks($bugID);

            /* 弹窗内的返回。*/
            /* Respond in Modal. */
            if(isonlybody())
            {
                $regionID = zget($kanbanData, 'regionID', 0);
                $this->bugZen->responseInModal($bug->execution, '', $regionID);
            }

            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->createLink('bug', 'view', "bugID=$bugID")));
        }

        $bug = $this->bug->getByID($bugID);

        /* 检查 bug 所属执行的权限。*/
        /* Check privilege for execution of the bug. */
        $this->bugZen->checkBugExecutionPriv($bug);

        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        $this->view->title   = $this->products[$bug->product] . $this->lang->colon . $this->lang->bug->confirm;
        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noclosed', $bug->assignedTo);
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
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
     * 激活一个bug。
     * Activate a bug.
     *
     * @param  int    $bugID
     * @param  string $kanbanInfo   a string of kanban info, for example, 'fromColID=1,toColID=2,fromLaneID=1,toLaneID=2,regionID=1'.
     * @access public
     * @return void
     */
    public function activate(int $bugID, string $kanbanInfo = '')
    {
        if(!empty($_POST))
        {
            $kanbanInfo = str_replace(array(',', ' '), array('&', ''), $kanbanInfo);
            parse_str($kanbanInfo, $kanbanParams);

            $bugData = $this->bugZen->buildBugForActivate($bugID);
            if(!$bugData) return $this->send(array('result' => 'fail', 'message' => $this->lang->bug->error->notExist));

            $this->bug->activate($bugData, $kanbanParams);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(isonlybody())
            {
                $regionID = zget($kanbanParams, 'regionID', 0);
                $bug      = $this->bug->getBaseInfo($bugID);
                $this->bugZen->responseInModal($bug->execution, '', $regionID);
            }

            return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('bug', 'view', "bugID={$bugID}"), 'closeModal' => true);
        }

        $this->bugZen->buildActivateForm($bugID);
    }

    /**
     * 根据Bug的ID来关闭Bug。
     * Close a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function close(int $bugID, string $extra = '')
    {
        $oldBug = $this->bug->getByID((int)$bugID);

        if(!empty($_POST))
        {
            $data = form::data($this->config->bug->form->close);

            $bug = $this->bugZen->prepareCloseExtras($data, $bugID);
            $this->bug->close($bug);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            if($oldBug->execution)
            {
                $this->loadModel('kanban');
                if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bug->id);
                if(isset($output['toColID'])) $this->kanban->moveCard($bug->id, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
            }

            $this->executeHooks($bugID);

            return $this->send($this->bugZen->responseAfterOperate($bugID));
        }

        $this->bugZen->checkBugExecutionPriv($oldBug);
        $this->bugZen->buildCloseForm($oldBug);
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
     * 导出bug数据。
     * Get data to export
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function export(int $productID, string $browseType = '', int $executionID = 0)
    {
        if($_POST)
        {
            $this->session->set('bugTransferParams', array('productID' => $productID, 'executionID' => $executionID, 'branch' => 'all'));

            $this->bugZen->setExportDataSource($productID, $browseType, $executionID);

            $this->loadModel('transfer')->export('bug');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $product = $this->loadModel('product')->getByID($productID);
        $this->bugZen->setExportFields($executionID, $product);

        $this->view->fileName        = $this->bugZen->getExportFileName($executionID, $browseType, $product);
        $this->view->allExportFields = $this->config->bug->exportFields;
        $this->view->customExport    = true;
        $this->display('file', 'export');
    }

    /**
     * Bug 的统计报表。
     * The report page.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $branchID
     * @param  int    $moduleID
     * @param  string $chartType
     * @access public
     * @return void
     */
    public function report(int $productID, string $browseType, int $branchID, int $moduleID, string $chartType = 'default')
    {
        $this->loadModel('report');
        $this->view->charts = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc = 'getDataOf' . $chart;
                $chartData = $this->bug->$chartFunc();

                $this->view->charts[$chart] = $this->bugZen->mergeChartOption($chart, $chartType);
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        /* 如果是影子产品并且对应的项目不是多迭代项目，删掉迭代 Bug 数量报表*/
        /* Unset execution bugs report if the product is shadow product and corresponding project is not multiple. */
        $project = $this->loadModel('project')->getByShadowProduct($productID);
        if(!empty($project) && !$project->multiple) unset($this->lang->bug->report->charts['bugsPerExecution']);

        $this->qa->setMenu($this->products, $productID, $branchID);

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common . $this->lang->colon . $this->lang->bug->reportChart;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->branchID      = $branchID;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
        $this->display();
    }

    /**
     * 关联相关 bug。
     * Link related bugs.
     *
     * @param  int    $bugID
     * @param  bool   $bySearch
     * @param  string $excludeBugs
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs(int $bugID, bool $bySearch = false, string $excludeBugs = '', int $queryID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $bug = $this->bug->getByID($bugID);

        /* 检查 bug 所属执行的权限。*/
        /* Check privilege of bug 所属执行的权限。*/
        $this->bugZen->checkBugExecutionPriv($bug);

        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        $this->bugZen->buildSearchFormForLinkBugs($bug, $excludeBugs, $queryID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Assign. */
        $this->view->title     = $this->lang->bug->linkBugs . "BUG #$bug->id $bug->title {$this->lang->dash} " . $this->products[$bug->product];
        $this->view->bug       = $bug;
        $this->view->bugs2Link = $this->bug->getBugs2Link($bugID, $bySearch, $excludeBugs, $queryID, $pager);
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * 批量创建bug。
     * Batch create.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $executionID
     * @param  int    $moduleID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function batchCreate(int $productID, string $branch = '', int $executionID = 0, int $moduleID = 0, string $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $bugs = $this->bugZen->buildBugsForBatchCreate($productID, $branch, $this->session->bugImagesFile);

            /* Batch create bugs. */
            $actions = $this->bug->batchCreate($bugs, $productID, $output, $this->post->uploadImage, $this->session->bugImagesFile);

            helper::setcookie('bugModule', 0, 0);

            /* Remove upload image file and session. */
            if(!empty($this->post->uploadImage) and !empty($this->session->bugImagesFile))
            {
                $classFile = $this->app->loadClass('zfile');
                $file      = current($_SESSION['bugImagesFile']);
                $realPath  = dirname($file['realpath']);
                if(is_dir($realPath)) $classFile->removeDir($realPath);
                unset($_SESSION['bugImagesFile']);
            }

            $response = $this->bugZen->responseAfterBatchCreate($productID, $branch, $executionID, $actions ? $actions : array());
            return $this->send($response);
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveVisitState($productID, $this->products);
        $product   = $this->product->getById($productID);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->qa->setMenu($this->products, $productID, $branch);

        $this->bugZen->assignBatchCreateVars($executionID, $product, $branch, $output, $this->session->bugImagesFile);

        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;
        $this->view->users     = $this->user->getPairs('devfirst|noclosed');
        $this->view->moduleID  = $moduleID;
        $this->view->product   = $product;
        $this->view->productID = $product->id;
        $this->display();
    }

    /**
     * 批量编辑 bugs。
     * Batch edit bugs.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function batchEdit(int $productID = 0, string $branch = '0')
    {
        if($this->post->title)
        {
            /* Build bugs. */
            $bugs = $this->bugZen->buildBugsForBatchEdit();

            /* Batch update the bugs. */
            $toTaskIdList = $this->bug->batchUpdate();

            /* Get response and return. */
            return $this->send($this->bugZen->responseAfterBatchEdit($toTaskIdList));
        }

        /* If there is no bug ID, return to the previous step. */
        if(!$this->post->bugIdList) $this->locate($this->session->bugList);

        $this->bugZen->assignBatchEditVars($productID, $branch);

        /* Assign. */
        $this->view->productID        = $productID;
        $this->view->severityList     = $this->lang->bug->severityList;
        $this->view->typeList         = $this->lang->bug->typeList;
        $this->view->priList          = $this->lang->bug->priList;
        $this->view->resolutionList   = $this->lang->bug->resolutionList;
        $this->view->statusList       = $this->lang->bug->statusList;
        $this->view->branch           = $branch;
        $this->view->showFields       = $this->config->bug->custom->batchEditFields;
        $this->display();
    }

    /**
     * 批量修改bug分支。
     * Batch change branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function batchChangeBranch(int $branchID)
    {
        if($this->post->bugIDList)
        {
            $bugIdList = array_unique($this->post->bugIDList);
            $oldBugs   = $this->bug->getByIdList($bugIdList);

            /* Remove condition mismatched bugs. */
            $skipBugIdList = '';
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

            if(!empty($skipBugIdList)) echo js::alert(sprintf($this->lang->bug->noSwitchBranch, $skipBugIdList));

            $allChanges = $this->bug->batchChangeBranch($bugIdList, $branchID, $oldBugs);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record log. */
            $this->loadModel('action');
            foreach($allChanges as $bugID => $changes)
            {
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return array('load' => $this->session->bugList, 'closeModal' => true);
    }

    /**
     * 批量修改bug所属模块。
     * Batch change the module of bug.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule(int $moduleID)
    {
        if(!empty($_POST) && isset($_POST['bugIdList']))
        {
            $bugIdList = array_unique($this->post->bugIdList);
            foreach($bugIdList as $bugID)
            {
                $bug = new stdclass();
                $bug->id     = (int)$bugID;
                $bug->module = $moduleID;

                $this->bug->update($bug);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }
        return $this->send(array('result' => 'success', 'load' => $this->session->bugList));
    }

    /**
     * 批量修改bug计划。
     * Batch change the plan of bug.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function batchChangePlan(int $planID)
    {
        if($this->post->bugIDList)
        {
            $bugIdList = array_unique($this->post->bugIDList);
            $this->bug->batchChangePlan($bugIdList, $planID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return array('load' => $this->session->bugList, 'closeModal' => true);
    }

    /**
     * 批量变更bug的指派人。
     * Batch update assign of bug.
     *
     * @param  string  $assignedTo
     * @param  int     $objectID  projectID|executionID
     * @param  string  $type      execution|project|product|my
     * @access public
     * @return void
     */
    public function batchAssignTo(string $assignedTo, int $objectID, string $type = 'execution')
    {
        if(!empty($_POST) && isset($_POST['bugIdList']))
        {
            $bugIdList = array_unique($this->post->bugIdList);

            $bug = form::data($this->config->bug->form->assignTo)->remove('mailto')->get();
            foreach($bugIdList as $bugID)
            {
                $bug->id         = (int)$bugID;
                $bug->assignedTo = $assignedTo;
                $this->bug->assign($bug);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        if($type == 'execution') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('execution', 'bug', "executionID=$objectID")));
        if($type == 'project')   return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('project', 'bug', "projectID=$objectID")));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 批量确认BUG。
     * Batch confirm bugs.
     *
     * @access public
     * @return void
     */
    public function batchConfirm()
    {
        if(!empty($_POST) && isset($_POST['bugIdList']))
        {
            $bugIdList = array_unique($this->post->bugIdList);
            $bugs      = $this->bug->getByList($bugIDList);

            $bug = form::data($this->config->bug->form->confirm)->setDefault('confirmed', 1)->remove('pri,type,status,mailto')->get();
            foreach($bugIdList as $bugID)
            {
                if(!empty($bugs[$bugID]->confirmed)) continue;

                $bug->id         = (int)$bugID;
                $bug->confirmed  = 1;
                $bug->assignedTo = $this->app->user->account;
                $this->bug->confirm($bug);
                $message = $this->executeHooks($bugID);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        if(empty($message)) $message = $this->lang->saveSuccess;
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * Batch resolve bugs.
     *
     * @param  string    $resolution
     * @param  string    $resolvedBuild
     * @access public
     * @return void
     */
    public function batchResolve(string $resolution, string $resolvedBuild = '')
    {
        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIdList = array_unique($this->post->bugIDList);
        $oldBugs   = $this->bug->getByIdList($bugIdList);
        $bugIdList = $this->bugZen->batchResolveIdFilter($bugIdList, $oldBugs);
        list($modules, $productQD) = $this->bugZen->getBatchResolveVars($oldBugs);

        $changes = $this->bug->batchResolve($bugIdList, $resolution, $resolvedBuild, $oldBugs, $modules, $productQD);
        if(dao::isError()) return print(js::error(dao::getError()));

        /* Link bug to build and release. */
        $this->bug->linkBugToBuild($bugIdList, $resolvedBuild);

        foreach($changes as $bugID => $bugChanges)
        {
            $actionID = $this->action->create('bug', $bugID, 'Resolved', '', $resolution);
            $this->action->logHistory($actionID, $bugChanges);
        }

        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * 批量关闭BUG。
     * Batch close bugs.
     *
     * @param  int    $releaseID
     * @param  string $viewType
     * @access public
     * @return void
     */
    public function batchClose(int $releaseID = 0, string $viewType = '')
    {
        $bugIdList = $releaseID ? $this->post->unlinkBugs : $this->post->bugIdList;
        if(!empty($_POST) && $bugIdList)
        {
            $bugIdList = array_unique($bugIdList);
            $bugs      = $this->bug->getByIdList($bugIdList);

            $bug      = form::data($this->config->bug->form->close)->get();
            $skipBugs = array();
            foreach($bugIdList as $bugID)
            {
                $oldBug = $bugs[$bugID];
                if($oldBug->status == 'resolved')
                {
                    $bug->id = (int)$bugID;
                    $this->bug->close($bug);
                }
                else if($oldBug->status != 'closed')
                {
                    $skipBugs[$bugID] = $bugID;
                }
            }

            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        $load = true;
        if($skipBugs) $load = array('confirm' => sprintf($this->lang->bug->skipClose, implode(',', $skipBugs)), 'confirmed' => 'true');

        if($viewType) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($viewType, 'view', "releaseID=$releaseID&type=bug"), 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $load, 'closeModal' => true));
    }

    /**
     * 批量激活bug。
     * Batch activate bugs.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return viod
     */
    public function batchActivate(int $productID, string $branch = '0')
    {
        if($this->post->statusList)
        {
            /* Get acitvate form data and extend data. */
            $activateData   = form::data($this->config->bug->form->batchActivate)->get();
            $postExtendData = array();
            $extendFields   = $this->bug->getFlowExtendFields();
            foreach($extendFields as $extendField) $postExtendData[$extendField->field] = $this->post->{$extendField->field};

            $this->bug->batchActivate($activateData, $postExtendData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->bugList, 'parent'));
        }

        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));
        $bugIdList = array_unique($this->post->bugIDList);

        $this->qa->setMenu($this->products, $productID, $branch);

        $this->view->title  = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchActivate;
        $this->view->bugs   = $this->bug->getByIdList($bugIdList);
        $this->view->users  = $this->user->getPairs();
        $this->view->builds = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased');

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
