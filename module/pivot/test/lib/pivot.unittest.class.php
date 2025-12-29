<?php
declare(strict_types=1);
class pivotTest
{
    private $objectModel;
    private $objectTao;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('pivot');
        $this->objectTao   = $tester->loadTao('pivot');
    }

    /**
     * 测试getByID。
     * Test getByID.
     *
     * @param  int         $id
     * @access public
     * @return object|bool
     */
    public function getByIDTest(int $id): object|bool
    {
        $result = $this->objectModel->getByID($id);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initSql method.
     *
     * @param  string $sql
     * @param  array  $filters
     * @param  string $groupList
     * @access public
     * @return array
     */
    public function initSqlTest(string $sql, array $filters, string $groupList): array
    {
        $result = $this->objectModel->initSql($sql, $filters, $groupList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setFilterDefault method.
     *
     * @param  array $filters
     * @param  bool  $processDateVar
     * @access public
     * @return array
     */
    public function setFilterDefaultTest(array $filters, bool $processDateVar = true): array
    {
        $result = $this->objectModel->setFilterDefault($filters, $processDateVar);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSysOptions method.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @param  mixed  $source
     * @param  string $saveAs
     * @param  string $driver
     * @access public
     * @return mixed
     */
    public function getSysOptionsTest($type = '', $object = '', $field = '', $source = '', $saveAs = '', $driver = 'mysql')
    {
        $result = $this->objectModel->getSysOptions($type, $object, $field, $source, $saveAs, $driver);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试 processGroupRows。
     * Test processGroupRows.
     *
     * @param  array  $columns
     * @param  string $sql
     * @param  array  $filterFormat
     * @param  array  $groups
     * @param  string $groupList
     * @param  array  $fieldS
     * @param  string $showColTotal
     * @param  array  $cols
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function processGroupRowsTest(array $columns, string $sql, array $filterFormat, array $groups, string $groupList, array $fields, string $showColTotal, array &$cols, array $langs): array
    {
        return $this->objectModel->processGroupRows($columns, $sql, $filterFormat, $groups, $groupList, $fields, $showColTotal, $cols, $langs);
    }

    /**
     * Test getPivotDataByID method.
     *
     * @param  int $id
     * @access public
     * @return mixed
     */
    public function getPivotDataByIDTest($id)
    {
        $result = $this->objectModel->getPivotDataByID($id);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPivotSpec method.
     *
     * @param  int    $pivotID
     * @param  string $version
     * @param  bool   $processDateVar
     * @param  bool   $addDrills
     * @access public
     * @return mixed
     */
    public function getPivotSpecTest($pivotID, $version, $processDateVar = false, $addDrills = true)
    {
        $result = $this->objectModel->getPivotSpec($pivotID, $version, $processDateVar, $addDrills);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processNameDesc method.
     *
     * @param  object $pivot
     * @access public
     * @return object
     */
    public function processNameDescTest($pivot)
    {
        $this->objectModel->processNameDesc($pivot);
        if(dao::isError()) return dao::getError();

        return $pivot;
    }

    /**
     * Test completePivot method.
     *
     * @param  object $pivot
     * @access public
     * @return object
     */
    public function completePivotTest($pivot)
    {
        // 使用反射调用私有方法completePivot
        $reflectionClass = new ReflectionClass($this->objectModel);
        $method = $reflectionClass->getMethod('completePivot');
        $method->setAccessible(true);

        // 调用completePivot方法，该方法会直接修改传入的对象
        $method->invoke($this->objectModel, $pivot);

        return $pivot;
    }

    /**
     * Test processDateVar method.
     *
     * @param  mixed  $var
     * @param  string $type
     * @access public
     * @return string
     */
    public function processDateVarTest($var, $type = 'date')
    {
        $result = $this->objectModel->processDateVar($var, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test appendWhereFilterToSql method.
     *
     * @param  string      $sql
     * @param  array|false $filters
     * @param  string      $driver
     * @access public
     * @return string
     */
    public function appendWhereFilterToSqlTest($sql, $filters, $driver)
    {
        $result = $this->objectModel->appendWhereFilterToSql($sql, $filters, $driver);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test filterFieldsWithSettings method.
     *
     * @param  array $fields
     * @param  array $groups
     * @param  array $columns
     * @access public
     * @return array
     */
    public function filterFieldsWithSettingsTest($fields, $groups, $columns)
    {
        $result = $this->objectModel->filterFieldsWithSettings($fields, $groups, $columns);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test generateTableCols method.
     *
     * @param  array $fields
     * @param  array $groups
     * @param  array $langs
     * @access public
     * @return array
     */
    public function generateTableColsTest($fields, $groups, $langs)
    {
        $result = $this->objectModel->generateTableCols($fields, $groups, $langs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getShowColPosition method.
     *
     * @param  array|object $settings
     * @access public
     * @return string
     */
    public function getShowColPositionTest($settings)
    {
        $result = $this->objectModel->getShowColPosition($settings);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test columnStatistics method.
     *
     * @param  array  $records
     * @param  string $statistic
     * @param  string $field
     * @access public
     * @return mixed
     */
    public function columnStatisticsTest(array $records, string $statistic, string $field): mixed
    {
        $result = $this->objectModel->columnStatistics($records, $statistic, $field);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupTreeWithKey method.
     *
     * @param  array $data
     * @access public
     * @return array|string
     */
    public function getGroupTreeWithKeyTest(array $data): array|string
    {
        $result = $this->objectModel->getGroupTreeWithKey($data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test formatCellData method.
     *
     * @param  string $key
     * @param  array  $data
     * @access public
     * @return array
     */
    public function formatCellDataTest(string $key, array $data): array
    {
        $result = $this->objectModel->formatCellData($key, $data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getColumnSummary method.
     *
     * @param  array  $data
     * @param  string $totalKey
     * @access public
     * @return array
     */
    public function getColumnSummaryTest(array $data, string $totalKey): array
    {
        $result = $this->objectModel->getColumnSummary($data, $totalKey);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test addRowSummary method.
     *
     * @param  array $groupTree
     * @param  array $data
     * @param  array $groups
     * @param  int   $currentGroup
     * @access public
     * @return array
     */
    public function addRowSummaryTest(array $groupTree, array $data, array $groups, int $currentGroup = 0): array
    {
        $result = $this->objectModel->addRowSummary($groupTree, $data, $groups, $currentGroup);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test pureCrystalData method.
     *
     * @param  array $records
     * @access public
     * @return array
     */
    public function pureCrystalDataTest(array $records): array
    {
        $result = $this->objectModel->pureCrystalData($records);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test flattenRow method.
     *
     * @param  array $row
     * @access public
     * @return array
     */
    public function flattenRowTest(array $row): array
    {
        $result = $this->objectModel->flattenRow($row);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test flattenCrystalData method.
     *
     * @param  array $crystalData
     * @param  bool  $withGroupSummary
     * @access public
     * @return array
     */
    public function flattenCrystalDataTest(array $crystalData, bool $withGroupSummary = false): array
    {
        $result = $this->objectModel->flattenCrystalData($crystalData, $withGroupSummary);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processRowSpan method.
     *
     * @param  array $records
     * @param  array $groups
     * @access public
     * @return array
     */
    public function processRowSpanTest(array $records, array $groups): array
    {
        $result = $this->objectModel->processRowSpan($records, $groups);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRowTotal method.
     *
     * @param  array $row
     * @access public
     * @return array
     */
    public function getRowTotalTest(array $row): array
    {
        $result = $this->objectModel->getRowTotal($row);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setPercentage method.
     *
     * @param  array $row
     * @param  array $rowTotal
     * @param  array $columnTotal
     * @access public
     * @return array
     */
    public function setPercentageTest(array $row, array $rowTotal, array $columnTotal): array
    {
        $result = $this->objectModel->setPercentage($row, $rowTotal, $columnTotal);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processPercentage method.
     *
     * @param  array $crystalData
     * @param  array $allSummary
     * @access public
     * @return array
     */
    public function processPercentageTest(array $crystalData, array $allSummary): array
    {
        $result = $this->objectModel->processPercentage($crystalData, $allSummary);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test groupRecords method.
     *
     * @param  array $records
     * @param  array $groups
     * @access public
     * @return array
     */
    public function groupRecordsTest(array $records, array $groups): array
    {
        $result = $this->objectModel->groupRecords($records, $groups);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setUniqueSlices method.
     *
     * @param  string $slice
     * @param  array  $records
     * @access public
     * @return array
     */
    public function setUniqueSlicesTest(?array $records = null, ?array $setting = null): array
    {
        if($records === null) {
            // 构造测试数据
            $records = array();
            $record1 = new stdClass();
            $record2 = new stdClass();
            $record3 = new stdClass();
            $record4 = new stdClass();

            $record1->category = 'bug';
            $record1->priority = '1';
            $record1->id = 1;
            $record2->category = 'story';
            $record2->priority = '2';
            $record2->id = 2;
            $record3->category = 'bug';
            $record3->priority = '1';
            $record3->id = 3;
            $record4->category = 'story';
            $record4->priority = '3';
            $record4->id = 4;
            $records = array($record1, $record2, $record3, $record4);
        }

        if($setting === null) {
            $setting = array('slice' => 'category');
        }

        $result = $this->objectModel->setUniqueSlices($records, $setting);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSliceRecords method.
     *
     * @param  array  $records
     * @param  string $field
     * @access public
     * @return array
     */
    public function getSliceRecordsTest(array $records, string $field): array
    {
        $result = $this->objectModel->getSliceRecords($records, $field);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCellData method.
     *
     * @param  string $columnKey
     * @param  array  $records
     * @param  array  $setting
     * @access public
     * @return array
     */
    public function getCellDataTest(string $columnKey, array $records, array $setting): array
    {
        $result = $this->objectModel->getCellData($columnKey, $records, $setting);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processCrystalData method.
     *
     * @param  array $groups
     * @param  array $records
     * @param  array $settings
     * @access public
     * @return array
     */
    public function processCrystalDataTest(array $groups, array $records, array $settings): array
    {
        $result = $this->objectModel->processCrystalData($groups, $records, $settings);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processRecordsForDisplay method.
     *
     * @param  array $records
     * @access public
     * @return array
     */
    public function processRecordsForDisplayTest(array $records): array
    {
        $result = $this->objectModel->processRecordsForDisplay($records);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRowSpanConfig method.
     *
     * @param  array $records
     * @access public
     * @return array
     */
    public function getRowSpanConfigTest(array $records): array
    {
        $result = $this->objectModel->getRowSpanConfig($records);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDrillsFromRecords method.
     *
     * @param  array $records
     * @param  array $groups
     * @access public
     * @return array
     */
    public function getDrillsFromRecordsTest(array $records, array $groups): array
    {
        $result = $this->objectModel->getDrillsFromRecords($records, $groups);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processQueryFilterDefaults method.
     *
     * @param  array|false $filters
     * @access public
     * @return array|false
     */
    public function processQueryFilterDefaultsTest(array|false $filters): array|false
    {
        $result = $this->objectModel->processQueryFilterDefaults($filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isFiltersAllEmpty method.
     *
     * @param  array $filters
     * @access public
     * @return bool
     */
    public function isFiltersAllEmptyTest(array $filters): bool
    {
        $result = $this->objectModel->isFiltersAllEmpty($filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test genOriginSheet method.
     *
     * @param  array       $fields
     * @param  array       $settings
     * @param  string      $sql
     * @param  array|false $filters
     * @param  array       $langs
     * @param  string      $driver
     * @access public
     * @return array|string
     */
    public function genOriginSheetTest($fields, $settings, $sql, $filters, $langs = array(), $driver = 'mysql')
    {
        if(dao::isError()) return dao::getError();

        $result = $this->objectModel->genOriginSheet($fields, $settings, $sql, $filters, $langs, $driver);

        return $result;
    }

    /**
     * Test getFilterFormat method.
     *
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function getFilterFormatTest(string $sql, array $filters): array
    {
        if(dao::isError()) return dao::getError();

        $result = $this->objectModel->getFilterFormat($sql, $filters);

        return $result;
    }

    /**
     * Test initVarFilter method.
     *
     * @param  array  $filters
     * @param  string $sql
     * @access public
     * @return string
     */
    public function initVarFilterTest(array $filters = array(), string $sql = ''): string
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('initVarFilter');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $filters, $sql);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getColLabel method.
     *
     * @param  string $key
     * @param  array  $fields
     * @param  array  $langs
     * @access public
     * @return string
     */
    public function getColLabelTest(string $key, array $fields, array $langs = array()): string
    {
        $result = $this->objectModel->getColLabel($key, $fields, $langs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupsKey method.
     *
     * @param  array  $groups
     * @param  object $record
     * @access public
     * @return string
     */
    public function getGroupsKeyTest(array $groups, object $record): string
    {
        $result = $this->objectModel->getGroupsKey($groups, $record);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processFilters method.
     *
     * @param  array  $filters
     * @param  string $filterStatus
     * @access public
     * @return array
     */
    public function processFiltersTest(array $filters, string $filterStatus): array
    {
        $result = $this->objectModel->processFilters($filters, $filterStatus);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setConditionValueWithFilters method.
     *
     * @param  array $condition
     * @param  array $filters
     * @access public
     * @return string
     */
    public function setConditionValueWithFiltersTest(array $condition, array $filters): string
    {
        $result = $this->objectModel->setConditionValueWithFilters($condition, $filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFieldsFromPivot method.
     *
     * @param  object $pivot
     * @param  string $key
     * @param  mixed  $default
     * @param  bool   $jsonDecode
     * @param  bool   $needArray
     * @access public
     * @return mixed
     */
    public function getFieldsFromPivotTest(object $pivot, string $key, mixed $default = null, bool $jsonDecode = false, bool $needArray = false): mixed
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('getFieldsFromPivot');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectModel, array($pivot, $key, $default, $jsonDecode, $needArray));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFieldsOptions method.
     *
     * @param  array  $fieldSettings
     * @param  array  $records
     * @param  string $driver
     * @access public
     * @return array
     */
    public function getFieldsOptionsTest($fieldSettings = array(), $records = array(), $driver = 'mysql')
    {
        $result = $this->objectModel->getFieldsOptions($fieldSettings, $records, $driver);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFieldsOptions method and return count.
     *
     * @param  array  $fieldSettings
     * @param  array  $records
     * @param  string $driver
     * @access public
     * @return int
     */
    public function getFieldsOptionsCountTest($fieldSettings = array(), $records = array(), $driver = 'mysql')
    {
        $result = $this->objectModel->getFieldsOptions($fieldSettings, $records, $driver);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test processDTableCols method.
     *
     * @param  array $cols
     * @access public
     * @return mixed
     */
    public function processDTableColsTest(array $cols)
    {
        $result = $this->objectModel->processDTableCols($cols);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processDTableData method.
     *
     * @param  array $cols
     * @param  array $datas
     * @access public
     * @return array
     */
    public function processDTableDataTest(array $cols, array $datas): array
    {
        $result = $this->objectModel->processDTableData($cols, $datas);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildPivotTable method.
     *
     * @param  mixed $data
     * @param  array $configs
     * @access public
     * @return string
     */
    public function buildPivotTableTest($data, $configs = array())
    {
        ob_start();
        $this->objectModel->buildPivotTable($data, $configs);
        $result = ob_get_contents();
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getReferSQL method.
     *
     * @param  string $object
     * @param  string $whereSQL
     * @param  array  $fields
     * @access public
     * @return string
     */
    public function getReferSQLTest($object, $whereSQL = '', $fields = array())
    {
        $result = $this->objectModel->getReferSQL($object, $whereSQL, $fields);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDrillSQL method.
     *
     * @param  string $objectTable
     * @param  string $whereSQL
     * @param  array  $conditions
     * @access public
     * @return string
     */
    public function getDrillSQLTest($objectTable, $whereSQL = '', $conditions = array())
    {
        $result = $this->objectModel->getDrillSQL($objectTable, $whereSQL, $conditions);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test execDrillSQL method.
     *
     * @param  string $object
     * @param  string $drillSQL
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function execDrillSQLTest($object, $drillSQL, $limit = 10)
    {
        $result = $this->objectModel->execDrillSQL($object, $drillSQL, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDrillResult method.
     *
     * @param  string $object
     * @param  string $whereSQL
     * @param  array  $filters
     * @param  array  $conditions
     * @param  bool   $emptyFilters
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function getDrillResultTest($object, $whereSQL, $filters = array(), $conditions = array(), $emptyFilters = true, $limit = 10)
    {
        $result = $this->objectModel->getDrillResult($object, $whereSQL, $filters, $conditions, $emptyFilters, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPivotVersions method.
     *
     * @param  int $pivotID
     * @access public
     * @return array|bool
     */
    public function getPivotVersionsTest(int $pivotID)
    {
        $result = $this->objectModel->getPivotVersions($pivotID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMaxVersion method.
     *
     * @param  int $pivotID
     * @access public
     * @return string
     */
    public function getMaxVersionTest(int $pivotID): string
    {
        $result = $this->objectModel->getMaxVersion($pivotID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMaxVersionByIDList method.
     *
     * @param  string|array $pivotIDList
     * @access public
     * @return array
     */
    public function getMaxVersionByIDListTest(string|array $pivotIDList): array
    {
        $result = $this->objectModel->getMaxVersionByIDList($pivotIDList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isVersionChange method.
     *
     * @param  array|object $pivots
     * @param  bool         $isObject
     * @access public
     * @return array|object
     */
    public function isVersionChangeTest(array|object $pivots, bool $isObject = true): array|object
    {
        $result = $this->objectModel->isVersionChange($pivots, $isObject);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test switchNewVersion method.
     *
     * @param  int    $pivotID
     * @param  string $version
     * @access public
     * @return mixed
     */
    public function switchNewVersionTest(int $pivotID, string $version)
    {
        try
        {
            $result = $this->objectModel->switchNewVersion($pivotID, $version);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Test fetchPivot method.
     *
     * @param  int         $id
     * @param  string|null $version
     * @access public
     * @return object|bool
     */
    public function fetchPivotTest(int $id, ?string $version = null): object|bool
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('fetchPivot');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao, $id, $version);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test mergePivotSpecData method.
     *
     * @param  mixed $pivots
     * @param  bool  $isObject
     * @access public
     * @return mixed
     */
    public function mergePivotSpecDataTest($pivots, $isObject = true)
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('mergePivotSpecData');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao, $pivots, $isObject);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processProductPlan method.
     *
     * @param  array  $products
     * @param  string $conditions
     * @access public
     * @return array
     */
    public function processProductPlanTest(array $products, string $conditions): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processProductPlan');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array(&$products, $conditions));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processPlanStories method.
     *
     * @param  array  $products
     * @param  string $storyType
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function processPlanStoriesTest(array $products, string $storyType, array $plans): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processPlanStories');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array(&$products, $storyType, $plans));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getExecutionList method.
     *
     * @param  string $begin
     * @param  string $end
     * @param  array  $executionIDList
     * @access public
     * @return array
     */
    public function getExecutionListTest(string $begin, string $end, array $executionIDList = array()): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getExecutionList');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($begin, $end, $executionIDList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getNoAssignExecution method.
     *
     * @param  array $deptUsers
     * @access public
     * @return array
     */
    public function getNoAssignExecutionTest(array $deptUsers): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getNoAssignExecution');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($deptUsers));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTeamTasks method.
     *
     * @param  array $taskIDList
     * @access public
     * @return array
     */
    public function getTeamTasksTest(array $taskIDList): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getTeamTasks');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($taskIDList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAssignBugGroup method.
     *
     * @access public
     * @return array
     */
    public function getAssignBugGroupTest(): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getAssignBugGroup');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductProjects method.
     *
     * @access public
     * @return array
     */
    public function getProductProjectsTest(): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getProductProjects');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAllProductsIDAndName method.
     *
     * @access public
     * @return array
     */
    public function getAllProductsIDAndNameTest(): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getAllProductsIDAndName');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProjectAndExecutionNameQuery method.
     *
     * @access public
     * @return array
     */
    public function getProjectAndExecutionNameQueryTest(): array
    {
        // 使用反射访问protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getProjectAndExecutionNameQuery');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchPivotDrills method.
     *
     * @param  int          $pivotID
     * @param  string       $version
     * @param  string|array $fields
     * @access public
     * @return array
     */
    public function fetchPivotDrillsTest(int $pivotID, string $version, string|array $fields): array
    {
        $result = $this->objectTao->fetchPivotDrills($pivotID, $version, $fields);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBugAssign method.
     *
     * @access public
     * @return array
     */
    public function getBugAssign(): array
    {
        $result = $this->objectModel->getBugAssign();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkIFChartInUse method.
     *
     * @param  int    $chartID
     * @param  string $type
     * @param  array  $screens
     * @access public
     * @return bool
     */
    public function checkIFChartInUseTest(int $chartID, string $type = 'chart', array $screens = array()): bool
    {
        $result = $this->objectModel->checkIFChartInUse($chartID, $type, $screens);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getConnectSQL method.
     *
     * @param  array $filters
     * @access public
     * @return string
     */
    public function getConnectSQLTest(array $filters): string
    {
        $result = $this->objectModel->getConnectSQL($filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProjectExecutions method.
     *
     * @access public
     * @return array
     */
    public function getProjectExecutions(): array
    {
        $result = $this->objectModel->getProjectExecutions();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProjectExecutions method with different scenarios.
     *
     * @param  string $testCase
     * @access public
     * @return mixed
     */
    public function getProjectExecutionsTest()
    {
        try {
            $result = $this->objectModel->getProjectExecutions();
            if(dao::isError()) return dao::getError();

            // 如果返回空数组，返回字符串'empty'以便测试
            if(is_array($result) && empty($result)) return 'empty';

            // 如果返回非空数组，返回字符串'array'以便测试
            if(is_array($result) && !empty($result)) return 'array';

            return $result;
        } catch (Exception $e) {
            // 如果方法调用出错，返回空数组
            return array();
        }
    }

    /**
     * Test replaceTableNames method.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function replaceTableNamesTest(string $sql): string
    {
        $result = $this->objectModel->replaceTableNames($sql);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFirstGroup method.
     *
     * @param  int $dimensionID
     * @access public
     * @return int
     */
    public function getFirstGroupTest(int $dimensionID): int
    {
        $method = new ReflectionMethod($this->objectTao, 'getFirstGroup');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $dimensionID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDrillCols method.
     *
     * @param  string $object
     * @access public
     * @return array
     */
    public function getDrillColsTest(string $object): array
    {
        $result = $this->objectModel->getDrillCols($object);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupsFromSettings method.
     *
     * @param  array $settings
     * @access public
     * @return array
     */
    public function getGroupsFromSettingsTest(array $settings): array
    {
        $result = $this->objectModel->getGroupsFromSettings($settings);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProducts method.
     *
     * @param  string $conditions
     * @param  string $storyType
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function getProductsTest(string $conditions = '', string $storyType = 'story', array $filters = array()): array
    {
        $result = $this->objectModel->getProducts($conditions, $storyType, $filters);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUserWorkLoad method.
     *
     * @param  array $projects
     * @param  array $teamTasks
     * @param  float $allHour
     * @access public
     * @return array
     */
    public function getUserWorkLoadTest(array $projects, array $teamTasks, float $allHour): array
    {
        $result = $this->objectModel->getUserWorkLoad($projects, $teamTasks, $allHour);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getWorkload method.
     *
     * @param  int    $dept
     * @param  string $assign
     * @param  array  $users
     * @param  float  $allHour
     * @access public
     * @return array
     */
    public function getWorkloadTest(int $dept, string $assign, array $users, float $allHour): array
    {
        $result = $this->objectModel->getWorkload($dept, $assign, $users, $allHour);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getWorkloadNoAssign method.
     *
     * @param  array  $deptUsers
     * @param  array  $users
     * @param  bool   $canViewExecution
     * @access public
     * @return array
     */
    public function getWorkloadNoAssignTest(array $deptUsers, array $users, bool $canViewExecution): array
    {
        $result = $this->objectModel->getWorkloadNoAssign($deptUsers, $users, $canViewExecution);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getWorkLoadAssign method.
     *
     * @param  array  $deptUsers
     * @param  array  $users
     * @param  bool   $canViewExecution
     * @param  float  $allHour
     * @access public
     * @return array
     */
    public function getWorkLoadAssignTest(array $deptUsers, array $users, bool $canViewExecution, float $allHour): array
    {
        $result = $this->objectModel->getWorkLoadAssign($deptUsers, $users, $canViewExecution, $allHour);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isShowLastRow method.
     *
     * @param  string $showColPosition
     * @access public
     * @return bool
     */
    public function isShowLastRowTest(string $showColPosition): bool
    {
        $result = $this->objectModel->isShowLastRow($showColPosition);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setExecutionName method.
     *
     * @param  object $execution
     * @param  bool   $canViewExecution
     * @access public
     * @return object
     */
    public function setExecutionNameTest(object $execution, bool $canViewExecution): object
    {
        $this->objectModel->setExecutionName($execution, $canViewExecution);
        if(dao::isError()) return dao::getError();

        return $execution;
    }

    /**
     * Test getAssignTask method.
     *
     * @param  array $deptUsers
     * @access public
     * @return array
     */
    public function getAssignTaskTest(array $deptUsers = array()): array
    {
        $result = $this->objectTao->getAssignTask($deptUsers);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupsByDimensionAndPath method.
     *
     * @param  int    $dimensionID
     * @param  string $path
     * @access public
     * @return array
     */
    public function getGroupsByDimensionAndPathTest(int $dimensionID, string $path): array
    {
        $method = new ReflectionMethod($this->objectTao, 'getGroupsByDimensionAndPath');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $dimensionID, $path);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
