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
    public function getById($storyID, $version = 0)
    {
        $story = $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
        if(!$story) return false;
        if(substr($story->closedDate, 0, 4) == '0000') $story->closedDate = '';
        if($version == 0) $version = $story->version;
        $spec = $this->dao->select('title,spec')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title = $spec->title;
        $story->spec  = $spec->spec;
        $story->projects = $this->dao->select('t1.project, t2.name')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.story')->eq($storyID)
            ->orderBy('t1.project DESC')
            ->fetchPairs();
        $story->tasks     = $this->dao->select('id,name,project')->from(TABLE_TASK)->where('story')->eq($storyID)->orderBy('id DESC')->fetchAll();
        //$story->bugCount  = $this->dao->select('COUNT(*)')->alias('count')->from(TABLE_BUG)->where('story')->eq($storyID)->fetch('count');
        //$story->caseCount = $this->dao->select('COUNT(*)')->alias('count')->from(TABLE_CASE)->where('story')->eq($storyID)->fetch('count');
        if($story->toBug) $story->toBugTitle = $this->dao->findById($story->toBug)->from(TABLE_BUG)->fetch('title');
        if($story->plan)  $story->planTitle  = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $extraStories = array();
        if($story->duplicateStory) $extraStories = array($story->duplicateStory);
        if($story->linkStories)    $extraStories = explode(',', $story->linkStories);
        if($story->childStories)   $extraStories = array_merge($extraStories, explode(',', $story->childStories));
        $extraStories = array_unique($extraStories);
        if(!empty($extraStories)) $story->extraStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($extraStories)->fetchPairs();
        return $story;
    }

    /* 新增需求。*/
    public function create()
    {
        $now   = date('Y-m-d H:i:s', time());
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->cleanFloat('estimate')
            ->stripTags('title')
            ->setDefault('plan', 0)
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('assignedDate', 0)
            ->add('version', 1)
            ->add('status', 'draft')
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->needNotReview, 'status', 'active')
            ->join('mailto', ',')
            ->remove('files,labels,spec,needNotReview')
            ->get();
        $this->dao->insert(TABLE_STORY)->data($story)->autoCheck()->batchCheck('title,estimate', 'notempty')->exec();
        if(!dao::isError())
        {
            $storyID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('story', $storyID, $extra = 1);
            $spec = htmlspecialchars($this->post->spec);
            $this->dao->insert(TABLE_STORYSPEC)
                ->set('story')->eq($storyID)
                ->set('version')->eq(1)
                ->set('title')->eq($story->title)
                ->set('spec')->eq($spec)->exec();
            return $storyID;
        }
        return false;
    }

    /* 变更需求。*/
    public function change($storyID)
    {
        $now         = date('Y-m-d H:i:s', time());
        $oldStory    = $this->getById($storyID);
        $specChanged = false;
        if($this->post->spec != $oldStory->spec or $this->post->title != $oldStory->title or $this->loadModel('file')->getCount()) $specChanged = true;

        $story = fixer::input('post')
            ->stripTags('title')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setIF($this->post->assignedTo != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($specChanged, 'version', $oldStory->version + 1)
            ->setIF($specChanged and $oldStory->status == 'active' and $this->post->needNotReview == false, 'status',  'changed')
            ->setIF($specChanged and $oldStory->status == 'draft'  and $this->post->needNotReview, 'status', 'active')
            ->setIF($specChanged, 'reviewedBy',  '')
            ->setIF($specChanged, 'closedBy', '')
            ->setIF($specChanged, 'closedReason', '')
            ->setIF($specChanged and $oldStory->reviewedBy, 'reviewedDate',  '0000-00-00')
            ->setIF($specChanged and $oldStory->closedBy,   'closedDate',   '0000-00-00')
            ->remove('files,labels,spec,comment,needNotReview')
            ->get();
        $this->dao->update(TABLE_STORY)
            ->data($story)
            ->autoCheck()
            ->check('title', 'notempty')
            ->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError())
        {
            if($specChanged)
            {
                $spec = htmlspecialchars($this->post->spec);
                $this->dao->insert(TABLE_STORYSPEC)
                    ->set('story')->eq($storyID)
                    ->set('version')->eq($oldStory->version + 1)
                    ->set('title')->eq($story->title)
                    ->set('spec')->eq($spec)
                    ->exec();
                $story->spec = $this->post->spec;
            }
            else
            {
                unset($oldStory->spec);
            }
            return common::createChanges($oldStory, $story);
        }
    }
 
    /* 更新需求。*/
    public function update($storyID)
    {
        $now         = date('Y-m-d H:i:s', time());
        $oldStory    = $this->getById($storyID);

        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->stripTags('title')
            ->add('assignedDate', $oldStory->assignedDate)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('plan', 0)
            ->setDefault('status', $oldStory->status)
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     != false or  $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason != false and $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->setIF($oldStory->status == 'draft', 'stage', '')
            ->remove('files,labels,comment')
            ->get();

        $this->dao->update(TABLE_STORY)
            ->data($story)
            ->autoCheck()
            ->batchCheck('title,estimate', 'notempty')
            ->checkIF($story->closedBy, 'closedReason', 'notempty')
            ->checkIF($story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->closedReason == 'subdivided', 'childStories', 'notempty')
            ->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError()) return common::createChanges($oldStory, $story);
    }
    
    /* 删除一条需求。*/
    public function delete($storyID)
    {
        $this->dao->delete()->from(TABLE_STORY)->where('id')->eq((int)$storyID)->limit(1)->exec();
    }

    /* 评审需求。*/
    public function review($storyID)
    {
        if($this->post->result == false)   die(js::alert($this->lang->story->mustChooseResult));
        if($this->post->result == 'revert' and $this->post->preVersion == false) die(js::alert($this->lang->story->mustChoosePreVersion));

        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = date('Y-m-d H:i:s');
        $date     = date('Y-m-d');
        $story = fixer::input('post')
            ->remove('result,preVersion,comment')
            ->setDefault('reviewedDate', $date)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setIF($this->post->result == 'pass' and $oldStory->status == 'draft',   'status', 'active')
            ->setIF($this->post->result == 'pass' and $oldStory->status == 'changed', 'status', 'active')
            ->setIF($this->post->result == 'reject', 'closedBy',   $this->app->user->account)
            ->setIF($this->post->result == 'reject', 'closedDate', $now)
            ->setIF($this->post->result == 'reject', 'assignedTo', 'closed')
            ->setIF($this->post->result == 'reject', 'status', 'closed')
            ->setIF($this->post->result == 'revert', 'version', $this->post->preVersion)
            ->setIF($this->post->result == 'revert', 'status',  'active')
            ->setIF($this->post->closedReason == 'done', 'stage', 'released')
            ->removeIF($this->post->result != 'reject', 'closedReason, duplicateStory, childStories')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'subdivided', 'childStories')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)
            ->autoCheck()
            ->batchCheck('assignedTo, reviewedBy', 'notempty')
            ->checkIF($this->post->result == 'reject', 'closedReason', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'subdivided', 'childStories',   'notempty')
            ->where('id')->eq($storyID)->exec();
        if($this->post->result == 'revert')
        {
            $preTitle = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($this->post->preVersion)->fetch('title');
            $this->dao->update(TABLE_STORY)->set('title')->eq($preTitle)->where('id')->eq($storyID)->exec();
            $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($oldStory->version)->exec();
            $this->dao->delete()->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->eq($storyID)->andWhere('extra')->eq($oldStory->version)->exec();
        }
        $this->setStage($storyID);
        return true;
    }
    
    /* 关闭需求。*/
    public function close($storyID)
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = date('Y-m-d H:i:s');
        $story = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('closedDate', $now)
            ->add('closedBy',   $this->app->user->account)
            ->add('assignedTo',   'closed')
            ->add('assignedDate', $now)
            ->add('status', 'closed') 
            ->removeIF($this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->closedReason != 'subdivided', 'childStories')
            ->setIF($this->post->closedReason == 'done', 'stage', 'released')
            ->setIF($this->post->closedReason != 'done', 'plan', 0)
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)
            ->autoCheck()
            ->check('closedReason', 'notempty')
            ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->closedReason == 'subdivided', 'childStories',   'notempty')
            ->where('id')->eq($storyID)->exec();
        return true;
    }

    /* 激活需求。*/
    public function activate($storyID)
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = date('Y-m-d H:i:s');
        $story = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('assignedDate', $now)
            ->add('status', 'active') 
            ->add('closedBy', '')
            ->add('closedReason', '')
            ->add('closedDate', '0000-00-00')
            ->add('reviewedBy', '')
            ->add('reviewedDate', '0000-00-00')
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();
        return true;
    }

    /* 设置需求的*/
    public function setStage($storyID, $customStage = '')
    {
        /* 指定了customStage，以其为准。*/
        if($customStage)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq($customStage)->where('id')->eq((int)$storyID)->exec();
            return;
        }

        /* 查找活动的项目。*/
        $projects = $this->dao->select('project')
            ->from(TABLE_PROJECTSTORY)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq((int)$storyID)
            ->andWhere('t2.status')->eq('doing')
            ->fetchPairs();

        /* 如果没有项目，但有计划，则阶段为planned或wait。*/
        if(!$projects)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('planned')->where('id')->eq((int)$storyID)->andWhere('plan')->gt(0)->exec();
            $this->dao->update(TABLE_STORY)->set('stage')->eq('wait')->where('id')->eq((int)$storyID)->andWhere('plan')->eq(0)->andWhere('status')->eq('active')->exec();
            return;
        }
        /* 查找对应的任务。*/
        $tasks = $this->dao->select('type,status')->from(TABLE_TASK)->where('project')->in($projects)->andWhere('story')->eq($storyID)->fetchGroup('type');

        /* 没有任务，则所处阶段为'已经立项'。*/
        if(!$tasks)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('projected')->where('id')->eq((int)$storyID)->exec();
            return;
        }

        /* 如果有测试任务。*/
        if(isset($tasks['test']))
        {
            $stage = 'tested';
            foreach($tasks['test'] as $task)
            {
                if($task->status != 'done')
                {
                    $stage = 'testing';
                    break;
                }
            }
        }
        else
        {
            $stage = 'developed';
            foreach($tasks as $type => $typeTasks)
            {
                foreach($typeTasks as $task)
                {
                    if($task->status != 'done')
                    {
                        $stage = 'developing';
                        break;
                    }
                }
            }
        }
        $this->dao->update(TABLE_STORY)->set('stage')->eq($stage)->where('id')->eq((int)$storyID)->exec();
        return;
    }

    /* 获得某一个产品某一个模块下面的所有需求列表。*/
    public function getProductStories($productID = 0, $moduleIds = 0, $status = 'all', $orderBy = 'id|desc', $pager = null)
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
    public function getProductStoryPairs($productID = 0, $moduleIds = 0, $status = 'all', $order = 'id|desc')
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
    public function getByQuery($productID, $query, $orderBy, $pager = null)
    {
        $tmpStories = $this->dao->select('*')->from(TABLE_STORY)->where($query)->andWhere('product')->eq((int)$productID)->orderBy($orderBy)->page($pager)->fetchGroup('plan');
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
    public function getProjectStories($projectID = 0, $orderBy='id|desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->orderBy($orderBy)
            ->page($pager)->fetchAll('id');
    }

    /* 获得某一个项目相关的需求id=>title的列表。*/
    public function getProjectStoryPairs($projectID = 0, $productID = 0)
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

    /* 获得指派给某一个用户的需求列表。*/
    public function getUserStories($account, $status = 'all', $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.title as planTitle, t3.name as productTitle')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.assignedTo')->eq($account)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /* 获得需求所在的活动的项目的成员列表。*/
    public function getProjectMembers($storyID)
    {
        $projects = $this->dao->select('project')
            ->from(TABLE_PROJECTSTORY)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq((int)$storyID)
            ->andWhere('t2.status')->eq('doing')
            ->fetchPairs();
        if($projects) return($this->dao->select('account')->from(TABLE_TEAM)->where('project')->in($projects)->fetchPairs('account'));
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
