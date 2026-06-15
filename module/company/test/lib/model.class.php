<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';
require_once dirname(__FILE__, 5) . '/lib/pager/pager.class.php';

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
    public function buildSearchFormTest(int $queryID, string $actionURL): array
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
    public function getFirstTest(): array|object|bool
    {
        $result = $this->invokeArgs('getFirst');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getByID method.
     *
     * @param  mixed $companyID 公司ID
     * @access public
     * @return array|object|false
     */
    public function getByIDTest($companyID): array|object|bool
    {
        if(!is_numeric($companyID)) return false;

        $result = $this->invokeArgs('getByID', [(int)$companyID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOutsideCompanies method.
     *
     * @access public
     * @return array
     */
    public function getOutsideCompaniesTest(): array
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
    public function getUsersTest(int $count = 0, string $browseType = 'inside', string $type = '', string|int $queryID = 0, int $deptID = 0, string $sort = '', ?object $pager = null): array|int
    {
        global $app;

        $originalRawModule = $app->rawModule ?? null;
        $originalRawMethod = $app->rawMethod ?? null;
        if(empty($app->rawModule)) $app->rawModule = 'company';
        if(empty($app->rawMethod)) $app->rawMethod = 'browse';

        $pager  = $pager ?: pager::init(0, 1000, 1);
        $result = $this->invokeArgs('getUsers', [$browseType, $type, $queryID, $deptID, $sort, $pager]);

        $app->rawModule = $originalRawModule;
        $app->rawMethod = $originalRawMethod;

        if(dao::isError()) return dao::getError();

        if($count === 1) return $pager->recTotal;

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
    public function updateTest(int $companyID, array|object $company): array|object|bool
    {
        if(is_array($company)) $company = (object)$company;

        $result = $this->invokeArgs('update', [$companyID, $company]);
        if(dao::isError()) return dao::getError();
        if(!$result) return false;

        return $this->invokeArgs('getByID', [$companyID]);
    }
}
