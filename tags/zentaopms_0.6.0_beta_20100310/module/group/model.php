<?php
/**
 * The model file of group module of ZenTaoMS.
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
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class groupModel extends model
{
    /* 为某一个公司添加分组。*/
    public function create($companyID)
    {
        extract($_POST);
        $sql = "INSERT INTO " . TABLE_GROUP . " (`company`, `name`, `desc`) VALUES ('$companyID', '$name', '$desc')";
        return $this->dbh->exec($sql);
    }

    /* 更新某一个分组信息。*/
    public function update($groupID)
    {
        extract($_POST);
        $sql = "UPDATE " . TABLE_GROUP . " SET `name` = '$name', `desc` = '$desc' WHERE id = '$groupID'";
        return $this->dbh->exec($sql);
    }


    /* 获取某一个公司的分组列表。*/
    public function getList($companyID)
    {
        $sql = "SELECT * FROM " . TABLE_GROUP . " WHERE company = '$companyID'";
        $groups = $this->dbh->query($sql)->fetchAll();
        if($groups) return $groups;
        return array();
    }

    /* 通过 id获取某一个分组信息。*/
    public function getByID($groupID)
    {
        $sql = "SELECT * FROM " . TABLE_GROUP . " WHERE id = '$groupID'";
        return $this->dbh->query($sql)->fetch();
    }

    /* 获得分组的权限列表。*/
    public function getPrivs($groupID)
    {
        $privs = array();
        $sql   = "SELECT module, method FROM " . TABLE_GROUPPRIV . " WHERE `group` = '$groupID' ORDER BY module";
        $stmt  = $this->dbh->query($sql);
        while($priv = $stmt->fetch()) $privs[$priv->module][$priv->method] = $priv->method;
        return $privs;
    }
    
    /* 获得分组的用户列表。*/
    public function getUserPairs($groupID)
    {
        $sql = "SELECT T2.account, T2.realname FROM " . TABLE_USERGROUP . " AS T1 LEFT JOIN " . TABLE_USER . " AS T2 ON T1.account = T2.account WHERE `group` = '$groupID'";
        return $this->fetchPairs($sql);
    }

    /* 删除一个分组信息。*/
    public function delete($groupID)
    {
        $sqls[] = "DELETE FROM " . TABLE_GROUP     . " WHERE id    = '$groupID'";
        $sqls[] = "DELETE FROM " . TABLE_USERGROUP . " WHERE `group` = '$groupID'";
        $sqls[] = "DELETE FROM " . TABLE_GROUPPRIV . " WHERE `group` = '$groupID'";
        foreach($sqls as $sql) $this->dbh->exec($sql);
    }

    /* 更新权限。*/
    public function updatePriv($groupID)
    {
        $sql = "DELETE FROM " . TABLE_GROUPPRIV . " WHERE `group` = '$groupID'";
        $this->dbh->exec($sql);
        if(empty($_POST['actions'])) return;
        foreach($_POST['actions'] as $moduleName => $moduleActions)
        {
            foreach($moduleActions as $actionName)
            {
                $sql = "INSERT INTO " . TABLE_GROUPPRIV . " VALUES('$groupID', '$moduleName', '$actionName')";
                $this->dbh->exec($sql);
            }
        }
    }

    /* 更新成员。*/
    public function updateUser($groupID)
    {
        $sql = "DELETE FROM " . TABLE_USERGROUP . " WHERE `group` = '$groupID'";
        $this->dbh->exec($sql);
        if(empty($_POST['members'])) return;
        foreach($_POST['members'] as $account)
        {
            $sql = "INSERT INTO " . TABLE_USERGROUP . " VALUES('$account', '$groupID')";
            $this->dbh->exec($sql);
        }
    }
}
