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
    /* 构造函数，加载公用的模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
        $this->loadModel('tree');
        $this->loadModel('action');
        $this->libs = $this->doc->getLibs();
    }

    /* 首页，跳转到浏览页面。*/
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /* 浏览文档。*/
    public function browse($libID = 'product', $moduleID = 0, $productID = 0, $projectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
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
        $this->view->productID     = $productID;
        $this->view->projectID     = $projectID;

        $this->display();
    }

    /* 新增文档库。*/
    public function createLib()
    {
        if(!empty($_POST))
        {
            $libID = $this->doc->createLib();
            if(!dao::isError())
            {
                $this->loadModel('action')->create('docLib', $libID, 'Created');
                die(js::locate($this->createLink($this->moduleName, 'browse', "libID=$libID"), 'parent'));
            }
            else
            {
                echo js::error(dao::getError());
            }
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
        }
        
        $lib = $this->doc->getLibByID($libID);
        $this->view->libName = empty($lib) ? $libID : $lib->name;
        $this->view->libID   = $libID;
        
        die($this->display());
    }

    /* 删除文档库。*/
    public function deleteLib($libID, $confirm = 'no')
    {
        if($libID == 'product' or $libID == 'project') die();
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->doc->confirmDeleteLib, $this->createLink('doc', 'deleteLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->doc->delete(TABLE_DOCLIB, $libID);
            die(js::locate($this->createLink('doc', 'browse'), 'parent'));
        }
    }
    
    /* 创建文档。*/
    public function create($libID, $moduleID = 0, $productID = 0, $projectID = 0, $from = 'doc')
    {
        $projectID = (int)$projectID;
        if(!empty($_POST))
        {
            $docID = $this->doc->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('doc', $docID, 'Created');

            if($from == 'product') $link = $this->createLink('product', 'doc', "productID={$this->post->product}");
            if($from == 'project') $link = $this->createLink('project', 'doc', "projectID={$this->post->project}");
            if($from == 'doc')
            {
                $productID = intval($this->post->product);
                $projectID = intval($this->post->project);
                $vars = "libID=$libID&moduleID={$this->post->module}&productID=$productID&projectID=$projectID";
                $link = $this->createLink('doc', 'browse', $vars);
            }
            die(js::locate($link, 'parent'));
        }

        $this->loadModel('product');
        $this->loadModel('project');

        /* 设置当前的文档库，设置菜单。*/
        if($from == 'product')
        {
            $this->lang->doc->menu = $this->lang->product->menu;
            $this->product->setMenu($this->product->getPairs(), $productID);
            $this->lang->set('menugroup.doc', 'product');
        }
        elseif($from == 'project')
        {
            $this->lang->doc->menu = $this->lang->project->menu;
            $this->project->setMenu($this->project->getPairs(), $projectID);
            $this->lang->set('menugroup.doc', 'project');
        }
        else
        {
            $this->doc->setMenu($this->libs, $libID);
        }

        /* 获得子模块列表。*/
        if($libID == 'product' or $libID == 'project')
        {
            $moduleOptionMenu = $this->tree->getOptionMenu(0, $libID . 'doc', $startModuleID = 0);
        }
        else
        {
            $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'customdoc', $startModuleID = 0);
        }

        /* 位置信息。*/
        $this->view->header->title = $this->libs[$libID] . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[]    = html::a($this->createLink('doc', 'browse', "libID=$libID"), $this->libs[$libID]);
        $this->view->position[]    = $this->lang->doc->create;

        $this->view->libID            = $libID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->moduleID         = $moduleID;
        $this->view->productID        = $productID;
        $this->view->projectID        = $projectID;
        $this->view->products         = $projectID == 0 ? $this->product->getPairs() : $this->project->getProducts($projectID);
        $this->view->projects         = $this->loadModel('project')->getPairs();

        $this->display();
    }

    /* 编辑文档。*/
    public function edit($docID)
    {
        /* 更新文档信息。*/
        if(!empty($_POST))
        {
            $changes  = $this->doc->update($docID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('doc', $docID);
            if(!empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('doc', $docID, $action, $fileAction);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('doc', 'view', "docID=$docID"), 'parent'));
        }

        /* 查找当前文档信息。*/
        $doc = $this->doc->getById($docID);

        /* 设置菜单。*/
        $libID = $doc->lib;
        $this->doc->setMenu($this->libs, $libID);

        /* 获得子模块列表。*/
        if($libID == 'product' or $libID == 'project')
        {
            $moduleOptionMenu = $this->tree->getOptionMenu(0, $libID . 'doc', $startModuleID = 0);
        }
        else
        {
            $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'customdoc', $startModuleID = 0);
        }

        /* 位置信息。*/
        $this->view->header->title = $this->libs[$libID] . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[]    = html::a($this->createLink('doc', 'browse', "libID=$libID"), $this->libs[$libID]);
        $this->view->position[]    = $this->lang->doc->create;

        $this->view->libID            = $libID;
        $this->view->users            = $this->user->getPairs('noclosed,nodeleted');
        $this->view->title            = $doc->title;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->moduleID         = $doc->module;
        $this->view->productID        = $doc->product;
        $this->view->projectID        = $doc->project;
        $this->display();
    }

    /* 查看一个文档。*/
    public function view($docID)
    {
        /* 查找文档信息。*/
        $doc = $this->doc->getById($docID);
        if(!$doc) die(js::error($this->lang->notFound) . js::locate('back'));
        
        /* 设置菜单。*/
        $this->doc->setMenu($this->libs, $doc->lib);

        /* 位置信息。*/
        $this->view->header->title = $this->libs[$doc->lib] . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[]    = html::a($this->createLink('doc', 'browse', "libID=$doc->lib"), $this->libs[$doc->lib]);
        $this->view->position[]    = $this->lang->doc->create;

        $this->view->doc     = $doc; 
        $this->view->actions = $this->loadModel('action')->getList('doc', $docID);
        $this->view->users   = $this->user->getPairs('noclosed,nodeleted');

        $this->display();
    }

    /* 删除文档。*/
    public function delete($docID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->doc->confirmDelete, inlink('delete', "docID=$docID&confirm=yes")));
        }
        else
        {
            $this->doc->delete(TABLE_DOC, $docID);
            die(js::locate($this->session->docList, 'parent'));
        }
    }

    /* 删除一个文件。*/
    public function deleteFile($fileID)
    {
        $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($fileID)->exec();
        die(js::reload('parent'));
    }
}
