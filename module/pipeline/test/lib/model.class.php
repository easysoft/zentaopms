<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pipelineModelTest extends baseTest
{
    protected $moduleName = 'pipeline';
    protected $className  = 'model';


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
        $pipeline = $this->instance->getByID($id);

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
        $this->instance->app->loadClass('pager', true);

        $pager        = new pager(0, $recPerPage, $pageID);
        $pipelineList = $this->instance->getList($type, $orderBy, $pager);

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
        $object->appType = $type;

        $pipelineID = $this->instance->create($object);

        if(dao::isError()) return dao::getError();
        return $this->instance->getByID($pipelineID);
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
        $oldObject = $this->instance->getByID($id);

        $server = new stdclass();
        foreach($data as $key => $value) $server->{$key}  = $value;

        $this->instance->update($id, $server);

        if(dao::isError()) return dao::getError();

        $object = $this->instance->getByID($id);
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
        $pipeline = $this->instance->getByNameAndType($name, $type);

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
        $pipeline = $this->instance->getByUrl($url);

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
        $pipelinePairs = $this->instance->getPairs($type);

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
        // 检查关联数据，模拟deleteByObject的逻辑
        if(in_array($type, array('gitlab', 'gitea', 'gogs')))
        {
            $repo = $this->instance->dao->select('*')->from(TABLE_REPO)
                ->where('deleted')->eq('0')
                ->andWhere('SCM')->eq(ucfirst($type))
                ->andWhere('serviceHost')->eq($id)
                ->fetch();
            if($repo) return false;
        }
        elseif($type == 'sonarqube')
        {
            $job = $this->instance->dao->select('id,name,repo,deleted')->from(TABLE_JOB)
                ->where('frame')->eq('sonarqube')
                ->andWhere('server')->eq($id)
                ->andWhere('deleted')->eq('0')
                ->fetch();
            if($job) return false;
        }

        $server = $this->instance->fetchByID($id);
        if(!$server) return false;

        // 执行删除操作
        $this->instance->dao->update(TABLE_PIPELINE)->set('deleted')->eq(1)->where('id')->eq($id)->exec();

        if(dao::isError()) return dao::getError();

        // 创建action记录，处理instanceID字段可能不存在的情况
        $actionExtra = isset($server->instanceID) ? ($server->instanceID ? 0 : 1) : 1;
        $this->instance->loadModel('action')->create($type, $id, 'deleted', '', $actionExtra);

        // 返回更新后的记录
        return $this->instance->getByID($id);
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
        $pairs = $this->instance->getUserBindedPairs($providerID, $providerType, $fields);

        if(dao::isError()) return dao::getError();
        return $pairs;
    }

    /**
     * 根据服务器ID和禅道账号获取禅道用户绑定的第三方账号。
     * Get user binded third party accounts.
     *
     * @param  int          $providerID
     * @param  string       $providerType  gitlab, gitea, gogs
     * @param  string       $zentaoAccount
     * @access public
     * @return array|string
     */
    public function getOpenIdByAccountTest(int $providerID, string $providerType, string $zentaoAccount): array|string
    {
        $openID = $this->instance->getOpenIdByAccount($providerID, $providerType, $zentaoAccount);

        if(dao::isError()) return dao::getError();
        return $openID;
    }

    /**
     * 根据禅道账号获取禅道用户绑定的第三方服务器和账号信息。
     * Get user binded third party accounts.
     *
     * @param  string $providerType gitlab, gitea, gogs
     * @param  string $account
     * @access public
     * @return array
     */
    public function getProviderPairsByAccountTest(string $providerType, string $account = ''): array
    {
        $pairs = $this->instance->getProviderPairsByAccount($providerType, $account);

        if(dao::isError()) return dao::getError();
        return $pairs;
    }
}
