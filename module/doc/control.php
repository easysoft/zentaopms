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
        $this->session->set('docList', $this->app->getURI(true), 'doc');
        echo $this->fetch('block', 'dashboard', 'dashboard=doc');
    }

    /**
     * 我的空间。
     * My space.
     *
     * @param  int    $objectID
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
    public function mySpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', int $param = 0, string $orderBy = 'order_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = 'list', int $docID = 0, string $search = '')
    {
        if(empty($objectID))
        {
            $this->docZen->initLibForMySpace();
            $spaces = $this->doc->getSubSpacesByType('mine');
            $objectID = array_pop(array_keys($spaces));
        }

        echo $this->fetch('doc', 'app', "type=mine&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&param=$param");
    }

    /**
     * 创建一个空间。
     * Create a space.
     *
     * @access public
     * @return void
     */
    public function createSpace(string $type = '')
    {
        if(!empty($_POST))
        {
            $this->lang->doc->name = $this->lang->doclib->spaceName;
            $space = form::data()->setDefault('addedBy', $this->app->user->account)->get();
            if($type) $space->type = $type;

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $spaceID = $this->doc->doInsertLib($space);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('docspace', $spaceID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('doc', 'teamSpace', "objectID={$spaceID}"), 'docApp' => array('selectSpace', $spaceID, true)));
        }

        $this->display();
    }

    /**
     * 创建一个文档库。
     * Create a library.
     *
     * @param  string $type     api|project|product|execution|custom|mine
     * @param  int    $objectID
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function createLib(string $type = '', int $objectID = 0, int $libID = 0)
    {
        $this->app->loadLang('api');

        if(!empty($_POST))
        {
            $lib = $this->docZen->buildLibForCreateLib();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $libID = $this->doc->createLib($lib, (string)$this->post->type, (string)$this->post->libType);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($type == 'custom' || $type == 'mine')
            {
                $lib      = $this->doc->getLibByID($libID);
                $objectID = $lib->parent;
            }

            return $this->docZen->responseAfterCreateLib($type, $objectID, $libID, $lib->name);
        }

        if(in_array($type, array('product', 'project'))) $this->app->loadLang('api');

        $objects = array();
        if($type == 'product') $objects = $this->product->getPairs();
        if($type == 'project')
        {
            $objects = $this->project->getPairsByProgram(0, 'all', false, 'order_asc');
            if($this->app->tab == 'doc')
            {
                $this->view->executionPairs = $this->execution->getPairs($objectID, 'all', 'multiple,leaf,noprefix');
                $this->view->project        = $this->project->getById($objectID);
            }
        }

        if($type == 'execution')
        {
            $objects   = $this->execution->getPairs(0, 'all', 'multiple,leaf,noprefix,withobject');
            $execution = $this->execution->getByID($objectID);
            if($execution->type == 'stage') $this->lang->doc->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doc->execution);
        }

        if($type == 'custom' || $type == 'mine' || $type == 'doctemplate')
        {
            $lib = $this->doc->getLibByID($libID);
            $this->view->spaces  = $this->doc->getSubSpacesByType($type);
            $this->view->spaceID = !empty($lib->parent) ? $lib->parent : $objectID;
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
     * @param  string $targetSpace
     * @access public
     * @return void
     */
    public function editLib(int $libID, string $targetSpace = '')
    {
        $lib = $this->doc->getLibByID($libID);
        if(!empty($_POST))
        {
            $this->lang->doc->name = $this->lang->nameAB;
            $libData = form::data()->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->doc->updateLib($libID, $libData);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $object = ($lib->type == 'custom' && $lib->parent == 0) ? 'docspace' : 'doclib';
                $actionID = $this->action->create($object, $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $lib = $this->doc->getLibByID($libID);
            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'closeModal' => true, 'docApp' => array('update', ($lib->type == 'custom' && $lib->parent == 0) ? 'space' : 'lib', $lib)));
        }

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

        $this->docZen->setAclForEditLib($lib, $targetSpace);

        if(($lib->type == 'custom' && $lib->parent > 0) || ($lib->type == 'mine' && $lib->main == 0))
        {
            $this->view->spaces      = $this->docZen->getAllSpaces();
            $this->view->targetSpace = $targetSpace ? $targetSpace : ($lib->type == 'mine' ? 'mine' : $lib->parent);
        }

        $this->view->lib         = $lib;
        $this->view->groups      = $this->loadModel('group')->getPairs();
        $this->view->users       = $this->user->getPairs('noletter|noclosed', $lib->users);
        $this->view->libID       = $libID;

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

        $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq('1')->where('id')->eq($libID)->exec();

        $object = ($lib->type == 'custom' && $lib->parent == 0) ? 'docspace' : 'doclib';
        $this->loadModel('action')->create($object, $libID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);

        $moduleName = 'doc';
        $objectType = $lib->type;
        $methodName = zget($this->config->doc->spaceMethod, $objectType);
        if($this->app->tab == 'doc') return $this->send(array('result' => 'success', 'load' => $this->createLink($moduleName, $methodName), 'app' => $this->app->tab));

        $objectID   = strpos(',product,project,execution,', ",$objectType,") !== false ? $lib->{$objectType} : 0;
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
            $libID    = (int)$this->post->lib;
            $doclib   = $this->loadModel('doc')->getLibByID($libID);
            $canVisit = $this->docZen->checkPrivForCreate($doclib, $objectType);
            if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));

            $moduleID = $this->post->module;
            helper::setcookie('lastDocModule', $moduleID);

            if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
            if($_POST['type'] == 'attachment' && empty($_FILES['files']['name'][0]))
            {
                $this->config->doc->form->create['title']['required'] = false;
                $this->config->doc->form->create['title']['skipRequired'] = true;
            }

            $this->config->doc->create->requiredFields = trim(str_replace(array(',content,', ',keywords,'), ",", ",{$this->config->doc->create->requiredFields},"), ',');

            $docData = form::data($this->config->doc->form->create)
                ->setDefault('addedBy', $this->app->user->account)
                ->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->uploadFormat == 'combinedDocs')
            {
                $docResult = $this->doc->create($docData);
            }
            else
            {
                $docResult = $this->doc->createSeperateDocs($docData);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
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
     * @param  int        $appendLib
     * @access public
     * @return void
     */
    public function create(string $objectType, int $objectID, int $libID, int $moduleID = 0, string $docType = '', int $appendLib = 0)
    {
        if(!empty($_POST))
        {
            $libID    = (int)$this->post->lib;
            $docLib   = $this->loadModel('doc')->getLibByID($libID);
            if($docLib)
            {
                $canVisit = $this->docZen->checkPrivForCreate($docLib, $objectType);
                if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));
            }

            $moduleID = $this->post->module;
            helper::setcookie('lastDocModule', $moduleID);

            if(!isset($_POST['lib']) && strpos($_POST['module'], '_') !== false) list($_POST['lib'], $_POST['module']) = explode('_', $_POST['module']);
            $docData = form::data()
                ->setDefault('addedBy', $this->app->user->account)
                ->setDefault('editedBy', $this->app->user->account)
                ->get();

            $docResult = $this->doc->create($docData, $this->post->labels);
            if(!$docResult || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->docZen->responseAfterCreate($docData->lib, $docResult);
        }

        $this->docZen->assignVarsForCreate($objectType, $objectID, $libID, $moduleID, $docType);

        $lib = $this->view->lib;
        if(empty($lib)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorEmptySpaceLib));

        $objectID = $this->view->objectID;
        $libData  = $this->doc->setMenuByType($lib->type, (int)$objectID, (int)$lib->id, (int)$appendLib);
        if(is_string($libData)) return $this->locate($libData);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $libData;
        if($this->config->edition != 'open') $this->loadModel('file');
        if($lib->type == 'custom' || $lib->type == 'mine') $this->view->spaces = $this->docZen->getAllSpaces($lib->type == 'mine' ? 'onlymine' : 'nomine');

        $this->view->title            = zget($lib, 'name', '', $lib->name . $this->lang->hyphen) . $this->lang->doc->create;
        $this->view->object           = $object;
        $this->view->objectDropdown   = $objectDropdown;
        $this->view->libTree          = $this->doc->getLibTree((int)$libID, $libs, $objectType, (int)$moduleID, (int)$objectID);
        $this->view->linkParams       = "objectID={$objectID}&%s&browseType=&orderBy=status,id_desc&param=0";

        if(!empty($objectType) && $objectType !== 'custom')
        {
            $objectIDVar = $objectType . 'ID';
            $this->view->$objectIDVar = $objectID;
        }
        $this->display();
    }

    /**
     * 编辑一个文档。
     * Edit a doc.
     *
     * @param  int     $docID
     * @param  bool    $comment
     * @param  int     $appendLib
     * @access public
     * @return void
     */
    public function edit(int $docID, bool $comment = false, int $appendLib = 0)
    {
        $doc = $this->doc->getByID($docID);
        if(!empty($_POST))
        {
            $changes = $files = array();
            if($comment == false)
            {
                $docData = form::data()
                    ->setDefault('editedBy', $this->app->user->account)
                    ->setIF(strpos(",$doc->editedList,", ",{$this->app->user->account},") === false, 'editedList', $doc->editedList . ",{$this->app->user->account}")
                    ->get();
                $result  = $this->doc->update($docID, $docData);
                if(dao::isError())
                {
                    if(!empty(dao::$errors['lib']) || !empty(dao::$errors['keywords'])) return $this->send(array('result' => 'fail', 'message' => dao::getError(), 'callback' => "zui.Modal.open({id: 'modalBasicInfo'});"));
                    return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }

                $changes = $result['changes'];
                $files   = $result['files'];
            }

            return $this->docZen->responseAfterEdit($doc, $changes, $files);
        }

        /* Get doc and set menu. */
        $moduleID   = (int)$doc->module;
        $libID      = (int)$doc->lib;
        $lib        = $this->doc->getLibByID($libID);
        $objectType = isset($lib->type) ? $lib->type : 'custom';
        $objectID   = zget($lib, $lib->type, 0);
        if($lib->type == 'custom') $objectID = $lib->parent;

        $libPairs = $this->doc->getLibs($lib->type, '', $doc->lib, $objectID);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($objectType, $objectID, (int)$doc->lib, (int)$appendLib);

        if($lib->type == 'custom' || $lib->type == 'mine') $this->view->spaces = $this->docZen->getAllSpaces($lib->type == 'mine' ? 'onlymine' : 'nomine');
        $this->docZen->setObjectsForEdit($lib->type, $objectID);

        $this->view->title          = $lib->name . $this->lang->hyphen . $this->lang->doc->edit;
        $this->view->doc            = $doc;
        $this->view->optionMenu     = $this->loadModel('tree')->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->type           = $lib->type;
        $this->view->libs           = $libPairs;
        $this->view->lib            = $lib;
        $this->view->libID          = $libID;
        $this->view->libTree        = $this->doc->getLibTree($libID, $libs, $objectType, $moduleID, (int)$objectID, '', 0, $docID);
        $this->view->groups         = $this->loadModel('group')->getPairs();
        $this->view->users          = $this->user->getPairs('noletter|noclosed|nodeleted', $doc->users);
        $this->view->files          = $this->loadModel('file')->getByObject('doc', $docID);
        $this->view->objectType     = $objectType;
        $this->view->object         = $object;
        $this->view->objectID       = $objectID;
        $this->view->objectDropdown = $objectDropdown;
        $this->view->moduleID       = $moduleID;
        $this->view->spaceType      = $objectType;
        $this->view->productID      = $doc->product;
        $this->view->projectID      = $doc->project;
        $this->view->executionID    = $doc->execution;
        $this->view->linkParams     = "objectID={$objectID}&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->otherEditing   = $this->doc->checkOtherEditing($docID);
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
        return $this->sendSuccess(array('load' => $this->session->docList ? $this->session->docList : $this->createLink('doc', 'index')));
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
        return $this->send(array('result' => 'success', 'message' => $action ? $this->lang->doc->cancelCollection : $this->lang->doc->collectSuccess, 'load' => true, 'status' => $action ? 'no' : 'yes'));
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
        if($objectType != 'mine' && empty($objectID)) return print(json_encode(array()));

        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($objectType, $unclosed, '', (int)$objectID);
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs(0, $objectType, $objectID);
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }
        $libItems = array();
        foreach($libPairs as $id => $name) $libItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $moduleItems = array();
        $libID       = key($libPairs);
        if($libID)
        {
            $optionMenu  = $this->loadModel('tree')->getOptionMenu($libID, $docType, $startModuleID = 0);
            foreach($optionMenu as $id => $name) $moduleItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode(array('libs' => $libItems, 'modules' => $moduleItems)));
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
            $libPairs = $this->doc->getLibs($type, $unclosed);
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs(0, 'nolink');
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }

        $libItems = array();
        foreach($libPairs as $id => $name) $libItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $moduleItems = array();
        $libID       = key($libPairs);
        if($libID)
        {
            $optionMenu  = $this->loadModel('tree')->getOptionMenu($libID, $docType, $startModuleID = 0);
            foreach($optionMenu as $id => $name) $moduleItems[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode(array('libs' => $libItems, 'modules' => $moduleItems)));
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
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $searchTitle
     * @access public
     * @return void
     */
    public function showFiles(string $type, int $objectID, string $viewType = '', string $browseType = '', int $param = 0,  string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $searchTitle = '')
    {
        $this->loadModel('file');
        if(empty($viewType)) $viewType = !empty($_COOKIE['docFilesViewType']) ? $this->cookie->docFilesViewType : 'list';
        helper::setcookie('docFilesViewType', $viewType, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $objects = $this->doc->getOrderedObjects($type, 'nomerge', $objectID);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($type, $objectID, 0);

        $object = $this->doc->getObjectByID($type, $objectID);
        if(empty($_POST) && !empty($searchTitle)) $this->post->title = $searchTitle;

        $this->session->set('storyList', $this->app->getURI(true), 'doc');
        $this->session->set('bugList', $this->app->getURI(true), 'doc');

        /* Load pager. */
        $rawMethod = $this->app->rawMethod;
        $this->app->rawMethod = 'showFiles';
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $this->app->rawMethod = $rawMethod;

        $files       = $this->doc->getLibFiles($type, $objectID, $browseType, (int)$param, $orderBy, $pager);
        $fileIcon    = $this->doc->getFileIcon($files);
        $sourcePairs = $this->doc->getFileSourcePairs($files);

        if($this->app->tab != 'doc')
        {
            $objectVar = $this->app->tab . 'ID';
            $this->view->{$objectVar} = $objectID;
        }

        $this->docZen->buildSearchFormForShowFiles($type, $objectID, $viewType, $param);

        $this->view->title          = $object->name;
        $this->view->type           = $type;
        $this->view->object         = $object;
        $this->view->files          = $this->docZen->processFiles($files, $fileIcon, $sourcePairs);
        $this->view->fileIcon       = $fileIcon;
        $this->view->sourcePairs    = $sourcePairs;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager          = $pager;
        $this->view->viewType       = $viewType;
        $this->view->orderBy        = $orderBy;
        $this->view->objectID       = $objectID;
        $this->view->canBeChanged   = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->summary        = $this->doc->summary($files);
        $this->view->libTree        = $this->doc->getLibTree(0, $libs, $type, 0, $objectID);
        $this->view->objectDropdown = $objectDropdown;
        $this->view->searchTitle    = $searchTitle;
        $this->view->linkParams     = "objectID=$objectID&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->spaceType      = $type;
        $this->view->objectType     = $type;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->libID          = 0;
        $this->view->moduleID       = 0;

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
        if(!$doc || !isset($doc->id))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->sendError($this->lang->notFound, $this->inlink('index'));
        }
        if(!$this->doc->checkPrivDoc($doc)) return $this->sendError($this->lang->doc->accessDenied, inlink('index'));

        $lib = $this->doc->getLibByID((int)$doc->lib);
        if(!empty($lib) && $lib->deleted == '1') $appendLib = $doc->lib;
        if($this->app->tab == 'doc' && !empty($lib) && $lib->type == 'execution') $appendLib = $doc->lib;

        $objectType = isset($lib->type) ? $lib->type : 'custom';
        $objectID   = zget($doc, $objectType, 0);
        if($objectType == 'custom') $objectID = $lib->parent;

        /* Get doc. */
        if($docID) $this->doc->createAction($docID, 'view');

        $this->view->title = $doc->title;
        echo $this->fetch('doc', 'app', "type=$objectType&spaceID=$objectID&libID=$lib->id&moduleID=$doc->module&docID=$docID&mode=view");
    }

    /**
     * 产品/项目/执行/团队空间。
     * ProductSpace/ProjectSpace/ExecutionSpace/TeamSpace.
     *
     * @param  string $type        custom|product|project|execution|doctemplate
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
    public function tableContents(string $type = 'custom', int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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
        $queryID = $queryID == 'myQueryID' ? 0 : $queryID;
        $params  = "objectID={$objectID}&libID={$libID}&moduleID=0&browseType=bySearch&orderBy={$orderBy}&param=myQueryID";
        if($this->app->rawMethod == 'tablecontents') $params = "type={$type}&" . $params;
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, $params);
        if($libType == 'api') $this->loadModel('api')->buildSearchForm($lib, $queryID, $actionURL, $libs, $type);
        if($libType != 'api') $this->doc->buildSearchForm($libID, $libs, $queryID, $actionURL, $type);

        $this->docZen->assignApiVarForSpace($type, $browseType, $libType, $libID, $libs, $objectID, $moduleID, $queryID, $orderBy, $param, $recTotal, $recPerPage, $pageID);

        /* For product drop menu. */
        if(in_array($type, array('product', 'project', 'execution')))
        {
            $objectKey = $type . 'ID';
            $this->view->$objectKey = $objectID;
        }

        $this->view->title          = $type == 'custom' ? $this->lang->doc->tableContents : $object->name . $this->lang->hyphen . $this->lang->doc->tableContents;
        $this->view->type           = $type;
        $this->view->objectType     = $type;
        $this->view->spaceType      = $type;
        $this->view->browseType     = $browseType;
        $this->view->isFirstLoad    = $isFirstLoad;
        $this->view->param          = $queryID;
        $this->view->users          = $this->user->getPairs('noletter');
        $this->view->libTree        = $this->doc->getLibTree($libID, $libs, $type, $moduleID, $objectID, $browseType, (int)$param);
        $this->view->libID          = $libID;
        $this->view->moduleID       = $moduleID;
        $this->view->objectDropdown = $objectDropdown;
        $this->view->lib            = $lib;
        $this->view->libType        = $libType;
        $this->view->objectID       = $objectID;
        $this->view->orderBy        = $orderBy;
        $this->view->release        = $browseType == 'byrelease' ? $param : 0;
        $this->view->exportMethod   = $libType == 'api' ? 'export' : $type . '2export';
        $this->view->linkParams     = "objectID={$objectID}&%s&browseType=&orderBy={$orderBy}&param=0";
        $this->view->canUpdateOrder = $lib && !($lib->type == 'custom' && $lib->parent == 0) && common::hasPriv('doc', 'sortDoc') && $orderBy == 'order_asc';

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
    public function productSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = 'list', int $docID = 0, string $search = '')
    {
        echo $this->fetch('doc', 'app', "type=product&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&param=$param");
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
    public function projectSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = 'list', int $docID = 0, string $search = '')
    {
        echo $this->fetch('doc', 'app', "type=project&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&param=$param");
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
    public function teamSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = 'list', int $docID = 0, string $search = '')
    {
        echo $this->fetch('doc', 'app', "type=custom&spaceID=$objectID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&param=$param");
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

            $libID    = (int)$this->post->lib;
            $moduleID = (int)$this->post->module;
            if(empty($libID)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->error->notempty, $this->lang->doc->lib)));

            $response['result']     = 'success';
            $response['closeModal'] = true;
            $response['callback']   = "redirectParentWindow(\"{$this->post->rootSpace}\", {$libID}, {$moduleID}, \"{$this->post->type}\")";
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
        $this->view->spaces    = $this->docZen->getAllSpaces('nomine');

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

        $libData = $this->doc->setMenuByType($objectType, $objectID, 0);

        $this->view->objectType    = $objectType;
        $this->view->objectID      = $objectID;
        $this->view->libID         = !empty($libData[1]) ? $libData[1] : 0;
        $this->view->module        = $module;
        $this->view->method        = $method == 'view' ? $objectType.'space' : $method;
        $this->view->normalObjects = $myObjects + $normalObjects;
        $this->view->closedObjects = $closedObjects;
        $this->view->objectsPinYin = common::convert2Pinyin($myObjects + $normalObjects + $closedObjects);

        $this->display();
    }

    /**
     * Ajax: 获取空间的下拉数据。
     * Ajax: Get the space drop down.
     *
     * @param  int    $libID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetSpaceMenu(int $libID, string $module, string $method, string $extra = 'nomine')
    {
        $this->view->libID  = $libID;
        $this->view->module = $module;
        $this->view->method = $method;
        $this->view->extra  = $extra;
        $this->view->spaces = $this->docZen->getAllSpaces($extra);

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

        $executionPairs = $this->execution->getPairs($projectID, 'all', 'multiple,leaf,noprefix');
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

    /**
     * 文档库排序
     * Doclib sorting.
     *
     * @access public
     * @return void
     */
    public function sortDoclib()
    {
        if($_POST)
        {
            foreach($_POST['orders'] as $id => $order) $this->doc->updateDoclibOrder($id, (int)$order);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * 文档排序
     * Doc sorting.
     *
     * @access public
     * @return void
     */
    public function sortDoc()
    {
        if($_POST)
        {
            $orders = json_decode($this->post->orders, true);
            asort($orders);
            $orders = array_flip($orders);

            /* Sort by sorted id list. */
            $this->doc->updateDocOrder($orders);
        }
    }

    /**
     * 目录排序
     * Catalog sorting.
     *
     * @access public
     * @return void
     */
    public function sortCatalog()
    {
        if($_POST)
        {
            foreach($_POST['orders'] as $id => $order) $this->doc->updateOrder($id, (int)$order, $_POST['type']);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * 删除文档附件。
     * Delete file for doc.
     *
     * @param  int    $docID
     * @param  int    $fileID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteFile(int $docID, int $fileID, string $confirm = 'no')
    {
        $this->loadModel('file');
        if($confirm == 'no')
        {
            $formUrl = inlink('deleteFile', "docID={$docID}&fileID={$fileID}&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->file->confirmDelete}').then((res) => {if(res) $.ajaxSubmit({url: '$formUrl'});});"));
        }
        else
        {
            $this->doc->updateDocFile($docID, $fileID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $file = $this->file->getById($fileID);
            if(in_array($file->extension, $this->config->file->imageExtensions)) $this->action->create($file->objectType, $file->objectID, 'deletedFile', '', $file->title);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }
    }

    /**
     * 移动文档库
     * Move Lib.
     *
     * @param  int    $libID
     * @param  string $targetSpace [int]
     * @access public
     * @return void
     */
    public function moveLib(int $libID, string $targetSpace = '')
    {
        $lib = $this->doc->getLibByID($libID);
        if(empty($targetSpace)) $targetSpace = $lib->parent;

        if(!empty($lib->main)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorEditSystemDoc));

        if(!empty($_POST))
        {
            $data = form::data()
                ->setIF($this->post->acl == 'open', 'groups', '')
                ->setIF($this->post->acl == 'open', 'users', '')
                ->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->doc->moveLib($libID, $data);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->docZen->responseAfterMove($this->post->space, $libID);
        }

        $spaceType = $this->doc->getSpaceType((int)$targetSpace);
        $this->docZen->setAclForCreateLib($spaceType);

        $this->view->title        = $this->lang->doc->moveLibAction;
        $this->view->spaces       = $this->doc->getSubSpacesByType('all');
        $this->view->lib          = $lib;
        $this->view->targetSpace  = $targetSpace;
        $this->view->libType      = $spaceType;
        $this->view->hasOthersDoc = $this->doc->hasOthersDoc($lib);
        $this->view->groups       = $this->loadModel('group')->getPairs();
        $this->view->users        = $this->loadModel('user')->getPairs('nocode|noclosed');
        $this->display();
    }

    /**
     * 移动文档
     * Move Document.
     *
     * @param  int    $docID
     * @param  int    $libID
     * @param  string $space [int]
     * @access public
     * @return void
     */
    public function moveDoc(int $docID, int $libID = 0, string $space = '', string $locate = '')
    {
        $doc = $this->doc->getByID($docID);
        if(!empty($_POST))
        {
            $data = form::data()->setIF($this->post->acl == 'open', 'groups', '')->setIF($this->post->acl == 'open', 'users', '')->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = common::createChanges($doc, $data);
            if($changes)
            {
                $this->doc->doUpdateDoc($docID, $data);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $actionID = $this->loadModel('action')->create('doc', $docID, 'Moved', '', json_encode(array('from' => $doc->lib, 'to' => $data->lib)));
                $this->action->logHistory($actionID, $changes);
            }

            return $this->docZen->responseAfterMove($this->post->space, $data->lib, $locate, $docID);
        }

        if(empty($libID)) $libID = (int)$doc->lib;
        $lib = $this->doc->getLibByID($libID);
        if(empty($space)) $space = $lib->parent;

        $spaceType = $this->doc->getSpaceType((int)$space);
        $libPairs = $this->doc->getLibPairs($spaceType, '', (int)$space);
        if(!isset($libPairs[$libID])) $libID = (int)key($libPairs);

        $this->view->docID      = $docID;
        $this->view->libID      = $libID;
        $this->view->space      = $space;
        $this->view->doc        = $doc;
        $this->view->spaces     = $this->doc->getSubSpacesByType('all');
        $this->view->libPairs   = $libPairs;
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->users      = $this->loadModel('user')->getPairs('nocode|noclosed');
        $this->display();
    }

    /**
     * Ajax: 检查对象权限。
     * Ajax check object priv.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxCheckObjectPriv(string $objectType, int $objectID)
    {
        $accounts = $this->post->users;
        $object   = $this->doc->getObjectByID($objectType, $objectID);
        if(empty($object)) return print('');
        if($object->acl == 'open') return print('');

        $this->loadModel('user');
        $userViews = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->in($accounts)->fetchAll('account');
        $userPairs = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs('account', 'realname');
        $denyUsers = array();
        foreach($accounts as $account)
        {
            if(strpos(",{$this->app->company->admins}", ",{$account},") !== false) continue;

            $userView = zget($userViews, $account, '');
            if(empty($userView)) $userView = $this->user->computeUserView($account);

            if($objectType == 'product'   && strpos(",{$userView->products},", ",{$objectID},") === false) $denyUsers[$account] = zget($userPairs, $account);
            if($objectType == 'project'   && strpos(",{$userView->projects},", ",{$objectID},") === false) $denyUsers[$account] = zget($userPairs, $account);
            if($objectType == 'execution' && strpos(",{$userView->sprints},",  ",{$objectID},") === false) $denyUsers[$account] = zget($userPairs, $account);
        }

        if(empty($denyUsers)) return print('');
        if(isset($this->lang->doc->whitelistDeny[$objectType])) return printf($this->lang->doc->whitelistDeny[$objectType], implode('、', $denyUsers));
    }

    /**
     * Ajax: 检查库权限。
     * Ajax check lib priv.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function ajaxCheckLibPriv(int $libID)
    {
        $accounts = $this->post->users;
        $lib      = $this->doc->getLibByID($libID);
        if(empty($lib)) return print('');
        if($lib->acl == 'open') return print('');
        if($lib->acl == 'default' && strpos("product,project,execution", $lib->type) !== false) return $this->ajaxCheckObjectPriv($lib->type, zget($lib, $lib->type, 0));

        $authAccounts[$lib->addedBy] = $lib->addedBy;
        if($lib->groups) $authAccounts += $this->loadModel('group')->getGroupAccounts(explode(',', $lib->groups));
        if($lib->users)
        {
            foreach(array_filter(explode(',', $lib->users)) as $account) $authAccounts[$account] = $account;
        }

        $userPairs = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs('account', 'realname');
        $denyUsers = array();
        foreach($accounts as $account)
        {
            if(strpos(",{$this->app->company->admins}", ",{$account},") !== false) continue;
            if(!isset($authAccounts[$account])) $denyUsers[$account] = zget($userPairs, $account);
        }

        if(empty($denyUsers)) return print('');
        if(isset($this->lang->doc->whitelistDeny['doc'])) return printf($this->lang->doc->whitelistDeny['doc'], implode('、', $denyUsers));
    }

    /**
     * Doc app view.
     * 文档应用视图。
     *
     * @param  string $type
     * @param  int    $spaceID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $docID
     * @param  string $mode
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $filterType
     * @param  string $search
     * @param  string $param
     * @access public
     * @access public
     * @return void
     */
    public function app(string $type = 'mine', int $spaceID = 0, int $libID = 0, int $moduleID = 0, int $docID = 0, string $mode = 'list', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $filterType = '', string $search = '', string $params = '')
    {
        $this->app->loadLang('file');

        $menuType = $type == 'mine' ? 'my' : ($type == 'custom' ? 'team' : $type);
        $this->lang->doc->menu->{$menuType}['alias'] .= ',' . $this->app->rawMethod;

        $this->view->type         = $type;
        $this->view->spaceID      = $spaceID;
        $this->view->libID        = $libID;
        $this->view->moduleID     = $moduleID;
        $this->view->docID        = $docID;
        $this->view->mode         = $mode;
        $this->view->filterType   = $filterType;
        $this->view->search       = $search;
        $this->view->recTotal     = $recTotal;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->orderBy      = $orderBy;
        $this->view->params       = $params;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->view->activeMenuID = $menuType;

        if(empty($this->view->title)) $this->view->title = $this->lang->doc->spaceList[$type];
        $this->display();
    }

    /**
     * Ajax: Get doc space data.
     * Ajax: 获取文档空间数据。
     *
     * @param  string $type
     * @param  int    $space
     * @access public
     * @return void
     */
    public function ajaxGetSpaceData(string $type = 'custom', int $spaceID = 0, string $picks = '')
    {
        $noPicks = empty($picks);
        $picks   = $noPicks ? '' : ",$picks,";

        list($spaces, $spaceID) = $this->doc->getSpaces($type, $spaceID);
        $data   = array('spaceID' => $spaceID);
        $libs   = $this->doc->getLibsOfSpace($type, $spaceID);
        $libIds = array_keys($libs);

        if($noPicks || strpos($picks, ',space,') !== false)  $data['spaces'] = $spaces;
        if($noPicks || strpos($picks, ',lib,') !== false)    $data['libs'] = array_values($libs);
        if($noPicks || strpos($picks, ',module,') !== false) $data['modules'] = array_values($this->doc->getModulesOfLibs($libIds));
        if($noPicks || strpos($picks, ',doc,') !== false)    $data['docs'] = array_values($this->doc->getDocsOfLibs($libIds + array($spaceID)));

        $this->send($data);
    }

    /**
     * Ajax: Get doc data.
     * Ajax: 获取文档数据。
     *
     * @param  int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function ajaxGetDoc(int $docID, int $version = 0)
    {
        $doc = $this->doc->getByID($docID, $version);
        $doc->lib     = (int)$doc->lib;
        $doc->module  = (int)$doc->module;
        $doc->privs   = array('edit' => common::hasPriv('doc', 'edit', $doc));
        $doc->editors = $this->doc->getEditors($docID);

        $this->send($doc);
    }

    /**
     * Get files by type and objectID.
     * 根据类型和对象ID获取文件列表。
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function ajaxGetFiles(string $type, int $objectID, string $browseType = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 9999, int $pageID = 1)
    {
        /* Load pager. */
        $rawMethod = $this->app->rawMethod;
        $this->app->rawMethod = 'showFiles';
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $this->app->rawMethod = $rawMethod;

        $files       = $this->doc->getLibFiles($type, $objectID, $browseType, 0, $orderBy, $pager);
        $sourcePairs = $this->doc->getFileSourcePairs($files);
        $files       = $this->docZen->processFiles($files, array(), $sourcePairs, true);

        echo json_encode(array_values($files)); // $this->send($files); not work.
    }
}
