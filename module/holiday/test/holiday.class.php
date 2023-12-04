<?php
declare(strict_types=1);
class holidayTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('holiday');
    }

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
        $objects = $this->objectModel->getById($id);

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
        $objects = $this->objectModel->getList($year, $type);

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
        $objects = $this->objectModel->getYearPairs();

        if(dao::isError()) return dao::getError();

        return count($objects);
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

        $lastInsertID = $this->objectModel->create($holiday);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getByID($lastInsertID);
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

        $this->objectModel->update($holiday);
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
        $objects = $this->objectModel->getHolidays($begin, $end);

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
        $objects = $this->objectModel->getWorkingDays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test getActualWorkingDays method.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return int
     */
    public function getActualWorkingDaysTest($begin, $end)
    {
        $objects = $this->objectModel->getActualWorkingDays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test getDaysBetweenTest method.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return int
     */
    public function getDaysBetweenTest($begin, $end)
    {
        $objects = $this->objectModel->getDaysBetween($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test isHoliday method.
     *
     * @param  string    $date
     * @access public
     * @return object
     */
    public function isHolidayTest($date)
    {
        $objects = $this->objectModel->isHoliday($date);

        if(dao::isError()) return dao::getError();

        return $objects === true ? "It is a holiday" : "It is not a holiday";
    }

    /**
     * Test isWorkingDay method.
     *
     * @param  string    $date
     * @access public
     * @return object
     */
    public function isWorkingDayTest($date)
    {
        $objects = $this->objectModel->isWorkingDay($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test updateProgramPlanDuration method.
     *
     * @param  int       $testProjectID
     * @param  object    $holiday
     * @access public
     * @return int
     */
    public function updateProgramPlanDurationTest($testProjectID, $holidayID)
    {
        global $tester;

        $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();

        $this->objectModel->updateProgramPlanDuration($holiday->begin, $holiday->end);

        $project = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($testProjectID)->fetch();

        if(dao::isError()) return dao::getError();

        return $project->planDuration;
    }

    /**
     * Test updateProjectRealDuration method.
     *
     * @param  int       $testProjectID
     * @param  object    $holiday
     * @access public
     * @return int
     */
    public function updateProjectRealDurationTest($testProjectID, $holidayID)
    {
        global $tester;

        $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();

        $this->objectModel->updateProjectRealDuration($holiday->begin, $holiday->end);

        $project = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($testProjectID)->fetch();

        if(dao::isError()) return dao::getError();

        return $project->realDuration;
    }

    /**
     * Test updateTaskPlanDuration method.
     *
     * @param  int       $testTaskID
     * @param  object    $holiday
     * @access public
     * @return int
     */
    public function updateTaskPlanDurationTest($testTaskID, $holidayID)
    {
        global $tester;

        $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();

        $this->objectModel->updateTaskPlanDuration($holiday->begin, $holiday->end);

        $task = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq($testTaskID)->fetch();

        if(dao::isError()) return dao::getError();

        return $task->planDuration;
    }

    /**
     * Test updateTaskRealDuration method.
     *
     * @param  int       $testTaskID
     * @param  object    $holiday
     * @access public
     * @return int
     */
    public function updateTaskRealDurationTest($testTaskID, $holidayID)
    {
        global $tester;

        $holiday = $tester->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($holidayID)->fetch();

        $this->objectModel->updateTaskRealDuration($holiday->begin, $holiday->end);

        $task = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq($testTaskID)->fetch();

        if(dao::isError()) return dao::getError();

        return $task->realDuration;
    }
}
