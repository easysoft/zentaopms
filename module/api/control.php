<?php
declare(strict_types=1);
/**
 * The control file of api of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: control.php 5143 2013-07-15 06:11:59Z thanatos thanatos915@163.com $
 * @link        https://www.zentao.net
 */
class api extends control
{
    public $objectType = 'nolink';
    public $objectID   = 0;

    public function __construct(string $moduleName = '', string $methodName = '', string $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->user   = $this->loadModel('user');
        $this->doc    = $this->loadModel('doc');
        $this->action = $this->loadModel('action');

        if($this->cookie->objectType) $this->objectType = $this->cookie->objectType;
        if($this->cookie->objectID)   $this->objectID   = $this->cookie->objectID;
    }

    /**
     * 文档应用下接口空间页面。
     * Api doc index page.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $apiID
     * @param  int    $version
     * @param  int    $release
     * @param  string $browseType
     * @param  string $params
     * @access public
     * @return void
     */
    public function index(int $libID = 0, int $moduleID = 0, int $apiID = 0, int $version = 0, int $release = 0, string $browseType = 'nolink', string $params = '', string $orderBy = 'order_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = '', string $search = '')
    {
        /* 设置空间类型。 */
        $this->session->set('spaceType', 'api', 'doc');
        /* 详情页返回上一页用的链接。 */
        $this->session->set('structList', inLink('index', "libID=$libID&moduleID=$moduleID"), 'doc');
        setCookie("docSpaceParam", '', $this->config->cookieLife, $this->config->webRoot, '', false, true);

        if(empty($libID) && !empty($apiID))
        {
            $api = $this->api->getByID($apiID, $version, $release);
            if($api) $libID = $api->lib;
        }

        if(empty($mode))
        {
            if(!$libID)    $mode = 'home';
            elseif($apiID) $mode = 'view';
            else           $mode = 'list';
        }

        $this->setMenu($libID);
        $objectType = $this->objectType;
        $objectID   = $this->objectID;

        /* 获取文档目录列表和当前选中的文档目录. */
        $libs = $this->doc->getApiLibs($libID, $objectType, (int)$objectID);
        if(empty($libs) && $objectType != 'nolink')
        {
            $objectType = 'nolink';
            $objectID   = 0;
            $libs       = $this->doc->getApiLibs($libID, 'nolink');
        }

        if($libID && !isset($libs[$libID])) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->doc->accessDenied, 'locate' => $this->createLink('api', 'index'))));
        $lib        = $libID ? zget($libs, $libID) : current($libs);
        $libID      = !empty($lib->id)      ? $lib->id : 0;
        $objectType = !empty($lib->product) ? 'product'     : (!empty($lib->project) ? 'project' : 'nolink');
        $objectID   = !empty($lib->product) ? $lib->product : (!empty($lib->project) ? $lib->project : 0);

        $this->view->libID          = $libID;
        $this->view->moduleID       = $moduleID;
        $this->view->apiID          = (int)$apiID;
        $this->view->release        = (int)$release;
        $this->view->apiVersion     = (int)$version;
        $this->view->mode           = $mode;
        $this->view->filterType     = $browseType;
        $this->view->search         = $search;
        $this->view->recTotal       = $recTotal;
        $this->view->recPerPage     = $recPerPage;
        $this->view->pageID         = $pageID;
        $this->view->orderBy        = $orderBy;
        $this->view->objectType     = $objectType;
        $this->view->objectID       = $objectID;
        $this->view->params         = $params;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->view->title          = $this->lang->api->pageTitle;
        $this->display();
    }

    /**
     * 获取接口空间首页页面。
     * Get the api home page.
     *
     * @param  string $type
     * @param  string $params
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function ajaxGetHome(string $type = 'nolink', string $params = 'notempty_unclosed', int $recPerPage = 20, int $pageID = 1)
    {
        if(!in_array($type, array('product', 'project', 'nolink'))) $type = 'nolink';

        $libs          = $this->doc->getApiLibs(0, $type);
        $flags         = explode('_', $params);
        $unclosed      = in_array('unclosed', $flags);
        $notempty      = in_array('notempty', $flags);
        $apis          = $this->api->getApiListBySearch(0, 0);
        $recTotal      = 0;
        $recPerPage    = $recPerPage ? max(1, $recPerPage) : 20;

        $productsCount = array();
        $projectsCount = array();
        foreach($apis as $api)
        {
            if(!isset($libs[$api->lib])) continue;
            $lib = $libs[$api->lib];
            if(isset($lib->apiCount)) $lib->apiCount++;
            else                      $lib->apiCount = 1;

            if($lib->product)
            {
                if(isset($productsCount[$lib->product])) $productsCount[$lib->product]++;
                else                                     $productsCount[$lib->product] = 1;
            }
            if($lib->project)
            {
                if(isset($projectsCount[$lib->project])) $projectsCount[$lib->project]++;
                else                                     $projectsCount[$lib->project] = 1;
            }
        }

        if($type === 'product' || $type === 'project')
        {
            $this->loadModel('program');
            $this->app->loadLang('project');
            $this->app->loadLang('product');
            $programPairs = $this->loadModel('program')->getTopPairs($unclosed ? 'noclosed' : '');
            $programs[]   = (object)array('id' => 0, 'name' => $this->lang->$type->emptyProgram);
            foreach($programPairs as $id => $name)
            {
                $program = new stdclass();
                $program->id = $id;
                $program->name = $name;
                $programs[$id] = $program;
            }
            if($type == 'product')
            {
                $products = $this->loadModel('product')->getList(0, $unclosed ? 'noclosed' : 'all', 0, 0);
                foreach($products as $product)
                {
                    if(!isset($programs[$product->program])) continue;
                    $program = $programs[$product->program];
                    $product->apiCount = isset($productsCount[$product->id]) ? $productsCount[$product->id] : 0;
                    if($notempty && $product->apiCount == 0) continue;
                    $program->products[] = $product;
                    $program->apiCount = isset($program->apiCount) ? $program->apiCount + $product->apiCount : $product->apiCount;
                }
            }
            else
            {
                $projects = $this->program->getProjectList(0, $unclosed ? 'unclosed' : 'all', 0, 'order_asc,id_desc');
                foreach($projects as $project)
                {
                    $topParent = $project->parent ? explode(',', trim($project->path, ','))[0] : 0;
                    if(!isset($programs[$topParent])) continue;
                    $program = $programs[$topParent];
                    $project->apiCount = isset($projectsCount[$project->id]) ? $projectsCount[$project->id] : 0;
                    if($notempty && $project->apiCount == 0) continue;
                    $program->apiCount = isset($program->apiCount) ? $program->apiCount + $project->apiCount : $project->apiCount;
                    $program->projects[] = $project;
                }
            }

            if($notempty) $programs = array_filter($programs, function($program) {return isset($program->apiCount) && $program->apiCount > 0;});

            $recTotal = count($programs);
            $pageID   = max(1, min($pageID, ceil($recTotal / $recPerPage)));
            $programs = array_slice($programs, ($pageID - 1) * $recPerPage, $recPerPage);
        }
        else
        {
            if($notempty) $libs = array_filter($libs, function($lib) {return isset($lib->apiCount) && $lib->apiCount > 0;});
            $recTotal = count($libs);
            $pageID   = max(1, min($pageID, ceil($recTotal / $recPerPage)));
            $libs     = array_slice($libs, ($pageID - 1) * $recPerPage, $recPerPage);
        }

        $this->app->loadClass('pager', true);

        $this->view->programs = isset($programs) ? $programs : array();
        $this->view->unclosed = $unclosed;
        $this->view->notempty = $notempty;
        $this->view->libs     = $libs;
        $this->view->type     = $type;
        $this->view->pager    = new pager($recTotal, $recPerPage, $pageID);
        $this->display();
    }

    /**
     * 获取接口界面数据。
     * Get the api UI data.
     *
     * @param  string $spaceID
     * @param  string $picks
     * @access public
     * @return void
     */
    public function ajaxGetData(string $spaceID = 'nolink', string $picks = '')
    {
        $noPicks    = empty($picks);
        $picks      = $noPicks ? '' : ",$picks,";
        $pickLib    = $noPicks || strpos($picks, ',lib,')    !== false;
        $pickModule = $noPicks || strpos($picks, ',module,') !== false;
        $data       = array('spaceID' => $spaceID);
        $isNolink   = $spaceID == 'nolink';

        list($objectType, $objectID) = $isNolink ? array('', 0) : explode('.', $spaceID . '.');
        if($noPicks || strpos($picks, ',space,') !== false)
        {
            $spaces['nolink'] = array('id' => 'nolink', 'name' => $this->lang->api->noLinked, 'type' => 'api');
            list($normalObjects, $closedObjects) = $this->api->getOrderedObjects();
            foreach($normalObjects as $type => $list)
            {
                foreach($list as $id => $name) $spaces["$type.{$id}"] = array('id' => "$type.{$id}", 'name' => $name, 'type' => 'api', 'objectType' => $type);
            }
            foreach($closedObjects as $type => $list)
            {
                foreach($list as $id => $name) $spaces["$type.{$id}"] = array('id' => "$type.{$id}", 'name' => $name, 'type' => 'api', 'objectType' => $type, 'closed' => true);
            }
            if(!$isNolink && !isset($spaces[$spaceID]))
            {
                $object = $this->loadModel($objectType)->getByID((int)$objectID);
                $spaces[$spaceID] = array('id' => $spaceID, 'name' => $object->name, 'type' => 'api');
            }
            $data['spaces'] = array_values($spaces);
        }

        if($pickLib || $pickModule)
        {
            $libs     = $isNolink ? $this->doc->getApiLibs(0, 'nolink') : $this->doc->getApiLibs(0, $objectType, (int)$objectID);
            $libIds   = array_keys($libs);
            if($pickLib)
            {
                $releases   = $this->api->getReleaseByQuery($libIds);
                $releaseMap = array();
                foreach($releases as $release) $releaseMap[$release->lib][] = $release;
                foreach($libs as $lib)
                {
                    $lib->versions = isset($releaseMap[$lib->id]) ? $releaseMap[$lib->id] : array();
                    if(!empty($lib->product))     $lib->space = "product.{$lib->product}";
                    elseif(!empty($lib->project)) $lib->space = "project.{$lib->project}";
                    else                          $lib->space = 'nolink';
                }
                $data['libs'] = array_values($libs);
            }
        }
        if($pickModule) $data['modules'] = array_values($this->doc->getModulesOfLibs($libIds, 'api'));

        if($noPicks || strpos($picks, ',doc,') !== false)
        {
            $apis       = $this->api->getApiListBySearch(0, 0, $objectType);
            $unsetProps = array('commonParams', 'params', 'paramsExample', 'response', 'responseExample');
            foreach($apis as $api)
            {
                foreach($unsetProps as $prop) unset($api->$prop);
                $api->originTitle = $api->title;
                $api->icon        = "api is-$api->method";
                $api->api         = true;
                $api->title       = "$api->method $api->path " . htmlspecialchars_decode($api->title);
                $api->editable    = common::hasPriv('api', 'edit');
            }
            $data['docs'] = array_values($apis);
        }

        echo json_encode($data);
    }

    /**
     * 获取指定库的 API 列表。
     * Get the API list of the specified library.
     *
     * @param  int    $libID
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function ajaxGetLibApiList(int $libID, int $releaseID = 0)
    {
        if($releaseID)
        {
            $release = $this->api->getRelease(0, 'byID', $releaseID);
            $apis    = $release ? $this->api->getApiListByRelease($release) : array();
        }
        else
        {
            $apis = $this->api->getApiListBySearch($libID, 0);
        }

        $unsetProps = array('commonParams', 'params', 'paramsExample', 'response', 'responseExample');
        foreach($apis as $api)
        {
            foreach($unsetProps as $prop) unset($api->$prop);
            $api->originTitle = $api->title;
            $api->icon        = "api is-$api->method";
            $api->api         = true;
            $api->title       = "$api->method $api->path " . htmlspecialchars_decode($api->title);
        }

        echo json_encode($apis);
    }

    /**
     * 获取接口数据。
     *
     * @param  int    $apiID
     * @param  int    $version
     * @param  int    $release
     * @access public
     * @return void
     */
    public function ajaxGetApi(int $apiID, int $version = 0, int $release = 0)
    {
        $api = $this->api->getByID($apiID, $version, $release);
        if($api)
        {
            $api->originTitle = $api->title;
            $api->icon        = "api is-$api->method";
            $api->title       = "$api->method $api->path $api->title";
            $api->api         = true;
            $api->editable    = common::hasPriv('api', 'edit');
        }
        $this->send($api);
    }

    /**
     * API详情页面。
     * View api.
     *
     * @param  int    $libID
     * @param  int    $apiID
     * @param  int    $moduleID
     * @param  int    $version
     * @param  int    $release
     * @access public
     * @return void
     */
    public function view(int $libID, int $apiID, int $moduleID = 0, int $version = 0, int $release = 0)
    {
        if(helper::isAjaxRequest('fetch'))
        {
            $api = $this->api->getByID($apiID, $version, $release);

            $this->view->api      = $api;
            $this->view->typeList = $this->api->getTypeList($api->lib);
            $this->display();
            return;
        }

        echo $this->fetch('api', 'index', "libID=$libID&moduleID=$moduleID&apiID=$apiID&version=$version&release=$release");
    }

    /**
     * 版本管理列表页面。
     * Release list.
     *
     * @param  int    $libID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function releases(int $libID, string $orderBy = 'id')
    {
        $this->app->loadLang('custom');

        /* Append id for second sort. */
        $sort     = common::appendOrder($orderBy);
        $releases = $this->api->getReleaseByQuery(array($libID), null, $sort);

        $this->view->title    = $this->lang->api->managePublish;
        $this->view->releases = $releases;
        $this->view->orderBy  = $orderBy;
        $this->view->libID    = $libID;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 删除一个版本。
     * Delete a release.
     *
     * @param  int    $libID
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteRelease(int $libID, int $id = 0)
    {
        $this->api->deleteRelease($id);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => true, 'closeModal' => true, 'docApp' => array(array('executeCommand', 'loadLazyContent', array('.api-release-list')), array('load', null, null, null, array('noLoading' => true, 'picks' => 'lib')))));
    }

    /**
     * 创建一个接口版本。
     * Create a api doc lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function createRelease(int $libID)
    {
        if(!empty($_POST))
        {
            $formData = form::data($this->config->api->form->createRelease)->add('lib', $libID)->add('addedBy', $this->app->user->account)->add('addedDate', helper::now())->get();

            /* Check version is exist. */
            if(!empty($formData->version) && $this->api->getRelease($libID, 'byVersion', $formData->version)) return $this->sendError($this->lang->api->noUniqueVersion);

            $this->api->publishLib($formData);

            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => true, 'closeModal' => true, 'docApp' => array(array('executeCommand', 'loadLazyContent', array('.api-release-list')), array('load', null, null, null, array('noLoading' => true, 'picks' => 'lib')))));
        }

        $this->display();
    }

    /**
     * 接口数据结构列表页面。
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
    public function struct(int $libID = 0, int $releaseID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $this->setMenu($libID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if($releaseID)
        {
            $release = $this->api->getRelease($libID, 'byID', $releaseID);
            $structs = $this->api->getStructListByRelease($release, '1 = 1 ', $pager, $sort);
        }
        else
        {
            $structs = $this->api->getStructByQuery($libID, $pager, $sort);
        }

        $this->view->title     = $this->lang->api->struct;
        $this->view->libID     = $libID;
        $this->view->releaseID = $releaseID;
        $this->view->structs   = $structs;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->display();
    }

    /**
     * 创建数据结构页面。
     * Create struct page.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function createStruct(int $libID = 0)
    {
        $this->setMenu($libID);

        if(!empty($_POST))
        {
            $this->lang->api->name = $this->lang->api->structName;
            $formData = form::data($this->config->api->form->createStruct)->add('lib', $libID)->add('version', 1)->add('addedBy', $this->app->user->account)->add('addedDate', helper::now())->get();

            $this->api->createStruct($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('locate' => helper::createLink('api', 'struct', "libID=$libID"), 'docApp' => array('executeCommand', 'loadLazyContent', array('.api-struct-list'))));
        }

        $options = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = array('label' => $item, 'value' => $key);
        }

        $this->view->title       = $this->lang->api->createStruct;
        $this->view->typeOptions = $options;
        $this->view->gobackLink  = $this->createLink('api', 'struct', "libID=$libID");
        $this->display();
    }

    /**
     * 编辑数据结构页面。
     * Edit struct
     *
     * @param  int    $libID
     * @param  int    $structID
     * @access public
     * @return void
     */
    public function editStruct(int $libID, int $structID)
    {
        $this->setMenu($libID);

        $struct = $this->api->getStructByID($structID);

        if(!empty($_POST))
        {
            $formData = form::data($this->config->api->form->editStruct)->add('id', $structID)->add('lib', $libID)->add('editedBy', $this->app->user->account)->add('editedDate', helper::now())->get();
            $formData->version = $struct->version + 1;

            $this->api->updateStruct($formData);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'struct', "libID={$struct->lib}"), 'docApp' => array('executeCommand', 'loadLazyContent', array('.api-struct-list'))));
        }

        $options = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item)
        {
            $options[] = array('label' => $item, 'value' => $key);
        }

        $this->view->title       = $struct->name . $this->lang->api->edit;
        $this->view->struct      = $struct;
        $this->view->typeOptions = $options;
        $this->display();
    }

    /**
     * 删除一条数据结构。
     * Delete a struct.
     *
     * @param  int    $libID
     * @param  int    $structID
     * @access public
     * @return void
     */
    public function deleteStruct(int $libID, int $structID = 0)
    {
        $this->api->delete(TABLE_APISTRUCT, $structID);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => inlink('struct', "libID=$libID"), 'docApp' => array('executeCommand', 'loadLazyContent', array('.api-struct-list'))));
    }

    /**
     * 创建一个接口文档库。
     * Create a api doc library.
     *
     * @param  string $type     project|product
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function createLib(string $type = 'product', int $objectID = 0)
    {
        if(!empty($_POST))
        {
            /* 组装formData。 */
            $fields  = $this->config->api->form->createLib;
            $libType = $this->post->libType;
            if($libType == 'project') $fields['project']['required'] = true;
            if($libType == 'product') $fields['product']['required'] = true;
            $formData = form::data($fields)->add('addedBy', $this->app->user->account)->add('addedDate', helper::now())->get();
            $formData->product   = $formData->libType == 'product' && !empty($formData->product)   ? $formData->product   : 0;
            $formData->project   = $formData->libType == 'project' && !empty($formData->project)   ? $formData->project   : 0;
            $formData->execution = $formData->libType == 'project' && !empty($formData->execution) ? $formData->execution : 0;

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $libID = $this->doc->createApiLib($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $objectID = $libType == 'project' ? $this->post->project : $this->post->product;
            if(helper::isAjaxRequest('modal')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true, 'docApp' => array("selectSpace", ($libType == 'product' || $libType == 'project') ? "$libType.$objectID" : 'nolink', $libID, true)));

            /* Set locate object data. */
            setCookie("objectType", $libType, $this->config->cookieLife, $this->config->webRoot);
            setCookie("objectID",   $objectID, $this->config->cookieLife, $this->config->webRoot);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('api', 'index', "libID=$libID"), 'closeModal' => true, 'docApp' => array("selectSpace", ($libType == 'product' || $libType == 'project') ? "$libType.$objectID" : 'nolink', $libID, true)));
        }

        /* 设置默认访问控制的语言项。 */
        $defaultAclLang = in_array($type, array('product', 'product')) ? $this->lang->{$type}->common : $this->lang->product->common;
        $this->lang->api->aclList['default'] = sprintf($this->lang->api->aclList['default'], $defaultAclLang);

        $this->view->type     = $type;
        $this->view->objectID = $objectID;
        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->users    = $this->user->getPairs('nocode|noclosed');
        $this->display();
    }

    /**
     * 编辑一个接口文档库。
     * Edit an api doc library
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editLib(int $id)
    {
        $lib = $this->doc->getLibByID($id);

        if(!empty($_POST))
        {
            /* 组装formData。 */
            $formData = form::data($this->config->api->form->editLib)->add('id', $id)->get();
            $formData->product   = $lib->product;
            $formData->project   = $lib->project;
            $formData->execution = $lib->execution;

            $this->doc->updateApiLib($id, $formData);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true, 'docApp' => array('load', null, null, null, array('noLoading' => true, 'picks' => 'lib'))));
        }

        $type   = $lib->product ? 'product' : ($lib->project ? 'project' : 'nolink');
        $object = $lib->product ? $this->loadModel('product')->getByID($lib->product) : $this->loadModel('project')->getById($lib->project);

        if($type != 'nolink') $this->lang->api->aclList['default'] = sprintf($this->lang->doclib->aclList['default'], $this->lang->{$type}->common);
        if($type == 'nolink') unset($this->lang->api->aclList['default']);

        $this->view->lib      = $lib;
        $this->view->type     = $type;
        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->users    = $this->user->getPairs('nocode|noclosed');
        $this->view->projects = $this->loadModel('project')->getPairsByModel();
        $this->view->products = $this->loadModel('product')->getPairs();
        $this->view->object   = $object;

        $this->display();
    }


    /**
     * 删除一个接口文档库。
     * Delete api library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function deleteLib(int $libID)
    {
        $this->doc->delete(TABLE_DOCLIB, $libID);

        if(isInModal()) return $this->send(array('result' => 'success', 'load' => $this->createLink('api', 'index'), 'closeModal' => true, 'app' => $this->app->tab));
        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true, 'app' => $this->app->tab));
    }

    /**
     * 创建一个接口文档。
     * Create an api doc.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $space     api|project|product
     * @access public
     * @return void
     */
    public function create(int $libID, int $moduleID = 0, string $space = '')
    {
        if(!empty($_POST))
        {
            $formData = form::data($this->config->api->form->create)
                ->add('product', 0)
                ->add('version', 1)
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', helper::now())
                ->add('editedDate', helper::now())
                ->get();

            $apiID = $this->api->create($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'index', "libID={$formData->lib}&moduleID={$formData->module}&apiID={$apiID}"), 'docApp' => array('executeCommand', 'loadApi', array($apiID, 0, 0, true))));
        }

        $this->setMenu($libID, $space);

        $lib     = $this->doc->getLibByID($libID);
        $libName = isset($lib->name) ? $lib->name . $this->lang->hyphen : '';

        $this->view->title            = $libName . $this->lang->api->create;
        $this->view->allUsers         = $this->loadModel('user')->getPairs('devfirst|noclosed');
        $this->view->libID            = $libID;
        $this->view->libName          = $lib->name;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'api', $startModuleID = 0);
        $this->display();
    }

    /**
     * 编辑一个接口文档。
     * Edit library.
     *
     * @param  int    $apiID
     * @access public
     * @return void
     */
    public function edit(int $apiID)
    {
        $api = $this->api->getByID($apiID);
        if(!empty($_POST))
        {
            $formData = form::data($this->config->api->form->edit)->add('id', $apiID)->add('version', $api->version)->add('editedBy', $this->app->user->account)->get();
            /* params 字段不进行 Unicode 编码，前端传过来的 params 是 Unicode 编码后的，导致 common::createChanges 时会始终认为 params 改变。 */
            $formData->params = json_encode(json_decode(html_entity_decode($formData->params), true), JSON_UNESCAPED_UNICODE);

            $this->api->update($formData);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('locate' => helper::createLink('api', 'index', "libID=$api->lib&moduleID=0&apiID=$apiID"), 'docApp' => array(array('executeCommand', 'selectApi', array($apiID)), array('executeCommand', 'loadApi'))));
        }

        $this->setMenu($api->lib);
        $this->getTypeOptions($api->lib);

        $this->view->title            = $api->title . $this->lang->hyphen . $this->lang->api->edit;
        $this->view->api              = $api;
        $this->view->allUsers         = $this->loadModel('user')->getPairs('devfirst|noclosed');;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($api->lib, 'api', $startModuleID = 0);
        $this->display();
    }

    /**
     * 删除一个接口文档。
     * Delete an api.
     *
     * @param  int    $apiID
     * @access public
     * @return void
     */
    public function delete(int $apiID)
    {
        $api = $this->api->getByID($apiID);
        $this->api->delete(TABLE_API, $apiID);

        if(dao::isError()) $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => inlink('index', "libID=$api->lib&module=$api->module")));
    }

    /**
     * Ajax获取接口允许的参数类型列表。
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
     * Ajax获取指定目录的数据结构。
     * AJAX: Get ref options.
     *
     * @param  int    $libID
     * @param  int    $structID
     * @access public
     * @return void
     */
    public function ajaxGetRefOptions(int $libID = 0, int $structID = 0)
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
     * Ajax获取指定ID的数据结构。
     * AJAX: Get ref info.
     *
     * @param  int    $refID
     * @access public
     * @return void
     */
    public function ajaxGetRefInfo(int $refID = 0)
    {
        $info = $this->api->getStructByID($refID);
        $this->sendSuccess(array('info' => $info));
    }

    /**
     * Ajax获取当前接口库的子目录结构。
     * AJAX: Get all child module.
     *
     * @param  int    $libID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetChild(int $libID, string $type = 'module')
    {
        $this->loadModel('tree');
        $childModules = $this->tree->getOptionMenu($libID, 'api');
        $select       = ($type == 'module') ? html::select('module', $childModules, '0', "class='form-control chosen'") : html::select('parent', $childModules, '0', "class='form-control chosen'");
        echo $select;
    }

    /**
     * 设置接口文档页面的导航菜单。
     * Set api menu by method name.
     *
     * @param  int    $libID
     * @param  string $space |null|api|project|product
     * @access public
     * @return void
     */
    private function setMenu(int $libID = 0, string $space = '')
    {
        common::setMenuVars('doc', $libID);
        if($space && strpos('|api|project|product|', "|{$space}|") === false) $space = '';

        $lib = $this->loadModel('doc')->getLibByID($libID);
        if($this->app->tab == 'product')
        {
            $this->loadModel('product')->setMenu($lib->product);
        }
        elseif($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($lib->project);
        }

        if($space)
        {
            $spaceType = $space;
            $this->session->set('spaceType', $space, 'doc');
        }
        else
        {
            $spaceType = $this->session->spaceType ? $this->session->spaceType : 'api';
        }

        if(in_array($spaceType, array('product', 'project')))
        {
            $this->lang->doc->menu->api['exclude'] = 'api-' . $this->app->rawMethod . ',' . $this->app->rawMethod;
            $this->lang->doc->menu->{$spaceType}['subModule'] = 'api';
        }
        else
        {
            $this->lang->doc->menu->{$spaceType}['alias'] .= ',' . $this->app->rawMethod;
        }
    }

    /**
     * 返回当前客户端的session信息。
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
     * 调用指定模型下的指定函数，并返回结果。
     * Execute a module's model's method, return the result.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $params param1=value1,param2=value2, don't use & to join them.
     * @access public
     * @return string
     */
    public function getModel(string $moduleName, string $methodName, string $params = '')
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

        $output['status'] = $result !== false ? 'success' : 'fail';
        $output['data']   = json_encode($result);
        $output['md5']    = md5($output['data']);
        $this->output     = json_encode($output);
        print($this->output);
    }

    /**
     * 请求某个接口并返回测试结果。
     * The interface of api.
     *
     * @param  string $filePath
     * @param  string $action
     * @access public
     * @return void
     */
    public function debug(string $filePath, string $action)
    {
        $filePath    = helper::safe64Decode($filePath);
        $fileDirPath = realpath(dirname($filePath));
        if(strpos($fileDirPath, $this->app->getModuleRoot()) !== 0 && strpos($fileDirPath, $this->app->getExtensionRoot()) !== 0) return;
        if($action == 'extendModel')
        {
            $method = $this->apiZen->getMethod($filePath, 'Model');
        }
        elseif($action == 'extendControl')
        {
            $method = $this->apiZen->getMethod($filePath);
        }

        if(!empty($_POST))
        {
            $result  = $this->apiZen->request($method->className, $method->methodName, $action);
            $content = json_decode($result['content']);
            $status  = zget($content, 'status', '');
            $data    = isset($content->data) ? json_decode($content->data) : '';
            $data    = '<xmp>' . print_r($data, true) . '</xmp>';

            $response['result'] = 'success';
            $response['status'] = $status;
            $response['url']    = htmlspecialchars($result['url']);
            $response['data']   = $data;
            return print(json_encode($response));
        }

        $this->view->method   = $method;
        $this->view->filePath = $filePath;
        $this->display();
    }

    /**
     * 通过接口进行sql查询并返回结果。
     * Query sql.
     *
     * @param  string $keyField
     * @access public
     * @return string
     */
    public function sql(string $keyField = '')
    {
        if(!$this->config->features->apiSQL) return printf($this->lang->api->error->disabled, '$config->features->apiSQL');

        $sql    = isset($_POST['sql']) ? $this->post->sql : '';
        $output = $this->api->sql($sql, $keyField);

        $output['sql'] = $sql;
        $this->output  = json_encode($output);
        print($this->output);
    }

    /**
     * 获取接口类型和数据结构组成的数组。
     * Get options of type.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    private function getTypeOptions(int $libID)
    {
        $options = array();
        /* 获取接口类型。 */
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

    /**
     * Ajax获取库所属产品或者项目的下拉菜单。
     * Ajax get objectType drop menu.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(string $objectType, int $objectID, int $libID = 0)
    {
        $objects   = $this->api->getGroupedObjects();
        $programs  = $this->loadModel('program')->getList('all', 'order_asc', 'top');
        $programs  = array_filter($programs, function($program) {return $program->parent == '0';});

        $this->view->objectType = $objectType;
        $this->view->objectID   = $objectID;
        $this->view->libID      = $libID;
        $this->view->programs   = $programs;
        $this->view->products   = $objects['product'];
        $this->view->projects   = $objects['project'];
        $this->view->nolinkLibs = $objects['nolink'];
        $this->display();
    }

    /**
     * 编辑接口库下的子目录。
     * Edit a catalog.
     *
     * @param  int    $moduleID
     * @param  string $type doc|api
     * @access public
     * @return void
     */
    public function editCatalog(int $moduleID, string $type)
    {
        echo $this->fetch('tree', 'edit', "moduleID=$moduleID&type=$type");
    }

    /**
     * 删除接口库下的子目录。
     * Delete a catalog.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function deleteCatalog(int $moduleID)
    {
        echo $this->fetch('tree', 'delete', "moduleID=$moduleID&confirm=yes");
    }

    /**
     * 排序接口库下的子目录。
     * Catalog sort.
     *
     * @access public
     * @return void
     */
    public function sortCatalog()
    {
        if(!empty($_POST['orders']))
        {
            foreach($_POST['orders'] as $id => $order)
            {
                $this->dao->update(TABLE_MODULE)->set('`order`')->eq($order)->where('id')->eq($id)->andWhere('type')->eq('api')->exec();
            }
        }

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success'));
    }

    /**
     * 点击dropMenu后的页面跳转。
     * Get api list.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function ajaxGetList(int $objectID, string $objectType)
    {
        helper::setcookie('objectType', $objectType);
        helper::setcookie('objectID',   (string)$objectID);

        $this->objectType = $objectType;
        $this->objectID   = $objectID;
        $this->locate(inlink('index'));
    }
}
