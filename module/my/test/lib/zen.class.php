<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class myZenTest extends baseTest
{
    protected $moduleName = 'my';
    protected $className  = 'zen';

    /**
     * Test assignRelatedData method.
     *
     * @param  array $feedbacks 反馈数据列表
     * @access public
     * @return object
     */
    public function assignRelatedDataTest($feedbacks = array())
    {
        $this->invokeArgs('assignRelatedData', [$feedbacks]);

        $view = $this->getProperty('view');
        $result = new stdClass();
        $result->hasBugs    = isset($view->bugs) && is_array($view->bugs) ? 1 : 0;
        $result->hasTodos   = isset($view->todos) && is_array($view->todos) ? 1 : 0;
        $result->hasStories = isset($view->stories) && is_array($view->stories) ? 1 : 0;
        $result->hasTasks   = isset($view->tasks) && is_array($view->tasks) ? 1 : 0;
        return $result;
    }
}
