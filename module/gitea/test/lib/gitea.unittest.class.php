<?php
class giteaTest
{
    private $gitea;

    public function __construct()
    {
        global $tester;
        $this->gitea  = $tester->loadModel('gitea');
    }

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
        try {
            $result = $this->gitea->apiGetMergeRequests($giteaID, $project);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch (TypeError $e) {
            return 'TypeError: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
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
}
