<?php
/**
 * The control file of build module of ZenTaoMS.
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
 * @package     build
 * @version     $$
 * @link        http://www.zentao.cn
 */
class build extends control
{
    /* 添加build。*/
    public function create($projectID)
    {
        if(!empty($_POST))
        {
            $this->build->create($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('project', 'build', "project=$projectID"), 'parent'));
        }

        /* 设置菜单。*/
        $this->loadModel('project')->setMenu($this->project->getPairs(), $projectID);

        /* 赋值。*/
        $this->view->header->title = $this->lang->build->create;
        $this->view->products = $this->project->getProducts($projectID);
        $this->view->users    = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /* 编辑build。*/
    public function edit($buildID)
    {
        if(!empty($_POST))
        {
            $this->build->update($buildID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('project', 'build', "projectID={$this->post->project}"), 'parent'));
        }

        /* 设置菜单。*/
        $build = $this->build->getById((int)$buildID);
        $this->loadModel('project')->setMenu($this->project->getPairs(), $build->project);

        /* 赋值。*/
        $this->view->header->title = $this->lang->build->edit;
        $this->view->position[]    = $this->lang->build->edit;
        $this->view->products      = $this->project->getProducts($build->project);
        $this->view->users         = $this->loadModel('user')->getPairs();
        $this->view->build         = $build;
        $this->display();
    }
                                                          
    /* 查看build。*/
    public function view($buildID)
    {
        /* 设置菜单。*/
        $build = $this->build->getById((int)$buildID);
        $this->loadModel('project')->setMenu($this->project->getPairs(), $build->project);

        /* 赋值。*/
        $this->view->header->title = $this->lang->build->view;
        $this->view->position[]    = $this->lang->build->view;
        $this->view->products      = $this->project->getProducts($build->project);
        $this->view->users         = $this->loadModel('user')->getPairs();
        $this->view->build         = $build;
        $this->display();
    }
 
    /* 删除build。*/
    public function delete($buildID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->build->confirmDelete, $this->createLink('build', 'delete', "buildID=$buildID&confirm=yes")));
        }
        else
        {
            $build = $this->build->getById($buildID);
            $this->build->delete($buildID);
            die(js::locate($this->createLink('project', 'build', "projectID=$build->project"), 'parent'));
        }
    }
}
