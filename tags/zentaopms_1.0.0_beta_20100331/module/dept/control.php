<?php
/**
 * The control file of dept module of ZenTaoMS.
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
 * @package     dept
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class dept extends control
{
    const NEW_CHILD_COUNT = 5;

    /* 部门列表。*/
    public function browse($deptID = 0)
    {
        $header['title'] = $this->lang->dept->manage . $this->lang->colon . $this->app->company->name;
        $position[]      = $this->lang->dept->manage;

        $parentDepts = $this->dept->getParents($deptID);
        $this->assign('header',     $header);
        $this->assign('position',   $position);
        $this->assign('deptID',     $deptID);
        $this->assign('depts',      $this->dept->getTreeMenu($rooteDeptID = 0, array('deptmodel', 'createManageLink')));
        $this->assign('parentDepts',$parentDepts);
        $this->assign('sons',       $this->dept->getSons($deptID));
        $this->display();
    }

    /* 编辑部门。*/
    public function edit($moduleID)
    {
        if(!empty($_POST))
        {
            if($this->product->update($_POST)) die(js::locate($this->createLink($this->moduleName, 'index', "product=$_POST[id]"), 'parent'));
        }

        $product = $this->product->getByID($productID);
        $header['title'] = $this->lang->product->edit . $this->lang->colon . $product->name;
        $this->assign('header',  $header);
        $this->assign('product', $product);
        $this->display();
    }

    /* 更新排序。*/
    public function updateOrder()
    {
        if(!empty($_POST))
        {
            $this->dept->updateOrder($_POST['orders']);
            die(js::reload('parent'));
        }
    }

    /* 维护下级部门。*/
    public function manageChild()
    {
        if(!empty($_POST))
        {
            $this->dept->manageChild($_POST['parentDeptID'], $_POST['depts']);
            die(js::reload('parent'));
        }
    }

    /* 删除某一个部门。*/
    public function delete($deptID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->dept->confirmDelete, $this->createLink('dept', 'delete', "deptID=$deptID&confirm=yes"));
            exit;
        }
        else
        {
            $this->dept->delete($deptID);
            die(js::reload('parent'));
        }
    }
}
