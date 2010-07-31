<?php
/**
 * The control file of doc module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: control.php 933 2010-07-06 06:53:40Z wwccss $
 * @link        http://www.zentaoms.com
 */
class doc extends control
{
    public function __construct()
    {
        parent::__construct();
        $this->libs = $this->doc->getLibs();
    }

    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /* 浏览某一个产品。*/
    public function browse($libID = 'product', $moduleID = 0, $productID = 0, $projectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('tree');
        $this->doc->setMenu($this->libs, $libID, 'doc');
        $this->session->set('docList',   $this->app->getURI(true));

        /* 设置header和导航条信息。*/
        $this->view->header->title = $this->lang->doc->index . $this->lang->colon . $this->libs[$libID];
        $this->view->position[]    = $this->libs[$libID];

        /* 加载分页类，并查询docs列表。*/
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* 查找文档列表。*/
        $modules = 0;
        if($moduleID) $modules = $this->tree->getAllChildID($moduleID);
        $docs = $this->doc->getDocs($libID, $productID, $projectID, $modules, $orderBy, $pager);

        /* 获得树状列表。*/
        if($libID == 'product' or $libID == 'project')
        {
            $moduleTree = $this->tree->getSystemDocTreeMenu($libID);
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($libID, $viewType = 'customdoc', $startModuleID = 0, array('treeModel', 'createDocLink'));
        }

        $this->view->libID         = $libID;
        $this->view->libName       = $this->libs[$libID];
        $this->view->moduleID      = $moduleID;
        $this->view->moduleTree    = $moduleTree;
        $this->view->parentModules = $this->tree->getParents($moduleID);
        $this->view->docs          = $docs;
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy       = $orderBy;

        $this->display();
    }

    /* 新增文档库。*/
    public function createLib()
    {
        if(!empty($_POST))
        {
            $libID = $this->doc->createLib();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('docLib', $libID, 'Created');
            die(js::locate($this->createLink($this->moduleName, 'browse', "libID=$libID"), 'parent'));
        }
        die($this->display());
    }

    /* 编辑文档库。*/
    public function editLib($libID)
    {
        if(!empty($_POST))
        {
            $changes = $this->doc->updateLib($libID); 
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('docLib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink($this->moduleName, 'browse', "libID=$libID"), 'parent'));
            //die(js::locate(inlink('view', "libID=$libID"), 'parent'));
        }
        
        $lib = $this->doc->getLibByID($libID);
        $this->view->libName = empty($lib) ? $libID : $lib->name;
        
        die($this->display());
    }

    /* 删除文档库。*/
    public function deleteLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->doc->confirmDeleteLib, $this->createLink('doc', 'deleteLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->doc->delete(TABLE_DOCLIB, $libID);
            //$this->session->set('doc', '');     // 清除session。
            die(js::locate($this->createLink('doc', 'browse'), 'parent'));
        }

    }

}
