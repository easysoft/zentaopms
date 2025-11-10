<?php
class metricZenTest
{
    public $metricZenTest;
    public $tester;
    
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('metric');
        $tester->loadModel('metric');

        $this->metricZenTest = initReference('metric');
    }

    /**
     * Test buildMetricForCreate method.
     *
     * @access public
     * @return mixed
     */
    public function buildMetricForCreateZenTest()
    {
        $method = $this->metricZenTest->getMethod('buildMetricForCreate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildMetricForEdit method.
     *
     * @access public
     * @return mixed
     */
    public function buildMetricForEditZenTest()
    {
        $method = $this->metricZenTest->getMethod('buildMetricForEdit');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterCreate method.
     *
     * @param  int    $metricID
     * @param  string $afterCreate
     * @param  string $from
     * @param  string $location
     * @access public
     * @return mixed
     */
    public function responseAfterCreateZenTest($metricID, $afterCreate, $from, $location = '')
    {
        $method = $this->metricZenTest->getMethod('responseAfterCreate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($metricID, $afterCreate, $from, $location));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterEdit method.
     *
     * @param  int    $metricID
     * @param  string $afterEdit
     * @param  string $location
     * @access public
     * @return mixed
     */
    public function responseAfterEditZenTest($metricID, $afterEdit, $location = '')
    {
        $method = $this->metricZenTest->getMethod('responseAfterEdit');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($metricID, $afterEdit, $location));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareDataset method.
     *
     * @param  object $calcGroup
     * @access public
     * @return mixed
     */
    public function prepareDatasetZenTest($calcGroup)
    {
        $method = $this->metricZenTest->getMethod('prepareDataset');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($calcGroup));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareTree method.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  array  $modules
     * @access public
     * @return mixed
     */
    public function prepareTreeZenTest($scope, $stage, $modules)
    {
        $method = $this->metricZenTest->getMethod('prepareTree');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($scope, $stage, $modules));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareScopeList method.
     *
     * @access public
     * @return mixed
     */
    public function prepareScopeListZenTest()
    {
        $method = $this->metricZenTest->getMethod('prepareScopeList');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test startTime method.
     *
     * @access public
     * @return mixed
     */
    public function startTimeZenTest()
    {
        $method = $this->metricZenTest->getMethod('startTime');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test endTime method.
     *
     * @param  float $beginTime
     * @access public
     * @return mixed
     */
    public function endTimeZenTest($beginTime)
    {
        $method = $this->metricZenTest->getMethod('endTime');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($beginTime));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getValidObjects method.
     *
     * @access public
     * @return mixed
     */
    public function getValidObjectsZenTest()
    {
        $method = $this->metricZenTest->getMethod('getValidObjects');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test calculateMetric method.
     *
     * @param  array $classifiedCalcGroup
     * @access public
     * @return mixed
     */
    public function calculateMetricZenTest($classifiedCalcGroup)
    {
        $method = $this->metricZenTest->getMethod('calculateMetric');
        $method->setAccessible(true);

        ob_start();
        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($classifiedCalcGroup));
        ob_get_clean();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareMetricRecord method.
     *
     * @param  array $calcList
     * @access public
     * @return mixed
     */
    public function prepareMetricRecordZenTest($calcList)
    {
        $method = $this->metricZenTest->getMethod('prepareMetricRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($calcList));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareReuseMetricResult method.
     *
     * @param  object $calc
     * @param  array  $options
     * @access public
     * @return mixed
     */
    public function prepareReuseMetricResultZenTest($calc, $options)
    {
        $method = $this->metricZenTest->getMethod('prepareReuseMetricResult');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($calc, $options));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getRecordByCodeAndDate method.
     *
     * @param  string $code
     * @param  object $calc
     * @param  string $date
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function getRecordByCodeAndDateZenTest($code, $calc, $date, $type = 'single')
    {
        $method = $this->metricZenTest->getMethod('getRecordByCodeAndDate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($code, $calc, $date, $type));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initMetricRecords method.
     *
     * @param  object $recordCommon
     * @param  string $scope
     * @param  string $date
     * @access public
     * @return mixed
     */
    public function initMetricRecordsZenTest($recordCommon, $scope, $date = 'now')
    {
        $method = $this->metricZenTest->getMethod('initMetricRecords');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($recordCommon, $scope, $date));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildRecordCommonFields method.
     *
     * @param  int    $metricID
     * @param  string $code
     * @param  string $date
     * @param  array  $dateValues
     * @access public
     * @return mixed
     */
    public function buildRecordCommonFieldsZenTest($metricID, $code, $date, $dateValues)
    {
        $method = $this->metricZenTest->getMethod('buildRecordCommonFields');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($metricID, $code, $date, $dateValues));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test completeMissingRecords method.
     *
     * @param  array $records
     * @param  object $metric
     * @access public
     * @return mixed
     */
    public function completeMissingRecordsZenTest($records, $metric)
    {
        $method = $this->metricZenTest->getMethod('completeMissingRecords');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($records, $metric));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test calcMetric method.
     *
     * @param  object $statement
     * @param  array  $calcList
     * @access public
     * @return mixed
     */
    public function calcMetricZenTest($statement, $calcList)
    {
        try {
            $method = $this->metricZenTest->getMethod('calcMetric');
            $method->setAccessible(true);

            $method->invokeArgs($this->metricZenTest->newInstance(), array($statement, $calcList));
            if(dao::isError()) return dao::getError();
            return true;
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage();
        } catch(Error $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Test getCalcFields method.
     *
     * @param  object $calc
     * @param  object $row
     * @access public
     * @return mixed
     */
    public function getCalcFieldsZenTest($calc, $row)
    {
        $method = $this->metricZenTest->getMethod('getCalcFields');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($calc, $row));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBasicInfo method.
     *
     * @param  object $view
     * @param  string $fields
     * @access public
     * @return mixed
     */
    public function getBasicInfoZenTest($view, $fields = 'scope,object,purpose,dateType,name,alias,code,unit,stage')
    {
        $method = $this->metricZenTest->getMethod('getBasicInfo');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($view, $fields));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getCreateEditInfo method.
     *
     * @param  object $view
     * @param  string $fields
     * @access public
     * @return mixed
     */
    public function getCreateEditInfoZenTest($view, $fields = 'createdBy,implementedBy,offlineBy,lastEdited')
    {
        $method = $this->metricZenTest->getMethod('getCreateEditInfo');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($view, $fields));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOldMetricInfo method.
     *
     * @param  int $oldMetricID
     * @access public
     * @return mixed
     */
    public function getOldMetricInfoZenTest($oldMetricID)
    {
        $method = $this->metricZenTest->getMethod('getOldMetricInfo');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($oldMetricID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processWeekConf method.
     *
     * @param  string $dateConf
     * @access public
     * @return mixed
     */
    public function processWeekConfZenTest($dateConf)
    {
        $method = $this->metricZenTest->getMethod('processWeekConf');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($dateConf));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getViewTableWidth method.
     *
     * @param  array $headers
     * @access public
     * @return mixed
     */
    public function getViewTableWidthZenTest($headers)
    {
        $method = $this->metricZenTest->getMethod('getViewTableWidth');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($headers));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getPagerExtra method.
     *
     * @param  int $tableWidth
     * @access public
     * @return mixed
     */
    public function getPagerExtraZenTest($tableWidth)
    {
        $method = $this->metricZenTest->getMethod('getPagerExtra');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($tableWidth));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test formatException method.
     *
     * @param  mixed $exception
     * @access public
     * @return mixed
     */
    public function formatExceptionZenTest($exception)
    {
        $method = $this->metricZenTest->getMethod('formatException');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($exception));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processUnitList method.
     *
     * @access public
     * @return mixed
     */
    public function processUnitListZenTest()
    {
        $method = $this->metricZenTest->getMethod('processUnitList');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareActionPriv method.
     *
     * @param  array $metrics
     * @access public
     * @return mixed
     */
    public function prepareActionPrivZenTest($metrics)
    {
        $method = $this->metricZenTest->getMethod('prepareActionPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($metrics));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getUniqueKeyByRecord method.
     *
     * @param  object $record
     * @param  string $scope
     * @access public
     * @return mixed
     */
    public function getUniqueKeyByRecordZenTest($record, $scope = '')
    {
        $method = $this->metricZenTest->getMethod('getUniqueKeyByRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($record, $scope));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}