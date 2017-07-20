<?php
/**
 * The control file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('user');
        $this->loadModel('tree');
        $this->loadModel('action');
        $this->loadModel('product');
        $this->loadModel('project');
        $this->from      = $this->cookie->from ? $this->cookie->from : 'doc';
        $this->productID = $this->cookie->product ? $this->cookie->product : '0';
    }

    /**
     * Go to browse page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->doc->setMenu();

        $products   = $this->doc->getLimitLibs('product', '9');
        $projects   = $this->doc->getLimitLibs('project', '9');
        $customLibs = $this->doc->getLimitLibs('custom', '9');
        $subLibs['product'] = $this->doc->getSubLibGroups('product', array_keys($products));
        $subLibs['project'] = $this->doc->getSubLibGroups('project', array_keys($projects));

        $this->view->title      = $this->lang->doc->common . $this->lang->colon . $this->lang->doc->index;
        $this->view->position[] = $this->lang->doc->index;
        $this->view->products   = $products;
        $this->view->projects   = $projects;
        $this->view->customLibs = $customLibs;
        $this->view->subLibs    = $subLibs;
        $this->display();
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
    public function browse($libID = 0, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $from = 'doc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->from = $from;
        setcookie('from',  $from, $this->config->cookieLife, $this->config->webRoot);

        $this->loadModel('search');

        /* Set browseType.*/ 
        $browseType = strtolower($browseType);
        if(($this->cookie->browseType == 'bymenu' or $this->cookie->browseType == 'bytree') and $browseType != 'bysearch') $browseType = $this->cookie->browseType;
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $moduleID   = ($browseType == 'bymodule' or $browseType == 'bymenu') ? (int)$param : 0;

        $type = 'custom';
        if($libID)
        {
            $lib = $this->doc->getLibByID($libID);
            if($lib->product or $lib->project) $type = $lib->product ? 'product' : 'project';
        }
        if(empty($libID)) $type = 'product';

        $this->libs = $this->doc->getLibs($type);
        if(empty($libID))
        {
            $libID = key($this->libs);
            $lib = $this->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq($libID)->orderBy('id')->fetch();
        }

        /* According the from, set menus. */
        if($from == 'product')
        {
            $this->lang->doc->menu      = $this->lang->product->menu;
            $this->lang->doc->menuOrder = $this->lang->product->menuOrder;
            $this->product->setMenu($this->product->getPairs(), $lib->product);
            $this->lang->set('menugroup.doc', 'product');
        }
        elseif($from == 'project')
        {
            $this->lang->doc->menu      = $this->lang->project->menu;
            $this->lang->doc->menuOrder = $this->lang->project->menuOrder;
            $this->project->setMenu($this->project->getPairs('nocode'), $lib->project);
            $this->lang->set('menugroup.doc', 'project');
        }
        else
        {
            $this->doc->setMenu($libID, $moduleID);
        }
        $this->session->set('docList',   $this->app->getURI(true));

        /* Set header and position. */
        $this->view->title      = $this->lang->doc->common . $this->lang->colon . $this->libs[$libID];
        $this->view->position[] = $this->libs[$libID];

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        if($browseType == 'bymenu' and strtolower($orderBy) != 'editeddate_desc' and strtolower($orderBy) != 'addeddate_desc') $orderBy = 'title_asc';
        $sort = $this->loadModel('common')->appendOrder($orderBy);
 
        /* Get docs by browse type. */
        $docs = $this->doc->getDocsByBrowseType($libID, $browseType, $queryID, $moduleID, $sort, $pager);
       
        /* Build the search form. */
        $actionURL = $this->createLink('doc', 'browse', "lib=$libID&browseType=bySearch&queryID=myQueryID&orderBy=$orderBy&from=$from");
        $this->doc->buildSearchForm($libID, $this->libs, $queryID, $actionURL, $type);

        $this->view->fixedMenu = false;
        foreach(customModel::getModuleMenu('doc') as $item)
        {
            if($item->name == "custom$libID" and empty($item->hidden)) $this->view->fixedMenu = true;
        }

        if($this->cookie->browseType == 'bymenu')
        {
            $this->view->modules = $this->doc->getDocMenu($libID, $moduleID, $orderBy == 'title_asc' ? 'name_asc' : 'id_desc');
            $this->view->parents = $this->loadModel('tree')->getParents($moduleID);
        }
        elseif($this->cookie->browseType == 'bytree')
        {
            $this->view->tree = $this->doc->getDocTree($libID);
        }
        else
        {
            $this->view->moduleTree = $this->loadModel('tree')->getTreeMenu($libID, $viewType = 'doc', $startModuleID = 0, array('treeModel', 'createDocLink'));
        }

        $this->view->libID         = $libID;
        $this->view->libName       = $this->libs[$libID];
        $this->view->moduleID      = $moduleID;
        $this->view->docs          = $docs;
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->type          = $type;
        $this->view->from          = $from;

        $this->display();
    }

    /**
     * Create a library.
     * 
     * @param  string $type 
     * @param  int    $objectID 
     * @access public
     * @return void
     */
    public function createLib($type = '', $objectID = 0)
    {
        if(!empty($_POST))
        {
            $libID = $this->doc->createLib();
            if(!dao::isError())
            {
                $this->loadModel('action')->create('docLib', $libID, 'Created');
                die(js::locate($this->createLink($this->moduleName, 'browse', "libID=$libID"), 'parent.parent'));
            }
            else
            {
                echo js::error(dao::getError());
            }
        }
        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->users    = $this->user->getPairs('nocode');
        $this->view->products = $this->product->getPairs('nocode');
        $this->view->projects = $this->project->getPairs('nocode');
        $this->view->type     = $type;
        $this->view->objectID = $objectID;
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
            die(js::locate($this->createLink($this->moduleName, 'browse', "libID=$libID"), 'parent.parent'));
        }
        
        $lib = $this->doc->getLibByID($libID);
        if(!empty($lib->product)) $this->view->product = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($lib->product)->fetch();
        if(!empty($lib->project)) $this->view->project = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->eq($lib->project)->fetch();
        $this->view->lib     = $lib;
        $this->view->groups  = $this->loadModel('group')->getPairs();
        $this->view->users   = $this->user->getPairs('nocode');
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
            $lib = $this->doc->getLibByID($libID);
            if(!empty($lib->main)) die(js::alert($this->lang->doc->errorMainSysLib));

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
    public function create($libID, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $docResult = $this->doc->create();
            if(!$docResult or dao::isError()) die(js::error(dao::getError()));

            $docID = $docResult['id'];
            $lib   = $this->doc->getLibByID($this->post->lib);
            if($docResult['status'] == 'exists')
            {
                echo js::alert(sprintf($this->lang->duplicate, $this->lang->doc->common));
                die(js::locate($this->createLink('doc', 'view', "docID=$docID"), 'parent'));
            }
            $this->action->create('doc', $docID, 'Created');

            $vars = "libID=$libID&browseType=byModule&moduleID={$this->post->module}&orderBy=id_desc&from=$this->from";
            $link = $this->createLink('doc', 'browse', $vars);
            die(js::locate($link, 'parent'));
        }

        $lib        = $this->doc->getLibByID($libID);
        $type       = $lib->product ? 'product' : ($lib->project ? 'project' : 'custom');

        /* According the from, set menus. */
        if($this->from == 'product')
        {
            $this->lang->doc->menu      = $this->lang->product->menu;
            $this->lang->doc->menuOrder = $this->lang->product->menuOrder;
            $this->product->setMenu($this->product->getPairs(), $lib->product);
            $this->lang->set('menugroup.doc', 'product');
        }
        elseif($this->from == 'project')
        {
            $this->lang->doc->menu      = $this->lang->project->menu;
            $this->lang->doc->menuOrder = $this->lang->project->menuOrder;
            $this->project->setMenu($this->project->getPairs('nocode'), $lib->project);
            $this->lang->set('menugroup.doc', 'project');
        }
        else
        {
            $this->doc->setMenu($libID, $moduleID);
        }

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->create;

        $this->view->libID            = $libID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->moduleID         = $moduleID;
        $this->view->type             = $type;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode');

        $this->display();
    }

    /**
     * Edit a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function edit($docID, $comment = false)
    {
        if(!empty($_POST))
        {
            if($comment == false)
            {
                $result = $this->doc->update($docID);
                if(dao::isError()) die(js::error(dao::getError()));
                $changes = $result['changes'];
                $files   = $result['files'];
            }
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('doc', $docID, $action, $fileAction . $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('doc', 'view', "docID=$docID"), 'parent'));
        }

        /* Get doc and set menu. */
        $doc = $this->doc->getById($docID);
        $libID = $doc->lib;

        if($doc->contentType == 'markdown') $this->config->doc->markdown->edit = array('id' => 'content', 'tools' => 'toolbar');

        $lib        = $this->doc->getLibByID($libID);
        $type       = $lib->product ? 'product' : ($lib->project ? 'project' : 'custom');
        $this->doc->setMenu($libID, $doc->module);

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->edit;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->edit;

        $this->view->doc              = $doc;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->type             = $type;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode');
        $this->display();
    }

    /**
     * View a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function view($docID, $version = 0)
    {
        /* Get doc. */
        $doc = $this->doc->getById($docID, $version, true);
        if(!$doc) die(js::error($this->lang->notFound) . js::locate('back'));

        if($doc->contentType == 'markdown')
        {
            $hyperdown    = $this->app->loadClass('hyperdown');
            $doc->content = $hyperdown->makeHtml($doc->content);
            $doc->digest  = $hyperdown->makeHtml($doc->digest);
        }

        /* Check priv when lib is product or project. */
        $lib  = $this->doc->getLibByID($doc->lib);
        $type = $lib->product ? 'product' : ($lib->project ? 'project' : 'custom');

        /* Set menu. */
        $this->doc->setMenu($doc->lib, $doc->module);

        $this->view->title      = "DOC #$doc->id $doc->title - " . $lib->name;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$doc->lib"), $lib->name);
        $this->view->position[] = $this->lang->doc->view;

        $this->view->doc        = $doc;
        $this->view->lib        = $lib;
        $this->view->type       = $type;
        $this->view->version    = $version ? $version : $doc->version;
        $this->view->actions    = $this->loadModel('action')->getList('doc', $docID);
        $this->view->users      = $this->user->getPairs('noclosed,noletter');
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('doc', $docID);
        $this->view->keTableCSS = $this->doc->extractKETableCSS($doc->content);

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

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                $this->send($response);
            }
            die(js::locate($this->session->docList, 'parent'));
        }
    }

    /**
     * Sort doc lib.
     * 
     * @access public
     * @return void
     */
    public function sort()
    {
        if($_POST)
        {
            foreach($_POST as $id => $order)
            {
                $this->dao->update(TABLE_DOCLIB)->set('order')->eq($order)->where('id')->eq($id)->exec();
            }
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success'));
        }
    }

    /**
     * Ajax get modules by libID.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function ajaxGetModules($libID)
    {
        $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        die(html::select('module', $moduleOptionMenu, 0, "class='form-control'"));
    }

    /**
     * Ajax fixed menu.
     * 
     * @param  int    $libID 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function ajaxFixedMenu($libID, $type = 'fixed')
    {
        $customMenuKey = $this->config->global->flow . '_doc';
        $customMenus = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=common&section=customMenu&key={$customMenuKey}");
        if($customMenus) $customMenus = json_decode($customMenus);
        if(empty($customMenus))
        {
            if($type == 'remove') die(js::reload('parent'));
            $i = 0;
            foreach($this->lang->doc->menu as $name => $item)
            {
                if($name == 'list') continue;
                $customMenu = new stdclass();
                $customMenu->name = $name; 
                $customMenu->order = $i; 
                $customMenus[] = $customMenu;
                $i++;
            }
        }

        foreach($customMenus as $i => $customMenu)
        {
            if(isset($customMenu->name) and $customMenu->name == "custom{$libID}") unset($customMenus[$i]);
        }

        $lib = $this->doc->getLibByID($libID);
        $customMenu = new stdclass();
        $customMenu->name      = "custom{$libID}";
        $customMenu->order     = count($customMenus); 
        $customMenu->float     = 'right'; 
        if($type == 'fixed') $customMenus[] = $customMenu;
        $this->setting->setItem("{$this->app->user->account}.common.customMenu.{$customMenuKey}", json_encode($customMenus));
        die(js::reload('parent'));
    }

    /**
     * Ajax get all libs 
     * 
     * @access public
     * @return void
     */
    public function ajaxGetAllLibs()
    {
        die(json_encode($this->doc->getAllLibGroups()));
    }

    /**
     * Show all libs by type.
     * 
     * @param  string $type 
     * @param  string $product 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function allLibs($type, $product = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        setcookie('product', $product, $this->config->cookieLife, $this->config->webRoot);

        $libName = isset($this->lang->doc->systemLibs[$type]) ? $this->lang->doc->systemLibs[$type] : $this->lang->doc->customAB;
        $crumb   = html::a(inlink('allLibs', "type=$type&product=$product"), $libName);
        if($product and $type == 'project') $crumb = $this->doc->getProductCrumb($product);

        $this->view->title      = $libName;
        $this->view->position[] = $libName;

        $this->doc->setMenu($libID = 0, $moduleID = 0, $crumb);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $libs    = $this->doc->getAllLibsByType($type, $pager, $product);
        $subLibs = array();
        if($type == 'product' or $type == 'project') $subLibs = $this->doc->getSubLibGroups($type, array_keys($libs));

        $this->view->type    = $type;
        $this->view->libs    = $libs;
        $this->view->subLibs = $subLibs;
        $this->view->pager   = $pager;
        $this->view->product = $product;
        $this->display();
    }

    /**
     * Show files for product or project.
     * 
     * @param  int    $type 
     * @param  int    $objectID 
     * @access public
     * @return void
     */
    public function showFiles($type, $objectID, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',  $uri);
        $this->app->session->set('storyList', $uri);
        $this->app->session->set('docList',   $uri);

        $table  = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
        $object = $this->dao->select('id,name')->from($table)->where('id')->eq($objectID)->fetch();

        /* According the from, set menus. */
        if($this->from == 'product')
        {
            $this->lang->doc->menu      = $this->lang->product->menu;
            $this->lang->doc->menuOrder = $this->lang->product->menuOrder;
            $this->product->setMenu($this->product->getPairs(), $objectID);
            $this->lang->set('menugroup.doc', 'product');
        }
        elseif($this->from == 'project')
        {
            $this->lang->doc->menu      = $this->lang->project->menu;
            $this->lang->doc->menuOrder = $this->lang->project->menuOrder;
            $this->project->setMenu($this->project->getPairs('nocode'), $objectID);
            $this->lang->set('menugroup.doc', 'project');
        }
        else
        {
            $crumb  = html::a(inlink('allLibs', "type=$type"), $type == 'product' ? $this->lang->productCommon : $this->lang->projectCommon) . $this->lang->doc->separator;
            if($this->productID and $type == 'project') $crumb = $this->doc->getProductCrumb($this->productID, $objectID);
            $crumb .= html::a(inlink('objectLibs', "type=$type&objectID=$objectID"), $object->name);
            $crumb .= $this->lang->doc->separator . ' ' . $this->lang->doclib->files;

            if($type == 'product')
            {
                $lib = $this->product->getById($objectID);
                if(!$this->product->checkPriv($lib)) $this->accessDenied();
            }

            if($type == 'project')
            {
                $lib = $this->project->getById($objectID);
                if(!$this->project->checkPriv($lib)) $this->accessDenied();
            }
            
            $this->doc->setMenu($libID = 0, $moduleID = 0, $crumb);
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $object->name;
        $this->view->position[] = $object->name;

        $this->view->type   = $type;
        $this->view->object = $object;
        $this->view->files  = $this->doc->getLibFiles($type, $objectID, $pager);
        $this->view->pager  = $pager;
        $this->display();
    }

    /**
     * Show libs for product or project
     * 
     * @access private
     * @return void
     */
    private function accessDenied()
    {
        echo(js::alert($this->lang->doc->accessDenied));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";

       if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
       die(js::locate('back'));
    }

    /**
     * Show libs for product or project
     * 
     * @param  string $type 
     * @param  int    $objectID 
     * @param  string $from 
     * @access public
     * @return void
     */
    public function objectLibs($type, $objectID, $from = 'doc')
    {
        $this->from = $from;
        setcookie('from',  $from, $this->config->cookieLife, $this->config->webRoot);

        $table  = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
        $object = $this->dao->select('id,name')->from($table)->where('id')->eq($objectID)->fetch();
        if($from == 'product')
        {
            $this->lang->doc->menu      = $this->lang->product->menu;
            $this->lang->doc->menuOrder = $this->lang->product->menuOrder;
            $this->product->setMenu($this->product->getPairs(), $objectID);
            $this->lang->set('menugroup.doc', 'product');
        }
        elseif($from == 'project')
        {
            $this->lang->doc->menu      = $this->lang->project->menu;
            $this->lang->doc->menuOrder = $this->lang->project->menuOrder;
            $this->project->setMenu($this->project->getPairs('nocode'), $objectID);
            $this->lang->set('menugroup.doc', 'project');
        }
        else
        {
            $crumb  = html::a(inlink('allLibs', "type=$type"), $type == 'product' ? $this->lang->productCommon : $this->lang->projectCommon) . $this->lang->doc->separator;
            if($this->productID and $type == 'project') $crumb = $this->doc->getProductCrumb($this->productID, $objectID);
            $crumb .= html::a(inlink('objectLibs', "type=$type&objectID=$objectID"), $object->name);
            $this->doc->setMenu($libID = 0, $moduleID = 0, $crumb);
        }

        $this->view->title      = $object->name;
        $this->view->position[] = $object->name;

        $this->view->type   = $type;
        $this->view->object = $object;
        $this->view->from   = $from;
        $this->view->libs   = $this->doc->getLibsByObject($type, $objectID);
        $this->display();
    }
}
