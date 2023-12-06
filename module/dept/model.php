<?php
declare(strict_types=1);
/**
 * The model file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @link        https://www.zentao.net
 */
class deptModel extends model
{
    /**
     * 根据部门ID获取部门信息。
     * Get a department by id.
     *
     * @param  int          $deptID
     * @access public
     * @return object|false
     */
    public function getByID(int $deptID): object|false
    {
        return $this->dao->findById($deptID)->from(TABLE_DEPT)->fetch();
    }

    /**
     * 获取所有部门名称。
     * Get all department names.
     *
     * @access public
     * @return array
     */
    public function getDeptPairs(): array
    {
        return $this->dao->select('id,name')->from(TABLE_DEPT)->fetchPairs();
    }

    /**
     * 获取下一级部门的部门信息。
     * Get sons of a department.
     *
     * @param  int    $deptID
     * @access public
     * @return array
     */
    public function getSons(int $deptID): array
    {
        return $this->dao->select('*')->from(TABLE_DEPT)->where('parent')->eq($deptID)->orderBy('`order`')->fetchAll();
    }

    /**
     * 获取当前部门以及父级部门的部门信息。
     * Get parents.
     *
     * @param  int    $deptID
     * @access public
     * @return array
     */
    public function getParents(int $deptID): array
    {
        if(!$deptID) return array();
        $path = $this->dao->select('path')->from(TABLE_DEPT)->where('id')->eq($deptID)->fetch('path');
        $path = substr($path, 1, -1);
        if(empty($path)) return array();

        return $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($path)->orderBy('grade')->fetchAll();
    }

    /**
     * 获取所有子级部门的部门信息。
     * Get child departments info.
     *
     * @param  int         $rootDeptID
     * @access public
     * @return array
     */
    public function getChildDepts(int $rootDeptID): array
    {
        $rootDept = $this->fetchByID($rootDeptID);
        $rootPath = !empty($rootDept) ? $rootDept->path : '';

        return $this->dao->select('*')->from(TABLE_DEPT)
            ->beginIF($rootPath)->where('path')->like("{$rootPath}%")->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');
    }

    /**
     * 获取所有自己部门的ID。
     * Get all childs.
     *
     * @param  int    $deptID
     * @access public
     * @return array
     */
    public function getAllChildID(int $deptID): array
    {
        $dept = $this->fetchByID($deptID);
        if(!$dept) return array();

        $childs = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like($dept->path . '%')->fetchPairs();
        return array_keys($childs);
    }

    /**
     * 获取带层级结构的部门列表。
     * Get option menu of departments.
     *
     * @param  int    $rootDeptID
     * @access public
     * @return array
     */
    public function getOptionMenu(int $rootDeptID = 0)
    {
        $deptMenu = array();
        $depts    = $this->getChildDepts($rootDeptID);
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

            if(!isset($deptMenu[$dept->parent])) $deptMenu[$dept->parent] = '';

            $deptMenu[$dept->parent] .= $deptName;
            if(!empty($deptMenu[$dept->id])) $deptMenu[$dept->parent] .= $deptMenu[$dept->id];
        }

        krsort($deptMenu);
        $topMenu = array_pop($deptMenu);
        $topMenu = explode("\n", trim((string)$topMenu));

        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $deptID) = explode('|', $menu);
            $lastMenu[$deptID] = $label;
        }

        return $lastMenu;
    }

    /**
     * 获取部门树形结构所需的数据。
     * Get the treemenu of departments.
     *
     * @param  int    $rootDeptID
     * @param  array  $userFunc
     * @param  int    $param
     * @access public
     * @return array
     */
    public function getTreeMenu(int $rootDeptID = 0, array $userFunc = array(), int $param = 0): array
    {
        $deptMenu = array();
        $depts    = $this->getChildDepts($rootDeptID);
        foreach($depts as $dept)
        {
            $data = new stdclass();
            $data->id     = $dept->id;
            $data->parent = $dept->parent;
            $data->name   = $dept->name;
            $data->url    = call_user_func($userFunc, $dept, $param);

            $deptMenu[] = $data;
        }

        return $deptMenu;
    }

    /**
     * 更新部门信息。
     * Update a dept.
     *
     * @param  object $dept
     * @access public
     * @return bool
     */
    public function update(object $dept): bool
    {
        $oldDept = $this->fetchByID($dept->id);

        /* 更新当前部门的信息。 */
        $this->dao->update(TABLE_DEPT)->data($dept)->autoCheck()->batchCheck($this->config->dept->edit->requiredFields, 'notempty')->where('id')->eq($dept->id)->exec();
        if(dao::isError()) return false;

        /* 变更当前部门的子部门负责人。 */
        $childs = $this->getAllChildID($dept->id);
        if(!empty($dept->manager)) $this->dao->update(TABLE_DEPT)->set('manager')->eq($dept->manager)->where('id')->in($childs)->andWhere('manager', true)->eq('')->orWhere('manager')->eq($oldDept->manager)->markRight(1)->exec();

        /* 整理部门的path和grade。 */
        $this->fixDeptPath();

        return !dao::isError();
    }

    /**
     * 生成用户列表页面部门的跳转链接。
     * Create the member link.
     *
     * @param  object $dept
     * @access public
     * @return string
     */
    public function createMemberLink(object $dept): string
    {
        return helper::createLink('company', 'browse', "browseType=inside&dept={$dept->id}");
    }

    /**
     * 生成权限成员维护页面部门的跳转链接。
     * Create the group manage members link.
     *
     * @param  object $dept
     * @param  int    $groupID
     * @access public
     * @return string
     */
    public function createGroupManageMemberLink(object $dept, int $groupID): string
    {
        return helper::createLink('group', 'managemember', "groupID=$groupID&deptID={$dept->id}");
    }

    /**
     * 生成维护管理对象页面部门的跳转链接。
     * Create the group manage program admin link.
     *
     * @param  object $dept
     * @param  int    $groupID
     * @access public
     * @return string
     */
    public function createManageProjectAdminLink(object $dept, int $groupID): string
    {
        return helper::createLink('group', 'manageProjectAdmin', "groupID=$groupID&deptID={$dept->id}");
    }

    /**
     * 部门排序。
     * Update order.
     *
     * @param  array  $orders
     * @access public
     * @return bool
     */
    public function updateOrder($orders): bool
    {
        $order = 1;
        foreach($orders as $deptID)
        {
            $this->dao->update(TABLE_DEPT)->set('`order`')->eq($order)->where('id')->eq($deptID)->exec();
            $order ++;
        }
        return !dao::isError();
    }

    /**
     * 新增或者编辑部门。
     * Manage childs.
     *
     * @param  int    $parentDeptID
     * @param  array  $childs
     * @param  int    $maxOrder
     * @access public
     * @return array
     */
    public function manageChild(int $parentDeptID, array $childs, int $maxOrder = 0): array
    {
        $parentDept = $this->fetchByID($parentDeptID);
        $grade      = $parentDept ? ($parentDept->grade + 1) : 1;
        $parentPath = $parentDept ? $parentDept->path : ',';

        $index      = 1;
        $deptIDList = array();
        foreach($childs as $deptID => $deptName)
        {
            if(empty($deptName)) continue;
            if(is_numeric($deptID))
            {
                /* 处理新插入的部门数据。 */
                $dept = new stdclass();
                $dept->name   = strip_tags($deptName);
                $dept->parent = $parentDeptID;
                $dept->grade  = $grade;
                $dept->order  = $maxOrder + $index * 10;
                $this->dao->insert(TABLE_DEPT)->data($dept)->exec();

                $deptID       = $this->dao->lastInsertID();
                $deptIDList[] = $deptID;
                $index ++;

                $childPath = $parentPath . "$deptID,";
                $this->dao->update(TABLE_DEPT)->set('path')->eq($childPath)->where('id')->eq($deptID)->exec();
            }
            else
            {
                /* 处理可能发生的变更名称。 */
                $deptID = str_replace('id', '', $deptID);
                $this->dao->update(TABLE_DEPT)->set('name')->eq(strip_tags($deptName))->where('id')->eq($deptID)->exec();
            }
        }

        /* 返回新增的部门ID。 */
        return $deptIDList;
    }

    /**
     * 获取部门下对应的用户列表。
     * Get users of a deparment.
     *
     * @param  string $browseType inside|outside|all
     * @param  array  $depts
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUsers(string $browseType = 'inside', array $depts = array(), string $orderBy = 'id', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($browseType == 'inside' || $browseType == 'outside')->andWhere('type')->eq($browseType)->fi()
            ->beginIF($depts)->andWhere('dept')->in($depts)->fi()
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取指定部门下的用户列表。
     * Get user pairs of a department.
     *
     * @param  int    $deptID
     * @param  string $key     id|account
     * @param  string $type    inside|outside
     * @param  string $params  all
     * @access public
     * @return array
     */
    public function getDeptUserPairs(int $deptID = 0, string $key = 'account', string $type = 'inside', string $params = ''): array
    {
        $childDepts = $this->getAllChildID($deptID);
        $keyField   = $key == 'id' ? 'id' : 'account';
        $type       = $type == 'outside' ? 'outside' : 'inside';

        return $this->dao->select("$keyField, realname")->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($params, 'all') === false)->andWhere('type')->eq($type)->fi()
            ->beginIF($childDepts)->andWhere('dept')->in($childDepts)->fi()
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy('account')
            ->fetchPairs();
    }

    /**
     * 整理部门的path和grade。
     * Fix dept path.
     *
     * @access public
     * @return void
     */
    public function fixDeptPath()
    {
        /* Get all depts grouped by parent. */
        $depts      = array();
        $groupDepts = $this->dao->select('id, parent')->from(TABLE_DEPT)->fetchGroup('parent', 'id');

        /* Cycle the groupDepts until it has no item any more. */
        while(count($groupDepts) > 0)
        {
            $oldCounts = count($groupDepts);    // Record the counts before processing.
            foreach($groupDepts as $parentDeptID => $childDepts)
            {
                /* If the parentDept doesn't exsit in the depts, skip it. If exists, compute it's child depts. */
                if(!isset($depts[$parentDeptID]) and $parentDeptID != 0) continue;
                if($parentDeptID == 0)
                {
                    $parentDept = new stdclass();
                    $parentDept->grade = 0;
                    $parentDept->path  = ',';
                }
                else
                {
                    $parentDept = $depts[$parentDeptID];
                }

                /* Compute it's child depts. */
                foreach($childDepts as $childDeptID => $childDept)
                {
                    $childDept->grade = $parentDept->grade + 1;
                    $childDept->path  = $parentDept->path . $childDept->id . ',';
                    $depts[$childDeptID] = $childDept;    // Save child dept to depts, thus the child of child can compute it's grade and path.
                }
                unset($groupDepts[$parentDeptID]);    // Remove it from the groupDepts.
            }
            if(count($groupDepts) == $oldCounts) break;   // If after processing, no dept processed, break the cycle.
        }

        /* Save depts to database. */
        foreach($depts as $dept) $this->dao->update(TABLE_DEPT)->data($dept)->where('id')->eq($dept->id)->exec();

        return !dao::isError();
    }

    /**
     * 获取带有层级关系的部门结构。
     * Get data structure.
     *
     * @access public
     * @return array
     */
    public function getDataStructure(): array
    {
        $tree       = array();
        $users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted|all');
        $treeGroups = $this->dao->select('*')->from(TABLE_DEPT)->orderBy('grade_desc,`order`')->fetchGroup('parent', 'id');
        foreach($treeGroups as $parent => $groups)
        {
            foreach($groups as $deptID => $node)
            {
                $node->managerName = zget($users, $node->manager);
                $node->url         = helper::createLink('dept', 'browse', "deptID={$deptID}");
                $node->key         = $node->name;
                $node->text        = $node->name;
                if(isset($tree[$deptID]))
                {
                    $node->items = $tree[$deptID];
                    unset($tree[$deptID]);
                }
                $tree[$node->parent][] = $node;
            }
        }

        krsort($tree);
        return $tree ? array_pop($tree) : array();
    }

    /**
     * 删除部门。
     * Delete dept.
     *
     * @param  int    $deptID
     * @access public
     * @return bool
     */
    public function deleteDept(int $deptID): bool
    {
        $this->dao->delete()->from(TABLE_DEPT)->where('id')->eq($deptID)->exec();
        return !dao::isError();
    }
}
