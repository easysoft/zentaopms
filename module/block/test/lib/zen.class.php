<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class blockZenTest extends baseTest
{
    protected $moduleName = 'block';
    protected $className  = 'zen';

    /**
     * Test printBuildBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printBuildBlockTest(object $block)
    {
        $this->invokeArgs('printBuildBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->builds))
        {
            $result->count = count($view->builds);
            foreach($view->builds as $index => $build)
            {
                $result->$index = $build;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }
}
