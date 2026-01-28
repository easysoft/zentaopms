<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class requirementModelTest extends baseTest
{
    protected $moduleName = 'requirement';
    protected $className  = 'model';

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

        $result = $this->instance->isClickable($data, $action);
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
        $result = $this->instance->getToAndCcList($story, $actionType);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}