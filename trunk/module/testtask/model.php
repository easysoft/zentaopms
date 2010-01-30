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
 * @copyright   Copyright: 2009 Chunsheng Wang
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
        $selectHtml = html::select('productID', $products, $productID, "onchange=\"switchProduct(this.value, 'case');\"");
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
        $this->dao->insert(TABLE_TESTTASK)->data($task)->autoCheck()->batchcheck('title,project,build', 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 获得某一个产品的测试任务列表。*/
    public function getProductTasks($productID, $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->where('t1.product')->eq((int)$productID)->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /* 获取一个测试任务的详细信息。*/
    public function getById($taskID)
    {
        return $this->dao->select('t1.*, t2.name AS productName, t3.name AS projectName, t4.name AS buildName')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->leftJoin(TABLE_BUILD)->alias('t4')->on('t1.build = t4.id')
            ->where('t1.id')->eq((int)$taskID)->fetch();
    }

    /* 更新测试任务信息。*/
    public function update($taskID)
    {
        $task = fixer::input('post')
            ->stripTags('name')
            ->specialChars('desc')
            ->get();
        $this->dao->update(TABLE_TESTTASK)->data($task)->autoCheck()->batchcheck('title,project,build', 'notempty')->where('id')->eq($taskID)->exec();
    }

    /* 删除测试任务信息。*/
    public function delete($taskID)
    {
        $this->dao->delete()->from(TABLE_TESTTASK)->where('id')->eq($taskID)->exec();
    }

    /* 关联用例。*/
    public function linkCase($taskID)
    {
        foreach($this->post->cases as $key => $caseID)
        {
            $row->task       = $taskID;
            $row->case       = $caseID;
            $row->version    = $this->post->versions[$key];
            $row->assignedTo = $this->app->user->account;
            $this->dao->replace(TABLE_TASKCASE)->data($row)->exec();
        }
    }

    /* 获得任务的用例列表。*/
    public function getCases($taskID)
    {
        return $this->dao->select('t2.*,t1.*')->from(TABLE_TASKCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->where('t1.task')->eq((int)$taskID)
            ->fetchAll();
    }
}
