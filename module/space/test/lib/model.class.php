<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class spaceModelTest extends baseTest
{
    protected $moduleName = 'space';
    protected $className  = 'model';

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
        $spaceList = $this->instance->getSpacesByAccount($account);

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
        $defaultSpace = $this->instance->createDefaultSpace($account);

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
        $defaultSpace = $this->instance->defaultSpace($account);

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
        $systemSpace = $this->instance->getSystemSpace($account);

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
        $this->instance->app->loadClass('pager', true);

        $pager     = new pager(0, $recPerPage, $pageID);
        $instances = $this->instance->getSpaceInstances($spaceID, $status, $searchName);

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
        $space = $this->instance->getByID($spaceID);

        if(dao::isError()) return dao::getError();
        return $space;
    }

    /**
     * 获取应用市场应用对应的外部应用。
     * Get External app By store app.
     *
     * @param  string $domain
     * @access public
     * @return array|object|bool
     */
    public function getExternalAppByAppTest(string $domain): array|object|bool
    {
        $instance = new stdclass();
        $instance->domain = $domain;
        $instance->id     = rand(1000, 9999);

        $pipeline = $this->instance->getExternalAppByApp($instance);

        if(dao::isError()) return dao::getError();
        return $pipeline;
    }

    /**
     * 获取用户空间的应用列表AppID。
     * Get app list AppID in space by space id.
     *
     * @param  int   $spaceID
     * @access public
     * @return array
     */
    public function getSpaceInstancesAppIDsTest(int $spaceID): array
    {
        $result = $this->instance->getSpaceInstancesAppIDs($spaceID);

        if(dao::isError()) return dao::getError();
        return $result;
    }
}
