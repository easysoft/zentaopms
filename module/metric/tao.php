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

    /**
     * 根据范围获取度量项。
     * Fetch metric by scope.
     *
     * @param  string $scope
     * @param  int    $limit
     * @access protected
     * @return array
     */
    protected function fetchMetricsByScope($scope, $limit = -1)
    {
        $metrics = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->andWhere('object')->in(array_keys($this->lang->metric->objectList))
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll();

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
        return $metric->scope;
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
     * 获取范围对象类型以构建分页对象。
     * Get object list with page.
     *
     * @param string  $code
     * @param string  $scope
     * @param object  $pager
     * @access protected
     * @return array|false
     */
    protected function getObjectsWithPager($code, $scope, $pager)
    {
        if($scope == 'system') return false;

        $scopeObjects = $this->dao->select($scope)->from(TABLE_METRICLIB)->where('metricCode')->eq($code)->fetchPairs();
        if($scope == 'product')
        {
            $objects = $this->dao->select('id')->from(TABLE_PRODUCT)
                ->where('deleted')->eq(0)
                ->andWhere('shadow')->eq(0)
                ->andWhere('id')->in($scopeObjects)
                ->page($pager)
                ->fetchPairs();
        }
        elseif($scope == 'project')
        {
            $objects = $this->dao->select('id')->from(TABLE_PROJECT)
                ->where('deleted')->eq(0)
                ->andWhere('type')->eq('project')
                ->andWhere('id')->in($scopeObjects)
                ->page($pager)
                ->fetchPairs();
        }
        elseif($scope == 'execution')
        {
            $objects = $this->dao->select('id')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('sprint,stage,kanban')
                ->andWhere('id')->in($scopeObjects)
                ->page($pager)
                ->fetchPairs();
        }
        elseif($scope == 'user')
        {
            $objects = $this->dao->select('account')->from(TABLE_USER)
                ->where('deleted')->eq('0')
                ->andWhere('account')->in($scopeObjects)
                ->page($pager)
                ->fetchPairs();
        }

        return $objects;
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
        $metricScope = $this->fetchMetricByID($code, 'scope');
        $objectList  = $this->getObjectsWithPager($code, $metricScope, $pager);

        $scopeList = array_intersect($fieldList, $this->config->metric->scopeList);
        $dateList  = array_intersect($fieldList, $this->config->metric->dateList);

        $dateType = $this->getDateType($dateList);
        $query['dateType'] = $dateType;

        $scope     = $this->processRecordQuery($query, 'scope');
        $dateBegin = $this->processRecordQuery($query, 'dateBegin', 'date');
        $dateEnd   = $this->processRecordQuery($query, 'dateEnd', 'date');
        list($dateBegin, $dateEnd) = $this->processRecordQuery($query, 'dateLabel', 'date');

        $calcDate  = $this->processRecordQuery($query, 'calcDate', 'date');

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

        $fieldList = array_merge($fieldList, array('id', 'value', 'date'));
        $wrapFields = array_map(fn($value) => "`$value`", $fieldList);
        $dataFieldStr = implode(',', $wrapFields);

        $records =  $this->dao->select($dataFieldStr)
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF($metricScope != 'system')->andWhere($metricScope)->in($objectList)->fi()
            ->beginIF(!empty($scope))->andWhere($scopeKey)->in($scopeValue)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'year')->andWhere('`year`')->ge($yearBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'year')->andWhere('`year`')->le($yearEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->ge($monthBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->le($monthEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->ge($weekBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->le($weekEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->ge($dayBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->le($dayEnd)->fi()
            ->beginIF(!empty($calcDate))->andWhere('date')->ge($calcDate)->fi()
            ->beginIF(!empty($scopeList))->orderBy("date desc, $scopeKey, year desc, month desc, week desc, day desc")->fi()
            ->beginIF(empty($scopeList))->orderBy("date desc, year desc, month desc, week desc, day desc")->fi()
            ->fetchAll();

        return $records;
    }

    /**
     * 获取度量数据有效字段。
     * Get metric record fields.
     *
     * @param  string $code
     * @access protected
     * @return array|false
     */
    protected function getRecordFields(string $code): array|false
    {
        $record = $this->dao->select('*')
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->limit(1)
            ->fetch();

        if(!$record) return false;

        $fields = array();
        foreach(array_keys((array)$record) as $field)
        {
            if(in_array($field, array('id', 'metricID', 'metricCode', 'value', 'date'))) continue;
            if(!empty($record->$field)) $fields[] = $field;
        }

        return $fields;
    }

    /**
     * 创建临时表用于存储最新的非重复度量数据的id。
     * Create temp table for storing distinct metric record id.
     *
     * @access protected
     * @return void
     */
    protected function createDistinctTempTable(): void
    {
        $sql  = "CREATE TABLE IF NOT EXISTS `metriclib_distinct` ( ";
        $sql .= " id INT AUTO_INCREMENT PRIMARY KEY ";
        $sql .= " )";

        $this->dao->exec($sql);
        $this->dao->exec("TRUNCATE TABLE `metriclib_distinct`");
    }

    /**
     * 将度量数据不重复的id插入到临时表中。
     * Insert distinct metric record id to temp table.
     *
     * @param  string $code
     * @param  array $fields
     * @access protected
     * @return void
     */
    protected function insertDistinctId2TempTable(string $code, array $fields): void
    {
        if(empty($fields)) return;
        /**
         * 判断fields中的字段是否与array('year', 'month', 'week', 'day')存在交集
         */
        foreach($fields as $key => $field) $fields[$key] = "`$field`";
        $intersect = array_intersect($fields, array('year', 'month', 'week', 'day'));
        if(empty($intersect)) $fields[] = 'left(date, 10)';

        $sql  = "INSERT INTO `metriclib_distinct` (id) ";
        $sql .= "SELECT MAX(id) AS id ";
        $sql .= "FROM zt_metriclib WHERE metricCode = '{$code}' ";
        $sql .= "GROUP BY " . implode(',', $fields);

        $this->dao->exec($sql);
    }

    /**
     * 删除重复的度量数据。
     * Delete duplication metric record.
     *
     * @param  string $code
     * @access protected
     * @return void
     */
    protected function deleteDuplicationRecord(string $code): void
    {
        $sql  = "DELETE FROM zt_metriclib ";
        $sql .= "WHERE id NOT IN (SELECT id FROM metriclib_distinct) ";
        $sql .= "AND metricCode = '{$code}'";

        $this->dao->exec($sql);
    }

    /**
     * 删除记录不重复度量数据id的临时表。
     * Drop temp table for storing distinct metric record id.
     *
     * @access protected
     * @return void
     */
    protected function dropDistinctTempTable(): void
    {
        $this->dao->exec("DROP TABLE IF EXISTS `metriclib_distinct`");
    }
}
