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

    /**
     * 创建默认空间。
     * Create default space by account.
     *
     * @param  string            $account
     * @access public
     * @return bool|array|object
     */
    public function createDefaultSpaceTest(string $account): bool|array|object
    {
        $defaultSpace = $this->objectModel->createDefaultSpace($account);

        if(dao::isError()) return dao::getError();
        return $defaultSpace;
    }

    /**
     * 获取用户的默认空间。
     * Get user's default space by user account.
     *
     * @param  string            $account
     * @access public
     * @return bool|array|object
     */
    public function defaultSpaceTest(string $account): bool|array|object
    {
        $defaultSpace = $this->objectModel->defaultSpace($account);

        if(dao::isError()) return dao::getError();
        return $defaultSpace;
    }

    /**
     * 获取用户的系统空间。
     * Get system space.
     *
     * @param  string            $account
     * @access public
     * @return bool|array|object
     */
    public function getSystemSpaceTest(string $account): bool|array|object
    {
        $systemSpace = $this->objectModel->getSystemSpace($account);

        if(dao::isError()) return dao::getError();
        return $systemSpace;
    }

    /**
     * 获取用户空间的应用列表。
     * Get app list in space by space id.
     *
     * @param  int    $spaceID
     * @param  string $status
     * @param  string $searchName
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function getSpaceInstancesTest(int $spaceID, string $status = 'all', string $searchName = '', int $recPerPage = 20, int $pageID = 1): array
    {
        $this->objectModel->app->loadClass('pager', true);

        $pager     = new pager(0, $recPerPage, $pageID);
        $instances = $this->objectModel->getSpaceInstances($spaceID, $status, $searchName);

        if(dao::isError()) return dao::getError();
        return $instances;
    }

    /**
     * 根据ID获取空间。
     * Get space by id.
     *
     * @param  int               $spaceID
     * @access public
     * @return array|object|bool
     */
    public function getByIDTest(int $spaceID): array|object|bool
    {
        $space = $this->objectModel->getByID($spaceID);

        if(dao::isError()) return dao::getError();
        return $space;
    }
}
