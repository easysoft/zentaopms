<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class convertModelTest extends baseTest
{
    protected $moduleName = 'convert';
    protected $className  = 'model';

    /**
     * Test getZentaoFields method.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getZentaoFieldsTest(string $module = ''): array
    {
        $result = $this->invokeArgs('getZentaoFields', [$module]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getZentaoObjectList method.
     *
     * @access public
     * @return array
     */
    public function getZentaoObjectListTest(): array
    {
        $result = $this->invokeArgs('getZentaoObjectList', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkDBName method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function checkDBNameTest($dbName = null)
    {
        $result = $this->invokeArgs('checkDBName', [$dbName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkImportJira method.
     *
     * @param  string $step
     * @param  array  $postData
     * @access public
     * @return mixed
     */
    public function checkImportJiraTest($step = '', $postData = array())
    {
        $result = $this->invokeArgs('checkImportJira', [$step, $postData]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test convertStage method.
     *
     * @param  string $jiraStatus
     * @param  string $issueType
     * @param  array  $relations
     * @access public
     * @return mixed
     */
    public function convertStageTest($jiraStatus = '', $issueType = '', $relations = array())
    {
        $originalJiraRelation = $this->instance->app->session->jiraRelation;

        // 如果没有提供relations参数，设置测试session数据
        if(empty($relations))
        {
            $testRelations = array(
                'zentaoStage1' => array(
                    'open' => 'wait',
                    'in-progress' => 'developing',
                    'done' => 'developed',
                    'closed' => 'closed'
                ),
                'zentaoStage2' => array(
                    'to-do' => 'wait',
                    'in-progress' => 'developing',
                    'done' => 'developed'
                )
            );
            $this->instance->app->session->set('jiraRelation', json_encode($testRelations));
        }

        $result = $this->invokeArgs('convertStage', [$jiraStatus, $issueType, $relations]);
        if(dao::isError()) return dao::getError();

        $this->instance->app->session->set('jiraRelation', $originalJiraRelation);

        return $result;
    }

    /**
     * Test convertStatus method.
     *
     * @param  string $objectType
     * @param  string $jiraStatus
     * @param  string $issueType
     * @param  array  $relations
     * @access public
     * @return mixed
     */
    public function convertStatusTest($objectType = '', $jiraStatus = '', $issueType = '', $relations = array())
    {
        $this->instance->app->loadConfig('testcase');
        $this->instance->app->loadConfig('feedback');

        // 备份原始session数据和配置
        $originalJiraRelation       = $this->instance->app->session->jiraRelation;
        $originalTestcaseNeedReview = $this->instance->config->testcase->needReview;
        $originalFeedbackNeedReview = $this->instance->config->feedback->needReview;
        $originalFeedbackTicket     = $this->instance->config->feedback->ticket;

        // 如果没有提供relations参数，设置测试session数据
        if(empty($relations))
        {
            $testRelations = array(
                'zentaoStatus1' => array(
                    'open' => 'active',
                    'in-progress' => 'doing',
                    'done' => 'closed',
                    'resolved' => 'resolved'
                ),
                'zentaoStatus2' => array(
                    'to-do' => 'wait',
                    'in-progress' => 'doing',
                    'done' => 'done'
                )
            );
            $this->instance->app->session->set('jiraRelation', json_encode($testRelations));
        }

        $result = $this->invokeArgs('convertStatus', [$objectType, $jiraStatus, $issueType, $relations]);
        if(dao::isError()) return dao::getError();

        // 恢复原始session数据和配置
        $this->instance->app->session->set('jiraRelation', $originalJiraRelation);
        $this->instance->config->testcase->needReview = $originalTestcaseNeedReview;
        $this->instance->config->feedback->needReview = $originalFeedbackNeedReview;
        $this->instance->config->feedback->ticket = $originalFeedbackTicket;

        return $result;
    }

    /**
     * Test createTmpTable4Jira method.
     *
     * @access public
     * @return mixed
     */
    public function createTmpTable4JiraTest()
    {
        try
        {
            // 先删除可能存在的表
            $this->instance->dao->exec("DROP TABLE IF EXISTS `jiratmprelation`");

            $this->invokeArgs('createTmpTable4Jira');
            if(dao::isError()) return dao::getError();

            // 检查表是否创建成功
            return $this->instance->dao->descTable('jiratmprelation');
        }
        catch(Exception $e)
        {
            return 'exception: ' . $e->getMessage();
        }
    }

    /**
     * Test dbExists method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function dbExistsTest($dbName)
    {
        $result = $this->invokeArgs('dbExists', [$dbName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
