<?php
/**
 * The model file of test task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class testtaskModel extends model
{
    /**
     * Set the menu.
     *
     * @param  array $products
     * @param  int   $productID
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = 0, $testtask = 0)
    {
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $selectHtml = $this->product->select($products, $productID, 'testtask', 'browse', '', $branch);

        if($testtask and $this->app->viewType != 'mhtml')
        {
            $testtasks = $this->getProductTasks($productID, 0, 'id_desc', null, array('local', 'totalStatus'));
            if(!isset($testtasks[$testtask])) $testtasks[$testtask] = $this->getById($testtask);

            $selectHtml .= "<div class='btn-group angle-btn'>";
            $selectHtml .= "<div class='btn-group'>";
            $selectHtml .= "<a data-toggle='dropdown' class='btn'>" . $testtasks[$testtask]->name . " <span class='caret'></span></a>";
            $selectHtml .= "<ul class='dropdown-menu'>";
            foreach($testtasks as $testtask) $selectHtml .= '<li>' . html::a(helper::createLink('testtask', 'cases', "taskID=$testtask->id"), "<i class='icon icon-file-o'></i> {$testtask->name}") . '</li>';
            $selectHtml .= "</ul>";
            $selectHtml .= "</div>";
            $selectHtml .= "</div>";

            $this->lang->modulePageActions = '';
            if(common::hasPriv('testtask', 'view'))     $this->lang->modulePageActions .= html::a(helper::createLink('testtask', 'view', "taskID={$testtask->id}"), "<i class='icon icon-file-text'> </i>" . $this->lang->testtask->view, '', "class='btn'");
            if(common::hasPriv('testreport', 'browse')) $this->lang->modulePageActions .= html::a(helper::createLink('testreport', 'browse', "objectID=$productID&objectType=product&extra={$testtask->id}"), "<i class='icon icon-flag'> </i>" . $this->lang->testtask->reportField, '', "class='btn'");
        }

        $this->app->loadLang('qa');
        $productIndex  = '<div class="btn-group angle-btn"><div class="btn-group">' . html::a(helper::createLink('qa', 'index', 'locate=no'), $this->lang->qa->index, '', "class='btn'") . '</div></div>';
        $productIndex .= $selectHtml;

        $pageNav     = '';
        $pageActions = '';
        $isMobile    = $this->app->viewType == 'mhtml';
        if($isMobile)
        {
            $this->app->loadLang('qa');
            $pageNav  = html::a(helper::createLink('qa', 'index'), $this->lang->qa->index) . $this->lang->colon;
        }
        else
        {
            if($this->config->global->flow == 'full')
            {
                $this->app->loadLang('qa');
                $pageNav = '<div class="btn-group angle-btn"><div class="btn-group">' . html::a(helper::createLink('qa', 'index', 'locate=no'), $this->lang->qa->index, '', "class='btn'") . '</div></div>';
            }
            else
            {
                if(common::hasPriv('testtask', 'create'))
                {
                    $link = helper::createLink('testtask', 'create', "productID=$productID");
                    $pageActions .= html::a($link, "<i class='icon icon-plus'></i> {$this->lang->testtask->create}", '', "class='btn btn-primary'");
                }
            }
        }
        $pageNav .= $selectHtml;

        $this->lang->modulePageNav     = $pageNav;
        $this->lang->modulePageActions = $pageActions;
        foreach($this->lang->testtask->menu as $key => $value)
        {
            if($this->config->global->flow == 'full') $this->loadModel('qa')->setSubMenu('testtask', $key, $productID);
            if($this->config->global->flow != 'onlyTest')
            {
                $replace = ($key == 'product') ? $selectHtml : $productID;
            }
            else
            {
                if($key == 'product')
                {
                    $replace = $selectHtml;
                }
                elseif($key == 'scope')
                {
                    $scope = $this->session->testTaskVersionScope;
                    $status = $this->session->testTaskVersionStatus;
                    $viewName = $scope == 'local'? $products[$productID] : $this->lang->testtask->all;

                    $replace  = '<li>';
                    $replace .= "<a href='###' data-toggle='dropdown'>{$viewName} <span class='caret'></span></a>";
                    $replace .= "<ul class='dropdown-menu' style='max-height:240px;overflow-y:auto'>";
                    $replace .= "<li>" . html::a(helper::createLink('testtask', 'browse', "productID=$productID&branch=$branch&type=all,$status"), $this->lang->testtask->all) . "</li>";
                    $replace .= "<li>" . html::a(helper::createLink('testtask', 'browse', "productID=$productID&branch=$branch&type=local,$status"), $products[$productID]) . "</li>";
                    $replace .= "</ul></li>";
                }
                else
                {
                    $replace = array();
                    $replace['productID'] = $productID;
                    $replace['branch']    = $branch;
                    $replace['scope']     = $this->session->testTaskVersionScope;
                }
            }
            common::setMenuVars($this->lang->testtask->menu, $key, $replace);
        }
    }

    /**
     * Set unit menu.
     * 
     * @param  array  $products 
     * @param  int    $productID 
     * @param  int    $branch 
     * @param  int    $testtask 
     * @access public
     * @return void
     */
    public function setUnitMenu($products, $productID, $branch = 0, $testtask = 0)
    {
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $selectHtml = $this->product->select($products, $productID, 'testtask', 'browseUnits', '', $branch);

        if($testtask and $this->app->viewType != 'mhtml')
        {
            $testtasks = $this->getProductUnitTasks($productID, 'all', 'id_desc');
            if(!isset($testtasks[$testtask])) $testtasks[$testtask] = $this->getById($testtask);

            $selectHtml .= "<div class='btn-group angle-btn'>";
            $selectHtml .= "<div class='btn-group'>";
            $selectHtml .= "<a data-toggle='dropdown' class='btn'>" . $testtasks[$testtask]->name . " <span class='caret'></span></a>";
            $selectHtml .= "<ul class='dropdown-menu'>";
            foreach($testtasks as $testtask) $selectHtml .= '<li>' . html::a(helper::createLink('testtask', 'unitCases', "taskID=$testtask->id"), "<i class='icon icon-file-o'></i> {$testtask->name}") . '</li>';
            $selectHtml .= "</ul>";
            $selectHtml .= "</div>";
            $selectHtml .= "</div>";
        }

        $this->app->loadLang('qa');
        $productIndex  = '<div class="btn-group angle-btn"><div class="btn-group">' . html::a(helper::createLink('qa', 'index', 'locate=no'), $this->lang->qa->index, '', "class='btn'") . '</div></div>';
        $productIndex .= $selectHtml;

        $pageNav     = '';
        $pageActions = '';
        $isMobile    = $this->app->viewType == 'mhtml';
        if($isMobile)
        {
            $this->app->loadLang('qa');
            $pageNav  = html::a(helper::createLink('qa', 'index'), $this->lang->qa->index) . $this->lang->colon;
        }
        else
        {
            if($this->config->global->flow == 'full')
            {
                $this->app->loadLang('qa');
                $pageNav = '<div class="btn-group angle-btn"><div class="btn-group">' . html::a(helper::createLink('qa', 'index', 'locate=no'), $this->lang->qa->index, '', "class='btn'") . '</div></div>';
            }
        }
        $pageNav .= $selectHtml;

        $this->lang->modulePageNav     = $pageNav;
        $this->lang->modulePageActions = $pageActions;
        if($this->config->global->flow != 'full') $this->lang->testtask->menu = new stdclass();
        foreach($this->lang->testtask->menu as $key => $value)
        {
            if($this->config->global->flow == 'full') $this->loadModel('qa')->setSubMenu('testtask', $key, $productID);
            if($this->config->global->flow != 'onlyTest')
            {
                $replace = ($key == 'product') ? $selectHtml : $productID;
            }
            else
            {
                if($key == 'product') $replace = $selectHtml;
            }
            common::setMenuVars($this->lang->testtask->menu, $key, $replace);
        }
    }

    /**
     * Create a test task.
     *
     * @param  int   $productID
     * @access public
     * @return void
     */
    function create()
    {
        $task = fixer::input('post')
            ->setDefault('build', '')
            ->stripTags($this->config->testtask->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('uid,contactListMenu')
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TESTTASK)->data($task)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->testtask->create->requiredFields, 'notempty')
            ->checkIF($task->begin != '', 'begin', 'date')
            ->checkIF($task->end != '', 'end', 'date')
            ->checkIF($task->end != '', 'end', 'ge', $task->begin)
            ->exec();

        if(!dao::isError())
        {
            $taskID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $taskID, 'testtask');
            return $taskID;
        }
    }

    /**
     * Get test tasks of a product.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProductTasks($productID, $branch = 0, $orderBy = 'id_desc', $pager = null, $scopeAndStatus = array(), $beginTime = 0, $endTime = 0)
    {
        $products = $scopeAndStatus[0] == 'all' ? $this->app->user->view->products : array();
        if($this->config->global->flow == 'onlyTest')
        {
            return $this->dao->select("t1.*, t2.name AS productName,t4.name AS buildName, t4.branch AS branch")
                ->from(TABLE_TESTTASK)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.auto')->ne('unit')
                ->beginIF($scopeAndStatus[0] == 'local')->andWhere('t1.product')->eq((int)$productID)->fi()
                ->beginIF($scopeAndStatus[0] == 'all')->andWhere('t1.product')->in($products)->fi()
                ->beginIF($scopeAndStatus[1] == 'totalStatus')->andWhere('t1.status')->in(('blocked,doing,wait,done'))->fi()
                ->beginIF($scopeAndStatus[1] != 'totalStatus')->andWhere('t1.status')->eq($scopeAndStatus[1])->fi()
                ->beginIF($branch)->andWhere("t4.branch = '$branch'")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select("t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName, if(t4.name != '', t4.branch, t5.branch) AS branch")
                ->from(TABLE_TESTTASK)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
                ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project = t5.project and t1.product = t5.product')

                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.auto')->ne('unit')
                ->andWhere('t1.project')->in("0,{$this->app->user->view->projects}") //Fix bug #3260.
                ->beginIF($scopeAndStatus[0] == 'local')->andWhere('t1.product')->eq((int)$productID)->fi()
                ->beginIF($scopeAndStatus[0] == 'all')->andWhere('t1.product')->in($products)->fi()
                ->beginIF($scopeAndStatus[1] == 'totalStatus')->andWhere('t1.status')->in('blocked,doing,wait,done')->fi()
                ->beginIF($scopeAndStatus[1] != 'totalStatus')->andWhere('t1.status')->eq($scopeAndStatus[1])->fi()
                ->beginIF($branch)->andWhere("if(t4.branch, t4.branch, t5.branch) = '$branch'")->fi()
                ->beginIF($beginTime)->andWhere('t1.begin')->ge($beginTime)->fi()
                ->beginIF($endTime)->andWhere('t1.end')->le($endTime)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
    }

    /**
     * Get product unit tasks.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  string $orderBy 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getProductUnitTasks($productID, $browseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $beginAndEnd = $this->loadModel('action')->computeBeginAndEnd($browseType);
        if($browseType == 'newest') $orderBy = 'end_desc,' . $orderBy;
        if($this->config->global->flow == 'onlyTest')
        {
            $tasks = $this->dao->select("t1.*, t2.name AS productName,t4.name AS buildName")
                ->from(TABLE_TESTTASK)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('t1.auto')->eq('unit')
                ->beginIF($browseType != 'all' and $browseType != 'newest' and $beginAndEnd)
                ->andWhere('t1.end')->ge($beginAndEnd['begin'])
                ->andWhere('t1.end')->le($beginAndEnd['end'])
                ->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $tasks = $this->dao->select("t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName")
                ->from(TABLE_TESTTASK)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
                ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('t1.auto')->eq('unit')
                ->beginIF($browseType != 'all' and $browseType != 'newest' and $beginAndEnd)
                ->andWhere('t1.end')->ge($beginAndEnd['begin'])
                ->andWhere('t1.end')->le($beginAndEnd['end'])
                ->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        $resultGroups = $this->dao->select('t1.task, t2.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTRESULT)->alias('t2')->on('t1.id=t2.run')
            ->where('t1.task')->in(array_keys($tasks))
            ->fetchGroup('task', 'run');

        foreach($tasks as $taskID => $task)
        {
            $results = zget($resultGroups, $taskID, array());

            $task->caseCount = count($results);
            $task->passCount = 0;
            $task->failCount = 0;
            foreach($results as $result)
            {
                if($result->caseResult == 'pass') $task->passCount ++;
                if($result->caseResult == 'fail') $task->failCount ++;
            }
        }

        return $tasks;
    }

    /**
     * Get test tasks of a project.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectTasks($projectID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.build = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.auto')->ne('unit')
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get task by idList.
     *
     * @param  array    $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        return $this->dao->select("*")->from(TABLE_TESTTASK)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get test task info by id.
     *
     * @param  int   $taskID
     * @param  bool  $setImgSize
     * @access public
     * @return void
     */
    public function getById($taskID, $setImgSize = false)
    {
        if($this->config->global->flow == 'onlyTest')
        {
            $task = $this->dao->select("t1.*, t2.name AS productName, t2.type AS productType, t3.name AS buildName, t3.branch AS branch")
                ->from(TABLE_TESTTASK)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build = t3.id')
                ->where('t1.id')->eq((int)$taskID)
                ->fetch();
        }
        else
        {
            $task = $this->dao->select("*")->from(TABLE_TESTTASK)->where('id')->eq((int)$taskID)->fetch();
            if($task)
            {
                $product = $this->dao->select('name,type')->from(TABLE_PRODUCT)->where('id')->eq($task->product)->fetch();
                $task->productName = $product->name;
                $task->productType = $product->type;
                $task->branch      = 0;
                $task->projectName = '';
                $task->buildName   = '';

                if($task->project)
                {
                    $task->projectName = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($task->project)->fetch('name');
                    $task->branch      = $this->dao->select('branch')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($task->project)->andWhere('product')->eq($task->product)->fetch('branch');
                }

                $build = $this->dao->select('branch,name')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch();
                if($build)
                {
                    $task->buildName = $build->name;
                    $task->branch    = $build->branch;
                }
            }
        }

        if(!$task) return false;

        $task = $this->loadModel('file')->replaceImgURL($task, 'desc');
        if($setImgSize) $task->desc = $this->loadModel('file')->setImgSize($task->desc);
        return $task;
    }

    /**
     * Get test tasks by user.
     *
     * @param   string $account
     * @access  public
     * @return  array
     */
    public function getByUser($account, $pager = null, $orderBy = 'id_desc', $type = '')
    {
        return $this->dao->select('t1.*, t2.name AS projectName, t3.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build = t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.auto')->ne('unit')
            ->andWhere('t1.owner')->eq($account)
            ->andWhere('t2.id')->in($this->app->user->view->projects)
            ->beginIF($type == 'wait')->andWhere('t1.status')->ne('done')->fi()
            ->beginIF($type == 'done')->andWhere('t1.status')->eq('done')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }



    /**
     * Get taskrun by case id.
     *
     * @param  int    $taskID
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function getRunByCase($taskID, $caseID)
    {
        return $this->dao->select('*')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->eq($caseID)->fetch();
    }

    /**
     * Get linkable casses.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  int    $taskID
     * @param  string $type
     * @param  string $param
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCases($productID, $task, $taskID, $type, $param, $pager)
    {
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = $this->session->testcaseQuery;
        $allProduct = "`product` = 'all'";
        if(strpos($query, '`product` =') === false && $type != 'bysuite') $query .= " AND `product` = $productID";
        if(strpos($query, $allProduct) !== false) $query = str_replace($allProduct, '1', $query);

        $cases = array();
        $linkedCases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs('case');
        if($type == 'all')     $cases = $this->getAllLinkableCases($task, $query, $linkedCases, $pager);
        if($type == 'bystory') $cases = $this->getLinkableCasesByStory($productID, $task, $query, $linkedCases, $pager);
        if($type == 'bybug')   $cases = $this->getLinkableCasesByBug($productID, $task, $query, $linkedCases, $pager);
        if($type == 'bysuite') $cases = $this->getLinkableCasesBySuite($productID, $task, $query, $param, $linkedCases, $pager);
        if($type == 'bybuild') $cases = $this->getLinkableCasesByTestTask($param, $linkedCases, $query, $pager);

        return $cases;
    }

    /**
     * Get all linkable  cases.
     *
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getAllLinkableCases($task, $query, $linkedCases, $pager)
    {
        return $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('id')->notIN($linkedCases)
                ->andWhere('status')->ne('wait')
                ->beginIF($task->branch)->andWhere('branch')->in("0,$task->branch")->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
    }

    /**
     * Get linkable cases by story.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByStory($productID, $task, $query, $linkedCases, $pager)
    {
        $stories = $this->dao->select('stories')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('stories');
        $cases   = array();
        if($stories)
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('product')->eq($productID)
                ->andWhere('status')->ne('wait')
                ->beginIF($linkedCases)->andWhere('id')->notIN($linkedCases)->fi()
                ->beginIF($task->branch)->andWhere('branch')->in("0,$task->branch")->fi()
                ->andWhere('story')->in(trim($stories, ','))
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }

        return $cases;
    }

    /**
     * Get linkable cases by bug.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByBug($productID, $task, $query, $linkedCases, $pager)
    {
        $bugs = $this->dao->select('bugs')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('bugs');
        $cases = array();
        if($bugs)
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('product')->eq($productID)
                ->andWhere('status')->ne('wait')
                ->beginIF($linkedCases)->andWhere('id')->notIN($linkedCases)->fi()
                ->beginIF($task->branch)->andWhere('branch')->in("0,$task->branch")->fi()
                ->andWhere('fromBug')->in(trim($bugs, ','))
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }

        return $cases;
    }

    /**
     * Get linkable cases by suite.
     *
     * @param  int    $productID
     * @param  object $task
     * @param  string $query
     * @param  string $suite
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesBySuite($productID, $task, $query, $suite, $linkedCases, $pager)
    {
        if(strpos($query, '`product`') !== false) $query = str_replace('`product`', 't1.`product`', $query);
        return $this->dao->select('t1.*,t2.version as version')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
                ->where($query)
                ->andWhere('t2.suite')->eq((int)$suite)
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('status')->ne('wait')
                ->beginIF($linkedCases)->andWhere('t1.id')->notIN($linkedCases)->fi()
                ->beginIF($task->branch)->andWhere('t1.branch')->in("0,$task->branch")->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
    }

    /**
     * Get linkeable cases by test task.
     *
     * @param  string $testTask
     * @param  array  $linkedCases
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkableCasesByTestTask($testTask, $linkedCases, $query, $pager)
    {
        $caseList  = $this->dao->select("`case`")->from(TABLE_TESTRUN)->where('task')->eq($testTask)->andWhere('`case`')->notin($linkedCases)->fetchPairs('case');

        return $this->dao->select("*")->from(TABLE_CASE)->where($query)->andWhere('id')->in($caseList)->andWhere('status')->ne('wait')->page($pager)->fetchAll();
    }

    /**
     * Get related test tasks.
     *
     * @param  int    $productID
     * @param  int    $testtaskID
     * @access public
     * @return array
     */
    public function getRelatedTestTasks($productID, $testTaskID)
    {
        $beginDate = $this->dao->select('begin')->from(TABLE_TESTTASK)->where('id')->eq($testTaskID)->fetch('begin');

        return $this->dao->select('id, name')->from(TABLE_TESTTASK)
            ->where('product')->eq($productID)
            ->andWhere('auto')->ne('unit')
            ->beginIF($beginDate)->andWhere('begin')->le($beginDate)->fi()
            ->andWhere('deleted')->eq('0')
            ->andWhere('id')->notin($testTaskID)
            ->orderBy('begin desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get report data of test task per run result.
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerRunResult($taskID)
    {
        $datas = $this->dao->select("t1.lastRunResult AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')
            ->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('name')
            ->orderBy('value DESC')
            ->fetchAll('name');

        if(!$datas) return array();

        $this->app->loadLang('testcase');
        foreach($datas as $result => $data) $data->name = isset($this->lang->testcase->resultList[$result])? $this->lang->testcase->resultList[$result] : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * Get report data of test task per Type.
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerType($taskID)
    {
        $datas = $this->dao->select('t2.type as name,count(*) as value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('name')
            ->orderBy('value desc')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $result => $data) if(isset($this->lang->testcase->typeList[$result])) $data->name = $this->lang->testcase->typeList[$result];

        return $datas;
    }

    /**
     * Get report data of test task per module
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerModule($taskID)
    {
        $datas = $this->dao->select('t2.module as name,count(*) as value')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('name')
            ->orderBy('value desc')
            ->fetchAll('name');
        if(!$datas) return array();

        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas));
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';

        return $datas;
    }

    /**
     * Get report data of test task per runner
     *
     * @param  int     $taskID
     * @access public
     * @return array
     */
    public function getDataOfTestTaskPerRunner($taskID)
    {
        $datas = $this->dao->select("t1.lastRunner AS name, COUNT('t1.*') AS value")->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq($taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('name')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        $users = $this->loadModel('user')->getPairs('noclosed|noletter');
        foreach($datas as $result => $data) $data->name = $result ? zget($users, $result, $result) : $this->lang->testtask->unexecuted;

        return $datas;
    }

    /**
     * Get bug info.
     * 
     * @param  int    $taskID 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getBugInfo($taskID, $productID)
    {
        $foundBugs = $this->dao->select('*')->from(TABLE_BUG)->where('product')->in($productID)->andWhere('testtask')->eq($taskID)->andWhere('deleted')->eq(0)->fetchAll();

        $severityGroups = $statusGroups = $openedByGroups = $resolvedByGroups = $resolutionGroups = $moduleGroups = array();
        $resolvedBugs   = 0;
        foreach($foundBugs as $bug)
        {
            $severityGroups[$bug->severity] = isset($severityGroups[$bug->severity]) ? $severityGroups[$bug->severity] + 1 : 1;
            $statusGroups[$bug->status]     = isset($statusGroups[$bug->status])     ? $statusGroups[$bug->status]     + 1 : 1;
            $openedByGroups[$bug->openedBy] = isset($openedByGroups[$bug->openedBy]) ? $openedByGroups[$bug->openedBy] + 1 : 1;
            $moduleGroups[$bug->module]     = isset($moduleGroups[$bug->module])     ? $moduleGroups[$bug->module]     + 1 : 1;

            if($bug->resolvedBy) $resolvedByGroups[$bug->resolvedBy] = isset($resolvedByGroups[$bug->resolvedBy]) ? $resolvedByGroups[$bug->resolvedBy] + 1 : 1;
            if($bug->resolution) $resolutionGroups[$bug->resolution] = isset($resolutionGroups[$bug->resolution]) ? $resolutionGroups[$bug->resolution] + 1 : 1;
            if($bug->status == 'resolved' or $bug->status == 'closed') $resolvedBugs ++;
        }

        $bugInfo['bugConfirmedRate']    = empty($resolvedBugs) ? 0 : round((zget($resolutionGroups, 'fixed', 0) + zget($resolutionGroups, 'postponed', 0)) / $resolvedBugs * 100, 2);
        $bugInfo['bugCreateByCaseRate'] = empty($byCaseNum) ? 0 : round($byCaseNum / count($foundBugs) * 100, 2);

        $this->app->loadLang('bug');
        $users = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted');
        $data  = array();
        foreach($severityGroups as $severity => $count)
        {
            $data[$severity] = new stdclass();
            $data[$severity]->name  = zget($this->lang->bug->severityList, $severity);
            $data[$severity]->value = $count;
        }
        $bugInfo['bugSeverityGroups'] = $data;

        $data = array();
        foreach($statusGroups as $status => $count)
        {
            $data[$status] = new stdclass();
            $data[$status]->name  = zget($this->lang->bug->statusList, $status);
            $data[$status]->value = $count;
        }
        $bugInfo['bugStatusGroups'] = $data;

        $data = array();
        foreach($resolutionGroups as $resolution => $count)
        {
            $data[$resolution] = new stdclass();
            $data[$resolution]->name  = zget($this->lang->bug->resolutionList, $resolution);
            $data[$resolution]->value = $count;
        }
        $bugInfo['bugResolutionGroups'] = $data;

        $data = array();
        foreach($openedByGroups as $openedBy => $count)
        {
            $data[$openedBy] = new stdclass();
            $data[$openedBy]->name  = zget($users, $openedBy);
            $data[$openedBy]->value = $count;
        }
        $bugInfo['bugOpenedByGroups'] = $data;

        $this->loadModel('tree');
        $modules = $this->tree->getOptionMenu($productID, $viewType = 'bug');
        $data    = array();
        foreach($moduleGroups as $moduleID => $count)
        {
            $data[$moduleID] = new stdclass();
            $data[$moduleID]->name  = zget($modules, $moduleID);
            $data[$moduleID]->value = $count;
        }
        $bugInfo['bugModuleGroups'] = $data;

        $data = array();
        foreach($resolvedByGroups as $resolvedBy => $count)
        {
            $data[$resolvedBy] = new stdclass();
            $data[$resolvedBy]->name  = zget($users, $resolvedBy);
            $data[$resolvedBy]->value = $count;
        }
        $bugInfo['bugResolvedByGroups'] = $data;

        return $bugInfo;
    }

     /**
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string    $chartType
     * @access public
     * @return void
     */
    public function mergeChartOption($chartType)
    {
        $chartOption  = isset($this->lang->testtask->report->$chartType) ? $this->lang->testtask->report->$chartType : new stdclass();
        $commonOption = $this->lang->testtask->report->options;

        if(!isset($chartOption->graph)) $chartOption->graph = new stdclass();
        $chartOption->graph->caption = $this->lang->testtask->report->charts[$chartType];
        if(!isset($chartOption->type))    $chartOption->type  = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* 合并配置。*/
        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
        return $chartOption;
    }

    /**
     * Update a test task.
     *
     * @param  int   $taskID
     * @access public
     * @return void
     */
    public function update($taskID)
    {
        $oldTask = $this->dao->select("*")->from(TABLE_TESTTASK)->where('id')->eq((int)$taskID)->fetch();
        $task = fixer::input('post')->stripTags($this->config->testtask->editor->edit['id'], $this->config->allowedTags)->join('mailto', ',')->remove('uid,comment,contactListMenu')->get();
        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTTASK)->data($task)
            ->autoCheck()
            ->batchcheck($this->config->testtask->edit->requiredFields, 'notempty')
            ->checkIF($task->end != '', 'end', 'ge', $task->begin)
            ->where('id')->eq($taskID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $taskID, 'testtask');
            return common::createChanges($oldTask, $task);
        }
    }

    /**
     * Start testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->setDefault('status', 'doing')
            ->remove('comment')->get();

        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTesttask, $testtask);
    }

    /**
     * Close testtask.
     *
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->setDefault('status', 'done')
            ->stripTags($this->config->testtask->editor->close['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('comment,uid')
            ->get();

        $testtask = $this->loadModel('file')->processImgURL($testtask, $this->config->testtask->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $taskID, 'testtask');
            return common::createChanges($oldTesttask, $testtask);
        }
    }

    /**
     * update block testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function block($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->setDefault('status', 'blocked')
            ->remove('comment')->get();

        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTesttask, $testtask);
    }

    /**
     * update activate testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function activate($taskID)
    {
        $oldTesttask = $this->getById($taskID);
        $testtask = fixer::input('post')
            ->setDefault('status', 'doing')
            ->remove('comment')->get();

        $this->dao->update(TABLE_TESTTASK)->data($testtask)
            ->autoCheck()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTesttask, $testtask);
    }

    /**
     * Link cases.
     *
     * @param  int    $taskID
     * @param  string $type
     * @access public
     * @return void
     */
    public function linkCase($taskID, $type)
    {
        if($this->post->cases == false) return;
        $postData = fixer::input('post')->get();

        if($type == 'bybuild') $assignedToPairs = $this->dao->select('`case`, assignedTo')->from(TABLE_TESTRUN)->where('`case`')->in($postData)->fetchPairs('case', 'assignedTo');
        foreach($postData->cases as $caseID)
        {
            $row = new stdclass();
            $row->task       = $taskID;
            $row->case       = $caseID;
            $row->version    = $postData->versions[$caseID];
            $row->assignedTo = '';
            $row->status     = 'wait';
            if($type == 'bybuild') $row->assignedTo = zget($assignedToPairs, $caseID, '');

            $this->dao->replace(TABLE_TESTRUN)->data($row)->exec();
        }
    }

    /**
     * Get test runs of a test task.
     *
     * @param  int    $taskID
     * @param  int    $moduleID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRuns($taskID, $moduleID, $orderBy, $pager = null)
    {
        $orderBy = (strpos($orderBy, 'assignedTo') !== false or strpos($orderBy, 'lastRunResult') !== false) ? ('t1.' . $orderBy) : ('t2.' . $orderBy);

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq((int)$taskID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($moduleID)->andWhere('t2.module')->in($moduleID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get test runs of a user.
     *
     * @param  int    $taskID
     * @param  int    $user
     * @param  obejct $pager
     * @access public
     * @return array
     */
    public function getUserRuns($taskID, $user, $modules = '', $orderBy, $pager = null)
    {
        $orderBy = strpos($orderBy, 'assignedTo') !== false ? ('t1.' . $orderBy) : ('t2.' . $orderBy);

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.task')->eq((int)$taskID)
            ->andWhere('t1.assignedTo')->eq($user)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get testtask linked cases.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @param  object $task
     * @access public
     * @return array
     */
    public function getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task)
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->taskCaseBrowseType and $this->session->taskCaseBrowseType != 'bysearch') ? $this->session->taskCaseBrowseType : $browseType;

        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $runs = $this->getRuns($task->id, $modules, $sort, $pager);
        }
        elseif($browseType == 'assignedtome')
        {
            $runs = $this->getUserRuns($task->id, $this->session->user->account, $modules, $sort, $pager);
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            if($this->session->testtaskQuery == false) $this->session->set('testtaskQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('testtaskQuery', $query->sql);
                    $this->session->set('testtaskForm', $query->form);
                }
            }

            $queryProductID = $productID;
            $allProduct     = "`product` = 'all'";
            $caseQuery      = $this->session->testtaskQuery;
            if(strpos($this->session->testtaskQuery, $allProduct) !== false)
            {
                $caseQuery = str_replace($allProduct, '1', $this->session->testtaskQuery);
                $caseQuery = $caseQuery . ' AND `product` ' . helper::dbIN($this->app->user->view->products);
                $queryProductID = 'all';
            }

            $caseQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $caseQuery);
            $caseQuery = str_replace(array('t2.`assignedTo`', 't2.`lastRunner`', 't2.`lastRunDate`', 't2.`lastRunResult`', 't2.`status`'), array('t1.`assignedTo`', 't1.`lastRunner`', 't1.`lastRunDate`', 't1.`lastRunResult`', 't1.`status`'), $caseQuery);
            $runs = $this->dao->select('t2.*,t1.*, t2.version as caseVersion,t3.title as storyTitle,t2.status as caseStatus')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
                ->where($caseQuery)
                ->andWhere('t1.task')->eq($task->id)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($queryProductID != 'all')->andWhere('t2.product')->eq($queryProductID)->fi()
                ->beginIF($task->branch)->andWhere('t2.branch')->in("0,{$task->branch}")->fi()
                ->orderBy(strpos($sort, 'assignedTo') !== false ? ('t1.' . $sort) : ('t2.' . $sort))
                ->page($pager)
                ->fetchAll('id');
        }

        return $runs;
    }

    /**
     * Get info of a test run.
     *
     * @param  int   $runID
     * @access public
     * @return void
     */
    public function getRunById($runID)
    {
        $testRun = $this->dao->findById($runID)->from(TABLE_TESTRUN)->fetch();
        $testRun->case = $this->loadModel('testcase')->getById($testRun->case, $testRun->version);
        return $testRun;
    }

    /**
     * Create test result
     *
     * @param  int   $runID
     * @access public
     * @return void
     */
    public function createResult($runID = 0)
    {
        /* Compute the test result.
         *
         * 1. if there result in the post, use it.
         * 2. if no result, set default is pass.
         * 3. then check the steps to compute result.
         *
         * */
        $postData   = fixer::input('post')->get();
        $caseResult = isset($postData->result) ? $postData->result : 'pass';
        if(isset($postData->steps) and $postData->steps)
        {
            foreach($postData->steps as $stepID => $stepResult)
            {
                if($stepResult != 'pass' and $stepResult != 'n/a')
                {
                    $caseResult = $stepResult;
                    break;
                }
            }
        }

        /* Create result of every step. */
        foreach($postData->steps as $stepID =>$stepResult)
        {
            $step['result'] = $stepResult;
            $step['real']   = $postData->reals[$stepID];
            $stepResults[$stepID] = $step;
        }

        /* Insert into testResult table. */
        $now = helper::now();
        $result = fixer::input('post')
            ->add('run', $runID)
            ->add('caseResult', $caseResult)
            ->setForce('stepResults', serialize($stepResults))
            ->setDefault('lastRunner', $this->app->user->account)
            ->setDefault('date', $now)
            ->skipSpecial('stepResults')
            ->remove('steps,reals,result')
            ->get();

        /* Remove files and labels field when uploading files for case result or step result. */
        foreach($result as $fieldName => $field)
        {
            if((strpos($fieldName, 'files') !== false) or (strpos($fieldName, 'labels') !== false)) unset($result->$fieldName);
        }

        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();

        /* Save upload files for case result or step result. */
        if(!dao::isError())
        {
            $resultID = $this->dao->lastInsertID();
            foreach($stepResults as $stepID => $stepResult) $this->loadModel('file')->saveUpload('stepResult', $resultID, $stepID, "files{$stepID}", "labels{$stepID}");
        }
        $this->dao->update(TABLE_CASE)->set('lastRunner')->eq($this->app->user->account)->set('lastRunDate')->eq($now)->set('lastRunResult')->eq($caseResult)->where('id')->eq($postData->case)->exec();

        if($runID)
        {
            /* Update testRun's status. */
            if(!dao::isError())
            {
                $runStatus = $caseResult == 'blocked' ? 'blocked' : 'done';
                $this->dao->update(TABLE_TESTRUN)
                    ->set('lastRunResult')->eq($caseResult)
                    ->set('status')->eq($runStatus)
                    ->set('lastRunner')->eq($this->app->user->account)
                    ->set('lastRunDate')->eq($now)
                    ->where('id')->eq($runID)
                    ->exec();
            }
        }

        if(!dao::isError()) $this->loadModel('score')->create('testtask', 'runCase', $runID);

        return $caseResult;
    }

    /**
     * Batch run case
     *
     * @param  string $runCaseType
     * @access public
     * @return void
     */
    public function batchRun($runCaseType = 'testcase', $taskID = 0)
    {
        $runs = array();
        $postData   = fixer::input('post')->get();
        $caseIdList = array_keys($postData->results);
        if($runCaseType == 'testtask')
        {
            $runs = $this->dao->select('id, `case`')->from(TABLE_TESTRUN)
                ->where('`case`')->in($caseIdList)
                ->beginIF($taskID)->andWhere('task')->eq($taskID)->fi()
                ->fetchPairs('case', 'id');
        }

        $stepGroups = $this->dao->select('t1.*')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.case')->in($caseIdList)
            ->andWhere('t1.version=t2.version')
            ->andWhere('t2.status')->ne('wait')
            ->fetchGroup('case', 'id');

        $now = helper::now();
        foreach($postData->results as $caseID => $result)
        {
            $runID       = isset($runs[$caseID]) ? $runs[$caseID] : 0;
            $dbSteps     = isset($stepGroups[$caseID]) ? $stepGroups[$caseID] : array();
            $postSteps   = isset($postData->steps[$caseID]) ? $postData->steps[$caseID] : array();
            $postReals   = $postData->reals[$caseID];

            $caseResult  = $result ? $result : 'pass';
            $stepResults = array();
            if($dbSteps)
            {
                foreach($dbSteps as $stepID => $step)
                {
                    $step           = array();
                    $step['result'] = $caseResult == 'pass' ? $caseResult : $postSteps[$stepID];
                    $step['real']   = $caseResult == 'pass' ? '' : $postReals[$stepID];
                    $stepResults[$stepID] = $step;
                }
            }
            else
            {
                $step           = array();
                $step['result'] = $caseResult;
                $step['real']   = $caseResult == 'pass' ? '' : $postReals[0];
                $stepResults[] = $step;
            }

            $result              = new stdClass();
            $result->run         = $runID;
            $result->case        = $caseID;
            $result->version     = $postData->version[$caseID];
            $result->caseResult  = $caseResult;
            $result->stepResults = serialize($stepResults);
            $result->lastRunner  = $this->app->user->account;
            $result->date        = $now;
            $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
            $this->dao->update(TABLE_CASE)->set('lastRunner')->eq($this->app->user->account)->set('lastRunDate')->eq($now)->set('lastRunResult')->eq($caseResult)->where('id')->eq($caseID)->exec();

            if($runID)
            {
                /* Update testRun's status. */
                if(!dao::isError())
                {
                    $runStatus = $caseResult == 'blocked' ? 'blocked' : 'done';
                    $this->dao->update(TABLE_TESTRUN)
                        ->set('lastRunResult')->eq($caseResult)
                        ->set('status')->eq($runStatus)
                        ->set('lastRunner')->eq($this->app->user->account)
                        ->set('lastRunDate')->eq($now)
                        ->where('id')->eq($runID)
                        ->exec();
                }
            }
        }
    }

    /**
     * Get results by runID or caseID
     *
     * @param  int   $runID
     * @param  int   $caseID
     * @access public
     * @return array
     */
    public function getResults($runID, $caseID = 0)
    {
        if($runID > 0)
        {
            $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('run')->eq($runID)->orderBy('id desc')->fetchAll('id');
        }
        else
        {
            $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('`case`')->eq($caseID)->orderBy('id desc')->fetchAll('id');
        }

        if(!$results) return array();

        $relatedVersions = array();
        $runIdList       = array();
        foreach($results as $result)
        {
            $runIdList[$result->run] = $result->run;
            $relatedVersions[]       = $result->version;
            $runCaseID               = $result->case;
        }
        $relatedVersions = array_unique($relatedVersions);

        $relatedSteps = $this->dao->select('*')->from(TABLE_CASESTEP)
            ->where('`case`')->eq($runCaseID)
            ->andWhere('version')->in($relatedVersions)
            ->orderBy('id')
            ->fetchGroup('version', 'id');
        $runs = $this->dao->select('t1.id,t2.build')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTTASK)->alias('t2')->on('t1.task=t2.id')
            ->where('t1.id')->in($runIdList)
            ->fetchPairs();

        $this->loadModel('file');
        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where("(objectType = 'caseResult' or objectType = 'stepResult')")
            ->andWhere('objectID')->in(array_keys($results))
            ->andWhere('extra')->ne('editor')
            ->orderBy('id')
            ->fetchAll();
        $resultFiles = array();
        $stepFiles   = array();
        foreach($files as $file)
        {
            $pathName = $this->file->getRealPathName($file->pathname);
            $file->webPath  = $this->file->webPath . $pathName;
            $file->realPath = $this->file->savePath . $pathName;
            if($file->objectType == 'caseResult')
            {
                $resultFiles[$file->objectID][$file->id] = $file;
            }
            elseif($file->objectType == 'stepResult' and $file->extra !== '')
            {
                $stepFiles[$file->objectID][(int)$file->extra][$file->id] = $file;
            }
        }
        foreach($results as $resultID => $result)
        {
            $result->stepResults = unserialize($result->stepResults);
            $result->build       = $result->run ? zget($runs, $result->run, 0) : 0;
            $result->files       = zget($resultFiles, $resultID, array()); //Get files of case result.
            if(isset($relatedSteps[$result->version]))
            {
                $relatedStep = $relatedSteps[$result->version];
                foreach($relatedStep as $stepID => $step)
                {
                    $relatedStep[$stepID] = (array)$step;
                    if(isset($result->stepResults[$stepID]))
                    {
                        $relatedStep[$stepID]['result'] = $result->stepResults[$stepID]['result'];
                        $relatedStep[$stepID]['real']   = $result->stepResults[$stepID]['real'];
                    }
                }
                $result->stepResults = $relatedStep;
            }

            /* Get files of step result. */
            foreach($result->stepResults as $stepID => $stepResult) $result->stepResults[$stepID]['files'] = isset($stepFiles[$resultID][$stepID]) ? $stepFiles[$resultID][$stepID] : array();
        }
        return $results;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object $product
     * @param  string $action
     * @access public
     * @return void
     */
    public static function isClickable($testtask, $action)
    {
        $action = strtolower($action);

        if($action == 'start')    return $testtask->status  == 'wait';
        if($action == 'block')    return ($testtask->status == 'doing'   || $testtask->status == 'wait');
        if($action == 'activate') return ($testtask->status == 'blocked' || $testtask->status == 'done');
        if($action == 'close')    return $testtask->status != 'done';
        if($action == 'runcase' and $testtask->auto == 'unit')  return false;
        if($action == 'runcase')  return isset($testtask->caseStatus) ? $testtask->caseStatus != 'wait' : $testtask->status != 'wait';
        return true;
    }

    /**
     * Print cell data.
     *
     * @param  object  $col
     * @param  object  $run
     * @param  array   $users
     * @param  object  $task
     * @param  array   $branches
     * @access public
     * @return void
     */
    public function printCell($col, $run, $users, $task, $branches, $mode = 'datatable')
    {
        $canView     = common::hasPriv('testcase', 'view');
        $caseLink    = helper::createLink('testcase', 'view', "caseID=$run->case&version=$run->version&from=testtask&taskID=$run->task");
        $account     = $this->app->user->account;
        $id          = $col->id;
        $caseChanged = $run->version < $run->caseVersion;
        if($col->show)
        {
            $class = "c-$id ";
            if($id == 'status') $class .= $run->status;
            if($id == 'title')  $class .= ' text-left';
            if($id == 'id')     $class .= ' cell-id';
            if($id == 'lastRunResult') $class .= " $run->lastRunResult";
            if($id == 'assignedTo' && $run->assignedTo == $account) $class .= ' red';
            if($id == 'actions') $class .= 'c-actions';

            echo "<td class='" . $class . "'" . ($id=='title' ? "title='{$run->title}'":'') . ">";
            if(isset($this->config->bizVersion)) $this->loadModel('flow')->printFlowCell('testcase', $run, $id);
            switch ($id)
            {
            case 'id':
                echo html::checkbox('caseIDList', array($run->case => sprintf('%03d', $run->case)));
                break;
            case 'pri':
                echo "<span class='label-pri label-pri-" . $run->pri . "' title='" . zget($this->lang->testcase->priList, $run->pri, $run->pri) . "'>";
                echo zget($this->lang->testcase->priList, $run->pri, $run->pri);
                echo "</span>";
                break;
            case 'title':
                if($run->branch) echo "<span class='label label-info label-outline'>{$branches[$run->branch]}</span>";
                echo $canView ? html::a($caseLink, $run->title, null, "style='color: $run->color'") : $run->title;
                break;
            case 'branch':
                echo $branches[$run->branch];
                break;
            case 'type':
                echo $this->lang->testcase->typeList[$run->type];
                break;
            case 'stage':
                foreach(explode(',', trim($run->stage, ',')) as $stage) echo $this->lang->testcase->stageList[$stage] . '<br />';
                break;
            case 'status':
                echo $caseChanged ? "<span class='warning'>{$this->lang->testcase->changed}</span>" : $this->processStatus('testtask', $run);
                break;
            case 'precondition':
                echo $run->precondition;
                break;
            case 'keywords':
                echo $run->keywords;
                break;
            case 'version':
                echo $run->version;
                break;
            case 'openedBy':
                echo zget($users, $run->openedBy);
                break;
            case 'openedDate':
                echo substr($run->openedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo zget($users, $run->reviewedBy);
                break;
            case 'reviewedDate':
                echo substr($run->reviewedDate, 5, 11);
                break;
            case 'lastEditedBy':
                echo zget($users, $run->lastEditedBy);
                break;
            case 'lastEditedDate':
                echo substr($run->lastEditedDate, 5, 11);
                break;
            case 'lastRunner':
                echo zget($users, $run->lastRunner);
                break;
            case 'lastRunDate':
                if(!helper::isZeroDate($run->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($run->lastRunDate));
                break;
            case 'lastRunResult':
                $lastRunResultText = $run->lastRunResult ? zget($this->lang->testcase->resultList, $run->lastRunResult, $run->lastRunResult) : $this->lang->testcase->unexecuted;
                $class = 'result-' . $run->lastRunResult;
                echo "<span class='$class'>" . $lastRunResultText . "</span>";
                break;
            case 'story':
                if($run->story and $run->storyTitle) echo html::a(helper::createLink('story', 'view', "storyID=$run->story"), $run->storyTitle);
                break;
            case 'assignedTo':
                echo zget($users, $run->assignedTo);
                break;
            case 'bugs':
                echo (common::hasPriv('testcase', 'bugs') and $run->bugs) ? html::a(helper::createLink('testcase', 'bugs', "runID={$run->id}&caseID={$run->case}"), $run->bugs, '', "class='iframe'") : $run->bugs;
                break;
            case 'results':
                echo (common::hasPriv('testtask', 'results') and $run->results) ? html::a(helper::createLink('testtask', 'results', "runID={$run->id}&caseID={$run->case}"), $run->results, '', "class='iframe'") : $run->results;
                break;
            case 'stepNumber':
                echo $run->stepNumber;
                break;
            case 'actions':
                if($caseChanged)
                {
                    common::printIcon('testcase', 'confirmChange', "id=$run->case&taskID=$run->task&from=list", $run, 'list', 'search', 'hiddenwin');
                    break;
                }

                common::printIcon('testcase', 'createBug', "product=$run->product&branch=$run->branch&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,version=$run->version,runID=$run->id,testtask=$task->id", $run, 'list', 'bug', '', 'iframe', '', "data-width='90%'");

                common::printIcon('testtask', 'results', "id=$run->id", $run, 'list', '', '', 'iframe', '', "data-width='90%'");
                common::printIcon('testtask', 'runCase', "id=$run->id", $run, 'list', '', '', 'runCase iframe', false, "data-width='95%'");

                if(common::hasPriv('testtask', 'unlinkCase', $run))
                {
                    $unlinkURL = helper::createLink('testtask', 'unlinkCase', "caseID=$run->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"casesForm\", confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$this->lang->testtask->unlinkCase}' class='btn'");
                }

                break;
            }
            echo '</td>';
        }
    }

    /**
     * Send mail.
     *
     * @param  int    $testtaskID
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function sendmail($testtaskID, $actionID)
    {
        $this->loadModel('mail');
        $testtask = $this->getByID($testtaskID);
        $users    = $this->loadModel('user')->getPairs('noletter');

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'testtask');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        $sendUsers = $this->getToAndCcList($testtask);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;
        $subject = $this->getSubject($testtask, $action->action);

        /* Send mail. */
        $this->mail->send($toList, $subject, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * Get mail subject.
     *
     * @param  object    $testtask
     * @param  string    $actionType
     * @access public
     * @return string
     */
    public function getSubject($testtask, $actionType)
    {
        /* Set email title. */
        if($actionType == 'opened')
        {
            return sprintf($this->lang->testtask->mail->create->title, $this->app->user->realname, $testtask->id, $testtask->name);
        }
        elseif($actionType == 'closed')
        {
            return sprintf($this->lang->testtask->mail->close->title, $this->app->user->realname, $testtask->id, $testtask->name);
        }
        else
        {
            return sprintf($this->lang->testtask->mail->edit->title, $this->app->user->realname, $testtask->id, $testtask->name);
        }
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $testtask
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($testtask)
    {
        /* Set toList and ccList. */
        $toList   = $testtask->owner;
        $ccList   = str_replace(' ', '', trim($testtask->mailto, ','));

        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        return array($toList, $ccList);
    }

    /**
     * Import unit results.
     * 
     * @param  int    $productID 
     * @access public
     * @return string
     */
    public function importUnitResult($productID)
    {
        $frame = $this->post->frame;
        unset($_POST['frame']);

        $fileName = $this->session->resultFile;
        $data     = $this->parseXMLResult($fileName, $productID, $frame);
        if($frame == 'cppunit' and empty($data['cases'])) $data = $this->parseCppXMLResult($fileName, $productID, $frame);

        /* Create task. */
        $this->post->set('auto', 'unit');
        $testtaskID = $this->create();

        unlink($fileName);
        unset($_SESSION['resultFile']);
        if(dao::isError()) return false;

        return $this->processAutoResult($testtaskID, $productID, $data['suites'], $data['cases'], $data['results'], $data['suiteNames'], $data['caseTitles'], 'unit');
    }

    /**
     * Process auto test result.
     * 
     * @param  int    $testtaskID 
     * @param  int    $productID 
     * @param  array  $suites 
     * @param  array  $cases 
     * @param  array  $results 
     * @param  array  $suiteNames 
     * @param  array  $caseTitles 
     * @param  string $auto     unit|func 
     * @access public
     * @return int
     */
    public function processAutoResult($testtaskID, $productID, $suites, $cases, $results, $suiteNames = array(), $caseTitles = array(), $auto = 'unit')
    {
        if(empty($cases)) die(js::alert($this->lang->testtask->noImportData));

        /* Import cases and link task and insert result. */
        $this->loadModel('action');
        $existSuites = $this->dao->select('*')->from(TABLE_TESTSUITE)->where('name')->in($suiteNames)->andWhere('product')->eq($productID)->andWhere('type')->eq($auto)->andWhere('deleted')->eq(0)->fetchPairs('name', 'id');
        foreach($suites as $suiteIndex => $suite)
        {
            $suiteID = 0;
            if($suite)
            {
                if(!isset($existSuites[$suite->name]))
                {
                    $this->dao->insert(TABLE_TESTSUITE)->data($suite)->exec();
                    $suiteID = $this->dao->lastInsertID();
                    $this->action->create('testsuite', $suiteID, 'opened');
                }
                else
                {
                    $suiteID = $existSuites[$suite->name];
                }
            }

            if($suiteID)
            {
                $existCases = $this->dao->select('t1.*')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
                    ->where('t1.title')->in($caseTitles[$suiteIndex])
                    ->andWhere('t1.product')->eq($productID)
                    ->beginIF($auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
                    ->andWhere('t1.deleted')->eq(0)
                    ->orderBy('id')
                    ->fetchPairs('title', 'id');
            }
            else
            {
                $existCases = $this->dao->select('*')->from(TABLE_CASE)
                    ->where('title')->in($caseTitles[$suiteIndex])
                    ->beginIF($auto == 'unit')->andWhere('auto')->eq($auto)->fi()
                    ->andWhere('product')->eq($productID)
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('id')
                    ->fetchPairs('title', 'id');
            }

            foreach($cases[$suiteIndex] as $i => $case)
            {
                if(isset($case->id))
                {
                    $caseID = $case->id;
                    $this->dao->update(TABLE_CASE)->data($case)->where('id')->eq($caseID)->exec();
                }
                elseif(!isset($existCases[$case->title]))
                {
                    $this->dao->insert(TABLE_CASE)->data($case)->exec();
                    $caseID = $this->dao->lastInsertID();
                    $this->action->create('case', $caseID, 'Opened');
                }
                else
                {
                    $caseID = $existCases[$case->title];
                }

                $testrun = new stdclass();
                $testrun->task          = $testtaskID;
                $testrun->case          = $caseID;
                $testrun->version       = $case->version;
                $testrun->lastRunner    = $case->lastRunner;
                $testrun->lastRunDate   = $case->lastRunDate;
                $testrun->lastRunResult = $case->lastRunResult;
                $testrun->status        = 'done';

                $this->dao->replace(TABLE_TESTRUN)->data($testrun)->exec();
                $runID = $this->dao->lastInsertID();

                if($suiteID)
                {
                    $suitecase = new stdclass();
                    $suitecase->suite   = $suiteID;
                    $suitecase->case    = $caseID;
                    $suitecase->version = $case->version;
                    $suitecase->product = $case->product;
                    $this->dao->replace(TABLE_SUITECASE)->data($suitecase)->exec();
                }

                $testresult = $results[$suiteIndex][$i];
                $testresult->run  = $runID;
                $testresult->case = $caseID;
                $this->dao->insert(TABLE_TESTRESULT)->data($testresult)->exec();
            }
        }

        return $testtaskID;
    }

    /**
     * Parse cppunit XML result.
     * 
     * @param  string $fileName 
     * @param  int    $productID 
     * @param  string $frame 
     * @access public
     * @return array
     */
    public function parseCppXMLResult($fileName, $productID, $frame)
    {
        /* Parse result xml. */
        $parsedXML = simplexml_load_file($fileName);

        /* Get testcase node. */
        $failNodes  = $parsedXML->xpath('FailedTests/FailedTest');
        $passNodes  = $parsedXML->xpath('SuccessfulTests/Test');
        $matchNodes = array_merge($failNodes, $passNodes);
        if(count($matchNodes) == 0) return array('suites' => array(), 'cases' => array(), 'results' => array(), 'suiteNames' => array(), 'caseTitles' => array());

        /* Get cases and results by parsed node. */
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $caseTitles = array();
        $suiteNames = array();
        $suiteIndex = 0;
        $suites     = array($suiteIndex => '');
        foreach($matchNodes as $caseIndex => $matchNode)
        {
            $case = new stdclass();
            $case->product    = $productID;
            $case->title      = (string)$matchNode->Name;
            $case->pri        = 3;
            $case->type       = 'unit';
            $case->stage      = 'unittest';
            $case->status     = 'normal';
            $case->openedBy   = $this->app->user->account;
            $case->openedDate = $now;
            $case->version    = 1;
            $case->auto       = 'unit';
            $case->frame      = $frame ? $frame : 'junit';

            $result = new stdclass();
            $result->case       = 0;
            $result->version    = 1;
            $result->caseResult = 'pass';
            $result->lastRunner = $this->app->user->account;
            $result->date       = $now;
            $result->duration   = 0;
            $result->xml        = $matchNode->asXML();
            $result->stepResults[0]['result'] = 'pass';
            $result->stepResults[0]['real']   = '';
            if(isset($matchNode->Message))
            {
                $result->caseResult = 'fail';
                $result->stepResults[0]['result'] = 'fail';
                $result->stepResults[0]['real']   = (string)$matchNode->Message;
            }
            $result->stepResults = serialize($result->stepResults);
            $case->lastRunner    = $this->app->user->account;
            $case->lastRunDate   = $now;
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Parse unit result from xml.
     * 
     * @param  string $fileName 
     * @param  int    $productID 
     * @param  string $frame 
     * @access public
     * @return array
     */
    public function parseXMLResult($fileName, $productID, $frame)
    {
        /* Parse result xml. */
        $rules     = zget($this->config->testtask->unitResultRules, $frame, $this->config->testtask->unitResultRules->common);
        $parsedXML = simplexml_load_file($fileName);

        /* Get testcase node. */
        $matchPaths = $rules['path'];
        $nameFields = $rules['name'];
        $failure    = $rules['failure'];
        $suiteField = $rules['suite'];
        $aliasSuite = zget($rules, 'aliasSuite', array());
        $aliasName  = zget($rules, 'aliasName', array());
        $matchNodes = array();
        foreach($matchPaths as $matchPath)
        {
            $matchNodes = $parsedXML->xpath($matchPath);
            if(count($matchNodes) != 0) break;
        }
        if(count($matchNodes) == 0) return array('suites' => array(), 'cases' => array(), 'results' => array(), 'suiteNames' => array(), 'caseTitles' => array());

        $parentPath  = '';
        $caseNode    = $matchPath;
        $parentNodes = array($parsedXML);
        if(strpos($matchPath, '/') !== false)
        {
            $explodedPath = explode('/', $matchPath);
            $caseNode     = array_pop($explodedPath);
            $parentPath   = implode('/', $explodedPath);
            $parentNodes  = $parsedXML->xpath($parentPath);
        }

        /* Get cases and results by parsed node. */
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $suites     = array();
        $caseTitles = array();
        $suiteNames = array();
        foreach($parentNodes as $suiteIndex => $parentNode)
        {
            $caseNodes  = $parentNode->xpath($caseNode);
            $attributes = $parentNode->attributes();
            $suite      = '';
            if(isset($attributes[$suiteField]))
            {
                $suite = new stdclass();
                $suite->product   = $productID;
                $suite->name      = (string)$attributes[$suiteField];
                $suite->type      = 'unit';
                $suite->addedBy   = $this->app->user->account;
                $suite->addedDate = $now;
                $suiteNames[]     = $suite->name;
            }
            else
            {
                $attributes = $caseNodes[0]->attributes();
                foreach($aliasSuite as $alias)
                {
                    if(isset($attributes[$alias]))
                    {
                        $suite = new stdclass();
                        $suite->product   = $productID;
                        $suite->name      = (string)$attributes[$alias];
                        $suite->type      = 'unit';
                        $suite->addedBy   = $this->app->user->account;
                        $suite->addedDate = $now;
                        $suiteNames[]     = $suite->name;
                        break;
                    }
                }
            }
            $suites[$suiteIndex] = $suite;

            foreach($caseNodes as $caseIndex => $matchNode)
            {
                $case = new stdclass();
                $case->product    = $productID;
                $case->title      = '';
                $case->pri        = 3;
                $case->type       = 'unit';
                $case->stage      = 'unittest';
                $case->status     = 'normal';
                $case->openedBy   = $this->app->user->account;
                $case->openedDate = $now;
                $case->version    = 1;
                $case->auto       = 'unit';
                $case->frame      = $frame ? $frame : 'junit';

                $attributes = $matchNode->attributes();
                foreach($nameFields as $field)
                {
                    if(!isset($attributes[$field])) continue;
                    $case->title .= (string)$attributes[$field] . ' ';
                }
                $case->title = trim($case->title);
                if(empty($case->title))
                {
                    foreach($aliasName as $field)
                    {
                        if(!isset($attributes[$field])) continue;
                        $case->title .= (string)$attributes[$field] . ' ';
                    }
                    $case->title = trim($case->title);
                }
                if(empty($case->title)) continue;

                $result = new stdclass();
                $result->case       = 0;
                $result->version    = 1;
                $result->caseResult = 'pass';
                $result->lastRunner = $this->app->user->account;
                $result->date       = $now;
                $result->duration   = isset($attributes['time']) ? (float)$attributes['time'] : 0;
                $result->xml        = $matchNode->asXML();
                $result->stepResults[0]['result'] = 'pass';
                $result->stepResults[0]['real']   = '';
                if(isset($matchNode->$failure))
                {
                    $result->caseResult = 'fail';
                    $result->stepResults[0]['result'] = 'fail';
                    if(is_string($matchNode->$failure))
                    {
                        $result->stepResults[0]['real'] = (string)$matchNode->$failure;
                    }
                    elseif(isset($matchNode->$failure[0]))
                    {
                        $result->stepResults[0]['real'] = (string)$matchNode->$failure[0];
                    }
                    else
                    {
                        $failureAttrs = $matchNode->$failure->attributes();
                        $result->stepResults[0]['real'] = (string)$failureAttrs['message'];
                    }
                }
                $result->stepResults = serialize($result->stepResults);
                $case->lastRunner    = $this->app->user->account;
                $case->lastRunDate   = $now;
                $case->lastRunResult = $result->caseResult;

                $caseTitles[$suiteIndex][]        = $case->title;
                $cases[$suiteIndex][$caseIndex]   = $case;
                $results[$suiteIndex][$caseIndex] = $result;
            }
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Parse unit result from ztf.
     * 
     * @param  array  $caseResults 
     * @param  string $frame 
     * @param  int    $productID 
     * @param  int    $jobID 
     * @param  int    $compileID 
     * @access public
     * @return array
     */
    public function parseZTFUnitResult($caseResults, $frame, $productID, $jobID, $compileID)
    {
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $suites     = array();
        $caseTitles = array();
        $suiteNames = array();
        $suiteIndex = 0;
        foreach($caseResults as $caseIndex => $caseResult)
        {
            $suite = '';
            if(isset($caseResult->testSuite) and !isset($suiteNames[$caseResult->testSuite]))
            {
                $suite = new stdclass();
                $suite->product   = $productID;
                $suite->name      = $caseResult->testSuite;
                $suite->type      = 'unit';
                $suite->addedBy   = $this->app->user->account;
                $suite->addedDate = $now;

                $suiteNames[$suite->name] = $suite->name;
                $suiteIndex ++;
            }
            if(!isset($suites[$suiteIndex])) $suites[$suiteIndex] = $suite;

            $case = new stdclass();
            $case->product    = $productID;
            $case->title      = $caseResult->title;
            $case->pri        = 3;
            $case->type       = 'unit';
            $case->stage      = 'unittest';
            $case->status     = 'normal';
            $case->openedBy   = $this->app->user->account;
            $case->openedDate = $now;
            $case->version    = 1;
            $case->auto       = 'unit';
            $case->frame      = $frame;

            $result = new stdclass();
            $result->case       = 0;
            $result->version    = 1;
            $result->caseResult = 'pass';
            $result->lastRunner = $this->app->user->account;
            $result->job        = $jobID;
            $result->compile    = $compileID;
            $result->date       = $now;
            $result->duration   = zget($caseResult, 'duration', 0);
            $result->stepResults[0]['result'] = 'pass';
            $result->stepResults[0]['real']   = '';
            if(!empty($caseResult->failure))
            {
                $result->caseResult = 'fail';
                $result->stepResults[0]['result'] = 'fail';
                $result->stepResults[0]['real']   = zget($caseResult->failure, 'desc', '');
            }
            $result->stepResults = serialize($result->stepResults);
            $case->lastRunner    = $this->app->user->account;
            $case->lastRunDate   = $now;
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }

    /**
     * Parse function result from ztf.
     * 
     * @param  array  $caseResults 
     * @param  string $frame 
     * @param  int    $productID 
     * @param  int    $jobID 
     * @param  int    $compileID 
     * @access public
     * @return array
     */
    public function parseZTFFuncResult($caseResults, $frame, $productID, $jobID, $compileID)
    {
        $now        = helper::now();
        $cases      = array();
        $results    = array();
        $suites     = array();
        $caseTitles = array();
        $suiteNames = array();
        $suiteIndex = 0;
        foreach($caseResults as $caseIndex => $caseResult)
        {
            $suite = '';
            if(!isset($suites[$suiteIndex])) $suites[$suiteIndex] = $suite;

            $case = new stdclass();
            $case->product    = $productID;
            $case->title      = $caseResult->title;
            $case->pri        = 3;
            $case->type       = 'feature';
            $case->stage      = 'feature';
            $case->status     = 'normal';
            $case->openedBy   = $this->app->user->account;
            $case->openedDate = $now;
            $case->version    = 1;
            $case->auto       = 'func';
            $case->frame      = $frame;

            $result = new stdclass();
            $result->case       = 0;
            $result->version    = 1;
            $result->caseResult = 'pass';
            $result->lastRunner = $this->app->user->account;
            $result->job        = $jobID;
            $result->compile    = $compileID;
            $result->date       = $now;
            $result->stepResults[0]['result'] = 'pass';
            $result->stepResults[0]['real']   = '';
            if(!empty($caseResult->steps))
            {
                $result->stepResults = array();
                $stepStatus = 'pass';
                foreach($caseResult->steps as $i => $step)
                {
                    $result->stepResults[$i]['result'] = $step->status ? 'pass' : 'fail';
                    $result->stepResults[$i]['real']   = $step->status ? '' : $step->checkPoints[0]->actual;
                    if(!$step->status) $stepStatus = 'fail';
                }
                $result->caseResult = $stepStatus;
            }
            $result->stepResults = serialize($result->stepResults);
            $case->lastRunner    = $this->app->user->account;
            $case->lastRunDate   = $now;
            $case->lastRunResult = $result->caseResult;

            $caseTitles[$suiteIndex][]        = $case->title;
            $cases[$suiteIndex][$caseIndex]   = $case;
            $results[$suiteIndex][$caseIndex] = $result;
        }

        return array('suites' => $suites, 'cases' => $cases, 'results' => $results, 'suiteNames' => $suiteNames, 'caseTitles' => $caseTitles);
    }
}
