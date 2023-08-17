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
                $products = $this->product->getProducts($objectID, $mode, '', false);
            }
            else
            {
                $products = $this->product->getPairs($mode, 0, '', 'all');
            }

            if(empty($products) and !helper::isAjaxRequest()) $this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=bug&objectID=$objectID"));
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
        /* 把访问的产品ID等状态信息保存到session和cookie中。*/
        /* Save the product id user last visited to session and cookie. */
        $productID  = $this->product->saveState($productID, $this->products);
        $product    = $this->product->getByID($productID);
        $branch     = $this->bugZen->getBrowseBranch($branch, $product->type);
        $browseType = $browseType ? strtolower($browseType) : 'unclosed';

        /* 设置1.5级导航相关信息。*/
        /* Set the 1.5 nav. */
        $this->qa->setMenu($this->products, $productID, $branch);

        /* 设置排序字段。*/
        /* Set the order field. */
        if(!$orderBy) $orderBy = 'id_desc';
        if($this->cookie->qaBugOrder) $orderBy = $this->cookie->qaBugOrder;

        $this->bugZen->setBrowseCookie($product, $branch, $browseType, $param, $orderBy);

        $this->bugZen->setBrowseSession($browseType);

        /* 处理列表页面的参数。*/
        /* Processing browse params. */
        list($moduleID, $queryID, $realOrderBy, $pager) = $this->bugZen->prepareBrowseParams($browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID);

        $this->bugZen->buildBrowseSearchForm($productID, $branch, $queryID);

        $executions = $this->loadModel('execution')->getPairs($this->projectID, 'all', 'empty|withdelete|hideMultiple');
        $bugs       = $this->bugZen->getBrowseBugs($product->id, $branch, $browseType, array_keys($executions), $moduleID, $queryID, $realOrderBy, $pager);

        $this->bugZen->buildBrowseView($bugs, $product, $branch, $browseType, $moduleID, $executions, $param, $orderBy, $pager);
        $this->display();
    }

    /**
     * 查看一个bug。
     * View a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function view(int $bugID)
    {
        /* Judge bug exits or not. */
        $bug = $this->bug->getById($bugID, true);
        if(!$bug) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('qa', 'index'))));

        $this->session->set('storyList', '', 'product');
        $this->session->set('projectList', $this->app->getURI(true) . "#app={$this->app->tab}", 'project');
        $this->bugZen->checkBugExecutionPriv($bug);

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        if(!isonlybody()) $this->bugZen->setViewMenu($bug);

        $bugID     = $bug->id;
        $productID = $bug->product;
        $product   = $this->product->getByID($productID);
        $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);

        $projects  = $this->product->getProjectPairsByProduct($productID, (string)$bug->branch);
        $projectID = key($projects);
        $this->session->set("project", $projectID, 'project');

        $this->executeHooks($bugID);

        $this->view->title       = "BUG #$bug->id $bug->title - " . $product->name;
        $this->view->product     = $product;
        $this->view->project     = $this->loadModel('project')->getByID($bug->project);
        $this->view->projects    = $projects;
        $this->view->executions  = $this->product->getExecutionPairsByProduct($productID, $bug->branch, (string)$projectID, 'noclosed');
        $this->view->bug         = $bug;
        $this->view->bugModule   = empty($bug->module) ? '' : $this->tree->getById($bug->module);
        $this->view->modulePath  = $this->loadModel('tree')->getParents($bug->module);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->branches    = $branches;
        $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $bug->branch, '');
        $this->view->builds      = $this->loadModel('build')->getBuildPairs($productID, 'all');
        $this->view->linkCommits = $this->loadModel('repo')->getCommitsByObject($bugID, 'bug');
        $this->view->actions     = $this->loadModel('action')->getList('bug', $bugID);
        $this->view->legendBasic = $this->bugZen->getBasicInfoTable($this->view);
        $this->view->legendLife  = $this->bugZen->getBugLifeTable($this->view);
        $this->view->legendMain  = $this->bugZen->getMainRelatedTable($this->view);
        $this->view->legendMisc  = $this->bugZen->getOtherRelatedTable($this->view);
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
        if($branch === '') $branch = (string)$this->cookie->preBranch;

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        extract($output);

        $from = isset($output['from']) ? $output['from'] : '';

        if(!empty($_POST))
        {
            $bug = form::data($this->config->bug->form->create)
                ->setIF($this->lang->navGroup->bug != 'qa', 'project', $this->session->project)
                ->setIF($this->post->assignedTo != '', 'assignedDate', helper::now())
                ->setIF($this->post->story !== false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
                ->get();

            if(empty($bug->deadline)) unset($bug->deadline);

            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $this->post->uid);

            $this->bugZen->checkExistBug($bug);

            $action = $from == 'sonarqube' ? 'fromSonarqube' : 'Opened';
            $bugID  = $this->bug->create($bug, $action);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $bug->id = $bugID;
            $this->bugZen->afterCreate($bug, $output, $from);

            $message = $this->executeHooks($bugID);
            return $this->responseAfterCreate($bug, $output, $message);
        }

        $productID      = $this->product->saveState($productID, $this->products);
        $currentProduct = $this->product->getByID($productID);
        $this->bugZen->setCreateMenu($productID, $branch, $output);

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
        $this->display();
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
        $oldBug = $this->bug->getByID($bugID);
        if(!empty($_POST))
        {
            $formData = form::data($this->config->bug->form->edit);
            $bug      = $this->bugZen->prepareEditExtras($formData, $oldBug);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = array();
            if(!$comment)
            {
                $changes = $this->bug->update($bug);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $this->bugZen->afterUpdate($bug, $oldBug);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            /* Get response after editing bug. */
            $message = $this->executeHooks($bugID);
            return $this->bugZen->responseAfterOperate($bugID, $changes, $kanbanGroup, 0, $message);
        }

        $this->bugZen->checkBugExecutionPriv($oldBug);
        $this->bugZen->setEditMenu($oldBug);
        $this->bugZen->buildEditForm($oldBug);
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
        $oldBug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            /* Init bug data. */
            $bug = form::data($this->config->bug->form->assignTo)->add('id', $bugID)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->assignto['id'], $this->post->uid);

            if($oldBug->status != 'closed') $this->bug->assign($bug);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($bugID);
            if(!$message) $message = $this->lang->saveSuccess;

            /* Response after assigning bug. */
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        /* Set menu. */
        $this->qa->setMenu($this->products, $oldBug->product, $oldBug->branch);

        /* Get assigned to member. */
        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $users = $this->bugZen->getAssignedToPairs($oldBug);
        }
        else
        {
            $users = $this->loadModel('user')->getPairs('devfirst|noclosed');
        }

        /* Show the variables associated. */
        $this->view->title   = $this->lang->bug->assignTo;
        $this->view->actions = $this->loadModel('action')->getList('bug', $bugID);
        $this->view->users   = $users;
        $this->view->bug     = $oldBug;
        $this->display();
    }

    /**
     * 确认 bug。
     * confirm a bug.
     *
     * @param  int    $bugID
     * @param  string $kanbanParams fromColID=,toColID=,fromLaneID=,toLaneID=,regionID=
     * @access public
     * @return void
     */
    public function confirm(int $bugID, string $kanbanParams = '')
    {
        $oldBug = $this->bug->getByID($bugID);

        /* 检查 bug 所属执行的权限。*/
        /* Check privilege for execution of the bug. */
        $this->bugZen->checkBugExecutionPriv($oldBug);

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

            $message  = $this->executeHooks($bugID);
            $regionID = zget($kanbanData, 'regionID', 0);
            return $this->bugZen->responseAfterOperate($bugID, array(), '', $regionID, $message);
        }

        $this->qa->setMenu($this->products, $oldBug->product, $oldBug->branch);

        $this->view->title   = $this->lang->bug->confirm;
        $this->view->bug     = $oldBug;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed', $oldBug->assignedTo);
        $this->view->actions = $this->loadModel('action')->getList('bug', $bugID);
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
        $oldBug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            /* Parse extra, and get variables. */
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            /* Init bug data. */
            $bug = $this->bugZen->buildBugForResolve($oldBug);

            /* Check required fields. */
            $this->bugZen->checkRequiredForResolve($bug, $oldBug->execution);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Can create build when resolving bug. */
            if($bug->createBuild == 'on')
            {
                $this->bug->createBuild($bug, $oldBug);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($oldBug->status != 'closed') $this->bug->resolve($bug, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message  = $this->executeHooks($bugID);
            $regionID = zget($output, 'regionID', 0);
            return $this->bugZen->responseAfterOperate($bugID, array(), '', $regionID, $message);
        }

        /* Remove 'Convert to story' from the solution list. */
        unset($this->lang->bug->resolutionList['tostory']);

        /* Set menu. */
        $this->qa->setMenu($this->products, $oldBug->product, $oldBug->branch);

        /* Show the variables associated. */
        $this->view->title      = $this->lang->bug->resolve;
        $this->view->bug        = $oldBug;
        $this->view->execution  = $oldBug->execution ? $this->loadModel('execution')->getByID($oldBug->execution) : '';
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->executions = $this->loadModel('product')->getExecutionPairsByProduct($oldBug->product, $oldBug->branch ? "0,{$oldBug->branch}" : 0, (string)$oldBug->project, 'stagefilter');
        $this->view->builds     = $this->loadModel('build')->getBuildPairs($oldBug->product, $oldBug->branch, 'withbranch,noreleased');
        $this->view->actions    = $this->loadModel('action')->getList('bug', $bugID);
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
        $oldBug = $this->bug->getByID($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            $kanbanInfo = str_replace(array(',', ' '), array('&', ''), $kanbanInfo);
            parse_str($kanbanInfo, $kanbanParams);

            $bug = form::data($this->config->bug->form->activate)->setDefault('assignedTo', $oldBug->resolvedBy)->add('activatedCount', $oldBug->activatedCount + 1)->add('id', $bugID)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->activate['id'], $this->post->uid);

            if($oldBug->status != 'resolved' && $oldBug->status != 'closed')
            {
                dao::$errors[] = $this->lang->bug->error->cannotActivate;
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $this->bug->activate($bug, $kanbanParams);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message  = $this->executeHooks($bugID);
            $regionID = zget($kanbanParams, 'regionID', 0);
            return $this->bugZen->responseAfterOperate($bugID, array(), '', $regionID, $message);
        }

        $productID = $oldBug->product;
        $this->qa->setMenu($this->products, $productID, $oldBug->branch);

        $this->view->title   = $this->lang->bug->activate;
        $this->view->bug     = $oldBug;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed', $oldBug->resolvedBy);
        $this->view->builds  = $this->loadModel('build')->getBuildPairs($productID, $oldBug->branch, 'noempty,noreleased', 0, 'execution', $oldBug->openedBuild);
        $this->view->actions = $this->loadModel('action')->getList('bug', $bugID);
        $this->display();
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
        $oldBug = $this->bug->getByID($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            $bug = form::data($this->config->bug->form->close)->add('id', $bugID)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->close['id'], $this->post->uid);

            $this->bug->close($bug, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message  = $this->executeHooks($bugID);
            $regionID = zget($output, 'regionID', 0);
            return $this->bugZen->responseAfterOperate($bugID, array(), '', $regionID, $message);
        }

        $this->view->title   = $this->lang->bug->close;
        $this->view->bug     = $oldBug;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions = $this->loadModel('action')->getList('bug', $oldBug->id);
        $this->display();
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
        if($confirm == 'no') return $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->bug->notice->confirmDelete, 'confirmed' => inlink('delete', "bugID=$bugID&confirm=yes&from=$from"))));

        $bug = $this->bug->getByID($bugID);

        $this->bug->delete(TABLE_BUG, $bugID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        /* 如果 bug 转任务，删除 bug 时确认是否更新任务状态。*/
        /* If the bug has been transfered to a task, confirm to update task when delete the bug. */
        if($bug->toTask)
        {
            $task = $this->task->getByID($bug->toTask);
            if(!$task->deleted)
            {
                $confirmedURL = $this->createLink('task', 'view', "taskID={$bug->toTask}");
                $canceledURL  = $this->createLink('bug', 'view', "bugID=$bugID");
                return $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->bug->notice->remindTask, 'confirmed' => $confirmedURL, 'canceled' => $canceledURL)));
            }
        }

        $message = $this->executeHooks($bugID);
        return $this->bugZen->responseAfterDelete($bug, $from, $message);
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

        $this->view->fileName        = $this->bugZen->getExportFileName($executionID, $browseType, $product);
        $this->view->allExportFields = $this->bugZen->getExportFields($executionID, $product);
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
    public function report(int $productID, string $browseType, int $branchID, int $moduleID, string $chartType = 'pie')
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

        /* 如果是影子产品并且对应的项目不是多迭代项目，删掉迭代 Bug 数量报表。*/
        /* Unset execution bugs report if the product is shadow and corresponding project is not multiple. */
        $project = $this->loadModel('project')->getByShadowProduct($productID);
        if(!empty($project) && !$project->multiple) unset($this->lang->bug->report->charts['bugsPerExecution']);

        $this->qa->setMenu($this->products, $productID, $branchID);

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common . $this->lang->colon . $this->lang->bug->reportChart;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->branchID      = $branchID;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? implode(',', $this->post->charts) : '';
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
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Assign. */
        $this->view->title     = $this->lang->bug->linkBugs . "BUG #$bug->id $bug->title {$this->lang->dash} " . $this->products[$bug->product];
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
            $bugs = $this->bugZen->checkBugsForBatchCreate($bugs, $productID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message        = '';
            $bugIdList      = array();
            $uploadImages   = $this->post->uploadImage;
            $bugImagesFiles = $this->session->bugImagesFile ? $this->session->bugImagesFile : array();
            foreach($bugs as $index => $bug)
            {
                $uploadImage = !empty($uploadImages[$index]) ? $uploadImages[$index] : '';

                $file = $this->bugZen->processImageForBatchCreate($bug, $uploadImage, $bugImagesFiles);

                $bug->id = $this->bug->create($bug);

                /* Processing other operations after batch creation. */
                $this->bugZen->afterBatchCreate($bug, $output, $uploadImage, $file);
                $message = $this->executeHooks($bug->id);

                $bugIdList[] = $bug->id;
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchCreate');

            return $this->bugZen->responseAfterBatchCreate($productID, $branch, $executionID, $bugIdList, $message);
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getById($productID);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->qa->setMenu($this->products, $productID, $branch);

        $bugImagesFile = $this->session->bugImagesFile ? $this->session->bugImagesFile : array();
        $this->bugZen->assignBatchCreateVars($executionID, $product, $branch, $output, $bugImagesFile);

        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;
        $this->view->moduleID  = $moduleID;
        $this->view->product   = $product;
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
        if($this->post->id)
        {
            /* Build bugs. */
            $bugs = $this->bugZen->buildBugsForBatchEdit();

            $this->bugZen->checkBugsForBatchUpdate($bugs);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $oldBugs = $this->getByIdList(array_column($bugs, 'id'));

            /* Update bugs. */
            $message      = '';
            $toTaskIdList = array();
            $unlinkPlans  = array();
            $link2Plans   = array();
            foreach($bugs as $bugID => $bug)
            {
                $this->bug->update($bug);

                /* Record score when bug is resolved. */
                $oldBug = $oldBugs[$bugID];
                if(isset($bug->status) and $bug->status == 'resolved' and $oldBug->status == 'active') $this->loadModel('score')->create('bug', 'resolve', $bug, $bug->resolvedBy);

                if($this->config->edition != 'pms' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

                if($oldBug->toTask != 0 && isset($bug->status) && $bug->status != $oldBug->status) $toTaskIdList[$oldBug->toTask] = $oldBug->toTask;

                /* Get changes of plan. */
                if($bug->plan != $oldBug->plan)
                {
                    if(!empty($oldBug->plan)) $unlinkPlans[$oldBug->plan] = empty($unlinkPlans[$oldBug->plan]) ? $bugID : "{$unlinkPlans[$oldBug->plan]},{$bugID}";
                    if(!empty($bug->plan))    $link2Plans[$bug->plan]     = empty($link2Plans[$bug->plan])     ? $bugID : "{$link2Plans[$bug->plan]},{$bugID}";
                }
                $message = $this->executeHooks($bug->id);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchEdit');

            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $bugs) $this->action->create('productplan', $planID, 'unlinkbug', '', $bugs);
            foreach($link2Plans as $planID => $bugs)  $this->action->create('productplan', $planID, 'linkbug',   '', $bugs);

            /* Get response and return. */
            return $this->bugZen->responseAfterBatchEdit($toTaskIdList, $message);
        }

        /* If there is no bug ID, return to the previous step. */
        if(!$this->post->bugIdList) $this->locate($this->session->bugList);

        $this->bugZen->assignBatchEditVars($productID, $branch);

        /* Assign. */
        $this->view->productID      = $productID;
        $this->view->severityList   = $this->lang->bug->severityList;
        $this->view->typeList       = $this->lang->bug->typeList;
        $this->view->priList        = $this->lang->bug->priList;
        $this->view->resolutionList = $this->lang->bug->resolutionList;
        $this->view->statusList     = $this->lang->bug->statusList;
        $this->view->branch         = $branch;
        $this->view->showFields     = $this->config->bug->custom->batchEditFields;
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
        if($this->post->bugIdList)
        {
            $bugIdList = array_unique($this->post->bugIdList);
            $oldBugs   = $this->bug->getByIdList($bugIdList);

            $skipBugIdList = '';
            foreach($bugIdList as $bugID)
            {
                $oldBug = $oldBugs[$bugID];
                if($branchID != $oldBug->branch)
                {
                    if(empty($oldBug->module))
                    {
                        $bug = new stdclass();
                        $bug->id     = (int)$bugID;
                        $bug->branch = $branchID;

                        $this->bug->update($bug);
                    }
                    else
                    {
                        $skipBugIdList .= '[' . $bugID . ']';
                    }
                }
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        $load = $this->session->bugList;
        if(!empty($skipBugIdList)) $load = array('confirm' => sprintf($this->lang->bug->notice->noSwitchBranch, $skipBugIdList), 'confirmed' => 'true', 'canceled' => 'true');
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $load));
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
        if($this->post->bugIdList)
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

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->bugList));
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
        if($this->post->bugIdList)
        {
            $bugIdList   = array_unique($this->post->bugIdList);
            $oldBugs     = $this->bug->getByIdList($bugIdList);
            $unlinkPlans = array();
            $link2Plans  = array();
            foreach($bugIdList as $bugID)
            {
                $oldBug = $oldBugs[$bugID];
                if($planID == $oldBug->plan) continue;

                /* Bugs link to plans and bugs unlink to plans. */
                $unlinkPlans[$oldBug->plan] = empty($unlinkPlans[$oldBug->plan]) ? $bugID : "{$unlinkPlans[$oldBug->plan]},$bugID";
                $link2Plans[$planID]        = empty($link2Plans[$planID])        ? $bugID : "{$link2Plans[$planID]},$bugID";

                /* Update bug plan. */
                $bug = new stdclass();
                $bug->id   = $bugID;
                $bug->plan = $planID;

                $this->bug->update($bug);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record plan action. */
            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $bugs) $this->action->create('productplan', $planID, 'unlinkbug', '', $bugs);
            foreach($link2Plans as $planID => $bugs)  $this->action->create('productplan', $planID, 'linkbug',   '', $bugs);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->bugList));
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
        if($this->post->bugIdList)
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
        if($this->post->bugIdList)
        {
            $bugIdList = array_unique($this->post->bugIdList);
            $bugs      = $this->bug->getByIdList($bugIdList);

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
        if($this->post->bugIdList)
        {
            /* Prepare resolve data. */
            $bugIdList = array_unique($this->post->bugIdList);
            $bugs      = $this->bug->getByIdList($bugIdList);

            list($modules, $productQD) = $this->bugZen->getBatchResolveVars($bugs);

            $users = $this->loadModel('user')->getPairs();
            $now   = helper::now();
            foreach($bugIdList as $bugID)
            {
                $oldBug = $bugs[$bugID];
                if($oldBug->resolution == 'fixed' || $oldBug->status != 'active') continue;

                /* Get bug assignedTo. */
                $assignedTo = $oldBug->openedBy;
                if(!isset($users[$assignedTo]))
                {
                    $assignedTo = '';
                    $module     = isset($modules[$oldBug->module]) ? $modules[$oldBug->module] : '';
                    while($module)
                    {
                        if($module->owner and isset($users[$module->owner]))
                        {
                            $assignedTo = $module->owner;
                            break;
                        }
                        $module = isset($modules[$module->parent]) ? $modules[$module->parent] : '';
                    }
                    if(empty($assignedTo)) $assignedTo = $productQD;
                }

                $bug = new stdClass();
                $bug->id            = (int)$bugID;
                $bug->resolution    = $resolution;
                $bug->resolvedBuild = $resolution == 'fixed' ? $resolvedBuild : '';
                $bug->resolvedBy    = $this->app->user->account;
                $bug->resolvedDate  = $now;
                $bug->status        = 'resolved';
                $bug->confirmed     = 1;
                $bug->assignedTo    = $assignedTo;
                $bug->assignedDate  = $now;

                $this->bug->resolve($bug);

                $message = $this->executeHooks($bug->id);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        if(empty($message)) $message = $this->lang->saveSuccess;
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
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
        if($bugIdList)
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
                elseif($oldBug->status != 'closed')
                {
                    $skipBugs[$bugID] = $bugID;
                }
            }

            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        $load = true;
        if($skipBugs) $load = array('confirm' => sprintf($this->lang->bug->notice->skipClose, implode(',', $skipBugs)), 'confirmed' => 'true', 'canceled' => 'true');

        if($viewType) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($viewType, 'view', "releaseID=$releaseID&type=bug"), 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $load, 'closeModal' => true));
    }

    /**
     * 批量激活bug。
     * Batch activate bugs.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function batchActivate(int $productID, string $branch = '0')
    {
        if($this->post->id)
        {
            $activateBugs = form::batchData($this->config->bug->form->batchActivate)->get();

            $oldBugs = $this->bug->getByIdList($this->post->id);

            foreach($activateBugs as $bugID => $bug)
            {
                if($bug->status != 'resolved' && $bug->status != 'closed') continue;
                $oldBug = $oldBugs[$bugID];

                $bug->status         = 'active';
                $bug->openedBuild    = implode(',', $bug->openedBuild);
                $bug->activatedCount = $oldBug->activatedCount + 1;

                $this->bug->activate($bug);
                $message = $this->executeHooks($bug->id);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');

            if(empty($message)) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->session->bugList));
        }

        if(!$this->post->bugIdList) $this->locate($this->session->bugList);
        $bugIdList = array_unique($this->post->bugIdList);

        $this->qa->setMenu($this->products, $productID, $branch);

        $this->view->title  = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchActivate;
        $this->view->bugs   = $this->bug->getByIdList($bugIdList);
        $this->view->users  = $this->user->getPairs('noclosed');
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
     * AJAX: 获取指派给指定用户的BUG。
     * AJAX: get bugs of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id       the id of the select control.
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserBugs(int $userID = 0, string $id = '' , int $appendID = 0): string
    {
        if($userID == 0) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;
        $bugs    = $this->bug->getUserBugPairs($account, true, 0, array(), array(), $appendID);

        $items = array();
        foreach($bugs as $bugID => $bugTitle)
        {
            if(empty($bugID)) continue;
            $items[] = array('text' => $bugTitle, 'value' => $bugID);
        }

        $fieldName = $id ? "bugs[$id]" : 'bug';
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * AJAX：获取 bug 模块的负责人。
     * AJAX: Get bug owner of a module.
     *
     * @param  int    $moduleID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function ajaxGetModuleOwner(int $moduleID, int $productID = 0): string
    {
        list($account, $realname) = $this->bug->getModuleOwner($moduleID, $productID);

        return print(json_encode(array($account, $realname)));
    }

    /**
     * Ajax 方式加载指派给表单。
     * AJAX: get team members of the executions as assignedTo list.
     *
     * @param  int    $executionID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxLoadAssignedTo(int $executionID, string $selectedUser = ''): string
    {
        $executionMembers = $this->user->getTeamMemberPairs($executionID, 'execution');
        $items            = array();
        foreach($executionMembers as $account => $member)
        {
            if($account) $items[] = array('text' => $member, 'value' => $account, 'keys' => $member);
        }
        return print(json_encode($items));
    }

    /**
     * Ajax 方式加载所有的用户。
     * AJAX: get all users as assignedTo list.
     *
     * @param  string $selectedUser
     * @param  string $params       noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname|outside|inside|all, can be sets of theme
     * @access public
     * @return string
     */
    public function ajaxLoadAllUsers(string $selectedUser = '', string $params = 'devfirst|noclosed'): string
    {
        $allUsers = $this->loadModel('user')->getPairs($params);

        return print(html::select('assignedTo', $allUsers, $selectedUser, 'class="form-control"'));
    }

    /**
     * Ajax 方式获取产品下拉菜单。
     * Drop menu page.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $productID, string $module, string $method, string $extra = '')
    {
        /* 如果extra不为空，通过extra获取产品。 */
        /* IF extra is not empty, get products by extra. */
        $products = array();
        if(!empty($extra)) $products = $this->product->getProducts($extra, 'all', 'program desc, line desc, ');

        /* 如果当前系统模式是全生命周期管理模式，设置项目集和产品线信息。 */
        /* If system mode is ALM, show programs and product lines. */
        if($this->config->systemMode == 'ALM')
        {
            $this->view->programs = $this->loadModel('program')->getPairs(true);
            $this->view->lines    = $this->product->getLinePairs();
        }

        /* 设置要展示的相关变量。 */
        /* Show the variables associated. */
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;
        $this->view->products  = $products;
        $this->view->projectID = $this->session->project;
        $this->view->link      = $this->product->getProductLink($module, $method, $extra);
        $this->display();
    }

    /**
     * AJAX: 获取产品的团队成员。
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
        /* 获取产品的成员。如果产品团队成员为空，返回未关闭的用户。 */
        /* Get product members. If product members is empty, get noclosed users. */
        $productMembers = $this->bug->getProductMemberPairs($productID, $branchID);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->loadModel('user')->getPairs('devfirst|noclosed');

        $items = array();
        foreach($productMembers as $account => $member)
        {
            if($account) $items[] = array('text' => $member, 'value' => $account, 'keys' => $member);
        }
        return print(json_encode($items));
    }

    /**
     * AJAX: 获产品的 bugs。
     * AJAX: Get product bugs.
     *
     * @param  int     $productID
     * @param  int     $bugID
     * @access public
     * @return int
     */
    public function ajaxGetProductBugs($productID, $bugID): int
    {
        /* 获取除了这个 bugID 的产品 bugs。 */
        /* Get product bugs exclude this bugID. */
        $product     = $this->loadModel('product')->getById($productID);
        $bug         = $this->bug->getById($bugID);
        $branch      = $bug->branch > 0 ? $bug->branch . ',0' : '0';
        $branch      = $product->type == 'branch' ? $branch : '';
        $productBugs = $this->bug->getProductBugPairs($productID, $branch);
        unset($productBugs[$bugID]);

        $bugList = array();
        foreach($productBugs as $bugID => $bugName) $bugList[] = array('value' => $bugID, 'text' => $bugName, 'keys' => $bugName);
        return $this->send($bugList);
    }

    /**
     * ajax方式获取项目团队成员。
     * Ajax get project team members.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function ajaxGetProjectTeamMembers(int $projectID)
    {
        /* 获取项目团队成员。 */
        /* Get project team members. */
        $teamMembers = empty($projectID) ? array() : $this->loadModel('project')->getTeamMemberPairs($projectID);
        $items       = array();
        foreach($teamMembers as $account => $member)
        {
            if($account)
            {
                $userName = ucfirst(mb_substr($account, 0, 1)) . ':' . ($member ? $member : $account);
                $items[] = array('text' => $userName, 'value' => $account, 'keys' => $userName);
            }
        }
        return print(json_encode($items));
    }

    /**
     * ajax方式获取执行类别。
     * Ajax get execution lang.
     *
     * @param  int  $projectID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionLang(int $projectID)
    {
        /* 获取项目信息，返回执行的语言项。 */
        /* Get project information and then return language item of the execution. */
        $project = $this->loadModel('project')->getByID($projectID);
        return $project->model == 'kanban' ? print($this->lang->bug->kanban) : print($this->lang->bug->execution);
    }

    /**
     * Ajax 方式获取已发布的版本。
     * Ajax get released builds.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return string
     */
    public function ajaxGetReleasedBuilds(int $productID, int|string $branch = 'all'): string
    {
        /* 获取已发布的版本。 */
        /* Get released builds. */
        $releasedBuilds = $this->loadModel('release')->getReleasedBuilds($productID, $branch);

        return print(helper::jsonEncode($releasedBuilds));
    }
}
