<?php
declare(strict_types = 1);
class repoTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test processRepoID method.
     *
     * @param  int       $repoID
     * @param  int       $objectID
     * @param  array     $scmList
     * @access public
     * @return int
     */
    public function processRepoIDTest(int $repoID, int $objectID, array $scmList = array()): int
    {
        if(dao::isError()) return dao::getError();

        // 检查session状态
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 如果没有传入repoID，从session获取
        if(!$repoID)
        {
            $repoID = isset($this->objectModel->session->repoID) ? (int)$this->objectModel->session->repoID : 1;
        }

        $repoPairs = array();

        // 模拟当前tab是project或execution
        if($this->objectModel->app->tab == 'project' || $this->objectModel->app->tab == 'execution')
        {
            if(!$scmList) $scmList = $this->objectModel->config->repo->notSyncSCM;

            // 获取代码库列表
            $repoList = $this->objectModel->getList($objectID);
            foreach($repoList as $repo)
            {
                if(!in_array($repo->SCM, $scmList)) continue;
                $repoPairs[$repo->id] = $repo->name;
            }

            // 检查repoID是否在列表中，如果不在则使用第一个
            if(!isset($repoPairs[$repoID]))
            {
                $repoID = !empty($repoPairs) ? (int)key($repoPairs) : $repoID;
            }
        }

        // 模拟设置视图数据
        $this->objectModel->view = new stdClass();
        $this->objectModel->view->repoID = $repoID;
        $this->objectModel->view->repoPairs = $repoPairs;

        // 保存状态
        $repoID = $this->objectModel->saveState($repoID, $objectID);

        if(!$hasSession) session_write_close();

        return $repoID;
    }
}