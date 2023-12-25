<?php
class spaceTest
{
    public function __construct(string $account = 'admin')
    {
        su($account);

        global $tester, $app;
        $this->objectModel = $tester->loadModel('space');

        $app->rawModule = 'space';
        $app->rawMethod = 'browse';
        $app->setModuleName('space');
        $app->setMethodName('browse');
    }

    /**
     * 获取用户的空间列表。
     * Get space list by user account.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getSpacesByAccountTest(string $account): array
    {
        $spaceList = $this->objectModel->getSpacesByAccount($account);

        if(dao::isError()) return dao::getError();
        return $spaceList;
    }
}
