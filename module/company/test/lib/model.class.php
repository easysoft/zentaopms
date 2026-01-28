<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class companyModelTest extends baseTest
{
    protected $moduleName = 'company';
    protected $className  = 'model';

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildSearchForm(int $queryID, string $actionURL): array
    {
        $this->invokeArgs('buildSearchForm', [$queryID, $actionURL]);
        if(dao::isError()) return dao::getError();
        return $this->instance->config->company->browse->search;
    }

    /**
     * Test getFirst method.
     *
     * @access public
     * @return array|object|false
     */
    public function getFirst(): array|object|bool
    {
        $result = $this->invokeArgs('getFirst');
        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * Test getByID method.
     *
     * @param  mixed $companyID 公司ID
     * @access public
     * @return array|object|false
     */
    public function getByID(int $companyID): array|object|bool
    {
        $result = $this->invokeArgs('getByID', [$companyID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOutsideCompanies method.
     *
     * @access public
     * @return array
     */
    public function getOutsideCompanies(): array
    {
        $result = $this->invokeArgs('getOutsideCompanies');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getUsers method.
     *
     * @param  string     $browseType
     * @param  string     $type
     * @param  string|int $queryID
     * @param  int        $deptID
     * @param  string     $sort
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getUsersTest(string $browseType = 'inside', string $type = '', string|int $queryID = 0, int $deptID = 0, string $sort = '', ?object $pager = null): array
    {
        $result = $this->invokeArgs('getUsers', [$browseType, $type, $queryID, $deptID, $sort]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test update method.
     *
     * @param  int    $companyID
     * @param  object $company
     * @access public
     * @return array|bool
     */
    public function update(int $companyID, object $company): array|bool
    {
        $result = $this->invokeArgs('update', [$companyID, $company]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
