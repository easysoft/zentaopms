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
        common::setMenuVars($this->lang->testcase->menu, 'testtask', $productID);
    }

    /* 创建一个Case。*/
    function create()
    {
        $now  = date('Y-m-d H:i:s');
        $case = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('status', 'normal')
            ->add('version', 1)
            ->remove('steps,expects')
            ->setDefault('story', 0)
            ->stripTags('title')
            ->specialChars('steps')
            ->get();
        $this->dao->insert(TABLE_CASE)->data($case)->autoCheck()->check('title', 'notempty')->exec();
        if(!$this->dao->isError())
        {
            $caseID = $this->dao->lastInsertID();
            foreach($this->post->steps as $stepID => $stepDesc)
            {
                if(empty($stepDesc)) continue;
                $step->case    = $caseID;
                $step->version = 1;
                $step->desc    = htmlspecialchars($stepDesc);
                $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }
            return $caseID;
        }
    }

    /* 获得某一个产品，某一个模块下面的所有case。*/
    public function getModuleCases($productID, $moduleIds = 0, $orderBy = 'id|desc', $pager = null)
    {
        $sql = $this->dao->select('*')->from(TABLE_CASE)->where('product')->eq((int)$productID);
        if(!empty($moduleIds)) $sql->andWhere('module')->in($moduleIds);
        return $sql->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /* 获取一个case的详细信息。*/
    public function getById($caseID, $version = 0)
    {
        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        foreach($case as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $case->$key = '';
        if($case->story) $case->storyTitle = $this->dao->findById($case->story)->from(TABLE_STORY)->fetch('title');
        if($version == 0) $version = $case->version;
        $case->steps = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->andWhere('version')->eq($version)->fetchAll();
        $case->files = $this->loadModel('file')->getByObject('case', $caseID);
        return $case;
    }

    /* 更新case信息。*/
    public function update($caseID)
    {
        $oldCase = $this->getById($caseID);
        $now     = date('Y-m-d H:i:s');
        $version = $oldCase->version + 1;
        $case    = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('version', $version)
            ->setDefault('story', 0)
            ->stripTags('title')
            ->remove('comment,steps,expects')
            ->get();
        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->batchCheck('title,status,type', 'notempty')->where('id')->eq((int)$caseID)->exec();
        if(!$this->dao->isError())
        {
            foreach($this->post->steps as $stepID => $stepDesc)
            {
                if(empty($stepDesc)) continue;
                $step->case    = $caseID;
                $step->version = $version;
                $step->desc    = htmlspecialchars($stepDesc);
                $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }

            /* 将步骤合并为字符串，以计算diff。*/
            $oldCase->steps = $this->joinStep($oldCase->steps);
            $case->steps    = $this->joinStep($this->getById($caseID, $version)->steps);
            return common::createChanges($oldCase, $case);
        }
    }

    /* 合并步骤。*/
    private function joinStep($steps)
    {
        $retrun = '';
        foreach($steps as $step) $return .= $step->desc . ' EXPECT:' . $step->expect . "\n";
        return $return;
    }
}
