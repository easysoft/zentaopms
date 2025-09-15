<?php
declare(strict_types = 1);
class requirementTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('requirement');
    }

    /**
     * Test isClickable method.
     *
     * @param  mixed $data
     * @param  mixed $action
     * @access public
     * @return mixed
     */
    public function isClickableTest($data = null, $action = null)
    {
        global $tester, $app;
        
        // Initialize $app->control if not set
        if(empty($app->control)) {
            $app->control = $tester;
        }
        
        $result = $this->objectModel->isClickable($data, $action);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getToAndCcList method.
     *
     * @param  object $story
     * @param  string $actionType
     * @access public
     * @return mixed
     */
    public function getToAndCcListTest($story, $actionType)
    {
        $result = $this->objectModel->getToAndCcList($story, $actionType);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}