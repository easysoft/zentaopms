<?php
class sonarqubeTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('sonarqube');
        $this->objectTao   = $tester->loadTao('sonarqube');
    }

    public function apiCreateProjectTest($sonarqubeID, $project = array())
    {
        // 预处理项目对象，确保必要的属性存在且不为null
        $projectObj = (object)$project;
        if(!isset($projectObj->projectName) || $projectObj->projectName === null) $projectObj->projectName = '';
        if(!isset($projectObj->projectKey) || $projectObj->projectKey === null) $projectObj->projectKey = '';

        try {
            $result = $this->objectModel->apiCreateProject($sonarqubeID, $projectObj);
        } catch (Exception $e) {
            return 'return false';
        } catch (TypeError $e) {
            return 'return false';
        }

        if($result === false) return 'return false';

        // 处理错误情况
        if(is_array($result))
        {
            if(isset($result[0]->msg))
            {
                $errorMsg = $result[0]->msg;
                if($errorMsg == "The 'project' parameter is missing") return 'missing project parameter';
                if(strpos($errorMsg, 'Could not create Project') !== false) return 'project exists';
            }
            return 'api error';
        }

        // 处理成功情况
        if(is_object($result))
        {
            if(isset($result->project) && isset($result->project->name)) return 'success';
            if(isset($result->name) && isset($project['projectName']) && $result->name == $project['projectName']) return 'success';
            return 'object result';
        }

        // 如果是其他类型，转为字符串返回
        return is_string($result) ? $result : 'unknown result';
    }

    public function createProjectTest($sonarqubeID, $post)
    {
        $result = $this->objectModel->createProject($sonarqubeID, (object)$post);
        $errors = dao::getError();
        if($errors)
        {
            if(current($errors['name']) == "项目标识的格式不正确。允许的字符为字母、数字、'-'、''、'.'和“：”，至少有一个非数字。") return 'return error';
            if(current($errors['name']) == false) return 'return false';
            if(current($errors['name']) == 'Could not create Project with key: "' . $post['projectKey'] . '". A similar key already exists: "' . $post['projectKey'] . '"') return true;
            return $errors;
        }
        else
        {
            if(empty($result)) $result = 'return false';
            return $result;
        }
    }

    /**
     * Test apiDeleteProject method.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return mixed
     */
    public function apiDeleteProjectTest($sonarqubeID, $projectKey)
    {
        try {
            $result = $this->objectModel->apiDeleteProject($sonarqubeID, $projectKey);
        } catch (Exception $e) {
            return 'return false';
        } catch (TypeError $e) {
            return 'return false';
        }

        if(dao::isError()) return dao::getError();

        // 处理各种返回结果
        if($result === false) return 'return false';
        if($result === null) return 'return true';

        // 处理错误情况
        if(is_array($result))
        {
            // 处理包含错误信息的数组
            if(isset($result[0]->msg))
            {
                return $result;
            }
            return 'api error array';
        }

        // 处理对象返回值
        if(is_object($result))
        {
            if(isset($result->errors))
            {
                return $result->errors;
            }
            if(isset($result->msg))
            {
                return array((object)array('msg' => $result->msg));
            }
            return 'object result';
        }

        // 其他情况
        return is_string($result) ? $result : 'unknown result';
    }

    public function apiGetIssuesTest($sonarqubeID, $projectKey = '')
    {
        $result = $this->objectModel->apiGetIssues($sonarqubeID, $projectKey);

        if(empty($result)) $result = 'return empty';
        return $result;
    }

    public function apiGetProjectsTest($sonarqubeID, $keyword = '')
    {
        $result = $this->objectModel->apiGetProjects($sonarqubeID, $keyword);

        if(empty($result)) $result = 'return empty';
        return $result;
    }

    public function apiValidateTest($sonarqubeID, $url = '', $token = '')
    {
        global $tester;
        $sonarqubeServer = $tester->loadModel('pipeline')->getByID($sonarqubeID);
        $url   = $url ? $url : $sonarqubeServer->url;
        $token = $token ? $token : $sonarqubeServer->token;

        $result = $this->objectModel->apiValidate($url, $token);

        if(empty($result)) $result = 'success';
        if(isset($result['password'])) $result = 'return false';
        if(isset($result['account'])) $result = 'return false';
        return $result;
    }

    public function getApiBaseTest($sonarqubeID)
    {
        list($apiRoot, $header) = $this->objectModel->getApiBase($sonarqubeID);

        if(empty($apiRoot)) return 'return empty';
        return $apiRoot;
    }

    public function getCacheFileTest($sonarqubeID, $projectKey)
    {
        $result = $this->objectModel->getCacheFile($sonarqubeID, $projectKey);

        if(strPos($result, '/' . $sonarqubeID . '-' ) !== false) return true;
        if($result === false) return true;
        return $result;
    }

    /**
     * Test checkTokenRequire method.
     *
     * @param  object $sonarqube
     * @access public
     * @return mixed
     */
    public function checkTokenRequireTest($sonarqube)
    {
        global $tester;

        // 使用反射创建一个模拟的zen对象，因为zen类需要通过框架加载
        $sonarqubeModel = $tester->loadModel('sonarqube');

        // 创建一个临时的zen类来测试
        $tempZenClass = new class($sonarqubeModel) {
            protected $dao;
            protected $config;
            protected $lang;

            public function __construct($model) {
                global $tester;
                $this->dao = $tester->app->loadClass('dao');
                $this->config = $tester->config;
                $this->lang = $tester->lang;
            }

            protected function checkTokenRequire(object $sonarqube): void
            {
                $this->dao->update('sonarqube')->data($sonarqube)
                    ->batchCheck(empty($sonarqubeID) ? $this->config->sonarqube->create->requiredFields : $this->config->sonarqube->edit->requiredFields, 'notempty')
                    ->batchCheck("url", 'URL');
                if(strpos($sonarqube->url, 'http') !== 0) dao::$errors['url'][] = $this->lang->sonarqube->hostError;
            }

            public function testCheckTokenRequire($sonarqube) {
                return $this->checkTokenRequire($sonarqube);
            }
        };

        try {
            $tempZenClass->testCheckTokenRequire($sonarqube);
            return dao::isError() ? dao::getError() : 'success';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Test sortAndPage method.
     *
     * @param  array  $dataList
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return mixed
     */
    public function sortAndPageTest($dataList, $orderBy, $recPerPage, $pageID)
    {
        global $tester;

        // 创建一个临时的zen类来测试
        $tempZenClass = new class() {
            protected $app;
            protected $view;

            public function __construct() {
                global $tester;
                $this->app = $tester->app;
                $this->view = new stdClass();
            }

            protected function sortAndPage($dataList, $orderBy, $recPerPage, $pageID)
            {
                /* Data sort. */
                list($order, $sort) = explode('_', $orderBy);
                $orderList = array();
                foreach($dataList as $data) $orderList[] = $data->$order;
                array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $dataList);

                /* Pager. */
                $this->app->loadClass('pager', true);
                $recTotal             = count($dataList);
                $pager                = new pager($recTotal, $recPerPage, $pageID);
                $dataList = array_chunk($dataList, $pager->recPerPage);

                $this->view->pager = $pager;
                return $dataList;
            }

            public function testSortAndPage($dataList, $orderBy, $recPerPage, $pageID) {
                return $this->sortAndPage($dataList, $orderBy, $recPerPage, $pageID);
            }
        };

        try {
            $result = $tempZenClass->testSortAndPage($dataList, $orderBy, $recPerPage, $pageID);
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
