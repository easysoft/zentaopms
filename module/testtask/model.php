<?php
/**
 * The model file of test task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
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
    public function setMenu($products, $productID)
    {
        $this->loadModel('product')->setMenu($products, $productID);
        $selectHtml = $this->product->select($products, $productID, 'testtask', 'browse');
        foreach($this->lang->testtask->menu as $key => $value)
        {
            $replace = ($key == 'product') ? $selectHtml . $this->lang->arrow: $productID;
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
            ->stripTags('name')
            ->get();
        $this->dao->insert(TABLE_TESTTASK)->data($task)->autoCheck()->batchcheck($this->config->testtask->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
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
    public function getProductTasks($productID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->where('t1.product')->eq((int)$productID)
            ->andWhere('t1.deleted')->eq(0)
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
     * @access public
     * @return void
     */
    public function getById($taskID)
    {
        $task = $this->dao->select('t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->where('t1.id')->eq((int)$taskID)->fetch();
        $task->desc = $this->loadModel('file')->setImgSize($task->desc);
        return $task;
    }

    /**
     * Get test tasks by user.
     * 
     * @param   string $account 
     * @access  public
     * @return  array
     */
    public function getByUser($account)
    {
        return $this->dao->select('t1.*, t2.name AS projectName, t3.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build = t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.owner')->eq($account)
            ->orderBy('id desc')
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
        $task = fixer::input('post')->stripTags('name')->get();
        $this->dao->update(TABLE_TESTTASK)->data($task)->autoCheck()->batchcheck($this->config->testtask->edit->requiredFields, 'notempty')->where('id')->eq($taskID)->exec();
        if(!dao::isError()) return common::createChanges($oldTask, $task);
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
        foreach($this->post->cases as $caseID)
        {
            $row->task       = $taskID;
            $row->case       = $caseID;
            $row->version    = $this->post->versions[$caseID];
            $row->assignedTo = '';
            $row->status     = 'wait';
            $this->dao->replace(TABLE_TESTRUN)->data($row)->exec();
        }
    }

    /**
     * Get test runs of a test task.
     * 
     * @param  int   $taskID 
     * @param  int   $moduleID 
     * @access public
     * @return array
     */
    public function getRuns($taskID, $moduleID)
    {
        return $this->dao->select('t2.*,t1.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq((int)$taskID)
            ->beginIF($moduleID)->andWhere('t2.module')->in($moduleID)->fi()
            ->fetchAll();
    }

    /**
     * Get test runs of a user.
     * 
     * @param  int   $taskID 
     * @param  int   $user 
     * @access public
     * @return array
     */
    public function getUserRuns($taskID, $user)
    {
        return $this->dao->select('t2.*,t1.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq((int)$taskID)
            ->andWhere('t1.assignedTo')->eq($user)
            ->fetchAll();
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
        $caseResult = $this->post->result ? $this->post->result : 'pass';
        if(isset($_POST['passall']) and $this->post->passall == false)
        {
            if($this->post->steps)
            {
                foreach($this->post->steps as $stepID => $stepResult)
                {
                    if($stepResult != 'pass' and $stepResult != 'n/a')
                    {
                        $caseResult = $stepResult;
                        break;
                    }
                }
            }
        }

        /* Create result of every step. */
        if($this->post->steps)
        {
            foreach($this->post->steps as $stepID =>$stepResult)
            {
                $step['result'] = $stepResult;
                $step['real']   = $this->post->reals[$stepID];
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
            ->add('runAccount', $this->app->user->account)
            ->add('date', $now)
            ->remove('steps,reals,passall,result')
            ->get();
        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
        $this->dao->update(TABLE_CASE)->set('runAccount')->eq($this->app->user->account)->set('lastRun')->eq($now)->set('lastResult')->eq($caseResult)->where('id')->eq($this->post->case)->exec();

        if($runID)
        {
            /* Update testRun's status. */
            if(!dao::isError())
            {
                $runStatus = $caseResult == 'blocked' ? 'blocked' : 'done';
                $this->dao->update(TABLE_TESTRUN)
                    ->set('lastResult')->eq($caseResult)
                    ->set('status')->eq($runStatus)
                    ->set('runAccount')->eq($this->app->user->account)
                    ->set('lastRun')->eq($now)
                    ->where('id')->eq($runID)
                    ->exec();
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
        foreach($results as $result)
        {
            $relatedVersions[] = $result->version;
            $runCaseID         = $result->case;
        }
        $relatedVersions = array_unique($relatedVersions);

        $relatedSteps =  $this->dao->select('*')->from(TABLE_CASESTEP)
            ->beginIF($caseID)->where('`case`')->eq($caseID)->fi()
            ->beginIF($runID)->where('`case`')->eq($runCaseID)->fi()
            ->andWhere('version')->in($relatedVersions)
            ->fetchAll();

        foreach($results as $resultID => $result)
        {
            $result->stepResults = unserialize($result->stepResults);
            $results[$resultID] = $result;

            foreach($relatedSteps as $key => $step)
            {
                if($result->version == $step->version)
                {
                    $result->stepResults[$step->id]['desc']   = $step->desc;
                    $result->stepResults[$step->id]['expect'] = $step->expect;
                }
            }
        }
        return $results;
    }
}
