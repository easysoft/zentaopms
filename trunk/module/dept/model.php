<?php
/**
 * The model file of dept dept of ZenTaoMS.
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
 * @author      Chunsheng Wang <wwccss@gmail.com>
 * @package     dept
 * @version     $Id: model.php 1360 2009-09-28 03:03:15Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php
class deptModel extends model
{
    /* 通过部门id获取部门信息。*/
    public function getByID($deptID)
    {
        return $this->dbh->query("SELECT * FROM " . TABLE_DEPT . " WHERE id = '$deptID'")->fetch();
    }

    /* 生成查询的sql语句。*/
    private function buildMenuQuery($rootDeptID)
    {
        $sql  = "SELECT * FROM " . TABLE_DEPT . " WHERE company = {$this->app->company->id}";
        if($rootDeptID > 0)
        {
            $rootDept = $this->getByID($rootDeptID);
            if($rootDept) $sql .= " AND `path` LIKE '$rootDept->path%'";
        }
        $sql .= " ORDER BY grade DESC, `order`";
        return $sql;
    }

    /* 获取部门的下类列表，用于生成select控件。*/
    function getOptionMenu($rootDeptID = 0)
    {
        $deptMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($rootDeptID));
        $depts = array();
        while($dept = $stmt->fetch()) $depts[$dept->id] = $dept;

        foreach($depts as $dept)
        {
            $parentDepts = explode(',', $dept->path);
            $deptName = '/';
            foreach($parentDepts as $parentDeptID)
            {
                if(empty($parentDeptID)) continue;
                $deptName .= $depts[$parentDeptID]->name . '/';
            }
            $deptName = rtrim($deptName, '/');
            $deptName .= "|$dept->id\n";

            if(isset($deptMenu[$dept->id]) and !empty($deptMenu[$dept->id]))
            {
                if(isset($deptMenu[$dept->parent]))
                {
                    $deptMenu[$dept->parent] .= $deptName;
                }
                else
                {
                    $deptMenu[$dept->parent] = $deptName;;
                }
                $deptMenu[$dept->parent] .= $deptMenu[$dept->id];
            }
            else
            {
                if(isset($deptMenu[$dept->parent]) and !empty($deptMenu[$dept->parent]))
                {
                    $deptMenu[$dept->parent] .= $deptName;
                }
                else
                {
                    $deptMenu[$dept->parent] = $deptName;
                }    
            }
        }

        $topMenu = @array_pop($deptMenu);
        $topMenu = explode("\n", trim($topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $deptID) = explode('|', $menu);
            $lastMenu[$deptID] = $label;
        }
        return $lastMenu;
    }

    /* 获取树状的部门列表。*/
    function getTreeMenu($rootDeptID = 0, $userFunc)
    {
        $deptMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($rootDeptID));
        while($dept = $stmt->fetch())
        {
            $linkHtml = call_user_func($userFunc, $dept);

            if(isset($deptMenu[$dept->id]) and !empty($deptMenu[$dept->id]))
            {
                if(!isset($deptMenu[$dept->parent])) $deptMenu[$dept->parent] = '';
                $deptMenu[$dept->parent] .= "<li>$linkHtml";  
                $deptMenu[$dept->parent] .= "<ul>".$deptMenu[$dept->id]."</ul>\n";
            }
            else
            {
                if(isset($deptMenu[$dept->parent]) and !empty($deptMenu[$dept->parent]))
                {
                    $deptMenu[$dept->parent] .= "<li>$linkHtml\n";  
                }
                else
                {
                    $deptMenu[$dept->parent] = "<li>$linkHtml\n";  
                }    
            }
            $deptMenu[$dept->parent] .= "</li>\n"; 
        }

        $lastMenu = "<ul id='tree'>" . @array_pop($deptMenu) . "</ul>\n";
        return $lastMenu; 
    }

    /* 生成编辑链接。*/
    function createManageLink($dept)
    {
        $linkHtml  = $dept->name;
        $linkHtml .= ' ' . html::a(helper::createLink('dept', 'browse', "deptid={$dept->id}"), $this->lang->dept->manageChild);
        $linkHtml .= ' ' . html::a(helper::createLink('dept', 'delete', "deptid={$dept->id}"), $this->lang->dept->delete, 'hiddenwin');
        $linkHtml .= ' ' . html::input("orders[$dept->id]", $dept->order, 'style="width:30px;text-align:center"');
        return $linkHtml;
    }

    /* 生成用户链接。*/
    function createMemberLink($dept)
    {
        $linkHtml = html::a(helper::createLink('company', 'browse', "dept={$dept->id}"), $dept->name);
        return $linkHtml;
    }

    /* 获得某一个部门的直接下级部门。*/
    public function getSons($deptID)
    {
        $sql = "SELECT * FROM " . TABLE_DEPT . " WHERE parent = '$deptID' ORDER BY `order`";
        return $this->dbh->query($sql)->fetchAll();
    }
    
    /* 获得一个部门的id列表。*/
    public function getAllChildId($deptID)
    {
        if($deptID == 0) return array();
        $dept = $this->getById($deptID);
        $sql = "SELECT id FROM " . TABLE_DEPT . " WHERE path LIKE '{$dept->path}%'";
        $stmt = $this->dbh->query($sql);
        $deptIds = array();
        while($id = $stmt->fetchColumn()) $deptIds[] = $id;
        return $deptIds;
    }

    /* 获得一个部门的所有上级部门。*/
    public function getParents($deptID)
    {
        if($deptID == 0) return array();
        $sql  = "SELECT path FROM " . TABLE_DEPT . " WHERE id = '$deptID'";
        $path = $this->dbh->query($sql)->fetchColumn();
        $path = substr($path, 1, -1);
        if(empty($path)) return array();
        $sql = "SELECT * FROM " . TABLE_DEPT . " WHERE id IN($path) ORDER BY grade";
        $parents = $this->dbh->query($sql)->fetchAll();
        return $parents;
    }

    /* 更新排序信息。*/
    public function updateOrder($orders)
    {
        foreach($orders as $deptID => $order)
        {
            $sql = "UPDATE " . TABLE_DEPT . " SET `order` = '$order' WHERE id = '$deptID' LIMIT 1";
            $this->dbh->exec($sql);
        }
    }

    /* 更新某一个部门的子部门。*/
    public function manageChild($parentDeptID, $childs)
    {
        $parentDept = $this->getByID($parentDeptID);
        if($parentDept)
        {
            $grade      = $parentDept->grade + 1;
            $parentPath = $parentDept->path;
        }
        else
        {
            $grade      = 1;
            $parentPath = ',';
        }

        foreach($childs as $deptID => $deptName)
        {
            if(empty($deptName)) continue;
            if(is_numeric($deptID))
            {
                $sql = "INSERT INTO " . TABLE_DEPT . "(`company`, `name`, `parent`, `path`, `grade`) 
                        VALUES('{$this->app->company->id}', '$deptName', '$parentDeptID', '', '$grade')";
                $this->dbh->exec($sql);
                $deptID  = $this->dbh->lastInsertID();
                $childPath = $parentPath . "$deptID,";
                $sql = "UPDATE " . TABLE_DEPT . " SET `path` = '$childPath' WHERE id = '$deptID' LIMIT 1";
                $this->dbh->exec($sql);
            }
            else
            {
                $deptID = str_replace('id', '', $deptID);
                $sql = "UPDATE " . TABLE_DEPT . " SET `name` = '$deptName' WHERE id = '$deptID' LIMIT 1";
                $this->dbh->exec($sql);
            }
        }
    }

    /* 获得某一个部门的成员列表。*/
    public function getUsers($deptID)
    {
        $sql = "SELECT * FROM " . TABLE_USER . " WHERE dept " . helper::dbIN($deptID) . " ORDER BY id";
        return $this->dbh->query($sql)->fetchAll();
    }
    
    /* 删除一个部门。Todo: 需要修改下级目录的权限，还有对应的需求列表。*/
    function delete($deptID)
    {
        $sql = "DELETE FROM " . TABLE_DEPT . " WHERE id = '$deptID' LIMIT 1";
        return $this->dbh->exec($sql);
    }
}
