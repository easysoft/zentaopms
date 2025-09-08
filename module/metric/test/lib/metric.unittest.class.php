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
}