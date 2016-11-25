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
            $replace = ($key == 'product') ? $selectHtml : $productID;
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
        $task = $this->loadModel('file')->processEditor($task, $this->config->testtask->editor->create['id'], $this->post->uid);
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
    public function getProductTasks($productID, $branch = 0, $orderBy = 'id_desc', $pager = null, $type = '')
    {
        return $this->dao->select("t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName, if(t4.name != '', t4.branch, t5.branch) AS branch")
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project = t5.project')
            ->where('t1.product')->eq((int)$productID)
            ->andWhere('t5.product = t1.product')
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($type == 'wait')->andWhere('t1.status')->ne('done')->fi()
            ->beginIF($type == 'done')->andWhere('t1.status')->eq('done')->fi()
            ->beginIF($branch)->andWhere("if(t4.branch, t4.branch, t5.branch) = '$branch'")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
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
            ->fetchAll();
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
        if($setImgSize) $task->desc = $this->loadModel('file')->setImgSize($task->desc);
        return $task;
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
     * Update a test task.
     * 
     * @param  int   $taskID 
     * @access public
     * @return void
     */
    public function update($taskID)
    {
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')->stripTags($this->config->testtask->editor->edit['id'], $this->config->allowedTags)->join('mailto', ',')->remove('uid')->get();
        $task = $this->loadModel('file')->processEditor($task, $this->config->testtask->editor->edit['id'], $this->post->uid);
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

        $testtask = $this->loadModel('file')->processEditor($testtask, $this->config->testtask->editor->close['id'], $this->post->uid);
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
     * Link cases.
     * 
     * @param  int   $taskID 
     * @access public
     * @return void
     */
    public function linkCase($taskID)
    {
        if($this->post->cases == false) return;
        $postData = fixer::input('post')->get();
        foreach($postData->cases as $caseID)
        {
            $row = new stdclass();
            $row->task       = $taskID;
            $row->case       = $caseID;
            $row->version    = $postData->versions[$caseID];
            $row->assignedTo = '';
            $row->status     = 'wait';
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
            ->beginIF($moduleID)->andWhere('t2.module')->in($moduleID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
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
            ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
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
            $runs = $this->dao->select('t2.*,t1.*, t2.version as caseVersion')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->where($caseQuery)
                ->andWhere('t1.task')->eq($task->id)
                ->beginIF($queryProductID != 'all')->andWhere('t2.product')->eq($queryProductID)->fi()
                ->beginIF($task->branch)->andWhere('t2.branch')->in("0,{$task->branch}")->fi()
                ->orderBy(strpos($sort, 'assignedTo') !== false ? ('t1.' . $sort) : ('t2.' . $sort))
                ->page($pager)
                ->fetchAll();
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
        if(isset($postData->steps) and $postData->steps)
        {
            foreach($postData->steps as $stepID =>$stepResult)
            {
                $step['result'] = $stepResult;
                $step['real']   = $postData->reals[$stepID];
                $stepResults[$stepID] = $step;
            }
        }
        else
        {
            $stepResults = array();
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
            if(!empty($stepResults))
            {
                foreach($stepResults as $stepID => $stepResult) $this->loadModel('file')->saveUpload('stepResult', $resultID, $stepID, "files{$stepID}", "labels{$stepID}");
            }
            else
            {
                $this->loadModel('file')->saveUpload('caseResult', $resultID);
            }
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
                ->beginIF($taskID)->andWhere('task')->eq($taskID)
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
            $dbSteps     = $stepGroups[$caseID];
            $postSteps   = $postData->steps[$caseID];
            $postReals   = $postData->reals[$caseID];

            $caseResult  = $result ? $result : 'pass';
            $stepResults = array();
            foreach($dbSteps as $stepID => $step)
            {
                $step           = array();
                $step['result'] = $caseResult == 'pass' ? $caseResult : $postSteps[$stepID];
                $step['real']   = $caseResult == 'pass' ? '' : $postReals[$stepID];
                $stepResults[$stepID] = $step;
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
        if($caseID > 0)
        {  
            $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('`case`')->eq($caseID)->orderBy('id desc')->fetchAll('id');
        }
        else
        {
            $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('run')->eq($runID)->orderBy('id desc')->fetchAll('id');
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
            ->fetchAll();
        $runs = $this->dao->select('t1.id,t2.build')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_TESTTASK)->alias('t2')->on('t1.task=t2.id')
            ->where('t1.id')->in($runIdList)
            ->fetchPairs();

        foreach($results as $resultID => $result)
        {
            $result->stepResults = unserialize($result->stepResults);
            $result->build       = $result->run ? zget($runs, $result->run, 0) : 0;
            $result->files       = $this->loadModel('file')->getByObject('caseResult', $resultID);//Get files of case result.
            $results[$resultID]  = $result;

            foreach($relatedSteps as $key => $step)
            {
                if($result->version == $step->version)
                {
                    $result->stepResults[$step->id]['desc']   = $step->desc;
                    $result->stepResults[$step->id]['expect'] = $step->expect;
                }
            }

            /* Get files of step result. */
            foreach($result->stepResults as $stepID => $stepResult)
            {
                $result->stepResults[$stepID]['files'] = $this->loadModel('file')->getByObject('stepResult', $resultID, $stepID);
            }
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

        if($action == 'start') return $testtask->status == 'wait';
        if($action == 'close') return $testtask->status != 'done';

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
            case 'actions':
                common::printIcon('testtask', 'runCase',    "id=$run->id", '', 'list', '', '', 'runCase iframe');
                common::printIcon('testtask', 'results',    "id=$run->id", '', 'list', '', '', 'iframe');

                if(common::hasPriv('testtask', 'unlinkCase'))
                {
                    $unlinkURL = helper::createLink('testtask', 'unlinkCase', "caseID=$run->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"casesForm\",confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$this->lang->testtask->unlinkCase}' class='btn-icon'");
                }

                common::printIcon('testcase', 'createBug', "product=$run->product&branch=$run->branch&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,version=$run->version,runID=$run->id,testtask=$task->id", $run, 'list', 'bug', '', 'iframe');
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
        $modulePath = $this->app->getModulePath();
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
