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
    public function setMenu($products, $productID, $branch = 0)
    {
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $selectHtml = $this->product->select($products, $productID, 'testtask', 'browse', '', $branch);
        foreach($this->lang->testtask->menu as $key => $value)
        {
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
                    $replace .= "<a data-toggle='dropdown'>{$viewName} <span class='caret'></span></a>";
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
     * Create a test task.
     * 
     * @param  int   $productID 
     * @access public
     * @return void
     */
    function create()
    {
        $task = fixer::input('post')->stripTags($this->config->testtask->editor->create['id'], $this->config->allowedTags)->join('mailto', ',')->remove('uid')->get();
        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TESTTASK)->data($task)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->testtask->create->requiredFields, 'notempty')
            ->checkIF($task->begin != '', 'begin', 'date')
            ->checkIF($task->end   != '', 'end', 'date')
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
    public function getProductTasks($productID, $branch = 0, $orderBy = 'id_desc', $pager = null, $scopeAndStatus = array())
    {
        if($this->config->global->flow == 'onlyTest')
        {
            return $this->dao->select("t1.*, t2.name AS productName, t4.name AS buildName, t4.branch AS branch")
                ->from(TABLE_TESTTASK)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF($scopeAndStatus[0] == 'local')->andWhere('t1.product')->eq((int)$productID)->fi()
                ->beginIF($scopeAndStatus[1] == 'totalStatus')->andWhere('t1.status')->in(array('blocked','doing','wait','done'))->fi()
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
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project = t5.project')
                ->where('t1.deleted')->eq(0)
                ->beginIF($scopeAndStatus[0] == 'local')->andWhere('t1.product')->eq((int)$productID)->fi()
                ->andWhere('t5.product = t1.product')
                ->beginIF($scopeAndStatus[1] == 'totalStatus')->andWhere('t1.status')->in(array('blocked','doing','wait','done'))->fi()
                ->beginIF($scopeAndStatus[1] != 'totalStatus')->andWhere('t1.status')->eq($scopeAndStatus[1])->fi()
                ->beginIF($branch)->andWhere("if(t4.branch, t4.branch, t5.branch) = '$branch'")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
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
        $task = $this->dao->select("t1.*, t2.name AS productName, t2.type AS productType, t3.name AS projectName, t4.name AS buildName, if(t4.name != '', t4.branch, t5.branch) AS branch")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project = t5.project')
            ->where('t1.id')->eq((int)$taskID)
            ->andWhere('t5.product = t1.product')
            ->fetch();
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
            ->andWhere('t1.owner')->eq($account)
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
        if($type == 'bybuild') $cases = $this->getLinkableCasesByTestTask($param, $linkedCases, $pager);

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
    public function getLinkableCasesByTestTask($testTask, $linkedCases, $pager)
    {
        $caseList  = $this->dao->select("`case`")->from(TABLE_TESTRUN)->where('task')->eq($testTask)->andWhere('`case`')->notin($linkedCases)->fetchPairs('case');
        
        return $this->dao->select("*")->from(TABLE_CASE)->where('id')->in($caseList)->andWhere('status')->ne('wait')->page($pager)->fetchAll();
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
        $datas = $this->dao->select('lastRunResult AS name, COUNT(*) AS value')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

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
        $datas = $this->dao->select('lastRunner AS name, COUNT(*) AS value')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $result => $data) $data->name = $result ? $result : $this->lang->testtask->unexecuted;

        return $datas;
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
        $task = fixer::input('post')->stripTags($this->config->testtask->editor->edit['id'], $this->config->allowedTags)->join('mailto', ',')->remove('uid')->get();
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

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion,t3.title as storyTitle')->from(TABLE_TESTRUN)->alias('t1')
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

        return $this->dao->select('t2.*,t1.*,t2.version as caseVersion')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
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
                $products  = array_keys($this->loadModel('product')->getPrivProducts());
                $caseQuery = str_replace($allProduct, '1', $this->session->testtaskQuery);
                $caseQuery = $caseQuery . ' AND `product `' . helper::dbIN(array_keys($products));
                $queryProductID = 'all';
            }

            $caseQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $caseQuery);
            $caseQuery = str_replace(array('t2.`assignedTo`', 't2.`lastRunner`', 't2.`lastRunDate`', 't2.`lastRunResult`'), array('t1.`assignedTo`', 't1.`lastRunner`', 't1.`lastRunDate`', 't1.`lastRunResult`'), $caseQuery);
            $runs = $this->dao->select('t2.*,t1.*, t2.version as caseVersion')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
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
            ->add('lastRunner', $this->app->user->account)
            ->add('date', $now)
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
    public function printCell($col, $run, $users, $task, $branches)
    {
        $caseLink = helper::createLink('testcase', 'view', "caseID=$run->case&version=$run->version&from=testtask&taskID=$run->task");
        $account  = $this->app->user->account;
        $id = $col->id;
        if($col->show)
        {
            $class = '';
            if($id == 'status') $class .= $run->status;
            if($id == 'title') $class .= ' text-left';
            if($id == 'lastRunResult') $class .= " $run->lastRunResult";
            if($id == 'assignedTo' && $run->assignedTo == $account) $class .= ' red';

            echo "<td class='" . $class . "'" . ($id=='title' ? "title='{$run->title}'":'') . ">";
            switch ($id)
            {
            case 'id':
                echo html::a($caseLink, sprintf('%03d', $run->case));
                break;
            case 'pri':
                echo "<span class='pri" . zget($this->lang->testcase->priList, $run->pri, $run->pri) . "'>";
                echo zget($this->lang->testcase->priList, $run->pri, $run->pri);
                echo "</span>";
                break;
            case 'title':
                if($run->branch) echo "<span class='label label-info label-badge'>{$branches[$run->branch]}</span>";
                echo html::a($caseLink, $run->title);
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
                echo ($run->version < $run->caseVersion) ? "<span class='warning'>{$this->lang->testcase->changed}</span>" : $this->lang->testtask->statusList[$run->status];
                break;
            case 'openedBy':
                $openedBy = zget($users, $run->openedBy, $run->openedBy);
                echo substr($openedBy, strpos($openedBy, ':') + 1);
                break;
            case 'openedDate':
                echo substr($run->openedDate, 5, 11);
                break;
            case 'lastRunner':
                $lastRunner = zget($users, $run->lastRunner, $run->lastRunner);
                echo substr($lastRunner, strpos($lastRunner, ':') + 1);
                break;
            case 'lastRunDate':
                if(!helper::isZeroDate($run->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($run->lastRunDate));
                break;
            case 'lastRunResult':
                if($run->lastRunResult) echo $this->lang->testcase->resultList[$run->lastRunResult];
                break;
            case 'story':
                if($run->story and $run->storyTitle) echo html::a(helper::createLink('story', 'view', "storyID=$run->story"), $run->storyTitle);
                break;
            case 'assignedTo':
                $assignedTo = zget($users, $run->assignedTo, $run->assignedTo);
                echo substr($assignedTo, strpos($assignedTo, ':') + 1);
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
                common::printIcon('testtask', 'runCase',    "id=$run->id", '', 'list', '', '', 'runCase iframe', false, "data-width='95%'");
                common::printIcon('testtask', 'results',    "id=$run->id", '', 'list', '', '', 'iframe', '', "data-width='90%'");

                if(common::hasPriv('testtask', 'unlinkCase'))
                {
                    $unlinkURL = helper::createLink('testtask', 'unlinkCase', "caseID=$run->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"casesForm\",confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$this->lang->testtask->unlinkCase}' class='btn-icon'");
                }

                common::printIcon('testcase', 'createBug', "product=$run->product&branch=$run->branch&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,version=$run->version,runID=$run->id,testtask=$task->id", $run, 'list', 'bug', '', 'iframe', '', "data-width='90%'");
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

        /* Set toList and ccList. */
        $toList   = $testtask->owner;
        $ccList   = str_replace(' ', '', trim($testtask->mailto, ','));
        if(empty($toList))
        {
            if(empty($ccList)) return;
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

        /* Set email title. */
        if($action->action == 'opened')
        {
            $mailTitle = sprintf($this->lang->testtask->mail->create->title, $this->app->user->realname, $testtaskID, $this->post->name);
        }
        elseif($action->action == 'closed')
        {
            $mailTitle = sprintf($this->lang->testtask->mail->close->title, $this->app->user->realname, $testtaskID, $testtask->name);
        }
        else
        {
            $mailTitle = sprintf($this->lang->testtask->mail->edit->title, $this->app->user->realname, $testtaskID, $this->post->name);
        }

        /* Send mail. */
        $this->mail->send($toList, $mailTitle, $mailContent, $ccList); 
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }
}
