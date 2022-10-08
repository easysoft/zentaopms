<?php
/**
 * The control file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @param string|int $libID product|execution or the int id of custom library
     * @param string $browseType
     * @param int $param
     * @param string $orderBy
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session, load module. */
        $uri = $this->app->getURI(true);
        $this->session->set('docList', $uri, 'doc');
        $this->session->set('productList', $uri, 'product');
        $this->session->set('executionList', $uri, 'execution');
        $this->session->set('projectList', $uri, 'project');
        $this->loadModel('search');

        /* Set browseType.*/
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;

        /* Set header and position. */
        $this->view->title = $this->lang->doc->common;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

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
     * @param string $type
     * @param int $objectID
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
                if($objectType == 'project' and $this->post->project) $objectID = $this->post->project;
                if($objectType == 'product' and $this->post->product) $objectID = $this->post->product;
                if($objectType == 'execution' and $this->post->execution) $objectID = $this->post->execution;
                if($objectType == 'custom' or $objectType == 'book') $objectID = 0;

                $this->action->create('docLib', $libID, 'Created');

                if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $libID));
                return print(js::locate($this->createLink('doc', 'objectLibs', "type=$objectType&objectID=$objectID&libID=$libID"), 'parent.parent'));
            }
            else
            {
                echo js::error(dao::getError());
            }
        }

        $products   = $this->product->getPairs();
        $projects   = $this->project->getPairsByProgram('', 'all', true);
        $executions = $this->execution->getList();

        /* Splice project name. */
        foreach($executions as $executionID => $execution)
        {
            $executionPrefix          = isset($projects[$execution->project]) ? $projects[$execution->project] . '/' : '';
            $executions[$executionID] = $executionPrefix . $execution->name;
        }

        /* Get the project that has permission to view. */
        foreach($projects as $projectID => $project) if(!$this->app->user->admin and strpos(',' . $this->app->user->view->projects . ',', ',' . $projectID . ',') === false) unset($projects[$projectID]);

        if($type == 'execution')
        {
            $execution = $this->execution->getByID($objectID);
            if($execution->type == 'stage') $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);
            if($execution->type == 'stage') $this->lang->doc->libTypeList['execution'] = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->libTypeList['execution']);
        }

        $libTypeList = $this->lang->doc->libTypeList;
        if(empty($products)) unset($libTypeList['product']);
        if(empty($projects)) unset($libTypeList['project']);
        if(empty($executions)) unset($libTypeList['execution']);

        $this->app->loadLang('api');

        $this->view->groups      = $this->loadModel('group')->getPairs();
        $this->view->users       = $this->user->getPairs('nocode|noclosed');
        $this->view->products    = $products;
        $this->view->projects    = $projects;
        $this->view->executions  = $executions;
        $this->view->type        = $type;
        $this->view->libTypeList = $libTypeList;
        $this->view->objectID    = $objectID;
        $this->display();
    }

    /**
     * Edit a library.
     *
     * @param int $libID
     * @access public
     * @return void
     */
    public function editLib($libID)
    {
        if(!empty($_POST))
        {
            $changes = $this->doc->updateLib($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->action->create('docLib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $docLib   = $this->doc->getLibById($libID);
            $objectID = 0;
            if(strpos('product,project,execution', $docLib->type) !== false)
            {
                $libType  = $docLib->type;
                $objectID = $docLib->$libType;
            }
            $hasLibPriv = $this->doc->checkPrivLib($docLib) ? 1 : 0;

            $response['message']    = $this->lang->saveSuccess;
            $response['result']     = 'success';
            $response['closeModal'] = true;
            $response['callback']   = "redirectParentWindow($hasLibPriv, $libID, $objectID)";
            return $this->send($response);
        }

        $lib = $this->doc->getLibByID($libID);
        if(!empty($lib->product)) $this->view->product = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($lib->product)->fetch();
        if(!empty($lib->execution))
        {
            $execution = $this->execution->getByID($lib->execution);
            if($execution->type == 'stage') $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);

            $this->view->execution = $execution;
        }
        $this->view->lib    = $lib;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->user->getPairs('noletter|noclosed', $lib->users);
        $this->view->libID  = $libID;

        $this->display();
    }

    /**
     * Delete a library.
     *
     * @param int $libID
     * @param string $confirm yes|no
     * @param string $type    lib|book
     * @param string $from    tableContents|objectLibs
     * @access public
     * @return void
     */
    public function deleteLib($libID, $confirm = 'no', $type = 'lib', $from = 'objectLibs')
    {
        if($libID == 'product' or $libID == 'execution') return;
        if($confirm == 'no')
        {
            $deleteTip = $type == 'book' ? $this->lang->doc->confirmDeleteBook : $this->lang->doc->confirmDeleteLib;
            return print(js::confirm($deleteTip, $this->createLink('doc', 'deleteLib', "libID=$libID&confirm=yes&type=$lib&from=$from")));
        }
        else
        {
            $lib = $this->doc->getLibByID($libID);
            if(!empty($lib->main)) return print(js::alert($this->lang->doc->errorMainSysLib));

            $this->doc->delete(TABLE_DOCLIB, $libID);
            if(isonlybody())
            {
                unset($_GET['onlybody']);
                return print(js::locate($this->createLink('doc', 'objectLibs', 'type=book'), 'parent.parent'));
            }

            $objectType = $lib->type;
            $objectID   = strpos(',product,project,execution,', ",$objectType,") !== false ? $lib->{$objectType} : 0;
            $browseLink = $this->createLink('doc', $from, "type=$objectType&objectID=$objectID");

            return print(js::locate($browseLink, 'parent'));
        }
    }

    /**
     * Create a doc.
     *
     * @param  string     $objectType
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType
     * @param  bool       $fromGlobal
     * @param  string     $from
     * @access public
     * @return void
     */
    public function create($objectType, $objectID, $libID, $moduleID = 0, $docType = '', $fromGlobal = false, $from = 'doc')
    {
        if(!empty($_POST))
        {
            setcookie('lastDocModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $docResult = $this->doc->create();
            if(!$docResult or dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $docID = $docResult['id'];
            $files = zget($docResult, 'files', '');
            $lib   = $this->doc->getLibByID($this->post->lib);
            if($docResult['status'] == 'exists')
            {
                return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->doc->common), 'locate' => $this->createLink('doc', 'view', "docID=$docID")));
            }

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
            $this->action->create('doc', $docID, 'Created', $fileAction);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $docID));
            $objectID = zget($lib, $lib->type, 0);
            $params   = "type={$lib->type}&objectID=$objectID&libID={$lib->id}&docID=" . $docResult['id'];
            $link     = isonlybody() ? 'parent' : $this->createLink('doc', 'objectLibs', $params) . '#app=' . $this->app->tab;
            $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link);

            return $this->send($response);
        }

        unset($_GET['onlybody']);

        if($this->app->tab == 'product')
        {
            $this->product->setMenu($objectID);
            unset($this->lang->product->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'project')
        {
            $this->project->setMenu($objectID);
            unset($this->lang->project->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'execution')
        {
            $this->execution->setMenu($objectID);
            unset($this->lang->execution->menu->doc['subMenu']);
        }
        else
        {
            $this->app->rawMethod = $objectType;
            unset($this->lang->doc->menu->product['subMenu']);
            unset($this->lang->doc->menu->custom['subMenu']);
            unset($this->lang->doc->menu->execution['subMenu']);
            if($this->config->systemMode == 'new') unset($this->lang->doc->menu->project['subMenu']);
        }

        $this->config->showMainMenu = (strpos($this->config->doc->textTypes, $docType) === false or $from == 'template');

        /* Get libs and the default lib id. */
        $gobackLink = ($objectID == 0 and $libID == 0) ? $this->createLink('doc', 'tableContents', "type=$objectType") : '';
        $unclosed   = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs       = $this->doc->getLibs($objectType, $extra = "withObject,$unclosed", $libID, $objectID);
        if(!$libID and !empty($libs)) $libID = key($libs);

        $lib     = $this->doc->getLibByID($libID);
        $type    = isset($lib->type) ? $lib->type : 'product';
        $libName = isset($lib->name) ? $lib->name . $this->lang->colon : '';

        $this->view->title = $libName . $this->lang->doc->create;

        $this->view->objectType       = $objectType;
        $this->view->objectID         = zget($lib, $lib->type, 0);
        $this->view->libID            = $libID;
        $this->view->lib              = $lib;
        $this->view->libs             = $libs;
        $this->view->gobackLink       = $gobackLink;
        $this->view->libName          = $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch('name');
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');
        $this->view->fromGlobal       = $fromGlobal;
        $this->view->from             = $from;

        $this->display();
    }

    /**
     * Create basic info for doc of text type.
     *
     * @param  string     $objectType
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType
     * @access public
     * @return void
     */
    public function createBasicInfo($objectType, $objectID, $libID, $moduleID = 0, $docType = '')
    {
        /* Get libs and the default lib id. */
        $unclosed   = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs       = $this->doc->getLibs($objectType, $extra = "withObject,$unclosed", $libID, $objectID);
        if(!$libID and !empty($libs)) $libID = key($libs);

        $lib     = $this->doc->getLibByID($libID);
        $type    = isset($lib->type) ? $lib->type : 'product';
        $libName = isset($lib->name) ? $lib->name . $this->lang->colon : '';

        $this->view->title = $libName . $this->lang->doc->create;

        $this->view->objectType       = $objectType;
        $this->view->objectID         = zget($lib, $lib->type, 0);
        $this->view->libID            = $libID;
        $this->view->lib              = $lib;
        $this->view->libs             = $libs;
        $this->view->libName          = $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch('name');
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');

        $this->display();
    }

    /**
     * Edit a doc.
     *
     * @param  int     $docID
     * @param  bool    $comment
     * @param  string  $objectType
     * @param  int     $objectID
     * @param  int     $libID
     * @param  string  $from
     * @access public
     * @return void
     */
    public function edit($docID, $comment = false, $objectType = '', $objectID = 0, $libID = 0, $from = 'edit')
    {
        if(!empty($_POST))
        {
            if($comment == false || $comment == 'false')
            {
                $result = $this->doc->update($docID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $changes = $result['changes'];
                $files   = $result['files'];
            }
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action     = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
                $actionID = $this->action->create('doc', $docID, $action, $fileAction . $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $link     = $this->session->docList ? $this->session->docList : $this->createLink('doc', 'index');
            $doc      = $this->doc->getById($docID);
            $lib      = $this->doc->getLibById($doc->lib);
            $objectID = zget($lib, $lib->type, 0);

            if(!empty($objectType) and $objectType != 'doc' and $doc->type != 'chapter' and $doc->type != 'article')
            {
                $link = $this->createLink('doc', 'objectLibs', "type={$lib->type}&objectID=$objectID&libID={$doc->lib}&docID=$docID") . "#app={$this->app->tab}";
            }

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        /* Get doc and set menu. */
        $doc   = $this->doc->getById($docID);
        $libID = $doc->lib;

        if($doc->contentType == 'markdown') $this->config->doc->markdown->edit = array('id' => 'content', 'tools' => 'toolbar');

        $lib  = $this->doc->getLibByID($libID);
        $type = $lib->type;

        /* Set menus. */
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($objectID);
            unset($this->lang->product->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'project')
        {
            $this->project->setMenu($objectID);
            unset($this->lang->project->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'execution')
        {
            $this->execution->setMenu($objectID);
            unset($this->lang->execution->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'my')
        {
            $this->lang->doc->menu                         = $this->lang->my->menu->contribute;
            $this->lang->modulePageNav                     = '';
            $this->lang->TRActions                         = '';
            $this->lang->my->menu->contribute['subModule'] = 'doc';
        }
        else
        {
            $this->app->rawMethod = $objectType;

            unset($this->lang->doc->menu->product['subMenu']);
            unset($this->lang->doc->menu->custom['subMenu']);
            if($this->config->systemMode == 'new') unset($this->lang->doc->menu->project['subMenu']);
            if($this->config->systemMode == 'classic') unset($this->lang->doc->menu->execution['subMenu']);

            /* High light menu. */
            if(strpos(',product,project,execution,custom,book,', ",$objectType,") !== false)
            {
                $menu                               = $this->lang->doc->menu->$objectType;
                $menu['alias']                      .= ',edit';
                $menu['subModule']                  = 'doc';
                $this->lang->doc->menu->$objectType = $menu;
            }
        }

        $this->config->showMainMenu = strpos(',html,markdown,text,', ",{$doc->type},") === false;

        $objects                   = $this->doc->getOrderedObjects($objectType);
        $appendLib                 = (!empty($lib) and $lib->deleted == '1') ? $libID : 0;
        $libs                      = $this->doc->getLibsByObject($objectType, $objectID, '', $appendLib);
        $this->lang->modulePageNav = $this->doc->select($objectType, $objects, $objectID, $libs, $libID);
        $this->lang->TRActions     = common::hasPriv('doc', 'create') ? $this->doc->buildCreateButton4Doc($objectType, $objectID, $libID) : '';

        $this->view->title      = $lib->name . $this->lang->colon . $this->lang->doc->edit;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$libID"), $lib->name);
        $this->view->position[] = $this->lang->doc->edit;

        $this->view->doc              = $doc;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->type             = $type;
        $this->view->libs             = $this->doc->getLibs('all', $extra = 'withObject|noBook', $libID, $objectID);
        $this->view->lib              = $lib;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('noletter|noclosed|nodeleted', $doc->users);
        $this->view->from             = $from;
        $this->view->files            = $this->loadModel('file')->getByObject('doc', $docID);
        $this->view->objectID         = zget($lib, $type, 0);
        $this->display();
    }

    /**
     * View a doc.
     *
     * @param int $docID
     * @param int $version
     * @access public
     * @return void
     */
    public function view($docID, $version = 0)
    {
        /* Get doc. */
        $docID = (int)$docID;
        $doc   = $this->doc->getById($docID, $version, true);
        if(!$doc)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('doc', 'index')));
        }

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

            $browseLink = inLink('objectLibs', "type=$type&objectID=$objectID&libID=$libID&docID=$docID");
            if(!(defined('RUN_MODE') && RUN_MODE == 'api')) $this->locate($browseLink);
        }

        /* Check priv when lib is product or project. */
        $lib  = $this->doc->getLibByID($doc->lib);
        $type = $lib->type;

        $this->view->title      = "DOC #$doc->id $doc->title - " . $lib->name;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$doc->lib"), $lib->name);
        $this->view->position[] = $this->lang->doc->view;

        $this->view->doc          = $doc;
        $this->view->lib          = $lib;
        $this->view->type         = $type;
        $this->view->version      = $version ? $version : $doc->version;
        $this->view->actions      = $this->action->getList('doc', $docID);
        $this->view->users        = $this->user->getPairs('noclosed,noletter');
        $this->view->preAndNext   = $this->loadModel('common')->getPreAndNextObject('doc', $docID);
        $this->view->keTableCSS   = $this->doc->extractKETableCSS($doc->content);
        $this->view->autoloadPage = $this->doc->checkAutoloadPage($doc);

        $this->display();
    }

    /**
     * Delete a doc.
     *
     * @param int $docID
     * @param string $confirm yes|no
     * @param string $from
     * @access public
     * @return void
     */
    public function delete($docID, $confirm = 'no', $from = 'list')
    {
        if($confirm == 'no')
        {
            $type = $this->dao->select('type')->from(TABLE_DOC)->where('id')->eq($docID)->fetch('type');
            $tips = $type == 'chapter' ? $this->lang->doc->confirmDeleteChapter : $this->lang->doc->confirmDelete;
            return print(js::confirm($tips, inlink('delete', "docID=$docID&confirm=yes")));
        }
        else
        {
            $doc        = $this->doc->getByID($docID);
            $objectType = $this->dao->select('type')->from(TABLE_DOCLIB)->where('id')->eq($doc->lib)->fetch('type');
            if($this->config->systemMode == 'classic' and $objectType == 'project') $objectType = 'execution';
            $this->doc->delete(TABLE_DOC, $docID);

            /* Delete doc files. */
            $this->loadModel('file');
            foreach($doc->files as $fileID => $file)
            {
                $file = $this->file->getById($fileID);
                $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($fileID)->exec();
                $this->loadModel('action')->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);

                $fileRecord = $this->dao->select('id')->from(TABLE_FILE)->where('pathname')->eq($file->pathname)->fetch();
                if(empty($fileRecord)) @unlink($file->realPath);
            }

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
                        $objectID = $objectType == 'project' ? $doc->project : $doc->product;
                        $response['locate'] = $this->createLink('doc', 'objectLibs', "type=$objectType&objectID={$objectID}&libID={$doc->lib}");
                    }
                }
                return $this->send($response);
            }

            return print(js::locate($this->session->docList, 'parent'));
        }
    }

    /**
     * Delete file for doc.
     *
     * @param int $docID
     * @param int $fileID
     * @param string $confirm
     * @access public
     * @return void
     */
    public function deleteFile($docID, $fileID, $confirm = 'no')
    {
        $this->loadModel('file');
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->file->confirmDelete, inlink('deleteFile', "docID=$docID&fileID=$fileID&confirm=yes")));
        }
        else
        {
            $docContent = $this->dao->select('t1.*')->from(TABLE_DOCCONTENT)->alias('t1')
                                    ->leftJoin(TABLE_DOC)->alias('t2')->on('t1.doc=t2.id and t1.version=t2.version')
                                    ->where('t2.id')->eq($docID)
                                    ->fetch();
            unset($docContent->id);
            $docContent->files   = trim(str_replace(",{$fileID},", ',', ",{$docContent->files},"), ',');
            $docContent->version += 1;
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($docContent->version)->where('id')->eq($docID)->exec();

            $file = $this->file->getById($fileID);
            $this->action->create($file->objectType, $file->objectID, 'deletedFile', '', $extra = $file->title);
            return print(js::locate($this->createLink('doc', 'view', "docID=$docID"), 'parent'));
        }
    }

    /**
     * Collect doc, doclib or module of doclib.
     *
     * @param int $objectID
     * @param int $objectType
     * @access public
     * @return void
     */
    public function collect($objectID, $objectType)
    {
        if($objectType == 'doc') $table = TABLE_DOC;
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
            $collectors   = explode(',', $collectors);
            $collectors[] = $this->app->user->account;
            $collectors   = implode(',', $collectors);
        }

        $collectors = trim($collectors, ',') ? ',' . trim($collectors, ',') . ',' : '';

        $this->dao->update($table)->set('collector')->eq($collectors)->where('id')->eq($objectID)->exec();

        return $this->send(array('status' => $hasCollect ? 'no' : 'yes'));
    }

    /**
     * Sort doc lib.
     *
     * @param string $type
     * @access protected
     * @return void
     */
    protected function sort($type = '')
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(empty($_POST)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorEmptyLib));
            foreach($_POST as $id => $order) $this->dao->update(TABLE_DOCLIB)->set('order')->eq($order)->where('id')->eq($id)->exec();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }

        if($type)
        {
            $this->view->libs = $this->doc->getLibs($type);
            $this->display();
        }
    }

    /**
     * Update order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            foreach($_POST['orders'] as $id => $order)
            {
                $this->dao->update(TABLE_DOC)->set('`order`')->eq($order)->where('id')->eq($id)->exec();
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * Ajax get modules by libID.
     *
     * @param int $libID
     * @access public
     * @return void
     */
    public function ajaxGetModules($libID)
    {
        $moduleOptionMenu = $this->tree->getOptionMenu($libID, 'doc', $startModuleID = 0);
        return print(html::select('module', $moduleOptionMenu, 0, "class='form-control'"));
    }

    /**
     * Ajax fixed menu.
     *
     * @param int $libID
     * @param string $type
     * @access public
     * @return void
     */
    public function ajaxFixedMenu($libID, $type = 'fixed')
    {
        $customMenuKey = $this->config->global->flow . '_doc';
        $customMenus   = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=common&section=customMenu&key={$customMenuKey}");
        if($customMenus) $customMenus = json_decode($customMenus);
        if(empty($customMenus))
        {
            if($type == 'remove') return print(js::reload('parent'));
            $customMenus = array();
            $i           = 0;
            foreach($this->lang->doc->menu as $name => $item)
            {
                if($name == 'list') continue;
                $customMenu        = new stdclass();
                $customMenu->name  = $name;
                $customMenu->order = $i;
                $customMenus[]     = $customMenu;
                $i++;
            }
        }

        $customMenus = (array)$customMenus;
        foreach($customMenus as $i => $customMenu)
        {
            if(isset($customMenu->name) and $customMenu->name == "custom{$libID}") unset($customMenus[$i]);
        }

        $lib               = $this->doc->getLibByID($libID);
        $customMenu        = new stdclass();
        $customMenu->name  = "custom{$libID}";
        $customMenu->order = count($customMenus);
        $customMenu->float = 'right';
        if($type == 'fixed') $customMenus[] = $customMenu;
        $this->setting->setItem("{$this->app->user->account}.common.customMenu.{$customMenuKey}", json_encode($customMenus));
        return print(js::reload('parent'));
    }

    /**
     * Ajax get all libs
     *
     * @access public
     * @return void
     */
    public function ajaxGetAllLibs()
    {
        return print(json_encode($this->doc->getAllLibGroups()));
    }

    /**
     * AJAX: Get libs by type.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetLibsByType($type)
    {
        $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs = $this->doc->getLibs($type, "withObject,$unclosed");

        return print(html::select('lib', $libs, '', 'class="form-control"'));
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
        $select       = ($type == 'module') ? html::select('module', $childModules, '', "class='form-control chosen'") : html::select('parent', $childModules, '', "class='form-control chosen'");
        return print($select);
    }

    /**
     * Ajax get docs by lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function ajaxGetDocs($libID)
    {
        if(!$libID) return print(html::select('doc', '', '', "class='form-control chosen'"));

        $docIdList = $this->doc->getPrivDocs($libID, 0);
        $docs = $this->dao->select('id, title')->from(TABLE_DOC)
            ->where('lib')->eq($libID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('id')->in($docIdList)
            ->orderBy('`order` asc')
            ->fetchPairs();

        return print(html::select('doc', array('' => '') + $docs, '', "class='form-control chosen'"));
    }

    /**
     * Ajax save draft.
     *
     * @param int $docID
     * @access public
     * @return void
     */
    public function ajaxSaveDraft($docID)
    {
        $this->doc->saveDraft($docID);
    }

    /**
     * Ajax get whitelist.
     *
     * @param  int    $doclibID
     * @param  string $acl open|custom|private
     * @param  string $control user|group
     * @param  int    $docID
     * @access public
     * @return string
     */
    public function ajaxGetWhitelist($doclibID, $acl = '', $control = '', $docID = 0)
    {
        $doclib        = $this->doc->getLibById($doclibID);
        $doc           = $this->doc->getById($docID);
        $users         = $this->user->getPairs('noletter|noempty|noclosed');
        $selectedUser  = $docID ? $doc->users : $doclib->users;
        $selectedGroup = $docID ? $doc->groups : $doclib->groups;
        $dropDirection = "data-drop-direction='top'";

        if($control == 'group')
        {
            $groups = $this->loadModel('group')->getPairs();
            if($doclib->acl == 'custom')
            {
                foreach($groups as $groupID => $group)
                {
                    if(strpos($doclib->groups, (string)$groupID) === false) unset($groups[$groupID]);
                }
                return print(html::select('groups[]', $groups, $selectedGroup, "class='form-control picker-select' multiple $dropDirection"));
            }
            if($doclib->acl == 'open') return print(html::select('groups[]', $groups, $selectedGroup, "class='form-control picker-select' multiple $dropDirection"));
            if($doclib->acl == 'default') return print(html::select('groups[]', $groups, $selectedGroup, "class='form-control picker-select' multiple $dropDirection"));
            if($doclib->acl == 'private') echo 'private';

            return false;
        }

        if($control == 'user')
        {
            foreach($users as $account => $user)
            {
                if(($doclib->acl == 'custom' or $doclib->acl == 'private') and strpos($doclib->users, (string)$account) === false ) unset($users[$account]);
            }

            if($doclib->acl == 'custom')
            {
                return print(html::select('users[]', $users, $selectedUser, "multiple class='form-control picker-select' $dropDirection"));
            }
            if($doclib->acl == 'open') return print(html::select('users[]', $users, $selectedUser, "multiple class='form-control picker-select' $dropDirection"));
            if($doclib->acl == 'default') return print(html::select('users[]', $users, $selectedUser, "multiple class='form-control picker-select' $dropDirection"));
            if($doclib->acl == 'private' and $doclib->type == 'project')
            {
                echo 'project';
            }
            else
            {
                echo 'private';
            }

            return false;
        }

        /* Sync whitelist when doclib permissions changed. */
        if($doclib->acl != 'custom' and !empty($doclib->project) and $acl == 'custom')
        {
            $project      = $this->loadModel('project')->getById($doclib->project);
            $projectTeams = $this->loadModel('user')->getTeamMemberPairs($doclib->project);
            $stakeholders = $this->loadModel('stakeholder')->getStakeHolderPairs($doclib->project);
            $whitelist    = implode(',', array_keys($projectTeams + $stakeholders)) . $project->whitelist . ',' . $project->PM . ',' . $doclib->users;

            $selectedUser = $whitelist;
        }

        return print(html::select('users[]', $users, $selectedUser, "class='form-control picker-select' multiple"));
    }

    /**
     * Show files.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $viewType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $searchTitle
     *
     * @access public
     * @return void
     */
    public function showFiles($type, $objectID, $viewType = '', $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $searchTitle = '')
    {
        if(empty($viewType)) $viewType = !empty($_COOKIE['docFilesViewType']) ? $this->cookie->docFilesViewType : 'card';
        setcookie('docFilesViewType', $viewType, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $objects                   = $this->doc->getOrderedObjects($type);
        $objectID                  = $this->{$type}->saveState($objectID, $objects);
        $libs                      = $this->doc->getLibsByObject($type, $objectID);
        $this->lang->modulePageNav = $this->doc->select($type, $objects, $objectID, $libs);

        $tab = strpos('doc,product,project,execution', $this->app->tab) !== false ? $this->app->tab : 'doc';
        if($tab != 'doc') $this->loadModel($tab)->setMenu($objectID);

        $table  = $this->config->objectTables[$type];
        $object = $this->dao->select('id,name,status')->from($table)->where('id')->eq($objectID)->fetch();

        if(!empty($_POST)) $searchTitle = $this->post->title;
        if(empty($_POST) and !empty($searchTitle)) $this->post->title = $searchTitle;

        $this->lang->TRActions = $this->doc->buildCollectButton4Doc();
        $this->lang->TRActions .= $this->doc->buildBrowseSwitch($type, $objectID, $viewType, $orderBy, $recTotal, $recPerPage, $pageID, $searchTitle);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $files = $this->doc->getLibFiles($type, $objectID, $orderBy, $pager);

        $this->view->title      = $object->name;
        $this->view->position[] = $object->name;

        $this->view->type         = $type;
        $this->view->object       = $object;
        $this->view->files        = $files;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager        = $pager;
        $this->view->viewType     = $viewType;
        $this->view->orderBy      = $orderBy;
        $this->view->objectID     = $objectID;
        $this->view->canBeChanged = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->summary      = $this->doc->summary($files);
        $this->view->sourcePairs  = $this->doc->getFileSourcePairs($files);
        $this->view->fileIcon     = $this->doc->getFileIcon($files);

        $this->display();
    }

    /**
     * Show all libs by type.
     *
     * @param string $type
     * @param string $product
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
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

        if(!$this->server->http_referer) return print(js::locate(inlink('index')));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(inlink('index')));

        return print(js::locate('back'));
    }

    /**
     * Show libs for product or project.
     *
     * @param string $type
     * @param int $objectID projectID|productID
     * @param int $libID
     * @param int $docID
     * @param int $version
     * @param int $appendLib
     * @access public
     * @return void
     */
    public function objectLibs($type, $objectID = 0, $libID = 0, $docID = 0, $version = 0, $appendLib = 0)
    {
        $lib = $this->doc->getLibById($libID);
        if(!empty($lib) and $lib->deleted == '1') $appendLib = $libID;

        if($this->config->systemMode == 'classic' and $type == 'project') $type = 'execution';
        list($libs, $libID, $object, $objectID) = $this->doc->setMenuByType($type, $objectID, $libID, $appendLib);

        /* Set Custom. */
        foreach(explode(',', $this->config->doc->customObjectLibs) as $libType) $customObjectLibs[$libType] = $this->lang->doc->customObjectLibs[$libType];

        $actionURL = $this->createLink('doc', 'tableContents', "type=$type&objectID=$objectID&libID=$libID&browseType=bySearch&param=myQueryID");
        $this->doc->buildSearchForm(0, array(), 0, $actionURL, $type);

        $moduleTree = $type == 'book' ? $this->doc->getBookStructure($libID) : $this->doc->getTreeMenu($type, $objectID, $libID, 0, $docID);

        /* Get doc. */
        if($docID)
        {
            $doc = $this->doc->getById($docID, $version, true);
            if(!$doc) return print(js::error($this->lang->notFound));

            if($doc->keywords)
            {
                $doc->keywords = str_replace("，", ',', $doc->keywords);
                $doc->keywords = explode(',', $doc->keywords);
            }
        }

        if(isset($doc) and ($doc->type == 'text' || $doc->type == 'article'))
        {
            /* Split content into an array. */
            $content = explode("\n", $doc->content);

            /* Get the head element, for example h1,h2,etc. */
            $includeHeadElement = array();
            foreach($content as $index => $element)
            {
                preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

                if(isset($headElement[1]) and !in_array($headElement[1], $includeHeadElement) and strip_tags($headElement[3]) != '') $includeHeadElement[] = $headElement[1];
            }

            /* Get the two elements with the highest rank. */
            sort($includeHeadElement);
            $includeHeadElement = array_slice($includeHeadElement, 0, 2);

            if($includeHeadElement)
            {
                $outline    = '<ul class="tree tree-angles" data-ride="tree" id="outline">';
                $preElement = '';
                foreach($content as $index => $element)
                {
                    preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

                    /* The current element is existed, the element is in the includeHeadElement, and the text in the element is not null. */
                    if(isset($headElement[1]) and in_array($headElement[1], $includeHeadElement) and strip_tags($headElement[3]) != '')
                    {
                        /* The element is the first level. */
                        if(array_search($headElement[1], $includeHeadElement) == 0)
                        {
                            /* The second level is existed, and previous element is the second level element. */
                            if(isset($includeHeadElement[1]) and $preElement == $includeHeadElement[1]) $outline .= '</ul></li>';
                            if($preElement == $includeHeadElement[0]) $outline .= '</li>';

                            /* Add the anchor to the element. */
                            $content[$index] = str_replace('<' . $includeHeadElement[0] . $headElement[2] . '>', '<' . $includeHeadElement[0] . $headElement[2] . " id='anchor{$index}'" . '>', $content[$index]);
                            $outline        .= '<li class="text-ellipsis">' . html::a('#anchor' . $index, strip_tags($headElement[3]), '', "title='" . strip_tags($headElement[3]) . "'");

                            $preElement = $headElement[1];
                        }
                        elseif(array_search($headElement[1], $includeHeadElement) == 1)
                        {
                            if($preElement == '') $outline .= '<li><ul>';
                            if($preElement == $includeHeadElement[0]) $outline .= '<ul>';

                            /* Add the anchor to the element. */
                            $content[$index] = str_replace('<' . $includeHeadElement[1] . $headElement[2] . '>', '<' . $includeHeadElement[1] . $headElement[2] . " id='anchor{$index}'" . '>', $content[$index]);
                            $outline        .= '<li class="text-ellipsis">' . html::a('#anchor' . $index, strip_tags($headElement[3]), '', "title='" . strip_tags($headElement[3]) . "'") . '</li>';

                            $preElement = $includeHeadElement[1];
                        }
                    }
                    if(isset($includeHeadElement[1]) and $preElement == $includeHeadElement[1] and !isset($content[$index + 1])) $outline .= '</ul></li>';
                }
                $outline .= '</ul>';

                $doc->content = implode("\n", $content);

                $this->view->outline = $outline;
            }
        }

        $doc = $docID ? $doc : '';

        $this->view->customObjectLibs = $customObjectLibs;
        $this->view->showLibs         = $this->config->doc->custom->objectLibs;

        $this->view->title      = ($type == 'book' or $type == 'custom') ? $this->lang->doc->customAB : $object->name;
        $this->view->position[] = ($type == 'book' or $type == 'custom') ? $this->lang->doc->customAB : $object->name;

        $this->view->docID        = $docID;
        $this->view->doc          = $doc;
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
        $this->view->preAndNext   = $this->doc->getPreAndNextDoc($docID, $libID);
        $this->view->autoloadPage = $this->doc->checkAutoloadPage($doc);

        $this->display();
    }

    /**
     * Show the catalog of the doc library.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function tableContents($type, $objectID = 0, $libID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        list($libs, $libID, $object, $objectID) = $this->doc->setMenuByType($type, $objectID, $libID);
        $this->session->set('createProjectLocate', $this->app->getURI(true), 'doc');

        $libID = (int)$libID;

        $moduleTree = $type == 'book' ? $this->doc->getBookStructure($libID) : $this->doc->getTreeMenu($type, $objectID, $libID);

        $title = ($type == 'book' or $type == 'custom') ? $this->lang->doc->tableContents : $object->name . $this->lang->colon . $this->lang->doc->tableContents;

        /* Build the search form. */
        $queryID   = $browseType == 'bySearch' ? (int)$param : 0;
        $actionURL = $this->createLink('doc', 'tableContents', "type=$type&objectID=$objectID&libID=$libID&browseType=bySearch&param=myQueryID");
        $this->doc->buildSearchForm($libID, $libs, $queryID, $actionURL, $type);

        $this->view->title      = $title;
        $this->view->type       = $type;
        $this->view->browseType = $browseType;
        $this->view->param      = $queryID;
        $this->view->users      = $this->user->getPairs('noletter');
        if($browseType == 'bySearch')
        {
            /* Load pager. */
            $rawMethod = $this->app->rawMethod;
            $this->app->rawMethod = 'tableContents';
            $this->app->loadClass('pager', $static = true);
            $pager = new pager($recTotal, $recPerPage, $pageID);
            $this->app->rawMethod = $rawMethod;

            $this->view->docs       = $this->doc->getDocsBySearch($type, $objectID, $libID, $queryID, $pager);
            $this->view->browseType = 'bySearch';
            $this->view->pager      = $pager;
            $this->display('doc', 'browse', "browseType=bySearch&param=$queryID");
        }
        else
        {
            $this->view->libs       = $libs;
            $this->view->objectID   = $objectID;
            $this->view->libID      = $libID;
            $this->view->moduleTree = $moduleTree;

            $this->display();
        }
    }

    /**
     * Select lib type.
     *
     * @access public
     * @return void
     */
    public function selectLibType()
    {
        if($_POST)
        {
            $response['message']    = $this->lang->saveSuccess;
            $response['result']     = 'success';
            $response['closeModal'] = true;
            $response['callback']   = "redirectParentWindow(\"{$this->post->objectType}\", \"{$this->post->lib}\", \"{$this->post->type}\")";
            return $this->send($response);
        }

        unset($this->lang->doc->libTypeList['book']);

        $globalTypeList = $this->lang->doc->libTypeList;
        $globalTypeList = $this->config->vision == 'lite' ? $globalTypeList : $globalTypeList + $this->lang->doc->libGlobalList;

        $defaultType = key($globalTypeList);
        $unclosed    = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs        = $this->doc->getLibs($defaultType, "withObject,$unclosed");

        $this->view->globalTypeList = $globalTypeList;
        $this->view->defaultType    = $defaultType;
        $this->view->libs           = $libs;

        $this->display();
    }

    /**
     * Sort libs.
     * @param  string $type
     * @param  int    $objectID
     *
     * @access public
     * @return void
     */
    public function sortLibs($type, $objectID)
    {
        if(!empty($_POST))
        {
            $this->doc->updateLibOrder();
            return print(js::reload('parent.parent'));
        }
        $this->view->title = $this->lang->doc->sortLibs;
        $this->view->libs  = $this->doc->getLibs($type, '', '', $objectID);
        $this->display();
    }
}
