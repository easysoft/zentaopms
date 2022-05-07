<?php
class holidayTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('holiday');
    }

    /**
     * Test getById method.
     *
     * @param  string    $id
     * @access public
     * @return object
     */
    public function getByIdTest($id)
    {
        $objects = $this->objectModel->getById($id);

        if($objects === false) return 'Object not found';
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getList method.
     *
     * @param  string $year
     * @param  string $type
     * @access public
     * @return object
     */
    public function getListTest($year = '', $type = 'all')
    {
        $objects = $this->objectModel->getList($year, $type);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test getYearPairs method.
     *
     * @access public
     * @return int
     */
    public function getYearPairsTest()
    {
        $objects = $this->objectModel->getYearPairs();

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test create method.
     *
     * @param  array  $param
     * @access public
     * @return object
     */
    public function createTest($param = array())
    {
        $defaultParam['type']  = 'holiday';
        $defaultParam['begin'] = '2022-01-01';
        $defaultParam['end']   = '2022-02-01';
        $defaultParam['name']  = '测试创建holiday';
        $defaultParam['desc']  = '默认的holiday';

        foreach($defaultParam as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;
        $lastInsertID = $this->objectModel->create();
        unset($_POST);

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
     * Test update method.
     *
     * @param  int    $holidayID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function updateTest($holidayID, $param = array())
    {
        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_HOLIDAY  ." WHERE id = $holidayID")->fetch();

        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $_POST[$field] = $param[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }

        $noError = $this->objectModel->update($bugID);
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
     * Test getHolidays method.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access public
     * @return object
     */
    public function getHolidaysTest($begin, $end)
    {
        $objects = $this->objectModel->getHolidays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    public function getWorkingDaysTest($begin = '', $end = '')
    {
        $objects = $this->objectModel->getWorkingDays($begin, $end);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

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
     * @param  string    $beginDate
     * @param  string    $endDate
     * @access public
     * @return object
     */
    public function updateProgramPlanDurationTest($beginDate, $endDate)
    {
        $objects = $this->objectModel->updateProgramPlanDuration($beginDate, $endDate);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test updateProjectRealDuration method.
     *
     * @param  string    $beginDate
     * @param  string    $endDate
     * @access public
     * @return object
     */
    public function updateProjectRealDurationTest($beginDate, $endDate)
    {
        $objects = $this->objectModel->updateProjectRealDuration($beginDate, $endDate);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test updateTaskPlanDuration method.
     *
     * @param  string    $beginDate
     * @param  string    $endDate
     * @access public
     * @return object
     */
    public function updateTaskPlanDurationTest($beginDate, $endDate)
    {
        $objects = $this->objectModel->updateTaskPlanDuration($beginDate, $endDate);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test updateTaskRealDuration method.
     *
     * @param  string    $beginDate
     * @param  string    $endDate
     * @access public
     * @return object
     */
    public function updateTaskRealDurationTest($beginDate, $endDate)
    {
        $objects = $this->objectModel->updateTaskRealDuration($beginDate, $endDate);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
