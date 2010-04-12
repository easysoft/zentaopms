<?php
/**
 * The model file of test task module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class testtaskModel extends model
{
    /* 设置菜单。*/
    public function setMenu($products, $productID)
    {
        $selectHtml = html::select('productID', $products, $productID, "onchange=\"switchProduct(this.value, 'testtask', 'browse');\"");
        common::setMenuVars($this->lang->testtask->menu, 'product',  $selectHtml . $this->lang->arrow);
        common::setMenuVars($this->lang->testtask->menu, 'bug',      $productID);
        common::setMenuVars($this->lang->testtask->menu, 'testcase', $productID);
        common::setMenuVars($this->lang->testtask->menu, 'testtask', $productID);
    }

    /* 创建一个测试任务。*/
    function create($productID)
    {
        $task = fixer::input('post')
            ->add('product', $productID)
            ->stripTags('name')
            ->specialChars('desc')
            ->get();
        $this->dao->insert(TABLE_TESTTASK)->data($task)->autoCheck()->batchcheck($this->config->testtask->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 获得某一个产品的测试任务列表。*/
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

    /* 获取一个测试任务的详细信息。*/
    public function getById($taskID)
    {
        return $this->dao->select('t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->where('t1.id')->eq((int)$taskID)->fetch();
    }

    /* 更新测试任务信息。*/
    public function update($taskID)
    {
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->get();
        $this->dao->update(TABLE_TESTTASK)->data($task)->autoCheck()->batchcheck($this->config->testtask->edit->requiredFields, 'notempty')->where('id')->eq($taskID)->exec();
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /* 关联用例。*/
    public function linkCase($taskID)
    {
        if($this->post->cases == false) return;
        foreach($this->post->cases as $key => $caseID)
        {
            $row->task       = $taskID;
            $row->case       = $caseID;
            $row->version    = $this->post->versions[$key];
            $row->assignedTo = '';
            $row->status     = 'wait';
            $this->dao->replace(TABLE_TESTRUN)->data($row)->exec();
        }
    }

    /* 获得任务的执行用例列表。*/
    public function getRuns($taskID)
    {
        return $this->dao->select('t2.*,t1.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq((int)$taskID)
            ->fetchAll();
    }

    /* 获得某一个testrun的信息。*/
    public function getRunById($runID)
    {
        $testRun = $this->dao->findById($runID)->from(TABLE_TESTRUN)->fetch();
        $testRun->case = $this->loadModel('testcase')->getById($testRun->case, $testRun->version);
        return $testRun;
    }

    /* 创建测试结果。*/
    public function createResult($runID)
    {
        /* 计算case的结果。*/
        $caseResult = 'pass';
        if(!$this->post->passall)
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

        /* 合并步骤的实际输出结果。*/
        foreach($this->post->steps as $stepID =>$stepResult)
        {
            $step['result'] = $stepResult;
            $step['real']   = $this->post->reals[$stepID];
            $stepResults[$stepID] = $step;
        }

        $now = helper::now();
        $result = fixer::input('post')
            ->add('run', $runID)
            ->add('caseResult', $caseResult)
            ->setForce('stepResults', serialize($stepResults))
            ->add('date', $now)
            ->remove('steps,reals,passall')
            ->get();
        $this->dao->insert(TABLE_TESTRESULT)->data($result)->autoCheck()->exec();
        if(!dao::isError())
        {
            $runStatus = $caseResult == 'blocked' ? 'blocked' : 'done';
            $this->dao->update(TABLE_TESTRUN)
                ->set('lastResult')->eq($caseResult)
                ->set('status')->eq($runStatus)
                ->set('lastRun')->eq($now)
                ->where('id')->eq($runID)
                ->exec();
        }
    }

    /* 获得执行结果。*/
    public function getRunResults($runID)
    {
        $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('run')->eq($runID)->orderBy('id desc')->fetchAll('id');
        if(!$results) return array();
        foreach($results as $resultID => $result)
        {
            $result->stepResults = unserialize($result->stepResults);
            $results[$resultID] = $result;
        }
        return $results;
    }
}
