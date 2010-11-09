<?php
/**
 * The model file of dept dept of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class deptModel extends model
{
    /* 通过部门id获取部门信息。*/
    public function getByID($deptID)
    {
        return $this->dao->findById($deptID)->from(TABLE_DEPT)->fetch();
    }

    /* 生成查询的sql语句。*/
    private function buildMenuQuery($rootDeptID)
    {
        $rootDept = $this->getByID($rootDeptID);
        if(!$rootDept) $rootDept->path = '';
        return $this->dao->select('*')->from(TABLE_DEPT)
            ->beginIF($rootDeptID > 0)->where('path')->like($rootDept->path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /* 获取部门的下类列表，用于生成select控件。*/
    public function getOptionMenu($rootDeptID = 0)
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
    public function getTreeMenu($rootDeptID = 0, $userFunc)
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
    public function createManageLink($dept)
    {
        $linkHtml  = $dept->name;
        $linkHtml .= ' ' . html::a(helper::createLink('dept', 'browse', "deptid={$dept->id}"), $this->lang->dept->manageChild);
        $linkHtml .= ' ' . html::a(helper::createLink('dept', 'delete', "deptid={$dept->id}"), $this->lang->dept->delete, 'hiddenwin');
        $linkHtml .= ' ' . html::input("orders[$dept->id]", $dept->order, 'style="width:30px;text-align:center"');
        return $linkHtml;
    }

    /* 生成用户链接。*/
    public function createMemberLink($dept)
    {
        $linkHtml = html::a(helper::createLink('company', 'browse', "dept={$dept->id}"), $dept->name, '_self', "id='dept{$dept->id}'");
        return $linkHtml;
    }

    /* 获得某一个部门的直接下级部门。*/
    public function getSons($deptID)
    {
        return $this->dao->select('*')->from(TABLE_DEPT)->where('parent')->eq($deptID)->orderBy('`order`')->fetchAll();
    }
    
    /* 获得一个部门的id列表。*/
    public function getAllChildId($deptID)
    {
        if($deptID == 0) return array();
        $dept = $this->getById($deptID);
        $childs = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like($dept->path . '%')->fetchPairs();
        return array_keys($childs);
    }

    /* 获得一个部门的所有上级部门。*/
    public function getParents($deptID)
    {
        if($deptID == 0) return array();
        $path = $this->dao->select('path')->from(TABLE_DEPT)->where('id')->eq($deptID)->fetch('path');
        $path = substr($path, 1, -1);
        if(empty($path)) return array();
        return $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($path)->orderBy('grade')->fetchAll();
    }

    /* 更新排序信息。*/
    public function updateOrder($orders)
    {
        foreach($orders as $deptID => $order) $this->dao->update(TABLE_DEPT)->set('`order`')->eq($order)->where('id')->eq($deptID)->exec();
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
                $dept->name   = $deptName;
                $dept->parent = $parentDeptID;
                $dept->grade  = $grade;
                $this->dao->insert(TABLE_DEPT)->data($dept)->exec();
                $deptID = $this->dao->lastInsertID();
                $childPath = $parentPath . "$deptID,";
                $this->dao->update(TABLE_DEPT)->set('path')->eq($childPath)->where('id')->eq($deptID)->exec();
            }
            else
            {
                $deptID = str_replace('id', '', $deptID);
                $this->dao->update(TABLE_DEPT)->set('name')->eq($deptName)->where('id')->eq($deptID)->exec();
            }
        }
    }

    /* 获得某一个部门的成员列表。*/
    public function getUsers($deptID)
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($deptID)->andWhere('dept')->in($deptID)->fi()
            ->orderBy('id')
            ->fetchAll();
    }
    
    /* 删除一个部门。Todo: 需要修改下级目录的权限，还有对应的需求列表。*/
    public function delete($deptID)
    {
        $this->dao->delete()->from(TABLE_DEPT)->where('id')->eq($deptID)->exec();
    }
}
