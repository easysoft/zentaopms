<?php
declare(strict_types = 1);
class repoZenGetNotExistReposTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getNotExistRepos method.
     *
     * @param  mixed $server
     * @access public
     * @return mixed
     */
    public function getNotExistReposTest($server)
    {
        // 如果server为空，直接返回空数组
        if(empty($server)) return array();

        $repoList = array();

        // 模拟GitLab项目数据
        $mockGitlabProjects = array();
        if(!empty($server) && isset($server->id)) {
            // 根据服务器ID返回不同的模拟数据
            switch($server->id) {
                case 1:
                    // 部分项目已存在
                    $project1 = new stdClass();
                    $project1->id = 101;
                    $project1->name = 'project1';
                    $project1->nameWithNamespace = 'group/project1';
                    $project1->name_with_namespace = 'group/project1';

                    $project2 = new stdClass();
                    $project2->id = 102;
                    $project2->name = 'project2';
                    $project2->nameWithNamespace = 'group/project2';
                    $project2->name_with_namespace = 'group/project2';

                    $mockGitlabProjects = array($project1, $project2);
                    break;
                case 2:
                    // 项目全部已存在 - 使用存在于数据库中的serviceProject ID
                    $project1 = new stdClass();
                    $project1->id = 4; // 这个ID在数据库中存在
                    $project1->name = 'repo4';
                    $project1->nameWithNamespace = 'group/repo4';
                    $project1->name_with_namespace = 'group/repo4';

                    $mockGitlabProjects = array($project1);
                    break;
                case 3:
                    // 项目全部不存在
                    $project1 = new stdClass();
                    $project1->id = 201;
                    $project1->name = 'newproject1';
                    $project1->nameWithNamespace = 'group/newproject1';
                    $project1->name_with_namespace = 'group/newproject1';

                    $project2 = new stdClass();
                    $project2->id = 202;
                    $project2->name = 'newproject2';
                    $project2->nameWithNamespace = 'group/newproject2';
                    $project2->name_with_namespace = 'group/newproject2';

                    $mockGitlabProjects = array($project1, $project2);
                    break;
                default:
                    // 无效服务器，返回空
                    return array();
            }
        }

        // 查询数据库获取已存在的版本库
        $existRepoList = $this->objectModel->dao->select('serviceProject,name')->from(TABLE_REPO)
            ->where('SCM')->eq('Gitlab')
            ->andWhere('serviceHost')->eq($server->id)
            ->fetchPairs();

        // 过滤掉已存在的项目
        foreach($mockGitlabProjects as $key => $repo)
        {
            if(isset($existRepoList[$repo->id])) {
                unset($mockGitlabProjects[$key]);
            }
        }

        if(dao::isError()) return dao::getError();

        return array_values($mockGitlabProjects);
    }
}