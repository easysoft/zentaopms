<?php
class projectZenTest
{
    public $projectZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('project');
        $tester->loadModel('project');

        $this->projectZenTest = initReference('project');
    }

    /**
     * 构建POST数据。
     * Build POST data.
     *
     * @param  int    $testData
     * @access public
     * @return array
     */
    public function buildPostData($testData)
    {
        $origData = array(
            'storyType' => array('story'),
            'parent' => 0,
            'charter' => '',
            'model' => 'scrum',
            'hasProduct' => 1,
            'workflowGroup' => 2,
            'budget' => '',
            'multiple' => 'on',
            'name' => 'name1',
            'PM' => '',
            'begin' => '2025-07-07',
            'end' => '',
            'days' => '',
            'productName' => '',
            'products' => array(''),
            'branch' => array(array('')),
            'plans' => array(array('')),
            'desc' => '',
            'budgetUnit' => 'CNY',
            'linkType' => 'plan',
            'deliverable' => array('new_0' => array('name' => '', 'doc' => '', 'fileID' => '')),
            'acl' => 'open',
            'whitelist' => array(''),
            'contactList' => '',
            'auth' => 'extend',
            'taskDateLimit' => 'auto'
        );

        return array_merge($origData, $testData);
    }

    /**
     * 测试prepareCreateExtras方法。
     * Test prepareCreateExtras method.
     *
     * @param  int    $testData
     * @param  int    $expect
     * @access public
     * @return array|bool
     */
    public function prepareCreateExtrasTest($testData, $expect)
    {
        $_POST    = $this->buildPostData($testData);
        $postData = form::data($this->tester->config->project->form->create);
        $method   = $this->projectZenTest->getMethod('prepareCreateExtras');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->projectZenTest->newInstance(), [$postData, $expect]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
