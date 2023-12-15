<?php
declare(strict_types=1);
/**
 * The control file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: control.php 933 2010-07-06 06:53:40Z wwccss $
 * @link        https://www.zentao.net
 */
class doc extends control
{
    /**
     * 构造函数，加载通用模块。
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
     * 文档仪表盘。
     * Document dashboard.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        echo $this->fetch('block', 'dashboard', 'dashboard=doc');
    }

    /**
     * 我的空间。
     * My space.
     *
     * @param  string $type       mine|view|collect|createdBy
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType all|draft|bysearch
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function mySpace(string $type = 'mine', int $libID = 0, int $moduleID = 0, string $browseType = 'all', int $param = 0, string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $browseType = strtolower($browseType);
        $type       = strtolower($type);

        if(empty($orderBy) && $type == 'mine') $orderBy = 'status,editedDate_desc';
        if(empty($orderBy) && ($type == 'view' || $type == 'collect')) $orderBy = 'status,date_desc';
        if(empty($orderBy) && ($type == 'createdby')) $orderBy = 'status,addedDate_desc';
        if(empty($orderBy) && ($type == 'editedby')) $orderBy = 'status,editedDate_desc';

        /* Save session, load module. */
        $uri = $this->app->getURI(true);
        $this->session->set('docList', $uri, 'doc');
        $this->session->set('productList', $uri, 'product');
        $this->session->set('executionList', $uri, 'execution');
        $this->session->set('projectList', $uri, 'project');
        $this->session->set('spaceType', 'mine', 'doc');
        $this->loadModel('search');

        if($moduleID) $libID = $this->tree->getById($moduleID)->root;
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType('mine', 0, $libID);

        $titleList      = array('mine' => 'myLib', 'view' => 'myView', 'collect' => 'myCollection', 'createdby' => 'myCreation', 'editedby' => 'myEdited');
        $objectTitle    = $this->lang->doc->{$titleList[$type]};
        $objectDropdown = "<div id='sidebarHeader'><div class='title' title='{$objectTitle}'>{$objectTitle}</div></div>";

        /* Build the search form. */
        $queryID    = $browseType == 'bysearch' ? $param : 0;
        $params     = "libID={$libID}&moduleID={$moduleID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}";
        if($this->app->rawMethod == 'myspace') $params = "type={$type}&{$params}";
        $actionURL  = $this->createLink('doc', $this->app->rawMethod, $params);
        $this->doc->buildSearchForm($libID, $libs, $queryID, $actionURL, $type);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Get doc list data. */
        $docs = array();
        if($type == 'mine')
        {
            if($browseType != 'bysearch' && !$libID)
            {
                $docs = array();
            }
            else
            {
                $docs = $browseType == 'bysearch' ? $this->doc->getDocsBySearch('mine', 0, $libID, $queryID, $orderBy, $pager) : $this->doc->getDocs($libID, $moduleID, $browseType, $orderBy, $pager);
            }
        }
        elseif(in_array($type, array('view', 'collect', 'createdby', 'editedby')))
        {
            $docs = $this->doc->getMineList($type, $browseType, $queryID, $orderBy, $pager);
        }

        $this->docZen->assignVarsForMySpace($type, $objectID, $libID, $moduleID, $browseType, $param, $orderBy, $docs, $pager, $libs, $objectDropdown);
        $this->display();
    }

    /**
     * 创建一个文档库。
     * Create a library.
     *
     * @param  string $type     api|project|product|execution|custom|mine
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function createLib(string $type = '', int $objectID = 0)
    {
        if(!empty($_POST))
        {
            $lib = $this->docZen->buildLibForCreateLib();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $libID = $this->doc->createLib($lib, (string)$this->post->type, (string)$this->post->libType);
            return $this->docZen->responseAfterCreateLib($type, $objectID, $libID);
        }

        if(in_array($type, array('product', 'project'))) $this->app->loadLang('api');

        $objects = array();
        if($type == 'product') $objects = $this->product->getPairs();
        if($type == 'project')
        {
            $excludedModel = $this->config->vision == 'lite' ? '' : 'kanban';
            $objects       = $this->project->getPairsByProgram(0, 'all', false, 'order_asc', $excludedModel);
            if($this->app->tab == 'doc')
            {
                $this->view->executionPairs = $this->execution->getPairs($objectID, 'sprint,stage', 'multiple,leaf,noprefix');
                $this->view->project        = $this->project->getById($objectID);
            }
        }

        if($type == 'execution')
        {
            $objects   = $this->execution->getPairs(0, 'sprint,stage', 'multiple,leaf,noprefix,withobject');
            $execution = $this->execution->getByID($objectID);
            if($execution->type == 'stage') $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);
        }

        $this->docZen->setAclForCreateLib($type);

        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->users    = $this->user->getPairs('nocode|noclosed');
        $this->view->objects  = $objects;
        $this->view->type     = $type;
        $this->view->objectID = $objectID;
        $this->display();
    }

    /**
     * 编辑一个文档库。
     * Edit a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editLib(int $libID)
    {
        if(!empty($_POST))
        {
            $this->lang->doc->name = $this->lang->doclib->name;
            $libData = form::data()->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->doc->updateLib($libID, $libData);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('docLib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'closeModal' => true, 'load' => true));
        }

        $lib = $this->doc->getLibByID($libID);
        if(!empty($lib->product)) $this->view->object = $this->product->getByID($lib->product);
        if(!empty($lib->project) && empty($lib->execution)) $this->view->object = $this->project->getByID($lib->project);
        if(!empty($lib->execution))
        {
            $execution = $this->execution->getByID($lib->execution);
            if($execution->type == 'stage')
            {
                if($execution->grade > 1)
                {
                    $parentExecutions = $this->execution->getPairsByList(explode(',', trim($execution->path, ',')), 'stage,kanban,sprint');
                    $execution->name  = implode('/', $parentExecutions);
                }

                $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);
            }

            $this->view->object = $execution;
        }

        $this->docZen->setAclForEditLib($lib);

        $this->view->lib    = $lib;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->user->getPairs('noletter|noclosed', $lib->users);
        $this->view->libID  = $libID;

        $this->display();
    }

    /**
     * 删除一个数据库。
     * Delete a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function deleteLib(int $libID)
    {
        if(in_array($libID, array('product', 'execution'))) return;

        $lib = $this->doc->getLibByID($libID);
        if(!empty($lib->main)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorMainSysLib, 'load' => array('alert' => $this->lang->doc->errorMainSysLib)));

        $this->doc->delete(TABLE_DOCLIB, $libID);
        if($this->app->tab == 'doc') return $this->send(array('result' => 'success', 'load' => true, 'app' => $this->app->tab));

        $objectType = $lib->type;
        $objectID   = strpos(',product,project,execution,', ",$objectType,") !== false ? $lib->{$objectType} : 0;
        $moduleName = 'doc';
        $methodName = zget($this->config->doc->spaceMethod, $objectType);
        if($this->app->tab == 'execution' && $objectType == 'execution')
        {
            $moduleName = 'execution';
            $methodName = 'doc';
        }
        $browseLink = $this->createLink($moduleName, $methodName, "objectID=$objectID");

        return $this->send(array('result' => 'success', 'load' => $browseLink, 'app' => $this->app->tab));
    }

    /**
     * 上传文档。
     * Upload docs.
     *
     * @param  string     $objectType product|project|execution|custom
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType    html|word|ppt|excel|attachment
     * @access public
     * @return void
     */
    public function uploadDocs(string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = '')
    {
        if(!empty($_POST))
        {
            $doclib   = $this->loadModel('doc')->getLibByID($libID);
            $canVisit = $this->docZen->checkPrivForCreate($doclib, $objectType);
            if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));

            $libID    = $this->post->lib;
            $moduleID = $this->post->module;
            if(empty($libID) && strpos($this->post->module, '_') !== false) list($libID, $moduleID) = explode('_', $this->post->module);
            helper::setcookie('lastDocModule', $moduleID);

            if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
            $docData = form::data($this->config->doc->create)->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->uploadFormat == 'combinedDocs')
            {
                $docResult = $this->doc->create($docData, $this->post->labels);
            }
            else
            {
                $docResult = $this->doc->createSeperateDocs($docData);
            }

            return $this->docZen->responseAfterUploadDocs($docResult);
        }

        $this->docZen->assignVarsForUploadDocs($objectType, $objectID, $libID, $moduleID, $docType);
        $this->display();
    }

    /**
     * 创建一个文档。
     * Create a doc.
     *
     * @param  string     $objectType product|project|execution|custom
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType    html|word|ppt|excel
     * @access public
     * @return void
     */
    public function create(string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = '')
    {
        $linkType = $objectType;
        if($objectType == 'execution' && $this->app->tab != 'execution') $linkType = 'project';

        if(!empty($_POST))
        {
            $docLib   = $this->loadModel('doc')->getLibByID($libID);
            $canVisit = $this->docZen->checkPrivForCreate($docLib, $objectType);
            if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));

            $libID    = $this->post->lib;
            $moduleID = $this->post->module;
            if(empty($libID) && strpos($this->post->module, '_') !== false) list($libID, $moduleID) = explode('_', $this->post->module);
            helper::setcookie('lastDocModule', $moduleID);

            if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
            $docData   = form::data()->get();
            $docResult = $this->doc->create($docData, $this->post->labels);
            if(!$docResult || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->docZen->responseAfterCreate($docData->lib, $docResult);
        }

        $lib = $libID ? $this->doc->getLibByID($libID) : '';
        if(empty($objectID) && $lib) $objectID = zget($lib, $lib->type, 0);

        /* Get libs and the default lib ID. */
        $unclosed   = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs       = $this->doc->getLibs($objectType, "withObject,{$unclosed}", $libID, $objectID);
        $moduleID   = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        if(!$libID && !empty($libs)) $libID = key($libs);
        if(empty($lib) && $libID) $lib = $this->doc->getLibByID($libID);

        $this->docZen->setObjectsForCreate($linkType, $lib, $unclosed, $objectID);

        $this->view->title            = zget($lib, 'name', '', $lib->name . $this->lang->colon) . $this->lang->doc->create;
        $this->view->linkType         = $linkType;
        $this->view->objectType       = $objectType;
        $this->view->objectID         = zget($lib, $lib->type, 0);
        $this->view->libID            = $libID;
        $this->view->lib              = $lib;
        $this->view->libs             = $libs;
        $this->view->libName          = zget($lib, 'name', '');
        $this->view->moduleOptionMenu = $this->doc->getLibsOptionMenu($libs);
        $this->view->moduleID         = $libID . '_' . $moduleID;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');
        $this->display();
    }

    /**
     * 编辑一个文档。
     * Edit a doc.
     *
     * @param  int     $docID
     * @param  bool    $comment
     * @access public
     * @return void
     */
    public function edit(int $docID, bool $comment = false)
    {
        $doc = $this->doc->getByID($docID);
        if(!empty($_POST))
        {
            $changes = $files = array();
            if($comment == false)
            {
                if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
                $docData = form::data()->setIF(strpos(",$doc->editedList,", ",{$this->app->user->account},") === false, 'editedList', $doc->editedList . ",{$this->app->user->account}")->get();
                $result  = $this->doc->update($docID, $docData);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $changes = $result['changes'];
                $files   = $result['files'];
            }

            return $this->docZen->responseAfterEdit($doc, $comment, $changes, $files);
        }

        /* Get doc and set menu. */
        $lib      = $this->doc->getLibByID((int)$doc->lib);
        $objectID = zget($lib, $lib->type, 0);
        $libs     = $this->doc->getLibs($lib->type, 'withObject', $doc->lib, $objectID);

        $this->docZen->setObjectsForEdit($lib->type, $objectID);

        $this->view->title            = $lib->name . $this->lang->colon . $this->lang->doc->edit;
        $this->view->doc              = $doc;
        $this->view->moduleOptionMenu = $this->doc->getLibsOptionMenu($libs);
        $this->view->type             = $lib->type;
        $this->view->libs             = $libs;
        $this->view->lib              = $lib;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('noletter|noclosed|nodeleted', $doc->users);
        $this->view->files            = $this->loadModel('file')->getByObject('doc', $docID);
        $this->view->objectID         = $objectID;
        $this->view->otherEditing     = $this->doc->checkOtherEditing($docID);
        $this->display();
    }

    /**
     * 删除一个文档。
     * Delete a doc.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function delete(int $docID)
    {
        $this->doc->delete(TABLE_DOC, $docID);

        /* Delete doc files. */
        $doc = $this->doc->getByID($docID);
        if($doc->files) $this->doc->deleteFiles(array_keys($doc->files));

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 收藏一个文档。
     * Collect a doc.
     *
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function collect(int $objectID)
    {
        $action = $this->doc->getActionByObject($objectID, 'collect');
        if($action)
        {
            $this->doc->deleteAction($action->id);
            $this->action->create('doc', $objectID, 'uncollected');
        }
        else
        {
            $this->doc->createAction($objectID, 'collect');
            $this->action->create('doc', $objectID, 'collected');
        }

        if($this->viewType == 'json') $this->send(array('status' => $action ? 'no' : 'yes'));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'status' => $action ? 'no' : 'yes'));
    }

    /**
     *
     * AJAX: 获取对象的目录。
     * Ajax get modules by object.
     *
     * @param  string $objectType project|product|custom|mine
     * @param  int    $objectID
     * @param  string $docType    doc|api
     * @access public
     * @return void
     */
    public function ajaxGetModules(string $objectType, int $objectID, string $docType = 'doc')
    {
        if(empty($objectID)) return print(array());

        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($objectType, "withObject,{$unclosed}", '', $objectID);
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs('', $objectType, $objectID);
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }

        $items            = array();
        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libPairs, $docType);
        foreach($moduleOptionMenu as $id => $name)
        {
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode($items));
    }

    /**
     * AJAX: 通过空间类型获取文档库。
     * AJAX: Get libs by type.
     *
     * @param  string $type    mine|product|project|nolink|custom
     * @param  string $docType doc|api
     * @access public
     * @return void
     */
    public function ajaxGetLibsByType(string $type, string $docType = 'doc')
    {
        $libPairs = array();
        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($type, "withObject,$unclosed");
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs('', 'nolink');
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }

        $items = array();
        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libPairs, $docType);
        foreach($moduleOptionMenu as $id => $name)
        {
            if(empty($id)) continue;
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode($items));
    }

    /**
     * Ajax: 获取白名单。
     * Ajax: Get whitelist.
     *
     * @param  int    $doclibID
     * @param  string $acl     open|custom|private
     * @param  string $control user|group
     * @param  int    $docID
     * @access public
     * @return string
     */
    public function ajaxGetWhitelist(int $doclibID, string $acl = '', string $control = '', int $docID = 0)
    {
        $doclib = $this->doc->getLibByID($doclibID);
        $doc    = $docID ? $this->doc->getByID($docID) : null;
        $users  = $this->user->getPairs('noletter|noempty|noclosed');
        if($control == 'group')
        {
            if($doclib->acl == 'private') return print('private');

            $groupItems = array();
            $groups     = $this->loadModel('group')->getPairs();
            foreach($groups as $groupID => $groupName) $groupItems[] = array('text' => $groupName, 'value' => $groupID, 'keys' => $groupName);
            return print(json_encode($groupItems));
        }

        if($doclib->acl == 'private') return print('private');

        $userItems = array();
        foreach($users as $account => $realname)
        {
            if($doclib->acl == 'private' && strpos($doclib->users, (string)$account) === false ) unset($users[$account]);
            $userItems[] = array('text' => $realname, 'value' => $account, 'keys' => $realname);
        }
        return print(json_encode($userItems));
    }

    /**
     * 附件库。
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
     * @access public
     * @return void
     */
    public function showFiles(string $type, int $objectID, string $viewType = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $searchTitle = '')
    {
        $this->loadModel('file');
        if(empty($viewType)) $viewType = !empty($_COOKIE['docFilesViewType']) ? $this->cookie->docFilesViewType : 'list';
        helper::setcookie('docFilesViewType', $viewType, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $objects = $this->doc->getOrderedObjects($type, 'nomerge', $objectID);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($type, $objectID, 0);

        $object = $this->doc->getObjectByID($type, $objectID);
        if(empty($_POST) && !empty($searchTitle)) $this->post->title = $searchTitle;

        /* Load pager. */
        $rawMethod = $this->app->rawMethod;
        $this->app->rawMethod = 'showFiles';
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $this->app->rawMethod = $rawMethod;

        $files       = $this->doc->getLibFiles($type, $objectID, $this->post->title, $orderBy, $pager);
        $fileIcon    = $this->doc->getFileIcon($files);
        $sourcePairs = $this->doc->getFileSourcePairs($files);

        $this->view->title             = $object->name;
        $this->view->type              = $type;
        $this->view->object            = $object;
        $this->view->files             = $this->docZen->processFiles($files, $fileIcon, $sourcePairs);
        $this->view->fileIcon          = $fileIcon;
        $this->view->sourcePairs       = $sourcePairs;
        $this->view->users             = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager             = $pager;
        $this->view->viewType          = $viewType;
        $this->view->orderBy           = $orderBy;
        $this->view->objectID          = $objectID;
        $this->view->canBeChanged      = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->summary           = $this->doc->summary($files);
        $this->view->libTree           = $this->doc->getLibTree(0, $libs, $type, 0, $objectID);
        $this->view->objectDropdown    = $objectDropdown;
        $this->view->searchTitle       = $searchTitle;
        $this->view->linkParams        = "objectID=$objectID&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->spaceType         = $type;
        $this->view->objectType        = $type;
        $this->view->libID             = 0;
        $this->view->moduleID          = 0;
        $this->view->defaultNestedShow = $this->docZen->getDefaultNestedShow(0, 0, $type);

        $this->display();
    }

    /**
     * 文档详情页面。
     * Document details page.
     *
     * @param  int    $docID
     * @param  int    $version
     * @param  int    $appendLib
     * @access public
     * @return void
     */
    public function view(int $docID = 0, int $version = 0, int $appendLib = 0)
    {
        $doc = $this->doc->getByID($docID, $version, true);
        if(!$this->doc->checkPrivDoc($doc)) return $this->sendError($this->lang->doc->accessDenied, inlink('index'));

        if(!$doc || !isset($doc->id))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->sendError($this->lang->notFound, $this->inlink('index'));
        }

        $lib = $this->doc->getLibByID((int)$doc->lib);
        if(!empty($lib) && $lib->deleted == '1') $appendLib = $doc->id;

        $objectType = isset($lib->type) ? $lib->type : 'custom';
        $objectID   = zget($doc, $objectType, 0);
        if($objectType == 'custom') $this->lang->doc->menu->custom['alias'] = 'teamspace,view';
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($objectType, $objectID, (int)$doc->lib, $appendLib);

        /* Get doc. */
        if($docID)
        {
            $this->doc->createAction($docID, 'view');
            $this->doc->removeEditing($doc);
            if($doc->keywords)
            {
                $doc->keywords = str_replace("，", ',', $doc->keywords);
                $doc->keywords = explode(',', $doc->keywords);
            }
        }

        if(isset($doc) && $doc->type == 'text') $doc = $this->docZen->processOutline($doc);
        if($this->app->tab != 'execution' && !empty($doc->execution)) $object = $this->execution->getByID($doc->execution);

        $this->docZen->assignVarsForView($docID, $version, $objectType, $objectID, $libID, $doc, $object, $objectType, $libs, $objectDropdown);
        $this->display();
    }

    /**
     * 产品/项目/执行/团队空间。
     * ProductSpace/ProjectSpace/ExecutionSpace/TeamSpace.
     *
     * @param  string $type        custom|product|project|execution
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType  all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function tableContents(string $type = 'custom', int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'status,id_desc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->docZen->setSpacePageStorage($type, $browseType, $objectID, $libID, $moduleID, $param);

        if($moduleID) $libID = $this->tree->getById($moduleID)->root;
        $isFirstLoad = $libID == 0 ? true : false;
        if(empty($browseType)) $browseType = 'all';

        $libData = $this->doc->setMenuByType($type, $objectID, $libID);
        if(is_string($libData)) return $this->locate($libData);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $libData;

        $libID   = (int)$libID;
        $lib     = $this->doc->getLibByID($libID);
        $libType = isset($lib->type) && $lib->type == 'api' ? 'api' : 'lib';

        /* Build the search form. */
        $queryID = $browseType == 'bySearch' ? $param : 0;
        $params  = "objectID={$objectID}&libID={$libID}&moduleID=0&browseType=bySearch&orderBy={$orderBy}&param=myQueryID";
        if($this->app->rawMethod == 'tablecontents') $params = "type={$type}&" . $params;
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, $params);
        if($libType == 'api') $this->loadModel('api')->buildSearchForm($lib, $queryID, $actionURL, $libs, $type);
        if($libType != 'api') $this->doc->buildSearchForm($libID, $libs, $queryID, $actionURL, $type);

        $this->assignApiVarForSpace($type, $browseType, $libType, $libID, $libs, $objectID, $moduleID, $queryID, $orderBy, $param, $recTotal, $recPerPage, $pageID);

        /* For product drop menu. */
        if(in_array($type, array('product', 'project', 'execution')))
        {
            $objectKey = $type . 'ID';
            $this->view->$objectKey = $objectID;
        }

        $executionID = $type == 'project' && $lib->type == 'execution' ? $lib->execution : 0;

        $this->view->title             = $type == 'custom' ? $this->lang->doc->tableContents : $object->name . $this->lang->colon . $this->lang->doc->tableContents;
        $this->view->type              = $type;
        $this->view->objectType        = $type;
        $this->view->spaceType         = $type;
        $this->view->browseType        = $browseType;
        $this->view->isFirstLoad       = $isFirstLoad;
        $this->view->param             = $queryID;
        $this->view->users             = $this->user->getPairs('noletter');
        $this->view->libTree           = $this->doc->getLibTree($libID, $libs, $type, $moduleID, $objectID, $browseType, (int)$param);
        $this->view->libID             = $libID;
        $this->view->moduleID          = $moduleID;
        $this->view->objectDropdown    = $objectDropdown;
        $this->view->lib               = $lib;
        $this->view->libType           = $libType;
        $this->view->objectID          = $objectID;
        $this->view->orderBy           = $orderBy;
        $this->view->release           = $browseType == 'byrelease' ? $param : 0;
        $this->view->exportMethod      = $libType == 'api' ? 'export' : $type . '2export';
        $this->view->linkParams        = "objectID={$objectID}&%s&browseType=&orderBy={$orderBy}&param=0";
        $this->view->defaultNestedShow = $this->docZen->getDefaultNestedShow($libID, $moduleID, $type, $executionID);

        $this->display();
    }

    /**
     * 产品空间。
     * Product space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType   all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function productSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'status,id_desc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $products = $this->product->getPairs('nocode');
        $objectID = $this->product->checkAccess($objectID, $products);

        echo $this->fetch('doc', 'tableContents', "type=product&objectID={$objectID}&libID={$libID}&moduleID={$moduleID}&browseType={$browseType}&orderBy={$orderBy}&param={$param}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 项目空间。
     * Project space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType  all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function projectSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'status,id_desc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $projects = $this->project->getPairsByProgram();
        $objectID = $this->project->checkAccess($objectID, $projects);

        echo $this->fetch('doc', 'tableContents', "type=project&objectID={$objectID}&libID={$libID}&moduleID={$moduleID}&browseType={$browseType}&orderBy={$orderBy}&param={$param}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 团队空间。
     * Team space.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType    all|draft|bysearch
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function teamSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'status,id_desc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        echo $this->fetch('doc', 'tableContents', "type=custom&objectID={$objectID}&libID={$libID}&moduleID={$moduleID}&browseType={$browseType}&orderBy={$orderBy}&param={$param}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 设置选择文档库类型的范围。
     * Set the scope of the document library type to be selected.
     *
     * @access public
     * @return void
     */
    public function selectLibType()
    {
        if($_POST)
        {
            $response = array();
            if(empty($_POST['module']))
            {
                $response['result'] = 'fail';
                $response['message']['module'] = sprintf($this->lang->error->notempty, $this->lang->doc->libAndModule);
                return $this->send($response);
            }

            if(strpos($this->post->module, '_') !== false) list($libID, $moduleID) = explode('_', $this->post->module);
            $response['result']     = 'success';
            $response['closeModal'] = true;
            $response['callback']   = "redirectParentWindow(\"{$this->post->space}\", {$libID}, {$moduleID}, \"{$this->post->type}\")";
            return $this->send($response);
        }

        $spaceList = $this->lang->doc->spaceList;
        $typeList  = $this->lang->doc->types;
        if(!common::hasPriv('doc', 'create'))       unset($spaceList['mine'], $spaceList['custom'], $typeList['doc']);
        if(!common::hasPriv('doc', 'mySpace'))      unset($spaceList['mine']);
        if(!common::hasPriv('doc', 'productSpace')) unset($spaceList['product']);
        if(!common::hasPriv('doc', 'projectSpace')) unset($spaceList['project']);
        if(!common::hasPriv('doc', 'teamSpace'))    unset($spaceList['custom']);
        if(!common::hasPriv('api', 'index'))        unset($spaceList['api']);
        if(!common::hasPriv('api', 'create'))       unset($spaceList['api'], $typeList['api']);
        if($this->config->vision == 'lite')         unset($spaceList['api'], $spaceList['product'], $typeList['api']);

        $products = $this->loadModel('product')->getPairs();
        $projects = $this->project->getPairsByProgram(0, 'all', false, 'order_asc');

        $this->view->spaceList = $spaceList;
        $this->view->typeList  = $typeList;
        $this->view->products  = $products;
        $this->view->projects  = $projects;

        $this->display();
    }

    /**
     * Ajax: 根据对象类型设置下拉菜单。
     * Ajax: Set the drop-down menu according to the object type.
     *
     * @param  string $objectType product|project
     * @param  int    $objectID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(string $objectType, int $objectID, string $module, string $method)
    {
        list($myObjects, $normalObjects, $closedObjects) = $this->doc->getOrderedObjects($objectType, 'nomerge', $objectID);

        $this->view->objectType    = $objectType;
        $this->view->objectID      = $objectID;
        $this->view->module        = $module;
        $this->view->method        = $method =='view' ? $objectType.'space' : $method;
        $this->view->normalObjects = $myObjects + $normalObjects;
        $this->view->closedObjects = $closedObjects;
        $this->view->objectsPinYin = common::convert2Pinyin($myObjects + $normalObjects + $closedObjects);

        $this->display();
    }

    /**
     * Ajax: 获取执行下拉数据。
     * Ajax: Get the execution drop down by the projectID.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function ajaxGetExecution(int $projectID)
    {
        $json  = array();
        $items = array();

        $executionPairs = $this->execution->getPairs($projectID, 'sprint,stage', 'multiple,leaf,noprefix');
        foreach($executionPairs as $id => $name) $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $json['items']   = $items;
        $json['project'] = $this->project->getByID($projectID);
        return print(json_encode($json));
    }

    /**
     * 编辑一个目录。
     * Edit a catalog.
     *
     * @param  int    $moduleID
     * @param  string $type     doc|api
     * @access public
     * @return void
     */
    public function editCatalog(int $moduleID, string $type)
    {
        echo $this->fetch('tree', 'edit', "moduleID={$moduleID}&type={$type}");
    }

    /**
     * 删除一个目录。
     * Delete a catalog.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function deleteCatalog(int $moduleID)
    {
        echo $this->fetch('tree', 'delete', "moduleID={$moduleID}&confirm=yes");
    }

    /**
     * 设置左侧树中是否显示文档。
     * Set documents are show or hidden in the left tree.
     *
     * @access public
     * @return void
     */
    public function displaySetting()
    {
        $this->loadModel('setting');
        if($_POST)
        {
            $this->setting->setItem($this->app->user->account . '.doc.showDoc', $this->post->showDoc);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $showDoc = $this->setting->getItem('owner=' . $this->app->user->account . '&module=doc&key=showDoc');
        $this->view->showDoc = $showDoc === '0' ? '0' : '1';

        $this->display();
    }
}
