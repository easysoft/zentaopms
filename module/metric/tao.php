<?php
declare(strict_types=1);
/**
 * The tao file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */

class metricTao extends metricModel
{
    /**
     * 获取度量项计算文件的根目录。
     * Get root of metric calculator.
     *
     * @access protected
     * @return string
     */
    protected function getCalcRoot()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc' . DS;
    }

    /**
     * 获取用户自定义的度量项计算文件的根目录。
     * Get root of custom metric calculator.
     *
     * @access protected
     * @return string
     */
    protected function getCustomCalcRoot()
    {
        return $this->app->getTmpRoot() . 'metric' .DS;
    }

    /**
     * 获取数据集文件的路径
     * Get path of calculator data set.
     *
     * @access protected
     * @return string
     */
    protected function getDatasetPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'dataset.php';
    }

    /**
     * 获取度量项基类文件的路径。
     * Get path of base calculator class.
     *
     * @access protected
     * @return string
     */
    protected function getBaseCalcPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc.class.php';
    }

    /**
     * 获取自定义的度量项计算文件的路径。
     * Get path of custom calculator file.
     *
     * @access protected
     * @return string
     */
    protected function getCustomCalcFile($code)
    {
        return $this->getCustomCalcRoot() . $code . '.php';
    }

    /**
     * 请求度量项数据列表。
     * Fetch metric list.
     *
     * @param  string    $scope
     * @param  string    $stage
     * @param  string    $object
     * @param  string    $purpose
     * @param  string    $query
     * @param  stirng    $sort
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function fetchMetrics($scope, $stage = 'all', $object = '', $purpose = '', $query = '', $sort = 'id_desc', $pager = null)
    {
        $metrics = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->andWhere('object')->in(array_keys($this->lang->metric->objectList))
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($stage != 'all')->andWhere('stage')->eq($stage)->fi()
            ->beginIF(!empty($object))->andWhere('object')->eq($object)->fi()
            ->beginIF(!empty($purpose))->andWhere('purpose')->eq($purpose)->fi()
            ->beginIF($sort)->orderBy($sort)->fi()
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll();

        return $metrics;
    }

    /**
     * 根据度量项编码获取度量项数据。
     * Fetch metric by code.
     *
     * @param  string       $code
     * @access protected
     * @return object|false
     */
    protected function fetchMetricByCode(string $code): object|false
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('code')->eq($code)
            ->fetch();
    }

    /**
     * 根据筛选条件获取度量项数据。
     * Fetch metric by filter.
     *
     * @param  array    $filters
     * @param  string $stage
     * @access protected
     * @return array
     */
    protected function fetchMetricsWithFilter(array $filters, string $stage = 'all'): array
    {
        $scopes   = null;
        $objects  = null;
        $purposes = null;

        if(isset($filters['scope']) && !empty($filters['scope'])) $scopes = implode(',', $filters['scope']);
        if(isset($filters['object']) && !empty($filters['object'])) $objects = implode(',', $filters['object']);
        if(isset($filters['purpose']) && !empty($filters['purpose'])) $purposes = implode(',', $filters['purpose']);

        $metrics = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->beginIF($stage != 'all')->andWhere('stage')->eq($stage)->fi()
            ->beginIF(!empty($scopes))->andWhere('scope')->in($scopes)->fi()
            ->beginIF(!empty($objects))->andWhere('object')->in($objects)->fi()
            ->beginIF(!empty($purposes))->andWhere('purpose')->in($purposes)->fi()
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,issue,risk')
            ->fetchAll();

        return $metrics;
    }

    /**
     * 请求我的收藏度量项。
     * Fetch my collect metrics.
     *
     * @param  string $stage
     * @access protected
     * @return array
     */
    protected function fetchMetricsByCollect(string $stage): array
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('collector')->like("%,{$this->app->user->account},%")
            ->beginIF($stage!= 'all')->andWhere('stage')->eq($stage)->fi()
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,issue,risk')
            ->fetchAll();
    }

    /**
     * 请求模块数据。
     * Fetch module data.
     *
     * @param string  $scope
     * @access protected
     * @return void
     */
    protected function fetchModules($scope)
    {
        return $this->dao->select('object, purpose')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,issue,risk')
            ->groupBy('object, purpose')
            ->fetchAll();
    }

    /**
     * 通过反射获取类的函数列表。
     * Get method name list of class by reflection.
     *
     * @param  string $className
     * @access protected
     * @return array
     */
    protected function getMethodNameList($className)
    {
        $classReflection = new ReflectionClass($className);
        $methodList = $classReflection->getMethods();

        $methodNameList = array();
        foreach($methodList as $index => $reflectionMethod)
        {
            if($reflectionMethod->class == $className) $methodNameList[$index] = $reflectionMethod->name;
        }

        return $methodNameList;
    }

    /**
     * 请求度量数据。
     * Fetch metric data.
     *
     * @param  string $code
     * @param  array  $fieldList
     * @access protected
     * @return array
     */
    protected function fetchMetricRecords(string $code, array $fieldList, array $query = array()): array
    {
        $dataFieldStr = implode(', ', $fieldList);
        if(!empty($dataFieldStr)) $dataFieldStr .= ', ';

        $record = $this->dao->select("id, {$dataFieldStr} value, date")
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->fetch();
        if(!$record) return array();

        $fieldList = array_keys((array)($record));
        $scopeList = array_intersect($fieldList, $this->config->metric->scopeList);
        $dateList  = array_intersect($fieldList, $this->config->metric->dateList);

        $date = '';
        if(empty($query))
        {
            // 如果二者为空，说明最终需要的数据只有两列，而这作为全局数据的标记，所以要取所有的数据，而不是最后一次生成的
            if(empty($scopeList) and empty($dateList))
            {
                $date = date('Y-m-d H:i:s', 0);
            }
            else
            {
                $maxDate = $this->dao->select("max(date) maxDate")->from(TABLE_METRICLIB)->fetch('maxDate');
                $date    = substr($maxDate, 0, 10);
            }
        }

        $scope     = $this->processRecordQuery($query, 'scope');
        $dateBegin = $this->processRecordQuery($query, 'dateBegin', 'date');
        $dateEnd   = $this->processRecordQuery($query, 'dateEnd', 'date');
        $calcTime  = $this->processRecordQuery($query, 'calcTime');
        $calcBegin = $this->processRecordQuery($query, 'calcBegin');
        $calcEnd   = $this->processRecordQuery($query, 'calcEnd');

        $dateType = empty($dateList) ? '' : $this->getDateType($dateList);

        $yearBegin  = empty($dateBegin) ? '' : $dateBegin->year;
        $yearEnd    = empty($dateEnd)   ? '' : $dateEnd->year;
        $monthBegin = empty($dateBegin) ? '' : $dateBegin->month;
        $monthEnd   = empty($dateEnd)   ? '' : $dateEnd->month;
        $weekBegin  = empty($dateBegin) ? '' : $dateBegin->week;
        $weekEnd    = empty($dateEnd)   ? '' : $dateEnd->week;
        $dayBegin   = empty($dateBegin) ? '' : $dateBegin->day;
        $dayEnd     = empty($dateEnd)   ? '' : $dateEnd->day;

        $scopeKey   = current($scopeList);
        $scopeValue = $scope;

        $records =  $this->dao->select("id, {$dataFieldStr} value, date")
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF(!empty($scope))->andWhere($scopeKey)->in($scopeValue)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'year')->andWhere('`year`')->ge($yearBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'year')->andWhere('`year`')->le($yearEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->ge($monthBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->le($monthEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->ge($weekBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->le($weekEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->ge($dayBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->le($dayEnd)->fi()
            ->beginIF(!empty($calcTime))->andWhere('left(date, 10)')->eq($calcTime)->fi()
            ->beginIF(!empty($calcBegin))->andWhere('left(date, 10)')->ge($calcBegin)->fi()
            ->beginIF(!empty($calcEnd))->andWhere('left(date, 10)')->le($calcEnd)->fi()
            ->beginIF(empty($query))->andWhere('date')->gt($date)->fi()
            ->beginIF(!empty($scopeList))->orderBy("date desc, $scopeKey, year desc, month desc, week desc, day desc")->fi()
            ->beginIF(empty($scopeList))->orderBy("date desc, year desc, month desc, week desc, day desc")->fi()
            ->fetchAll();

        return $records;
    }

    /**
     * 处理度量数据查询条件。
     * Process metric data query.
     *
     * @param  array     $query
     * @param  string    $key
     * @param  string    $type
     * @access protected
     * @return object|string|false
     */
    protected function processRecordQuery(array $query, string $key, string $type = 'common'): object|string|false
    {
        if(!isset($query[$key]) || empty($query[$key])) return false;

        if($type == 'date')
        {
            list($year, $month, $day) = explode('-', $query[$key]);

            $timestamp = strtotime($query[$key]);
            $week      = date('W', $timestamp);

            $dateParse = new stdClass();
            $dateParse->year  = $year;
            $dateParse->month = "{$year}{$month}";
            $dateParse->week  = "{$year}{$week}";
            $dateParse->day   = "{$year}{$month}{$day}";

            return $dateParse;
        }
        return $query[$key];
    }

    /**
     * 获取度量数据的日期字段。
     * Get date field of metric data.
     *
     * @param  string $code
     * @access protected
     * @return array
     */
    protected function getMetricRecordDateField(string $code): array
    {
        $record = $this->dao->select("year, month, week, day")
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->fetch();

        if(!$record) return array();

        $dataFields = array();
        $recordKeys = array_keys((array)$record);
        foreach($recordKeys as $recordKey)
        {
            if(!empty($record->$recordKey)) $dataFields[] = $recordKey;
        }

        return $dataFields;
    }

    /**
     * 获取度量数据的日期类型。
     * Get date type of metric data.
     *
     * @param  array    $dateFields
     * @access protected
     * @return string
     */
    protected function getDateType(array $dateFields): string
    {
        if(in_array('day', $dateFields)) return 'day';
        if(in_array('week', $dateFields)) return 'week';
        if(in_array('month', $dateFields)) return 'month';
        if(in_array('year', $dateFields)) return 'year';
    }

    /**
     * 解析SQL函数。
     * Parsing SQL function.
     *
     * @param  string $sql
     * @access protected
     * @return string
     */
    protected function parseSqlFunction($sql)
    {
        $pattern = "/create\s+function\s+`{0,1}([\$,a-z,A-z,_,0-9,\(,|)]+`{0,1})\(+/Ui";
        preg_match_all($pattern, $sql, $matches);

        if(empty($matches[1][0])) return null;
        return trim($matches[1][0], '`');
    }

    /**
     * 替换换行符和回车符为指定字符。
     * Replace CR and LF to char.
     *
     * @param  string $str
     * @param  string $replace
     * @access protected
     * @return string
     */
    protected function replaceCRLF(string $str, string $replace = ';'): string
    {
        $str = trim($str);
        if(strpos($str, "\n\r") !== false) $str = str_replace("\n\r", $replace, $str);
        if(strpos($str, "\r\n") !== false) $str = str_replace("\r\n", $replace, $str);
        if(strpos($str, "\n") !== false)   $str = str_replace("\n",   $replace, $str);
        if(strpos($str, "\r") !== false)   $str = str_replace("\r",   $replace, $str);

        return $str;
    }
}
