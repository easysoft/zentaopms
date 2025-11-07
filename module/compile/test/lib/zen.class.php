<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class compileZenTest extends baseTest
{
    protected $moduleName = 'compile';
    protected $className  = 'zen';

    /**
     * Test buildSearchForm method.
     *
     * @param  int $repoID
     * @param  int $jobID
     * @param  int $queryID
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest(int $repoID = 0, int $jobID = 0, int $queryID = 0)
    {
        global $tester, $lang, $app;

        // 重新初始化配置以确保测试独立性
        $app->loadLang('job');
        $app->loadLang('compile');
        $tester->config->compile->search = array();
        $tester->config->compile->search['module']                = 'compile';
        $tester->config->compile->search['fields']['name']        = $lang->compile->name;
        $tester->config->compile->search['fields']['status']      = $lang->compile->status;
        $tester->config->compile->search['fields']['repo']        = $lang->job->repo;
        $tester->config->compile->search['fields']['engine']      = $lang->compile->buildType;
        $tester->config->compile->search['fields']['triggerType'] = $lang->job->triggerType;
        $tester->config->compile->search['fields']['createdDate'] = $lang->compile->atTime;
        $tester->config->compile->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
        $tester->config->compile->search['params']['status']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->compile->statusList);
        $tester->config->compile->search['params']['repo']        = array('operator' => '=',       'control' => 'select', 'values' => array());
        $tester->config->compile->search['params']['engine']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->job->engineList);
        $tester->config->compile->search['params']['triggerType'] = array('operator' => 'include', 'control' => 'select', 'values' => $lang->job->triggerTypeList);
        $tester->config->compile->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');

        // 调用方法
        $this->invokeArgs('buildSearchForm', [$repoID, $jobID, $queryID]);
        if(dao::isError()) return dao::getError();

        // 读取配置
        $result = array();

        // 提取URL中的关键参数
        $actionURL = $tester->config->compile->search['actionURL'] ?? '';
        $result['repoID'] = 0;
        $result['jobID'] = 0;
        if(preg_match('/repoID=(-?\d+)/', $actionURL, $matches))
        {
            $result['repoID'] = (int)$matches[1];
        }
        if(preg_match('/jobID=(\d+)/', $actionURL, $matches))
        {
            $result['jobID'] = (int)$matches[1];
        }

        $result['queryID'] = $tester->config->compile->search['queryID'] ?? 0;
        $result['hasRepoField'] = isset($tester->config->compile->search['fields']['repo']) ? 1 : 0;

        return $result;
    }
}
