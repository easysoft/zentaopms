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
}
