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

        $this->objectModel    = $tester->loadModel('project');
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

    /**
     * 格式化导出的项目数据。
     * Format the export project data.
     *
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function formatExportProjectsTest($status, $orderBy)
    {
        $method = $this->projectZenTest->getMethod('formatExportProjects');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->projectZenTest->newInstance(), [$status, $orderBy]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 设置编辑页面变量。
     * Send variables to edit page.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function buildEditFormTest(int $projectID): object
    {
        global $config, $tester;
        $config->project->unitList = '';
        $project = $this->objectModel->fetchByID($projectID);

        return callZenMethod('project', 'buildEditForm', [$projectID, $project], 'view');
    }

    /**
     * 处理项目列表展示数据。
     * Process project list display data.
     *
     * @access public
     * @return array
     */
    public function processProjectListDataTest(): array
    {
        $projectList = $this->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchAll('id');
        return callZenMethod('project', 'processProjectListData', [$projectList]);
    }

    /**
     * 测试检查工作日是否合法。
     * Test checkWorkdaysLegtimate method.
     *
     * @param  object $project
     * @access public
     * @return bool
     */
    public function checkWorkdaysLegtimateTest($project): bool
    {
        $method = $this->projectZenTest->getMethod('checkWorkdaysLegtimate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->projectZenTest->newInstance(), [$project]);
        return $result;
    }
}
