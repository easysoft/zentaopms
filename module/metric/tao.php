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
    protected function fetchMetricByCode(string $code): object|false
    {
        return $this->dao->select('*')->from(TABLE_METRIC)
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
            ->beginIF($this->config->edition == 'biz')->andWhere('object')->notIN('issue,risk')
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
    protected function getObjectsWithPager($code, $scope, $pager = null, $extra = array())
    {
        if($scope == 'system') return false;

        $scopeObjects = $this->dao->select($scope)->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF(!empty($extra))->andWhere($scope)->in($extra)->fi()
            ->fetchPairs();
        $objects = array();
        if($scope == 'product')
        {
            $objects = $this->dao->select('id')->from(TABLE_PRODUCT)
                ->where('deleted')->eq(0)
                ->andWhere('shadow')->eq(0)
                ->andWhere('id')->in($scopeObjects)
                ->beginIF(!empty($pager))->page($pager)->fi()
                ->fetchPairs();
        }
        elseif($scope == 'project')
        {
            $objects = $this->dao->select('id')->from(TABLE_PROJECT)
                ->where('deleted')->eq(0)
                ->andWhere('type')->eq('project')
                ->andWhere('id')->in($scopeObjects)
                ->beginIF(!empty($pager))->page($pager)->fi()
                ->fetchPairs();
        }
        elseif($scope == 'execution')
        {
            $objects = $this->dao->select('id')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('sprint,stage,kanban')
                ->andWhere('id')->in($scopeObjects)
                ->beginIF(!empty($pager))->page($pager)->fi()
                ->fetchPairs();
        }
        elseif($scope == 'user')
        {
            $objects = $this->dao->select('account')->from(TABLE_USER)
                ->where('deleted')->eq('0')
                ->andWhere('account')->in($scopeObjects)
                ->beginIF(!empty($pager))->page($pager)->fi()
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
        $dateList  = array_intersect($fieldList, $this->config->metric->dateList);

        $dateType = $this->getDateType($dateList);
        $query['dateType'] = $dateType;

        $scopeValue = $this->processRecordQuery($query, 'scope');
        $dateBegin  = $this->processRecordQuery($query, 'dateBegin', 'date');
        $dateEnd    = $this->processRecordQuery($query, 'dateEnd', 'date');
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

        $metric      = $this->fetchMetricByID($code, 'scope');
        $scopeKey    = $metric->scope;
        $objectList  = $this->getObjectsWithPager($code, $scopeKey, $pager, $scopeValue);

        $fieldList = array_merge($fieldList, array('id', 'value', 'date'));
        $wrapFields = array_map(fn($value) => "`$value`", $fieldList);
        $dataFieldStr = implode(',', $wrapFields);

        $stmt = $this->dao->select($dataFieldStr)
            ->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->beginIF($scopeKey != 'system')->andWhere($scopeKey)->in($objectList)->fi()
            ->beginIF(!empty($scopeValue))->andWhere($scopeKey)->in($scopeValue)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'year')->andWhere('`year`')->ge($yearBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'year')->andWhere('`year`')->le($yearEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->ge($monthBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'month')->andWhere('CONCAT(`year`, `month`)')->le($monthEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->ge($weekBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'week')->andWhere('CONCAT(`year`, `week`)')->le($weekEnd)->fi()
            ->beginIF(!empty($dateBegin) and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->ge($dayBegin)->fi()
            ->beginIF(!empty($dateEnd)   and $dateType == 'day')->andWhere('CONCAT(`year`, `month`, `day`)')->le($dayEnd)->fi()
            ->beginIF(!empty($calcDate))->andWhere('date')->ge($calcDate)->fi()
            ->beginIF($scopeKey != 'system')->orderBy("date desc, $scopeKey, year desc, month desc, week desc, day desc")->fi()
            ->beginIF($scopeKey == 'system')->orderBy("date desc, year desc, month desc, week desc, day desc")->fi();

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
    protected function fetchLatestMetricRecords(string $code, array $fieldList, array $query = array(), object|null $pager = null): array
    {
        $metric       = $this->fetchMetricByID($code);
        $metricScope  = $metric->scope;
        $lastCalcDate = substr($metric->lastCalcTime, 0, 10);
        $objectList   = $this->getObjectsWithPager($code, $metricScope);

        $scopeList = array_intersect($fieldList, $this->config->metric->scopeList);
        $dateList  = array_intersect($fieldList, $this->config->metric->dateList);

        $dateType = $this->getDateType($dateList);
        $query['dateType'] = $dateType;

        $scope     = $this->processRecordQuery($query, 'scope');
        $dateBegin = $this->processRecordQuery($query, 'dateBegin', 'date');
        $dateEnd   = $this->processRecordQuery($query, 'dateEnd', 'date');
        list($dateBegin, $dateEnd) = $this->processRecordQuery($query, 'dateLabel', 'date');

        $calcDate  = $dateType == 'nodate' ? $lastCalcDate : null;

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

        $stmt = $this->dao->select($dataFieldStr)
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

        if(!$record) return array();

        $fields = array();
        foreach(array_keys((array)$record) as $field)
        {
            if(in_array($field, array('id', 'metricID', 'metricCode', 'value', 'date', 'calcType'))) continue;
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
        $intersect = array_intersect($fields, array('year', 'month', 'week', 'day'));
        foreach($fields as $key => $field) $fields[$key] = "`$field`";
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

    /**
     * 创建sqlite备份数据库。
     * Create backup database.
     *
     * @access protected
     * @return void
     */
    protected function createBackupDatabase()
    {
        $database = $this->config->db->name . $this->config->metric->sqliteSuffix;
        $this->dao->exec("DROP DATABASE IF EXISTS `{$database}`");
        $this->dao->exec("CREATE DATABASE `$database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    /**
     * 同步数据库结构。
     * Sync database schema.
     *
     * @access protected
     * @return void
     */
    protected function syncDatabaseSchema()
    {
        $fromDB = $this->config->db->name;
        $toDB   = $this->config->db->name . $this->config->metric->sqliteSuffix;

        $tableSql = "SELECT table_name as tableName FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = '{$fromDB}' order by table_name;";
        $tables = $this->dao->query($tableSql)->fetchAll();

        $createSqls = array();
        foreach($tables as $table)
        {
            $tableName = $table->tableName;
            $createSql = $this->dao->query("SHOW CREATE TABLE `$fromDB`.`$tableName`")->fetchAll();
            $createSql = $createSql[0]->{'Create Table'};

            $createSql = str_replace("CREATE TABLE `$tableName`", "CREATE TABLE `$toDB`.`$tableName`", $createSql);

            $this->dao->exec("DROP TABLE IF EXISTS `$toDB`.`$tableName`;");
            $this->dao->exec($createSql);
        }
    }

    /**
     * 获取数据库表。
     * Get database tables.
     *
     * @param  int    $db
     * @param  string $orderBy
     * @access protected
     * @return void
     */
    protected function getDatabaseTables($db, $orderBy = 'table_name')
    {
        $tablePrefix = $this->config->db->prefix;
        $sql = "SELECT table_name as tableName, TABLE_ROWS AS rowCount FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = '$db' and table_name like '$tablePrefix%' order by $orderBy";

        $tables = $this->dao->query($sql)->fetchAll();

        return array_map(function($table){return $table->tableName;}, $tables);
    }

    /**
     * 获取数据库表字段。
     * Get database table fields.
     *
     * @param  int    $db
     * @param  int    $tableName
     * @access protected
     * @return void
     */
    protected function getTableFields($db, $tableName)
    {
        $fields = $this->dao->query("desc `$db`.`$tableName`", array("Field"))->fetchAll();
        $fields = array_map(function($field){return $field->Field;}, $fields);

        return $fields;
    }

    /**
     * 同步数据。
     * Sync data.
     *
     * @access protected
     * @return void
     */
    protected function syncData()
    {
        $fromDB         = $this->config->db->name;
        $db             = $this->config->db->name . $this->config->metric->sqliteSuffix;
        $tablePrefix    = $this->config->db->prefix;
        $keepTableNames = $this->config->metric->keepTables;
        $ignoreFields   = $this->config->metric->ignoreFields;

        $existTables = $this->getDatabaseTables($db);

        $dropSqls = array();

        foreach ($existTables as $existTable)
        {
            $tableName = $existTable;
            $tableNameNoPrefix = str_replace($tablePrefix, '', $tableName);
            if (in_array($tableNameNoPrefix, $keepTableNames))
            {
                $fields = zget($ignoreFields, $tableNameNoPrefix, array());
                if (empty($fields)) continue;
                // Drop table fields.
                foreach ($fields as $field)
                {
                    $this->dao->exec("ALTER TABLE `$db`.`$tableName` DROP COLUMN `$field`;");
                }
            }
            else
            {
                $this->dao->exec("DROP TABLE IF EXISTS `$db`.`$tableName`;");
            }
        }

        $existTables = $this->getDatabaseTables($db);

        foreach($existTables as $existTable)
        {
            $fields = $this->getTableFields($db, $existTable);
            $fields = implode("`,`", $fields);
            $this->dao->exec("INSERT INTO `$db`.`$existTable` (`$fields`) SELECT `$fields` FROM `$fromDB`.`$existTable`;");
        }
    }

    /**
     * 获取mysql表行数。
     * Get mysql table rows.
     *
     * @param  int    $db
     * @param  int    $tableName
     * @access protected
     * @return void
     */
    protected function mysqlRows($db, $tableName = null)
    {
        if(empty($tableName))
        {
            $sql = "SELECT SUM(TABLE_ROWS) AS total_count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db';";
            $count = $this->dao->query($sql)->fetchAll();
            $count = $count[0]->total_count;
            return $count;
        }
        $sql = "SELECT count(1) as count FROM `$db`.`$tableName`";
        $count = $this->dao->query($sql)->fetchAll();
        $count = $count[0]->count;
        return $count;
    }

    /**
     * 切割表。
     * Slice tables.
     *
     * @param  float  $sliceRows
     * @access protected
     * @return void
     */
    protected function sliceTables($sliceRows = 500000)
    {
        $db          = $this->config->db->name . $this->config->metric->sqliteSuffix;
        $tablePrefix = $this->config->db->prefix;
        $tables      = $this->getDatabaseTables($db, 'table_rows desc');

        $allTables   = array();
        $currentRows = 0;
        $sliceTables = array();
        foreach($tables as $tableName)
        {
            $count     = $this->mysqlRows($db, $tableName);
            $currentRows += $count;
            $sliceTables[$tableName] = $count;
            if($currentRows > $sliceRows)
            {
                $allTables[] = $sliceTables;
                $sliceTables = array();
                $currentRows = 0;
            }
        }

        if(!empty($sliceTables)) $allTables[] = $sliceTables;

        return $allTables;
    }

    /**
     * 获取sqlite临时目录。
     * Get sqlite temp root.
     *
     * @access protected
     * @return void
     */
    protected function getSqliteTmpRoot()
    {
        $tmpRoot = $this->app->getTmpRoot();
        $root    = $tmpRoot . 'sqlite' . DS;
        if(!is_dir($root)) mkdir($root, 0777, true);
        return $root;
    }

    /**
     * 同步数据到sqlite。
     * Sync data to sqlite.
     *
     * @param  int    $sliceTables
     * @access protected
     * @return void
     */
    protected function syncData2Sqlite($sliceTables)
    {
        $host = $this->config->db->host;
        $port = $this->config->db->port;
        $db   = $this->config->db->name . $this->config->metric->sqliteSuffix;
        $user = $this->config->db->user;
        $pwd  = $this->config->db->password;

        $tmpRoot = $this->getSqliteTmpRoot();
        $zentaoSql = $tmpRoot . 'zentao_%s.sql';
        $sqliteDB  = $tmpRoot . 'sqlite.db';

        // Delete sqlite db file if exist.
        if(file_exists($sqliteDB)) unlink($sqliteDB);

        $mysqldumpCommand = $this->config->metricDB->command->mysqldump;
        $sqlite3Command   = $this->config->metricDB->command->sqlite3;
        $mysql2sqlite     = $this->app->getModuleRoot() . 'metric' . DS . 'sqlite' . DS . 'mysql2sqlite';

        foreach($sliceTables as $index => $sliceTable)
        {
            $tables = array_keys($sliceTable);
            $tables = implode(" ", $tables);

            // 导出从库的数据到sql文件
            $sqlFile = sprintf($zentaoSql, str_pad((string)($index + 1), 3, "0", STR_PAD_LEFT));
            $command = "{$mysqldumpCommand} -h{$host} -P{$port} -u{$user} -p{$pwd} {$db} {$tables} > {$sqlFile} --skip-comments";
            exec($command, $output);

            // 将导出的sql文件转为sqlite3类型的sql文件
            $sqliteFile = sprintf($zentaoSql, '_sqlite' . str_pad((string)($index + 1), 3, "0", STR_PAD_LEFT));
            $command = "$mysql2sqlite {$sqlFile} > {$sqliteFile}";
            exec($command, $output);

            // 将导出的sqlite3类型的sql文件导入sqlite3数据库
            $command = "$sqlite3Command {$sqliteDB} < {$sqliteFile}";
            exec($command, $output);

            // 删除导出的sql文件
            if(file_exists($sqlFile)) unlink($sqlFile);
            // 删除导出的sqlite3类型的sql文件
            if(file_exists($sqliteFile)) unlink($sqliteFile);
        }
        $this->dao->exec("DROP DATABASE IF EXISTS `{$db}`");
    }
}
