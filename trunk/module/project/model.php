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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class projectModel extends model
{
    const LINK_MEMBERS_ONE_TIME = 10;

    /* 新增项目。*/
    public function create($project = array())
    {
        if(!is_array($project) or empty($project)) return false;
        $companyID = $this->app->company->id;
        extract($project);
        $sql = "INSERT INTO " . TABLE_PROJECT . " (`company`, `name`, `code`, `begin`, `end`, `goal`, `desc`, `team`) VALUES('$companyID', '$name', '$code', '$begin', '$end', '$goal', '$desc', '$team')";
        $this->dbh->query($sql);
        return $this->dbh->lastInsertId();
    }

    /* 更新一个项目。*/
    public function update($projectID)
    {
        extract($_POST);
        $sql = " UPDATE " . TABLE_PROJECT . " SET `name` = '$name', `code` = '$code', `parent` = '$parent', 
                 `begin` = '$begin', `end` = '$end', `goal` = '$goal', `desc` = '$desc', `team` = '$team'
                 WHERE id = '$projectID'";
        return $this->dbh->exec($sql);
    }

    /* 删除一个项目。*/
    public function delete($projectID)
    {
        return $this->dbh->exec("DELETE FROM " . TABLE_PROJECT . " WHERE id = '$projectID' LIMIT 1");
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
        $projects = array();
        $sql = "SELECT id, name FROM " . TABLE_PROJECT . " WHERE isCat = '0' AND company = '{$this->app->company->id}'";
        return $this->fetchPairs($sql);;
    }

    /* 获得完整的列表。*/
    public function getList()
    {
        $sql = "SELECT * FROM " . TABLE_PROJECT . " WHERE isCat = '0' AND company = '{$this->app->company->id}'";
        return $this->dbh->query($sql)->fetchAll();
    }

    /* 通过Id获取项目信息。*/
    public function getById($projectID)
    {
        return $this->dbh->query("SELECT * FROM " . TABLE_PROJECT . " WHERE id = '$projectID'")->fetch();
    }

    /* 获得相关的产品列表。*/
    public function getProducts($projectID)
    {
        $sql = " SELECT T2.id, T2.name FROM " . TABLE_PROJECTPRODUCT . " AS T1 " .
                " LEFT JOIN " . TABLE_PRODUCT . " AS T2 ON T1.product = T2.id " .
                " WHERE T1.project = '$projectID'";
        return $this->fetchPairs($sql);
    }

    /* 更新相关产品。*/
    public function updateProducts($projectID)
    {
        $sql = "DELETE FROM " . TABLE_PROJECTPRODUCT . " WHERE project = '$projectID'";
        $this->dbh->exec($sql);
        if(!isset($_POST['products'])) return;
        $products = array_unique($_POST['products']);
        foreach($products as $productID)
        {
            $sql = "REPLACE INTO " . TABLE_PROJECTPRODUCT . " VALUES('$projectID', '$productID')";
            $this->dbh->query($sql);
        }
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
        if(!isset($_POST['stories']) or empty($_POST['stories'])) return;
        extract($_POST);
        foreach($stories as $key => $storyID)
        {
            $productID = $products[$key];
            $sql = "INSERT INTO " . TABLE_PROJECTSTORY . " VALUES ('$projectID', '$productID', '$storyID')";
            $this->dbh->query($sql);
        }        
    }

    /* 移除一个需求。*/
    public function unlinkStory($projectID, $storyID)
    {
        $sql = "DELETE FROM " . TABLE_PROJECTSTORY . " WHERE project = '$projectID' AND story = '$storyID' LIMIT 1";
        return $this->dbh->exec($sql);
    }

    /* 获取团队成员。*/
    public function getTeamMembers($projectID)
    {
        $sql = "SELECT T1.*, T2.realname FROM " . TABLE_TEAM . " AS T1 LEFT JOIN " . TABLE_USER . " AS T2 ON T1.account = T2.account  WHERE T1.project = '$projectID'"; 
        $stmt = $this->dbh->query($sql);
        return $stmt->fetchAll();
    }

   /* 获取团队成员account=>name列表。*/
    public function getTeamMemberPair($projectID)
    {
        $sql = "SELECT T2.account, T2.realname FROM " . TABLE_TEAM . " AS T1 LEFT JOIN " . TABLE_USER . " AS T2 ON T1.account = T2.account  WHERE T1.project = '$projectID'"; 
        return $this->fetchPairs($sql);
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
                $sql = "UPDATE " . TABLE_TEAM . " SET role = '$role', workingHour = '$workingHour' WHERE project = '$projectID' AND account = '$account'";
            }
            else
            {
                $sql = "INSERT INTO " . TABLE_TEAM . " (project, account, joinDate, role, workingHour) VALUES ('$projectID', '$account', NOW(), '$role', '$workingHour')";
            }
            $this->dbh->query($sql);
        }        
    }

     /* 删除一个成员。*/
    public function unlinkMember($projectID, $account)
    {
        $sql = "DELETE FROM " . TABLE_TEAM . " WHERE project = '$projectID' AND account = '$account'";
        return $this->dbh->exec($sql);
    }
}
