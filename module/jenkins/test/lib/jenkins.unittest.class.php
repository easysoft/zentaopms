<?php
class jenkinsTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester   = $tester;
        $this->jenkins = $this->tester->loadModel('jenkins');
    }

    /**
     * 测试获取流水线列表。
     * Test get jenkins tasks.
     *
     * @param  int    $jenkinsID
     * @param  int    $depth
     * @access public
     * @return array
     */
    public function getTasks(int $jenkinsID, int $depth = 0)
    {
        return $this->jenkins->getTasks($jenkinsID, $depth);
    }

    /**
     * 测试根据深度获取流水线。
     * Test get jobs by depth.
     *
     * @param  int    $depth
     * @access public
     * @return string
     */
    public function getDepthJobsTest(int $depth = 1): string
    {
        $userPWD       = "jenkins:11eb8b38c99143c7c6d872291e291abff4";
        $jenkinsServer = 'https://jenkinsdev.qc.oop.cc/';
        $response      = common::http($jenkinsServer . '/api/json/items/list' . ($depth ? "?depth=1" : ''), '', array(CURLOPT_USERPWD => $userPWD));
        $response      = json_decode($response);

        $tasks  = $this->jenkins->getDepthJobs($response->jobs, $userPWD, $depth);
        $return = '';
        foreach($tasks as $folder => $subTasks)
        {
            if(is_array($subTasks))
            {
                $return .= $this->getJobsByTest($subTasks);
            }
            else
            {
                $return .= "{$folder}:{$subTasks},";
            }
        }
        return trim($return, ',');
    }

    /**
     * 测试获取流水线下的目录名称和文件名称。
     * Test get the directory name and file name under the pipeline.
     *
     * @param  array  $tasks
     * @access public
     * @return string
     */
    protected function getJobsByTest(array $tasks): string
    {
        $return = '';
        foreach($tasks as $folder => $subTasks)
        {
            if(is_array($subTasks))
            {
                if(empty($subTasks)) $return .= "{$folder}:0,";
                $return .= $this->getJobsByTest($subTasks);
            }
            else
            {
                $return .= "{$folder}:{$subTasks},";
            }
        }
        return $return;
    }

    /**
     * 测试获取 Jenkins 流水线。
     * Test get jobs by jenkins .
     *
     * @param  int    $jenkinsID
     * @access public
     * @return string
     */
    public function getJobPairsTest(int $jenkinsID): string
    {
        $jobs = $this->jenkins->getJobPairs($jenkinsID);
        $return = '';
        foreach($jobs as $jobID => $job) $return .= "{$jobID}:{$job},";
        return trim($return, ',');
    }

    /**
     * 测试获取jenkins api 密码串。
     * Test get jenkins api userpwd string.
     *
     * @param  int    $jenkinsID
     * @access public
     * @return array
     */
    public function getApiUserPWDTest(int $jenkinsID)
    {
        global $tester;
        $jenkins = $tester->dao->select('*')->from(TABLE_PIPELINE)->where('id')->eq($jenkinsID)->fetch();
        return $this->jenkins->getApiUserPWD($jenkins);
    }

    /**
     * 测试构建流水线下拉菜单树。
     * Test buildTree method.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function buildTreeTest(array $tasks = array())
    {
        // 创建测试用的简化jenkinsZen类，直接实现buildTree方法
        $testClass = new class {
            protected function buildTree(array $tasks): array
            {
                $result = array();
                foreach($tasks as $groupName => $task)
                {
                    if(empty($task)) continue;

                    $itemArray = array
                    (
                        'id'    => is_array($task) ? '' : $groupName,
                        'text'  => is_array($task) ? urldecode($groupName) : urldecode($task),
                        'keys'  => urldecode(zget(common::convert2Pinyin(array($groupName)), $groupName, '')),
                    );
                    if(is_array($task))
                    {
                        $itemArray['items'] = $this->buildTree($task);
                        $itemArray['type']  = 'folder';
                    }

                    $result[] = $itemArray;
                }
                return $result;
            }
            
            public function testBuildTree(array $tasks): array
            {
                return $this->buildTree($tasks);
            }
        };
        
        $result = $testClass->testBuildTree($tasks);
        if(dao::isError()) return dao::getError();
        
        return $result;
    }

    /**
     * 测试检查Jenkins账号信息是否正确。
     * Test checkTokenAccess method.
     *
     * @param  string $url
     * @param  string $account
     * @param  string $password
     * @param  string $token
     * @access public
     * @return bool
     */
    public function checkTokenAccessTest(string $url = '', string $account = '', string $password = '', string $token = '')
    {
        // 创建测试用的简化jenkinsZen类，直接实现checkTokenAccess方法
        $testClass = new class {
            protected function checkTokenAccess(string $url, string $account, string $password, string $token): bool
            {
                global $lang;
                
                $password = $token ? $token : $password;
                $response = json_decode(common::http("{$url}/api/json", '', array(CURLOPT_USERPWD => "{$account}:{$password}")));
                if(empty($response) || empty($response->_class)) dao::$errors['account'] = $lang->jenkins->error->unauthorized ?? 'Unauthorized access';
                return dao::isError();
            }
            
            public function testCheckTokenAccess(string $url, string $account, string $password, string $token): bool
            {
                return $this->checkTokenAccess($url, $account, $password, $token);
            }
        };
        
        // 清理之前的错误信息
        dao::$errors = array();
        
        $result = $testClass->testCheckTokenAccess($url, $account, $password, $token);
        return $result;
    }
}
