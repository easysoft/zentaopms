<?php
declare(strict_types = 1);
class repoZenTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test buildTaskSearchForm method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $modules
     * @param  array     $executionPairs
     * @access public
     * @return mixed
     */
    public function buildTaskSearchFormTest(int $repoID, string $revision, string $browseType, int $queryID, array $modules, array $executionPairs)
    {
        if(dao::isError()) return dao::getError();

        // 构建搜索配置
        $searchConfig = array();
        $searchConfig['actionURL'] = helper::createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID", '', true);
        $searchConfig['queryID'] = $queryID;
        $searchConfig['style'] = 'simple';

        // 设置搜索参数
        $searchParams = array();
        $searchParams['module']['values'] = $modules;
        $searchParams['execution']['values'] = array('' => '') + $executionPairs;

        // 加载search模块
        $this->objectModel->loadModel('search');

        return array(
            'result' => 'success',
            'actionURL' => $searchConfig['actionURL'],
            'queryID' => $searchConfig['queryID'],
            'style' => $searchConfig['style'],
            'moduleCount' => count($searchParams['module']['values']),
            'executionCount' => count($searchParams['execution']['values']) - 1, // 减去空选项
            'searchParams' => $searchParams
        );
    }
}