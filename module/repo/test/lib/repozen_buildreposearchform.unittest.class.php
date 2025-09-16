<?php
declare(strict_types = 1);
class repoZenBuildRepoSearchFormTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test buildRepoSearchForm method.
     *
     * @param  array $products
     * @param  array $projects
     * @param  int   $objectID
     * @param  string $orderBy
     * @param  int   $recPerPage
     * @param  int   $pageID
     * @param  int   $param
     * @access public
     * @return mixed
     */
    public function buildRepoSearchFormTest($products = array(), $projects = array(), $objectID = 0, $orderBy = 'id_desc', $recPerPage = 20, $pageID = 1, $param = 0)
    {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();

        // 保存原始配置
        $originalSearch = isset($this->objectModel->config->repo->search) ? $this->objectModel->config->repo->search : array();

        // 模拟配置搜索参数
        if(!isset($this->objectModel->config->repo->search)) $this->objectModel->config->repo->search = array();
        if(!isset($this->objectModel->config->repo->search['params'])) $this->objectModel->config->repo->search['params'] = array();

        $this->objectModel->config->repo->search['params']['product'] = array('values' => $products);
        $this->objectModel->config->repo->search['params']['projects'] = array('values' => $projects);
        $this->objectModel->config->repo->search['actionURL'] = "index.php?m=repo&f=maintain&objectID={$objectID}&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}&type=bySearch&param=myQueryID";
        $this->objectModel->config->repo->search['queryID'] = $param;
        $this->objectModel->config->repo->search['onMenuBar'] = 'yes';

        // 模拟search模块的setSearchParams方法
        $searchModel = $this->objectModel->loadModel('search');
        if(method_exists($searchModel, 'setSearchParams'))
        {
            $searchModel->setSearchParams($this->objectModel->config->repo->search);
        }

        if(dao::isError()) return dao::getError();

        // 验证配置是否正确设置
        $result = array(
            'productValues' => $this->objectModel->config->repo->search['params']['product']['values'],
            'projectsValues' => $this->objectModel->config->repo->search['params']['projects']['values'],
            'actionURL' => $this->objectModel->config->repo->search['actionURL'],
            'queryID' => $this->objectModel->config->repo->search['queryID'],
            'onMenuBar' => $this->objectModel->config->repo->search['onMenuBar']
        );

        // 恢复原始配置
        $this->objectModel->config->repo->search = $originalSearch;

        return $result;
    }
}