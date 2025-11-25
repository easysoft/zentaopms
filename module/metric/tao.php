<?php
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
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->andWhere('object')->in(array_keys($this->lang->metric->objectList))
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($stage != 'all')->andWhere('stage')->eq($stage)->fi()
            ->beginIF(!empty($object))->andWhere('object')->eq($object)->fi()
            ->beginIF(!empty($purpose))->andWhere('purpose')->eq($purpose)->fi()
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,ticket,issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'biz')->andWhere('object')->notIN('issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'ipd' && $this->config->vision == 'rnd')->andWhere('code')->notIN($this->config->metric->orMetricList)->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('code')->notIN($this->config->metric->waterfallCode)->fi()
            ->beginIF($sort)->orderBy($sort)->fi()
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll();
    }

    /**
     * 根据范围获取度量项。
     * Fetch metric by scope.
     *
     * @param  string|array $scopes
     * @param  int          $limit
     * @access protected
     * @return array
     */
    protected function fetchMetricsByScope($scopes, $limit = -1)
    {
        if(!is_array($scopes)) $scopes = array($scopes);
        $metrics = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->in($scopes)
            ->andWhere('object')->in(array_keys($this->lang->metric->objectList))
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll('scope');

        return $metrics;
    }

    /**
     * 根据编号获取度项。
     * Fetch metric by id.
     *
     * @param  string       $code
     * @param  array|string $fields
     * @access protected
     * @return mixed
     */
    protected function fetchMetricByID($code, $fields = '*')
    {
        if(is_array($fields)) $fields = implode(',', $fields);

        $metric = $this->dao->select($fields)->from(TABLE_METRIC)->where('code')->eq($code)->fetch();
        return $metric;
    }

    /**
     * 根据编号列表获取度项。
     * Fetch metric list by id list.
     *
     * @param  array     $metricIDList
     * @access protected
     * @return array
     */
    protected function fetchMetricsByIDList($metricIDList)
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($metricIDList)
            ->fetchAll();
    }

    /**
     * 根据编号列表获取度项。
     * Fetch metric list by id list.
     *
     * @param  array     $metricIDList
     * @access protected
     * @return array
     */
    protected function fetchMetricsByCodeList($codeList)
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq(0)
            ->andWhere('code')->in($codeList)
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
    protected function fetchMetricByCode($code, $fieldList = '*')
    {
        return $this->dao->select($fieldList)->from(TABLE_METRIC)
            ->where('code')->eq($code)
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
    protected function fetchMetricsWithFilter($filters, $stage = 'all')
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
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,ticket,issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'biz')->andWhere('object')->notIN('issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'ipd' && $this->config->vision == 'rnd')->andWhere('code')->notIN($this->config->metric->orMetricList)->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('code')->notIN($this->config->metric->waterfallCode)
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
    protected function fetchMetricsByCollect($stage)
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('collector')->like("%,{$this->app->user->account},%")
            ->beginIF($stage!= 'all')->andWhere('stage')->eq($stage)->fi()
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,ticket,issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'biz')->andWhere('object')->notIN('issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'ipd' && $this->config->vision == 'rnd')->andWhere('code')->notIN($this->config->metric->orMetricList)->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('code')->notIN($this->config->metric->waterfallCode)
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
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,ticket,issue,risk,demand')->fi()
            ->beginIF($this->config->edition == 'biz')->andWhere('object')->notIN('issue,risk,demand')->fi()
            ->groupBy('object, purpose')
            ->fetchAll();
    }

    /**
     * 获取范围对象类型以构建分页对象。
     * Get object list with page.
     *
     * @param string  $code
     * @param string  $scope
     * @param object  $pager
     * @access protected
     * @return array|false
     */
    protected function getObjectsWithPager($metric, $query, $pager = null, $extra = array())
    {
        $code  = $metric->code;
        $scope = $metric->scope;
        $dateType = $metric->dateType;

        if($scope == 'system') return false;

        $scopeObjects = $this->dao->select($scope)->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF(!empty($extra))->andWhere($scope)->in($extra)->fi();

        $scopeObjects = $this->processDAOWithDate($scopeObjects, $query, $dateType)->fetchPairs();

        $objects = null;
        if($scope == 'product')
        {
            $objects = $this->dao->select('id')->from(TABLE_PRODUCT)
                ->where('deleted')->eq(0)
                ->andWhere('shadow')->eq(0)
                ->andWhere('id')->in($scopeObjects);
        }
        elseif($scope == 'project')
        {
            $objects = $this->dao->select('id')->from(TABLE_PROJECT)
                ->where('deleted')->eq(0)
                ->andWhere('type')->eq('project')
                ->andWhere('id')->in($scopeObjects);
        }
        elseif($scope == 'execution')
        {
            $objects = $this->dao->select('id')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('sprint,stage,kanban')
                ->andWhere('id')->in($scopeObjects);
        }
        elseif($scope == 'user')
        {
            $objects = $this->dao->select('account')->from(TABLE_USER)
                ->where('deleted')->eq('0')
                ->andWhere('account')->in($scopeObjects);
        }
        elseif($scope == 'repo')
        {
            $objects = $this->dao->select('id')->from(TABLE_REPO)
                ->where('deleted')->eq('0')
                ->andWhere('id')->in($scopeObjects);
        }

        if(!is_null($objects))
        {
            if(!empty($pager)) $objects = $objects->page($pager);

            return $objects->fetchPairs();
        }
        return array();
    }

    /**
     * Process dao with query date values.
     *
     * @param  object    $stmt
     * @param  object    $query
     * @param  string    $dateType
     * @access protected
     * @return object
     */
    protected function processDAOWithDate($stmt, $query, $dateType)
    {
        $dateBegin  = $this->processRecordQuery($query, 'dateBegin', 'date');
        $dateEnd    = $this->processRecordQuery($query, 'dateEnd', 'date');
        $calcDate   = $this->processRecordQuery($query, 'calcDate', 'date');

        list($dateBegin, $dateEnd) = $this->processRecordQuery($query, 'dateLabel', 'date');

        $yearBegin  = empty($dateBegin) ? '' : $dateBegin->year;
        $yearEnd    = empty($dateEnd)   ? '' : $dateEnd->year;
        $monthBegin = empty($dateBegin) ? '' : $dateBegin->month;
        $monthEnd   = empty($dateEnd)   ? '' : $dateEnd->month;
        $weekBegin  = empty($dateBegin) ? '' : $dateBegin->week;
        $weekEnd    = empty($dateEnd)   ? '' : $dateEnd->week;
        $dayBegin   = empty($dateBegin) ? '' : $dateBegin->day;
        $dayEnd     = empty($dateEnd)   ? '' : $dateEnd->day;

        $stmt = $stmt->beginIF(!empty($dateBegin) and $dateType == 'year')->andWhere('`year`')->ge($yearBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'year')->andWhere('`year`')->le($yearEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->ge($monthBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->le($monthEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->ge($weekBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->le($weekEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->ge($dayBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->le($dayEnd)->fi()
            ->beginIF(!empty($calcDate))->andWhere('date')->ge($calcDate)->fi();

        return $stmt;
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
    protected function fetchMetricRecords($code, $fieldList, $query = array(), $pager = null)
    {
        $metric   = $this->getByCode($code);
        $scopeKey = $metric->scope;
        $dateType = $metric->dateType;

        $query['dateType'] = $dateType;

        $scopeValue = $this->processRecordQuery($query, 'scope');

        $objectList = $this->getObjectsWithPager($metric, $query, $pager, $scopeValue);

        $fieldList = array_merge($fieldList, array('id', 'value', 'date', 'calcType', 'calculatedBy'));
        $wrapFields = array_map(function ($value) {
            return "`$value`";
        }, $fieldList);
        $dataFieldStr = implode(',', $wrapFields);

        $stmt = $this->dao->select($dataFieldStr)
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF($scopeKey != 'system' && !empty($objectList))->andWhere($scopeKey)->in($objectList)->fi()
            ->beginIF(!empty($scopeValue))->andWhere($scopeKey)->in($scopeValue)->fi();

        $stmt = $this->processDAOWithDate($stmt, $query, $dateType)
            ->beginIF($scopeKey != 'system')->orderBy("date desc, $scopeKey, year desc, month desc, week desc, day desc")->fi()
            ->beginIF($scopeKey == 'system')->orderBy("date desc, year desc, month desc, week desc, day desc")->fi();

        if($scopeKey == 'system') $stmt = $stmt->page($pager); // beginIF not work with page()
        return $stmt->fetchAll();
    }

    protected function fetchMetricRecordsWithOption($code, $fieldList, $options = array(), $pager = null)
    {
        $metric = $this->getByCode($code);

        $scopeKey = $metric->scope;
        $dateType = $metric->dateType;

        $fieldList = array_merge($fieldList, array('id', 'value', 'date', 'calcType', 'calculatedBy'));
        $wrapFields = array_map(function ($value) {
            return "`$value`";
        }, $fieldList);
        $dataFieldStr = implode(',', $wrapFields);

        $stmt = $this->dao->select($dataFieldStr)->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code);

        if(!empty($options))
        {
            foreach($options as $key => $option)
            {
                $stmt = $stmt->andWhere($key)->in($option);
            }
        }

        if($dateType == 'nodate')
        {
            $stmt->andWhere('date')->ge(helper::today());
        }

        $stmt = $stmt->orderBy("date desc");

        if($scopeKey == 'system') $stmt = $stmt->page($pager); // beginIF not work with page()
        return $stmt->fetchAll();
    }

    /**
     * 请求最新的度量数据。
     * Fetch latest metric data.
     *
     * @param  string      $code
     * @param  array       $fieldList
     * @param  array       $query
     * @param  object|null $pager
     * @access protected
     * @return array
     */
    protected function fetchLatestMetricRecords($code, $fieldList, $query = array(), $pager = null)
    {
        $metric       = $this->getByCode($code);
        $dateType     = $metric->dateType;
        $lastCalcDate = substr($metric->lastCalcTime, 0, 10);
        $objectList   = $this->getObjectsWithPager($metric, $query);

        $query['dateType'] = $dateType;

        $scopeValue = $this->processRecordQuery($query, 'scope');
        $scopeKey   = $metric->scope;

        $fieldList = array_merge($fieldList, array('id', 'value', 'date'));
        $wrapFields = array_map(function ($value) {
            return "`$value`";
        }, $fieldList);
        $dataFieldStr = implode(',', $wrapFields);

        $stmt = $this->dao->select($dataFieldStr)
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF($scopeKey != 'system')->andWhere($scopeKey)->in($objectList)->fi()
            ->beginIF(!empty($scopeValue))->andWhere($scopeKey)->in($scopeValue)->fi();

        $stmt = $this->processDAOWithDate($stmt, $query, $dateType)
            ->beginIF(!empty($scopeList))->orderBy("date desc, $scopeKey, year desc, month desc, week desc, day desc")->fi()
            ->beginIF(empty($scopeList))->orderBy("date desc, year desc, month desc, week desc, day desc")->fi();

        $stmt = $stmt->page($pager);
        return $stmt->fetchAll();
    }

    /**
     * 根据日期获取度量数据。
     * Fetch metric record by date.
     *
     * @param  string $code
     * @param  string    $date
     * @param  int    $limit
     * @access protected
     * @return array
     */
    protected function fetchMetricRecordByDate($code = 'all', $date = '', $limit = 100)
    {
        $nextDate = empty($date) ? '' : date('Y-m-d', strtotime($date) + 86400);
        $records = $this->dao->select('id')->from(TABLE_METRICLIB)
            ->where('1 = 1')
            ->beginIF($code != 'all')->andWhere('metricCode')->eq($code)->fi()
            ->beginIF(!empty($date))
            ->andWhere('date')->ge($date)
            ->andWhere('date')->lt($nextDate)
            ->fi()
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll();

        return $records;
    }

    /**
     * Set deleted.
     *
     * @param  string    $code
     * @param  string    $value
     * @access protected
     * @return void
     */
    protected function setDeleted($code, $value)
    {
        $this->dao->update(TABLE_METRICLIB)
            ->set('deleted')->eq($value)
            ->where('metricCode')->eq($code)
            ->exec();
    }

    /**
     * Keep latest records.
     *
     * @param  int    $code
     * @param  int    $fields
     * @access protected
     * @return void
     */
    protected function keepLatestRecords($code, $fields)
    {
        if(empty($fields)) return;
        /**
         * 判断fields中的字段是否与array('year', 'month', 'week', 'day')存在交集
         */
        $intersect = array_intersect($fields, array('year', 'month', 'week', 'day'));
        foreach($fields as $key => $field) $fields[$key] = "`$field`";
        if(empty($intersect)) $fields[] = 'left(date, 10)';
        $table = TABLE_METRICLIB;

        $sql  = " UPDATE $table SET deleted = '0' WHERE id IN (";
        $sql .= "    SELECT MAX(id) AS maxid";
        $sql .= "    FROM $table";
        $sql .= "    WHERE metricCode = '$code'";
        $sql .= "    GROUP BY ". implode(',', $fields);
        $sql .= " )";

        $this->dao->exec($sql);
    }

    /**
     * Execute delete.
     *
     * @param  string     $code
     * @access protected
     * @return void
     */
    protected function executeDelete($code)
    {
        $this->dao->delete()->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->andWhere('deleted', true)->eq('1')
            ->orWhere('value')->eq(0)->markRight(1)
            ->exec();
    }

    /**
     * 重建id列顺序。
     * Rebuild id column order.
     *
     * @access protected
     * @return void
     */
    protected function rebuildIdColumn()
    {
        if($this->config->db->driver != 'mysql') return;

        $table = TABLE_METRICLIB;
        $tableRowCount = $this->dao->select('COUNT(id) as rowcount')->from(TABLE_METRICLIB)->fetch('rowcount');
        $autoIncrement = $tableRowCount + 1;
        $this->dao->exec("SET @count = 0;UPDATE $table SET `id` = @count:= @count + 1;");
        $this->dao->exec("ALTER TABLE $table AUTO_INCREMENT = $autoIncrement");
    }
}
