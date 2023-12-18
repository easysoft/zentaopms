<?php
class metricTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('metric');
        $this->tester = $tester;
        $this->initMetric();
    }

    /**
     * 运行sql重置zt_metric表。
     * Truncate zt_metric and insert data again.
     *
     * @access public
     * @return void
     */
    public function initMetric()
    {
        global $tester,$app;
        $appPath = $app->getAppRoot();
        $sqlFile = $appPath . 'test/data/metric.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
    }

    /**
     * 测试getByID方法。
     * Test getByID method.
     *
     * @param  int          $metricID
     * @param  array|string $fieldList
     * @access public
     * @return void
     */
    public function getByID($metricID, $fieldList = '*')
    {
        return $this->objectModel->getByID($metricID, $fieldList);
    }

    /**
     * 测试getByID方法。
     * Test getByID method.
     *
     * @param  string       $code
     * @param  array|string $fieldList
     * @access public
     * @return void
     */
    public function getByCode($code, $fieldList = '*')
    {
        return $this->objectModel->getByCode($code, $fieldList);
    }

    /**
     * 测试getByList方法。
     * Test getByList method.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  int    $param
     * @param  string $sort
     * @access public
     * @return array|false
     */
    public function getList($scope, $stage = 'all', $sort = 'id_desc')
    {
        return $this->objectModel->getList($scope, $stage, $param, '', 0, $sort);
    }

    /**
     * 测试getScopePairs方法。
     * Test getScopePairs method.
     *
     * @param  bool   $all
     * @access public
     * @return array
     */
    public function getScopePairs($all = true)
    {
        return $this->objectModel->getScopePairs($all);
    }

    /**
     * 测试根据code获取度量项数据方法。
     * Test get metric by code.
     *
     * @param  string     $code
     * @param  array|null $options
     * @access public
     * @return void
     */
    public function getMetricByCode($code, $options = null)
    {
        return $this->objectModel->getResultByCode($code, $options);
    }

    /**
     * 获取度量项基类文件的路径。
     * Get path of base calculator class.
     *
     * @access private
     * @return string
     */
    private function getBaseCalcPath()
    {
        global $tester;
        return $tester->app->getModuleRoot() . 'metric' . DS . 'calc.class.php';
    }

    /**
     * 计算度量项，并返回计算后的度量项对象。
     * Calculate metric.
     *
     * @param  string $file
     * @access public
     * @return object
     */
    public function calcMetric($file)
    {
        $code    = pathinfo($file, PATHINFO_FILENAME);
        $purpose = basename(dirname($file));
        $scope   = basename(dirname($file, 2));

        include_once $this->getBaseCalcPath();
        include_once $this->objectModel->getCalcRoot() . $scope . DS . $purpose . DS . $code . '.php';

        $calc = new $code;
        $rows = $this->prepareDataset($calc)->fetchAll();

        foreach($rows as $row)
        {
            $calc->calculate((object)$row);
        }

        return $calc;
    }

    /**
     * 准备计算度量项所需要的数据源。
     * Prepare dataset object for calc.
     *
     * @param  object    $calc
     * @access public
     * @return void
     */
    public function prepareDataset($calc)
    {
        global $tester;
        $dataSource = $calc->dataset;

        if(!isset($calc->dataset))
        {
            $calc->setDAO($tester->dao);
            return $calc->getStatement();
        }

        $dataset   = $this->objectModel->getDataset($tester->dao);
        $fieldList = implode(',', array_unique($calc->fieldList));

        return $dataset->$dataSource($fieldList);
    }
}
