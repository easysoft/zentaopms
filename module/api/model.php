<?php
declare(strict_types=1);
/**
 * The model file of api module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class apiModel extends model
{
    /* Status. */
    const STATUS_DOING  = 'doing';
    const STATUS_DONE   = 'done';
    const STATUS_HIDDEN = 'hidden';

    /* Scope. */
    const SCOPE_QUERY     = 'query';
    const SCOPE_FORM_DATA = 'formData';
    const SCOPE_PATH      = 'path';
    const SCOPE_BODY      = 'body';
    const SCOPE_HEADER    = 'header';
    const SCOPE_COOKIE    = 'cookie';

    /* Params. */
    const PARAMS_TYPE_CUSTOM = 'custom';

    /**
     * 发布接口。
     * Create release.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function publishLib(object $formData): bool
    {
        /* Get lib modules list. */
        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$formData->lib)
            ->andWhere('type')->eq('api')
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->fetchAll();

        /* Get all api list. */
        $apis = $this->dao->select('id,version')->from(TABLE_API)
            ->where('lib')->eq($formData->lib)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();

        /* Get all struct list. */
        $structs = $this->dao->select('id,version')->from(TABLE_APISTRUCT)
            ->where('lib')->eq($formData->lib)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();

        $snap = array('modules' => $modules, 'apis' => $apis, 'structs' => $structs);

        $formData->snap = json_encode($snap);
        $this->dao->insert(TABLE_API_LIB_RELEASE)->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->api->createrelease->requiredFields, 'notempty')
            ->exec();

        return !dao::isError();
    }

    /**
     * 删除一条发布。
     * Delete a lib publish.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function deleteRelease(int $id): bool
    {
        $this->dao->delete()->from(TABLE_API_LIB_RELEASE)->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * 创建一个接口。
     * Create an api doc.
     *
     * @param  object    $formData
     * @access public
     * @return int|false
     */
    public function create(object $formData): int|false
    {
        $this->dao->insert(TABLE_API)->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->api->create->requiredFields, 'notempty')
            ->check('title', 'unique', "lib = $formData->lib AND module = $formData->module")
            ->check('path',  'unique', "lib = $formData->lib AND module = $formData->module AND method = '$formData->method'")
            ->exec();

        if(dao::isError()) return false;

        /* 维护历史记录。 */
        $apiID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('api', $apiID, 'Created', '', '', '', false);

        /* 维护接口文档的历史版本。 */
        $formData->id = $apiID;
        $apiSpec      = $this->getApiSpecByData($formData);
        $this->dao->replace(TABLE_API_SPEC)->data($apiSpec)->exec();

        return $apiID;
    }

    /**
     * 更新一个接口。
     * Update an api doc.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function update(object $formData): bool
    {
        $oldApi = $this->dao->findByID($formData->id)->from(TABLE_API)->fetch();

        if(!empty($formData->editedDate) && $oldApi->editedDate != $formData->editedDate)
        {
            /* 如果提交之前已有其他人变更接口则提示错误信息。 */
            dao::$errors['message'][] = $this->lang->error->editedByOther;
            return false;
        }
        $formData->editedDate = helper::now();

        /* 仅在有变更的情况下才变更版本号。 */
        $changes = common::createChanges($oldApi, $formData);
        if(!empty($changes)) $formData->version = $oldApi->version + 1;

        $this->dao->update(TABLE_API)
            ->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->api->edit->requiredFields, 'notempty')
            ->check('title', 'unique', "id != $oldApi->id AND lib = $oldApi->lib AND module = $formData->module")
            ->check('path',  'unique', "id != $oldApi->id AND lib = $oldApi->lib AND module = $formData->module AND method = '$formData->method'")
            ->where('id')->eq($formData->id)
            ->exec();

        if(dao::isError()) return false;

        /* 维护历史记录。 */
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('api', $formData->id, 'edited', '', '', '', false);
            $this->action->logHistory($actionID, $changes);
        }

        /* 维护接口文档的历史版本。 */
        $apiSpec = $this->getApiSpecByData($formData);
        $this->dao->replace(TABLE_API_SPEC)->data($apiSpec)->exec();

        return !dao::isError();
    }

    /**
     * 创建数据结构。
     * Create a global struct.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function createStruct(object $formData): bool
    {
        $this->dao->insert(TABLE_APISTRUCT)->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->api->struct->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        /* 维护历史记录。 */
        $structID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('apistruct', $structID, 'Created');

        /* 维护数据结构的历史版本。 */
        $structSpec = $this->getApiStructSpecByData($formData);
        $this->dao->insert(TABLE_APISTRUCT_SPEC)->data($structSpec)->exec();
        return !dao::isError();
    }

    /**
     * 更新数据结构。
     * Update a struct.
     *
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function updateStruct(object $formData): bool
    {
        $oldData = $this->dao->findByID($formData->id)->from(TABLE_APISTRUCT)->fetch();

        $this->dao->update(TABLE_APISTRUCT)
            ->data($formData)->autoCheck()
            ->batchCheck($this->config->api->struct->requiredFields, 'notempty')
            ->where('id')->eq($formData->id)
            ->exec();

        if(dao::isError()) return false;

        /* 维护历史记录。 */
        $changes = common::createChanges($oldData, $formData);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('apistruct', $formData->id, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }

        /* 维护数据结构的历史版本。 */
        $structSpec = $this->getApiStructSpecByData($formData);
        $this->dao->insert(TABLE_APISTRUCT_SPEC)->data($structSpec)->exec();

        return !dao::isError();
    }

    /**
     * 根据文档库ID获取数据结构列表。
     * Get struct list by api doc id.
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getStructListByLibID(int $id): array
    {
        $structList = $this->dao->select('*')->from(TABLE_APISTRUCT)->where('lib')->eq($id)->fetchAll();
        foreach($structList as $struct) $struct->attribute = json_decode($struct->attribute, true);

        return $structList;
    }

    /**
     * 根据ID获取数据结构信息。
     * Get a struct info.
     *
     * @param  int          $id
     * @access public
     * @return object|false
     */
    public function getStructByID(int $id): object|false
    {
        $struct = $this->dao->select('*')->from(TABLE_APISTRUCT)->where('id')->eq($id)->fetch();
        if($struct) $struct->attribute = json_decode($struct->attribute, true);

        return $struct;
    }

    /**
     * 根据版本号或者发布ID获取发布信息。
     * Get release.
     *
     * @param  int          $libID
     * @param  string       $type   byVersion | byID
     * @param  string|int   $param
     * @access public
     * @return object|false
     */
    public function getRelease(int $libID = 0, string $type = 'byID', string|int $param = '0'): object|false
    {
        $release = $this->dao->select('*')->from(TABLE_API_LIB_RELEASE)
            ->where('1 = 1')
            ->beginIF($libID)->andWhere('lib')->eq($libID)->fi()
            ->beginIF($type == 'byVersion')->andWhere('version')->eq($param)->fi()
            ->beginIF($type == 'byID')->andWhere('id')->eq($param)->fi()
            ->fetch();

        if($release) $release->snap = json_decode($release->snap, true);
        return $release;
    }

    /**
     * 根据文档库ID获取发布列表。
     * Get releases by lib id.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getReleaseListByLib(int $libID): array
    {
        return $this->dao->select('*')->from(TABLE_API_LIB_RELEASE)->where('lib')->eq($libID)->fetchAll('id');
    }

    /**
     * 根据接口ID获取接口信息。
     * Get api doc by id.
     *
     * @param  int          $id
     * @param  int          $version
     * @param  int          $releaseID
     * @access public
     * @return object|false
     */
    public function getByID(int $id, int $version = 0, int $releaseID = 0): object|false
    {
        if($releaseID)
        {
            $release = $this->getRelease(0, 'byID', $releaseID);
            if(!empty($release->snap['apis']))
            {
                foreach($release->snap['apis'] as $api)
                {
                    if($api['id'] == $id) $version = $api['version'];
                }
            }
        }

        /* 如果要根据版本号查询，那主要查询的是spec表，否则查询api表即可。 */
        if($version)
        {
            $fields = 'spec.*,api.id,api.product,api.lib,api.version,doc.name as libName,module.name as moduleName,api.editedBy,api.editedDate';
        }
        else
        {
            $fields = 'api.*,doc.name as libName,module.name as moduleName';
        }

        $api = $this->dao->select($fields)->from(TABLE_API)->alias('api')
            ->beginIF($version)->leftJoin(TABLE_API_SPEC)->alias('spec')->on('api.id = spec.doc')->fi()
            ->leftJoin(TABLE_DOCLIB)->alias('doc')->on('api.lib = doc.id')
            ->leftJoin(TABLE_MODULE)->alias('module')->on('api.module = module.id')
            ->where('api.id')->eq($id)
            ->beginIF($version)->andWhere('spec.version')->eq($version)->fi()
            ->fetch();

        if($api)
        {
            $api->params   = json_decode($api->params,   true);
            $api->response = json_decode($api->response, true);
        }

        return $api;
    }

    /**
     * 获取发布下的所有接口文档。
     * Get api list by release.
     *
     * @param  object $release
     * @param  string $where
     * @access public
     * @return array
     */
    public function getApiListByRelease(object $release, string $where = '1 = 1 '): array
    {
        /* 根据发布中的apis生成查询条件。 */
        $strJoin = array();
        if(isset($release->snap['apis']))
        {
            foreach($release->snap['apis'] as $api)
            {
                $strJoin[] = "(spec.doc = {$api['id']} and spec.version = {$api['version']} )";
            }
        }
        if($strJoin) $where .= 'and (' . implode(' or ', $strJoin) . ')';

        $apiList = $this->dao->select('api.lib,spec.*,api.id')->from(TABLE_API)->alias('api')
            ->leftJoin(TABLE_API_SPEC)->alias('spec')->on('api.id = spec.doc')
            ->where($where)
            ->fetchAll();

        return $apiList;
    }

    /**
     * 根据发布ID或者模块ID获取接口列表。
     * Get api doc list by module id or release id.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $releaseID
     * @param  object $pager
     * @return array
     */
    public function getListByModuleID(int $libID = 0, int $moduleID = 0, int $releaseID = 0, object $pager = null): array
    {
        /* Get release info. */
        if($releaseID > 0)
        {
            /* 根据发布ID获取发信息。 */
            $release = $this->getRelease(0, 'byID', $releaseID);

            $where = "1 = 1 and lib = $libID ";
            if($moduleID > 0 && isset($release->snap['modules']))
            {
                /* 根据发布中的modules生成查询条件。 */
                $sub = array();
                foreach($release->snap['modules'] as $module)
                {
                    $tmp = explode(',', $module['path']);
                    if(in_array($moduleID, $tmp)) $sub[] = $module['id'];
                }
                if($sub) $where .= 'and module in (' . implode(',', $sub) . ')';
            }
            $apiList = $this->getApiListByRelease($release, $where);
        }
        else
        {
            /* 根据模块ID获取发布信息。 */
            if($moduleID > 0)
            {
                $sub   = $this->dao->select('id')->from(TABLE_MODULE)->where('FIND_IN_SET(' . $moduleID . ', path)')->processSQL();
                $where = 'module in (' . $sub . ')';
            }
            else
            {
                /* 没有模块ID的根据libID来获取发布信息。 */
                $where = 'lib = ' . $libID;
            }
            $apiList = $this->dao->select('*')->from(TABLE_API)->where($where)->andWhere('deleted')->eq(0)->page($pager)->fetchAll();
        }

        foreach($apiList as $api)
        {
            $api->params   = json_decode($api->params, true);
            $api->response = json_decode($api->response, true);
        }
        return $apiList;
    }

    /**
     * 获取接口状态对应的语言项。
     * Get status text by status.
     *
     * @param  string $status
     * @access public
     * @return string
     */
    public static function getApiStatusText(string $status): string
    {
        global $lang;

        switch($status)
        {
            case static::STATUS_DOING:
            {
                return $lang->api->doing;
            }
            case static::STATUS_DONE:
            {
                return $lang->api->done;
            }
        }
        return $status;
    }

    /**
     * 获取指定文档库下的数据结构列表。
     * Get struct list by lib id.
     *
     * @param  int    $libID
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStructByQuery(int $libID, object $pager = null, string $orderBy = ''): array
    {
        return $this->dao->select('t1.*,t2.realname as addedName')->from(TABLE_APISTRUCT)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t2.account = t1.addedBy')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.lib')->eq($libID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取指定发布下的数据结构列表。
     * Get struct list by release.
     *
     * @param  object $release
     * @param  string $where
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStructListByRelease(object $release, string $where = '1 = 1 ', object $pager = null, string $orderBy = 'id'): array
    {
        $strJoin = array();
        if(isset($release->snap['structs']))
        {
            /* 根据发布中的structs生成查询条件。 */
            foreach($release->snap['structs'] as $struct)
            {
                $strJoin[] = "(object.id = {$struct['id']} and spec.version = {$struct['version']} )";
            }
        }
        if($strJoin) $where .= 'and (' . implode(' or ', $strJoin) . ')';

        return $this->dao->select('object.id,object.lib,spec.name,spec.type,spec.desc,spec.attribute,spec.version,spec.addedBy,spec.addedDate,user.realname as addedName')
            ->from(TABLE_APISTRUCT)->alias('object')
            ->leftJoin(TABLE_APISTRUCT_SPEC)->alias('spec')->on('object.name = spec.name')
            ->leftJoin(TABLE_USER)->alias('user')->on('user.account = spec.addedBy')
            ->where($where)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取指定文档库下的数据结构列表。
     * Get release list by lib id.
     *
     * @param  array  $libID
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReleaseByQuery(array $libID, object $pager = null, string $orderBy = ''): array
    {
        return $this->dao->select('*')->from(TABLE_API_LIB_RELEASE)
            ->where('lib')->in($libID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 查询SQL语句并返回结果。
     * Query sql.
     *
     * @param  string $sql
     * @param  string $keyField
     * @access public
     * @return array
     */
    public function sql(string $sql, string $keyField = ''): array
    {
        /* 检查允许接口调用SQL的配置项是否打开。 */
        if(!$this->config->features->apiSQL) return sprintf($this->lang->api->error->disabled, '$config->features->apiSQL');

        $sql = trim($sql);
        if(strpos($sql, ';') !== false) $sql = substr($sql, 0, strpos($sql, ';'));

        /* 如果没传SQL参数，则无法进行下一步。 */
        if(empty($sql)) return array('status' => 'fail', 'message' => '');

        /* 如果SQL语句中没有select单词，则无法进行下一步。 */
        if(stripos($sql, 'select ') !== 0) return array('status' => 'fail', 'message' => $this->lang->api->error->onlySelect);

        try
        {
            $stmt = $this->dbh->query($sql);
            $rows = array();
            if(empty($keyField))
            {
                $rows = $stmt->fetchAll();
            }
            else
            {
                /* 用keyFiled作为键展示查询结果。 */
                while($row = $stmt->fetch()) $rows[$row->$keyField] = $row;
            }

            $result = array('status' => 'success', 'data' => $rows);
        }
        catch(PDOException $e)
        {
            $result = array('status' => 'fail', 'message' => $e->getMessage());
        }

        return $result;
    }

    /**
     * Create demo data.
     *
     * @param  string  $name
     * @param  string  $baseUrl
     * @param  string  $version
     * @access public
     * @return int
     */
    public function createDemoData($name, $baseUrl, $version = '16.0')
    {
        /* Replace the doc lib name to api lib name. */
        $this->app->loadLang('doc');
        $this->lang->doclib->name = $this->lang->doclib->apiLibName;

        $firstAccount   = $this->dao->select('account')->from(TABLE_USER)->orderBy('id_asc')->limit(1)->fetch('account');
        $currentAccount = isset($this->app->user->account) ? $this->app->user->account : $firstAccount;

        /* Insert doclib. */
        $lib = new stdclass();
        $lib->type      = 'api';
        $lib->name      = $name;
        $lib->baseUrl   = $baseUrl;
        $lib->acl       = 'open';
        $lib->users     = ',' . $currentAccount . ',';
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->api->createlib->requiredFields, 'notempty')
            ->check('name', 'unique', "`type` = 'api'")
            ->exec();

        $libID = $this->dao->lastInsertID();

        /* Insert struct. */
        $structMap = array();
        $structs   = $this->getDemoData('apistruct', $version);
        foreach($structs as $struct)
        {
            $oldID = $struct->id;
            unset($struct->id);

            $struct->lib        = $libID;
            $struct->addedBy    = $currentAccount;
            $struct->addedDate  = helper::now();
            $struct->editedBy   = $currentAccount;
            $struct->editedDate = helper::now();

            $this->dao->insert(TABLE_APISTRUCT)->data($struct)->exec();
            $newID = $this->dao->lastInsertID();

            $structMap[$oldID] = $newID;
        }

        /* Insert struct spec. */
        $specs = $this->getDemoData('apistruct_spec', $version);
        foreach($specs as $spec)
        {
            unset($spec->id);

            $spec->addedBy   = $currentAccount;
            $spec->addedDate = helper::now();

            $this->dao->insert(TABLE_APISTRUCT_SPEC)->data($spec)->exec();
        }

        /* Insert module. */
        $modules = $this->getDemoData('module', $version);
        foreach($modules as $module)
        {
            if($module->type != 'api') continue;

            $oldID = $module->id;
            unset($module->id);

            $module->root = $libID;

            $this->dao->insert(TABLE_MODULE)->data($module)->exec();
            $newID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_MODULE)->set('path')->eq(",$newID,")->where('id')->eq($newID)->exec();

            $moduleMap[$oldID] = $newID;
        }

        /* Insert api. */
        $this->loadModel('action');
        $apiMap = array();
        $apis   = $this->getDemoData('api', $version);
        foreach($apis as $api)
        {
            $oldID = $api->id;
            unset($api->id);

            $api->lib        = $libID;
            $api->module     = $moduleMap[$api->module];
            $api->addedBy    = $currentAccount;
            $api->addedDate  = helper::now();
            $api->editedBy   = $currentAccount;
            $api->editedDate = helper::now();

            $this->dao->insert(TABLE_API)->data($api)->exec();
            $newID = $this->dao->lastInsertID();

            $this->action->create('api', $newID, 'Created', '', '', $currentAccount);

            $apiMap[$oldID] = $newID;
        }

        /* Insert api spec. */
        $specs = $this->getDemoData('apispec', $version);
        foreach($specs as $spec)
        {
            unset($spec->id);

            $spec->doc       = $apiMap[$spec->doc];
            $spec->module    = zget($moduleMap, $spec->module, 0);
            $spec->owner     = $currentAccount;
            $spec->addedBy   = $currentAccount;
            $spec->addedDate = helper::now();

            $this->dao->insert(TABLE_API_SPEC)->data($spec)->exec();
        }

        return $libID;
    }

    /**
     * Get demo data.
     *
     * @param  string   $table
     * @param  string   $version
     * @access public
     * @return array
     */
    public function getDemoData($table, $version)
    {
        $file = $this->app->getAppRoot() . 'db' . DS . 'api' . DS . $version . DS . $table;
        return unserialize(preg_replace_callback('#s:(\d+):"(.*?)";#s', function($match){return 's:'.strlen($match[2]).':"'.$match[2].'";';}, file_get_contents($file)));
    }

     /**
     * Build search form.
     *
     * @param  object $lib
     * @param  string $queryID
     * @param  string $actionURL
     * @param  array  $libs
     * @param  string $type product|project
     * @access public
     * @return void
     */
    public function buildSearchForm($lib, $queryID, $actionURL, $libs = array(), $type = '')
    {
        if(empty($lib)) return;
        $libPairs = array('' => '', $lib->id => $lib->name);
        $this->config->api->search['module'] = 'api';
        if(!empty($libs))
        {
            foreach($libs as $lib)
            {
                if(empty($lib)) continue;
                if($lib->type != 'api') continue;
                $libPairs[$lib->id] = $lib->name;
            }
            $this->config->api->search['module'] = !empty($type) ? $type . 'apiDoc' : 'api';
        }

        $this->config->api->search['queryID']                 = $queryID;
        $this->config->api->search['actionURL']               = $actionURL;
        $this->config->api->search['params']['lib']['values'] = $libPairs + array('all' => $this->lang->api->allLibs);

        $this->loadModel('search')->setSearchParams($this->config->api->search);
    }

    /**
     * Get api by search.
     *
     * @param  string $libID
     * @param  int    $queryID
     * @param  string $objectType product|project
     * @param  array  $libs
     * @access public
     * @return array
     */
    public function getApiListBySearch($libID, $queryID, $objectType = '', $libs = array())
    {
        $queryName = $objectType ? $objectType . 'apiDocQuery' : 'apiQuery';
        $queryForm = $objectType ? $objectType . 'apiDocForm' : 'apiForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryForm, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->$queryName == false) $this->session->set($queryName, ' 1 = 1');
        }

        $apiQuery = $this->session->$queryName;
        if(strpos($apiQuery, "`lib` = 'all'") !== false) $apiQuery = str_replace("`lib` = 'all'", '1', $apiQuery);

        $list = $this->dao->select('*')
            ->from(TABLE_API)
            ->where('deleted')->eq(0)
            ->andWhere($apiQuery)
            ->beginIF(!empty($libs))->andWhere('`lib`')->in($libs)->fi()
            ->fetchAll();

        return $list;
    }

    /**
     * Get ordered objects for dic.
     *
     * @access public
     * @return array
     */
    public function getOrderedObjects()
    {
        $normalObjects = $closedObjects = array();

        $libs     = $this->loadModel('doc')->getApiLibs();
        $products = $this->dao->select('t1.id, t1.order, t1.name, t1.status')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t2.product=t1.id')
            ->where('t2.id')->gt(0)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.id')->in(array_keys($libs))
            ->orderBy('order_asc')
            ->fetchAll('id');

        foreach($products as $id => $product)
        {
            if($product->status == 'normal')
            {
                $normalObjects['product'][$id] = $product->name;
            }
            elseif($product->status == 'closed')
            {
                $closedObjects['product'][$id] = $product->name;
            }
        }

        $projects = $this->dao->select('t1.id, t1.order, t1.name, t1.status')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t2.project=t1.id')
            ->where('t2.id')->gt(0)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t2.id')->in(array_keys($libs))
            ->orderBy('t1.hasProduct_asc, order_asc')
            ->fetchAll('id');

        foreach($projects as $id => $project)
        {
            if($project->status != 'done' and $project->status != 'closed')
            {
                $normalObjects['project'][$id] = $project->name;
            }
            elseif($project->status == 'done' or $project->status == 'closed')
            {
                $closedObjects['project'][$id] = $project->name;
            }
        }

        return array($normalObjects, $closedObjects);
    }

    /**
     * Get priv Apis..
     *
     * @param  string $mode all
     * @access public
     * @return array
     */
    public function getPrivApis($mode = '')
    {
        $libs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('type')->eq('api')
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchAll('id');

        $this->loadModel('doc');
        foreach($libs as $libID => $lib)
        {
            if(!$this->doc->checkPrivLib($lib)) unset($libs[$libID]);
        }

        return $this->dao->select('id')->from(TABLE_API)
            ->where('lib')->in(array_keys($libs))
            ->beginIF($mode != 'all')->andWhere('deleted')->eq(0)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取创建接口版本时所需的数据。
     * Get spec of api.
     *
     * @param  object  $data
     * @access private
     * @return array
     */
    private function getApiSpecByData(object $data): array
    {
        return array(
            'doc'             => $data->id,
            'title'           => $data->title,
            'path'            => $data->path,
            'module'          => !empty($data->module)          ? $data->module          : 0,
            'protocol'        => !empty($data->protocol)        ? $data->protocol        : 'HTTP',
            'method'          => !empty($data->method)          ? $data->method          : 'GET',
            'requestType'     => !empty($data->requestType)     ? $data->requestType     : '',
            'responseType'    => !empty($data->responseType)    ? $data->responseType    : '',
            'status'          => !empty($data->status)          ? $data->status          : 'done',
            'owner'           => !empty($data->owner)           ? $data->owner           : '',
            'desc'            => !empty($data->desc)            ? $data->desc            : '',
            'version'         => !empty($data->version)         ? $data->version         : 1,
            'params'          => !empty($data->params)          ? $data->params          : '',
            'paramsExample'   => !empty($data->paramsExample)   ? $data->paramsExample   : '',
            'responseExample' => !empty($data->responseExample) ? $data->responseExample : '',
            'response'        => !empty($data->response)        ? $data->response        : '',
            'addedBy'         => $this->app->user->account,
            'addedDate'       => helper::now(),
        );
    }

    /**
     * 获取创建数据结构版本时所需的数据。
     * Get struct spec of api.
     *
     * @param  object  $data
     * @access private
     * @return array
     */
    private function getApiStructSpecByData(object $data): array
    {
        return array(
            'name'      => $data->name,
            'type'      => !empty($data->type)      ? $data->type      : '',
            'desc'      => !empty($data->desc)      ? $data->desc      : '',
            'version'   => !empty($data->version)   ? $data->version   : 1,
            'attribute' => !empty($data->attribute) ? $data->attribute : '',
            'addedBy'   => !empty($data->addedBy)   ? $data->addedBy   : $this->app->user->account,
            'addedDate' => !empty($data->addedDate) ? $data->addedDate : helper::now()
        );
    }
}
