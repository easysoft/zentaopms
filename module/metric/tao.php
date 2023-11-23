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

    protected function fetchMetricsByIDList($metricIDList)
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($metricIDList)
            ->fetchAll();
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
     * 请求度量数据。
     * Fetch metric data.
     *
     * @param  string      $code
     * @param  array       $fieldList
     * @param  array       $query
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function fetchMetricRecords(string $code, array $fieldList, array $query = array(), object|null $pager = null): array
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

        /**
         * 如果没有传入筛选参数
         *   如果范围和日期列为空，说明最终需要的数据只有两列，而这作为全局数据的标记，所以要取所有的数据，否则只取今天之前的数据。
         * 如果传入了筛选参数，则按照筛选参数进行筛选。
         * If no filtering parameters are passed in
         *   If the range and date columns are empty, only two columns of data are needed at the end, and this serves as a marker for global data, so get all the data, otherwise only the data before today.
         * If filtering parameters are passed in, filter according to the filtering parameters.
         */
        $date = empty($query) ? (empty($scopeList) && empty($dateList) ? date('Y-m-d H:i:s', 0) : helper::today()) : '';

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
            ->page($pager)
            ->fetchAll();

        return $records;
    }
}
