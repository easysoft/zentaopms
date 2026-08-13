<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class spaceModelTest extends baseTest
{
    protected $moduleName = 'space';
    protected $className  = 'model';

    /**
     * Get one field from an array item.
     *
     * @param  array      $items
     * @param  int|string $key
     * @param  string     $field
     * @access private
     * @return string|int
     */
    private function getArrayItemField(array $items, int|string $key, string $field): string|int
    {
        $item = zget($items, $key, null);
        if(is_null($item)) return '';

        return zget($item, $field, '');
    }

    /**
     * Get one field from a zero-based array item.
     *
     * @param  array  $items
     * @param  int    $index
     * @param  string $field
     * @access private
     * @return string|int
     */
    private function getIndexedItemField(array $items, int $index, string $field): string|int
    {
        $items = array_values($items);
        $item  = zget($items, $index, null);
        if(is_null($item)) return '';

        return zget($item, $field, '');
    }

    /**
     * Find space by ID from space list result.
     *
     * @param  array|object $spaces
     * @param  int          $spaceID
     * @access private
     * @return object|null
     */
    private function findSpaceById(array|object $spaces, int $spaceID): ?object
    {
        if(is_object($spaces) && isset($spaces->data)) $spaces = $spaces->data;
        foreach((array)$spaces as $space)
        {
            if((int)zget($space, 'id', 0) !== $spaceID) continue;
            return $space;
        }

        return null;
    }

    /**
     * Get a change field from update changes.
     *
     * @param  array  $changes
     * @param  string $field
     * @param  string $changeField
     * @access private
     * @return string|int
     */
    private function getChangeField(array $changes, string $field, string $changeField): string|int
    {
        foreach($changes as $change)
        {
            if(zget($change, 'field', '') != $field) continue;
            return zget($change, $changeField, '');
        }

        return '';
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
        $spaceList = $this->instance->getSpacesByAccount($account);

        if(dao::isError()) return dao::getError();
        return $spaceList;
    }

    /**
     * 创建默认空间。
     * Create default space.
     *
     * @access public
     * @return bool|array
     */
    public function createDefaultSpaceTest(): bool|array
    {
        $result = $this->instance->createDefaultSpace();

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Create default space and fetch a field from the latest space.
     *
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function createDefaultSpaceAndGetFieldTest(string $field): string|int|array
    {
        $result = $this->instance->createDefaultSpace();
        if(dao::isError()) return dao::getError();
        if(!$result) return '';

        $space = $this->instance->dao->select('*')->from(TABLE_SPACE)->orderBy('id_desc')->limit(1)->fetch();
        return zget($space, $field, '');
    }

    /**
     * Create default space and fetch manager count.
     *
     * @access public
     * @return int|array
     */
    public function createDefaultSpaceAndGetManagerCountTest(): int|array
    {
        $result = $this->instance->createDefaultSpace();
        if(dao::isError()) return dao::getError();
        if(!$result) return 0;

        $spaceID = (int)$this->instance->dao->select('id')->from(TABLE_SPACE)->orderBy('id_desc')->limit(1)->fetch('id');
        return (int)$this->instance->dao->select('COUNT(*) AS count')->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($spaceID)->andWhere('role')->eq('manager')->fetch('count');
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
     * Test getByID method field.
     *
     * @param  int $spaceID
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getByIDFieldTest(int $spaceID, string $field): string|int|array
    {
        $space = $this->instance->getByID($spaceID);

        if(dao::isError()) return dao::getError();
        return empty($space) ? '' : zget($space, $field, '');
    }

    /**
     * Test getByID field equals expected value.
     *
     * @param  int    $spaceID
     * @param  string $field
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function getByIDFieldEqualsTest(int $spaceID, string $field, string $expected): int|array
    {
        $value = $this->getByIDFieldTest($spaceID, $field);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test getByID member field.
     *
     * @param  int    $spaceID
     * @param  string $account
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getByIDMemberFieldTest(int $spaceID, string $account, string $field): string|int|array
    {
        $space = $this->instance->getByID($spaceID);

        if(dao::isError()) return dao::getError();
        return empty($space) ? '' : zget(zget($space->members, $account, array()), $field, '');
    }

    /**
     * Test getByID member field equals expected value.
     *
     * @param  int    $spaceID
     * @param  string $account
     * @param  string $field
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function getByIDMemberFieldEqualsTest(int $spaceID, string $account, string $field, string $expected): int|array
    {
        $value = $this->getByIDMemberFieldTest($spaceID, $account, $field);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test create method.
     *
     * @param  object $formData
     * @access public
     * @return int|bool|array
     */
    public function createTest(object $formData): int|bool|array
    {
        $result = $this->instance->create($formData);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test whether create method succeeds.
     *
     * @param  object $formData
     * @access public
     * @return int|array
     */
    public function createSuccessTest(object $formData): int|array
    {
        $result = $this->instance->create($formData);

        if(dao::isError()) return dao::getError();
        return $result ? 1 : 0;
    }

    /**
     * Test create method error message.
     *
     * @param  object $formData
     * @access public
     * @return string|array
     */
    public function createErrorTest(object $formData): string|array
    {
        $this->instance->create($formData);

        if(dao::isError())
        {
            $errors = dao::getError();
            return (string)zget($errors, 0, zget($errors, 'apiMessage', ''));
        }

        return '';
    }

    /**
     * Test create method and fetch manager count.
     *
     * @param  object $formData
     * @access public
     * @return int|array
     */
    public function createAndGetManagerCountTest(object $formData): int|array
    {
        $spaceID = $this->instance->create($formData);

        if(dao::isError()) return dao::getError();
        if(!$spaceID) return 0;

        return (int)$this->instance->dao->select('COUNT(*) AS count')->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($spaceID)->andWhere('role')->eq('manager')->fetch('count');
    }

    /**
     * Test update method.
     *
     * @param  object $space
     * @param  object $formData
     * @access public
     * @return false|array
     */
    public function updateTest(object $space, object $formData): false|array
    {
        $result = $this->instance->update($space, $formData);

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
     * Test getMemberList method count.
     *
     * @access public
     * @return int|array
     */
    public function getMemberListCountTest(): int|array
    {
        $members = $this->instance->getMemberList();

        if(dao::isError()) return dao::getError();
        return count($members);
    }

    /**
     * Test getMemberList method by space ID.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getMemberListBySpaceTest(int $spaceID): array
    {
        $members = $this->instance->getMemberList();

        if(dao::isError()) return dao::getError();
        return zget($members, $spaceID, array());
    }

    /**
     * Test getMemberList method count by space ID.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function getMemberListBySpaceCountTest(int $spaceID): int|array
    {
        $members = $this->instance->getMemberList();

        if(dao::isError()) return dao::getError();
        return count(zget($members, $spaceID, array()));
    }

    /**
     * Test getMemberList method by account.
     *
     * @param  int    $spaceID
     * @param  string $account
     * @access public
     * @return object|bool|array
     */
    public function getMemberListByAccountTest(int $spaceID, string $account): object|bool|array
    {
        $members = $this->instance->getMemberList();

        if(dao::isError()) return dao::getError();
        return zget(zget($members, $spaceID, array()), $account, false);
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
     * Test getByIdList method count.
     *
     * @param  array $spaceIdList
     * @param  bool  $showDeleted
     * @access public
     * @return int|array
     */
    public function getByIdListCountTest(array $spaceIdList = array(), bool $showDeleted = true): int|array
    {
        $spaces = $this->instance->getByIdList($spaceIdList, $showDeleted);

        if(dao::isError()) return dao::getError();
        return count($spaces);
    }

    /**
     * Test getByIdList method field by index.
     *
     * @param  array  $spaceIdList
     * @param  bool   $showDeleted
     * @param  int    $index
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getByIdListFieldTest(array $spaceIdList, bool $showDeleted, int $index, string $field): string|int|array
    {
        $spaces = $this->instance->getByIdList($spaceIdList, $showDeleted);

        if(dao::isError()) return dao::getError();
        return $this->getIndexedItemField($spaces, $index, $field);
    }

    /**
     * Test getByIdList method field by space ID.
     *
     * @param  array  $spaceIdList
     * @param  bool   $showDeleted
     * @param  int    $spaceID
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getByIdListSpaceFieldTest(array $spaceIdList, bool $showDeleted, int $spaceID, string $field): string|int|array
    {
        $spaces = $this->instance->getByIdList($spaceIdList, $showDeleted);

        if(dao::isError()) return dao::getError();

        $space = $this->findSpaceById($spaces, $spaceID);
        return is_null($space) ? '' : zget($space, $field, '');
    }

    /**
     * Test getByIdList field equals expected value.
     *
     * @param  array  $spaceIdList
     * @param  bool   $showDeleted
     * @param  int    $spaceID
     * @param  string $field
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function getByIdListSpaceFieldEqualsTest(array $spaceIdList, bool $showDeleted, int $spaceID, string $field, string $expected): int|array
    {
        $value = $this->getByIdListSpaceFieldTest($spaceIdList, $showDeleted, $spaceID, $field);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test getReposBySpace method.
     *
     * @param  int    $spaceID
     * @param  string $acl
     * @access public
     * @return array
     */
    public function getReposBySpaceTest(int $spaceID, string $acl = ''): array
    {
        $repos = $this->instance->getReposBySpace($spaceID, $acl);

        if(dao::isError()) return dao::getError();
        return $repos;
    }

    /**
     * Test getReposBySpace method count.
     *
     * @param  int    $spaceID
     * @param  string $acl
     * @access public
     * @return int|array
     */
    public function getReposBySpaceCountTest(int $spaceID, string $acl = ''): int|array
    {
        $repos = $this->instance->getReposBySpace($spaceID, $acl);

        if(dao::isError()) return dao::getError();
        return count($repos);
    }

    /**
     * Test getReposBySpace method field.
     *
     * @param  int    $spaceID
     * @param  int    $repoID
     * @param  string $field
     * @param  string $acl
     * @access public
     * @return string|int|array
     */
    public function getReposBySpaceFieldTest(int $spaceID, int $repoID, string $field, string $acl = ''): string|int|array
    {
        $repos = $this->instance->getReposBySpace($spaceID, $acl);

        if(dao::isError()) return dao::getError();
        return $this->getArrayItemField($repos, $repoID, $field);
    }

    /**
     * Test getArtifactLibsBySpace method.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getArtifactLibsBySpaceTest(int $spaceID): array
    {
        $libs = $this->instance->getArtifactLibsBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $libs;
    }

    /**
     * Test getArtifactLibsBySpace method count.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function getArtifactLibsBySpaceCountTest(int $spaceID): int|array
    {
        $libs = $this->instance->getArtifactLibsBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return count($libs);
    }

    /**
     * Test getArtifactLibsBySpace method field.
     *
     * @param  int    $spaceID
     * @param  int    $libID
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getArtifactLibsBySpaceFieldTest(int $spaceID, int $libID, string $field): string|int|array
    {
        $libs = $this->instance->getArtifactLibsBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $this->getArrayItemField($libs, $libID, $field);
    }

    /**
     * Test getPipelineBySpace method.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getPipelineBySpaceTest(int $spaceID): array
    {
        $pipelines = $this->instance->getPipelineBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $pipelines;
    }

    /**
     * Test getPipelineBySpace method count.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function getPipelineBySpaceCountTest(int $spaceID): int|array
    {
        $pipelines = $this->instance->getPipelineBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return count($pipelines);
    }

    /**
     * Test getPipelineBySpace method field.
     *
     * @param  int    $spaceID
     * @param  int    $pipelineID
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getPipelineBySpaceFieldTest(int $spaceID, int $pipelineID, string $field): string|int|array
    {
        $pipelines = $this->instance->getPipelineBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $this->getArrayItemField($pipelines, $pipelineID, $field);
    }

    /**
     * Test getSystemBySpace method.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function getSystemBySpaceTest(int $spaceID): array
    {
        $systems = $this->instance->getSystemBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $systems;
    }

    /**
     * Test getSystemBySpace method count.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function getSystemBySpaceCountTest(int $spaceID): int|array
    {
        $systems = $this->instance->getSystemBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return count($systems);
    }

    /**
     * Test getSystemBySpace method field.
     *
     * @param  int    $spaceID
     * @param  int    $systemID
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getSystemBySpaceFieldTest(int $spaceID, int $systemID, string $field): string|int|array
    {
        $systems = $this->instance->getSystemBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $this->getArrayItemField($systems, $systemID, $field);
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
     * Test getSpaceUsers method count.
     *
     * @param  int    $spaceID
     * @param  string $role
     * @access public
     * @return int|array
     */
    public function getSpaceUsersCountTest(int $spaceID, string $role = ''): int|array
    {
        $users = $this->instance->getSpaceUsers($spaceID, $role);

        if(dao::isError()) return dao::getError();
        return count($users);
    }

    /**
     * Test getSpaceUsers method field by account.
     *
     * @param  int    $spaceID
     * @param  string $role
     * @param  string $account
     * @access public
     * @return string|array
     */
    public function getSpaceUsersAccountTest(int $spaceID, string $role, string $account): string|array
    {
        $users = $this->instance->getSpaceUsers($spaceID, $role);

        if(dao::isError()) return dao::getError();
        return (string)zget($users, $account, '');
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
     * Test getRepoUsersBySpace method count.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function getRepoUsersBySpaceCountTest(int $spaceID = 0): int|array
    {
        $users = $this->instance->getRepoUsersBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return count($users);
    }

    /**
     * Test getRepoUsersBySpace method field by index.
     *
     * @param  int    $spaceID
     * @param  int    $index
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getRepoUsersBySpaceFieldTest(int $spaceID, int $index, string $field): string|int|array
    {
        $users = $this->instance->getRepoUsersBySpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $this->getIndexedItemField($users, $index, $field);
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
     * Test getGroupMembersBySpace method count.
     *
     * @param  int  $spaceID
     * @param  bool $allVision
     * @access public
     * @return int|array
     */
    public function getGroupMembersBySpaceCountTest(int $spaceID = 0, bool $allVision = false): int|array
    {
        $members = $this->instance->getGroupMembersBySpace($spaceID, $allVision);

        if(dao::isError()) return dao::getError();
        return count($members);
    }

    /**
     * Test getGroupMembersBySpace method field by index.
     *
     * @param  int    $spaceID
     * @param  bool   $allVision
     * @param  int    $index
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getGroupMembersBySpaceFieldTest(int $spaceID, bool $allVision, int $index, string $field): string|int|array
    {
        $members = $this->instance->getGroupMembersBySpace($spaceID, $allVision);

        if(dao::isError()) return dao::getError();
        return $this->getIndexedItemField($members, $index, $field);
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
     * Test getSpaceMembers method count.
     *
     * @param  int  $spaceID
     * @param  bool $allVision
     * @access public
     * @return int|array
     */
    public function getSpaceMembersCountTest(int $spaceID, bool $allVision = false): int|array
    {
        $members = $this->instance->getSpaceMembers($spaceID, $allVision);

        if(dao::isError()) return dao::getError();
        return count($members);
    }

    /**
     * Test getSpaceMembers method field by account.
     *
     * @param  int    $spaceID
     * @param  string $account
     * @param  string $field
     * @param  bool   $allVision
     * @access public
     * @return string|int|array
     */
    public function getSpaceMembersFieldTest(int $spaceID, string $account, string $field, bool $allVision = false): string|int|array
    {
        $members = $this->instance->getSpaceMembers($spaceID, $allVision);

        if(dao::isError()) return dao::getError();
        return zget(zget($members, $account, new stdClass()), $field, '');
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
     * Test restore method error message.
     *
     * @param  int $spaceID
     * @param  int $actionID
     * @access public
     * @return string|array
     */
    public function restoreErrorTest(int $spaceID, int $actionID): string|array
    {
        $this->instance->restore($spaceID, $actionID);

        if(dao::isError())
        {
            $errors = dao::getError();
            return (string)zget($errors, 0, zget($errors, 'apiMessage', ''));
        }

        return '';
    }

    /**
     * Test restore method and fetch action extra.
     *
     * @param  int $spaceID
     * @param  int $actionID
     * @access public
     * @return string|array
     */
    public function restoreActionExtraTest(int $spaceID, int $actionID): string|array
    {
        $this->instance->restore($spaceID, $actionID);

        if(dao::isError()) dao::getError();
        return (string)$this->instance->dao->select('extra')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch('extra');
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
     * Test getProductsBySpace method count.
     *
     * @param  int  $spaceID
     * @param  bool $hasPairs
     * @access public
     * @return int|array
     */
    public function getProductsBySpaceCountTest(int $spaceID, bool $hasPairs = false): int|array
    {
        $products = $this->instance->getProductsBySpace($spaceID, $hasPairs);

        if(dao::isError()) return dao::getError();
        return count($products);
    }

    /**
     * Test getProductsBySpace method field by key.
     *
     * @param  int    $spaceID
     * @param  bool   $hasPairs
     * @param  int    $key
     * @access public
     * @return string|int|array
     */
    public function getProductsBySpaceItemTest(int $spaceID, bool $hasPairs, int $key): string|int|array
    {
        $products = $this->instance->getProductsBySpace($spaceID, $hasPairs);

        if(dao::isError()) return dao::getError();
        return zget($products, $key, '');
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
     * Test getPrivs method label by module and method.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return string|array
     */
    public function getPrivLabelTest(string $module, string $method): string|array
    {
        $privs = $this->instance->getPrivs();

        if(dao::isError()) return dao::getError();
        return (string)zget(zget($privs, $module, new stdclass()), $method, '');
    }

    /**
     * Test getDevOpsAllPrivs method.
     *
     * @access public
     * @return array
     */
    public function getDevOpsAllPrivsTest(): array
    {
        $privs = $this->instance->getDevOpsAllPrivs();

        if(dao::isError()) return dao::getError();
        return $privs;
    }

    /**
     * Test if devops all privs contains a module.
     *
     * @param  string $module
     * @access public
     * @return int|array
     */
    public function hasDevOpsModulePrivTest(string $module): int|array
    {
        $privs = $this->instance->getDevOpsAllPrivs();

        if(dao::isError()) return dao::getError();
        return isset($privs[$module]) ? 1 : 0;
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

    /**
     * Test migrateGroupPrivs method and fetch migrated privilege count.
     *
     * @param  string $method
     * @access public
     * @return int|array
     */
    public function getMigratedGroupPrivCountTest(string $method): int|array
    {
        $result = $this->instance->migrateGroupPrivs();
        if(dao::isError()) return dao::getError();
        if(!$result) return 0;

        return (int)$this->instance->dao->select('COUNT(*) AS count')->from(TABLE_GROUPPRIV)->where('module')->eq('space')->andWhere('method')->eq($method)->fetch('count');
    }

    /**
     * Test isClickable method.
     *
     * @param  object $space
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(object $space, string $action): bool
    {
        return $this->instance::isClickable($space, $action);
    }

    /**
     * Test getListByAccount method.
     *
     * @param  string $account
     * @access public
     * @return array|object
     */
    public function getListByAccountTest(string $account): array|object
    {
        $spaces = $this->instance->getListByAccount($account);

        if(dao::isError()) return dao::getError();
        return $spaces;
    }

    /**
     * Test getListByAccount method count.
     *
     * @param  string $account
     * @access public
     * @return int|array
     */
    public function getListByAccountCountTest(string $account): int|array
    {
        $spaces = $this->instance->getListByAccount($account);

        if(dao::isError()) return dao::getError();
        return count((array)$spaces);
    }

    /**
     * Test getListByAccount method field by index.
     *
     * @param  string $account
     * @param  int    $index
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getListByAccountFieldTest(string $account, int $index, string $field): string|int|array
    {
        $spaces = $this->instance->getListByAccount($account);

        if(dao::isError()) return dao::getError();
        return $this->getIndexedItemField((array)$spaces, $index, $field);
    }

    /**
     * Test getListByAccount method field by space ID.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getListByAccountSpaceFieldTest(string $account, int $spaceID, string $field): string|int|array
    {
        $spaces = $this->instance->getListByAccount($account);

        if(dao::isError()) return dao::getError();

        $space = $this->findSpaceById((array)$spaces, $spaceID);
        return is_null($space) ? '' : zget($space, $field, '');
    }

    /**
     * Test getListByAccount field equals expected value.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  string $field
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function getListByAccountSpaceFieldEqualsTest(string $account, int $spaceID, string $field, string $expected): int|array
    {
        $value = $this->getListByAccountSpaceFieldTest($account, $spaceID, $field);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test getListByAccount member field by space ID.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  string $member
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getListByAccountSpaceMemberFieldTest(string $account, int $spaceID, string $member, string $field): string|int|array
    {
        $spaces = $this->instance->getListByAccount($account);

        if(dao::isError()) return dao::getError();

        $space = $this->findSpaceById((array)$spaces, $spaceID);
        return is_null($space) ? '' : zget(zget($space->members, $member, array()), $field, '');
    }

    /**
     * Test getListByAccount member field equals expected value.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  string $member
     * @param  string $field
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function getListByAccountSpaceMemberFieldEqualsTest(string $account, int $spaceID, string $member, string $field, string $expected): int|array
    {
        $value = $this->getListByAccountSpaceMemberFieldTest($account, $spaceID, $member, $field);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test getSpacesByAccount method count.
     *
     * @param  string $account
     * @access public
     * @return int|array
     */
    public function getSpacesByAccountCountTest(string $account): int|array
    {
        $spaceList = $this->instance->getSpacesByAccount($account);

        if(dao::isError()) return dao::getError();
        return count($spaceList);
    }

    /**
     * Test getSpacesByAccount member field.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  string $member
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function getSpacesByAccountMemberFieldTest(string $account, int $spaceID, string $member, string $field): string|int|array
    {
        $spaceList = $this->instance->getSpacesByAccount($account);

        if(dao::isError()) return dao::getError();
        return zget(zget(zget($spaceList, $spaceID, array()), $member, new stdClass()), $field, '');
    }

    /**
     * Test getPairs method count.
     *
     * @param  string $account
     * @param  bool   $filterRepoCreate
     * @access public
     * @return int|array
     */
    public function getPairsCountTest(string $account = '', bool $filterRepoCreate = false): int|array
    {
        $pairs = $this->instance->getPairs($account, $filterRepoCreate);

        if(dao::isError()) return dao::getError();
        return count($pairs);
    }

    /**
     * Test getPairs method field by space ID.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  bool   $filterRepoCreate
     * @access public
     * @return string|int|array
     */
    public function getPairsFieldTest(string $account, int $spaceID, bool $filterRepoCreate = false): string|int|array
    {
        $pairs = $this->instance->getPairs($account, $filterRepoCreate);

        if(dao::isError()) return dao::getError();
        return zget($pairs, $spaceID, '');
    }

    /**
     * Test getPairs field equals expected value.
     *
     * @param  string $account
     * @param  int    $spaceID
     * @param  string $expected
     * @param  bool   $filterRepoCreate
     * @access public
     * @return int|array
     */
    public function getPairsFieldEqualsTest(string $account, int $spaceID, string $expected, bool $filterRepoCreate = false): int|array
    {
        $value = $this->getPairsFieldTest($account, $spaceID, $filterRepoCreate);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test deleteSpace method success result.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function deleteSpaceSuccessTest(int $spaceID): int|array
    {
        $result = $this->instance->deleteSpace($spaceID);

        if(dao::isError()) return dao::getError();
        return $result ? 1 : 0;
    }

    /**
     * Test deleteSpace method error message.
     *
     * @param  int $spaceID
     * @access public
     * @return string|array
     */
    public function deleteSpaceErrorTest(int $spaceID): string|array
    {
        $this->instance->deleteSpace($spaceID);

        if(dao::isError())
        {
            $errors = dao::getError();
            return (string)zget($errors, 0, zget($errors, 'apiMessage', ''));
        }

        return '';
    }

    /**
     * Test deleteSpace method and fetch deleted action count.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function deleteSpaceActionCountTest(int $spaceID): int|array
    {
        $result = $this->instance->deleteSpace($spaceID);

        if(dao::isError()) return dao::getError();
        if(!$result) return 0;

        return (int)$this->instance->dao->select('COUNT(*) AS count')->from(TABLE_ACTION)->where('objectType')->eq('space')->andWhere('objectID')->eq($spaceID)->andWhere('action')->eq('deleted')->fetch('count');
    }

    /**
     * Test deleteSpace method and check deleted action exists.
     *
     * @param  int $spaceID
     * @access public
     * @return int|array
     */
    public function deleteSpaceHasActionTest(int $spaceID): int|array
    {
        $count = $this->deleteSpaceActionCountTest($spaceID);
        if(is_array($count)) return $count;

        return $count > 0 ? 1 : 0;
    }

    /**
     * Test update changes by field.
     *
     * @param  object $space
     * @param  object $formData
     * @param  string $field
     * @param  string $changeField
     * @access public
     * @return string|int|array
     */
    public function updateChangeFieldTest(object $space, object $formData, string $field, string $changeField): string|int|array
    {
        $changes = $this->instance->update($space, $formData);

        if(dao::isError()) return dao::getError();
        return $this->getChangeField($changes, $field, $changeField);
    }

    /**
     * Test update change field equals expected value.
     *
     * @param  object $space
     * @param  object $formData
     * @param  string $field
     * @param  string $changeField
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function updateChangeFieldEqualsTest(object $space, object $formData, string $field, string $changeField, string $expected): int|array
    {
        $value = $this->updateChangeFieldTest($space, $formData, $field, $changeField);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test update method and fetch updated space field.
     *
     * @param  object $space
     * @param  object $formData
     * @param  string $field
     * @access public
     * @return string|int|array
     */
    public function updateAndGetFieldTest(object $space, object $formData, string $field): string|int|array
    {
        $result = $this->instance->update($space, $formData);

        if(dao::isError()) return dao::getError();
        if($result === false) return '';

        $updatedSpace = $this->instance->getByID((int)$space->id);
        if(dao::isError()) return dao::getError();
        return empty($updatedSpace) ? '' : zget($updatedSpace, $field, '');
    }

    /**
     * Test update and verify updated field equals expected value.
     *
     * @param  object $space
     * @param  object $formData
     * @param  string $field
     * @param  string $expected
     * @access public
     * @return int|array
     */
    public function updateAndGetFieldEqualsTest(object $space, object $formData, string $field, string $expected): int|array
    {
        $value = $this->updateAndGetFieldTest($space, $formData, $field);
        if(is_array($value)) return $value;

        return $value === $expected ? 1 : 0;
    }

    /**
     * Test update method and fetch manager count.
     *
     * @param  object $space
     * @param  object $formData
     * @access public
     * @return int|array
     */
    public function updateAndGetManagerCountTest(object $space, object $formData): int|array
    {
        $result = $this->instance->update($space, $formData);

        if(dao::isError()) return dao::getError();
        if($result === false) return 0;

        return (int)$this->instance->dao->select('COUNT(*) AS count')->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq($space->id)->andWhere('role')->eq('manager')->fetch('count');
    }
}
