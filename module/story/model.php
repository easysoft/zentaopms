<?php
/**
 * The model file of story module of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class storyModel extends model
{
    /* 获取某一条需求的信息。*/
    public function findByID($storyID)
    {
        return $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
    }

    /* 新增需求。*/
    function create()
    {
        $now   = date('Y-m-d H:i:s', time());
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->cleanFloat('estimate')
            ->stripTags('title')
            ->specialChars('spec')
            ->setDefault('plan', 0)
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('assignedDate', 0)
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->get();
        $this->dao->insert(TABLE_STORY)->data($story)->autoCheck()->check('title', 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /* 更新需求。*/
    function update($storyID)
    {
        $now      = date('Y-m-d H:i:s', time());
        $oldStory = $this->findByID($storyID);
        $story    = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->stripTags('title')
            ->specialChars('spec')
            ->remove('comment')
            ->add('assignedDate', $oldStory->assignedDate)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('plan', 0)
            ->setIF($this->post->assignedTo != $oldStory->assignedTo, 'assignedDate', $now)
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->check('title', 'notempty')->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError()) return common::createChanges($oldStory, $story);
    }
    
    /* 删除一条需求。*/
    function delete($storyID)
    {
        $this->dao->delete()->from(TABLE_STORY)->where('id')->eq((int)$storyID)->limit(1)->exec();
    }
    
    /* 获得某一个产品某一个模块下面的所有需求列表。*/
    function getProductStories($productID = 0, $moduleIds = 0, $status = 'all', $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.title as planTitle')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->where('t1.product')->in($productID)
            ->onCaseOf(!empty($moduleIds))->andWhere('module')->in($moduleIds)->endCase() 
            ->onCaseOf($status != 'all')->andWhere('status')->in($status)->endCase()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /* 获得某一个产品某一个模块下面的所有需求id=>title列表。*/
    function getProductStoryPairs($productID = 0, $moduleIds = 0, $status = 'all', $order = 'id|desc')
    {
        $sql = $this->dao->select('t1.id, t1.title, t1.module, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1');
        if($productID) $sql->andWhere('t1.product')->in($productID);
        if($moduleIds) $sql->andWhere('t1.module')->in($moduleIds);
        if($status != 'all') $sql->andWhere('status')->in($status);
        $stories = $sql->orderBy($order)->fetchAll();
        return $this->formatStories($stories);
    }

    /* 按照某一个查询条件获取列表。*/
    public function getByQuery($query, $orderBy, $pager = null)
    {
        $tmpStories = $this->dao->select('*')->from(TABLE_STORY)->where($query)->orderBy($orderBy)->page($pager)->fetchGroup('plan');
        if(!$tmpStories) return array();
        $plans   = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('id')->in(array_keys($tmpStories))->fetchPairs();
        $stories = array();
        foreach($tmpStories as $planID => $planStories)
        {
            foreach($planStories as $story)
            {
                $story->planTitle = isset($plans[$planID]) ? $plans[$planID] : '';
                $stories[] = $story;
            }
        }
        return $stories;
    }

    /* 获得某一个项目相关的所有需求列表。*/
    function getProjectStories($projectID = 0, $orderBy='id|desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->orderBy($orderBy)
            ->page($pager)->fetchAll('id');
    }

    /* 获得某一个项目相关的需求id=>title的列表。*/
    function getProjectStoryPairs($projectID = 0, $productID = 0)
    {
        $sql = $this->dao->select('t2.id, t2.title, t2.module, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')
            ->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$projectID);
        if($productID) $sql->andWhere('t1.product')->eq((int)$productID);
        $stories = $sql->fetchAll();
        return $this->formatStories($stories);
    }

    /* 获得某一个产品计划下面所有的需求列表。*/
    public function getPlanStories($planID, $status = 'all', $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_STORY)
            ->where('plan')->eq((int)$planID)
            ->onCaseOf($status != 'all')->andWhere('status')->in($status)->endCase()
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /* 获得某一个产品计划下面所有的需求列表。*/
    public function getPlanStoryPairs($planID, $status = 'all', $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_STORY)->where('plan')->eq($planID)->onCaseOf($status != 'all')->andWhere('status')->in($status)->endCase()->fetchAll();
    }

    /* 格式化需求显示。*/
    private function formatStories($stories)
    {
        /* 查找每个story所对应的模块名称。*/
        $modules = array();
        foreach($stories as $story) $modules[] = $story->module;
        $moduleNames = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($modules)->fetchPairs();

        /* 重新组织每一个story的展示方式。*/
        $storyPairs = array('' => '');
        foreach($stories as $story) $storyPairs[$story->id] = $story->id . ':' . $story->product . '/' . ($story->module > 0 ? $moduleNames[$story->module] . '/' : '') . $story->title;
        return $storyPairs;
    }

    /* 从story列表中提取所有出现过的账户。*/
    public function extractAccountsFromList($stories)
    {
        $accounts = array();
        foreach($stories as $story)
        {
            if(!empty($story->openedBy))     $accounts[] = $story->openedBy;
            if(!empty($story->assignedTo))   $accounts[] = $story->assignedTo;
            if(!empty($story->closedBy))     $accounts[] = $story->closedBy;
            if(!empty($story->lastEditedBy)) $accounts[] = $story->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /* 从一条story中提取所有出现过的账户。*/
    public function extractAccountsFromSingle($story)
    {
        $accounts = array();
        if(!empty($story->openedBy))     $accounts[] = $story->openedBy;
        if(!empty($story->assignedTo))   $accounts[] = $story->assignedTo;
        if(!empty($story->closedBy))     $accounts[] = $story->closedBy;
        if(!empty($story->lastEditedBy)) $accounts[] = $story->lastEditedBy;
        return array_unique($accounts);
    }
}
