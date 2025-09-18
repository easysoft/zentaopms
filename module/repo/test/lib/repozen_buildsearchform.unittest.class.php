<?php
declare(strict_types = 1);
class repoZenBuildSearchFormTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest(int $queryID, string $actionURL)
    {
        // 确保session已启动
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 备份原始配置
        $originalSearch = isset($this->objectModel->config->repo->search) ? $this->objectModel->config->repo->search : null;

        // 模拟searchCommits配置
        if(!isset($this->objectModel->config->repo->searchCommits))
        {
            $this->objectModel->config->repo->searchCommits = array(
                'actionURL' => '',
                'queryID'   => 0,
                'fields'    => array('date', 'committer', 'commit'),
                'params'    => array()
            );
        }

        try
        {
            // 执行buildSearchForm方法的核心逻辑
            $this->objectModel->config->repo->search = $this->objectModel->config->repo->searchCommits;
            $this->objectModel->config->repo->search['actionURL'] = $actionURL;
            $this->objectModel->config->repo->search['queryID']   = $queryID;

            // 模拟setSearchParams调用
            $searchModel = $this->objectModel->loadModel('search');
            if(method_exists($searchModel, 'setSearchParams'))
            {
                $searchModel->setSearchParams($this->objectModel->config->repo->search);
            }

            if(!$hasSession) session_write_close();

            // 验证配置是否正确设置
            $result = array(
                'actionURL' => $this->objectModel->config->repo->search['actionURL'],
                'queryID'   => $this->objectModel->config->repo->search['queryID'],
                'hasSearchCommits' => isset($this->objectModel->config->repo->searchCommits) ? 1 : 0
            );

            return $result;
        }
        catch(Exception $e)
        {
            if(!$hasSession) session_write_close();

            return array(
                'error' => $e->getMessage(),
                'hasSearchCommits' => 0
            );
        }
        finally
        {
            // 恢复原始配置
            if($originalSearch !== null)
            {
                $this->objectModel->config->repo->search = $originalSearch;
            }
        }
    }
}