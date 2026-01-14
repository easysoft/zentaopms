<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class holidayModelTest extends baseTest
{
    protected $moduleName = 'holiday';
    protected $className  = 'model';

    /**
     * 测试通过 ID 获取节假日。
     * Test get holiday by id.
     *
     * @param  int           $id
     * @access public
     * @return object|string|array
     */
    public function getByIdTest(int $id): object|string|array
    {
        $objects = $this->instance->getById($id);

        if($objects === false) return 'Object not found';
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取节假日列表
     * Test get holiday list.
     *
     * @param  string $year
     * @param  string $type
     * @access public
     * @return string|array
     */
    public function getListTest(string $year = '', string $type = 'all'): string|array
    {
        if($year == 'thisyear') $year = date('Y');
        if($year == 'lastyear') $year = date('Y', strtotime('-1 year'));
        $objects = $this->instance->getList($year, $type);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * 测试获取年份。
     * Test get year pairs.
     *
     * @access public
     * @return int|array
     */
    public function getYearPairsTest(): int|array
    {
        $objects = $this->instance->getYearPairs();

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试获取特定年份的年份对。
     * Test get year pairs with specific year.
     *
     * @param  string $year
     * @access public
     * @return string|array
     */
    public function getYearPairsTestWithSpecificYear(string $year): string|array
    {
        global $tester;
        $objects = $tester->dao->select('year,year')->from(TABLE_HOLIDAY)->where('year')->eq($year)->groupBy('year')->orderBy('year_desc')->fetchPairs();

        if(dao::isError()) return dao::getError();

        return count($objects) > 0 ? $year : '0';
    }

    /**
     * 测试空表时获取年份对。
     * Test get year pairs with empty table.
     *
     * @access public
     * @return int|array
     */
    public function getYearPairsTestEmptyTable(): int|array
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_HOLIDAY)->exec();
        $objects = $this->instance->getYearPairs();

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试包含空年份时获取年份对。
     * Test get year pairs with empty year.
     *
     * @access public
     * @return int|array
     */
    public function getYearPairsTestWithEmptyYear(): int|array
    {
        global $tester;
        $tester->dao->update(TABLE_HOLIDAY)->set('year')->eq('')->where('id')->eq('1')->exec();
        $objects = $this->instance->getYearPairs();

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试多年份数据。
     * Test multiple year data.
     *
     * @access public
     * @return int|array
     */
    public function getYearPairsTestMultiYear(): int|array
    {
        global $tester;
        $tester->dao->insert(TABLE_HOLIDAY)->data(array(
            'name' => '测试节假日',
            'type' => 'holiday',
            'year' => '2024',
            'begin' => '2024-01-01',
            'end' => '2024-01-01',
            'desc' => '测试'
        ))->exec();

        $objects = $this->instance->getYearPairs();

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试年份排序验证。
     * Test year order validation.
     *
     * @access public
     * @return string|array
     */
    public function getYearPairsTestOrderValidation(): string|array
    {
        $objects = $this->instance->getYearPairs();

        if(dao::isError()) return dao::getError();

        $years = array_keys($objects);
        return count($years) > 0 ? (string)$years[0] : '0';
    }

    /**
     * 测试创建一个家假日。
     * Test create a holiday.
     *
     * @param  array             $param
     * @access public
     * @return object|bool|array
     */
    public function createTest(array $param = array()): object|bool|array
    {
        $defaultParam['type']  = 'holiday';
        $defaultParam['begin'] = '2022-01-01';
        $defaultParam['end']   = '2022-02-01';
        $defaultParam['name']  = '测试创建holiday';
        $defaultParam['desc']  = '默认的holiday';

        $holiday = new stdclass();

        foreach($defaultParam as $field => $defaultValue) $holiday->{$field} = $defaultValue;
        foreach($param as $key => $value) $holiday->{$key} = $value;

        $lastInsertID = $this->instance->create($holiday);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->instance->getByID($lastInsertID);
            return $object;
        }
    }

    /**
     * 测试更新一个节假日。
     * Test update a holiday.
     *
     * @param  int    $holidayID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function updateTest(int $holidayID, array $param = array()): string|array
    {
        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_HOLIDAY  ." WHERE id = {$holidayID}")->fetch();

        $holiday = new stdclass();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $holiday->{$field} = $param[$field];
            }
            else
            {
                $holiday->{$field} = $value;
            }
        }

        $this->instance->update($holiday);
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return 'true';
        }
    }

    /**
     * 测试通过开始和结束日期获取节假日。
     * Test get holidays by begin and end.
     *
     * @param  string       $begin
     * @param  string       $end
     * @access public
     * @return int|array
     */
    public function getHolidaysTest(string $begin, string $end): int|array
    {
        $begin = date('Y-m-d', strtotime($begin));
        $end   = date('Y-m-d', strtotime($end));
        $objects = $this->instance->getHolidays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试获取工作日。
     * Test get working days.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return int|array
     */
    public function getWorkingDaysTest(string $begin = '', string $end = ''): int|array
    {
        $begin = date('Y-m-d', strtotime($begin));
        $end   = date('Y-m-d', strtotime($end));
        $objects = $this->instance->getWorkingDays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试获取实际工作日。
     * Test get actual working days.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return int|array
     */
    public function getActualWorkingDaysTest(string $begin, string $end): int|array
    {
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : '0000-00-00';
        $end   = $end ? date('Y-m-d', strtotime($end)) : '';
        $objects = $this->instance->getActualWorkingDays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试获取开始和结束日期之间的日期。
     * Test get the dates between the begin and end.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return int|array
     */
    public function getDaysBetweenTest(string $begin, string $end): int|array
    {
        $objects = $this->instance->getDaysBetween($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试某天是否是节假日.
     * Test judge if is holiday.
     *
     * @param  string      $date
     * @access public
     * @return string|array
     */
    public function isHolidayTest(string $date): string|array
    {
        $objects = $this->instance->isHoliday($date);

        if(dao::isError()) return dao::getError();

        return $objects === true ? "It is a holiday" : "It is not a holiday";
    }

    /**
     * 测试某天是否是工作日。
     * Test judge if is a working day.
     *
     * @param  string       $date
     * @access public
     * @return string|array
     */
    public function isWorkingDayTest(string $date): string|array
    {
        $date = !empty($date) ? date('Y-m-d', strtotime($date)) : '0000-00-00';
        $objects = $this->instance->isWorkingDay($date);

        if(dao::isError()) return dao::getError();

        return $objects === true ? 'It is a working day' : 'It is not a working day';
    }

    /**
     * 测试更新项目工期。
     * Test update project duration.
     *
     * @param  int       $testProjectID
     * @param  int       $holidayID
     * @param  bool      $updateDuration
     * @access public
     * @return int|array
     */
    public function updateProgramPlanDurationTest(int $testProjectID, int $holidayID, bool $updateDuration): int|array
    {
        global $tester;

        if($updateDuration)
        {
            $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();
            $this->instance->updateProgramPlanDuration($holiday->begin, $holiday->end);
        }

        $project = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($testProjectID)->fetch();

        if(dao::isError()) return dao::getError();

        return $project->planDuration;
    }

    /**
     * 测试更新项目的实际工期。
     * Test update project real duration.
     *
     * @param  int       $testProjectID
     * @param  int       $holiday
     * @param  bool      $updateDuration
     * @access public
     * @return int|array
     */
    public function updateProjectRealDurationTest(int $testProjectID, int $holidayID, bool $updateDuration): int|array
    {
        global $tester;

        if($updateDuration)
        {
            $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();
            $this->instance->updateProjectRealDuration($holiday->begin, $holiday->end);
        }

        $project = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($testProjectID)->fetch();

        if(dao::isError()) return dao::getError();

        return $project->realDuration;
    }

    /**
     * 测试更新任务计划工期。
     * Test update task plan duration.
     *
     * @param  int       $testTaskID
     * @param  int       $holidayID
     * @param  bool      $updateDuration
     * @access public
     * @return int|array
     */
    public function updateTaskPlanDurationTest(int $testTaskID, int $holidayID, bool $updateDuration): int|array
    {
        global $tester;

        if($updateDuration)
        {
            $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();
            $this->instance->updateTaskPlanDuration($holiday->begin, $holiday->end);
        }

        $task = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq($testTaskID)->fetch();

        if(dao::isError()) return dao::getError();

        return $task->planDuration;
    }

    /**
     * 测试更新任务实际工期。
     * Test update task real duration.
     *
     * @param  int       $testTaskID
     * @param  int       $holidayID
     * @param  bool      $updateDuration
     * @access public
     * @return int|array
     */
    public function updateTaskRealDurationTest(int $testTaskID, int $holidayID, bool $updateDuration): int|array
    {
        global $tester;

        if($updateDuration)
        {
            $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();
            $this->instance->updateTaskRealDuration($holiday->begin, $holiday->end);
        }

        $task = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq($testTaskID)->fetch();

        if(dao::isError()) return dao::getError();

        return $task->realDuration;
    }

    /**
     * 测试通过 API 获取节假日。
     * Test get holidays by api.
     *
     * @param  string       $year
     * @access public
     * @return int|array
     */
    public function getHolidayByAPITest(string $year): int|array
    {
        global $app;
        $app->wwwRoot = dirname(__FILE__, 5) . DS . 'www' . DS;
        common::$httpClient = new httpClient();

        if($year == 'this year') $year = '2023';
        if($year == 'last year') $year = '2022';
        if($year == 'next year') $year = '2024';
        if($year == 'invalid') $year = 'invalid';
        $objects = $this->instance->getHolidayByAPI($year);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试创建一个家假日。
     * Test create a holiday.
     *
     * @param  array             $holidayParams
     * @access public
     * @return object|bool|array
     */
    public function batchCreateTest(array $holidayParams = array()): object|bool|array
    {
        $defaultParam['type']  = 'holiday';
        $defaultParam['begin'] = '2022-01-01';
        $defaultParam['end']   = '2022-02-01';
        $defaultParam['name']  = '测试创建holiday';
        $defaultParam['desc']  = '默认的holiday';


        $holidays = array();
        foreach($holidayParams as $holidayParam)
        {
            $holiday = new stdclass();
            foreach($defaultParam as $field => $defaultValue) $holiday->{$field} = $defaultValue;
            foreach($holidayParam as $key => $value) $holiday->{$key} = $value;
            $holidays[] = $holiday;
        }

        $lastInsertID = $this->instance->batchCreate($holidays);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->instance->getByID($lastInsertID);
            return $object;
        }
    }
}
