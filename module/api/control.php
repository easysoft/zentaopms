<?php

/**
 * The control file of api of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * @param  int $libID
     * @param  int $moduleID
     * @param  int $apiID
     * @param  int $version
     * @param  int $release
     * @access public
     * @return void
     */
    public function index($libID = 0, $moduleID = 0, $apiID = 0, $version = 0, $release = 0)
    {
        /* Get all api doc libraries. */
        $libs = $this->doc->getApiLibs();

        /* Generate bread crumbs dropMenu. */
        if($libs)
        {
            if($libID == 0) $libID = key($libs);
            $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID, $release);
        }
        $this->setMenu($libID);

        /* Get an api doc. */
        if($apiID > 0)
        {
            $api = $this->api->getLibById($apiID, $version);
            if($api)
            {
                $moduleID  = $api->module;
                $libID     = $api->lib;
                $api->desc = htmlspecialchars_decode($api->desc);

                $this->view->api     = $api;
                $this->view->apiID   = $apiID;
                $this->view->version = $version;
                $this->view->actions = $apiID ? $this->action->getList('api', $apiID) : array();
            }
        }
        else
        {
            /* Get module api list. */
            $apiList = $this->api->getListByModuleId($libID, $moduleID, $release);

            $this->view->apiList = $apiList;
        }

        $this->view->isRelease  = $release > 0;
        $this->view->release    = $release;
        $this->view->title      = $this->lang->api->title;
        $this->view->libID      = $libID;
        $this->view->apiID      = $apiID;
        $this->view->libs       = $libs;
        $this->view->moduleTree = $libID ? $this->doc->getApiModuleTree($libID, $apiID) : '';
        $this->view->users      = $this->user->getPairs('noclosed,noletter');

        $this->display();
    }

    /**
     * Release manage page.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function editPublish($libID, $orderBy = 'id', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $libs = $this->doc->getApiLibs();
        $this->app->loadClass('pager', $static = true);
        $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID);

        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort     = $this->loadModel('common')->appendOrder($orderBy);
        $releases = $this->api->getReleaseByQuery($libID, $pager, $sort);

        $this->view->releases = $releases;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->view->title    = $this->lang->api->managePublish;
        $this->view->libID    = $libID;
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
            echo js::confirm($this->lang->custom->notice->confirmDelete, $this->createLink('api', 'deleteRelease', "libID=$libID&id=$id&confirm=yes"), '');
            die;
        }
        else
        {
            $this->api->deletePublish($id);
            if(dao::isError()) return $this->sendError(dao::getError());
            die(js::locate(inlink('editPublish', "libID=$libID"), 'parent'));
        }
    }

    /**
     * Publish a api doc lib.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function publish($libID)
    {
        $lib = $this->doc->getLibById($libID);

        if(!empty($_POST))
        {
            $data = fixer::input('post')
                ->add('lib', $libID)
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', helper::now())
                ->get();

            // check version is exist.
            if($this->api->getReleaseByVersion($libID, $data->version))
            {
                return $this->sendError($this->lang->api->noUniqueVersion);
            }
            $this->api->publishLib($data);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('locate' => $this->createLink('api', 'index', "libID=$libID")));
        }

        $this->view->title = $this->lang->api->publish . $lib->name;
        $this->display();
    }

    /**
     * Api doc global struct page.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function struct($libID = 0, $orderBy = 'id', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $libs = $this->doc->getApiLibs();
        $this->app->loadClass('pager', $static = true);
        $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID);

        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $structs = $this->api->getStructByQuery($libID, $pager, $sort);

        common::setMenuVars('doc', $libID);
        $this->view->libID   = $libID;
        $this->view->structs = $structs;
        $this->view->orderBy = $orderBy;
        $this->view->title   = $this->lang->api->struct;
        $this->view->pager   = $pager;
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
                ->add('addedBy', $userId)
                ->add('addedDate', $now)
                ->add('editedBy', $userId)
                ->add('editedDate', $now)
                ->get();


            $id = $this->api->createStruct($data);
            $this->action->create('apistruct', $id, 'Created');

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'editStruct', "libID=$libID&structID=$id")));
        }

        $options = [];
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = [
                'label' => $item,
                'value' => $key,
            ];
        }
        $this->view->typeOptions = $options;
        $this->view->title       = $this->lang->api->createStruct;

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
            $now    = helper::now();
            $userId = $this->app->user->account;
            $data   = fixer::input('post')
                ->add('lib', $struct->lib)
                ->add('editedBy', $userId)
                ->add('editedDate', $now)
                ->json('attribute')
                ->get();

            $changes = $this->api->updateStruct($structID, $data);
            if(dao::isError()) return $this->sendError(dao::getError());
            $actionID = $this->action->create('apistruct', $structID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'editStruct', "libID={$struct->lib}&structID=$structID")));
        }

        $options = [];
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = [
                'label' => $item,
                'value' => $key,
            ];
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
            echo js::confirm($this->lang->custom->notice->confirmDelete, $this->createLink('api', 'deleteStruct', "libID=$libID&structID=$structID&confirm=yes"), '');
            die;
        }
        else
        {
            $this->api->deleteStruct($structID);
            if(dao::isError()) return $this->sendError(dao::getError());
            $this->action->create('apistruct', $structID, 'Deleted');
            die(js::locate(inlink('struct', "libID=$libID"), 'parent'));
        }
    }

    /**
     * Create a api doc library.
     *
     * @access public
     * @return void
     */
    public function createLib()
    {
        if(!empty($_POST))
        {
            $lib = fixer::input('post')
                ->join('groups', ',')
                ->join('users', ',')
                ->get();

            if($lib->acl == 'private') $lib->users = $this->app->user->account;

            /* save api doc library */
            $libID = $this->doc->createApiLib($lib);
            if(dao::isError())
            {
                return $this->sendError(dao::getError());
            }
            $this->action->create('docLib', $libID, 'Created');

            /* save doc library success */
            return $this->sendSuccess(array('locate' => $this->createLink('api', 'index', "libID=$libID")));
        }
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->user->getPairs('nocode');

        $this->display();
    }

    /**
     * Edit an api doc library
     *
     * @param $id
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
        $this->view->users  = $this->user->getPairs('nocode');

        $this->display();
    }


    /**
     * @param  int    $libID
     * @param  string $confirm
     * @param  string $from
     */
    public function deleteLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->doc->confirmDeleteLib, $this->createLink('api', 'deleteLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->doc->delete(TABLE_DOCLIB, $libID);
            if(isonlybody())
            {
                unset($_GET['onlybody']);
                die(js::locate($this->createLink('api', 'index'), 'parent.parent'));
            }

            die(js::locate($this->createLink('api', 'index'), 'parent'));
        }
    }

    /**
     * Edit library.
     *
     * @param  int $apiID
     * @access public
     * @return void
     */
    public function edit($apiID)
    {
        if(helper::isAjaxRequest() && !empty($_POST))
        {
            $this->loadModel('api');

            $now    = helper::now();
            $userId = $this->app->user->account;
            $params = fixer::input('post')
                ->remove('type')
                ->add('addedBy', $userId)
                ->add('addedDate', $now)
                ->add('editedBy', $userId)
                ->add('editedDate', $now)
                ->setDefault('product,module', 0)
                ->get();

            $this->api->update($apiID, $params);
            if(dao::isError())
            {
                return $this->sendError(dao::getError());
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

        $example = array('example' => 'type,description');
        $example = json_encode($example, JSON_PRETTY_PRINT);

        $options = [];
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = [
                'label' => $item,
                'value' => $key,
            ];
        }
        $this->view->typeOptions = $options;
        $this->view->user        = $this->app->user->account;
        $this->view->allUsers    = $this->loadModel('user')->getPairs('devfirst|noclosed');;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($api->lib, 'api', $startModuleID = 0);
        $this->view->moduleID         = $api->module ? (int)$api->module : (int)$this->cookie->lastDocModule;
        $this->view->example          = $example;
        $this->view->title            = $api->title . $this->lang->api->edit;

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
            $now    = helper::now();
            $params = fixer::input('post')
                ->remove('type')
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', $now)
                ->add('editedBy', $this->app->user->account)
                ->add('editedDate', $now)
                ->add('version', 1)
                ->setDefault('product,module', 0)
                ->get();

            $apiID = $this->api->create($params);
            if(empty($apiID)) return $this->sendError(dao::getError());

            $this->action->create('api', $apiID, 'Created');
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'index', "libID=0&moduleID=0&apiID=$apiID")));
        }

        $libs = $this->doc->getLibs('api', '', $libID);
        if(!$libID and !empty($libs)) $libID = key($libs);

        $this->setMenu($libID);

        $lib     = $this->doc->getLibByID($libID);
        $libName = isset($lib->name) ? $lib->name . $this->lang->colon : '';

        $example = array('example' => 'type,description');
        $example = json_encode($example, JSON_PRETTY_PRINT);

        $this->getTypeOptions($libID);
        $this->view->user             = $this->app->user->account;
        $this->view->allUsers         = $this->loadModel('user')->getPairs('devfirst|noclosed');
        $this->view->libID            = $libID;
        $this->view->libName          = $lib->name;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'api', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->libs             = $libs;
        $this->view->example          = $example;
        $this->view->title            = $libName . $this->lang->api->create;
        $this->view->users            = $this->user->getPairs('nocode');

        $this->display();
    }

    /**
     * @param  int    $apiID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($apiID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $tips = $this->lang->api->confirmDelete;
            die(js::confirm($tips, inlink('delete', "apiID=$apiID&confirm=yes")));
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
     * Get params type options by scope
     *
     * @access public
     * @return void
     */
    public function ajaxGetParamsTypeOptions()
    {
        $options = [];
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = [
                'label' => $item,
                'value' => $key,
            ];
        }
        $this->sendSuccess(array('data' => $options));
    }

    /**
     * Get ref options by ajax.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function ajaxGetRefOptions($libID = 0, $structID = 0)
    {
        $res = $this->api->getStructListByLibID($libID);

        $options = array();
        foreach($res as $item)
        {
            if($item->id == $structID)
                continue;
            $options[$item->id] = $item->name;
        }

        echo html::select('refTarget', $options, '', "class='form-control'");
    }

    /**
     * Get ref info by ajax.
     *
     * @param  int $refID
     * @access public
     * @return void
     */
    public function ajaxGetRefInfo($refID = 0)
    {
        $info = $this->api->getStructByID($refID);
        $this->sendSuccess(array('info' => $info));
    }

    /**
     * Set doc menu by method name.
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    private
    function setMenu($libID = 0)
    {
        common::setMenuVars('doc', $libID);

        // global struct link
        $menu = '';

        if(common::hasPriv('api', 'publish'))
        {
            $menu .= html::a(helper::createLink('api', 'publish', "libID=$libID"), $this->lang->api->publish, '', 'class="btn btn-link iframe"');
        }
        // page of index menu
        if(intval($libID) > 0)
        {
            $menu .= "<div class='dropdown' id='createDropdown'>";
            $menu .= "<button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> " . $this->lang->curd->create . " <span class='caret'></span></button>";
            $menu .= "<ul class='dropdown-menu pull-right'>";

            /* check has permission create api doc */
            if(common::hasPriv('api', 'create'))
            {
                $menu .= "<li>";
                $menu .= html::a(helper::createLink('api', 'create', "libID=$libID"), "<i class='icon-rich-text icon'></i> " . $this->lang->api->apiDoc, '', "data-app='{$this->app->tab}'");
                $menu .= "</li>";
            }

            /* check has permission create api doc lib */
            if(common::hasPriv('api', 'createLib'))
            {
                $menu .= '<li class="divider"></li>';
                $menu .= '<li>' . html::a(helper::createLink('api', 'createLib'), "<i class='icon-doc-lib icon'></i> " . $this->lang->api->createLib, '', "class='iframe' data-width='70%'") . '</li>';
            }

            $menu .= "</ul></div>";
        }
        else
        {
            /* generate create api doc lib button */
            if(common::hasPriv('api', 'createDoc'))
            {
                $menu .= html::a(helper::createLink('api', 'createLib'), '<i class="icon icon-plus"></i> ' . $this->lang->api->createLib, '', 'class="btn btn-secondary iframe"');
            }
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
            $output   .= html::a(inlink('index', "libID=$key"), $lib->name, '', "class='$selected' data-app='{$this->app->tab}'");
        }
        $output .= "</div></div></div></div></div>";

        /* Get lib version */
        $versions = $this->api->getReleaseListByApi($libID);
        if(!empty($versions))
        {
            $versionName = $version > 0 ? $versions[$version]->desc : $this->lang->api->defaultVersion;
            $output      .= <<<EOT
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
            $selected    = $version > 0 ? '' : 'selected';
            $output      .= html::a(inlink('index', "libID=$libID&moduleID=0&apiID=0&version=0&release=0"), $this->lang->api->defaultVersion, '', "class='$selected'");
            foreach($versions as $key => $item)
            {
                $selected = $key == $version ? 'selected' : '';
                $output   .= html::a(inlink('index', "libID=$libID&moduleID=0&apiID=0&version=0&release=$key"), $item->desc, '', "class='$selected' data-app='{$this->app->tab}'");
            }
            $output .= "</div></div></div></div></div>";
        }

        return $output;
    }

    /**
     * Show doc of api doc library
     *
     * @param  int $libID
     * @access public
     * @return void
     */
    public function showLibs($libID = 0)
    {
        $lib = $this->doc->getLibById($libID);
        if(!empty($lib) and $lib->deleted == '1') $appendLib = $libID;
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
        if(!$this->config->features->apiGetModel) die(sprintf($this->lang->api->error->disabled, '$config->features->apiGetModel'));

        $params    = explode(',', $params);
        $newParams = array_shift($params);
        foreach($params as $param)
        {
            $sign      = strpos($param, '=') !== false ? '&' : ',';
            $newParams .= $sign . $param;
        }

        parse_str($newParams, $params);
        $module = $this->loadModel($moduleName);
        $result = call_user_func_array(array(&$module, $methodName), $params);
        if(dao::isError()) die(json_encode(dao::getError()));
        $output['status'] = $result ? 'success' : 'fail';
        $output['data']   = json_encode($result);
        $output['md5']    = md5($output['data']);
        $this->output     = json_encode($output);
        die($this->output);
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
        $filePath = helper::safe64Decode($filePath);
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
        if(!$this->config->features->apiSQL) die(sprintf($this->lang->api->error->disabled, '$config->features->apiSQL'));

        $sql    = isset($_POST['sql']) ? $this->post->sql : '';
        $output = $this->api->sql($sql, $keyField);

        $output['sql'] = $sql;
        $this->output  = json_encode($output);
        die($this->output);
    }

    private
    function getTypeOptions($libID)
    {
        $options = [];
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = [
                'label' => $item,
                'value' => $key,
            ];
        }
        /* get all struct by libID. */
        $structs = $this->api->getStructListByLibID($libID);
        foreach($structs as $struct)
        {
            $options[] = [
                'label' => $struct->name,
                'value' => $struct->id,
            ];
        }
        $this->view->typeOptions = $options;
    }

}
