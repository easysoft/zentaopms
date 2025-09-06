<?php
class apiTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('api');
    }

    /**
     * Test publish a lib.
     *
     * @param  object $data
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function publishLibTest($data, $confirm = true)
    {
        $objectID = $this->objectModel->publishLib($data);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getRelease($data->lib);

        if($confirm) $this->objectModel->deleteRelease($objectID);

        return $objects;
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
        $this->objectModel->deleteRelease($id);

        $objects = $this->objectModel->getRelease($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
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
        global $tester;

        $_POST = $params;
        $objects = $this->objectModel->create($params['lib']);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_API)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    /**
     * Test create a data struct.
     *
     * @param  object $data
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function createStructTest($data, $confirm = true)
    {
        global $tester;

        $objectID = $this->objectModel->createStruct($data);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getStructByID($objectID);

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    /**
     * Test update a data struct.
     *
     * @param  int    $id
     * @param  array  $params
     * @param  bool   $confirm
     * @access public
     * @return object
     */
    public function updateStructTest($id, $params, $confirm = true)
    {
        global $tester;

        $_POST = $params;
        $objects = $this->objectModel->updateStruct($id);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($id)->exec();

        return $objects;
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
        global $tester;
        $_POST = $params;

        $objects = $this->objectModel->update($apiID);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_API)->where('id')->eq($apiID)->exec();

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
        global $tester;

        $objects = $this->objectModel->getStructListByLibID($id);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects['0']->id)->exec();

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
        global $tester;

        $objects = $this->objectModel->getStructByID($id);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects->id)->exec();

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
        $objects = $this->objectModel->getRelease($libID = 0, $type = '', $param = 0);

        if(dao::isError()) return dao::getError();

        if($confirm) $this->objectModel->deleteRelease($objects->id);

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
        $objects = $this->objectModel->getReleaseListByApi($libID);

        if(dao::isError()) return dao::getError();

        if($confirm) $this->objectModel->deleteRelease($objects[$id]->id);

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
        $objects = $this->objectModel->getLibById($id, $version = 0, $release = 0);

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
        global $tester;

        $objects = $this->objectModel->getApiListByRelease($release, $where = '1 = 1 ');

        if(dao::isError()) return dao::getError();

        foreach($release->snap['apis'] as $api) $tester->dao->delete()->from(TABLE_API)->where('id')->eq($api['id'])->exec();
        $this->objectModel->deleteRelease($release->id);

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
        $objects = $this->objectModel->getListByModuleId($libID, $moduleID, $release);

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
        $objects = $this->objectModel->getStructListByRelease($release, $where = '1 = 1 ', $orderBy = 'id');

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
        global $tester;

        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('createDemoLib');
        $method->setAccessible(true);

        $libID = $method->invoke($this->objectModel, $name, $baseUrl, $currentAccount);

        if(dao::isError()) return dao::getError();

        $lib = $tester->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq($libID)->fetch();

        $tester->dao->delete()->from(TABLE_DOCLIB)->where('id')->eq($libID)->exec();

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
        global $tester;

        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('createDemoStruct');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $libID, $version, $currentAccount);

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
        global $tester;

        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('createDemoStructSpec');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $version, $currentAccount);

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
        global $tester;

        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('createDemoModule');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $libID, $version);

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
        global $tester;

        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('createDemoApi');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $libID, $version, $moduleMap, $currentAccount);

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
        global $tester;

        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('createDemoApiSpec');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $version, $apiMap, $moduleMap, $currentAccount);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupedObjects method.
     *
     * @access public
     * @return array
     */
    public function getGroupedObjectsTest()
    {
        $result = $this->objectModel->getGroupedObjects();

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
        global $tester;

        // 备份原始配置
        $originalConfig = isset($tester->config->api->search) ? $tester->config->api->search : null;

        // 执行方法
        $this->objectModel->buildSearchForm($lib, $queryID, $actionURL, $libs, $type);

        if(dao::isError()) return dao::getError();

        // 返回设置后的搜索配置
        $result = array(
            'module' => isset($tester->config->api->search['module']) ? $tester->config->api->search['module'] : '',
            'queryID' => isset($tester->config->api->search['queryID']) ? $tester->config->api->search['queryID'] : '',
            'actionURL' => isset($tester->config->api->search['actionURL']) ? $tester->config->api->search['actionURL'] : '',
            'libValues' => isset($tester->config->api->search['params']['lib']['values']) ? $tester->config->api->search['params']['lib']['values'] : array()
        );

        // 恢复原始配置
        if($originalConfig !== null) $tester->config->api->search = $originalConfig;

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
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('getApiSpecByData');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $data);

        if(dao::isError()) return dao::getError();

        return $result;
    }
}
