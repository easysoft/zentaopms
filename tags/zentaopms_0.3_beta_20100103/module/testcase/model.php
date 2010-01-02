<?php
/**
 * The model file of case module of ZenTaoMS.
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
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class testcaseModel extends model
{
    /* 设置菜单。*/
    public function setMenu($products, $productID)
    {
        $selectHtml = html::select('productID', $products, $productID, "onchange=\"switchProduct(this.value, 'case');\"");
        common::setMenuVars($this->lang->testcase->menu, 'product',  $selectHtml . $this->lang->arrow);
        common::setMenuVars($this->lang->testcase->menu, 'bug',      $productID);
        common::setMenuVars($this->lang->testcase->menu, 'testcase', $productID);
    }

    /* 创建一个Case。*/
    function create()
    {
        $now  = date('Y-m-d H:i:s');
        $case = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('status', 'normal')
            ->setDefault('story', 0)
            ->stripTags('title')
            ->specialChars('steps')
            ->get();
        $this->dao->insert(TABLE_CASE)->data($case)->autoCheck()->check('title', 'notempty')->exec();
        return $this->dao->lastInsertID();
    }

    /* 获得某一个产品，某一个模块下面的所有case。*/
    public function getModuleCases($productID, $moduleIds = 0, $orderBy = 'id|desc', $pager = null)
    {
        $sql = $this->dao->select('*')->from(TABLE_CASE)->where('product')->eq((int)$productID);
        if(!empty($moduleIds)) $sql->andWhere('module')->in($moduleIds);
        return $sql->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /* 获取一个case的详细信息。*/
    public function getById($caseID)
    {
        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        foreach($case as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $case->$key = '';
        if($case->story) $case->storyTitle = $this->dao->findById($case->story)->from(TABLE_STORY)->fetch('title');
        return $case;
    }

    /* 更新case信息。*/
    public function update($caseID)
    {
        $oldCase = $this->getById($caseID);
        $now     = date('Y-m-d H:i:s');
        $case    = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('story', 0)
            ->stripTags('title')
            ->specialChars('steps')
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->check('title', 'notempty')->where('id')->eq((int)$caseID)->exec();
        if(!dao::isError()) return common::createChanges($oldCase, $case);
    }
}
