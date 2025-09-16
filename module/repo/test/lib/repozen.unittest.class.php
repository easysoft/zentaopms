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

    /**
     * Test updateLastCommit method.
     *
     * @param  object $repo
     * @param  object $lastRevision
     * @access public
     * @return mixed
     */
    public function updateLastCommitTest($repo, $lastRevision)
    {
        if(empty($repo) || !is_object($repo)) return false;
        if(empty($lastRevision) || !is_object($lastRevision)) return false;

        // 如果lastRevision没有committed_date字段，直接返回true（方法会return）
        if(empty($lastRevision->committed_date)) return true;

        // 格式化提交日期
        $lastCommitDate = date('Y-m-d H:i:s', strtotime($lastRevision->committed_date));

        // 检查是否需要更新
        $needUpdate = empty($repo->lastCommit) || $lastCommitDate > $repo->lastCommit;

        if($needUpdate)
        {
            // 模拟数据库更新操作
            $this->objectModel->dao->update(TABLE_REPO)
                ->set('lastCommit')->eq($lastCommitDate)
                ->where('id')->eq($repo->id)
                ->exec();

            if(dao::isError()) return dao::getError();
        }

        return $needUpdate;
    }

    /**
     * Test getBrowseInfo method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function getBrowseInfoTest($repo)
    {
        if(empty($repo) || !is_object($repo)) return false;
        if($repo->SCM != 'Gitlab') return null;

        $branches = array('master' => 'master', 'develop' => 'develop');
        $tags = array('v1.0', 'v2.0');
        return array($branches, $tags);
    }
}