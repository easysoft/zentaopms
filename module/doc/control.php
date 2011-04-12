<?php
/**
 * The control file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: control.php 933 2010-07-06 06:53:40Z wwccss $
 * @link        http://www.zentao.net
 */
class doc extends control
{
    /**
     * Construct function, load user, tree, action auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
        $this->loadModel('tree');
        $this->loadModel('action');
        $this->loadModel('product');
        $this->loadModel('project');
        $this->libs = $this->doc->getLibs();
    }

    /**
     * Go to browse page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * Browse docs.
     * 
     * @param  string|int $libID    product|project or the int id of custom library
     * @param  int    $moduleID 
     * @param  int    $productID 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($libID = 'product', $moduleID = 0, $productID = 0, $projectID = 0, $browseType = 'byModule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {  
        /* Set browseType.*/ 
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu, save session. */
        $this->doc->setMenu($this->libs, $libID, 'doc');
        $this->session->set('docList',   $this->app->getURI(true));

        /* Set header and position. */
        $this->view->header->title = $this->lang->doc->index . $this->lang->colon . $this->libs[$libID];
        $this->view->position[]    = $this->libs[$libID];

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
 
        /* Get docs. */
        $modules = 0;
        $docs=array();
        if($browseType == "bymodule")
        {
            if($moduleID) $modules = $this->tree->getAllChildID($moduleID);
            $docs = $this->doc->getDocs($libID, $productID, $projectID, $modules, $orderBy, $pager);
        }
        elseif($browseType == "bysearch")
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('docQuery', $query->sql);
                    $this->session->set('docForm', $query->form);
                }
                else
                {
                    $this->session->set('docQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->docQuery == false) $this->session->set('docQuery', ' 1 = 1');
            }
            $docQuery = str_replace("`product` = 'all'", '1', $this->session->docQuery); // Search all producti.
            $docQuery = str_replace("`project` = 'all'", '1', $docQuery);                // Search all project.
            $docs = $this->dao->select('*')->from(TABLE_DOC)->where($docQuery)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        /* Get the tree menu. */
        if($libID == 'product')
        {
            $moduleTree = $this->tree->getProductDocTreeMenu();
        }
        elseif($libID == 'project')
        {
            $moduleTree = $this->tree->getProjectDocTreeMenu();
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($libID, $viewType = 'customdoc', $startModuleID = 0, array('treeModel', 'createDocLink'));
        }
       
        /* Build the search form. */
        $this->config->doc->search['actionURL'] = $this->createLink('doc', 'browse', "libID=$libID&moduleID=$moduleID&procuctID=$productID&projectID=$projectID&browseType=bySearch&queryID=myQueryID");
        $this->config->doc->search['queryID']   = $queryID;
        $this->config->doc->search['params']['product']['values']       = array(''=>'') + $this->product->getPairs() + array('all'=>$this->lang->doc->allProduct);
        $this->config->doc->search['params']['project']['values']       = array(''=>'') + $this->project->getPairs() + array('all'=>$this->lang->doc->allProject);
        $this->config->doc->search['params']['lib']['values']           = array(''=>'') + $this->libs;
        $this->config->doc->search['params']['type']['values']           = array(''=>'') + $this->config->doc->search['params']['type']['values'];
                /* Get the modules. */
        if($libID == 'product' or $libID == 'project')
        {
            $moduleOptionMenu = $this->tree->getOptionMenu(0, $libID . 'doc', $startModuleID = 0);
        }
        else
        {
            $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'customdoc', $startModuleID = 0);
        }
        $this->config->doc->search['params']['module']['values']        = array(''=>'') + $moduleOptionMenu;
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->doc->search);

        $this->view->libID         = $libID;
        $this->view->libName       = $this->libs[$libID];
        $this->view->moduleID      = $moduleID;
        $this->view->moduleTree    = $moduleTree;
        $this->view->parentModules = $this->tree->getParents($moduleID);
        $this->view->docs          = $docs;
        $this->view->pager         = $pager;
        //$this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy       = $orderBy;
        $this->view->productID     = $productID;
        $this->view->projectID     = $projectID;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;

        $this->display();
    }

    /**
     * Create a library.
     * 
     * @access public
     * @return void
     */
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

    /**
     * Edit a library.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
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

    /**
     * Delete a library.
     * 
     * @param  int    $libID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
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
    
    /**
     * Create a doc.
     * 
     * @param  int|string   $libID 
     * @param  int          $moduleID 
     * @param  int          $productID 
     * @param  int          $projectID 
     * @param  string       $from 
     * @access public
     * @return void
     */
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

        /* According the from, set menus. */
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

        /* Get the modules. */
        if($libID == 'product' or $libID == 'project')
        {
            $moduleOptionMenu = $this->tree->getOptionMenu(0, $libID . 'doc', $startModuleID = 0);
        }
        else
        {
            $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'customdoc', $startModuleID = 0);
        }

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

    /**
     * Edit a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function edit($docID)
    {
        if(!empty($_POST))
        {
            $changes  = $this->doc->update($docID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('doc', $docID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('doc', $docID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('doc', 'view', "docID=$docID"), 'parent'));
        }

        /* Get doc and set menu. */
        $doc = $this->doc->getById($docID);
        $libID = $doc->lib;
        $this->doc->setMenu($this->libs, $libID);

        /* Get modules. */
        if($libID == 'product' or $libID == 'project')
        {
            $moduleOptionMenu = $this->tree->getOptionMenu(0, $libID . 'doc', $startModuleID = 0);
        }
        else
        {
            $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'customdoc', $startModuleID = 0);
        }

        $this->view->header->title = $this->libs[$libID] . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[]    = html::a($this->createLink('doc', 'browse', "libID=$libID"), $this->libs[$libID]);
        $this->view->position[]    = $this->lang->doc->edit;

        $this->view->doc              = $doc;
        $this->view->libID            = $libID;
        $this->view->users            = $this->user->getPairs('noclosed,nodeleted');
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->display();
    }

    /**
     * View a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function view($docID)
    {
        /* Get doc. */
        $doc = $this->doc->getById($docID);
        if(!$doc) die(js::error($this->lang->notFound) . js::locate('back'));
        
        /* Get library. */
        $lib = $doc->libName;
        if($doc->lib == 'product') $lib = $doc->productName;
        if($doc->lib == 'project') $lib = $doc->productName . $this->lang->arrow . $doc->projectName;

        /* Set menu. */
        $this->doc->setMenu($this->libs, $doc->lib);

        $this->view->header->title = $this->libs[$doc->lib] . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[]    = html::a($this->createLink('doc', 'browse', "libID=$doc->lib"), $this->libs[$doc->lib]);
        $this->view->position[]    = $this->lang->doc->view;

        $this->view->doc     = $doc;
        $this->view->lib     = $lib;
        $this->view->actions = $this->loadModel('action')->getList('doc', $docID);
        $this->view->users   = $this->user->getPairs('noclosed,nodeleted');

        $this->display();
    }

    /**
     * Delete a doc.
     * 
     * @param  int    $docID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
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
}
