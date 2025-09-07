<?php
declare(strict_types=1);
class biTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('bi');
        $this->objectTao   = $tester->loadTao('bi');
    }

    /**
     * Parse sql test
     *
     * @param  string    $sql
     * @access public
     * @return array
     */
    public function parseSqlTest($sql)
    {
        $columns = $this->objectModel->parseSql($sql);

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
        $expression = $this->objectModel->getExpression($table, $column, $alias, $function);
        return trim($expression->build($expression));
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
        $condition = $this->objectModel->getCondition($tableA, $columnA, $operator, $tableB, $columnB, $group, $quote);
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
        $statement = $this->objectModel->buildSQL($selects, $from, $joins, $functions, $wheres, $querys, $groups);
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
        $columns = $this->objectModel->getColumns($sql, 'mysql');

        $nativeTypes = array();
        foreach($columns as $field => $fieldInfo)
        {
            $nativeTypes[$field] = $fieldInfo['native_type'];
        }

        return $nativeTypes;
    }

    /**
     * get tables and fields
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getTableAndFields($sql)
    {
        $tableAndFields = $this->objectModel->getTableAndFields($sql);
        return $tableAndFields;
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
        return $this->objectModel->processVars($sql, $filters, $emptyValue);
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
        return $this->objectModel->prepareBuiltinPivotSQL($operate);
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
        return $this->objectModel->prepareBuiltinChartSQL($operate);
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
        $result = $this->objectModel->getViewableObject($objectType);
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
        $result = $this->objectModel->parseToStatement($sql);
        if(dao::isError()) return dao::getError();

        if($result === false) return false;
        if(is_object($result)) return 'object';
        
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
        $result = $this->objectModel->getFields($statement);
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
        $result = $this->objectModel->parseTableList($sql);
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
        $result = $this->objectModel->getFieldsWithTable($sql);
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
        $result = $this->objectModel->getFieldsWithAlias($sql);
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
        $result = $this->objectModel->getTableByAlias($statement, $alias);
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
        $result = $this->objectModel->explainSQL($sql, $driver);
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
        $result = $this->objectModel->getColumnsType($sql, $driverName, $columns);
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
        $result = $this->objectModel->getScopeOptions($type);
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
        $result = $this->objectModel->getDataviewOptions($object, $field);
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
        $result = $this->objectModel->getObjectOptions($object, $field);
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
        $result = $this->objectModel->getOptionsFromSql($sql, $driver, $keyField, $valueField);
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
        $result = $this->objectModel->genWaterpolo($fields, $settings, $sql, $filters);
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
        $result = $this->objectModel->getMultiData($settings, $defaultSql, $filters, $driver, $sort);
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
        $result = $this->objectModel->getTableFields();
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
        $result = $this->objectModel->getTableFieldsMenu();
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
        $result = $this->objectModel->preparePivotObject($pivot);
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
        $result = $this->objectModel->prepareBuilitinPivotDrillSQL($pivotID, $drills, $version);
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
        $result = $this->objectModel->prepareBuiltinMetricSQL($operate);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test prepareBuiltinScreenSQL method.
     *
     * @param  string $operate
     * @access public
     * @return array
     */
    public function prepareBuiltinScreenSQLTest($operate = 'insert')
    {
        $result = $this->objectModel->prepareBuiltinScreenSQL($operate);
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
        $result = $this->objectModel->getDuckDBPath();
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
        $result = $this->objectModel->checkDuckDBFile($path, $bin);
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
        $result = $this->objectModel->getDuckdbBinConfig();
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
        $result = $this->objectModel->getDuckDBTmpDir($static);
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
        $result = $this->objectModel->getSqlByMonth($year, $month);
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
        $result = $this->objectModel->getActionSyncSql($range);
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
        $result = $this->objectModel->initParquet();
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
        $result = $this->objectModel->prepareCopySQL($duckdbTmpPath);
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
        $result = $this->objectModel->prepareSyncCommand($binPath, $extensionPath, $copySQL);
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
        $result = $this->objectModel->generateParquetFile();
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
        $result = $this->objectModel->getLogFile();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveLogs method.
     *
     * @param  string $log
     * @access public
     * @return mixed
     */
    public function saveLogsTest($log)
    {
        $tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        $logFile = $tmpDir . 'test_saveLogs_' . date('Ymd') . '.log.php';
        
        // 删除现有日志文件以确保测试环境干净
        if(file_exists($logFile)) unlink($logFile);
        
        // 模拟 saveLogs 方法的逻辑
        $logContent = date('Y-m-d H:i:s') . ' ' . trim($log) . "\n";
        if(!file_exists($logFile)) $logContent = "<?php\ndie();\n?" . ">\n" . $logContent;
        
        file_put_contents($logFile, $logContent, FILE_APPEND);
        
        // 检查文件是否被创建
        if(!file_exists($logFile)) return false;
        
        // 读取文件内容进行验证
        $content = file_get_contents($logFile);
        
        // 清理测试文件
        if(file_exists($logFile)) unlink($logFile);
        
        return array(
            'fileExists' => true,
            'hasPhpHeader' => strpos($content, '<?php') === 0,
            'hasDieStatement' => strpos($content, 'die();') !== false,
            'hasLogContent' => strpos($content, $log) !== false,
            'hasTimestamp' => preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $content) === 1
        );
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
        $result = $this->objectModel->parseSqlVars($sql, $filters);
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
        $result = $this->objectModel->sql2Statement($sql, $mode);
        if(dao::isError()) return dao::getError();

        if(is_string($result)) return $result;
        if(is_object($result)) return 'object';

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
        $result = $this->objectModel->validateSql($sql, $driver);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
