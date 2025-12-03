<?php
declare(strict_types = 1);

class actionTaoTest
{
    public $objectModel;
    public $objectTao;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('action');
        $this->objectTao   = $tester->loadTao('action');
    }

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

        $this->objectTao->processLinkStoryAndBugActionExtra($action, $module, $method);
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

        $this->objectTao->processToStoryActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }
}
