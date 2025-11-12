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

    /**
     * Test buildTaskData method.
     *
     * @param  array $tasks 任务数据列表
     * @access public
     * @return array
     */
    public function buildTaskDataTest($tasks = array())
    {
        $result = $this->invokeArgs('buildTaskData', [$tasks]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test showWorkCount method.
     *
     * @param  int $recTotal   总记录数
     * @param  int $recPerPage 每页记录数
     * @param  int $pageID     页码
     * @access public
     * @return object
     */
    public function showWorkCountTest($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        ob_start();
        $this->invokeArgs('showWorkCount', [$recTotal, $recPerPage, $pageID]);
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');
        $result = new stdClass();
        $result->hasTodoCount = isset($view->todoCount) && is_array($view->todoCount) ? 1 : 0;
        $result->taskCount = isset($view->todoCount['task']) ? $view->todoCount['task'] : -1;
        $result->storyCount = isset($view->todoCount['story']) ? $view->todoCount['story'] : -1;
        $result->bugCount = isset($view->todoCount['bug']) ? $view->todoCount['bug'] : -1;
        $result->caseCount = isset($view->todoCount['case']) ? $view->todoCount['case'] : -1;
        $result->testtaskCount = isset($view->todoCount['testtask']) ? $view->todoCount['testtask'] : -1;
        $result->isOpenedURAndSR = isset($view->isOpenedURAndSR) ? $view->isOpenedURAndSR : 0;

        return $result;
    }

    /**
     * Test showWorkCountNotInOpen method.
     *
     * @param  array  $count 待处理工作数量数组
     * @access public
     * @return object
     */
    public function showWorkCountNotInOpenTest($count = array())
    {
        /* Create a mock pager object. */
        $pager = new stdClass();
        $pager->recTotal = 0;
        $pager->recPerPage = 20;
        $pager->pageID = 1;

        $result = $this->invokeArgs('showWorkCountNotInOpen', [$count, $pager]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');
        $output = new stdClass();
        $output->task = isset($result['task']) ? $result['task'] : 0;
        $output->story = isset($result['story']) ? $result['story'] : 0;
        $output->bug = isset($result['bug']) ? $result['bug'] : 0;
        $output->case = isset($result['case']) ? $result['case'] : 0;
        $output->testtask = isset($result['testtask']) ? $result['testtask'] : 0;
        $output->requirement = isset($result['requirement']) ? $result['requirement'] : 0;
        $output->issue = isset($result['issue']) ? $result['issue'] : 0;
        $output->risk = isset($result['risk']) ? $result['risk'] : 0;
        $output->qa = isset($result['qa']) ? $result['qa'] : 0;
        $output->meeting = isset($result['meeting']) ? $result['meeting'] : 0;
        $output->ticket = isset($result['ticket']) ? $result['ticket'] : 0;
        $output->feedback = isset($result['feedback']) ? $result['feedback'] : 0;
        $output->isBiz = isset($view->isBiz) ? $view->isBiz : 0;
        $output->isMax = isset($view->isMax) ? $view->isMax : 0;
        $output->isIPD = isset($view->isIPD) ? $view->isIPD : 0;

        return $output;
    }
}
