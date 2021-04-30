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
        $this->loadModel('execution');
    }

    /**
     * Go to browse page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->session->set('docList', $this->app->getURI(true), 'doc');
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 5, 1);

        $actionURL = $this->createLink('doc', 'browse', "lib=0&browseType=bySearch&queryID=myQueryID");
        $this->doc->buildSearchForm(0, array(), 0, $actionURL, 'index');

        $this->view->title      = $this->lang->doc->common . $this->lang->colon . $this->lang->doc->index;
        $this->view->position[] = $this->lang->doc->index;

        $this->view->latestEditedDocs = $this->doc->getDocsByBrowseType('byediteddate', 0, 0, 'editedDate_desc, id_desc', $pager);
        $this->view->myDocs           = $this->doc->getDocsByBrowseType('openedbyme', 0, 0, 'addedDate_desc', $pager);
        $this->view->collectedDocs    = $this->doc->getDocsByBrowseType('collectedbyme', 0, 0, 'addedDate_desc', $pager);
        $this->view->statisticInfo    = $this->doc->getStatisticInfo();
        $this->view->users            = $this->user->getPairs('noletter');

        $this->display();
    }

    /**
     * Browse docs.
     *
     * @param  string|int $libID    product|execution or the int id of custom library
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('docList', $this->app->getURI(true), 'doc');
        $this->loadModel('search');

        /* Set browseType.*/
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;

        /* Set header and position. */
        $this->view->title      = $this->lang->doc->common;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        if($browseType == 'collectedbyme')
        {
            $this->app->rawMethod = 'collect';
        }
        elseif($browseType == 'openedbyme')
        {
            $this->app->rawMethod = 'my';
        }
        elseif($browseType == 'byediteddate')
        {
            $this->app->rawMethod = 'recent';
        }

        $this->view->moduleID   = $moduleID;
        $this->view->docs       = $this->doc->getDocsByBrowseType($browseType, $queryID, $moduleID, $sort, $pager);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->pager      = $pager;

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
            $libID = $this->doc->createlib();
            if(!dao::isError())
            {
                $objectType = $this->post->type;
                if($objectType == 'project' and $this->post->project)     $objectID = $this->post->project;
                if($objectType == 'product' and $this->post->product)     $objectID = $this->post->product;
                if($objectType == 'execution' and $this->post->execution) $objectID = $this->post->execution;
                if($objectType == 'custom' or $objectType == 'book')      $objectID = 0;

                if($objectType == 'execution' and $this->config->systemMode == 'new')
                {
                    $execution  = $this->execution->getByID($this->post->execution);
                    $objectType = 'project';
                    $objectID   = $execution->project;
                }

                $this->action->create('docLib', $libID, 'Created');

                die(js::locate($this->createLink('doc', 'objectLibs', "type=$objectType&objectID=$objectID&libID=$libID"), 'parent.parent'));
            }
            else
            {
                echo js::error(dao::getError());
            }
        }

        $products   = $this->product->getPairs();
        $projects   = $this->project->getPairsByProgram();
        $executions = $this->execution->getPairs();

        $libTypeList = $this->lang->doc->libTypeList;
        if(empty($products))   unset($libTypeList['product']);
        if(empty($projects))   unset($libTypeList['project']);
        if(empty($executions)) unset($libTypeList['execution']);

        $this->view->groups      = $this->loadModel('group')->getPairs();
        $this->view->users       = $this->user->getPairs('nocode');
        $this->view->products    = $products;
        $this->view->projects    = $projects;
        $this->view->executions  = $executions;
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
                $actionID = $this->action->create('docLib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $response['message'] = $this->lang->saveSuccess;
            $response['result']  = 'success';
            $response['locate']  = 'parent';
            $this->send($response);
        }

        $lib = $this->doc->getLibByID($libID);
        if(!empty($lib->product))   $this->view->product   = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($lib->product)->fetch();
        if(!empty($lib->execution)) $this->view->execution = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->eq($lib->execution)->fetch();
        $this->view->lib     = $lib;
        $this->view->groups  = $this->loadModel('group')->getPairs();
        $this->view->users   = $this->user->getPairs('noletter|noclosed', $lib->users);
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
        if($libID == 'product' or $libID == 'execution') die();
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->doc->confirmDeleteLib, $this->createLink('doc', 'deleteLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $lib = $this->doc->getLibByID($libID);
            if(!empty($lib->main)) die(js::alert($this->lang->doc->errorMainSysLib));

            $this->doc->delete(TABLE_DOCLIB, $libID);
            if(isonlybody())
            {
                unset($_GET['onlybody']);
                die(js::locate($this->createLink('doc', 'objectLibs', 'type=book'), 'parent.parent'));
            }

            $browseLink = $this->createLink('doc', 'index');
            if(in_array($this->app->openApp, array('product', 'project', 'execution')))
            {
                $objectType = $lib->type;
                $objectID   = $lib->{$objectType};
                $browseLink = $this->createLink('doc', 'objectLibs', "type=$objectType&objectID=$objectID");
            }

            die(js::locate($browseLink, 'parent'));
        }
    }

    /**
     * Create a doc.
     *
     * @param  string       $objectType
     * @param  int          $objectID
     * @param  int|string   $libID
     * @param  int          $moduleID
     * @param  string       $docType
     * @access public
     * @return void
     */
    public function create($objectType, $objectID, $libID, $moduleID = 0, $docType = '')
    {
        if(!empty($_POST))
        {
            setcookie('lastDocModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $docResult = $this->doc->create();
            if(!$docResult or dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $docID = $docResult['id'];
            $files = zget($docResult, 'files', '');
            $lib   = $this->doc->getLibByID($this->post->lib);
            if($docResult['status'] == 'exists')
            {
                $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->doc->common), 'locate' => $this->createLink('doc', 'view', "docID=$docID")));
            }

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
            $this->action->create('doc', $docID, 'Created', $fileAction);

            $objectID = zget($lib, $lib->type, '');
            $params   = "type={$lib->type}&objectID=$objectID&libID={$lib->id}&docID=" . $docResult['id'];
            $link     = $this->createLink('doc', 'objectLibs', $params);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        if($this->app->openApp == 'product')
        {
            $this->product->setMenu($objectID);
            unset($this->lang->product->menu->doc['subMenu']);
        }
        else if($this->app->openApp == 'project')
        {
            $this->project->setMenu($objectID);
            unset($this->lang->project->menu->doc['subMenu']);
        }
        else if($this->app->openApp == 'execution')
        {
            $this->execution->setMenu($objectID);
            unset($this->lang->execution->menu->doc['subMenu']);
        }
        else
        {
            $this->app->rawMethod = $objectType;
        }

        $lib  = $this->doc->getLibByID($libID);
        $type = $lib->type;

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->create;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->create;

        $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';

        $this->view->libID            = $libID;
        $this->view->libs             = $this->doc->getLibs($objectType, $extra = "withObject,$unclosed", $libID, $objectID);
        $this->view->libName          = $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch('name');
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->type             = $type;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');

        $this->display();
    }

    /**
     * Edit a doc.
     *
     * @param  int    $docID
     * @param  bool   $comment
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function edit($docID, $comment = false, $objectType = '', $objectID = 0, $libID = 0)
    {
        if(!empty($_POST))
        {
            if($comment == false || $comment == 'false')
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

            $link = $this->session->docList ? $this->session->docList : $this->createLink('doc', 'index');
            if(!empty($objectType) and $objectType != 'doc')
            {
                $link = $this->createLink('doc', 'objectLibs', "type=$objectType&objectID=$objectID&libID=$libID&docID=$docID");
            }

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        /* Get doc and set menu. */
        $doc   = $this->doc->getById($docID);
        $libID = $doc->lib;

        if($doc->contentType == 'markdown') $this->config->doc->markdown->edit = array('id' => 'content', 'tools' => 'toolbar');

        $lib  = $this->doc->getLibByID($libID);
        $type = $lib->type;

        /* set menus. */
        if($this->app->openApp == 'product')
        {
            $this->product->setMenu($objectID);
            unset($this->lang->product->menu->doc['subMenu']);
        }
        else if($this->app->openApp == 'project')
        {
            $this->project->setMenu($objectID);
            unset($this->lang->project->menu->doc['subMenu']);
        }
        else if($this->app->openApp == 'execution')
        {
            $this->execution->setMenu($objectID);
            unset($this->lang->execution->menu->doc['subMenu']);
        }
        else if($this->app->openApp == 'my')
        {
            $this->lang->doc->menu     = $this->lang->my->menu->contribute;
            $this->lang->modulePageNav = '';
            $this->lang->TRActions     = '';
            $this->lang->my->menu->contribute['subModule'] = 'doc';
        }
        else
        {
            $this->app->rawMethod = $objectType;

            /* High light menu. */
            if(strpos(',product,project,execution,custom,book,', ",$objectType,") !== false)
            {
                $menu = $this->lang->doc->menu->$objectType;
                $menu['alias']    .= ',edit';
                $menu['subModule'] = 'doc';
                $this->lang->doc->menu->$objectType = $menu;
            }
        }

        $objects = $this->doc->getOrderedObjects($objectType);
        $libs    = $this->doc->getLibsByObject($objectType, $objectID);
        $this->lang->modulePageNav = $this->doc->select($objectType, $objects, $objectID, $libs, $libID);
        $this->lang->TRActions     = common::hasPriv('doc', 'create') ? $this->doc->buildCreateButton4Doc($objectType, $objectID, $libID) : '';

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->edit;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->edit;

        $this->view->doc              = $doc;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->type             = $type;
        $this->view->libs             = $this->doc->getLibs('all', $extra = 'withObject|noBook', $libID, $objectID);
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('noletter|noclosed|nodeleted', $doc->users);
        $this->display();
    }

    /**
     * View a doc.
     *
     * @param  int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function view($docID, $version = 0)
    {
        /* Get doc. */
        $docID = (int)$docID;
        $doc   = $this->doc->getById($docID, $version, true);
        if(!$doc) die(js::error($this->lang->notFound) . js::locate('back'));

        /* The global search opens in the document library. */
        if(!isonlybody())
        {
            $docLib = $this->doc->getLibById($doc->lib);
            if($docLib->type == 'custom')
            {
                $libID    = $doc->lib;
                $objectID = 0;
                $type     = 'custom';
            }
            else
            {
                $libID    = $docLib->id;
                $type     = $docLib->type;
                $objectID = $docLib->$type;
            }

            if($type == 'execution')
            {
                $type     = 'project';
                $objectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($objectID)->fetch('project');
            }

            $browseLink = inLink('objectLibs', "type=$type&objectID=$objectID&libID=$libID&docID=$docID");
            $this->locate($browseLink);
        }

        if($doc->contentType == 'markdown')
        {
            $hyperdown    = $this->app->loadClass('hyperdown');
            $doc->content = $hyperdown->makeHtml($doc->content);
            $doc->digest  = $hyperdown->makeHtml($doc->digest);
        }

        /* Check priv when lib is product or project. */
        $lib  = $this->doc->getLibByID($doc->lib);
        $type = $lib->type;

        $this->view->title      = "DOC #$doc->id $doc->title - " . $lib->name;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$doc->lib"), $lib->name);
        $this->view->position[] = $this->lang->doc->view;

        $this->view->doc        = $doc;
        $this->view->lib        = $lib;
        $this->view->type       = $type;
        $this->view->version    = $version ? $version : $doc->version;
        $this->view->actions    = $this->action->getList('doc', $docID);
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
     * @param  string $from
     * @access public
     * @return void
     */
    public function delete($docID, $confirm = 'no', $from = 'list')
    {
        if($confirm == 'no')
        {
            $type = $this->dao->select('type')->from(TABLE_DOC)->where('id')->eq($docID)->fetch('type');
            $tips = $type == 'chapter' ? $this->lang->doc->confirmDeleteChapter : $this->lang->doc->confirmDelete;
            die(js::confirm($tips, inlink('delete', "docID=$docID&confirm=yes")));
        }
        else
        {
            $doc        = $this->doc->getByID($docID);
            $objectType = $this->dao->select('type')->from(TABLE_DOCLIB)->where('id')->eq($doc->lib)->fetch('type');
            if($this->config->systemMode == 'classic' and $objectType == 'project') $objectType = 'execution';
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

                    if($from == 'lib')
                    {
                        $response['locate']  = $this->createLink('doc', 'objectLibs', "type=$objectType");
                    }
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
            $this->action->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);
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
     * @param  string $type
     * @access public
     * @return void
     */
    public function sort($type = '')
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(empty($_POST)) $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorEmptyLib));
            foreach($_POST as $id => $order) $this->dao->update(TABLE_DOCLIB)->set('order')->eq($order)->where('id')->eq($id)->exec();

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
        $childModules = $this->tree->getOptionMenu($libID, 'doc');
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
        setcookie('product', $product, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $libName = $this->lang->doc->libTypeList[$type];
        $crumb   = html::a(inlink('allLibs', "type=$type&product=$product"), $libName);
        if($product and $type == 'executionCommon') $crumb = $this->doc->getProductCrumb($product);

        $this->view->title      = $libName;
        $this->view->position[] = $libName;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $libs    = $this->doc->getAllLibsByType($type, $pager);
        $subLibs = array();
        if($type == 'product' or $type == 'execution')
        {
            $subLibs = $this->doc->getSubLibGroups($type, array_keys($libs));
            if($this->cookie->browseType == 'bylist') $this->view->users = $this->user->getPairs('noletter');
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
        $this->view->from    = $this->lang->navGroup->doc == 'project' ? 'project' : 'doc';
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
     * Show libs for product or project.
     *
     * @param  string $type
     * @param  int    $objectID  projectID|productID
     * @param  int    $libID
     * @param  int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function objectLibs($type, $objectID = 0, $libID = 0, $docID = 0, $version = 0)
    {
        if(empty($type))
        {
            $doclib   = $this->doc->getLibById($libID);
            $type     = $doclib->type == 'execution' ? 'project' : $doclib->type;
            $objectID = $type == 'custom' or $type == 'book' ? 0 : $doclib->$type;
        }

        $this->session->set('docList', $this->app->getURI(true), $this->app->openApp);

        $objects = $this->doc->getOrderedObjects($type);

        if($type == 'custom')
        {
            $libs = $this->doc->getLibsByObject('custom', 0);
            $this->app->rawMethod = 'custom';
            if($libID == 0) $libID = key($libs);
            $this->lang->modulePageNav = $this->doc->select($type, $objects, $objectID, $libs, $libID);

            $object = new stdclass();
            $object->id = 0;
        }
        elseif($type == 'book')
        {
            $libs = $this->doc->getLibsByObject('book', 0);
            $this->app->rawMethod = 'book';
            if($libID == 0 and !empty($libs)) $libID = reset($libs)->id;
            $this->lang->modulePageNav = $this->doc->select($type, $objects, $objectID, $libs, $libID);

            if(!$docID) $docID = $this->dao->select('id')->from(TABLE_DOC)
                ->where('lib')->eq($libID)
                ->andWhere('type')->eq('article')
                ->andWhere('deleted')->eq(0)
                ->orderBy('editedDate_desc,id_desc')
                ->fetch('id');

            $object = new stdclass();
            $object->id = 0;
        }
        else
        {
            if($type == 'product')
            {
                $objectID = $this->product->saveState($objectID, $objects);
                $table    = TABLE_PRODUCT;

                $libs = $this->doc->getLibsByObject('product', $objectID);

                if($libID == 0) $libID = key($libs);
                $this->lang->modulePageNav = $this->doc->select($type, $objects, $objectID, $libs, $libID);

                $this->app->rawMethod = 'product';
            }
            else if($type == 'project')
            {
                $objectID = $this->project->saveState($objectID, $objects);
                $table    = TABLE_PROJECT;

                $libs = $this->doc->getLibsByObject('project', $objectID);

                if($libID == 0) $libID = key($libs);
                $this->lang->modulePageNav = $this->doc->select($type, $objects, $objectID, $libs, $libID);

                $this->app->rawMethod = 'project';
            }
            else if($type == 'execution')
            {
                $objectID = $this->execution->saveState($objectID, $objects);
                $table = TABLE_EXECUTION;
                $libs  = $this->doc->getLibsByObject('execution', $objectID);

                if($libID == 0) $libID = key($libs);
                $this->lang->modulePageNav = $this->doc->select($type, $objects, $objectID, $libs, $libID);

                $this->app->rawMethod = 'execution';
            }

            $object = $this->dao->select('id,name,status')->from($table)->where('id')->eq($objectID)->fetch();
            if(empty($object)) $this->locate($this->createLink($type, 'create', '', '', '', $this->session->project));
        }

        if(!$libID) $libID = key($libs);

        $openApp = $this->app->openApp;
        if($openApp != 'doc') $this->loadModel($openApp)->setMenu($objectID);

        /* Set Custom. */
        foreach(explode(',', $this->config->doc->customObjectLibs) as $libType) $customObjectLibs[$libType] = $this->lang->doc->customObjectLibs[$libType];

        $actionURL = $this->createLink('doc', 'browse', "lib=0&browseType=bySearch&queryID=myQueryID");
        $this->doc->buildSearchForm(0, array(), 0, $actionURL, 'objectLibs');

        $this->lang->TRActions = common::hasPriv('doc', 'create') ? $this->doc->buildCreateButton4Doc($type, $objectID, $libID) : '';

        $moduleTree = $type == 'book' ? $this->doc->getBookStructure($libID) : $this->doc->getTreeMenu($type, $objectID, $libID, 0, $docID);

        /* Get doc. */
        if($docID)
        {
            $doc = $this->doc->getById($docID, $version, true);
            if(!$doc) die(js::error($this->lang->notFound) . js::locate('back'));

            if($doc->contentType == 'markdown')
            {
                $hyperdown    = $this->app->loadClass('hyperdown');
                $doc->content = $hyperdown->makeHtml($doc->content);
                $doc->digest  = $hyperdown->makeHtml($doc->digest);
            }
        }

        $this->view->customObjectLibs = $customObjectLibs;
        $this->view->showLibs         = $this->config->doc->custom->objectLibs;

        $this->view->title      = ($type == 'book' or $type == 'custom') ? $this->lang->doc->customAB : $object->name;
        $this->view->position[] = ($type == 'book' or $type == 'custom') ? $this->lang->doc->customAB : $object->name;

        $this->view->docID        = $docID;
        $this->view->doc          = $docID ? $doc : '';
        $this->view->type         = $type;
        $this->view->version      = $version;
        $this->view->object       = $object;
        $this->view->objectID     = $objectID;
        $this->view->objectType   = $type;
        $this->view->libID        = $libID;
        $this->view->lib          = isset($libs[$libID]) ? $libs[$libID] : new stdclass();

        $this->view->libs         = $this->doc->getLibsByObject($type, $objectID);
        $this->view->moduleTree   = $moduleTree;
        $this->view->canBeChanged = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->actions      = $docID ? $this->action->getList('doc', $docID) : array();
        $this->view->users        = $this->user->getPairs('noclosed,noletter');
        $this->display();
    }
}
