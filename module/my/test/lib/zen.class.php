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

    /**
     * Test buildCaseData method.
     *
     * @param  array  $cases 用例数据列表
     * @param  string $type  类型(assigntome|openedbyme)
     * @access public
     * @return array
     */
    public function buildCaseDataTest($cases = array(), $type = 'assigntome')
    {
        $result = $this->invokeArgs('buildCaseData', [$cases, $type]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchFormForFeedback method.
     *
     * @param  int    $queryID
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function buildSearchFormForFeedbackTest($queryID = 0, $orderBy = 'id_desc')
    {
        global $tester;

        if(!isset($tester->config->feedback)) $tester->config->feedback = new stdClass();

        $tester->config->feedback->search = array();
        $tester->config->feedback->search['module'] = 'feedback';
        $tester->config->feedback->search['fields'] = array('id' => 'ID', 'product' => '产品', 'module' => '模块', 'title' => '标题', 'assignedTo' => '指派给', 'processedBy' => '处理者', 'closedBy' => '关闭者', 'closedDate' => '关闭日期', 'closedReason' => '关闭原因', 'processedDate' => '处理日期', 'solution' => '解决方案', 'status' => '状态');
        $tester->config->feedback->search['params'] = array('product' => array('operator' => '=', 'control' => 'select', 'values' => array()), 'module' => array('operator' => 'belong', 'control' => 'select', 'values' => array()), 'assignedTo' => array('operator' => '=', 'control' => 'select', 'values' => array()), 'processedBy' => array('operator' => '=', 'control' => 'select', 'values' => array()), 'closedBy' => array('operator' => '=', 'control' => 'select', 'values' => array()), 'status' => array('operator' => '=', 'control' => 'select', 'values' => array()));

        $this->invokeArgs('buildSearchFormForFeedback', [$queryID, $orderBy]);
        if(dao::isError()) return dao::getError();

        $result = new stdClass();
        $result->queryID = $tester->config->feedback->search['queryID'] ?? 0;
        $result->module = $tester->config->feedback->search['module'] ?? '';
        $result->hasActionURL = isset($tester->config->feedback->search['actionURL']) && !empty($tester->config->feedback->search['actionURL']) ? 1 : 0;

        return $result;
    }
}
