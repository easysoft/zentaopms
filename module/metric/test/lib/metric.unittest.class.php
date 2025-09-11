<?php
declare(strict_types = 1);
class metricTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('metric');
        $this->objectTao   = $tester->loadTao('metric');
    }

    /**
     * Test getViewTableData method.
     *
     * @param  object $metric
     * @param  array  $result
     * @access public
     * @return mixed
     */
    public function getViewTableDataTest($metric = null, $result = null)
    {
        $result = $this->objectModel->getViewTableData($metric, $result);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTimeTable method.
     *
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $withCalcTime
     * @access public
     * @return mixed
     */
    public function getTimeTableTest($data = null, $dateType = 'day', $withCalcTime = true)
    {
        $result = $this->objectModel->getTimeTable($data, $dateType, $withCalcTime);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getObjectTable method.
     *
     * @param  array  $header
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $withCalcTime
     * @access public
     * @return mixed
     */
    public function getObjectTableTest($header = null, $data = null, $dateType = 'day', $withCalcTime = true)
    {
        $result = $this->objectModel->getObjectTable($header, $data, $dateType, $withCalcTime);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMetricsByCodeList method.
     *
     * @param  array $codeList
     * @access public
     * @return mixed
     */
    public function getMetricsByCodeListTest($codeList = null)
    {
        $result = $this->objectModel->getMetricsByCodeList($codeList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRecordCalcInfo method.
     *
     * @param  mixed $recordID
     * @access public
     * @return mixed
     */
    public function getRecordCalcInfoTest($recordID = null)
    {
        $result = $this->objectModel->getRecordCalcInfo($recordID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDAO method.
     *
     * @access public
     * @return mixed
     */
    public function getDAOTest()
    {
        $result = $this->objectModel->getDAO();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDataStatement method.
     *
     * @param  object $calculator
     * @param  string $returnType
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getDataStatementTest($calculator = null, $returnType = 'statement', $vision = 'rnd')
    {
        $result = $this->objectModel->getDataStatement($calculator, $returnType, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test calculateMetricByCode method.
     *
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function calculateMetricByCodeTest($code = null)
    {
        $result = $this->objectModel->calculateMetricByCode($code);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getResultByCodeWithArray method.
     *
     * @param  string $code
     * @param  array  $options
     * @param  string $type
     * @param  object $pager
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getResultByCodeWithArrayTest($code = null, $options = array(), $type = 'realtime', $pager = null, $vision = 'rnd')
    {
        $result = $this->objectModel->getResultByCodeWithArray($code, $options, $type, $pager, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test mergeRecord method.
     *
     * @param  array $record
     * @param  array $result
     * @access public
     * @return mixed
     */
    public function mergeRecordTest($record = null, $result = array())
    {
        $result = $this->objectModel->mergeRecord($record, $result);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUniqueKeyByRecord method.
     *
     * @param  array $record
     * @access public
     * @return mixed
     */
    public function getUniqueKeyByRecordTest($record = null)
    {
        $result = $this->objectModel->getUniqueKeyByRecord($record);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCalculator method.
     *
     * @param  string $scope
     * @param  string $purpose
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function getCalculatorTest($scope = null, $purpose = null, $code = null)
    {
        $result = $this->objectModel->getCalculator($scope, $purpose, $code);
        if(dao::isError()) return dao::getError();

        if(is_object($result) && isset($result->fieldList)) return get_class($result);
        return $result;
    }

    /**
     * Test calculateReuseMetric method.
     *
     * @param  object $calculator
     * @param  array  $options
     * @param  string $type
     * @param  object $pager
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function calculateReuseMetricTest($calculator = null, $options = array(), $type = 'realtime', $pager = null, $vision = 'rnd')
    {
        $result = $this->objectModel->calculateReuseMetric($calculator, $options, $type, $pager, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test calculateSingleMetric method.
     *
     * @param  object $calculator
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function calculateSingleMetricTest($calculator = null, $vision = 'rnd')
    {
        if($calculator === null) return false;
        
        $result = $this->objectModel->calculateSingleMetric($calculator, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test calculateDefaultMetric method.
     *
     * @param  object $calculator
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function calculateDefaultMetricTest($calculator = null, $vision = 'rnd')
    {
        if($calculator === null) return false;
        
        $this->objectModel->calculateDefaultMetric($calculator, $vision);
        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Test getLatestResultByCode method.
     *
     * @param  string $code
     * @param  array  $options
     * @param  object $pager
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getLatestResultByCodeTest($code = null, $options = array(), $pager = null, $vision = 'rnd')
    {
        // Suppress errors and capture output to handle various error conditions
        ob_start();
        $error = error_get_last();
        
        try {
            $result = $this->objectModel->getLatestResultByCode($code, $options, $pager, $vision);
            if(dao::isError()) return dao::getError();

            ob_end_clean();
            return $result;
        } catch(TypeError $e) {
            ob_end_clean();
            return 'TypeError: Metric not found or invalid';
        } catch(Exception $e) {
            ob_end_clean();
            return 'Exception: ' . $e->getMessage();
        } catch(Error $e) {
            ob_end_clean();
            return 'Error: ' . $e->getMessage();
        }
        
        $output = ob_get_clean();
        if(!empty($output)) {
            return 'Error captured: ' . strip_tags($output);
        }
        
        return false;
    }

    /**
     * Test getResultByCodes method.
     *
     * @param  array  $codes
     * @param  array  $options
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getResultByCodesTest($codes = array(), $options = array(), $vision = 'rnd')
    {
        $result = $this->objectModel->getResultByCodes($codes, $options, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLogFile method.
     *
     * @access public
     * @return mixed
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
    public function saveLogsTest($log = '')
    {
        $logFile = $this->objectModel->getLogFile();
        $originalExists = file_exists($logFile);
        $originalContent = $originalExists ? file_get_contents($logFile) : '';
        
        $this->objectModel->saveLogs($log);
        if(dao::isError()) return dao::getError();
        
        $result = array();
        $result['fileExists'] = file_exists($logFile);
        
        if($result['fileExists']) {
            $content = file_get_contents($logFile);
            $result['hasPhpHeader'] = !$originalExists && strpos($content, "<?php\ndie();\n?>") === 0;
            $result['hasTimestamp'] = preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $content);
            $result['hasLogContent'] = strpos($content, trim($log)) !== false;
            
            // Clean up: restore original content or remove file
            if($originalExists) {
                file_put_contents($logFile, $originalContent);
            } else {
                unlink($logFile);
            }
        }
        
        return $result;
    }

    /**
     * Test getRecordFields method.
     *
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function getRecordFieldsTest($code = '')
    {
        $result = $this->objectModel->getRecordFields($code);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test rebuildPrimaryKey method.
     *
     * @param  string $testType
     * @access public
     * @return mixed
     */
    public function rebuildPrimaryKeyTest($testType = '')
    {
        global $tester;
        
        if(empty($testType))
        {
            // 测试空表情况
            $tester->dao->delete()->from(TABLE_METRICLIB)->exec();
            $this->objectModel->rebuildPrimaryKey();
            if(dao::isError()) return dao::getError();
            return null;
        }

        if($testType == 'normal')
        {
            // 清空表并插入正常数据
            $tester->dao->delete()->from(TABLE_METRICLIB)->exec();
            for($i = 1; $i <= 5; $i++)
            {
                $record = new stdClass();
                $record->metricCode = 'test_metric_' . $i;
                $record->value = $i * 10;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $i);
                $record->date = '2024-01-' . sprintf('%02d', $i);
                $tester->dao->insert(TABLE_METRICLIB)->data($record)->exec();
            }

            $this->objectModel->rebuildPrimaryKey();
            if(dao::isError()) return dao::getError();

            // 验证ID是否从1开始连续
            $records = $tester->dao->select('id')->from(TABLE_METRICLIB)->orderBy('id')->fetchAll();
            for($i = 0; $i < count($records); $i++)
            {
                if($records[$i]->id != ($i + 1)) return array('result' => 'failed');
            }
            return array('result' => 'success');
        }

        if($testType == 'discontinuous')
        {
            // 清空表并插入不连续ID数据
            $tester->dao->delete()->from(TABLE_METRICLIB)->exec();
            $ids = array(2, 5, 8, 12, 15);
            foreach($ids as $index => $id)
            {
                $record = new stdClass();
                $record->metricCode = 'test_metric_' . $id;
                $record->value = $id * 10;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $index + 1);
                $record->date = '2024-01-' . sprintf('%02d', $index + 1);
                $tester->dao->insert(TABLE_METRICLIB)->data($record)->exec();
                $tester->dao->update(TABLE_METRICLIB)->set('id')->eq($id)->where('metricCode')->eq('test_metric_' . $id)->exec();
            }

            $this->objectModel->rebuildPrimaryKey();
            if(dao::isError()) return dao::getError();

            // 验证ID是否变为连续
            $records = $tester->dao->select('id')->from(TABLE_METRICLIB)->orderBy('id')->fetchAll();
            for($i = 0; $i < count($records); $i++)
            {
                if($records[$i]->id != ($i + 1)) return array('result' => 'failed');
            }
            return array('result' => 'success');
        }

        if($testType == 'large')
        {
            // 清空表并插入大量数据测试
            $tester->dao->delete()->from(TABLE_METRICLIB)->exec();
            for($i = 1; $i <= 50; $i++)
            {
                $record = new stdClass();
                $record->metricCode = 'large_test_' . $i;
                $record->value = $i * 100;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $i % 28 + 1);
                $record->date = '2024-01-' . sprintf('%02d', $i % 28 + 1);
                $tester->dao->insert(TABLE_METRICLIB)->data($record)->exec();
            }

            $this->objectModel->rebuildPrimaryKey();
            if(dao::isError()) return dao::getError();

            // 验证大量数据的ID连续性
            $count = $tester->dao->select('COUNT(*) as count')->from(TABLE_METRICLIB)->fetch('count');
            $maxId = $tester->dao->select('MAX(id) as maxId')->from(TABLE_METRICLIB)->fetch('maxId');
            if($count == $maxId && $count == 50) return array('result' => 'success');
            return array('result' => 'failed');
        }

        if($testType == 'verify')
        {
            // 验证AUTO_INCREMENT值设置
            $tester->dao->delete()->from(TABLE_METRICLIB)->exec();
            for($i = 1; $i <= 3; $i++)
            {
                $record = new stdClass();
                $record->metricCode = 'verify_test_' . $i;
                $record->value = $i * 10;
                $record->year = '2024';
                $record->month = '01';
                $record->day = sprintf('%02d', $i);
                $record->date = '2024-01-' . sprintf('%02d', $i);
                $tester->dao->insert(TABLE_METRICLIB)->data($record)->exec();
            }

            $this->objectModel->rebuildPrimaryKey();
            if(dao::isError()) return dao::getError();

            // 验证下次插入的ID是否正确
            $record = new stdClass();
            $record->metricCode = 'auto_increment_test';
            $record->value = 999;
            $record->year = '2024';
            $record->month = '01';
            $record->day = '04';
            $record->date = '2024-01-04';
            $tester->dao->insert(TABLE_METRICLIB)->data($record)->exec();

            $newId = $tester->dao->select('id')->from(TABLE_METRICLIB)->where('metricCode')->eq('auto_increment_test')->fetch('id');
            return array('autoIncrement' => ($newId == 4));
        }

        return false;
    }

    /**
     * Test insertMetricLib method.
     *
     * @param  array  $recordWithCode
     * @param  string $calcType
     * @access public
     * @return mixed
     */
    public function insertMetricLibTest($recordWithCode = array(), $calcType = 'cron')
    {
        $result = $this->objectModel->insertMetricLib($recordWithCode, $calcType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test clearOutDatedRecords method.
     *
     * @param  string $code
     * @param  string $cycle
     * @access public
     * @return mixed
     */
    public function clearOutDatedRecordsTest($code = '', $cycle = '')
    {
        global $tester;
        
        if(empty($code) || empty($cycle)) return 0;
        
        // 记录删除前的数据量
        $beforeCount = $tester->dao->select('COUNT(*) as count')->from(TABLE_METRICLIB)->where('metricCode')->eq($code)->fetch('count');
        
        $this->objectModel->clearOutDatedRecords($code, $cycle);
        
        // 记录删除后的数据量
        $afterCount = $tester->dao->select('COUNT(*) as count')->from(TABLE_METRICLIB)->where('metricCode')->eq($code)->fetch('count');
        
        return $beforeCount - $afterCount;
    }

    /**
     * Test getDataset method.
     *
     * @param  mixed $dao
     * @access public
     * @return mixed
     */
    public function getDatasetTest($dao = null)
    {
        if($dao === null) {
            global $tester;
            $dao = $tester->dao;
        }
        
        $result = $this->objectModel->getDataset($dao);
        if(dao::isError()) return dao::getError();

        if(is_object($result)) {
            $info = new stdClass();
            $info->className = get_class($result);
            $info->dao = is_object($result->dao) ? gettype($result->dao) : 'null';
            $info->config = is_object($result->config) ? gettype($result->config) : 'null';
            $info->vision = isset($result->vision) ? gettype($result->vision) : 'null';
            return $info;
        }
        
        return $result;
    }

    /**
     * Test classifyCalc method.
     *
     * @param  array $calcList
     * @access public
     * @return mixed
     */
    public function classifyCalcTest($calcList = array())
    {
        $result = $this->objectModel->classifyCalc($calcList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test uniteFieldList method.
     *
     * @param  array $calcList
     * @access public
     * @return mixed
     */
    public function uniteFieldListTest($calcList = array())
    {
        $result = $this->objectModel->uniteFieldList($calcList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest($queryID = 0, $actionURL = '')
    {
        $this->objectModel->buildSearchForm($queryID, $actionURL);
        if(dao::isError()) return dao::getError();

        // 验证方法是否正确执行，通过返回成功标志
        return true;
    }

    /**
     * Test getWaterfullProjectPairs method.
     *
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getWaterfullProjectPairsTest($vision = 'rnd')
    {
        $result = $this->objectModel->getWaterfullProjectPairs($vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPairsByIdList method.
     *
     * @param  string $scope
     * @param  array  $idList
     * @access public
     * @return mixed
     */
    public function getPairsByIdListTest($scope = '', $idList = array())
    {
        $result = $this->objectModel->getPairsByIdList($scope, $idList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processScopeList method.
     *
     * @param  string $stage
     * @access public
     * @return mixed
     */
    public function processScopeListTest($stage = 'all')
    {
        $this->objectModel->processScopeList($stage);
        if(dao::isError()) return dao::getError();

        // 方法执行成功，返回true
        return true;
    }

    /**
     * Test processUnitList method.
     *
     * @access public
     * @return mixed
     */
    public function processUnitListTest()
    {
        // 备份原始unitList配置
        $originalUnitList = isset($this->objectModel->lang->metric->unitList['measure']) 
                           ? $this->objectModel->lang->metric->unitList['measure'] : null;

        $this->objectModel->processUnitList();
        if(dao::isError()) return dao::getError();

        // 获取处理后的measure单位
        $processedMeasure = isset($this->objectModel->lang->metric->unitList['measure']) 
                           ? $this->objectModel->lang->metric->unitList['measure'] : null;

        return $processedMeasure;
    }

    /**
     * Test processObjectList method.
     *
     * @param  mixed $urAndSR
     * @access public
     * @return mixed
     */
    public function processObjectListTest($urAndSR = null)
    {
        // 备份原始配置
        $originalUrAndSR = isset($this->objectModel->config->custom->URAndSR) 
                          ? $this->objectModel->config->custom->URAndSR : null;
        $originalObjectList = isset($this->objectModel->lang->metric->objectList['requirement']) 
                             ? $this->objectModel->lang->metric->objectList['requirement'] : null;

        // 设置测试配置
        if($urAndSR !== null)
        {
            if(!isset($this->objectModel->config->custom)) $this->objectModel->config->custom = new stdClass();
            $this->objectModel->config->custom->URAndSR = $urAndSR;
        }
        else if(isset($this->objectModel->config->custom->URAndSR))
        {
            unset($this->objectModel->config->custom->URAndSR);
        }

        // 确保requirement条目存在
        if(!isset($this->objectModel->lang->metric->objectList['requirement']))
        {
            $this->objectModel->lang->metric->objectList['requirement'] = 'Requirement';
        }

        $this->objectModel->processObjectList();
        if(dao::isError()) return dao::getError();

        // 检查requirement条目是否存在
        $hasRequirement = isset($this->objectModel->lang->metric->objectList['requirement']);

        // 恢复原始配置
        if($originalUrAndSR !== null)
        {
            $this->objectModel->config->custom->URAndSR = $originalUrAndSR;
        }
        else if(isset($this->objectModel->config->custom->URAndSR))
        {
            unset($this->objectModel->config->custom->URAndSR);
        }

        if($originalObjectList !== null)
        {
            $this->objectModel->lang->metric->objectList['requirement'] = $originalObjectList;
        }

        return $hasRequirement;
    }

    /**
     * Test createSqlFunction method.
     *
     * @param  string $sql
     * @param  object $measurement
     * @access public
     * @return mixed
     */
    public function createSqlFunctionTest($sql = '', $measurement = null)
    {
        // 清除错误缓冲区以捕获可能的警告信息
        ob_start();
        $result = $this->objectModel->createSqlFunction($sql, $measurement);
        ob_end_clean();
        
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test execSqlMeasurement method.
     *
     * @param  object $measurement
     * @param  array  $vars
     * @access public
     * @return mixed
     */
    public function execSqlMeasurementTest($measurement = null, $vars = array())
    {
        ob_start();
        $result = $this->objectModel->execSqlMeasurement($measurement, $vars);
        $output = ob_get_clean();
        
        if(dao::isError()) return dao::getError();

        // 如果有HTML输出，从输出中提取实际结果
        if(!empty($output) && strpos($output, '<pre') !== false)
        {
            // 清理HTML标签，只保留实际内容
            $cleanOutput = preg_replace('/<[^>]*>/', '', $output);
            $cleanOutput = trim($cleanOutput);
            
            // 尝试提取最后的数字或值
            if(preg_match('/(\d+)$/', $cleanOutput, $matches))
            {
                return $matches[1];
            }
            return $cleanOutput;
        }

        return $result;
    }

    /**
     * Test updateMetricFields method.
     *
     * @param  string $metricID
     * @param  object $metric
     * @access public
     * @return mixed
     */
    public function updateMetricFieldsTest($metricID = '', $metric = null)
    {
        if(empty($metricID) || $metric === null) return 'invalid_params';
        
        try {
            $this->objectModel->updateMetricFields($metricID, $metric);
            if(dao::isError()) return dao::getError();
            return '';
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage();
        }
    }

    /**
     * Test getMetricById method.
     *
     * @param  string $metricID
     * @access public
     * @return mixed
     */
    public function getMetricByIdTest($metricID = '')
    {
        global $tester;
        $result = $tester->dao->select('*')->from(TABLE_METRIC)->where('id')->eq($metricID)->fetch();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processOldMetrics method.
     *
     * @param  mixed $metrics
     * @access public
     * @return mixed
     */
    public function processOldMetricsTest($metrics = null)
    {
        if($metrics === null)
        {
            // 默认测试数据
            $metrics = array();
            $metric1 = new stdClass();
            $metric1->id = 1;
            $metric1->type = 'sql';
            $metric1->fromID = 1;
            $metric1->unit = '';
            $metrics[] = $metric1;
            
            $metric2 = new stdClass();
            $metric2->id = 2;
            $metric2->type = 'php';
            $metric2->fromID = 2;
            $metric2->unit = '';
            $metrics[] = $metric2;
        }
        
        $result = $this->objectModel->processOldMetrics($metrics);
        if(dao::isError()) return dao::getError();
        
        return $result;
    }

    /**
     * Test processOldMetrics method with open edition.
     *
     * @access public
     * @return mixed
     */
    public function processOldMetricsOpenTest()
    {
        global $config;
        $originalEdition = $config->edition;
        $config->edition = 'open';
        
        try {
            $metrics = array();
            $metric1 = new stdClass();
            $metric1->id = 1;
            $metric1->type = 'sql';
            $metric1->fromID = 1;
            $metric1->unit = '';
            $metrics[] = $metric1;
            
            $result = $this->objectModel->processOldMetrics($metrics);
            if(dao::isError()) return dao::getError();
            
            return $result;
        }
        finally
        {
            $config->edition = $originalEdition;
        }
    }

    /**
     * Test processOldMetrics method with max edition.
     *
     * @access public
     * @return mixed
     */
    public function processOldMetricsMaxTest()
    {
        global $config;
        $originalEdition = $config->edition;
        $config->edition = 'max';
        
        try {
            $metrics = array();
            $metric1 = new stdClass();
            $metric1->id = 1;
            $metric1->type = 'sql';
            $metric1->fromID = 1;
            $metric1->unit = '';
            $metrics[] = $metric1;
            
            $result = $this->objectModel->processOldMetrics($metrics);
            if(dao::isError()) return dao::getError();
            
            return $result;
        }
        finally
        {
            $config->edition = $originalEdition;
        }
    }

    /**
     * Test processOldMetrics method with empty metrics.
     *
     * @access public
     * @return mixed
     */
    public function processOldMetricsEmptyTest()
    {
        $metrics = array();
        $result = $this->objectModel->processOldMetrics($metrics);
        if(dao::isError()) return dao::getError();
        
        return $result;
    }

    /**
     * Test processOldMetrics method with new metric type.
     *
     * @access public
     * @return mixed
     */
    public function processOldMetricsNewTest()
    {
        global $config;
        $originalEdition = $config->edition;
        $config->edition = 'max';
        
        try {
            $metrics = array();
            $metric = new stdClass();
            $metric->id = 1;
            $metric->type = 'php';
            $metric->fromID = 1;
            $metric->unit = '';
            $metrics[] = $metric;
            
            $result = $this->objectModel->processOldMetrics($metrics);
            if(dao::isError()) return dao::getError();
            
            return $result;
        }
        finally
        {
            $config->edition = $originalEdition;
        }
    }

    /**
     * Test getMetricRecordType method.
     *
     * @param  string $code
     * @param  string $scope
     * @access public
     * @return mixed
     */
    public function getMetricRecordTypeTest($code = '', $scope = 'system')
    {
        $result = $this->objectModel->getMetricRecordType($code, $scope);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getEchartLegend method.
     *
     * @param  array  $series
     * @param  string $range
     * @access public
     * @return mixed
     */
    public function getEchartLegendTest($series = array(), $range = 'time')
    {
        $result = $this->objectModel->getEchartLegend($series, $range);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getEchartsOptions method.
     *
     * @param  array  $header
     * @param  array  $data
     * @param  string $chartType
     * @access public
     * @return mixed
     */
    public function getEchartsOptionsTest($header = array(), $data = array(), $chartType = 'line')
    {
        $result = $this->objectModel->getEchartsOptions($header, $data, $chartType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getObjectOptions method.
     *
     * @param  array  $data
     * @param  string $type
     * @param  string $chartType
     * @access public
     * @return mixed
     */
    public function getObjectOptionsTest($data = array(), $type = 'line', $chartType = 'line')
    {
        $result = $this->objectModel->getObjectOptions($data, $type, $chartType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTimeOptions method.
     *
     * @param  array  $header
     * @param  array  $data
     * @param  string $type
     * @param  string $chartType
     * @access public
     * @return mixed
     */
    public function getTimeOptionsTest($header = array(), $data = array(), $type = 'line', $chartType = 'line')
    {
        $result = $this->objectModel->getTimeOptions($header, $data, $type, $chartType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateMetricDate method.
     *
     * @access public
     * @return mixed
     */
    public function updateMetricDateTest()
    {
        global $tester;
        
        // 记录更新前有多少条 createdDate 为 null 的记录
        $beforeCount = $tester->dao->select('count(*)')->from(TABLE_METRIC)->where('createdDate is null')->fetch('count(*)');
        
        $this->objectModel->updateMetricDate();
        if(dao::isError()) return dao::getError();
        
        // 记录更新后有多少条 createdDate 为 null 的记录
        $afterCount = $tester->dao->select('count(*)')->from(TABLE_METRIC)->where('createdDate is null')->fetch('count(*)');
        
        return array('before' => $beforeCount, 'after' => $afterCount, 'updated' => $beforeCount - $afterCount);
    }

    /**
     * Test parseDateStr method.
     *
     * @param  string $date
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function parseDateStrTest($date, $dateType = 'all')
    {
        $result = $this->objectModel->parseDateStr($date, $dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDateByDateType method.
     *
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function getDateByDateTypeTest($dateType)
    {
        $result = $this->objectModel->getDateByDateType($dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getStartAndEndOfWeek method.
     *
     * @param  int    $year
     * @param  int    $week
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function getStartAndEndOfWeekTest($year, $week, $type = 'datetime')
    {
        $result = $this->objectModel->getStartAndEndOfWeek($year, $week, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isCalcByCron method.
     *
     * @param  string $code
     * @param  string $date
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function isCalcByCronTest($code, $date, $dateType)
    {
        $result = $this->objectModel->isCalcByCron($code, $date, $dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getNoDataTip method.
     *
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function getNoDataTipTest($code)
    {
        $result = $this->objectModel->getNoDataTip($code);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkHasInferenceOfDate method.
     *
     * @param  string $code
     * @param  string $dateType
     * @param  string $date
     * @access public
     * @return mixed
     */
    public function checkHasInferenceOfDateTest($code, $dateType, $date)
    {
        $result = $this->objectModel->checkHasInferenceOfDate($code, $dateType, $date);
        if(dao::isError()) return dao::getError();

        return $result ? 1 : 0;
    }

    /**
     * Test isFirstInference method.
     *
     * @param  string|array|null $codes
     * @access public
     * @return mixed
     */
    public function isFirstInferenceTest($codes = null)
    {
        $result = $this->objectModel->isFirstInference($codes);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getInstallDate method.
     *
     * @access public
     * @return mixed
     */
    public function getInstallDateTest()
    {
        $result = $this->objectModel->getInstallDate();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test filterCalcByEdition method.
     *
     * @param  array $calcInstances
     * @access public
     * @return mixed
     */
    public function filterCalcByEditionTest($calcInstances = null)
    {
        if($calcInstances === null)
        {
            // 默认测试数据
            $calcInstances = array();
        }
        
        $result = $this->objectModel->filterCalcByEdition($calcInstances);
        if(dao::isError()) return dao::getError();

        // 返回过滤后的实例数量，便于测试验证
        return count($result);
    }

    /**
     * Test getObjectsWithPager method.
     *
     * @param  object $metric
     * @param  array  $query
     * @param  object $pager
     * @param  array  $extra
     * @access public
     * @return mixed
     */
    public function getObjectsWithPagerTest($metric = null, $query = array(), $pager = null, $extra = array())
    {
        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getObjectsWithPager');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $metric, $query, $pager, $extra);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processDAOWithDate method.
     *
     * @param  object $stmt
     * @param  array  $query
     * @param  string $dateType
     * @access public
     * @return mixed
     */
    public function processDAOWithDateTest($stmt = null, $query = array(), $dateType = 'day')
    {
        global $tester;
        
        if($stmt === null)
        {
            $stmt = $tester->dao->select('*')->from(TABLE_METRICLIB)->where('1')->eq('1');
        }
        
        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processDAOWithDate');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $stmt, $query, $dateType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchMetricRecords method.
     *
     * @param  string $code
     * @param  array  $fieldList
     * @param  array  $query
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function fetchMetricRecordsTest($code = '', $fieldList = array(), $query = array(), $pager = null)
    {
        // 检查度量项是否存在，如果不存在则返回空数组
        $metric = $this->objectModel->getByCode($code);
        if(!$metric) return array();
        
        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('fetchMetricRecords');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $code, $fieldList, $query, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchMetricRecordsWithOption method.
     *
     * @param  string $code
     * @param  array  $fieldList
     * @param  array  $options
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function fetchMetricRecordsWithOptionTest($code = '', $fieldList = array(), $options = array(), $pager = null)
    {
        // 检查度量项是否存在，如果不存在则返回空数组
        $metric = $this->objectModel->getByCode($code);
        if(!$metric) return array();
        
        // 使用反射来调用protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('fetchMetricRecordsWithOption');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $code, $fieldList, $options, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}