<?php
/**
 * The control file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: control.php 933 2010-07-06 06:53:40Z wwccss $
 * @link        http://www.zentao.net
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
     * Upload docs.
     *
     * @param  string     $objectType   product|project|execution|custom
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType       html|word|ppt|excel|attachment
     * @param  string     $from
     * @access public
     * @return void
     */
    public function uploadDocs($objectType, $objectID, $libID, $moduleID = 0, $docType = '', $from = 'doc')
    {
        $linkType = $objectType;

        if(!empty($_POST))
        {
            $doclib   = $this->loadModel('doc')->getLibById($libID);
            $canVisit = true;

            if(!empty($doclib->groups)) $groupAccounts = $this->loadModel('group')->getGroupAccounts(explode(',', $doclib->groups));

            switch($objectType)
            {
                case 'custom':
                    $account = (string)$this->app->user->account;
                    if(($doclib->acl == 'custom' or $doclib->acl == 'private') and strpos($doclib->users, $account) === false and $doclib->addedBy !== $account and !(isset($groupAccounts) and in_array($account, $groupAccounts, true))) $canVisit = false;
                    break;
                case 'product':
                    $canVisit = $this->loadModel('product')->checkPriv($doclib->product);
                    break;
                case 'project':
                    $canVisit = $this->loadModel('project')->checkPriv($doclib->project);
                    break;
                case 'execution':
                    $canVisit = $this->loadModel('execution')->checkPriv($doclib->execution);
                    break;
                default:
                break;
            }
            if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));

            $libID    = $this->post->lib;
            $moduleID = $this->post->module;
            if(empty($libID) and strpos($this->post->module, '_') !== false) list($libID, $moduleID) = explode('_', $this->post->module);
            setcookie('lastDocModule', $moduleID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);

            if($this->post->uploadFormat == 'combinedDocs')
            {
                unset($_POST['uploadFormat']);
                $docResult = $this->doc->create();
                if(!$docResult or dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $docID = $docResult['id'];
                $files = zget($docResult, 'files', '');

                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
                $actionType = 'created';
                $this->action->create('doc', $docID, $actionType, $fileAction);

                if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $docID));
                $params   = "docID=" . $docResult['id'];
                $link     = isonlybody() ? 'parent' : $this->createLink('doc', 'view', $params);
            }
            else
            {
                $docResult = $this->doc->createSeperateDocs();
                if(!$docResult or dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $docsAction = zget($docResult, 'docsAction', '');

                if(!empty($docsAction))
                {
                    foreach($docsAction as $docID => $fileTitle)
                    {
                        $fileAction = $this->lang->addFiles . $fileTitle->title . "\n";
                        $actionType = 'created';
                        $this->action->create('doc', $docID, $actionType, $fileAction);
                    }
                }

                if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
                $link = 'parent';
            }

            $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link);
            return $this->send($response);
        }

        unset($_GET['onlybody']);
        $this->config->showMainMenu = (strpos($this->config->doc->textTypes, $docType) === false or $from == 'template');

        $lib = $libID ? $this->doc->getLibByID($libID) : '';
        if(empty($objectID) and $lib) $objectID = zget($lib, $lib->type, 0);

        /* Get libs and the default lib id. */
        $gobackLink = ($objectID == 0 and $libID == 0) ? $this->createLink('doc', 'tableContents', "type=$linkType") : '';
        $unclosed   = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs       = $this->doc->getLibs($objectType, $extra = "withObject,$unclosed", $libID, $objectID);
        if(!$libID and !empty($libs)) $libID = key($libs);
        if(empty($lib) and $libID) $lib = $this->doc->getLibByID($libID);

        $objects  = array();
        if($linkType == 'project')
        {
            $excludedModel = $this->config->vision == 'lite' ? '' : 'kanban';
            $objects       = $this->project->getPairsByProgram('', 'all', false, 'order_asc', $excludedModel);
            $this->view->executions = array();
            if($lib->type == 'execution')
            {
                $execution = $this->loadModel('execution')->getById($lib->execution);
                $objectID  = $execution->project;
                $libs      = $this->doc->getLibs('execution', $extra = "withObject,$unclosed", $libID, $lib->execution);
                $this->view->execution  = $execution;
                $this->view->executions = array(0 => '') + $this->execution->getPairs($objectID, 'sprint,stage', 'multiple,leaf,noprefix');
            }
        }
        elseif($linkType == 'execution')
        {
            $execution = $this->loadModel('execution')->getById($objectID);
            $objects   = $this->execution->getPairs($execution->project, 'sprint,stage', "multiple,leaf,noprefix");
        }
        elseif($linkType == 'product')
        {
            $objects = $this->loadModel('product')->getPairs();
        }
        elseif($linkType == 'mine')
        {
            $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
        }
        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libs);

        $moduleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $moduleID = $libID . '_' . $moduleID;

        $this->view->title            = empty($lib) ? '' : zget($lib, 'name', '', $lib->name . $this->lang->colon) . $this->lang->doc->uploadDoc;
        $this->view->linkType         = $linkType;
        $this->view->objectType       = $objectType;
        $this->view->objectID         = empty($lib) ? 0 : zget($lib, $lib->type, 0);
        $this->view->libID            = $libID;
        $this->view->lib              = $lib;
        $this->view->libs             = $libs;
        $this->view->objects          = $objects;
        $this->view->gobackLink       = $gobackLink;
        $this->view->libName          = zget($lib, 'name', '');
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->moduleID         = $moduleID;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');
        $this->view->from             = $from;

        $this->display();
    }


    /**
     * Create a doc.
     *
     * @param  string     $objectType   product|project|execution|custom
     * @param  int        $objectID
     * @param  int|string $libID
     * @param  int        $moduleID
     * @param  string     $docType       html|word|ppt|excel
     * @param  string     $from
     * @access public
     * @return void
     */
    public function create($objectType, $objectID, $libID, $moduleID = 0, $docType = '', $from = 'doc')
    {
        $linkType = $objectType;
        if($objectType == 'execution' and $this->app->tab != 'execution') $linkType = 'project';

        if(!empty($_POST))
        {
            $doclib   = $this->loadModel('doc')->getLibByID($libID);
            $canVisit = true;

            if(!empty($doclib->groups)) $groupAccounts = $this->loadModel('group')->getGroupAccounts(explode(',', $doclib->groups));

            switch($objectType)
            {
                case 'custom':
                    $account = (string)$this->app->user->account;
                    if(($doclib->acl == 'custom' or $doclib->acl == 'private') and strpos($doclib->users, $account) === false and $doclib->addedBy !== $account and !(isset($groupAccounts) and in_array($account, $groupAccounts, true))) $canVisit = false;
                    break;
                case 'product':
                    $canVisit = $this->loadModel('product')->checkPriv($doclib->product);
                    break;
                case 'project':
                    $canVisit = $this->loadModel('project')->checkPriv($doclib->project);
                    break;
                case 'execution':
                    $canVisit = $this->loadModel('execution')->checkPriv($doclib->execution);
                    break;
                default:
                break;
            }
            if(!$canVisit) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->accessDenied));

            $libID    = $this->post->lib;
            $moduleID = $this->post->module;
            if(empty($libID) and strpos($this->post->module, '_') !== false) list($libID, $moduleID) = explode('_', $this->post->module);
            helper::setcookie('lastDocModule', $moduleID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

            $docResult = $this->doc->create();
            if(!$docResult or dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $docID = $docResult['id'];
            $files = zget($docResult, 'files', '');
            $lib   = $this->doc->getLibByID($libID);

            $fileAction = '';
            if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
            $actionType = $_POST['status'] == 'draft' ? 'savedDraft' : 'releasedDoc';
            $this->action->create('doc', $docID, $actionType, $fileAction);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $docID));
            $params   = "docID=" . $docResult['id'];
            $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('doc', 'view', $params));

            return $this->send($response);
        }

        unset($_GET['onlybody']);
        $this->config->showMainMenu = (strpos($this->config->doc->textTypes, $docType) === false or $from == 'template');

        $lib = $libID ? $this->doc->getLibByID($libID) : '';
        if(empty($objectID) and $lib) $objectID = zget($lib, $lib->type, 0);

        /* Get libs and the default lib id. */
        $gobackLink = ($objectID == 0 and $libID == 0) ? $this->createLink('doc', 'tableContents', "type=$linkType") : '';
        $unclosed   = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
        $libs       = $this->doc->getLibs($objectType, $extra = "withObject,$unclosed", $libID, $objectID);
        if(!$libID and !empty($libs)) $libID = key($libs);
        if(empty($lib) and $libID) $lib = $this->doc->getLibByID($libID);

        $objects  = array();
        if($linkType == 'project')
        {
            $excludedModel = $this->config->vision == 'lite' ? '' : 'kanban';
            $objects       = $this->project->getPairsByProgram(0, 'all', false, 'order_asc', $excludedModel);
            $this->view->executions = array();
            if($lib->type == 'execution')
            {
                $execution = $this->loadModel('execution')->getById($lib->execution);
                $objectID  = $execution->project;
                $libs      = $this->doc->getLibs('execution', $extra = "withObject,$unclosed", $libID, $lib->execution);
                $this->view->execution  = $execution;
                $this->view->executions = array(0 => '') + $this->execution->getPairs($objectID, 'sprint,stage', 'multiple,leaf,noprefix');
            }
        }
        elseif($linkType == 'execution')
        {
            $execution = $this->loadModel('execution')->getById($objectID);
            $objects   = $this->execution->getPairs($execution->project, 'sprint,stage', "multiple,leaf,noprefix");
        }
        elseif($linkType == 'product')
        {
            $objects = $this->loadModel('product')->getPairs();
        }
        elseif($linkType == 'mine')
        {
            $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
        }
        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libs);

        $moduleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $moduleID = $libID . '_' . $moduleID;

        $this->view->title            = zget($lib, 'name', '', $lib->name . $this->lang->colon) . $this->lang->doc->create;
        $this->view->linkType         = $linkType;
        $this->view->objectType       = $objectType;
        $this->view->objectID         = zget($lib, $lib->type, 0);
        $this->view->libID            = $libID;
        $this->view->lib              = $lib;
        $this->view->libs             = $libs;
        $this->view->objects          = $objects;
        $this->view->gobackLink       = $gobackLink;
        $this->view->libName          = zget($lib, 'name', '');
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->moduleID         = $moduleID;
        $this->view->docType          = $docType;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('nocode|noclosed|nodeleted');
        $this->view->from             = $from;

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
        $doc = $this->doc->getById($docID);

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
                $action = 'Commented';
                if(!empty($changes))
                {
                    $newType = $_POST['status'];
                    if($doc->status == 'draft' and $newType == 'normal') $action = 'releasedDoc';
                    if($doc->status == $newType) $action = 'Edited';
                }

                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
                $actionID = $this->action->create('doc', $docID, $action, $fileAction . $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $link     = $this->createLink('doc', 'view', "docID={$docID}");
            $oldLib   = $doc->lib;
            $doc      = $this->doc->getById($docID);
            $lib      = $this->doc->getLibByID($doc->lib);
            $objectID = zget($lib, $lib->type, 0);
            if(!$this->doc->checkPrivDoc($doc))
            {
                $moduleName = 'doc';
                if($this->app->tab == 'execution')
                {
                    $moduleName = 'execution';
                    $methodName = 'doc';
                }
                else
                {
                    $methodName = zget($this->config->doc->spaceMethod, $lib->type);
                }
                $params = "objectID=$objectID&libID={$doc->lib}";
                $link   = $this->createLink($moduleName, $methodName, $params);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        /* Get doc and set menu. */
        $libID = $doc->lib;

        if($doc->contentType == 'markdown') $this->config->doc->markdown->edit = array('id' => 'content', 'tools' => 'toolbar');

        $lib        = $this->doc->getLibByID($libID);
        $objectType = $lib->type;
        $objectID   = zget($lib, $objectType, 0);

        $libs    = $this->doc->getLibs($objectType, 'withObject', $libID, $objectID);
        $objects = array();
        if($objectType == 'project')
        {
            $objects = $this->project->getPairsByProgram(0, 'all', false, 'order_asc', 'kanban');
        }
        elseif($objectType == 'execution')
        {
            $execution = $this->loadModel('execution')->getById($objectID);
            $objects   = $this->execution->resetExecutionSorts($objects, array(), array(), $execution->project);
        }
        elseif($objectType == 'product')
        {
            $objects = $this->loadModel('product')->getPairs();
        }
        elseif($objectType == 'mine')
        {
            $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
        }
        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libs);

        $this->config->showMainMenu = strpos(',html,markdown,text,', ",{$doc->type},") === false;

        $this->view->title            = $lib->name . $this->lang->colon . $this->lang->doc->edit;
        $this->view->doc              = $doc;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->type             = $objectType;
        $this->view->libs             = $libs;
        $this->view->objects          = $objects;
        $this->view->lib              = $lib;
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->view->users            = $this->user->getPairs('noletter|noclosed|nodeleted', $doc->users);
        $this->view->from             = $from;
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
        $this->loadModel('action');
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
            $docContent->files = trim(str_replace(",{$fileID},", ',', ",{$docContent->files},"), ',');
            $docContent->version += 1;
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($docContent->version)->where('id')->eq($docID)->exec();

            $file = $this->file->getById($fileID);
            if(in_array($file->extension, $this->config->file->imageExtensions)) $this->action->create($file->objectType, $file->objectID, 'deletedFile', '', $extra = $file->title);
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
        $table = '';
        if($objectType == 'doc') $table = TABLE_DOC;
        if(empty($table)) return;

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
     * Ajax get modules by object.
     *
     * @param string $objectType project|product|custom|mine
     * @param int    $objectID
     * @param string $docType     doc|api
     * @access public
     * @return void
     */
    public function ajaxGetModules($objectType, $objectID, $docType = 'doc')
    {
        if(empty($objectID)) return print(html::select('module', array(), '', "class='form-control'"));

        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($objectType, $extra = "withObject,$unclosed", '', $objectID);
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs('', $objectType, $objectID);
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }

        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libPairs, $docType);

        $items = array();
        foreach($moduleOptionMenu as $id => $name)
        {
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode($items));
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
     * @param  string $space       mine|product|project|nolink|custom
     * @param  string $docType     doc|api
     * @access public
     * @return void
     */
    public function ajaxGetLibsByType($space, $docType = 'doc')
    {
        $libPairs = array();
        if($docType == 'doc')
        {
            $unclosed = strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '';
            $libPairs = $this->doc->getLibs($space, "withObject,$unclosed");
        }
        elseif($docType == 'api')
        {
            $libs     = $this->doc->getApiLibs('', 'nolink');
            $libPairs = array();
            foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
        }

        $moduleOptionMenu = $this->doc->getLibsOptionMenu($libPairs, $docType);

        $items = array();
        foreach($moduleOptionMenu as $id => $name)
        {
            if(empty($id)) continue;
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }
        return print(json_encode($items));
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
            ->andWhere('status')->eq('normal')
            ->andWhere('id')->in($docIdList)
            ->orderBy('order_asc,id_desc')
            ->fetchPairs();

        return print(html::select('doc', $docs, '', "class='form-control chosen'"));
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
        $doclib        = $this->doc->getLibByID($doclibID);
        $doc           = $docID ? $this->doc->getById($docID) : null;
        $users         = $this->user->getPairs('noletter|noempty|noclosed');
        $selectedUser  = $docID ? $doc->users : $doclib->users;
        $selectedGroup = $docID ? $doc->groups : $doclib->groups;
        $dropDirection = "data-drop-direction='top'";

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
    public function showFiles($type, $objectID, $viewType = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $searchTitle = '')
    {
        $this->loadModel('file');
        if(empty($viewType)) $viewType = !empty($_COOKIE['docFilesViewType']) ? $this->cookie->docFilesViewType : 'list';
        helper::setcookie('docFilesViewType', $viewType, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $objects = $this->doc->getOrderedObjects($type, 'nomerge', $objectID);
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($type, $objectID, 0);

        $table  = $this->config->objectTables[$type];
        $object = $this->dao->select('id,name,status')->from($table)->where('id')->eq($objectID)->fetch();

        if(empty($_POST) and !empty($searchTitle)) $this->post->title = $searchTitle;

        /* Load pager. */
        $rawMethod = $this->app->rawMethod;
        $this->app->rawMethod = 'showFiles';
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $this->app->rawMethod = $rawMethod;

        $files       = $this->doc->getLibFiles($type, $objectID, $orderBy, $pager);
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
        $this->view->defaultNestedShow = $this->docZen->getDefacultNestedShow(0, 0, $type);

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
        $doc = $this->doc->getById($docID, $version, true);
        if(!$this->doc->checkPrivDoc($doc))
        {
            echo(js::alert($this->lang->doc->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(inlink('index')));
            helper::end(print(js::locate(inlink('index'))));
        }

        if(!$doc or !isset($doc->id))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->inlink('index')));
        }

        $lib = $this->doc->getLibByID($doc->lib);
        if(!empty($lib) and $lib->deleted == '1') $appendLib = $doc->id;

        $objectType = isset($lib->type) ? $lib->type : 'custom';
        $type       = $objectType == 'execution' && $this->app->tab != 'execution' ? 'project' : $objectType;
        $objectID   = zget($doc, $type, 0);
        if($objectType == 'custom') $this->lang->doc->menu->custom['alias'] = 'teamspace,view';
        list($libs, $libID, $object, $objectID, $objectDropdown) = $this->doc->setMenuByType($type, $objectID, $doc->lib, $appendLib);

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

        if(isset($doc) and $doc->type == 'text')
        {
            /* Split content into an array. */
            $content = preg_replace('/(<(h[1-6])[\S\s]*?\>[\S\s]*?<\/\2>)/', "\$1\n", $doc->content);
            $content = explode("\n", $content);

            /* Get the head element, for example h1,h2,etc. */
            $includeHeadElement = array();
            foreach($content as $index => $element)
            {
                preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

                if(isset($headElement[1]) and !in_array($headElement[1], $includeHeadElement) and strip_tags($headElement[3]) != '') $includeHeadElement[] = $headElement[1];
            }

            /* Get the two elements with the highest rank. */
            sort($includeHeadElement);

            if($includeHeadElement)
            {
                $topLevel    = (int)ltrim($includeHeadElement[0], 'h');
                $outlineList = $this->docZen->buildOutlineList($topLevel, $content, $includeHeadElement);
                $outlineTree = $this->docZen->buildOutlineTree($outlineList);
                $this->view->outlineTree = $outlineTree;

                foreach($content as $index => $element)
                {
                    preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

                    /* The current element is existed, the element is in the includeHeadElement, and the text in the element is not null. */
                    if(isset($headElement[1]) && in_array($headElement[1], $includeHeadElement) && strip_tags($headElement[3]) != '')
                    {
                        $content[$index] = str_replace('<' . $headElement[1] . $headElement[2] . '>', '<' . $headElement[1] . $headElement[2] . " id='anchor{$index}'" . '>', $content[$index]);
                    }
                }

                $doc->content = implode("\n", $content);
            }
        }

        if($this->app->tab != 'execution' and !empty($doc->execution)) $object = $this->execution->getByID($doc->execution);

        $this->view->title             = $this->lang->doc->common . $this->lang->colon . $doc->title;
        $this->view->docID             = $docID;
        $this->view->doc               = $doc;
        $this->view->version           = $version;
        $this->view->object            = $object;
        $this->view->objectID          = $objectID;
        $this->view->objectType        = $objectType;
        $this->view->type              = $type;
        $this->view->libID             = $libID;
        $this->view->lib               = isset($libs[$libID]) ? $libs[$libID] : new stdclass();
        $this->view->libs              = $this->doc->getLibsByObject($type, (int)$objectID);
        $this->view->canBeChanged      = common::canModify($type, $object); // Determines whether an object is editable.
        $this->view->actions           = $docID ? $this->action->getList('doc', $docID) : array();
        $this->view->users             = $this->user->getPairs('noclosed,noletter');
        $this->view->autoloadPage      = $this->doc->checkAutoloadPage($doc);
        $this->view->libTree           = $this->doc->getLibTree((int)$libID, (array)$libs, $type, $doc->module, (int)$objectID, '', 0, $docID);
        $this->view->preAndNext        = $this->loadModel('common')->getPreAndNextObject('doc', $docID);
        $this->view->moduleID          = $doc->module;
        $this->view->objectDropdown    = $objectDropdown;
        $this->view->canExport         = ($this->config->edition != 'open' && common::hasPriv('doc', $type . '2export'));
        $this->view->exportMethod      = $type . '2export';
        $this->view->editors           = $this->doc->getEditors($docID);
        $this->view->linkParams        = "objectID=$objectID&%s&browseType=&orderBy=status,id_desc&param=0";
        $this->view->spaceType         = $objectType;
        $this->view->defaultNestedShow = $this->docZen->getDefacultNestedShow($libID, $doc->module);

        $this->display();
    }

    /**
     * Show team Space.
     *
     * @param  string $type custom|product|project|execution
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
    public function tableContents($type = 'custom', $objectID = 0, $libID = 0, $moduleID = 0, $browseType = 'all', $orderBy = 'status,id_desc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('createProjectLocate', $uri, 'doc');
        $this->session->set('structList', $uri, 'doc');
        $this->session->set('spaceType', $type, 'doc');
        $this->session->set('docList', $uri, 'doc');

        $docSpaceParam = new stdclass();
        $docSpaceParam->type       = $type;
        $docSpaceParam->objectID   = $objectID;
        $docSpaceParam->libID      = $libID;
        $docSpaceParam->moduleID   = $moduleID;
        $docSpaceParam->browseType = $browseType;
        $docSpaceParam->param      = $param;
        setCookie("docSpaceParam", json_encode($docSpaceParam), $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        if($moduleID) $libID = $this->tree->getById($moduleID)->root;
        $isFirstLoad = $libID == 0 ? true : false;
        if(empty($browseType)) $browseType = 'all';

        $libData = $this->doc->setMenuByType($type, $objectID, $libID);
        if(is_string($libData)) $this->locate($libData);

        list($libs, $libID, $object, $objectID, $objectDropdown) = $libData;

        $libID   = (int)$libID;
        $title   = $type == 'custom' ? $this->lang->doc->tableContents : $object->name . $this->lang->colon . $this->lang->doc->tableContents;
        $lib     = $this->doc->getLibByID($libID);
        $libType = isset($lib->type) && $lib->type == 'api' ? 'api' : 'lib';

        /* Build the search form. */
        $queryID = $browseType == 'bySearch' ? (int)$param : 0;
        $params  = "objectID=$objectID&libID=$libID&moduleID=0&browseType=bySearch&orderBy=$orderBy&param=myQueryID";
        if($this->app->rawMethod == 'tablecontents') $params = "type=$type&" . $params;
        $actionURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, $params);
        if($libType == 'api')
        {
            $this->loadModel('api')->buildSearchForm($lib, $queryID, $actionURL, $libs, $type);
        }
        else
        {
            $this->doc->buildSearchForm($libID, $libs, $queryID, $actionURL, $type);
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if($libType == 'api')
        {
            $this->loadModel('api');
            $this->session->set('objectName', $this->lang->doc->api, 'doc');

            $this->view->libs    = $libs;
            $this->view->apiID   = 0;
            $this->view->release = 0;
            $this->view->apiList = $browseType == 'bySearch' ? $this->api->getApiListBySearch($libID, $queryID, $type, array_keys($libs)) : $this->api->getListByModuleId($libID, $moduleID, $param, $pager);
        }
        else
        {
            if(in_array($type, array('product', 'project'))) $this->session->set('objectName', $this->lang->doc->common, 'doc');
            $this->view->docs = $browseType == 'bySearch' ? $this->doc->getDocsBySearch($type, $objectID, $libID, $queryID, $orderBy, $pager) : $this->doc->getDocs($libID, $moduleID, $browseType, $orderBy, $pager);
        }

        $apiObjectType = $type == 'product' || $type == 'project' ? $type : '';
        $apiObjectID   = $apiObjectType ? $objectID : 0;
        $apiLibs       = $apiObjectType ? $this->doc->getApiLibs(0, $apiObjectType, $apiObjectID) : array();

        $canExport = $libType == 'api' ? common::hasPriv('api', 'export') : common::hasPriv('doc', $type . '2export');
        if($this->config->edition == 'open') $canExport = false;

        /* For product drop menu. */
        if(in_array($type, array('product', 'project', 'execution')))
        {
            $objectKey = $type . 'ID';
            $this->view->$objectKey = $objectID;
        }

        $executionID = $type == 'project' && $lib->type == 'execution' ? $lib->execution : 0;

        $this->view->title             = $title;
        $this->view->type              = $type;
        $this->view->objectType        = $type;
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
        $this->view->pager             = $pager;
        $this->view->objectID          = $objectID;
        $this->view->orderBy           = $orderBy;
        $this->view->release           = $browseType == 'byrelease' ? $param : 0;
        $this->view->canExport         = $canExport;
        $this->view->exportMethod      = $libType == 'api' ? 'export' : $type . '2export';
        $this->view->apiLibID          = key($apiLibs);
        $this->view->spaceType         = $type;
        $this->view->linkParams        = "objectID=$objectID&%s&browseType=&orderBy=$orderBy&param=0";
        $this->view->defaultNestedShow = $this->docZen->getDefacultNestedShow($libID, $moduleID, $type, $executionID);

        $this->display();
    }

    /**
     * Show product space.
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
    public function productSpace(int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'status,id_desc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $products = $this->product->getPairs('nocode');
        $objectID = $this->product->checkAccess($objectID, $products);

        echo $this->fetch('doc', 'tableContents', "type=product&objectID=$objectID&libID=$libID&moduleID=$moduleID&browseType=$browseType&orderBy=$orderBy&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Show project space.
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
    public function projectSpace($objectID = 0, $libID = 0, $moduleID = 0, $browseType = 'all', $orderBy = 'status,id_desc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $projects = $this->project->getPairsByProgram();
        $objectID = $this->project->checkAccess($objectID, $projects);

        echo $this->fetch('doc', 'tableContents', "type=project&objectID=$objectID&libID=$libID&moduleID=$moduleID&browseType=$browseType&orderBy=$orderBy&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Show team space.
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
    public function teamSpace($objectID = 0, $libID = 0, $moduleID = 0, $browseType = 'all', $orderBy = 'status,id_desc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('doc', 'tableContents', "type=custom&objectID=$objectID&libID=$libID&moduleID=$moduleID&browseType=$browseType&orderBy=$orderBy&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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

        $this->view->spaceList  = $spaceList;
        $this->view->typeList   = $typeList;
        $this->view->products   = $products;
        $this->view->projects   = $projects;

        $this->display();
    }

    /**
     * Ajax get objectType drop menu.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($objectType, $objectID, $module, $method)
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
     * Ajax Get the execution drop down by the projectID.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function ajaxGetExecution($projectID)
    {
        $json  = array();
        $items = array();

        $executionPairs = $this->execution->getPairs($projectID, 'sprint,stage', 'multiple,leaf,noprefix');
        foreach($executionPairs as $id => $name) $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);

        $json['items']   = $items;
        $json['project'] = $this->project->getById($projectID);
        return print(json_encode($json));
    }

    /**
     * Edit a catalog.
     *
     * @param  int    $moduleID
     * @param  string $type doc|api
     * @access public
     * @return void
     */
    public function editCatalog($moduleID, $type)
    {
        echo $this->fetch('tree', 'edit', "moduleID=$moduleID&type=$type");
    }

    /**
     * Delete a catalog.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function deleteCatalog($moduleID)
    {
        echo $this->fetch('tree', 'delete', "moduleID=$moduleID&confirm=yes");
    }

    /**
     * Display setting.
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
     * Catalog sort.
     *
     * @access public
     * @return void
     */
    public function sortCatalog()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            foreach($_POST['orders'] as $id => $order)
            {
                $this->dao->update(TABLE_MODULE)->set('`order`')->eq($order)->where('id')->eq($id)->andWhere('type')->eq('doc')->exec();
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success'));
        }

    }
}
