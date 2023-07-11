<?php
class metricTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('metric');
        $this->tester = $tester;
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
     * @param  string    $scope
     * @param  string    $purpose
     * @param  string    $code
     * @param  array    $rows
     * @access public
     * @return object
     */
    public function calcMetric($scope, $purpose, $code)
    {
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
            return $calc->getStatement($tester->dao);
        }

        $dataset   = $this->objectModel->getDataset($tester->dao);
        $fieldList = implode(',', array_unique($calc->fieldList));

        return $dataset->$dataSource($fieldList);
    }
}
