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
        $this->from = 'doc';
        setcookie('from', 'doc', $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->doc->setMenu();

        $this->session->set('docList', $this->app->getURI(true));
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 5, 1);

        $this->lang->modulePageActions  = $this->doc->setFastMenu($this->lang->doc->fast);
        $this->lang->modulePageActions .= common::hasPriv('doc', 'createLib') ? html::a(helper::createLink('doc', 'createLib'), "<i class='icon icon-plus'></i> " . $this->lang->doc->createLib, '', "class='btn btn-secondary iframe' data-width='70%'") : '';
        $this->lang->modulePageActions .= common::hasPriv('doc', 'create') ? $this->doc->buildCreateButton4Doc() : '';

        $actionURL = $this->createLink('doc', 'browse', "lib=0&browseType=bySearch&queryID=myQueryID");
        $this->doc->buildSearchForm(0, array(), 0, $actionURL, 'index');

        $this->view->title            = $this->lang->doc->common . $this->lang->colon . $this->lang->doc->index;
        $this->view->position[]       = $this->lang->doc->index;
        $this->view->latestEditedDocs = $this->loadModel('doc')->getDocsByBrowseType(0, 'byediteddate', 0, 0, 'editedDate_desc, id_desc', $pager);
        $this->view->myDocs           = $this->loadModel('doc')->getDocsByBrowseType(0, 'openedbyme', 0, 0, 'addedDate_desc', $pager);
        $this->view->statisticInfo    = $this->doc->getStatisticInfo();
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->doingProjects    = $this->loadModel('project')->getList('undone', 5);

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
        setcookie('from', $from, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->loadModel('search');

        /* Set browseType.*/
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;

        $type = '';
        $productID = 0;
        $projectID = 0;
        if($libID)
        {
            $lib       = $this->doc->getLibByID($libID);
            $type      = $lib->type;
            $productID = $lib->product;
            $projectID = $lib->project;

            if($type != 'product' and $type != 'project') $from = 'doc';
        }

        $this->libs = $this->doc->getLibs($type, '', $libID);

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
            $menuType = (!$type && (in_array($browseType, array_keys($this->lang->doc->fastMenuList)) || $browseType == 'bysearch')) ? $browseType : $type;
            $this->doc->setMenu($menuType, $libID, $moduleID, $productID, $projectID);
        }
        $this->session->set('docList', $this->app->getURI(true));

        /* Set header and position. */
        $this->view->title      = $this->lang->doc->common . ($libID ? $this->lang->colon . $this->libs[$libID] : '');
        $this->view->position[] = $libID ? $this->libs[$libID] : '';

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Build the search form. */
        $actionURL = $this->createLink('doc', 'browse', "lib=$libID&browseType=bySearch&queryID=myQueryID&orderBy=$orderBy&from=$from");
        $this->doc->buildSearchForm($libID, $this->libs, $queryID, $actionURL, $type);

        $title   = '';
        $module  = $moduleID ? $this->loadModel('tree')->getByID($moduleID) : '';
        if($module) $title = $module->name;
        if($libID)  $title = html::a(helper::createLink('doc', 'browse', "libID=$libID"), $this->libs[$libID], '');
        if(in_array($browseType, array_keys($this->lang->doc->fastMenuList))) $title = $this->lang->doc->fastMenuList[$browseType];
        if($browseType == 'bysearch') $title = $this->lang->doc->search;
        if($param != 0) $title = $this->doc->buildCrumbTitle($libID, $param, $title);
        if($browseType == 'fastsearch')
        {
            if($this->post->searchDoc) $this->session->set('searchDoc', $this->post->searchDoc);
            $title = '"' . $this->session->searchDoc  . '" ' . $this->lang->doc->searchResult;
        }
        else
        {
            $this->session->set('searchDoc', '');
        }

        $libs = array();
        if($browseType == 'collectedbyme')
        {
            $libs = $this->doc->getAllLibsByType('collector');
            $this->view->itemCounts = $this->doc->statLibCounts(array_keys($libs));
        }

        $attachLibs = array();
        if(!empty($lib) and (!empty($lib->product) or !empty($lib->project)) and $browseType != 'bymodule')
        {
            $count = $this->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('project')->eq($lib->project)->andWhere('product')->eq($lib->product)->fetch('count');
            if($count == 1 and $type and isset($lib->$type))
            {
                $objectLibs = $this->doc->getLibsByObject($type, $lib->$type);
                if(isset($objectLibs['project'])) $attachLibs['project'] = $objectLibs['project'];
                if(isset($objectLibs['files']))   $attachLibs['files']   = $objectLibs['files'];
            }
        }

        $this->view->breadTitle = $title;
        $this->view->libID      = $libID;
        $this->view->moduleID   = $moduleID;
        $this->view->modules    = $this->doc->getDocMenu($libID, $moduleID, '`order`', $browseType);
        $this->view->docs       = $this->doc->getDocsByBrowseType($libID, $browseType, $queryID, $moduleID, $sort, $pager);
        $this->view->attachLibs = $attachLibs;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->type       = $type;
        $this->view->from       = $from;
        $this->view->pager      = $pager;
        $this->view->libs       = $libs;
        $this->view->currentLib = $libID ? $lib : '';

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
        $libTypeList = $this->lang->doc->libTypeList;
        $products    = $this->product->getPairs('nocode');
        $projects    = $this->project->getPairs('nocode');
        if(empty($products)) unset($libTypeList['product']);
        if(empty($projects)) unset($libTypeList['project']);

        $this->view->groups      = $this->loadModel('group')->getPairs();
        $this->view->users       = $this->user->getPairs('nocode');
        $this->view->products    = $products;
        $this->view->projects    = $projects;
        $this->view->type        = $type;
        $this->view->libTypeList = $libTypeList;
        $this->view->objectID    = $objectID;
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
        $this->view->users   = $this->user->getPairs('noletter', $lib->users);
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
    public function create($libID, $moduleID = 0, $docType = '')
    {
        if(!empty($_POST))
        {
            setcookie('lastDocModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', false, false);
            $docResult = $this->doc->create();
            if(!$docResult or dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $docID = $docResult['id'];
            $files = $docResult['files'];
            $lib   = $this->doc->getLibByID($this->post->lib);
            if($docResult['status'] == 'exists')
            {
                $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->doc->common), 'locate' => $this->createLink('doc', 'view', "docID=$docID")));
            }

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
            $this->action->create('doc', $docID, 'Created', $fileAction);

            $vars = "libID={$this->post->lib}&browseType=byModule&moduleID={$this->post->module}&orderBy=id_desc&from=$this->from";
            $link = $this->createLink('doc', 'browse', $vars);
            if($this->app->getViewType() == 'xhtml') $link = $this->createLink('doc', 'view', "docID=$docID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $lib  = $this->doc->getLibByID($libID);
        $type = $lib->type;

        /* According the from, set menus. */
        if($this->from == 'product')
        {
            $this->lang->doc->menu      = $this->lang->product->menu;
            $this->lang->doc->menuOrder = $this->lang->product->menuOrder;
            $this->product->setMenu($this->product->getPairs(), $lib->product);
            $this->lang->set('menugroup.doc', 'product');

            $this->lang->modulePageActions = common::hasPriv('doc', 'createLib') ? html::a(helper::createLink('doc', 'createLib'), "<i class='icon icon-plus'></i> " . $this->lang->doc->createLib, '', "class='btn btn-secondary iframe' data-width='70%'") : '';
        }
        elseif($this->from == 'project')
        {
            $this->lang->doc->menu      = $this->lang->project->menu;
            $this->lang->doc->menuOrder = $this->lang->project->menuOrder;
            $this->project->setMenu($this->project->getPairs('nocode'), $lib->project);
            $this->lang->set('menugroup.doc', 'project');

            $this->lang->modulePageActions = common::hasPriv('doc', 'createLib') ? html::a(helper::createLink('doc', 'createLib'), "<i class='icon icon-plus'></i> " . $this->lang->doc->createLib, '', "class='btn btn-secondary iframe' data-width='70%'") : '';
        }
        else
        {
            $this->doc->setMenu($type, $libID, $moduleID, $lib->product, $lib->project);
        }

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->create;

        $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';

        $this->view->libID            = $libID;
        $this->view->libs             = $this->doc->getLibs($type = 'all', $extra = "withObject,$unclosed", $libID);
        $this->view->libName          = $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch('name');
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->type             = $type;
        $this->view->docType          = $docType;
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
                if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
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
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('doc', 'view', "docID=$docID")));
        }

        /* Get doc and set menu. */
        $doc = $this->doc->getById($docID);
        $libID = $doc->lib;

        if($doc->contentType == 'markdown') $this->config->doc->markdown->edit = array('id' => 'content', 'tools' => 'toolbar');

        $lib  = $this->doc->getLibByID($libID);
        $type = $lib->type;
        $this->doc->setMenu($type, $libID, $doc->module, $lib->product, $lib->project);

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->edit;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->edit;

        $this->view->doc              = $doc;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->type             = $type;
        $this->view->libs             = $this->doc->getLibs($type = 'all', $extra = 'withObject');
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('noletter', $doc->users);
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
        $type = $lib->type;

        /* Set menu. */
        $this->doc->setMenu($type, $doc->lib, $doc->module, $lib->product, $lib->project);

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
     * Delete file for doc.
     * 
     * @param  int    $docID 
     * @param  int    $fileID 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function deleteFile($docID, $fileID, $confirm = 'no')
    {
        $this->loadModel('file');
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->file->confirmDelete, inlink('deleteFile', "docID=$docID&fileID=$fileID&confirm=yes")));
        }
        else
        {
            $docContent = $this->dao->select('t1.*')->from(TABLE_DOCCONTENT)->alias('t1')
                ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.doc=t2.id and t1.version=t2.version')
                ->where('t2.id')->eq($docID)
                ->fetch();
            unset($docContent->id);
            $docContent->files    = trim(str_replace(",{$fileID},", ',', ",{$docContent->files},"), ',');
            $docContent->version += 1;
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($docContent->version)->where('id')->eq($docID)->exec();

            $file = $this->file->getById($fileID);
            $this->loadModel('action')->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);
            die(js::locate($this->createLink('doc', 'view', "docID=$docID"), 'parent'));
        }
    }

    /**
     * Collect doc, doclib or module of doclib.
     *
     * @param  int    $objectID
     * @param  int    $objectType
     * @access public
     * @return void
     */
    public function collect($objectID, $objectType)
    {
        if($objectType == 'doc')    $table = TABLE_DOC;
        if($objectType == 'doclib') $table = TABLE_DOCLIB;
        if($objectType == 'module') $table = TABLE_MODULE;
        $collectors = $this->dao->select('collector')->from($table)->where('id')->eq($objectID)->fetch('collector');

        $hasCollect = strpos($collectors, ",{$this->app->user->account},") !== false;
        if($hasCollect)
        {
            $collectors = str_replace(",{$this->app->user->account},", ',', $collectors);
        }
        else
        {
            $collectors = explode(',', $collectors);
            $collectors[] = $this->app->user->account;
            $collectors = implode(',', $collectors);
        }

        $collectors = trim($collectors, ',') ? ',' . trim($collectors, ',') . ',' : '';

        $this->dao->update($table)->set('collector')->eq($collectors)->where('id')->eq($objectID)->exec();

        $this->send(array('status' => $hasCollect ? 'no' : 'yes'));
    }

    /**
     * Sort doc lib.
     *
     * @access public
     * @return void
     */
    public function sort($type = '')
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

        if($type)
        {
            $this->view->libs = $this->doc->getLibs($type);
            $this->display();
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
            $customMenus = array();
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

        $customMenus = (array)$customMenus;
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
     * Ajax get all child module. 
     *
     * @access public
     * @return void
     */
    public function ajaxGetChild($libID, $type = 'module')
    {
        $childModules = $this->loadModel('tree')->getOptionMenu($libID, 'doc');
        $select = ($type == 'module') ? html::select('module', $childModules, '', "class='form-control chosen'") : html::select('parent', $childModules, '', "class='form-control chosen'");
        die($select);
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
        setcookie('product', $product, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $libName = $this->lang->doc->libTypeList[$type];
        $crumb   = html::a(inlink('allLibs', "type=$type&product=$product"), $libName);
        if($product and $type == 'project') $crumb = $this->doc->getProductCrumb($product);

        $this->view->title      = $libName;
        $this->view->position[] = $libName;

        $this->doc->setMenu($type, $libID = 0, $moduleID = 0, $productID = 0, $projectID = 0, $crumb);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $libs    = $this->doc->getAllLibsByType($type, $pager, $product);
        $subLibs = array();
        if($type == 'product' or $type == 'project')
        {
            $subLibs = $this->doc->getSubLibGroups($type, array_keys($libs));
            if($this->cookie->browseType == 'bylist') $this->view->users = $this->loadModel('user')->getPairs('noletter');
        }
        else
        {
            $this->view->itemCounts = $this->doc->statLibCounts(array_keys($libs));
        }

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
    public function showFiles($type, $objectID, $viewType = '', $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',  $uri);
        $this->app->session->set('storyList', $uri);
        $this->app->session->set('docList',   $uri);

        if(empty($viewType)) $viewType = !empty($_COOKIE['docFilesViewType']) ? $this->cookie->docFilesViewType : 'card';
        setcookie('docFilesViewType', $viewType, $this->config->cookieLife, $this->config->webRoot, '', false, true);

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

            $productID = 0;
            $projectID = 0;
            if($type == 'product')
            {
                $productID = $objectID;
                if(!$this->product->checkPriv($objectID)) $this->accessDenied();
            }

            if($type == 'project')
            {
                $projectID = $objectID;
                if(!$this->project->checkPriv($objectID)) $this->accessDenied();
            }

            $this->doc->setMenu($type, $libID = 0, $moduleID = 0, $productID, $projectID, $crumb);
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $object->name;
        $this->view->position[] = $object->name;

        $this->view->type       = $type;
        $this->view->object     = $object;
        $this->view->files      = $this->doc->getLibFiles($type, $objectID, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;
        $this->view->viewType   = $viewType;
        $this->view->orderBy    = $orderBy;
        $this->view->objectID   = $objectID;

        $this->display();
    }

    /**
     * Show accessDenied response.
     *
     * @access private
     * @return void
     */
    private function accessDenied()
    {
        echo(js::alert($this->lang->doc->accessDenied));

        if(!$this->server->http_referer) die(js::locate(inlink('index')));

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
        setcookie('from', $from, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $table  = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
        $object = $this->dao->select('id,name')->from($table)->where('id')->eq($objectID)->fetch();
        if(empty($object)) $this->locate($this->createLink($type, 'create'));
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
            $productID = $type == 'product' ? $objectID : 0;
            $projectID = $type == 'project' ? $objectID : 0;
            $this->doc->setMenu($type, $libID = 0, $moduleID = 0, $productID, $projectID, $crumb);
        }

        /* Set Custom. */
        foreach(explode(',', $this->config->doc->customObjectLibs) as $libType) $customObjectLibs[$libType] = $this->lang->doc->customObjectLibs[$libType];

        $actionURL = $this->createLink('doc', 'browse', "lib=0&browseType=bySearch&queryID=myQueryID");
        $this->doc->buildSearchForm(0, array(), 0, $actionURL, 'objectLibs');

        $this->view->customObjectLibs = $customObjectLibs;
        $this->view->showLibs         = $this->config->doc->custom->objectLibs;

        $this->view->title      = $object->name;
        $this->view->position[] = $object->name;

        $this->view->type   = $type;
        $this->view->object = $object;
        $this->view->from   = $from;
        $this->view->libs   = $this->doc->getLibsByObject($type, $objectID);
        $this->display();
    }
}
