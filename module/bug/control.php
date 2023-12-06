<?php
declare(strict_types=1);
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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
        if(!isInModal())
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

            if(empty($products) && (helper::isAjaxRequest('zin') || helper::isAjaxRequest('fetch'))) $this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=bug&objectID=$objectID"));
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
        $productID  = $this->product->checkAccess($productID, $this->products);
        $product    = $this->product->getByID($productID);
        $branch     = $this->bugZen->getBrowseBranch($branch, (string)$product->type);
        $browseType = $browseType ? strtolower($browseType) : 'unclosed';

        /* 设置1.5级导航相关信息。*/
        /* Set the 1.5 nav. */
        $this->qa->setMenu($productID, $branch);

        /* 设置排序字段。*/
        /* Set the order field. */
        if(!$orderBy) $orderBy =  $this->cookie->qaBugOrder ? $this->cookie->qaBugOrder : 'id_desc';

        $this->bugZen->setBrowseCookie((object)$product, $branch, $browseType, $param, $orderBy);

        $this->bugZen->setBrowseSession($browseType);

        /* 处理列表页面的参数。*/
        /* Processing browse params. */
        list($moduleID, $queryID, $realOrderBy, $pager) = $this->bugZen->prepareBrowseParams($browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID);

        $this->bugZen->buildBrowseSearchForm($productID, $branch, $queryID);

        $executions = $this->loadModel('execution')->fetchPairs($this->projectID, 'all', 'empty|withdelete|hideMultiple');
        $bugs       = $this->bugZen->getBrowseBugs((int)$product->id, $branch, $browseType, array_keys($executions), $moduleID, $queryID, $realOrderBy, $pager);

        $this->bugZen->buildBrowseView($bugs, (object)$product, $branch, $browseType, $moduleID, $executions, $param, $orderBy, $pager);
        $this->display();
    }

    /**
     * 查看一个 bug。
     * View a bug.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function view(int $bugID)
    {
        $bug = $this->bug->getByID($bugID, true);

        /* 判断 bug 是否存在。*/
        /* Judge bug exits or not. */
        if(!$bug) return print(js::alert($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));

        /* 检查用户是否拥有所属执行的权限。*/
        /* Check bug execution priv. */
        $this->bugZen->checkBugExecutionPriv($bug);

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        if(!isInModal()) $this->bugZen->setViewMenu($bug);

        $product   = $this->product->getByID($bug->product);
        $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);
        $projects  = $this->product->getProjectPairsByProduct($bug->product, (string)$bug->branch);
        $projectID = key($projects);
        if(in_array($this->app->tab, array('project', 'execution')))
        {
            $objectType = $this->app->tab == 'project' ? 'projectID' : 'executionID';
            $this->view->{$objectType} = $this->session->{$this->app->tab};
        }

        $this->session->set("project", $projectID, 'project');
        $this->session->set('storyList', '', 'product');
        $this->session->set('projectList', $this->app->getURI(true) . "#app={$this->app->tab}", 'project');

        if($this->app->tab == 'repo') $this->view->repoID = $bug->repo;
        $this->view->title       = "BUG #$bug->id $bug->title - " . $product->name;
        $this->view->product     = $product;
        $this->view->project     = $this->loadModel('project')->getByID($bug->project);
        $this->view->projects    = $projects;
        $this->view->executions  = $this->product->getExecutionPairsByProduct($bug->product, (string)$bug->branch, (int)$projectID, 'noclosed');
        $this->view->bug         = $bug;
        $this->view->bugModule   = empty($bug->module) ? '' : $this->tree->getByID($bug->module);
        $this->view->modulePath  = $this->loadModel('tree')->getParents($bug->module);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->branches    = $branches;
        $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $bug->branch, '');
        $this->view->builds      = $this->loadModel('build')->getBuildPairs(array($bug->product), 'all', 'noterminate,nodone,hasdeleted');
        $this->view->linkCommits = $this->loadModel('repo')->getCommitsByObject($bug->id, 'bug');
        $this->view->actions     = $this->loadModel('action')->getList('bug', $bug->id);
        $this->view->legendBasic = $this->bugZen->getBasicInfoTable($this->view);
        $this->view->legendLife  = $this->bugZen->getBugLifeTable($this->view);
        $this->view->legendMain  = $this->bugZen->getMainRelatedTable($this->view);
        $this->view->legendMisc  = $this->bugZen->getOtherRelatedTable($this->view);
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('bug', $bugID);
        $this->display();
    }

    /**
     * 创建一个 bug。
     * Create a bug.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $extras    Other params, for example, executionID=10,moduleID=10.
     * @access public
     * @return void
     */
    public function create(int $productID, string $branch = '', string $extras = '')
    {
        $extras = str_replace(array(',', ' ', '*'), array('&', '', '-'), $extras);
        parse_str($extras, $params);

        $from = isset($params['from']) ? $params['from'] : '';

        if(!empty($_POST))
        {
            $formData = form::data($this->config->bug->form->create);
            $bug      = $this->bugZen->prepareCreateExtras($formData);

            $this->bugZen->checkExistBug($bug);

            $bugID = $this->bug->create($bug, $from);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $bug->id = $bugID;
            $this->bugZen->afterCreate($bug, $params, $from);

            $message = $this->executeHooks($bugID);
            return $this->responseAfterCreate($bug, $params, $message);
        }

        $productID = $this->product->checkAccess($productID, $this->products);
        $product   = $this->product->getByID($productID);
        if($branch === '') $branch = (string)$this->cookie->preBranch;
        $this->bugZen->setCreateMenu($productID, $branch, $params);

        /* 初始化一个bug对象，尽可能把属性都绑定到bug对象上，extract() 出来的变量除外。 */
        /* Init bug, give bug as many variables as possible, except for extract variables. */
        $fields = array('productID' => $productID, 'branch' => $branch, 'title' => ($from == 'sonarqube' ? $_COOKIE['sonarqubeIssue'] : ''), 'assignedTo' => (isset($product->QD) ? $product->QD : ''));
        $bug = $this->bugZen->initBug($fields);
        $bug = $this->bugZen->setOptionMenu($bug, $product);

        /* 处理复制 bug，从用例、测试单、日志转 bug。 */
        /* Handle copy bug, bug from case, testtask, todo. */
        $bug = $this->bugZen->extractObjectFromExtras($bug, $params);

        /* 获取分支、版本、需求、项目、执行、产品、项目的模式，构造$this->view。*/
        /* Get branches, builds, stories, project, projects, executions, products, project model and build create form. */
        $this->bugZen->buildCreateForm($bug, $params, $from);

        $this->display();
    }

    /**
     * 更新 bug 信息。
     * Edit a bug.
     *
     * @param  int    $bugID
     * @param  bool   $comment true|false
     * @access public
     * @return void
     */
    public function edit(int $bugID, bool $comment = false)
    {
        $oldBug = $this->bug->getByID($bugID);
        if(!empty($_POST))
        {
            $formData = form::data($this->config->bug->form->edit);
            $bug      = $this->bugZen->prepareEditExtras($formData, $oldBug);
            $this->bugZen->checkRquiredForEdit($bug);

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
            return $this->bugZen->responseAfterOperate($bugID, $changes, $message);
        }

        $this->bugZen->checkBugExecutionPriv($oldBug);
        $this->bugZen->setEditMenu($oldBug);
        $this->bugZen->buildEditForm($oldBug);
        $this->display();
    }

    /**
     * 指派 bug。
     * Assign bug.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function assignTo(int $bugID)
    {
        /* 获取更新前的 bug，并且检查所属执行的权限。*/
        /* Get old bug and check privilege of the execution. */
        $oldBug = $this->bug->getByID($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            /* 初始化 bug 数据。*/
            /* Init bug data. */
            $bug = form::data($this->config->bug->form->assignTo)->add('id', $bugID)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->assignto['id'], $this->post->uid);

            if($this->app->rawMethod == 'batchassignto') unset($bug->mailto);

            if($oldBug->status != 'closed') $this->bug->assign($bug, $oldBug);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($bugID);
            if(!$message) $message = $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        $this->qa->setMenu($oldBug->product, $oldBug->branch);

        /* 获取指派给的用户列表。*/
        /* Get users that can be assigned to. */
        if($this->app->tab == 'project' || $this->app->tab == 'execution')
        {
            $users = $this->bugZen->getAssignedToPairs($oldBug);
        }
        else
        {
            $users = $this->loadModel('user')->getPairs('devfirst|noclosed');
        }

        $this->view->title   = $this->lang->bug->assignTo;
        $this->view->bug     = $oldBug;
        $this->view->users   = $users;
        $this->view->actions = $this->loadModel('action')->getList('bug', $bugID);
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

            /* 返回确认 bug 后的响应。 */
            /* Return response after confirming bug. */
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $message = $this->executeHooks($bugID);
            return $this->bugZen->responseAfterOperate($bugID, array(), $message);
        }

        $this->qa->setMenu($oldBug->product, $oldBug->branch);

        /* 展示相关变量。 */
        /* Show the variables associated. */
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
        /* 获取 bug 信息，并检查 bug 所属执行的权限。*/
        /* Get bug info, and check privilege of bug 所属执行的权限。*/
        $oldBug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            /* 解析信息，并获取变量。 */
            /* Parse extra, and get variables. */
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            /* 初始化 bug 数据。 */
            /* Init bug data. */
            $bug = $this->bugZen->buildBugForResolve($oldBug);

            /* 检查解决 bug 的必填项。 */
            /* Check required fields. */
            $this->bugZen->checkRequiredForResolve($bug, $oldBug->execution);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 如果需要创建版本，创建版本。 */
            /* If you need to create a build, create one. */
            if($bug->createBuild == 'on')
            {
                $this->bug->createBuild($bug, $oldBug);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            /* 只有非关闭状态的 bug 可以解决。 */
            /* Only unclosed bug can be resolved. */
            if($oldBug->status != 'closed') $this->bug->resolve($bug, $output);

            /* 返回解决 bug 后的响应。 */
            /* Return response after resolving bug. */
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $message = $this->executeHooks($bugID);
            return $this->bugZen->responseAfterOperate($bugID, array(), $message);
        }

        /* 移除解决方案“转需求”。 */
        /* Remove 'Convert to story' from the solution list. */
        unset($this->lang->bug->resolutionList['tostory']);

        /* 设置菜单。 */
        /* Set menu. */
        $this->qa->setMenu($oldBug->product, $oldBug->branch);

        /* 展示相关变量。 */
        /* Show the variables associated. */
        $this->view->title      = $this->lang->bug->resolve;
        $this->view->bug        = $oldBug;
        $this->view->execution  = $oldBug->execution ? $this->loadModel('execution')->getByID($oldBug->execution) : '';
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->executions = $this->loadModel('product')->getExecutionPairsByProduct($oldBug->product, $oldBug->branch ? "0,{$oldBug->branch}" : '0', (int)$oldBug->project, 'stagefilter');
        $this->view->builds     = $this->loadModel('build')->getBuildPairs(array($oldBug->product), $oldBug->branch, 'withbranch,noreleased');
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
        /* 获取 bug 信息，并检查 bug 所属执行的权限。*/
        /* Get bug info, and check privilege of bug 所属执行的权限。*/
        $oldBug = $this->bug->getByID($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            /* 只有状态为解决或者关闭的bug才可以激活。 */
            /* Only bugs whose status is resolved or closed can be activated. */
            if($oldBug->status != 'resolved' && $oldBug->status != 'closed')
            {
                dao::$errors[] = $this->lang->bug->error->cannotActivate;
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            /* 获取bug信息。 */
            /* Build bug data. */
            $bug = form::data($this->config->bug->form->activate)->setDefault('assignedTo', $oldBug->resolvedBy)->add('activatedCount', $oldBug->activatedCount + 1)->add('id', $bugID)->add('resolvedDate', null)->add('closedDate', null)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->activate['id'], $this->post->uid);

            /* 解析看板信息。 */
            /* Parse kanban information. */
            $kanbanInfo = str_replace(array(',', ' '), array('&', ''), $kanbanInfo);
            parse_str($kanbanInfo, $kanbanParams);

            $this->bug->activate($bug, $kanbanParams);

            /* 返回激活 bug 后的响应。 */
            /* Return response after activating bug. */
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $message = $this->executeHooks($bugID);
            return $this->bugZen->responseAfterOperate($bugID, array(), $message);
        }

        $productID = $oldBug->product;
        $this->qa->setMenu($productID, $oldBug->branch);

        /* 展示相关变量。 */
        /* Show the variables associated. */
        $this->view->title   = $this->lang->bug->activate;
        $this->view->bug     = $oldBug;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed', $oldBug->resolvedBy);
        $this->view->builds  = $this->loadModel('build')->getBuildPairs(array($productID), $oldBug->branch, 'noempty,noreleased', 0, 'execution', $oldBug->openedBuild);
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
        /* 获取 bug 信息，并检查 bug 所属执行的权限。*/
        /* Get bug info, and check privilege of bug 所属执行的权限。*/
        $oldBug = $this->bug->getByID($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        if(!empty($_POST))
        {
            /* 设置bug信息。 */
            /* Set bug information. */
            $bug = form::data($this->config->bug->form->close)->add('id', $bugID)->get();
            $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->close['id'], $this->post->uid);

            /* 设置额外变量。 */
            /* Set extra variables. */
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            $this->bug->close($bug, $output);

            /* 返回关闭 bug 后的响应。 */
            /* Return response after closing bug. */
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $message  = $this->executeHooks($bugID);
            return $this->bugZen->responseAfterOperate($bugID, array(), $message);
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
     * @param  int    $bugID
     * @param  string $from    taskkanban
     * @access public
     * @return void
     */
    public function delete(int $bugID, string $from = '')
    {
        /* 删除 bug。 */
        /* Delete bug. */
        $bug = $this->bug->getByID($bugID);
        $this->bug->delete(TABLE_BUG, $bugID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        /* 返回删除 bug 后的响应。 */
        /* Return response after deleting bug. */
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
            /* 设置bug导出的参数。 */
            /* Set bug export params. */
            $this->session->set('bugTransferParams', array('productID' => $productID, 'executionID' => $executionID, 'branch' => 'all'));

            /* 设置导出数据源。 */
            /* Set export data source. */
            if(!$productID || $browseType == 'bysearch')
            {
                /* 设置模块数据源。 */
                /* Set module data source. */
                $this->config->bug->dtable->fieldList['module']['dataSource']['method'] = 'getAllModulePairs';
                $this->config->bug->dtable->fieldList['module']['dataSource']['params'] = 'bug';

                /* 如果导出执行的bug，设置数据源。 */
                /* In execution, set data source. */
                if($executionID)
                {
                    $object    = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
                    $projectID = $object->type == 'project' ? $object->id : $object->parent;
                    $this->config->bug->dtable->fieldList['project']['dataSource']   = array('module' => 'project', 'method' => 'getPairsByIdList', 'params' => $projectID);
                    $this->config->bug->dtable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs', 'params' => $projectID);
                }
            }

            $this->loadModel('transfer')->export('bug');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        /* 获取产品信息。 */
        /* Get product information. */
        $product = $this->loadModel('product')->getByID($productID);

        /* 展示相关变量。 */
        /* Show the variables associated. */
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

        $this->qa->setMenu($productID, $branchID);

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
     * @param  string $bySearch
     * @param  string $excludeBugs
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs(int $bugID, string $bySearch = 'false', string $excludeBugs = '', int $queryID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $bug      = $this->bug->getByID($bugID);
        $bySearch = $bySearch === 'true';

        /* 检查 bug 所属执行的权限。*/
        /* Check privilege of bug 所属执行的权限。*/
        $this->bugZen->checkBugExecutionPriv($bug);

        $this->qa->setMenu($bug->product, $bug->branch);

        $this->bugZen->buildSearchFormForLinkBugs($bug, $excludeBugs, $queryID);

        /* Load pager. */
        $this->app->loadClass('pager', true);
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
     * 批量创建 bug。
     * Batch create bug.
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

        $bugImagesFile = $this->session->bugImagesFile ? $this->session->bugImagesFile : array();

        if(!empty($_POST))
        {
            $bugs = $this->bugZen->buildBugsForBatchCreate($productID, $branch, $bugImagesFile);
            $bugs = $this->bugZen->checkBugsForBatchCreate($bugs, $productID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message   = '';
            $bugIdList = array();
            foreach($bugs as $index => $bug)
            {
                $bug->id = $this->bug->create($bug);

                /* 批量创建后的一些其他操作。*/
                /* Processing other operations after batch creation. */
                $uploadImage = !empty($this->post->uploadImage[$index]) ? $this->post->uploadImages[$index] : '';
                $file        = $this->bugZen->processImageForBatchCreate($bug, $uploadImage, $bugImagesFile);
                $this->bugZen->afterBatchCreate($bug, $output, $uploadImage, $file);

                $message = $this->executeHooks($bug->id);

                $bugIdList[] = $bug->id;
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchCreate');

            return $this->bugZen->responseAfterBatchCreate($productID, $branch, $executionID, $bugIdList, $message);
        }

        /* 把访问的产品ID等状态信息保存到session和cookie中。*/
        /* Save the product id user last visited to session and cookie. */
        $productID = $this->product->checkAccess($productID, $this->products);
        $product   = $this->product->getByID($productID);

        /* 设置当前分支，并且设置导航。*/
        /* Get branch and set menu. */
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->qa->setMenu($productID, $branch);

        /* 展示批量创建bug的相关变量。*/
        /* Show the variables associated with the batch creation bugs. */
        $this->bugZen->assignBatchCreateVars($executionID, $product, $branch, $output, $bugImagesFile);

        $this->view->title    = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;
        $this->view->moduleID = $moduleID;
        $this->view->product  = $product;
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
        /* 如果没有选择的 bug，跳转到前一个页面。*/
        /* If there is no bug ID, locate to the previous step. */
        if(!$this->post->bugIdList && !$this->post->id)
        {
            $url = !empty($this->session->bugList) ? $this->session->bugList : $this->createLink('qa', 'index');
            $this->locate($url);
        }

        if($this->post->id)
        {
            /* 为批量编辑 bug 构造数据。*/
            /* Build bugs. */
            $bugs = $this->bugZen->buildBugsForBatchEdit();

            /* 检查数据。*/
            /* Check bugs. */
            $this->bugZen->checkBugsForBatchUpdate($bugs);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $oldBugs = $this->bug->getByIdList(array_column($bugs, 'id'));

            $message = '';
            foreach($bugs as $bugID => $bug)
            {
                $this->bug->update($bug);

                $oldBug = $oldBugs[$bugID];

                /* 批量编辑 bug 后的一些操作。*/
                /* Operate after batch edit bugs. */
                $this->bugZen->operateAfterBatchEdit($bug, $oldBug);

                /* 获取待处理的任务和计划列表。*/
                /* Get toTaskIdList, unlinkPlans and link2Plans to be processed. */
                list($toTaskIdList, $unlinkPlans, $link2Plans) = $this->bugZen->getToBeProcessedData($bug, $oldBug);

                $message = $this->executeHooks($bug->id);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchEdit');

            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $bugs) $this->action->create('productplan', $planID, 'unlinkbug', '', $bugs);
            foreach($link2Plans as $planID => $bugs)  $this->action->create('productplan', $planID, 'linkbug',   '', $bugs);

            /* 批量编辑的返回。*/
            /* Response of batch edit. */
            return $this->bugZen->responseAfterBatchEdit($toTaskIdList, $message);
        }

        $this->bugZen->assignBatchEditVars($productID, $branch);

        $this->display();
    }

    /**
     * 批量修改 bug 的分支。
     * Batch change branch of the bug.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function batchChangeBranch(int $branchID)
    {
        if($this->post->bugIdList)
        {
            /* 准备 bug 修改分支需要的数据。 */
            /* Prepare data to change branch. */
            $bugIdList = array_unique($this->post->bugIdList);
            $oldBugs   = $this->bug->getByIdList($bugIdList);

            /* 更新 bug 的分支，获取跳过的 bug id 列表。 */
            /* Update the branch of the bug, and get the list of bug id to skip. */
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

        /* 返回批量修改分支 bugs 后的响应。 */
        /* Return response after batch changing branch of the bugs. */
        $load = $this->session->bugList;
        if(!empty($skipBugIdList)) $load = array('confirm' => sprintf($this->lang->bug->notice->noSwitchBranch, $skipBugIdList), 'confirmed' => true, 'canceled' => true);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $load));
    }

    /**
     * 批量修改 bug 的所属模块。
     * Batch change the module of the bug.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule(int $moduleID)
    {
        if($this->post->bugIdList)
        {
            /* 获取要修改计划的 bug id 列表。 */
            /* Get bug id list to change plan. */
            $bugIdList = array_unique($this->post->bugIdList);
            foreach($bugIdList as $bugID)
            {
                /* 更新 bug 的模块。 */
                /* Update the module of the bug. */
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
     * 批量修改 bug 的计划。
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
            /* 准备 bug 修改计划需要的数据。 */
            /* Prepare data to change plan. */
            $bugIdList = array_unique($this->post->bugIdList);
            $oldBugs   = $this->bug->getByIdList($bugIdList);

            /* 修改 bugs 的计划。 */
            /* Change the plan of bugs. */
            $unlinkPlans = array();
            $link2Plans  = array();
            foreach($bugIdList as $bugID)
            {
                $oldBug = $oldBugs[$bugID];

                /* 如果新老计划一致，跳过。 */
                /* If the new plan is the same as the old one, skip it. */
                if($planID == $oldBug->plan) continue;

                /* 关联 bug 到新计划，并取消与旧计划关联。 */
                /* Link the bug to the new plan and unlink the bug from the old plan. */
                $unlinkPlans[$oldBug->plan] = empty($unlinkPlans[$oldBug->plan]) ? $bugID : "{$unlinkPlans[$oldBug->plan]},$bugID";
                $link2Plans[$planID]        = empty($link2Plans[$planID])        ? $bugID : "{$link2Plans[$planID]},$bugID";

                /* 更新 bug 的计划。 */
                /* Update the plan of the bug. */
                $bug = new stdclass();
                $bug->id   = (int)$bugID;
                $bug->plan = $planID;
                $this->bug->update($bug);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 记录 bug 的计划变更动态。 */
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
     * 批量变更 bugs 的指派人。
     * Batch update assign of bugs.
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
            /* 获取指派给的 bug id 列表。 */
            /* Get bug id list to assign. */
            $bugIdList = array_unique($this->post->bugIdList);

            $oldBugList = $this->bug->getByIdList($bugIdList);

            /* 初始化 bug 数据。 */
            /* Init bug data. */
            $bug = form::data($this->config->bug->form->assignTo)->remove('mailto')->get();
            foreach($bugIdList as $bugID)
            {
                /* 构建 bug。 */
                /* Build bug. */
                $bug->id         = (int)$bugID;
                $bug->assignedTo = $assignedTo;

                $this->bug->assign($bug, $oldBugList[$bugID]);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        /* 返回批量指派 bugs 后的响应。 */
        /* Return response after batch assigning bugs. */
        if($type == 'execution') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('execution', 'bug', "executionID=$objectID")));
        if($type == 'project')   return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('project', 'bug', "projectID=$objectID")));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 批量确认 bugs。
     * Batch confirm bugs.
     *
     * @access public
     * @return void
     */
    public function batchConfirm()
    {
        if($this->post->bugIdList)
        {
            /* 准备 bug 确认需要的数据。 */
            /* Prepare resolve data. */
            $bugIdList = array_unique($this->post->bugIdList);
            $bugs      = $this->bug->getByIdList($bugIdList);

            /* 初始化 bug 数据。 */
            /* Init bug data. */
            $bug = form::data($this->config->bug->form->confirm)->setDefault('confirmed', 1)->remove('pri,type,status,mailto')->get();
            foreach($bugIdList as $bugID)
            {
                /* 如果 bug 已经确认过，跳过。 */
                /* If bug has been confirmed, skip it. */
                if(!empty($bugs[$bugID]->confirmed)) continue;

                /* 构建 bug。 */
                /* Build bug. */
                $bug->id         = (int)$bugID;
                $bug->confirmed  = 1;
                $bug->assignedTo = $this->app->user->account;

                $this->bug->confirm($bug);
                $message = $this->executeHooks($bugID);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        /* 返回批量确认 bugs 后的响应。 */
        /* Return response after batch confirming bugs. */
        if(empty($message)) $message = $this->lang->saveSuccess;
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * 批量解决 bugs。
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
            /* 准备 bug 解决 需要的数据。 */
            /* Prepare resolve data. */
            $bugIdList = array_unique($this->post->bugIdList);
            $bugs      = $this->bug->getByIdList($bugIdList);

            list($modules, $productQD) = $this->bugZen->getBatchResolveVars($bugs);

            $users = $this->loadModel('user')->getPairs();
            $now   = helper::now();
            foreach($bugIdList as $bugID)
            {
                /* 只有激活的bug或者解决方案不为已解决的bug可以解决。 */
                /* Only active bugs or bugs whose resolution is not fixed can be resolve. */
                $oldBug = $bugs[$bugID];
                if($oldBug->resolution == 'fixed' || $oldBug->status != 'active') continue;

                /* 获取 bug 的指派给人员。 */
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

                /* 构建 bug。 */
                /* Build bug. */
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

        /* 返回批量解决 bugs 后的响应。 */
        /* Return response after batch resolving bugs. */
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
        /* 关闭解决的 bugs。 */
        /* Close resolved bugs. */
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

        /* 返回批量关闭 bugs 后的响应。 */
        /* Return response after batch closing bugs. */
        $load = true;
        if($skipBugs) $load = array('confirm' => sprintf($this->lang->bug->notice->skipClose, implode(',', $skipBugs)), 'confirmed' => true, 'canceled' => true);
        if($viewType) $load = $this->createLink($viewType, 'view', "releaseID=$releaseID&type=bug");
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
            /* 获取原有 bug 信息。 */
            /* Get old bugs. */
            $oldBugs = $this->bug->getByIdList($this->post->id);

            /* 激活状态是解决或关闭的 bugs。 */
            /* Activate bugs whose status is resolved or closed. */
            $activateBugs = form::batchData($this->config->bug->form->batchActivate)->get();
            foreach($activateBugs as $bugID => $bug)
            {
                if($bug->status != 'resolved' && $bug->status != 'closed') continue;
                $oldBug = $oldBugs[$bugID];

                $bug->status         = 'active';
                $bug->activatedCount = $oldBug->activatedCount + 1;

                $this->bug->activate($bug);
                $message = $this->executeHooks($bug->id);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchOther');

            /* 返回批量激活 bugs 后的响应。 */
            /* Return response after batch activating bugs. */
            if(empty($message)) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->session->bugList));
        }

        /* 如果没有要激活的bugs，跳转到上一个界面。 */
        /* If there are no bugs to activate, locate to the previous interface. */
        if(!$this->post->bugIdList)
        {
            $url = !empty($this->session->bugList) ? $this->session->bugList : $this->createLink('qa', 'index');
            $this->locate($url);
        }


        $this->qa->setMenu($productID, $branch);

        /* 展示关联的变量。 */
        /* Show the variables associated. */
        $bugIdList = array_unique($this->post->bugIdList);
        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchActivate;
        $this->view->bugs      = $this->bug->getByIdList($bugIdList);
        $this->view->users     = $this->user->getPairs('noclosed');
        $this->view->builds    = $this->loadModel('build')->getBuildPairs(array($productID), $branch, 'noempty,noreleased');
        $this->view->productID = $productID;
        $this->display();
    }

    /**
     * 确认 bug 关联的需求变更。
     * Confirm story change.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function confirmStoryChange(int $bugID)
    {
        /* 获取更新前的 bug，并且检查所属执行的权限。*/
        /* Get old bug and check privilege of the execution. */
        $oldBug = $this->bug->getById($bugID);
        $this->bugZen->checkBugExecutionPriv($oldBug);

        /* 更新 bug 的需求版本。 */
        /* Update the version of the story. */
        $bug = new stdclass();
        $bug->id           = $bugID;
        $bug->storyVersion = $oldBug->latestStoryVersion;
        $this->bug->update($bug);

        $message = $this->executeHooks($bugID);

        /* 返回确认 bug 需求变更后的响应。 */
        /* Return response after confirming the version change of the story in the bug. */
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(empty($message)) $message = $this->lang->saveSuccess;
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "bugID={$bugID}")));
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
        /* 如果user id是0，把它设置为当前用户的id。 */
        /* If user id is zero, set it to current user id. */
        if($userID == 0) $userID = $this->app->user->id;

        /* 获取指派给user id的bug。 */
        /* Get bugs of user id. */
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
        /* 获取bug模块负责人。 */
        /* Get module owner. */
        list($account, $realname) = $this->bug->getModuleOwner($moduleID, $productID);
        return print(json_encode(array('account' => $account, 'realname' => $realname)));
    }

    /**
     * Ajax 方式加载指派给表单。
     * AJAX: get team members of the executions as assignedTo list.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function ajaxLoadAssignedTo(int $executionID): string
    {
        /* 获取执行团队成员，并将它转为picker组件需要的数据。 */
        /* Get execution team members, and convert it to picker items. */
        $members = $this->user->getTeamMemberPairs($executionID, 'execution');
        $items   = array();
        foreach($members as $account => $member)
        {
            if($account) $items[] = array('text' => $member, 'value' => $account);
        }
        return print(json_encode($items));
    }

    /**
     * Ajax 方式加载所有的用户。
     * AJAX: get all users as assignedTo list.
     *
     * @param  string $params noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname|outside|inside|all, can be sets of theme
     * @access public
     * @return array
     */
    public function ajaxLoadAllUsers(string $params = 'devfirst|noclosed')
    {
        /* 获取所有用户，并将它转为picker组件需要的数据。 */
        /* Get all users, and convert it to picker items. */
        $allUsers = $this->loadModel('user')->getPairs($params);
        $userList = array();
        foreach($allUsers as $account => $user) $userList[] = array('text' => $user, 'value' => $account, 'key' => $user . $account);
        return print(json_encode($userList));
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
            if($account) $items[] = array('text' => $member, 'value' => $account);
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
    public function ajaxGetProductBugs($productID, $bugID, $type = 'html'): int
    {
        /* 获取除了这个 bugID 的产品 bugs。 */
        /* Get product bugs exclude this bugID. */
        $search      = $this->get->search;
        $limit       = $this->get->limit;
        $product     = $this->loadModel('product')->getById($productID);
        $bug         = $this->bug->getById($bugID);
        $branch = $product->type == 'branch' ? ($bug->branch > 0 ? $bug->branch . ',0' : '0') : '';

        $productBugs = array();
        if($bug->resolution and $bug->duplicateBug)
        {
            $duplicateBug = $this->bug->getByID($bug->duplicateBug);
            $productBugs  = $this->bug->getProductBugPairs($productID, $branch, $duplicateBug->title, $limit);
        }
        $this->config->moreLinks['duplicateBug'] = inlink('ajaxGetProductBugs', "productID={$productID}&bugID={$bugID}&type=json");

        unset($productBugs[$bugID]);
        if($type == 'json') return print(helper::jsonEncode($productBugs));

        $bugList = array();
        foreach($productBugs as $bugID => $bugName) $bugList[] = array('value' => $bugID, 'text' => $bugName);
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
                $items[] = array('text' => $userName, 'value' => $account);
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

    /**
     * Ajax get relation cases.
     *
     * @param  int        $bugID
     * @access public
     * @return string
     */
    public function ajaxGetProductCases($bugID)
    {
        $search = $this->get->search;
        $limit  = $this->get->limit;

        $bug = $this->bug->getByID($bugID);

        $cases = $this->loadmodel('testcase')->getPairsByProduct($bug->product, array(0, $bug->branch), $search, $limit);

        return print(helper::jsonEncode($cases));
    }
}
