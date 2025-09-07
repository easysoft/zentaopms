<?php
declare(strict_types = 1);
class dataviewTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('dataview');
        $this->objectTao   = $tester->loadTao('dataview');
    }

    /**
     * Test __construct method.
     *
     * @param  string $testType
     * @access public
     * @return object
     */
    public function __constructTest(string $testType = 'normal'): object
    {
        $result = new stdClass();
        $dataviewModel = $this->objectModel;
        
        switch($testType) {
            case 'biModel':
                $result->result = property_exists($dataviewModel, 'bi') && !empty($dataviewModel->bi);
                break;
            case 'parentConstructor':
                $result->result = property_exists($dataviewModel, 'app') && !empty($dataviewModel->app);
                break;
            case 'dao':
                $result->result = property_exists($dataviewModel, 'dao') && !empty($dataviewModel->dao);
                break;
            case 'modelInstance':
                $result->result = $dataviewModel instanceof dataviewModel;
                break;
            case 'modelExists':
                $result->result = !empty($dataviewModel);
                break;
            case 'className':
                $result->result = get_class($dataviewModel);
                break;
            default:
                $result->result = 'normal';
                break;
        }
        
        return $result;
    }
}