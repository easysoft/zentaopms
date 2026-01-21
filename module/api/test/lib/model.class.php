<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class apiModelTest extends baseTest
{
    protected $moduleName = 'api';
    protected $className  = 'model';

    /**
     * Test publish a lib.
     *
     * @param  object $data
     * @access public
     * @return mixed
     */
    public function publishLibTest($data)
    {
        $result = $this->instance->publishLib($data);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test delete a release.
     *
     * @param  int    $id
     * @param  int    $libID
     * @access public
     * @return object
     */
    public function deleteReleaseTest($id, $libID = 0)
    {
        $this->instance->deleteRelease($id);

        $objects = $this->instance->getRelease($libID);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test getApiStatusText method.
     *
     * @param  string $status
     * @access public
     * @return string
     */
    public function getApiStatusTextTest($status)
    {
        $result = $this->instance->getApiStatusText($status);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test create a api.
     *
     * @param  array  $params
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function createTest($params, $confirm = true)
    {
        $_POST   = $params;
        $objects = $this->instance->create($params['lib']);
        if(dao::isError()) return dao::getError();

        if($confirm) $this->instance->dao->delete()->from(TABLE_API)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    /**
     * Test create a data struct.
     *
     * @param  object $data
     * @access public
     * @return mixed
     */
    public function createStructTest($data)
    {
        $result = $this->instance->createStruct($data);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test update a data struct.
     *
     * @param  object $formData
     * @access public
     * @return mixed
     */
    public function updateStructTest($formData)
    {
        $result = $this->instance->updateStruct($formData);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test update a api.
     *
     * @param  int    $apiID
     * @param  array  $params
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function updateTest($apiID, $params, $confirm = true)
    {
        $_POST   = $params;
        $objects = $this->instance->update($apiID);
        if(dao::isError()) return dao::getError();

        if($confirm) $this->instance->dao->delete()->from(TABLE_API)->where('id')->eq($apiID)->exec();

        return $objects;
    }

    /**
     * Test get data struct list by libID.
     *
     * @param  int    $id
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function getStructListByLibIDTest($id, $confirm = true)
    {
        $objects = $this->instance->getStructListByLibID($id);
        if(dao::isError()) return dao::getError();

        if($confirm) $this->instance->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects['0']->id)->exec();

        return $objects;
    }

    /**
     * Test get a data struct by ID.
     *
     * @param  int    $id
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function getStructByIDTest($id, $confirm = true)
    {
        $objects = $this->instance->getStructByID($id);
        if(dao::isError()) return dao::getError();

        if($confirm) $this->instance->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    /**
     * Test get release by libID.
     *
     * @param  int    $libID
     * @param  string $type
     * @param  int    $params
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function getReleaseTest($libID = 0, $type = '', $param = 0, $confirm = true)
    {
        $objects = $this->instance->getRelease($libID = 0, $type = '', $param = 0);
        if(dao::isError()) return dao::getError();

        if($confirm) $this->instance->deleteRelease($objects->id);

        return $objects;
    }

    /**
     * Test get release list by api.
     *
     * @param  int    $libID
     * @param  int    $id
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function getReleaseListByApiTest($libID, $id = 0, $confirm = true)
    {
        $objects = $this->instance->getReleaseListByApi($libID);
        if(dao::isError()) return dao::getError();

        if($confirm) $this->instance->deleteRelease($objects[$id]->id);

        return $objects[$id];
    }

    /**
     * Test get lib by ID.
     *
     * @param  int    $id
     * @param  int    $version
     * @param  int    $release
     * @access public
     * @return object
     */
    public function getLibByIdTest($id, $version = 0, $release = 0)
    {
        $objects = $this->instance->getLibById($id, $version = 0, $release = 0);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get lib by ID.
     *
     * @param  int    $id
     * @param  int    $version
     * @param  int    $release
     * @access public
     * @return object
     */
    public function getApiListByReleaseTest($release, $where = '1 = 1 ')
    {
        $objects = $this->instance->getApiListByRelease($release, $where = '1 = 1 ');
        if(dao::isError()) return dao::getError();

        foreach($release->snap['apis'] as $api) $this->instance->dao->delete()->from(TABLE_API)->where('id')->eq($api['id'])->exec();
        $this->instance->deleteRelease($release->id);

        return $objects;
    }

    /**
     * Test get list by module ID.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $release
     * @access public
     * @return void
     */
    public function getListByModuleIdTest($libID = 0, $moduleID = 0, $release = 0)
    {
        $objects = $this->instance->getListByModuleId($libID, $moduleID, $release);
        if(dao::isError()) return dao::getError();
        return current($objects);
    }

    /**
     * Test get struct list by release.
     *
     * @param  object $release
     * @param  string $where
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getStructListByReleaseTest($release, $where = '1 = 1 ', $orderBy = 'id')
    {
        $objects = $this->instance->getStructListByRelease($release, $where = '1 = 1 ', $orderBy = 'id');
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test createDemoLib method.
     *
     * @param  string $name
     * @param  string $baseUrl
     * @param  string $currentAccount
     * @access public
     * @return object|false
     */
    public function createDemoLibTest($name, $baseUrl, $currentAccount)
    {
        $libID = $this->invokeArgs('createDemoLib', [$name, $baseUrl, $currentAccount]);
        if(dao::isError()) return dao::getError();

        $lib = $this->instance->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq($libID)->fetch();
        $this->instance->dao->delete()->from(TABLE_DOCLIB)->where('id')->eq($libID)->exec();

        return $lib;
    }

    /**
     * Test createDemoStruct method.
     *
     * @param  int    $libID
     * @param  string $version
     * @param  string $currentAccount
     * @access public
     * @return mixed
     */
    public function createDemoStructTest($libID, $version, $currentAccount)
    {
        $result = $this->invokeArgs('createDemoStruct', [$libID, $version, $currentAccount]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test createDemoStructSpec method.
     *
     * @param  string $version
     * @param  string $currentAccount
     * @access public
     * @return mixed
     */
    public function createDemoStructSpecTest($version, $currentAccount)
    {
        $result = $this->invokeArgs('createDemoStructSpec', [$version, $currentAccount]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test createDemoModule method.
     *
     * @param  int    $libID
     * @param  string $version
     * @access public
     * @return mixed
     */
    public function createDemoModuleTest($libID, $version)
    {
        $result = $this->invokeArgs('createDemoModule', [$libID, $version]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test createDemoApi method.
     *
     * @param  int    $libID
     * @param  string $version
     * @param  array  $moduleMap
     * @param  string $currentAccount
     * @access public
     * @return mixed
     */
    public function createDemoApiTest($libID, $version, $moduleMap, $currentAccount)
    {
        $result = $this->invokeArgs('createDemoApi', [$libID, $version, $moduleMap, $currentAccount]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test createDemoApiSpec method.
     *
     * @param  string $version
     * @param  array  $apiMap
     * @param  array  $moduleMap
     * @param  string $currentAccount
     * @access public
     * @return mixed
     */
    public function createDemoApiSpecTest($version, $apiMap, $moduleMap, $currentAccount)
    {
        // 先检查版本文件是否存在，避免后续的file_get_contents错误
        $demoDataFile = $this->instance->app->getAppRoot() . 'db' . DS . 'api' . DS . $version . DS . 'apispec';
        if(!file_exists($demoDataFile)) return 0;

        $result = $this->invokeArgs('createDemoApiSpec', [$version, $apiMap, $moduleMap, $currentAccount]);
        if(dao::isError()) return dao::getError();
        return $result ? 1 : 0;
    }

    /**
     * Test getGroupedObjects method.
     *
     * @access public
     * @return array
     */
    public function getGroupedObjectsTest()
    {
        $result = $this->instance->getGroupedObjects();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  object $lib
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  array  $libs
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest($lib, $queryID, $actionURL, $libs = array(), $type = '')
    {
        // 备份原始配置
        $originalConfig = isset($this->instance->config->api->search) ? $this->instance->config->api->search : null;

        // 执行方法
        $this->instance->buildSearchForm($lib, $queryID, $actionURL, $libs, $type);
        if(dao::isError()) return dao::getError();

        // 返回设置后的搜索配置
        $result = $this->instance->config->api->search;

        // 恢复原始配置
        if($originalConfig !== null) $this->instance->config->api->search = $originalConfig;

        return $result;
    }

    /**
     * Test getApiSpecByData method.
     *
     * @param  object $data
     * @access public
     * @return array
     */
    public function getApiSpecByDataTest($data)
    {
        $result = $this->invokeArgs('getApiSpecByData', [$data]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getApiStructSpecByData method.
     *
     * @param  object $data
     * @access public
     * @return array
     */
    public function getApiStructSpecByDataTest($data)
    {
        $result = $this->invokeArgs('getApiStructSpecByData', [$data]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTypeList method.
     *
     * @param  int $libID
     * @access public
     * @return array
     */
    public function getTypeListTest($libID)
    {
        $result = $this->instance->getTypeList($libID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getPrivApis method.
     *
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getPrivApisTest($mode = '')
    {
        $result = $this->instance->getPrivApis($mode);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
