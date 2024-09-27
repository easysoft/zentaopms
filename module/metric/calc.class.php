<?php
/**
 * 度量项定义基类。
 * Base class of measurement func.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @license   LGPL
 * @Link      https://www.zentao.net
 */
class baseCalc
{
    /**
     * 来源数据集。
     * dataset
     *
     * @var int
     * @access public
     */
    public $dataset = null;

    /**
     * 数据库连接。
     * Database connection.
     *
     * @var object
     * @access public
     */
    public $dao = null;

    /**
     * Git 仓库对象。
     * Git repository object.
     *
     * @var object
     * @access public
     */
    public $scm = null;

    /**
     * 参数列表。
     * fieldList
     *
     * @var array
     * @access public
     */
    public $fieldList = array();

    /**
     * 数据主表。
     * Main table.
     *
     * @var string
     * @access public
     */
    public $mainTable;

    /**
     * 连接表。
     * Left join tables.
     *
     * @var array
     * @access public
     */
    public $subTables;

    /**
     * 过滤条件。
     * Filters of data.
     *
     * @var array
     * @access public
     */
    public $filters;

    /**
     * 指标结果。
     * Result of indicators.
     *
     * @var array|float
     * @access public
     */
    public $result = 0;

    /**
     * 节假日信息。
     * holidays
     *
     * @var array
     * @access public
     */
    public $holidays = array();

    /**
     * 休息日
     * weekend
     *
     * @var float
     * @access public
     */
    public $weekend = 2;

    /**
     * 是否复用
     *
     * @var bool
     * @access public
     */
    public $reuse = false;

    /**
     * 是否启用initMetricRecords，尽可能补零逻辑
     *
     * @var bool
     * @access public
     */
    public $initRecord = true;

    /**
     * 是否支持独立查询
     *
     * @var bool
     * @access public
     */
    public $supportSingleQuery = false;

    /**
     * 是否使用独立查询
     *
     * @var bool
     * @access public
     */
    public $useSingleQuery = false;

    /**
     * 独立查询sql
     *
     * @var string
     * @access public
     */
    public $singleSql = '';

    /**
     * 是否使用 SCM。
     *
     * @var    bool
     * @access public
     */
    public $useSCM = false;

    /**
     * 设置DAO 。
     * Set DAO.
     *
     * @param  object $dao
     * @access public
     * @return void
     */
    public function setDAO($dao)
    {
        $this->dao = $dao;
    }

    public function setSCM($scm)
    {
        $this->scm = $scm;
    }

    public function setHolidays($holidays)
    {
        $this->holidays = $holidays;
    }

    public function setWeekend($weekend)
    {
        $this->weekend = $weekend;
    }

    /**
     * 获取数据查询句柄。
     * Get pdo statement of sql query.
     *
     * @param  object    $dao
     * @access public
     * @return PDOStatement
     */
    public function getStatement()
    {
    }

    /**
     * 计算度量项。
     * Calculate metric.
     *
     * @param  object $data
     * @access public
     * @return void
     */
    public function calculate($data)
    {
    }

    /**
     * 获取度量项结果。
     *
     * @param  array  $options
     * @access public
     * @return mixed
     */
    public function getResult($options = array())
    {
        if(empty($this->result)) return null;
        return array((object)array('value' => 0));
    }

    /**
     * 根据选项过滤数据。
     * Filter rows by options.
     *
     * @param  array|int $rows
     * @param  array     $options array('product' => '1,2,3,4')
     * @access protected
     * @return array|false
     */
    protected function filterByOptions($rows, $options)
    {
        if(empty($options)) return $rows;

        $rows = (array)$rows;
        $options = $this->expandOptions($options);

        $filteredRows = array();
        foreach($rows as $row)
        {
            $satisify = true;
            foreach($options as $scope => $option)
            {
                $row = (array)$row;
                if(!isset($row[$scope])) continue;
                $satisify = ($satisify && in_array($row[$scope], $option));
            }
            if($satisify) $filteredRows[] = $row;
        }

        return !empty($filteredRows) ? $filteredRows : false;
    }

    /**
     * 判断是否为日期。
     * Check if a string is a date.
     *
     * @param  string    $dateString
     * @access public
     * @return bool
     */
    public function isDate($dateString)
    {
        $format   = 'Y-m-d';
        $dateTime = DateTime::createFromFormat($format, $dateString);

        return $dateTime && $dateTime->format($format) === $dateString;
    }

    /**
     * 扩展选项。
     * Expand options.
     *
     * @param  array  $options
     * @access protected
     * @return array
     */
    protected function expandOptions($options)
    {
        foreach($options as $scope => $option) $options[$scope] = explode(',', $option);
        return $options;
    }

    /**
     * 获取数据源的SQL语句
     * Get SQL of data source.
     *
     * @param  object $dao
     * @param  object $config
     * @access public
     * @return string
     */
    public function getSQL($dao, $config)
    {
        if(!empty($this->dataset))
        {
            $dataSource = $this->dataset;
            include_once dirname(__FILE__) . '/dataset.php';
            $dataset = new dataset($dao, $config);

            $statement = $dataset->$dataSource(implode(',', $this->fieldList));
            return $statement->queryString;
        }

        return $this->dao->get();
    }

    /**
     * 打印数据源的SQL语句
     * Print SQL of data source.
     *
     * @param  object $dao
     * @param  object $config
     * @access public
     * @return mixed
     */
    public function printSQL($dao, $config)
    {
        echo $this->getSQL($dao, $config);
    }

    /**
     * 获取数据源的结果集。
     * Get rows of data source.
     *
     * @param  object $dao
     * @param  object $config
     * @access public
     * @return array
     */
    public function getRows($dao, $config)
    {
        if(!empty($this->dataset))
        {
            $dataSource = $this->dataset;
            include_once dirname(__FILE__) . '/dataset.php';
            $dataset = new dataset($dao, $config);

            $statement = $dataset->$dataSource(implode(',', $this->fieldList));
            return $statement->fetchAll();
        }

        return $this->getStatement()->fetchAll();
    }

    /**
     * 转换计算结果数组为度量记录数据数组。
     * Get records from result.
     *
     * @param  array  $keyNames
     * @access public
     * @return array
     */
    public function getRecords($keyNames, $result = null)
    {
        if($this->useSingleQuery) return $this->singleQuery();

        if(empty($result)) $result = $this->result;

        if(empty($keyNames)) return $result;

        if(current($keyNames) == 'value') return array(array('value' => $result));

        $records = array();
        $keyName = array_shift($keyNames);
        foreach($result as $key => $value) $records[] = array($keyName => $key, 'value' => $value);

        while($keyName = array_shift($keyNames))
        {
            if($keyName == 'value') break;

            $newRecords = array();
            foreach($records as $record)
            {
                foreach($record['value'] as $key => $value)
                {
                    $record[$keyName] = $key;
                    $record['value']  = $value;
                    $newRecords[] = $record;
                }
            }

            $records = $newRecords;
        }

        return $records;
    }

    /**
     * 独立查询。
     *
     * @access public
     * @return void
     */
    public function singleQuery()
    {
        $sql = $this->singleSql;
        return $this->dao->select('*')->from("($sql) tt")->fetchAll();
    }

    /**
     * 设置独立查询sql语句。
     *
     * @param  string  $sql
     * @access public
     * @return void
     */
    public function setSingleSql($sql)
    {
        $this->singleSql = $sql;
    }

    /**
     * 获取独立查询sql语句。
     *
     * @access public
     * @return void
     */
    public function getSingleSql()
    {
        return "({$this->singleSql}) tt";
    }

    /**
     * 启用独立查询。
     *
     * @access public
     * @return void
     */
    public function enableSingleQuery()
    {
        $this->useSingleQuery = true;
    }

    /**
     * 获取日期的周数。
     * Get week of date.
     *
     * @param  string $dateStr
     * @access public
     * @return string
     */
    public function getWeek($dateStr)
    {
        if(empty($dateStr)) return false;

        if(strlen($dateStr) > 10) $dateStr = substr($dateStr, 0, 10);
        $date = DateTime::createFromFormat('Y-m-d', $dateStr);

        return substr($date->format('oW'), -2);
    }

    /**
     * 获取日期的年份。
     * Get year of date.
     *
     * @param  string $dateStr
     * @access public
     * @return string|false
     */
    public function getYear($dateStr)
    {
        if(empty($dateStr)) return false;

        if(strlen($dateStr) > 8) $dateStr = substr($dateStr, 0, 8);
        $year = substr($dateStr, 0, 4);

        return $year == '0000' ? false : $year;
    }

    /**
     * GetLastDay
     *
     * @param  int    $date
     * @access public
     * @return string
     */
    public function getLastDay($date)
    {
        $monday   = $this->getThisMonday($date);
        $sunday   = $this->getThisSunday($date);
        $workdays = $this->getActualWorkingDays($monday, $sunday);
        return end($workdays);
    }

    /**
     * Get monday for a date.
     *
     * @param  int $date
     * @access public
     * @return date
     */
    public function getThisMonday($date)
    {
        $timestamp = strtotime($date);

        $day = date('w', $timestamp);
        if($day == 0) $day = 7;

        return date('Y-m-d', $timestamp - (($day - 1) * 24 * 3600));
    }

    /**
     * Get fridays.
     *
     * @param  string $start
     * @param  string $end
     * @access public
     * @return array
     */
    public function getFridays($start, $end)
    {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = new DateInterval('P1D'); // 间隔为1天
        $period = new DatePeriod($start, $interval, $end);

        $fridays = array();
        foreach ($period as $date) {
            if ($date->format('N') == 5) { // 5代表星期五
                $fridays[] = $date->format('Y-m-d');
            }
        }

        return $fridays;
    }

    public function getFridayByWeek($year, $week) {
        $first_day = date("Y-m-d", strtotime($year . "-01-01"));

        $first_friday = date("Y-m-d", strtotime("{$first_day} next Friday"));

        $friday = date("Y-m-d", strtotime("{$first_friday} + " . ($week - 1) . " weeks"));

        return $friday;
    }

    /**
     * GetThisSunday
     *
     * @param  int    $date
     * @access public
     * @return date
     */
    public function getThisSunday($date)
    {
        $monday = $this->getThisMonday($date);
        return date('Y-m-d', strtotime($monday) + (6 * 24 * 3600));
    }

    /**
     * 获取开始和结束日期间的日期。
     * Get the dates between the begin and end.
     *
     * @param  string  $begin
     * @param  string  $end
     * @access public
     * @return array
     */
    public function getDaysBetween(string $begin, string $end): array
    {
        $beginTime = strtotime($begin);
        $endTime   = strtotime($end);
        $days      = ($endTime - $beginTime) / 86400;

        $dateList  = array();
        for($i = 0; $i <= $days; $i ++) $dateList[] = date('Y-m-d', strtotime("+{$i} days", $beginTime));

        return $dateList;
    }

    /**
     * 通过开始和结束日期获取节假日。
     * Get holidays by begin and end.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getHolidays(string $begin, string $end): array
    {
        $records = array();
        foreach($this->holidays as $holiday)
        {
            if($holiday->type != 'holiday') continue;
            if($holiday->begin > $end || $holiday->end < $begin) continue;

            $records[] = $holiday;
        }

        $naturalDays = $this->getDaysBetween($begin, $end);

        $holidays = array();
        foreach($records as $record)
        {
            $dates    = $this->getDaysBetween($record->begin, $record->end);
            $holidays = array_merge($holidays, $dates);
        }

        return array_intersect($naturalDays, $holidays);
    }

    /**
     * 获取工作日。
     * Get working days.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getWorkingDays(string $begin = '', string $end = ''): array
    {
        $records = array();
        foreach($this->holidays as $holiday)
        {
            if($holiday->type != 'working') continue;
            if($holiday->begin > $end || $holiday->end < $begin) continue;

            $records[] = $holiday;
        }

        $workingDays = array();
        foreach($records as $record)
        {
            $dates       = $this->getDaysBetween($record->begin, $record->end);
            $workingDays = array_merge($workingDays, $dates);
        }

        return $workingDays;
    }

    /**
     * 获取系统休息日配置。
     * Get system weekend.
     *
     * @access public
     * @return void
     */
    public function getSystemWeekend()
    {
        $weekend = $this->dao->select('value')->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('weekend')
            ->fetch('value');

        return $weekend ? $weekend : 2;
    }

    /**
     * 获取实际工作日。
     * Get actual working days.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getActualWorkingDays(string $begin, string $end): array
    {
        if(empty($begin) || empty($end) || $begin == '0000-00-00' || $end == '0000-00-00') return array();

        /* Get holidays, working days and weekend days .*/
        $holidays    = $this->getHolidays($begin, $end);
        $workingDays = $this->getWorkingDays($begin, $end);
        $weekend     = $this->weekend;

        /* When the start date and end date are the same. */
        $actualDays = array();
        if($begin == $end)
        {
            if(in_array($begin, $workingDays)) return array($begin);
            if(in_array($begin, $holidays))    return array();

            $w = date('w', strtotime($begin));
            if($w == 0 || ($weekend == 2 && $w == 6)) return array();

            return array($begin);
        }

        /* Process actual working days. */
        for($i = 0, $currentDay = $begin; $currentDay < $end; $i ++)
        {
            $currentDay = date('Y-m-d', strtotime("{$begin} + {$i} days"));
            $w          = date('w', strtotime($currentDay));

            if(in_array($currentDay, $workingDays))
            {
                $actualDays[] = $currentDay;
                continue;
            }

            if(in_array($currentDay, $holidays)) continue;
            if($w == 0 || ($weekend == 2 && $w == 6)) continue;

            $actualDays[] = $currentDay;
        }

        return $actualDays;
    }

    public function getDevAccountList()
    {
        global $dao;
        return $dao->select('account')->from(TABLE_USER)->where('role')->eq('dev')->andWhere('deleted')->eq('0')->fetchPairs();
    }

    public function getExecutions()
    {
        global $dao;
        $executions = $dao->select("t1.id, t1.name, if(t1.multiple='1', t1.status, t2.status) as status, if(t1.multiple='1', t1.realBegan, t2.realBegan) as realBegan, if(t1.multiple='1', t1.closedDate, t2.closedDate) as closedDate")->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->in('sprint,stage,kanban')
            ->andWhere("t1.vision LIKE '%rnd%'", true)
            ->orWhere("t1.vision IS NULL")->markRight(1)
            ->fetchAll('id');

        return $executions;
    }
}
