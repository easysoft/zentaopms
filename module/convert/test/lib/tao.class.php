<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class MockWorkflowHook
{
    public function check($hook)
    {
        return array('SELECT * FROM zt_bug', array());
    }
}

class convertTaoTest extends baseTest
{
    protected $moduleName = 'convert';
    protected $className  = 'tao';

    /**
     * Test importJiraIssue method.
     *
     * @param  array $dataList
     * @access public
     * @return mixed
     */
    public function importJiraIssueTest($dataList = array())
    {
        $result = $this->invokeArgs('importJiraIssue', array($dataList));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processJiraContent method.
     *
     * @param  string $content
     * @param  array  $fileList
     * @access public
     * @return string
     */
    public function processJiraContentTest($content = '', $fileList = array())
    {
        $result = $this->invokeArgs('processJiraContent', array($content, $fileList));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processWorkflowHooks method.
     *
     * @param  array  $jiraAction
     * @param  array  $jiraStepList
     * @param  string $module
     * @access public
     * @return array
     */
    public function processWorkflowHooksTest($jiraAction = array(), $jiraStepList = array(), $module = '')
    {
        global $app;

        $convertTaoFile = $app->getAppRoot() . 'module/convert/tao.php';
        if(file_exists($convertTaoFile))
        {
            include_once $convertTaoFile;
            $convertTao = new convertTao();

            // Mock workflowhook对象
            $convertTao->workflowhook = new MockWorkflowHook();

            $reflection = new ReflectionClass($convertTao);
            $method = $reflection->getMethod('processWorkflowHooks');
            $method->setAccessible(true);

            $result = $method->invokeArgs($convertTao, array($jiraAction, $jiraStepList, $module));
            if(dao::isError()) return dao::getError();
            return $result;
        }

        return array();
    }
}
