<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class buildZenTest extends baseTest
{
    protected $moduleName = 'build';
    protected $className  = 'zen';

    /**
     * Test assignBugVarsForView method.
     *
     * @param  object    $build
     * @param  string    $type
     * @param  string    $sort
     * @param  string    $param
     * @param  object    $bugPager
     * @param  object    $generatedBugPager
     * @access public
     * @return array
     */
    public function assignBugVarsForViewTest($build = null, $type = '', $sort = '', $param = '', $bugPager = null, $generatedBugPager = null)
    {
        if($bugPager === null)
        {
            $bugPager = new stdclass();
            $bugPager->recTotal  = 0;
            $bugPager->pageTotal = 1;
        }

        if($generatedBugPager === null)
        {
            $generatedBugPager = new stdclass();
            $generatedBugPager->recTotal  = 0;
            $generatedBugPager->pageTotal = 1;
        }

        $this->invokeArgs('assignBugVarsForView', [$build, $type, $sort, $param, $bugPager, $generatedBugPager]);

        if(dao::isError()) return dao::getError();

        return array(
            'type'              => $this->instance->view->type ?? '',
            'param'             => $this->instance->view->param ?? '',
            'bugs'              => $this->instance->view->bugs ?? array(),
            'generatedBugs'     => $this->instance->view->generatedBugs ?? array(),
            'hasBugPager'       => isset($this->instance->view->bugPager),
            'hasGeneratedPager' => isset($this->instance->view->generatedBugPager)
        );
    }
}
