<?php
/**
 * The model file of bug module of ZenTaoMS.
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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class bugModel extends model
{
    /* 创建一个Bug。*/
    function create()
    {
        $now = date('Y-m-d H:i:s');
        $bug = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->setDefault('project,story,task', 0)
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->stripTags('title')
            ->cleanInt('product, module, severity')
            ->specialChars('steps')
            ->join('mailto', ',')
            ->get();
        $this->dao->insert(TABLE_BUG)->data($bug)->autoCheck()->check('title', 'notempty')->exec();
        return $this->dao->lastInsertID();
    }

    /* 获得某一个产品，某一个模块下面的所有bug。*/
    public function getModuleBugs($productID, $moduleIds = 0, $orderBy = 'id|desc', $pager = null)
    {
        $sql = $this->dao->select('*')->from(TABLE_BUG)->where('product')->eq((int)$productID);
        if(!empty($moduleIds)) $sql->andWhere('module')->in($moduleIds);
        return $sql->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /* 获取一个bug的详细信息。*/
    public function getById($bugID)
    {
        $bug = $this->dao->select('t1.*, t2.name AS projectName, t3.title AS storyTitle, t4.name AS taskName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_TASK)->alias('t4')->on('t1.task = t4.id')
            ->where('t1.id')->eq((int)$bugID)->fetch();
        foreach($bug as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $bug->$key = '';
        $bug->mailto = ltrim(trim($bug->mailto), ',');
        if($bug->duplicateBug) $bug->duplicateBugTitle = $this->dao->findById($bug->duplicateBug)->from(TABLE_BUG)->fields('title')->fetch('title');
        return $bug;
    }

    /* 更新bug信息。*/
    public function update($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = date('Y-m-d H:i:s');
        $bug = fixer::input('post')
            ->cleanInt('product,module,severity,project,story,task')
            ->stripTags('title')
            ->specialChars('steps')
            ->remove('comment')
            ->setDefault('project,module,project,story,task,duplicateBug', 0)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setIF($this->post->assignedTo != $oldBug->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->resolvedBy != '' and $this->post->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($this->post->resolution != '' and $this->post->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($this->post->resolution != '' and $this->post->resolvedBy   == '', 'resolvedBy',   $this->app->user->account)
            ->setIF($this->post->closedBy   != '' and $this->post->closedDate   == '', 'closedDate',   $now)
            ->setIF($this->post->closedDate != '' and $this->post->closedBy     == '', 'closedBy',     $this->app->user->account)
            ->setIF($this->post->closedBy   != '' or  $this->post->closedDate   != '', 'assignedTo',   'closed') 
            ->setIF($this->post->closedBy   != '' or  $this->post->closedDate   != '', 'assignedDate', $now) 
            ->setIF($this->post->resolution != '' or  $this->post->resolvedDate != '', 'status',       'resolved') 
            ->setIF($this->post->closedBy   != '' or  $this->post->closedDate   != '', 'status',       'closed') 
            ->setIF(($this->post->resolution != '' or  $this->post->resolvedDate != '') and $this->post->assignedTo == '', 'assignedTo', $oldBug->openedBy) 
            ->setIF(($this->post->resolution != '' or  $this->post->resolvedDate != '') and $this->post->assignedTo == '', 'assignedDate', $now)
            ->setIF($this->post->resolution == '' and $this->post->resolvedDate =='', 'status', 'active')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->check('title', 'notempty')
            ->checkIF($bug->resolvedBy, 'resolution', 'notempty')
            ->checkIF($bug->closedBy,   'resolution', 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldBug, $bug);
    }

    /* 解决Bug。*/
    public function resolve($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = date('Y-m-d H:i:s');
        $bug = fixer::input('post')
            ->add('resolvedBy',     $this->app->user->account)
            ->add('resolvedDate',   $now)
            ->add('status',         'resolved')
            ->add('assignedTo',     $oldBug->openedBy)
            ->add('assignedDate',   $now)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('duplicateBug', 0)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->check('resolution', 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();
    }

    /* 激活Bug。*/
    public function activate($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = date('Y-m-d H:i:s');
        $bug = fixer::input('post')
            ->add('assignedTo', $oldBug->resolvedBy)
            ->add('assignedDate', $now)
            ->add('resolution', '')
            ->add('status', 'active')
            ->add('resolvedDate', '0000-00-00')
            ->add('resolvedBy', '')
            ->add('resolvedBuild', '')
            ->add('closedBy', '')
            ->add('closedDate', '0000-00-00')
            ->add('duplicateBug', 0)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
    }

    /* 关闭Bug。*/
    public function close($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = date('Y-m-d H:i:s');
        $bug = fixer::input('post')
            ->add('assignedTo',     'closed')
            ->add('assignedDate',   $now)
            ->add('status',         'closed')
            ->add('closedBy',       $this->app->user->account)
            ->add('closedDate',     $now)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
    }

    /* 从bug列表中提取所有出现过的账户。*/
    public function extractAccountsFromList($bugs)
    {
        $accounts = array();
        foreach($bugs as $bug)
        {
            if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
            if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
            if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
            if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
            if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /* 从一条bug中提取所有出现过的账户。*/
    public function extractAccountsFromSingle($bug)
    {
        $accounts = array();
        if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
        if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
        if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
        if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
        if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        return array_unique($accounts);
    }

    /* 获得用户的Bug id=>title列表。*/
    public function getUserBugPairs($account)
    {
        $bugs = array();
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as product')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->query();
        while($bug = $stmt->fetch())
        {
            $bug->title = $bug->product . ' / ' . $bug->title;
            $bugs[$bug->id] = $bug->title;
        }
        return $bugs;
    }

    /* 获得某个项目的bug列表。*/
    public function getProjectBugs($projectID, $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUG)->where('project')->eq((int)$projectID)->orderBy($orderBy)->page($pager)->fetchAll();
    }
}
