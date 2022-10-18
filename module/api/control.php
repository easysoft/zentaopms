<?php
/**
 * The control file of api of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: control.php 5143 2013-07-15 06:11:59Z thanatos thanatos915@163.com $
 * @link        http://www.zentao.net
 */
class api extends control
{
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->user   = $this->loadModel('user');
        $this->doc    = $this->loadModel('doc');
        $this->action = $this->loadModel('action');
    }

    /**
     * Api doc index page.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $apiID
     * @param  int    $version
     * @param  int    $release
     * @param  int    $appendLib
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return void
     */
    public function index($libID = 0, $moduleID = 0, $apiID = 0, $version = 0, $release = 0, $appendLib = 0, $browseType = '', $param = 0)
    {
        /* Get all api doc libraries. */
        $libs = $this->doc->getApiLibs($appendLib);
        if($libID == 0 and !empty($libs)) $libID = key($libs);

        /* Get an api doc. */
        if($apiID > 0)
        {
            $api = $this->api->getLibById($apiID, $version, $release);
            if($api)
            {
                $moduleID  = $api->module;
                $libID     = $api->lib;
                $api->desc = htmlspecialchars_decode($api->desc);

                $this->view->api      = $api;
                $this->view->apiID    = $apiID;
                $this->view->version  = $version;
                $this->view->typeList = $this->api->getTypeList($api->lib);
                $this->view->actions  = $apiID ? $this->action->getList('api', $apiID) : array();
            }
        }
        else
        {
            /* Get module api list. */
            $apiList = $this->api->getListByModuleId($libID, $moduleID, $release);

            $this->view->apiList  = $apiList;
            $this->view->typeList = $this->api->getTypeList($libID);
        }

        $lib       = $this->doc->getLibById($libID);
        $appendLib = (!empty($lib) and $lib->deleted == '1') ? $libID : 0;

        /* Generate bread crumbs dropMenu. */
        if($libs) $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID, $release);

        $this->setMenu($libID, $moduleID);
        $this->lang->TRActions = '<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> ' . $this->lang->api->search . '</a>' . $this->lang->TRActions;

        /* Build the search form. */
        $queryID   = $browseType == 'bySearch' ? (int)$param : 0;
        $actionURL = $this->createLink('api', 'index', "libID=$libID&moduleID=0&apiID=0&version=0&release=0&appendLib=0&browseType=bySearch&param=myQueryID");
        $this->api->buildSearchForm($lib,$queryID, $actionURL);

        if($browseType == 'bySearch')
        {
            $this->view->apiList  = $this->api->getApiListBySearch($libID, $queryID);
            $this->view->typeList = $this->api->getTypeList($libID);
        }

        $this->view->lib        = $lib;
        $this->view->isRelease  = $release > 0;
        $this->view->release    = $release;
        $this->view->title      = $this->lang->api->pageTitle;
        $this->view->libID      = $libID;
        $this->view->apiID      = $apiID;
        $this->view->libs       = $libs;
        $this->view->browseType = $browseType;
        $this->view->moduleTree = $libID ? $this->doc->getApiModuleTree($libID, $apiID, $release, $moduleID) : '';
        $this->view->users      = $this->user->getPairs('noclosed,noletter');

        $this->display();
    }

    /**
     * Release list.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function releases($libID, $orderBy = 'id', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $libs = $this->doc->getApiLibs();
        $this->app->loadClass('pager', $static = true);
        $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID);

        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort     = common::appendOrder($orderBy);
        $releases = $this->api->getReleaseByQuery($libID, $pager, $sort);

        $this->view->releases = $releases;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->view->title    = $this->lang->api->managePublish;
        $this->view->libID    = $libID;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * @param  int    $libID
     * @param  int    $id
     * @param  string $confirm
     */
    public function deleteRelease($libID, $id = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->custom->notice->confirmDelete, $this->createLink('api', 'deleteRelease', "libID=$libID&id=$id&confirm=yes"), ''));
        }
        else
        {
            $this->api->deleteRelease($id);
            if(dao::isError()) return $this->sendError(dao::getError());
            return print(js::locate(inlink('releases', "libID=$libID"), 'parent'));
        }
    }

    /**
     * Create a api doc lib.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function createRelease($libID)
    {
        $lib = $this->doc->getLibById($libID);

        if(!empty($_POST))
        {
            $data = fixer::input('post')
                ->add('lib', $libID)
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', helper::now())
                ->get();

            /* Check version is exist. */
            if(!empty($data->version) and $this->api->getRelease($libID, 'byVersion', $data->version))
            {
                return $this->sendError($this->lang->api->noUniqueVersion);
            }
            $this->api->publishLib($data);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('locate' => $this->createLink('api', 'index', "libID=$libID")));
        }

        $libName = isset($lib->name) ? $lib->name : '';
        $this->view->title = $this->lang->api->createRelease . $libName;

        $this->display();
    }

    /**
     * Api doc global struct page.
     *
     * @param  int    $libID
     * @param  int    $releaseID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function struct($libID = 0, $releaseID = 0, $orderBy = 'id', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $libs = $this->doc->getApiLibs();
        $this->app->loadClass('pager', $static = true);
        $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID, $releaseID);

        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        if($releaseID)
        {
            $release = $this->api->getRelease($libID, 'byId', $releaseID);
            $structs = $this->api->getStructListByRelease($release, '1 = 1 ', $sort);
        }
        else
        {
            $structs = $this->api->getStructByQuery($libID, $pager, $sort);
        }

        common::setMenuVars('doc', $libID);
        $this->view->libID     = $libID;
        $this->view->releaseID = $releaseID;
        $this->view->structs   = $structs;
        $this->view->orderBy   = $orderBy;
        $this->view->title     = $this->lang->api->struct;
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * Create struct page.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function createStruct($libID = 0)
    {
        common::setMenuVars('doc', $libID);
        if(!empty($_POST))
        {
            $now    = helper::now();
            $userId = $this->app->user->account;
            $data   = fixer::input('post')
                ->add('lib', $libID)
                ->skipSpecial('attribute')
                ->add('addedBy', $userId)
                ->add('addedDate', $now)
                ->add('editedBy', $userId)
                ->add('editedDate', $now)
                ->get();


            $id = $this->api->createStruct($data);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->action->create('apistruct', $id, 'Created');

            return $this->sendSuccess(array('locate' => helper::createLink('api', 'struct', "libID=$libID")));
        }

        $options = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = array('label' => $item, 'value' => $key);
        }
        $this->view->typeOptions = $options;
        $this->view->title       = $this->lang->api->createStruct;
        $this->view->gobackLink  = $this->createLink('api', 'struct', "libID=$libID");

        $this->display();
    }

    /**
     * Edit struct
     *
     * @param  int $libID
     * @param  int $structID
     * @access public
     * @return void
     */
    public function editStruct($libID, $structID)
    {
        common::setMenuVars('doc', $libID);
        $struct = $this->api->getStructByID($structID);

        if(!empty($_POST))
        {
            $changes = $this->api->updateStruct($structID);
            if(dao::isError()) return $this->sendError(dao::getError());
            $actionID = $this->action->create('apistruct', $structID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'struct', "libID={$struct->lib}")));
        }

        $options = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = array('label' => $item, 'value' => $key);
        }

        $this->view->struct      = $struct;
        $this->view->typeOptions = $options;
        $this->view->title       = $struct->name . $this->lang->api->edit;
        $this->display();
    }

    /**
     * Delete a struct.
     *
     * @param  int    $libID
     * @param  int    $structID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteStruct($libID, $structID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->custom->notice->confirmDelete, $this->createLink('api', 'deleteStruct', "libID=$libID&structID=$structID&confirm=yes"), ''));
        }
        else
        {
            $this->api->delete(TABLE_APISTRUCT, $structID);
            if(dao::isError()) return $this->sendError(dao::getError());
            return print(js::locate(inlink('struct', "libID=$libID"), 'parent'));
        }
    }

    /**
     * Create a api doc library.
     *
     * @param  string normal|demo
     * @access public
     * @return void
     */
    public function createLib($type = 'normal')
    {
        if(!empty($_POST))
        {
            $libID = $type == 'demo' ? $this->api->createDemoData($this->post->name, $this->post->baseUrl) : $this->doc->createApiLib();
            if(dao::isError())  return $this->sendError(dao::getError());

            /* If the created api library of imported zentao api library, return directly. */
            if($type == 'demo') return $this->sendSuccess(array('locate' => $this->createLink('api', 'index', "libID=$libID")));

            /* Record action for create api library. */
            $this->action->create('docLib', $libID, 'Created');

            if(!helper::isAjaxRequest()) return print(js::locate($this->createLink('api', 'index', "libID=$libID"), 'parent.parent'));
            return $this->sendSuccess(array('locate' => $this->createLink('api', 'index', "libID=$libID")));
        }

        $this->view->type   = $type;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->user->getPairs('nocode|noclosed');

        $this->display();
    }

    /**
     * Edit an api doc library
     *
     * @param  int     $id
     * @access public
     * @return void
     */
    public function editLib($id)
    {
        $doc = $this->doc->getLibById($id);

        if(!empty($_POST))
        {
            $lib = fixer::input('post')->join('groups', ',')->join('users', ',')->get();

            if($lib->acl == 'private') $lib->users = $this->app->user->account;
            $this->doc->updateApiLib($id, $doc, $lib);

            if(dao::isError()) return $this->sendError(dao::getError());

            $res = array(
                'message'    => $this->lang->saveSuccess,
                'closeModal' => true,
                'callback'   => "redirectParentWindow($id)",
            );
            return $this->sendSuccess($res);
        }

        $this->view->doc    = $doc;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->user->getPairs('nocode|noclosed');

        $this->display();
    }


    /**
     * Delete api library.
     *
     * @param  int    $libID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->api->confirmDeleteLib, $this->createLink('api', 'deleteLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->doc->delete(TABLE_DOCLIB, $libID);
            if(isonlybody())
            {
                unset($_GET['onlybody']);
                return print(js::locate($this->createLink('api', 'index'), 'parent.parent'));
            }

            return print(js::locate($this->createLink('api', 'index'), 'parent'));
        }
    }

    /**
     * Edit library.
     *
     * @param  int     $apiID
     * @access public
     * @return void
     */
    public function edit($apiID)
    {
        if(helper::isAjaxRequest() && !empty($_POST))
        {
            $changes = $this->api->update($apiID);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($changes)
            {
                $actionID = $this->action->create('api', $apiID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('locate' => helper::createLink('api', 'index', "libID=0&moduleID=0&apiID=$apiID")));
        }

        $api = $this->api->getLibById($apiID);
        if($api)
        {
            $this->view->api  = $api;
            $this->view->edit = true;
        }

        $this->setMenu($api->lib);

        $this->getTypeOptions($api->lib);

        $this->view->title            = $api->title . $this->lang->api->edit;
        $this->view->gobackLink       = $this->createLink('api', 'index', "libID={$api->lib}&moduleID={$api->module}&apiID=$apiID");
        $this->view->user             = $this->app->user->account;
        $this->view->allUsers         = $this->loadModel('user')->getPairs('devfirst|noclosed');;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($api->lib, 'api', $startModuleID = 0);
        $this->view->moduleID         = $api->module ? (int)$api->module : (int)$this->cookie->lastDocModule;

        $this->display();
    }

    /**
     * Create an api doc.
     *
     * @param  int $libID
     * @param  int $moduleID
     * @access public
     * @return void
     */
    public function create($libID, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $api = $this->api->create();
            if($api === false) return $this->sendError(dao::getError());

            $this->action->create('api', $api->id, 'Created');
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'index', "libID={$api->lib}&moduleID=0&apiID={$api->id}")));
        }

        $libs = $this->doc->getLibs('api', '', $libID);
        if(!$libID and !empty($libs)) $libID = key($libs);

        $this->setMenu($libID);

        $lib     = $this->doc->getLibByID($libID);
        $libName = isset($lib->name) ? $lib->name . $this->lang->colon : '';

        $this->getTypeOptions($libID);
        $this->view->gobackLink       = $this->createLink('api', 'index', "libID=$libID&moduleID=$moduleID");
        $this->view->user             = $this->app->user->account;
        $this->view->allUsers         = $this->loadModel('user')->getPairs('devfirst|noclosed');
        $this->view->libID            = $libID;
        $this->view->libName          = $lib->name;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'api', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->libs             = $libs;
        $this->view->title            = $libName . $this->lang->api->create;
        $this->view->users            = $this->user->getPairs('nocode');

        $this->display();
    }

    /**
     * Delete an api.
     *
     * @param  int    $apiID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($apiID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->api->confirmDelete, inlink('delete', "apiID=$apiID&confirm=yes")));
        }
        else
        {
            $api = $this->api->getLibById($apiID);
            $this->api->delete(TABLE_API, $apiID);

            if(dao::isError())
            {
                $this->sendError(dao::getError());
            }
            else
            {
                $this->sendSuccess(array('locate' => $this->createLink('api', 'index', "libID=$api->lib&module=$api->module")));
            }
        }
    }

    /**
     * AJAX: Get params type options by scope.
     *
     * @access public
     * @return void
     */
    public function ajaxGetParamsTypeOptions()
    {
        $options = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = array('label' => $item, 'value' => $key);
        }
        $this->sendSuccess(array('data' => $options));
    }

    /**
     * AJAX: Get ref options.
     *
     * @param  int     $libID
     * @param  int     $structID
     * @access public
     * @return void
     */
    public function ajaxGetRefOptions($libID = 0, $structID = 0)
    {
        $res = $this->api->getStructListByLibID($libID);

        $options = array();
        foreach($res as $item)
        {
            if($item->id == $structID) continue;

            $options[$item->id] = $item->name;
        }

        echo html::select('refTarget', $options, '', "class='form-control'");
    }

    /**
     * AJAX: Get ref info.
     *
     * @param  int    $refID
     * @access public
     * @return void
     */
    public function ajaxGetRefInfo($refID = 0)
    {
        $info = $this->api->getStructByID($refID);
        $this->sendSuccess(array('info' => $info));
    }

    /**
     * AJAX: Get all child module.
     *
     * @param  int     $libID
     * @param  string  $type
     * @access public
     * @return void
     */
    public function ajaxGetChild($libID, $type = 'module')
    {
        $this->loadModel('tree');
        $childModules = $this->tree->getOptionMenu($libID, 'api');
        $select       = ($type == 'module') ? html::select('module', $childModules, '0', "class='form-control chosen'") : html::select('parent', $childModules, '0', "class='form-control chosen'");
        echo $select;
    }

    /**
     * Set doc menu by method name.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    private function setMenu($libID = 0, $moduleID = 0)
    {
        common::setMenuVars('doc', $libID);

        /* Global struct link. */
        $menu = '';

        if($libID and common::hasPriv('api', 'createRelease'))
        {
            $menu .= html::a(helper::createLink('api', 'createRelease', "libID=$libID"), $this->lang->api->createRelease, '', 'class="btn btn-link iframe"');
        }

        /* page of index menu. */
        if(common::hasPriv('api', 'create') or common::hasPriv('api', 'createLib'))
        {
            $menu .= "<div class='dropdown' id='createDropdown'>";
            $menu .= "<button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> " . $this->lang->api->createAB . " <span class='caret'></span></button>";
            $menu .= "<ul class='dropdown-menu pull-right'>";

            /* check has permission create api doc */
            if(intval($libID) > 0 and common::hasPriv('api', 'create'))
            {
                $menu .= "<li>";
                $menu .= html::a(helper::createLink('api', 'create', "libID=$libID&moduleID=$moduleID"), "<i class='icon-rich-text icon'></i> " . $this->lang->api->createApi, '', "data-app='{$this->app->tab}'");
                $menu .= "</li>";
            }

            /* check has permission create api doc lib */
            if(common::hasPriv('api', 'createLib'))
            {
                $menu .= '<li>' . html::a(helper::createLink('api', 'createLib'), "<i class='icon-doc-lib icon'></i> " . $this->lang->api->createLib, '', "class='iframe' data-width='70%'") . '</li>';

                $menu .= '<li class="divider"></li>';
                $menu .= '<li>' . html::a(helper::createLink('api', 'createLib', 'type=demo'), "<i class='icon-zentao icon'></i> " . $this->lang->api->createDemo, '', "class='iframe' data-width='70%'") . '</li>';
            }

            $menu .= "</ul></div>";
        }

        $this->lang->TRActions = $menu;
    }

    /**
     * Generate api doc index page dropMenu
     *
     * @param  array $libs
     * @param  int   $libID
     * @param  int   $version
     * @access public
     * @return string
     */
    private function generateLibsDropMenu($libs, $libID, $version = 0)
    {
        if(empty($libs)) return '';
        if(!isset($libs[$libID])) return '';

        $methodName = $this->app->rawMethod;

        $libName = $libs[$libID]->name;
        $output  = <<<EOT
<div class='btn-group angle-btn'>
  <div class='btn-group'>
    <button id='currentBranch' data-toggle='dropdown' type='button' class='btn btn-limit'>{$libName} <span class='caret'></span>
    </button>
    <div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList'>
      <div class="input-control search-box has-icon-left has-icon-right search-example">
        <input type="search" class="form-control search-input" />
        <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
        <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
      </div>
      <div class='table-col'>
        <div class='list-group'>
EOT;
        foreach($libs as $key => $lib)
        {
            $selected = $key == $libID ? 'selected' : '';
            $output  .= html::a(inlink($methodName, "libID=$key"), $lib->name, '', "class='$selected' data-app='{$this->app->tab}'");
        }
        if(count($libs) >= 2 and common::hasPriv('doc', 'sortLibs'))
        {
            $output   .= '<li class="divider"></li>';
            $output   .= html::a($this->createLink('doc', 'sortLibs', "type=api&objectID=0", '', true), "<i class='icon-move'></i>  {$this->lang->doc->sortLibs}", '', "data-title='{$this->lang->doc->sortLibs}' data-toggle='modal' data-type='iframe' data-width='400px' data-app='{$this->app->tab}'");
        }
        $output .= "</div></div></div></div></div>";

        /* Get lib version */
        $versions = $this->api->getReleaseListByApi($libID);
        if(!empty($versions))
        {
            $versionName = $version > 0 ? $versions[$version]->version : $this->lang->api->defaultVersion;
            $output     .= <<<EOT
<div class='btn-group angle-btn'>
  <div class='btn-group'>
    <button id='currentBranch' data-toggle='dropdown' type='button' class='btn btn-limit'>{$versionName} <span class='caret'></span>
    </button>
    <div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList'>
      <div class="input-control search-box has-icon-left has-icon-right search-example">
        <input type="search" class="form-control search-input" />
        <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
        <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
      </div>
      <div class='table-col'>
        <div class='list-group'>
EOT;
            $params   = $methodName == 'index' ? "libID=$libID&moduleID=0&apiID=0&version=0" : "libID=$libID";
            $selected = $version > 0 ? '' : 'selected';
            $output  .= html::a(inlink($methodName, $params . "&release=0"), $this->lang->api->defaultVersion, '', "class='$selected'");
            foreach($versions as $key => $item)
            {
                $selected = $key == $version ? 'selected' : '';
                $output  .= html::a(inlink($methodName, $params . "&release=$key"), $item->version, '', "class='$selected' data-app='{$this->app->tab}'");
            }
            $output .= "</div></div></div></div></div>";
        }

        return $output;
    }

    /**
     * Return session to the client.
     *
     * @access public
     * @return void
     */
    public function getSessionID()
    {
        $this->session->set('rand', mt_rand(0, 10000));
        $this->view->sessionName = session_name();
        $this->view->sessionID   = session_id();
        $this->view->rand        = $this->session->rand;
        $this->display();
    }

    /**
     * Execute a module's model's method, return the result.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $params param1=value1,param2=value2, don't use & to join them.
     * @access public
     * @return string
     */
    public function getModel($moduleName, $methodName, $params = '')
    {
        if(!$this->config->features->apiGetModel) return printf($this->lang->api->error->disabled, '$config->features->apiGetModel');

        $params    = explode(',', $params);
        $newParams = array_shift($params);
        foreach($params as $param)
        {
            $sign       = strpos($param, '=') !== false ? '&' : ',';
            $newParams .= $sign . $param;
        }

        parse_str($newParams, $params);
        $module = $this->loadModel($moduleName);
        $result = call_user_func_array(array(&$module, $methodName), $params);
        if(dao::isError()) return print(json_encode(dao::getError()));
        $output['status'] = $result ? 'success' : 'fail';
        $output['data']   = json_encode($result);
        $output['md5']    = md5($output['data']);
        $this->output     = json_encode($output);
        print($this->output);
    }

    /**
     * The interface of api.
     *
     * @param  int $filePath
     * @param  int $action
     * @access public
     * @return void
     */
    public function debug($filePath, $action)
    {
        $filePath    = helper::safe64Decode($filePath);
        $fileDirPath = realpath(dirname($filePath));
        if(strpos($fileDirPath, $this->app->getModuleRoot()) !== 0 and strpos($fileDirPath, $this->app->getExtensionRoot()) !== 0) return;
        if($action == 'extendModel')
        {
            $method = $this->api->getMethod($filePath, 'Model');
        }
        elseif($action == 'extendControl')
        {
            $method = $this->api->getMethod($filePath);
        }

        if(!empty($_POST))
        {
            $result  = $this->api->request($method->className, $method->methodName, $action);
            $content = json_decode($result['content']);
            $status  = $content->status;
            $data    = json_decode($content->data);
            $data    = '<xmp>' . print_r($data, true) . '</xmp>';

            $response['result'] = 'success';
            $response['status'] = $status;
            $response['url']    = $result['url'];
            $response['data']   = $data;
            $this->send($response);
        }

        $this->view->method   = $method;
        $this->view->filePath = $filePath;
        $this->display();
    }

    /**
     * Query sql.
     *
     * @param  string $keyField
     * @access public
     * @return void
     */
    public function sql($keyField = '')
    {
        if(!$this->config->features->apiSQL) return printf($this->lang->api->error->disabled, '$config->features->apiSQL');

        $sql    = isset($_POST['sql']) ? $this->post->sql : '';
        $output = $this->api->sql($sql, $keyField);

        $output['sql'] = $sql;
        $this->output  = json_encode($output);
        print($this->output);
    }

    /**
     * Get options of type.
     *
     * @param  int   $libID
     * @access public
     * @return void
     */
    private function getTypeOptions($libID)
    {
        $options = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = array('label' => $item, 'value' => $key);
        }

        /* Get all struct by libID. */
        $structs = $this->api->getStructListByLibID($libID);
        foreach($structs as $struct)
        {
            $options[] = array('label' => $struct->name, 'value' => $struct->id);
        }
        $this->view->typeOptions = $options;
    }
}
