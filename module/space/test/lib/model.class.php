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
        $app = $this->instance->app;

        $originalModuleName = $app->moduleName ?? null;
        $originalMethodName = $app->methodName ?? null;
        $originalRawModule  = $app->rawModule ?? null;
        $originalRawMethod  = $app->rawMethod ?? null;

        if(empty($app->moduleName)) $app->moduleName = 'space';
        if(empty($app->methodName)) $app->methodName = 'browse';
        if(empty($app->rawModule))  $app->rawModule  = 'space';
        if(empty($app->rawMethod))  $app->rawMethod  = 'browse';

        $app->loadClass('pager', true);

        $pager     = new pager(0, $recPerPage, $pageID);
        $instances = $this->instance->getSpaceInstances($spaceID, $status, $searchName, $pager);

        $app->moduleName = $originalModuleName;
        $app->methodName = $originalMethodName;
        $app->rawModule  = $originalRawModule;
        $app->rawMethod  = $originalRawMethod;

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

    /**
     * Test getMemberList method.
     *
     * @access public
     * @return array
     */
    public function getMemberListTest(): array
    {
        $members = $this->instance->getMemberList();

        if(dao::isError()) return dao::getError();
        return $members;
    }

    /**
     * Test getByIdList method.
     *
     * @param  array $spaceIdList
     * @param  bool  $showDeleted
     * @access public
     * @return array
     */
    public function getByIdListTest(array $spaceIdList = array(), bool $showDeleted = true): array
    {
        $spaces = $this->instance->getByIdList($spaceIdList, $showDeleted);

        if(dao::isError()) return dao::getError();
        return $spaces;
    }

    /**
     * Test getSpaceUsers method.
     *
     * @param  int    $spaceID
     * @param  string $role
     * @access public
     * @return array
     */
    public function getSpaceUsersTest(int $spaceID, string $role = ''): array
    {
        $users = $this->instance->getSpaceUsers($spaceID, $role);

        if(dao::isError()) return dao::getError();
        return $users;
    }

    /**
     * Test getRepoUsersBySpace method.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getRepoUsersBySpaceTest(int $spaceID = 0): array
    {
        $users = $this->instance->getRepoUsersBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $users;
    }

    /**
     * Test getGroupMembersBySpace method.
     *
     * @param  int  $spaceID
     * @param  bool $allVision
     * @access public
     * @return array
     */
    public function getGroupMembersBySpaceTest(int $spaceID = 0, bool $allVision = false): array
    {
        $members = $this->instance->getGroupMembersBySpace($spaceID, $allVision);

        if(dao::isError()) return dao::getError();
        return $members;
    }

    /**
     * Test getSpaceMembers method.
     *
     * @param  int  $spaceID
     * @param  bool $allVision
     * @access public
     * @return array
     */
    public function getSpaceMembersTest(int $spaceID, bool $allVision = false): array
    {
        $members = $this->instance->getSpaceMembers($spaceID, $allVision);

        if(dao::isError()) return dao::getError();
        return $members;
    }

    /**
     * Test manageMembers method.
     *
     * @param  int   $spaceID
     * @param  array $members
     * @access public
     * @return bool
     */
    public function manageMembersTest(int $spaceID, array $members): bool
    {
        $result = $this->instance->manageMembers($spaceID, $members);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test removeMember method.
     *
     * @param  int    $spaceID
     * @param  string $account
     * @access public
     * @return bool
     */
    public function removeMemberTest(int $spaceID, string $account): bool
    {
        $result = $this->instance->removeMember($spaceID, $account);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test restore method.
     *
     * @param  int $spaceID
     * @param  int $actionID
     * @access public
     * @return bool
     */
    public function restoreTest(int $spaceID, int $actionID): bool|array
    {
        $result = $this->instance->restore($spaceID, $actionID);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProductsBySpace method.
     *
     * @param  int  $spaceID
     * @param  bool $hasPairs
     * @access public
     * @return array
     */
    public function getProductsBySpaceTest(int $spaceID, bool $hasPairs = false): array
    {
        $products = $this->instance->getProductsBySpace($spaceID, $hasPairs);

        if(dao::isError()) return dao::getError();
        return $products;
    }

    /**
     * Test setMenu method.
     *
     * @param  int $spaceID
     * @access public
     * @return void
     */
    public function setMenuTest(int $spaceID = 0)
    {
        $this->instance->setMenu($spaceID);

        if(dao::isError()) return dao::getError();
        return $this->instance->session->devopsSpace;
    }

    /**
     * Test getPrivs method.
     *
     * @access public
     * @return object
     */
    public function getPrivsTest(): object
    {
        $privs = $this->instance->getPrivs();

        if(dao::isError()) return dao::getError();
        return $privs;
    }

    /**
     * Test migrateGroupPrivs method.
     *
     * @access public
     * @return bool
     */
    public function migrateGroupPrivsTest(): bool
    {
        $result = $this->instance->migrateGroupPrivs();

        if(dao::isError()) return dao::getError();
        return $result;
    }
}
