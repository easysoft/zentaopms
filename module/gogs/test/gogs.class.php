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
}
