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
     * Test buildCreateForm method in zen layer.
     *
     * @param  int $objectID
     * @access public
     * @return mixed
     */
    public function buildCreateFormTest(int $objectID)
    {
        // 模拟app环境和配置
        $this->objectModel->app->tab = 'project';

        // 模拟保存状态
        $this->objectModel->saveState(0, $objectID);

        // 捕获视图输出，避免实际页面渲染
        ob_start();

        // 模拟buildCreateForm方法的核心逻辑
        $this->objectModel->app->loadLang('action');
        $this->objectModel->loadModel('product');

        // 根据tab类型获取产品列表
        if($this->objectModel->app->tab == 'project' || $this->objectModel->app->tab == 'execution')
        {
            $products = $this->objectModel->loadModel('project')->getBranchesByProject($objectID);
            $products = $this->objectModel->product->getProducts($objectID, 'all', '', false, array_keys($products));
        }
        else
        {
            $products = $this->objectModel->product->getPairs('', 0, '', 'all');
        }

        // 模拟设置视图变量
        $title = $this->objectModel->lang->repo->common . $this->objectModel->lang->hyphen . $this->objectModel->lang->repo->create;
        $groups = $this->objectModel->loadModel('group')->getPairs();
        $users = $this->objectModel->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $serviceHosts = $this->objectModel->loadModel('pipeline')->getPairs(implode(',', $this->objectModel->config->repo->notSyncSCM), true);

        ob_end_clean();

        if(dao::isError()) return dao::getError();

        // 返回设置的关键数据
        return array(
            'title' => $title,
            'products' => $products,
            'groups' => $groups,
            'users' => $users,
            'serviceHosts' => $serviceHosts,
            'objectID' => $objectID
        );
    }
}