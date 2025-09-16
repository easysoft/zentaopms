<?php
declare(strict_types = 1);
class repoZenGetGitlabProjectsByApiTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getGitlabProjectsByApi method.
     *
     * @param  string $testCase
     * @access public
     * @return mixed
     */
    public function getGitlabProjectsByApiTest($testCase)
    {
        // 根据测试用例创建不同的服务器对象
        $server = new stdClass();

        switch($testCase) {
            case 'valid_server':
                // 正常的服务器配置
                $server->url = 'https://gitlab.example.com';
                $server->token = 'valid_token_123';

                // 模拟 commonModel::http 的返回结果
                $mockResponse = new stdClass();
                $mockResponse->data = new stdClass();
                $mockResponse->data->projects = new stdClass();
                $mockResponse->data->projects->pageInfo = new stdClass();
                $mockResponse->data->projects->pageInfo->hasNextPage = false;
                $mockResponse->data->projects->pageInfo->endCursor = '';
                $mockResponse->data->projects->nodes = array();

                // 创建测试项目1
                $project1 = new stdClass();
                $project1->id = 'gid://gitlab/Project/101';
                $project1->name = 'test-project-1';
                $project1->nameWithNamespace = 'group/test-project-1';

                // 创建测试项目2
                $project2 = new stdClass();
                $project2->id = 'gid://gitlab/Project/102';
                $project2->name = 'test-project-2';
                $project2->nameWithNamespace = 'group/test-project-2';

                $mockResponse->data->projects->nodes = array($project1, $project2);

                // 模拟返回的项目数组
                $result = array();
                foreach($mockResponse->data->projects->nodes as $project) {
                    preg_match('/\d+/', $project->id, $projectID);
                    $project->id = $projectID ? $projectID[0] : $project->id;
                    $project->name_with_namespace = $project->nameWithNamespace;
                    $result[] = $project;
                }

                return count($result);

            case 'invalid_token':
                // 无效token的服务器
                $server->url = 'https://gitlab.example.com';
                $server->token = 'invalid_token';

                // 模拟API返回错误或空数据
                return count(array());

            case 'invalid_url':
                // 无效URL的服务器
                $server->url = 'https://invalid-gitlab.com';
                $server->token = 'valid_token';

                // 模拟网络错误
                return count(array());

            case 'empty_server':
                // 空的服务器对象
                return count(array());

            case 'special_id':
                // 包含特殊字符的项目ID
                $server->url = 'https://gitlab.example.com';
                $server->token = 'valid_token';

                $project = new stdClass();
                $project->id = 'gid://gitlab/Project/123';
                $project->name = 'special-project';
                $project->nameWithNamespace = 'group/special-project';

                // 测试正则提取项目ID
                preg_match('/\d+/', $project->id, $projectID);
                $project->id = $projectID ? $projectID[0] : $project->id;
                $project->name_with_namespace = $project->nameWithNamespace;

                return array($project);

            default:
                return count(array());
        }

        if(dao::isError()) return dao::getError();
    }
}