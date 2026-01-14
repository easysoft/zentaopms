<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class actionTaoTest extends baseTest
{
    protected $moduleName = 'action';
    protected $className  = 'tao';

    /**
     * Test processLinkStoryAndBugActionExtra method.
     *
     * @param  string $ids
     * @param  string $module
     * @param  string $method
     * @access public
     * @return object
     */
    public function processLinkStoryAndBugActionExtraTest(string $ids, string $module = 'story', string $method = 'view'): object
    {
        $action = new stdClass();
        $action->extra = $ids;

        $this->instance->processLinkStoryAndBugActionExtra($action, $module, $method);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processToStoryActionExtra method.
     *
     * @param  int    $storyID
     * @param  string $product
     * @access public
     * @return object
     */
    public function processToStoryActionExtraTest(int $storyID, string $product = '1'): object
    {
        $action = new stdClass();
        $action->extra = (string)$storyID;
        $action->product = $product;

        $this->instance->processToStoryActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processCreateChildrenActionExtra method.
     *
     * @param  string $taskIDs
     * @access public
     * @return object
     */
    public function processCreateChildrenActionExtraTest(string $taskIDs): object
    {
        $action = new stdClass();
        $action->extra = $taskIDs;

        $this->instance->processCreateChildrenActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }
}
