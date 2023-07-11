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
        include_once $this->objectModel->metricTao->getBaseCalcPath();
        include_once $this->objectModel->getCalcRoot() . $scope . DS . $purpose . DS . $code . '.php';

        $calc = new $code;
        $rows = $this->prepareDataset($calc)->fetchAll();

        foreach($rows as $row)
        {
            $calc->calculate((object)$row);
        }

        return $calc;
    }

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
