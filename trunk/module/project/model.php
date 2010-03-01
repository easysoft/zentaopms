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

    /* 设置菜单。*/
    public function setMenu($projects, $projectID)
    {
        $selectHtml = html::select('projectID', $projects, $projectID, 'onchange="switchProject(this.value);"');
        foreach($this->lang->project->menu as $key => $menu)
        {
            if($key == 'list') common::setMenuVars($this->lang->project->menu, 'list',  $selectHtml . $this->lang->arrow);
            else common::setMenuVars($this->lang->project->menu, $key,  $projectID);
        }
    }

    /* 新增项目。*/
    public function create()
    {
        $project = fixer::input('post')
            ->add('company', $this->app->company->id)
            ->stripTags('name, code, team')
            ->specialChars('goal, desc')
            ->get();
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchCheck('name,code,team', 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->check('name', 'unique')
            ->check('code', 'unique')
            ->exec();
        if(!dao::isError()) return $this->dao->lastInsertId();
    }

    /* 更新一个项目。*/
    public function update($projectID)
    {
        $projectID = (int)$projectID;
        $project = fixer::input('post')
            ->stripTags('name, code, team')
            ->specialChars('goal, desc')
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->get();
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchCheck('name,code,team', 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->check('name', 'unique', "id!=$projectID")
            ->check('code', 'unique', "id!=$projectID")
            ->where('id')->eq($projectID)
            ->limit(1)
            ->exec();
    }

    /* 删除一个项目。*/
    public function delete($projectID)
    {
        return $this->dao->delete()->from(TABLE_PROJECT)->where('id')->eq((int)$projectID)->andWhere('company')->eq($this->app->company->id)->limit(1)->exec();
    }
    
    /* 获得项目目录列表。*/
    public function getCats()
    {
        $cats = array();
        $stmt = $this->dbh->query("SELECT id, name FROM " . TABLE_PROJECT . " WHERE isCat = '1'");
        while($cat = $stmt->fetch()) $cats[$cat->id] = $cat->name;
        return $cats;
    }

    /* 获得项目id=>name列表。*/
    public function getPairs()
    {
        return $this->dao->select('id,name')->from(TABLE_PROJECT)->where('iscat')->eq(0)->andwhere('company')->eq($this->app->company->id)->orderBy('status, end|desc')->fetchPairs();
    }

    /* 获得完整的列表。*/
    public function getList($status = 'all')
    {
        $sql = $this->dao->select('*')->from(TABLE_PROJECT)->where('iscat')->eq(0)->andwhere('company')->eq($this->app->company->id);
        if($status != 'all') $sql->andWhere('status')->in($status);
        return $sql->orderBy('status, end|desc')->fetchAll();
    }

    /* 通过Id获取项目信息。*/
    public function getById($projectID)
    {
        $project = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
        $total   = $this->dao->select('SUM(estimate) AS totalEstimate, SUM(consumed) AS totalConsumed, SUM(`left`) AS totalLeft')->from(TABLE_TASK)->where('project')->eq((int)$projectID)->andWhere('status')->ne('cancel')->fetch();
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
        foreach($products as $productID) $this->dao->insert(TABLE_PROJECTPRODUCT)->set('project')->eq((int)$projectID)->set('product')->eq((int)$productID)->exec();
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
            ->orderBy('t1.id')
            ->fetchPairs();
    }

    /* 获得相关的子项目列表。*/
    public function getChildProjects($projectID)
    {
        $sql = "SELECT id, name FROM " . TABLE_PROJECT . " WHERE parent = '$projectID'";
        return $this->fetchPairs($sql, 'id', 'name');
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
        $versions = $this->loadModel('story')->getVersions($this->post->stories);
        foreach($this->post->stories as $storyID)
        {
            $productID = $this->post->products[$key];
            $this->dao->insert(TABLE_PROJECTSTORY)
                ->set('project')->eq($projectID)
                ->set('product')->eq($productID)
                ->set('story')->eq($storyID)
                ->set('version')->eq($versions[$storyID])
                ->exec();
            $this->story->setStage($storyID);
        }        
    }

    /* 移除一个需求。*/
    public function unlinkStory($projectID, $storyID)
    {
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->andWhere('story')->eq($storyID)->limit(1)->exec();
        $this->loadModel('story')->setStage($storyID);
    }

    /* 获取团队成员。*/
    public function getTeamMembers($projectID)
    {
        return $this->dao->select('t1.*, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)->fetchAll();
    }

   /* 获取团队成员account=>name列表。*/
    public function getTeamMemberPairs($projectID)
    {
        $users = $this->dao->select('t1.account, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)->fetchPairs();
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
                $member->joinDate    = date('Y-m-d');
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

    /* 燃烧图所需要的数据。*/
    public function getBurnData($projectID = 0)
    {
        $project = $this->getById($projectID);
        $sql     = $this->dao->select('date AS name, `left` AS value')->from(TABLE_BURN)->where('project')->eq((int)$projectID);

        /* 没有指定结束日期的情况。*/
        if($project->end == '0000-00-00')
        {
            $sets = $sql->orderBy('date|desc')->limit(14)->fetchAll('name');
            $sets = array_reverse($sets);

            /* 如果没有记录，手工补齐。*/
            if(!$sets)
            {
                $current = time();
                for($i = 0; $i < 14; $i ++)
                {
                    $nextDay = date('Y-m-d', $current + 60 * 60 * 24 * $i);
                    $set     = array('name' => $nextDay, 'value' => '');
                    $sets[]  = (object)$set;
                }
            }
            foreach($sets as $set) $set->name = substr($set->name, 5);
            return $sets;
        }
        else
        {
            $sets     = $sql->orderBy('date')->fetchAll('name');
            $current = $project->begin;
            $end     = $project->end;
            if($sets)
            {
                end($sets);
                $current = key($sets);
            }

            /* 根据当前日期和项目最后结束的日期，补足后续日期。*/
            if(helper::diffDate($end, $current) > 0)
            {
                while(true)
                {
                    $nextDay = date('Y-m-d', strtotime('next day', strtotime($current)));
                    $current = $nextDay;
                    $sets[$current]->name = $current;
                    $sets[$current]->value = '';    // value为空，这样fushioncharts不会打印节点。
                    if($nextDay == $end) break;
                }
            }
            foreach($sets as $set) $set->name = substr($set->name, 5);
            return $sets;
        }
    }
}
