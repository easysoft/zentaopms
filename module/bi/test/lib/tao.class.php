<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class biTaoTest extends baseTest
{
    protected $moduleName = 'bi';
    protected $className  = 'tao';

    /**
     * Test fetchAllTables method.
     *
     * @access public
     * @return mixed
     */
    public function fetchAllTablesTest()
    {
        $result = $this->invokeArgs('fetchAllTables');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test fetchTableQueue method.
     *
     * @access public
     * @return mixed
     */
    public function fetchTableQueueTest()
    {
        $result = $this->invokeArgs('fetchTableQueue');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test updateSyncTime method.
     *
     * @param  array $tables
     * @access public
     * @return mixed
     */
    public function updateSyncTimeTest($tables)
    {
        if(empty($tables)) return 0;

        $this->invokeArgs('updateSyncTime', [$tables]);
        if(dao::isError()) return dao::getError();

        $updatedCount = $dao->select('COUNT(1) AS count')->from(TABLE_DUCKDBQUEUE)
            ->where('object')->in($tables)
            ->andWhere('syncTime')->ge($currentTime)
            ->fetch('count');

        return $updatedCount;
    }

    /**
     * Test fetchActionDate method.
     *
     * @access public
     * @return mixed
     */
    public function fetchActionDateTest()
    {
        $result = $this->invokeArgs('fetchActionDate');
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
