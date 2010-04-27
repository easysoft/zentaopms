<?php
/**
 * The model file of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class projectModel extends model
{
    /* 每次关联成员的数量。*/
    const LINK_MEMBERS_ONE_TIME = 10;

    /* 检查权限。*/
    public function checkPriv($project)
    {
        /* 检查是否是管理员。*/
        $account = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) !== false) return true; 

        /* 访问级别为open，不做任何处理。*/
        if($project->acl == 'open') return true;

        /* 获得团队的成员列表，供后面判断。*/
        $teamMembers = $this->getTeamMemberPairs($project->id);

        /* 级别为private。*/
        if($project->acl == 'private')
        {
            return isset($teamMembers[$this->app->user->account]);
        }

        /* 级别为custom。*/
        if($project->acl == 'custom')
        {
            if(isset($teamMembers[$this->app->user->account])) return true;
            $userGroups    = $this->loadModel('user')->getGroups($this->app->user->account);
            $projectGroups = explode(',', $project->whitelist);
            foreach($userGroups as $groupID)
            {
                if(in_array($groupID, $projectGroups)) return true;
            }
            return false;
        }
    }

    /* 设置菜单。*/
    public function setMenu($projects, $projectID)
    {
        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();
        $selectHtml = html::select('projectID', $projects, $projectID, "onchange=\"switchProject(this.value, '$moduleName', '$methodName');\"");
        foreach($this->lang->project->menu as $key => $menu)
        {
            if($key == 'list') common::setMenuVars($this->lang->project->menu, 'list',  $selectHtml . $this->lang->arrow);
            else common::setMenuVars($this->lang->project->menu, $key,  $projectID);
        }
    }

    /* 新增项目。*/
    public function create()
    {
        $this->lang->project->team = $this->lang->project->teamname;
        $project = fixer::input('post')
            ->stripTags('name, code, team')
            ->specialChars('goal, desc')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->get();
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->create->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->check('name', 'unique')
            ->check('code', 'unique')
            ->exec();

        /* 将当前操作者加入到项目团队中。*/
        if(!dao::isError())
        {
            $projectID = $this->dao->lastInsertId();
            $member->project  = $projectID;
            $member->account  = $this->app->user->account;
            $member->joinDate = helper::today();
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            return $projectID;
        } 
    }

    /* 更新一个项目。*/
    public function update($projectID)
    {
        $oldProject = $this->getById($projectID);
        $this->lang->project->team = $this->lang->project->teamname;
        $projectID = (int)$projectID;
        $project = fixer::input('post')
            ->stripTags('name, code, team')
            ->specialChars('goal, desc')
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->get();
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->edit->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->check('name', 'unique', "id!=$projectID")
            ->check('code', 'unique', "id!=$projectID")
            ->where('id')->eq($projectID)
            ->limit(1)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /* 获得项目id=>name列表。*/
    public function getPairs()
    {
        $mode = $this->cookie->projectMode;
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('iscat')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->onCaseOf($mode == 'noclosed')->andWhere('status')->ne('done')->endCase()
            ->orderBy('status, end desc')->fetchAll();
        $pairs = array();
        foreach($projects as $project)
        {
            if($this->checkPriv($project)) $pairs[$project->id] = $project->name;
        }
        return $pairs;
    }

    /* 获得完整的列表。*/
    public function getList($status = 'all')
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)->where('iscat')->eq(0)
            ->onCaseOf($status != 'all')->andWhere('status')->in($status)->endcase()
            ->andWhere('deleted')->eq(0)
            ->orderBy('status, end DESC')
            ->fetchAll();
    }

    /* 通过Id获取项目信息。*/
    public function getById($projectID)
    {
        $project = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
        if(!$project) return false;
        $total   = $this->dao->select('
            SUM(estimate) AS totalEstimate, 
            SUM(consumed) AS totalConsumed, 
            SUM(`left`) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('project')->eq((int)$projectID)
            ->andWhere('status')->ne('cancel')
            ->andWhere('deleted')->eq(0)
            ->fetch();
        $project->totalEstimate = round($total->totalEstimate, 1);
        $project->totalConsumed = round($total->totalConsumed, 1);
        $project->totalLeft     = round($total->totalLeft, 1);
        return $project;
    }

    /* 获得相关的产品列表。*/
    public function getProducts($projectID)
    {
        return $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->fetchPairs();
    }

    /* 更新相关产品。*/
    public function updateProducts($projectID)
    {
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->exec();
        if(!isset($_POST['products'])) return;
        $products = array_unique($_POST['products']);
        foreach($products as $productID)
        {
            $data->project = $projectID;
            $data->product = $productID;
            $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
        }
    }

    /* 获得相关项目列表。*/
    public function getRelatedProjects($projectID)
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->fetchAll('product');
        if(!$products) return array();
        $products = array_keys($products);
        return $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')
            ->on('t1.id = t2.project')
            ->where('t2.product')->in($products)
            ->andWhere('t1.id')->ne((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id')
            ->fetchPairs();
    }

    /* 获得可以被导入的任务列表。*/
    public function getTasks2Imported($projectID)
    {
        $this->loadModel('task');
        $releatedProjects = $this->getRelatedProjects($projectID);
        if(!$releatedProjects) return array();
        $tasks = array();
        foreach($releatedProjects as $releatedProjectID => $releatedProjectName)
        {
            $projectTasks = $this->task->getProjectTasks($releatedProjectID, 'wait,doing,cancel');
            if(!$projectTasks) continue;
            $tasks = array_merge($tasks, $projectTasks); 
        }
        return $tasks;
    }

    /* 导入任务。*/
    public function importTask($projectID)
    {
        $tasks = $this->dao->select('id, project, owner, story, consumed')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');

        /* 更新task表。*/
        foreach($tasks as $task)
        {
            /* 记录owner和story。*/
            $owners[$task->owner]  = $task->project;
            $stories[$task->story] = $task->story;

            $status = $task->consumed > 0 ? 'doing' : 'wait';
            $this->dao->update(TABLE_TASK)->set('project')->eq($projectID)->set('status')->eq($status)->where('id')->in($this->post->tasks)->exec();
            $this->loadModel('action')->create('task', $task->id, 'moved', '', $task->project);
        }

        /* 去掉story=0的记录。*/
        unset($stories[0]);

        /* 将没有关联进来的用户加入到团队中。*/
        $teamMembers = $this->getTeamMemberPairs($projectID);
        foreach($owners as $account => $preProjectID)
        {
            if(!isset($teamMembers[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)->where('project')->eq($preProjectID)->andWhere('account')->eq($account)->fetch();
                $role->project  = $projectID;
                $role->joinDate = helper::today();
                $this->dao->insert(TABLE_TEAM)->data($role)->exec();
            }
        }

        /* 将没有关联的需求关联到项目中。*/
        $projectStories = $this->loadModel('story')->getProjectStoryPairs($projectID);
        foreach($stories as $storyID)
        {
            if(!isset($projectStories[$storyID]))
            {
                $story = $this->dao->findById($storyID)->fields("$projectID as project, id as story, product, version")->from(TABLE_STORY)->fetch();
                $this->dao->insert(TABLE_PROJECTSTORY)->data($story)->exec();
            }
        }
    }

    /* 获得相关的子项目列表。*/
    public function getChildProjects($projectID)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)->where('parent')->eq((int)$projectID)->fetchPairs();
    }

    /* 更新child项目。*/
    public function updateChilds($projectID)
    {
        $sql = "UPDATE " . TABLE_PROJECT . " SET parent = 0 WHERE parent = '$projectID'";
        $this->dbh->exec($sql);
        if(!isset($_POST['childs'])) return;
        $childs = array_unique($_POST['childs']);
        foreach($childs as $childProjectID)
        {
            $sql = "UPDATE " . TABLE_PROJECT . " SET parent = '$projectID' WHERE id = '$childProjectID'";
            $this->dbh->query($sql);
        }
    }

    /* 关联需求。*/
    public function linkStory($projectID)
    {
        if($this->post->stories == false) return false;
        $this->loadModel('action');
        $versions = $this->loadModel('story')->getVersions($this->post->stories);
        foreach($this->post->stories as $key => $storyID)
        {
            $productID = $this->post->products[$key];
            $data->project = $projectID;
            $data->product = $productID;
            $data->story   = $storyID;
            $data->version = $versions[$storyID];
            $this->dao->insert(TABLE_PROJECTSTORY)->data($data)->exec();
            $this->story->setStage($storyID);
            $this->action->create('story', $storyID, 'linked2project', '', $projectID);
        }        
    }

    /* 移除一个需求。*/
    public function unlinkStory($projectID, $storyID)
    {
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->andWhere('story')->eq($storyID)->limit(1)->exec();
        $this->loadModel('story')->setStage($storyID);
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromproject', '', $projectID);
    }

    /* 获取团队成员。*/
    public function getTeamMembers($projectID)
    {
        return $this->dao->select('t1.*, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWHere('t2.company')->eq($this->app->company->id)
            ->fetchAll();
    }

   /* 获取团队成员account=>name列表。*/
    public function getTeamMemberPairs($projectID)
    {
        $users = $this->dao->select('t1.account, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWHere('t2.company')->eq($this->app->company->id)
            ->fetchPairs();
        if(!$users) return array();
        foreach($users as $account => $realName)
        {
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            $users[$account] =  $firstLetter . ($realName ? $realName : $account);
        }
        return array('' => '') + $users;
    }

    /* 关联成员。*/
    public function manageMembers($projectID)
    {
        extract($_POST);

        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;
            $role        = $roles[$key];
            $workingHour = $workingHours[$key];
            $mode        = $modes[$key];

            if($mode == 'update')
            {
                $this->dao->update(TABLE_TEAM)
                    ->set('role')->eq($role)
                    ->set('workingHour')->eq($workingHour)
                    ->where('project')->eq((int)$projectID)
                    ->andWhere('account')->eq($account)
                    ->exec();
            }
            else
            {
                $member->project     = (int)$projectID;
                $member->account     = $account;
                $member->joinDate    = helper::today();
                $member->role        = $role;
                $member->workingHour = $workingHour;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            }
        }        
    }

     /* 删除一个成员。*/
    public function unlinkMember($projectID, $account)
    {
        $this->dao->delete()->from(TABLE_TEAM)->where('project')->eq((int)$projectID)->andWhere('account')->eq($account)->exec();
    }

    /* 计算所有项目的燃尽图数据。*/
    public function computeBurn()
    {
        $today    = helper::today();
        $projects = $this->dao->select('id')->from(TABLE_PROJECT)
            ->where("end >= '$today'")
            ->orWhere('end')->eq('0000-00-00')
            ->fetchPairs();
        $burns = $this->dao->select("project, '$today' AS date, sum(`left`) AS `left`, SUM(consumed) AS `consumed`")
            ->from(TABLE_TASK)
            ->where('project')->in($projects)
            ->andWhere('status')->ne('cancel')
            ->groupBy('project')
            ->fetchAll();
        foreach($burns as $burn) $this->dao->replace(TABLE_BURN)->data($burn)->exec();
    }

    /* 燃烧图所需要的数据。*/
    public function getBurnData($projectID = 0, $itemCounts = 21)
    {
        /* 获得项目的信息，和已经计算过的燃烧图数量。*/
        $project    = $this->getById($projectID);
        $burnCounts = $this->dao->select('count(*) AS counts')->from(TABLE_BURN)->where('project')->eq($projectID)->fetch('counts');

        /* 如果已经有超过$itemCounts的数据，则直接查找最后$itemCounts的数据。*/
        $sql = $this->dao->select('date AS name, `left` AS value')->from(TABLE_BURN)->where('project')->eq((int)$projectID);
        if($burnCounts > $itemCounts)
        {
            $sets = $sql->orderBy('date DESC')->limit($itemCounts)->fetchAll('name');
        }
        else
        {
            /* 不足$itemCounts，先将burn表里面的数据查出，再进行补齐。*/
            $sets    = $sql->orderBy('date ASC')->fetchAll('name');
            $current = helper::today();
            if($project->end != '0000-00-00')
            {
                $period = helper::diffDate($project->end, $project->begin);
                $counts = $period > $itemCounts ? $itemCounts : $period;
            }
            else
            {
                $counts = $itemCounts;
            }
            for($i = 0; $i < $counts - $burnCounts; $i ++)
            {
                $sets[$current]->name = $current;
                $sets[$current]->value = '';
                $nextDay = date(DT_DATE1, strtotime('next day', strtotime($current)));
                $current = $nextDay;
            }
        }
        foreach($sets as $set) $set->name = substr($set->name, 5);
        return $sets;
    }
}
