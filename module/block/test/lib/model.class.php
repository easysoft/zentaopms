<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class blockModelTest extends baseTest
{
    protected $moduleName = 'block';
    protected $className  = 'model';

    /**
     * Test getModelType4Projects method.
     *
     * @param  array $projectIdList
     * @access public
     * @return string
     */
    public function getModelType4ProjectsTest($projectIdList = array())
    {
        $result = $this->invokeArgs('getModelType4Projects', array($projectIdList));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
