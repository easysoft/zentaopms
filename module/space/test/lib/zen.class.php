<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class spaceZenTest extends baseTest
{
    protected $moduleName = 'space';
    protected $className  = 'zen';

    /**
     * Test buildManageMembersFields method.
     *
     * @param  int $spaceID
     * @access public
     * @return array
     */
    public function buildManageMembersFieldsTest(int $spaceID): array
    {
        $result = $this->invokeArgs('buildManageMembersFields', [$spaceID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildManageMembersData method.
     *
     * @param  array $formData
     * @param  array $members
     * @access public
     * @return array
     */
    public function buildManageMembersDataTest(array $formData, array $members): array
    {
        $result = $this->invokeArgs('buildManageMembersData', [$formData, $members]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
