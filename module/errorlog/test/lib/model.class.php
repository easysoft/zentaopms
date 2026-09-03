<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class errorlogModelTest extends baseTest
{
    protected $moduleName = 'errorlog';
    protected $className  = 'model';

    /**
     * 测试获取错误日志列表。
     * Test get error log list.
     *
     * @param  string $query
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function getListTest(string $query = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        $this->instance->app->rawModule  = 'errorlog';
        $this->instance->app->rawMethod  = 'browse';
        $this->instance->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $logs = $this->instance->getList($query, $orderBy, $pager);
        if(dao::isError()) return dao::getError();
        return $logs;
    }

    /**
     * 测试根据ID获取错误日志。
     * Test get error log by id.
     *
     * @param  int $id
     * @access public
     * @return object|array
     */
    public function getByIDTest(int $id): object|array|false
    {
        $log = $this->instance->getByID($id);
        if(dao::isError()) return dao::getError();
        return $log;
    }

    /**
     * 测试根据请求ID获取错误日志。
     * Test get error log by request id.
     *
     * @param  string $requestID
     * @access public
     * @return object|array
     */
    public function getByRequestIDTest(string $requestID): object|array|false
    {
        $log = $this->instance->getByRequestID($requestID);
        if(dao::isError()) return dao::getError();
        return $log;
    }

    /**
     * 测试获取错误级别对应的展示类型。
     * Test get level type by error level.
     *
     * @param  int $level
     * @access public
     * @return string
     */
    public function getLevelTypeTest(int $level): string
    {
        return $this->instance->getLevelType($level);
    }

    /**
     * 测试获取有错误日志的模块列表。
     * Test get module pairs which have error logs.
     *
     * @access public
     * @return array
     */
    public function getModulePairsTest(): array
    {
        $modules = $this->instance->getModulePairs();
        if(dao::isError()) return dao::getError();
        return $modules;
    }

    /**
     * 测试删除过期错误日志。
     * Test delete overdue error logs.
     *
     * @param  string $date
     * @access public
     * @return bool|array
     */
    public function deleteByDateTest(string $date): bool|array
    {
        $result = $this->instance->deleteByDate($date);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试根据ID删除错误日志。
     * Test delete an error log by id.
     *
     * @param  int $id
     * @access public
     * @return bool|array
     */
    public function deleteByIDTest(int $id): bool|array
    {
        $result = $this->instance->deleteByID($id);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试根据ID批量删除错误日志。
     * Test delete error logs by ids.
     *
     * @param  array $ids
     * @access public
     * @return bool|array
     */
    public function deleteByIDsTest(array $ids): bool|array
    {
        $result = $this->instance->deleteByIDs($ids);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
