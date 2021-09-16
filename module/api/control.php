<?php

/**
 * The control file of api of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: control.php 5143 2013-07-15 06:11:59Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
class api extends control
{
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->group  = $this->loadModel('group');
        $this->user   = $this->loadModel('user');
        $this->doc    = $this->loadModel('doc');
        $this->action = $this->loadModel('action');
        $this->tree   = $this->loadModel('tree');
        $this->api    = $this->loadModel('api');
    }

    /**
     * Api doc index page.
     * @return void
     * @author thanatos thanatos915@163.com
     */
    public function index()
    {
        $params  = $_GET;
        $libID   = $params['libID'];
        $module  = $params['module'];
        $apiID   = $params['apiID'];
        $version = $params['version'];

        /* Get all api doc libraries */
        $libs = $this->doc->getApiLibs();

        /* generate bread crumbs dropMenu */
        if($libs)
        {
            if($libID == 0) $libID = key($libs);
            $this->lang->modulePageNav = $this->generateLibsDropMenu($libs, $libID);
            $this->view->libID         = $libID;
        }
        $this->setMenu($libID);


        /* Get an api doc */
        if($apiID > 0)
        {
            $api = $this->api->getLibById($apiID, $version);
            if($api)
            {
                $module              = $api->module;
                $_GET['module']      = $module;
                $libID               = $api->lib;
                $this->view->api     = $api;
                $this->view->apiID   = $apiID;
                $this->view->version = $version;
                $this->view->actions = $apiID ? $this->action->getList('api', $apiID) : array();
            }
        }
        else
        {
            /* Get module api list */
            $apiList             = $this->api->getListByModuleId($libID, $module);
            $this->view->apiList = $apiList;
            $this->view->moudle  = $module;
        }

        /* Get module tree */
        $moduleTree = $this->doc->getApiModuleTree($libID, $apiID);

        $this->view->title      = $this->lang->api->title;
        $this->view->libs       = $libs;
        $this->view->moduleTree = $moduleTree;
        $this->view->users      = $this->user->getPairs('noclosed,noletter');

        $this->display();
    }

    /**
     * Show an Api doc
     * @param $id
     * @author thanatos thanatos915@163.com
     */
    public function detail($id)
    {

    }

    /**
     * Create a api doc library.
     * @author thanatos thanatos915@163.com
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
                $this->sendError(dao::getError());
                exit;
            }
            $this->action->create('docLib', $libID, 'Created');

            /* save doc library success */
            $this->sendSuccess([
                'locate' => $this->createLink('api', 'index', "libID=$libID"),
            ]);
            exit;
        }
        $this->view->groups = $this->group->getPairs();
        $this->view->users  = $this->user->getPairs('nocode');

        $this->display();
    }

    public function edit()
    {
        $apiID = $_GET['apiID'];
        if(helper::isAjaxRequest() && !empty($_POST))
        {
            $this->loadModel('api');

            $now    = helper::now();
            $userId = $this->app->user->account;
            $params = fixer::input('post')
                ->add('addedBy', $userId)
                ->add('addedDate', $now)
                ->add('editedBy', $userId)
                ->add('editedDate', $now)
                ->setDefault('product,module', 0)
                ->json('params,response')
                ->get();


            $this->api->update($apiID, $params);
            if(dao::isError())
            {
                $this->sendError(dao::getError());
                exit;
            }

            $this->action->create('api', $apiID, 'Edited');
            $this->sendSuccess([
                'locate' => helper::createLink('api', 'index', "apiID=$apiID"),
            ]);
            exit;
        }

        $api = $this->api->getLibById($apiID);
        if($api)
        {
            $this->view->api  = $api;
            $this->view->edit = true;
        }

        $example             = [
            'example' => 'type,description'
        ];
        $this->view->example = json_encode($example, JSON_PRETTY_PRINT);

        $allUsers                     = $this->loadModel('user')->getPairs('devfirst|noclosed');
        $this->view->user             = $this->app->user->account;
        $this->view->allUsers         = $allUsers;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($api->lib, 'api', $startModuleID = 0);
        $this->view->moduleID         = $api->module ? (int)$api->module : (int)$this->cookie->lastDocModule;
        $this->view->title            = $api->title . $this->lang->api->edit;

        $this->display();

    }

    /**
     * Create an api doc.
     * @author thanatos thanatos915@163.com
     */
    public function create()
    {
        $libID    = $_GET['libID'];
        $moduleID = $_GET['moduleID'];
        if(helper::isAjaxRequest() && !empty($_POST))
        {
            $this->loadModel('api');

            $now    = helper::now();
            $userId = $this->app->user->account;
            $params = fixer::input('post')
                ->add('addedBy', $userId)
                ->add('addedDate', $now)
                ->add('editedBy', $userId)
                ->add('editedDate', $now)
                ->add('version', 1)
                ->setDefault('product,module', 0)
                ->unHtml('params,response')
                ->json('params,response')
                ->get();

            $apiID = $this->api->create($params);
            if(empty($apiID))
            {
                $this->sendError(dao::getError());
                exit;
            }

            $this->action->create('api', $apiID, 'Created');
            $this->sendSuccess([
                'locate' => helper::createLink('api', 'index', "apiID=$apiID"),
            ]);
            exit;
        }

        $libs = $this->doc->getLibs('api', '', $libID);
        if(!$libID and !empty($libs)) $libID = key($libs);

        $lib     = $this->doc->getLibByID($libID);
        $libName = isset($lib->name) ? $lib->name . $this->lang->colon : '';

        $example             = [
            'example' => 'type,description'
        ];
        $this->view->example = json_encode($example, JSON_PRETTY_PRINT);

        $allUsers                     = $this->loadModel('user')->getPairs('devfirst|noclosed');
        $this->view->user             = $this->app->user->account;
        $this->view->allUsers         = $allUsers;
        $this->view->libID            = $libID;
        $this->view->libName          = $lib->name;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($libID, 'api', $startModuleID = 0);
        $this->view->moduleID         = $moduleID ? (int)$moduleID : (int)$this->cookie->lastDocModule;
        $this->view->libs             = $libs;
        $this->view->title            = $libName . $this->lang->api->create;
        $this->view->users            = $this->user->getPairs('nocode');

        $this->display('api', 'create');
    }

    /**
     * @param        $apiID
     * @param string $confirm
     * @author thanatos thanatos915@163.com
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
                $this->sendSuccess([
                    'locate' => $this->createLink('api', 'index', "libID=$api->lib&module=$api->module"),
                ]);
            }
        }
    }

    /**
     * Get params type options by scope
     *
     * @param string $scope the params position
     * @author thanatos thanatos915@163.com
     */
    public function ajaxGetParamsTypeOptions($scope)
    {
        if(empty($scope)) die();
        $options = [];
        if($scope == apiModel::SCOPE_BODY)
        {
            $options = $this->lang->api->allParamsTypeOptions;
        }
        else
        {
            $options = $this->lang->api->paramsTypeOptions;
        }

        echo html::select('paramsTypeOptions', $options, '', "class='form-control'  onchange='changeType(this);'");
        exit;
    }

    /**
     * Set doc menu by method name.
     *
     * @author thanatos thanatos915@163.com
     */
    private function setMenu($libID = 0)
    {
        $menu = '';
        // page of index menu
        if(intval($libID) > 0)
        {
            $menu = "<div class='dropdown' id='createDropdown'>";
            $menu .= "<button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> " . $this->lang->curd->create . " <span class='caret'></span></button>";
            $menu .= "<ul class='dropdown-menu pull-right'>";

            /* check has permission create api doc */
            if(common::hasPriv('api', 'create'))
            {
                $menu .= "<li>";
                $menu .= html::a(helper::createLink('api', 'create', "libID=$libID"), "<i class='icon-rich-text icon'></i> 接口", '', "data-app='{$this->app->tab}'");
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
                $menu = html::a(helper::createLink('api', 'createLib'), '<i class="icon icon-plus"></i> ' . $this->lang->api->createLib, '', 'class="btn btn-secondary iframe"');
            }
        }

        $this->lang->TRActions = $menu;
    }

    /**
     * Generate api doc index page dropMenu
     *
     * @author thanatos thanatos915@163.com
     */
    private function generateLibsDropMenu($libs, $libID)
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

        return $output;
    }

    /**
     * Show doc of api doc library
     * @author thanatos thanatos915@163.com
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
    public
    function getSessionID()
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
     * @param string $moduleName
     * @param string $methodName
     * @param string $params param1=value1,param2=value2, don't use & to join them.
     * @access public
     * @return string
     */
    public
    function getModel($moduleName, $methodName, $params = '')
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
     * @param int $filePath
     * @param int $action
     * @access public
     * @return void
     */
    public
    function debug($filePath, $action)
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
     * @param string $keyField
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


    /**
     * @var groupModel
     */
    public $group;

    /**
     * @var userModel
     */
    public $user;

    /**
     * @var docModel
     */
    public $doc;

    /**
     * @var actionModel
     */
    public $action;

    /**
     * @var treeModel;
     */
    public $tree;

    /**
     * @var apiModel
     */
    public $api;
}
