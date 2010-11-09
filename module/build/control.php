<?php
/**
 * The control file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class build extends control
{
    /* 添加build。*/
    public function create($projectID)
    {
        if(!empty($_POST))
        {
            $buildID = $this->build->create($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('build', $buildID, 'opened');
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
            $changes = $this->build->update($buildID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('build', $buildID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "buildID=$buildID"), 'parent'));
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
        if(!$build) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->loadModel('project')->setMenu($this->project->getPairs(), $build->project);

        /* 赋值。*/
        $this->view->header->title = $this->lang->build->view;
        $this->view->position[]    = $this->lang->build->view;
        $this->view->products      = $this->project->getProducts($build->project);
        $this->view->users         = $this->loadModel('user')->getPairs();
        $this->view->build         = $build;
        $this->view->actions       = $this->loadModel('action')->getList('build', $buildID);
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
            $this->build->delete(TABLE_BUILD, $buildID);
            die(js::locate($this->createLink('project', 'build', "projectID=$build->project"), 'parent'));
        }
    }

    /* AJAX接口：获得产品的build列表。*/
    public function ajaxGetProductBuilds($productID, $varName, $build = '')
    {
        if($varName == 'openedBuild')   die(html::select($varName . '[]', $this->build->getProductBuildPairs($productID, 'noempty'), $build, 'size=4 class=select-3 multiple'));
        if($varName == 'resolvedBuild') die(html::select($varName, $this->build->getProductBuildPairs($productID, 'noempty'), $build, 'class=select-3'));
    }

    /* AJAX接口：获得项目的build列表。*/
    public function ajaxGetProjectBuilds($projectID, $productID, $varName, $build = '')
    {
        if($varName == 'openedBuild')   die(html::select($varName . '[]', $this->build->getProjectBuildPairs($projectID, $productID, 'noempty'), $build, 'size=4 class=select-3 multiple'));
        if($varName == 'resolvedBuild') die(html::select($varName, $this->build->getProjectBuildPairs($projectID, $productID, 'noempty'), $build, 'class=select-3'));
    }
}
