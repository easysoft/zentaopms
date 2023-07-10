<?php
class metricTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('metric');
        $tihs->tester = $tester;
    }

    /**
     * Get metric func root.
     *
     * @access public
     * @return string
     */
    public function getCalcRoot()
    {
        return $this->tester->app->getModuleRoot() . 'metric' . DS . 'calc' . DS;
    }

    public function getData($name)
    {
        $dataRoot = $this->tester->app->getModuleRoot() . 'metric' . DS . 'test' . DS . 'data' . DS;
        $jsonString = file_get_contents($dataRoot . name . '.json');

        $data = json_decode($jsonString, true);

        return $jsonString;
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
    public function calcMetric($scope, $purpose, $code, $rows)
    {
        require_once $this->tester->app->getModuleRoot() . DS . 'metric' . DS . 'calc.class.php';

        print_r(baseCalc);
        die;
        require_once $this->getCalcRoot() . $scope . DS . $purpose . DS . $code . '.php';
        return;

        $calc = new $code;

        foreach($rows as $row)
        {
            $calc->calculate($row);
        }

        return $calc;
    }
}
