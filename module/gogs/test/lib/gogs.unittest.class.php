<?php
class gogsTest
{
    private $gogs;

    public function __construct()
    {
        global $tester;
        $this->gogs  = $tester->loadModel('gogs');
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

        $result = $this->gogs->bindUser(1, $userList, $nameList);
        if(!$result) return dao::getError();

        return $this->gogs->dao->select('*')->from(TABLE_OAUTH)
            ->where('providerID')->eq(1)
            ->andWhere('providerType')->eq('gogs')
            ->fetchAll();
    }

    /**
     * Test apiGetMergeRequests method.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return mixed
     */
    public function apiGetMergeRequestsTest(int $gogsID = 0, string $project = ''): mixed
    {
        $result = $this->gogs->apiGetMergeRequests($gogsID, $project);
        if(dao::isError()) return dao::getError();

        return is_array($result) ? count($result) : 0;
    }
}
