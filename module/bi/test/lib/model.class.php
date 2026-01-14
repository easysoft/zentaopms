<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class biModelTest extends baseTest
{
    protected $moduleName = 'bi';
    protected $className  = 'model';

    /**
     * Parse sql test
     *
     * @param  string    $sql
     * @access public
     * @return array
     */
    public function parseSqlTest($sql)
    {
        $columns = $this->instance->parseSql($sql);

        $result = array();
        foreach($columns as $field => $column)
        {
            if(empty($column['table'])) continue;

            $result[$field] = "{$column['table']['originTable']}=>{$column['table']['column']}";
        }

        arsort($result);

        return $result;
    }

    /**
     * get expression test.
     *
     * @param  string    $table
     * @param  string    $column
     * @param  string    $alias
     * @param  string    $function
     * @access public
     * @return string
     */
    public function getExpressionTest($table = null, $column = null, $alias = null, $function = null)
    {
        $expression = $this->instance->getExpression($table, $column, $alias, $function);
        return trim($expression->build($expression));
    }

    /**
     * Test buildQueryResultTableColumns method.
     *
     * @param  array $fieldSettings
     * @access public
     * @return array
     */
    public function buildQueryResultTableColumnsTest($fieldSettings)
    {
        $result = $this->instance->buildQueryResultTableColumns($fieldSettings);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * get condition test.
     *
     * @param  mixed  $tableA
     * @param  mixed  $columnA
     * @param  string $operator
     * @param  mixed  $tableB
     * @param  mixed  $columnB
     * @param  int    $group
     * @access public
     * @return string
     */
    public function getConditionTest(mixed $tableA = null, mixed $columnA = null, string $operator = '', mixed $tableB = null, mixed $columnB = null, int $group = 1, bool $quote = true): string
    {
        $condition = $this->instance->getCondition($tableA, $columnA, $operator, $tableB, $columnB, $group, $quote);
        return $condition->build($condition);
    }

    /**
     * build SQL test.
     *
     * @param  array $args
     * @access public
     * @return string
     */
    public function buildSQLTest(array $args): string
    {
        $selects   = zget($args, 'selects', array());
        $from      = zget($args, 'from', array());
        $joins     = zget($args, 'joins', array());
        $functions = zget($args, 'functions', array());
        $wheres    = zget($args, 'wheres', array());
        $querys    = zget($args, 'querys', array());
        $groups    = zget($args, 'groups', array());
        $statement = $this->instance->buildSQL($selects, $from, $joins, $functions, $wheres, $querys, $groups);
        return str_replace(PHP_EOL, '', $statement->build());
    }

    /**
     * get columns native type
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getColumns($sql)
    {
        $columns = $this->instance->getColumns($sql, 'mysql');

        $nativeTypes = array();
        foreach($columns as $field => $fieldInfo)
        {
            $nativeTypes[$field] = $fieldInfo['native_type'];
        }

        return $nativeTypes;
    }

    /**
     * Test getColumns method.
     *
     * @param  string $sql
     * @param  string $driver
     * @param  bool   $returnOrigin
     * @access public
     * @return mixed
     */
    public function getColumnsTest($sql, $driver = 'mysql', $returnOrigin = false)
    {
        if(empty($sql)) return 0;

        // 尝试实际调用（如果测试环境允许）
        $result = $this->instance->getColumns($sql, $driver, $returnOrigin);
        if(dao::isError()) return dao::getError();

        // 处理无效驱动的情况
        if($result === false) return 0;

        return $result;
    }

    /**
     * get tables and fields
     *
     * @param  string $sql
     * @access public
     * @return array|false
     */
    public function getTableAndFields($sql)
    {
        $result = $this->instance->getTableAndFields($sql);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTables method.
     *
     * @param  string $sql
     * @param  bool   $deep
     * @access public
     * @return array
     */
    public function getTablesTest($sql, $deep = false)
    {
        $statement = $this->instance->parseToStatement($sql);
        if(dao::isError()) return dao::getError();

        if(!$statement) return array();

        $result = $this->instance->getTables($statement, $deep);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test process vars.
     *
     * @param  string $sql
     * @param  array  $filters
     * @param  bool   $emptyValue
     * @access public
     * @return string
     */
    public function processVarsTest($sql, $filters, $emptyValue)
    {
        return $this->instance->processVars($sql, $filters, $emptyValue);
    }

    /**
     * Test prepareBuiltinPivot.
     *
     * @param  string $operate
     * @access public
     * @return string
     */
    public function prepareBuiltinPivotSQLTest($operate)
    {
        return $this->instance->prepareBuiltinPivotSQL($operate);
    }

    /**
     * Test prepareBuiltinChartSQL method.
     *
     * @param  string $operate
     * @access public
     * @return array
     */
    public function prepareBuiltinChartSQLTest($operate)
    {
        $result = $this->instance->prepareBuiltinChartSQL($operate);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getViewableObject method.
     *
     * @param  string $objectType
     * @access public
     * @return mixed
     */
    public function getViewableObjectTest($objectType)
    {
        $result = $this->instance->getViewableObject($objectType);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test parseToStatement method.
     *
     * @param  string $sql
     * @access public
     * @return mixed
     */
    public function parseToStatementTest($sql)
    {
        $result = $this->instance->parseToStatement($sql);
        if(dao::isError()) return dao::getError();
        return $result;
    }


    /**
     * Test getFields method.
     *
     * @param  object $statement
     * @access public
     * @return mixed
     */
    public function getFieldsTest($statement)
    {
        $result = $this->instance->getFields($statement);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test parseTableList method.
     *
     * @param  string $sql
     * @access public
     * @return mixed
     */
    public function parseTableListTest($sql)
    {
        $result = $this->instance->parseTableList($sql);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFieldsWithTable method.
     *
     * @param  string $sql
     * @access public
     * @return mixed
     */
    public function getFieldsWithTableTest($sql)
    {
        $result = $this->instance->getFieldsWithTable($sql);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFieldsWithAlias method.
     *
     * @param  string $sql
     * @access public
     * @return mixed
     */
    public function getFieldsWithAliasTest($sql)
    {
        $result = $this->instance->getFieldsWithAlias($sql);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTableByAlias method.
     *
     * @param  mixed $statement
     * @param  string $alias
     * @access public
     * @return mixed
     */
    public function getTableByAliasTest($statement, $alias)
    {
        $result = $this->instance->getTableByAlias($statement, $alias);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test explainSQL method.
     *
     * @param  string $sql
     * @param  string $driver
     * @access public
     * @return mixed
     */
    public function explainSQLTest($sql, $driver = 'mysql')
    {
        $result = $this->instance->explainSQL($sql, $driver);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getColumnsType method.
     *
     * @param  string $sql
     * @param  string $driverName
     * @param  array  $columns
     * @access public
     * @return mixed
     */
    public function getColumnsTypeTest($sql, $driverName = 'mysql', $columns = array())
    {
        $result = $this->instance->getColumnsType($sql, $driverName, $columns);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getScopeOptions method.
     *
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function getScopeOptionsTest($type)
    {
        $result = $this->instance->getScopeOptions($type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDataviewOptions method.
     *
     * @param  string $object
     * @param  string $field
     * @access public
     * @return mixed
     */
    public function getDataviewOptionsTest($object, $field)
    {
        $result = $this->instance->getDataviewOptions($object, $field);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getObjectOptions method.
     *
     * @param  string $object
     * @param  string $field
     * @access public
     * @return mixed
     */
    public function getObjectOptionsTest($object, $field)
    {
        $result = $this->instance->getObjectOptions($object, $field);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOptionsFromSql method.
     *
     * @param  string $sql
     * @param  string $driver
     * @param  string $keyField
     * @param  string $valueField
     * @access public
     * @return mixed
     */
    public function getOptionsFromSqlTest($sql, $driver, $keyField, $valueField)
    {
        $result = $this->instance->getOptionsFromSql($sql, $driver, $keyField, $valueField);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test genWaterpolo method.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return mixed
     */
    public function genWaterpoloTest($fields, $settings, $sql, $filters)
    {
        $result = $this->instance->genWaterpolo($fields, $settings, $sql, $filters);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getMultiData method.
     *
     * @param  array  $settings
     * @param  string $defaultSql
     * @param  array  $filters
     * @param  string $driver
     * @param  bool   $sort
     * @access public
     * @return mixed
     */
    public function getMultiDataTest($settings, $defaultSql, $filters, $driver, $sort = false)
    {
        $result = $this->instance->getMultiData($settings, $defaultSql, $filters, $driver, $sort);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTableFields method.
     *
     * @access public
     * @return mixed
     */
    public function getTableFieldsTest()
    {
        $result = $this->instance->getTableFields();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTableFieldsMenu method.
     *
     * @access public
     * @return mixed
     */
    public function getTableFieldsMenuTest()
    {
        $result = $this->instance->getTableFieldsMenu();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test preparePivotObject method.
     *
     * @param  mixed $pivot
     * @access public
     * @return mixed
     */
    public function preparePivotObjectTest($pivot)
    {
        $result = $this->instance->preparePivotObject($pivot);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareBuilitinPivotDrillSQL method.
     *
     * @param  int   $pivotID
     * @param  array $drills
     * @param  int   $version
     * @access public
     * @return array
     */
    public function prepareBuilitinPivotDrillSQLTest($pivotID, $drills, $version)
    {
        $result = $this->instance->prepareBuilitinPivotDrillSQL($pivotID, $drills, $version);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareBuiltinMetricSQL method.
     *
     * @param  string $operate
     * @access public
     * @return array
     */
    public function prepareBuiltinMetricSQLTest($operate = 'insert')
    {
        $result = $this->instance->prepareBuiltinMetricSQL($operate);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareBuiltinScreenSQL method.
     *
     * @param  string $operate
     * @access public
     * @return mixed
     */
    public function prepareBuiltinScreenSQLTest($operate = 'insert')
    {
        $result = $this->instance->prepareBuiltinScreenSQL($operate);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDuckDBPath method.
     *
     * @access public
     * @return mixed
     */
    public function getDuckDBPathTest()
    {
        $result = $this->instance->getDuckDBPath();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkDuckDBFile method.
     *
     * @param  string $path
     * @param  array  $bin
     * @access public
     * @return mixed
     */
    public function checkDuckDBFileTest($path, $bin)
    {
        $result = $this->instance->checkDuckDBFile($path, $bin);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDuckdbBinConfig method.
     *
     * @access public
     * @return mixed
     */
    public function getDuckdbBinConfigTest()
    {
        $result = $this->instance->getDuckdbBinConfig();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDuckDBTmpDir method.
     *
     * @param  bool $static
     * @access public
     * @return mixed
     */
    public function getDuckDBTmpDirTest($static = false)
    {
        $result = $this->instance->getDuckDBTmpDir($static);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getSqlByMonth method.
     *
     * @param  string $year
     * @param  string $month
     * @access public
     * @return mixed
     */
    public function getSqlByMonthTest($year = 'Y', $month = 'm')
    {
        $result = $this->instance->getSqlByMonth($year, $month);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getActionSyncSql method.
     *
     * @param  string $range
     * @access public
     * @return mixed
     */
    public function getActionSyncSqlTest($range = 'current')
    {
        $result = $this->instance->getActionSyncSql($range);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initParquet method.
     *
     * @access public
     * @return mixed
     */
    public function initParquetTest()
    {
        $result = $this->instance->initParquet();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareCopySQL method.
     *
     * @param  string $duckdbTmpPath
     * @access public
     * @return mixed
     */
    public function prepareCopySQLTest($duckdbTmpPath)
    {
        $result = $this->instance->prepareCopySQL($duckdbTmpPath);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareSyncCommand method.
     *
     * @param  string $binPath
     * @param  string $extensionPath
     * @param  string $copySQL
     * @access public
     * @return mixed
     */
    public function prepareSyncCommandTest($binPath, $extensionPath, $copySQL)
    {
        $result = $this->instance->prepareSyncCommand($binPath, $extensionPath, $copySQL);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test generateParquetFile method.
     *
     * @access public
     * @return mixed
     */
    public function generateParquetFileTest()
    {
        $result = $this->instance->generateParquetFile();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getLogFile method.
     *
     * @access public
     * @return string
     */
    public function getLogFileTest()
    {
        $result = $this->instance->getLogFile();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test parseSqlVars method.
     *
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return string
     */
    public function parseSqlVarsTest($sql, $filters)
    {
        $result = $this->instance->parseSqlVars($sql, $filters);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test sql2Statement method.
     *
     * @param  string $sql
     * @param  string $mode
     * @access public
     * @return mixed
     */
    public function sql2StatementTest($sql, $mode = 'text')
    {
        $result = $this->instance->sql2Statement($sql, $mode);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test validateSql method.
     *
     * @param  string $sql
     * @param  string $driver
     * @access public
     * @return mixed
     */
    public function validateSqlTest($sql, $driver = 'mysql')
    {
        $result = $this->instance->validateSql($sql, $driver);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareSqlPager method.
     *
     * @param  object $statement
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $driver
     * @access public
     * @return string
     */
    public function prepareSqlPagerTest($statement, $recPerPage, $pageID, $driver = 'mysql')
    {
        $result = $this->instance->prepareSqlPager($statement, $recPerPage, $pageID, $driver);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareColumns method.
     *
     * @param  string $sql
     * @param  object $statement
     * @param  string $driver
     * @access public
     * @return mixed
     */
    public function prepareColumnsTest($sql, $statement, $driver = 'mysql')
    {
        $result = $this->instance->prepareColumns($sql, $statement, $driver);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getSqlTypeAndFields method.
     *
     * @param  string $sql
     * @param  string $driver
     * @access public
     * @return mixed
     */
    public function getSqlTypeAndFieldsTest($sql, $driver = 'mysql')
    {
        $result = $this->instance->getSqlTypeAndFields($sql, $driver);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getParams4Rebuild method.
     *
     * @param  string $sql
     * @param  object $statement
     * @param  array  $columnFields
     * @access public
     * @return mixed
     */
    public function getParams4RebuildTest($sql, $statement, $columnFields)
    {
        $result = $this->instance->getParams4Rebuild($sql, $statement, $columnFields);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getSQL method.
     *
     * @param  string $sql
     * @param  string $driver
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return mixed
     */
    public function getSQLTest($sql, $driver = 'mysql', $recPerPage = 10, $pageID = 1)
    {
        $result = $this->instance->getSQL($sql, $driver, $recPerPage, $pageID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test querySQL method.
     *
     * @param  string $sql
     * @param  string $limitSql
     * @param  string $driver
     * @access public
     * @return mixed
     */
    public function querySQLTest($sql, $limitSql, $driver = 'mysql')
    {
        $result = $this->instance->querySQL($sql, $limitSql, $driver);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test query method.
     *
     * @param  mixed  $sqlOrStateObj
     * @param  string $driver
     * @param  bool   $useFilter
     * @access public
     * @return mixed
     */
    public function queryTest($sqlOrStateObj, $driver = 'mysql', $useFilter = true)
    {
        $result = $this->instance->query($sqlOrStateObj, $driver, $useFilter);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTableList method.
     *
     * @param  bool $hasDataview
     * @param  bool $withPrefix
     * @access public
     * @return mixed
     */
    public function getTableListTest($hasDataview = true, $withPrefix = true)
    {
        $result = $this->instance->getTableList($hasDataview, $withPrefix);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareFieldObjects method.
     *
     * @access public
     * @return mixed
     */
    public function prepareFieldObjectsTest()
    {
        $result = $this->instance->prepareFieldObjects();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareFieldSettingFormData method.
     *
     * @param  mixed $settings
     * @access public
     * @return mixed
     */
    public function prepareFieldSettingFormDataTest($settings)
    {
        $result = $this->instance->prepareFieldSettingFormData($settings);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test rebuildFieldSettings method.
     *
     * @param  array        $fieldPairs
     * @param  object       $columns
     * @param  array        $relatedObject
     * @param  object|array $fieldSettings
     * @param  array        $objectFields
     * @access public
     * @return mixed
     */
    public function rebuildFieldSettingsTest($fieldPairs, $columns, $relatedObject, $fieldSettings, $objectFields)
    {
        $result = $this->instance->rebuildFieldSettings($fieldPairs, $columns, $relatedObject, $fieldSettings, $objectFields);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test convertDataForDtable method.
     *
     * @param  object $data
     * @param  array  $configs
     * @param  string $version
     * @param  string $status
     * @access public
     * @return mixed
     */
    public function convertDataForDtableTest($data, $configs, $version, $status)
    {
        $result = $this->instance->convertDataForDtable($data, $configs, $version, $status);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDrillFields method.
     *
     * @param  int   $rowIndex
     * @param  string $columnKey
     * @param  array $drills
     * @access public
     * @return array
     */
    public function getDrillFieldsTest(int $rowIndex, string $columnKey, array $drills): array
    {
        $result = $this->instance->getDrillFields($rowIndex, $columnKey, $drills);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processDrills method.
     *
     * @param  string $field
     * @param  array  $drillFields
     * @param  array  $columns
     * @access public
     * @return array
     */
    public function processDrillsTest(string $field, array $drillFields, array $columns): array
    {
        $result = $this->instance->processDrills($field, $drillFields, $columns);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareDrillConditions method.
     *
     * @param  array  $drillFields
     * @param  array  $conditions
     * @param  string $originField
     * @access public
     * @return array
     */
    public function prepareDrillConditionsTest(array $drillFields, array $conditions, string $originField): array
    {
        $result = $this->instance->prepareDrillConditions($drillFields, $conditions, $originField);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test json2Array method.
     *
     * @param  string|object|array|null $json
     * @access public
     * @return array
     */
    public function json2ArrayTest($json): array
    {
        $result = $this->instance->json2Array($json);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getCorrectGroup method.
     *
     * @param  string $id
     * @param  string $type
     * @access public
     * @return string
     */
    public function getCorrectGroupTest($id, $type)
    {
        $result = $this->instance->getCorrectGroup($id, $type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test downloadDuckdb method.
     *
     * @access public
     * @return string
     */
    public function downloadDuckdbTest()
    {
        $result = $this->instance->downloadDuckdb();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkDuckdbInstall method.
     *
     * @access public
     * @return mixed
     */
    public function checkDuckdbInstallTest()
    {
        $result = $this->instance->checkDuckdbInstall();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test updateDownloadingTagFile method.
     *
     * @param  string $type
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function updateDownloadingTagFileTest($type = 'file', $action = 'create')
    {
        $result = $this->instance->updateDownloadingTagFile($type, $action);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test unzipFile method.
     *
     * @param  string $path
     * @param  string $file
     * @param  string $extractFile
     * @access public
     * @return bool
     */
    public function unzipFileTest(string $path, string $file, string $extractFile): bool
    {
        $result = $this->instance->unzipFile($path, $file, $extractFile);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test downloadFile method.
     *
     * @param  string $url
     * @param  string $savePath
     * @param  string $finalFile
     * @access public
     * @return mixed
     */
    public function downloadFileTest(string $url, string $savePath, string $finalFile)
    {
        $result = $this->instance->downloadFile($url, $savePath, $finalFile);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test jsonEncode method.
     *
     * @param  object|array $object
     * @access public
     * @return mixed
     */
    public function jsonEncodeTest($object)
    {
        $result = $this->invokeArgs('jsonEncode', [$object]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
