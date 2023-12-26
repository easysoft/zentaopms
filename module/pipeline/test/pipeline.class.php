<?php
class pipelineTest
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct(string $account = 'admin')
    {
        su($account);

        global $tester, $app;
        $this->objectModel = $tester->loadModel('pipeline');

        $app->rawModule = 'pipeline';
        $app->rawMethod = 'index';
        $app->setModuleName('pipeline');
        $app->setMethodName('index');
    }


    /**
     * 根据id获取一条服务器记录。
     * Get a pipeline by id.
     *
     * @param  int          $id
     * @access public
     * @return object|false
     */
    public function getByIDTest(int $id): object|false
    {
        $pipeline = $this->objectModel->getByID($id);

        if(dao::isError()) return dao::getError();
        return $pipeline;
    }

    /**
     * 获取服务器列表。
     * Get pipeline list.
     *
     * @param  string $type       jenkins|gitlab
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function getListTest(string $type = 'jenkins', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1): array
    {
        $this->objectModel->app->loadClass('pager', true);

        $pager        = new pager(0, $recPerPage, $pageID);
        $pipelineList = $this->objectModel->getList($type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();
        return $pipelineList;
    }

    /**
     * 创建服务器。
     * Create a server.
     *
     * @param  string       $type
     * @param  array        $object
     * @access public
     * @return array|object
     */
    public function createTest(string $type, object $object)
    {
        $object->type = $type;

        $pipelineID = $this->objectModel->create($object);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($pipelineID);
    }

    /**
     * 更新服务器。
     * Update a server.
     *
     * @param  int        $id
     * @param  array      $data
     * @access public
     * @return array|bool
     */
    public function updateTest(int $id, array $data): array|bool
    {
        $oldObject = $this->objectModel->getByID($id);

        $server = new stdclass();
        foreach($data as $key => $value) $server->{$key}  = $value;

        $this->objectModel->update($id, $server);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($id);
        return $object ? common::createChanges($oldObject, $object) : array();
    }

    /**
     * 根据名称及类型获取一条流水线记录。
     * Get a pipeline by name and type.
     *
     * @param  string $name
     * @param  string $type
     * @access public
     * @return object|false|array
     */
    public function getByNameAndTypeTest(string $name, string $type): object|false|array
    {
        $pipeline = $this->objectModel->getByNameAndType($name, $type);

        if(dao::isError()) return dao::getError();
        return $pipeline;
    }

    /**
     * 根据url获取渠成创建的代码库。
     * Get a pipeline by url which created by quickon.
     *
     * @param  string $url
     * @access public
     * @return object|false|array
     */
    public function getByUrlTest(string $url): object|false|array
    {
        $pipeline = $this->objectModel->getByUrl($url);

        if(dao::isError()) return dao::getError();
        return $pipeline;
    }

    /**
     * 获取服务器列表。
     * Get pipeline pairs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairsTest(string $type): array
    {
        $pipelinePairs = $this->objectModel->getPairs($type);

        if(dao::isError())  return dao::getError();
        return $pipelinePairs;
    }

    /**
     * 删除服务器。
     * Delete one record.
     *
     * @param  int               $id
     * @param  string            $type
     * @access public
     * @return array|object|bool
     */
    public function deleteByObjectTest(int $id, string $type): array|object|bool
    {
        $this->objectModel->deleteByObject($id, $type);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($id);
    }

    /**
     * 获取禅道用户绑定的第三方账号。
     * Get user binded third party accounts.
     *
     * @param  int    $providerID
     * @param  string $providerType gitlab, gitea, gogs
     * @param  string $fields
     * @access public
     * @return array
     */
    public function getUserBindedPairsTest(int $providerID, string $providerType, string $fields = ''): array
    {
        $pairs = $this->objectModel->getUserBindedPairs($providerID, $providerType, $fields);

        if(dao::isError()) return dao::getError();
        return $pairs;
    }
}
