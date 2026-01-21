<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class metricTaoTest extends baseTest
{
    protected $moduleName = 'metric';
    protected $className  = 'tao';

    /**
     * Test getObjectsWithPager method.
     *
     * @param  object      $metric
     * @param  object      $query
     * @param  object|null $pager
     * @param  array       $extra
     * @access public
     * @return mixed
     */
    public function getObjectsWithPagerTest($metric = null, $query = null, $pager = null, $extra = array())
    {
        $result = $this->invokeArgs('getObjectsWithPager', [$metric, $query, $pager, $extra]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test executeDelete method.
     *
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function executeDeleteTest($code = '')
    {
        global $tester;

        if(empty($code)) return 'invalid_code';

        // 记录删除前的记录数
        $beforeCount = \$this->instance->dao->select('COUNT(*) as count')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->fetch('count');

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('executeDelete');
        $method->setAccessible(true);

        $method->invoke($this->instance, $code);
        if(dao::isError()) return dao::getError();

        // 记录删除后的记录数
        $afterCount = \$this->instance->dao->select('COUNT(*) as count')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->fetch('count');

        return $beforeCount - $afterCount;
    }

    /**
     * Test fetchLatestMetricRecords method.
     *
     * @param  string      $code
     * @param  array       $fieldList
     * @param  array       $query
     * @param  object|null $pager
     * @access public
     * @return mixed
     */
    public function fetchLatestMetricRecordsTest($code = null, $fieldList = array(), $query = array(), $pager = null)
    {
        if(!$code) return array();

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('fetchLatestMetricRecords');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $code, $fieldList, $query, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchMetricRecordByDate method.
     *
     * @param  string $code
     * @param  string $date
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function fetchMetricRecordByDateTest($code = 'all', $date = '', $limit = 100)
    {
        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('fetchMetricRecordByDate');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $code, $date, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchMetricRecords method.
     *
     * @param  string $code
     * @param  array  $fieldList
     * @param  array  $query
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function fetchMetricRecordsTest($code = '', $fieldList = array(), $query = array(), $pager = null)
    {
        // 检查度量项是否存在，如果不存在则返回空数组
        $metric = $this->instance->getByCode($code);
        if(!$metric) return array();

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('fetchMetricRecords');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $code, $fieldList, $query, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchMetricRecordsWithOption method.
     *
     * @param  string $code
     * @param  array  $fieldList
     * @param  array  $options
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function fetchMetricRecordsWithOptionTest($code = '', $fieldList = array(), $options = array(), $pager = null)
    {
        // 检查度量项是否存在，如果不存在则返回空数组
        $metric = $this->instance->getByCode($code);
        if(!$metric) return array();

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('fetchMetricRecordsWithOption');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $code, $fieldList, $options, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test keepLatestRecords method.
     *
     * @param  string $code
     * @param  array  $fields
     * @access public
     * @return mixed
     */
    public function keepLatestRecordsTest($code = '', $fields = array())
    {
        global $tester;

        if(empty($code)) return 'invalid_code';

        // 记录操作前未删除的记录数
        $beforeUndeleted = \$this->instance->dao->select('COUNT(*) as count')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->andWhere('deleted')->eq('0')
            ->fetch('count');

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('keepLatestRecords');
        $method->setAccessible(true);

        $method->invoke($this->instance, $code, $fields);
        if(dao::isError()) return dao::getError();

        // 记录操作后未删除的记录数
        $afterUndeleted = \$this->instance->dao->select('COUNT(*) as count')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->andWhere('deleted')->eq('0')
            ->fetch('count');

        return $afterUndeleted - $beforeUndeleted;
    }

    /**
     * Test rebuildIdColumn method.
     *
     * @param  string $testType
     * @access public
     * @return mixed
     */
    public function rebuildIdColumnTest($testType = '')
    {
        global $tester;

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('rebuildIdColumn');
        $method->setAccessible(true);

        if(empty($testType))
        {
            // 测试空表情况
            \$this->instance->dao->delete()->from(TABLE_METRICLIB)->exec();
            $method->invoke($this->instance);
            if(dao::isError()) return dao::getError();
            return array('result' => 'empty_table');
        }

        if($testType == 'normal')
        {
            // 清空表并插入正常数据
            \$this->instance->dao->delete()->from(TABLE_METRICLIB)->exec();
            for($i = 1; $i <= 5; $i++)
            {
                $record = new stdClass();
                $record->metricCode = 'test_metric_' . $i;
                $record->value = $i * 10;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $i);
                $record->date = '2024-01-' . sprintf('%02d', $i);
                \$this->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();
            }

            $method->invoke($this->instance);
            if(dao::isError()) return dao::getError();

            // 验证ID是否从1开始连续
            $records = \$this->instance->dao->select('id')->from(TABLE_METRICLIB)->orderBy('id')->fetchAll();
            for($i = 0; $i < count($records); $i++)
            {
                if($records[$i]->id != ($i + 1)) return array('result' => 'failed');
            }
            return array('result' => 'success');
        }

        if($testType == 'discontinuous')
        {
            // 清空表并插入不连续ID数据
            \$this->instance->dao->delete()->from(TABLE_METRICLIB)->exec();
            $ids = array(2, 5, 8, 12, 15);
            foreach($ids as $index => $id)
            {
                $record = new stdClass();
                $record->metricCode = 'test_metric_' . $id;
                $record->value = $id * 10;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $index + 1);
                $record->date = '2024-01-' . sprintf('%02d', $index + 1);
                \$this->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();
                \$this->instance->dao->update(TABLE_METRICLIB)->set('id')->eq($id)->where('metricCode')->eq('test_metric_' . $id)->exec();
            }

            $method->invoke($this->instance);
            if(dao::isError()) return dao::getError();

            // 验证ID是否变为连续
            $records = \$this->instance->dao->select('id')->from(TABLE_METRICLIB)->orderBy('id')->fetchAll();
            for($i = 0; $i < count($records); $i++)
            {
                if($records[$i]->id != ($i + 1)) return array('result' => 'failed');
            }
            return array('result' => 'success');
        }

        if($testType == 'large')
        {
            // 清空表并插入大量数据测试
            \$this->instance->dao->delete()->from(TABLE_METRICLIB)->exec();
            for($i = 1; $i <= 50; $i++)
            {
                $record = new stdClass();
                $record->metricCode = 'large_test_' . $i;
                $record->value = $i * 100;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $i % 28 + 1);
                $record->date = '2024-01-' . sprintf('%02d', $i % 28 + 1);
                \$this->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();
            }

            $method->invoke($this->instance);
            if(dao::isError()) return dao::getError();

            // 验证大量数据的ID连续性
            $count = \$this->instance->dao->select('COUNT(*) as count')->from(TABLE_METRICLIB)->fetch('count');
            $maxId = \$this->instance->dao->select('MAX(id) as maxId')->from(TABLE_METRICLIB)->fetch('maxId');
            if($count == $maxId && $count == 50) return array('result' => 'success');
            return array('result' => 'failed');
        }

        if($testType == 'autoincrement')
        {
            // 验证AUTO_INCREMENT值设置
            \$this->instance->dao->delete()->from(TABLE_METRICLIB)->exec();
            for($i = 1; $i <= 3; $i++)
            {
                $record = new stdClass();
                $record->metricCode = 'auto_test_' . $i;
                $record->value = $i * 10;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $i);
                $record->date = '2024-01-' . sprintf('%02d', $i);
                \$this->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();
            }

            $method->invoke($this->instance);
            if(dao::isError()) return dao::getError();

            // 验证下次插入的ID是否正确
            $record = new stdClass();
            $record->metricCode = 'auto_increment_test';
            $record->value = 999;
            $record->year = '2024';
            $record->month = '01';
            $record->day = '04';
            $record->date = '2024-01-04';
            \$this->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();

            $newId = \$this->instance->dao->select('id')->from(TABLE_METRICLIB)->where('metricCode')->eq('auto_increment_test')->fetch('id');
            return array('autoIncrement' => ($newId == 4 ? '1' : '0'));
        }

        return array('result' => 'unknown_test_type');
    }

    /**
     * Test setDeleted method.
     *
     * @param  string $code
     * @param  string $value
     * @access public
     * @return mixed
     */
    public function setDeletedTest($code = '', $value = '0')
    {
        global $tester;

        if(empty($code)) return 'invalid_code';

        // 记录更新前的状态
        $beforeCount = \$this->instance->dao->select('COUNT(*) as count')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->andWhere('deleted')->eq($value)
            ->fetch('count');

        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('setDeleted');
        $method->setAccessible(true);

        $method->invoke($this->instance, $code, $value);
        if(dao::isError()) return dao::getError();

        // 记录更新后的状态
        $afterCount = \$this->instance->dao->select('COUNT(*) as count')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->andWhere('deleted')->eq($value)
            ->fetch('count');

        return $afterCount - $beforeCount;
    }
}
