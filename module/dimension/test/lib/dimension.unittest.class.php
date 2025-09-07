<?php
declare(strict_types = 1);
class dimensionTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('dimension');
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
        $dimensionModel = $this->objectModel;
        
        switch($testType) {
            case 'parentConstructor':
                $result->result = property_exists($dimensionModel, 'app') && !empty($dimensionModel->app);
                break;
            case 'dao':
                $result->result = property_exists($dimensionModel, 'dao') && !empty($dimensionModel->dao);
                break;
            case 'modelInstance':
                $result->result = $dimensionModel instanceof dimensionModel;
                break;
            case 'modelExists':
                $result->result = !empty($dimensionModel);
                break;
            case 'className':
                $result->result = get_class($dimensionModel);
                break;
            default:
                $result->result = 'normal';
                break;
        }
        
        return $result;
    }
}