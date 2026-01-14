<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class giteaZenTest extends baseTest
{
    protected $moduleName = 'gitea';
    protected $className  = 'zen';

    /**
     * Test bindUser method.
     *
     * @param  array  $userList
     * @access public
     * @return array|string
     */
    public function bindUserTester(array $userList): array|string
    {
        $nameList = array();
        foreach($userList as $openID => $user) $nameList[$openID] = 'Gitea-' . $user;

        $result = $this->gitea->bindUser(1, $userList, $nameList);
        if(!$result) return dao::getError();

        return $this->gitea->dao->select('*')->from(TABLE_OAUTH)
            ->where('providerID')->eq(1)
            ->andWhere('providerType')->eq('gitea')
            ->fetchAll();
    }

    /**
     * Test apiErrorHandling method.
     *
     * @param  object $response
     * @access public
     * @return array
     */
    public function apiErrorHandlingTester(object $response): array
    {
        $this->gitea->apiErrorHandling($response);
        return dao::getError();
    }

    /**
     * Test parseApiError method.
     *
     * @param  string $message
     * @access public
     * @return array
     */
    public function parseApiErrorTester(string $message): array
    {
        $this->gitea->parseApiError($message);
        return dao::getError();
    }

    /**
     * Test apiGetMergeRequests method.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return mixed
     */
    public function apiGetMergeRequestsTest(int $giteaID, string $project)
    {
        $result = $this->gitea->apiGetMergeRequests($giteaID, $project);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test checkToken method.
     *
     * @param  object $giteaData
     * @access public
     * @return mixed
     */
    public function checkTokenTest($giteaData)
    {
        // 由于checkToken是protected方法，在测试脚本中直接调用
        // 这里只是提供接口，实际调用在测试脚本中完成
        return $giteaData;
    }

    /**
     * Test getMatchedUsers method.
     *
     * @param  int    $giteaID
     * @param  array  $giteaUsers
     * @access public
     * @return array
     */
    public function getMatchedUsersTest(int $giteaID, array $giteaUsers): array
    {
        global $tester;
        $giteaModel = $tester->loadModel('gitea');

        // 创建gitea别名以便zen类可以继承
        if(!class_exists('gitea')) {
            class_alias('giteaModel', 'gitea');
        }

        // 加载zen文件（仅在类不存在时加载）
        if(!class_exists('giteaZen')) {
            include_once dirname(__FILE__, 3) . '/zen.php';
        }

        // 创建giteaZen实例
        $giteaZen = new giteaZen();

        // 使用反射调用protected方法
        $reflection = new ReflectionClass($giteaZen);
        $method = $reflection->getMethod('getMatchedUsers');
        $method->setAccessible(true);

        $result = $method->invoke($giteaZen, $giteaID, $giteaUsers);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
