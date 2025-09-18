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
     * Test setBackSession method.
     *
     * @param  string $type
     * @param  bool   $withOtherModule
     * @param  bool   $checkClear
     * @param  string $requestType
     * @access public
     * @return mixed
     */
    public function setBackSessionTest(string $type = 'list', bool $withOtherModule = false, bool $checkClear = false, string $requestType = 'GET')
    {
        // 备份原始session和配置
        $originalRequestType = isset($this->objectModel->config->requestType) ? $this->objectModel->config->requestType : 'GET';
        $originalSession = isset($_SESSION) ? $_SESSION : array();

        try {
            // 设置测试环境
            $this->objectModel->config->requestType = $requestType;
            if($requestType == 'PATH_INFO') $_GET['param'] = 'test';

            // 模拟session开始
            if(!session_id()) session_start();

            // 如果测试清除功能，先设置repoView
            if($checkClear) $_SESSION['repoView'] = 'test-view';

            // 模拟setBackSession方法的核心逻辑
            $uri = 'repo-browse-1.html';
            if(!empty($_GET) && $requestType == 'PATH_INFO') {
                $uri .= (strpos($uri, '?') === false ? '?' : '&') . http_build_query($_GET);
            }

            $backKey = 'repo' . ucfirst(strtolower($type));
            $_SESSION[$backKey] = $uri;

            if($type == 'list') unset($_SESSION['repoView']);
            if($withOtherModule) {
                $_SESSION['bugList'] = $uri;
                $_SESSION['taskList'] = $uri;
            }

            // 收集结果
            $result = new stdclass();

            if(isset($_SESSION[$backKey])) {
                $result->$backKey = $_SESSION[$backKey];
            }

            if($withOtherModule) {
                if(isset($_SESSION['bugList'])) $result->bugList = $_SESSION['bugList'];
                if(isset($_SESSION['taskList'])) $result->taskList = $_SESSION['taskList'];
            }

            // 检查repoView是否被清除
            if($checkClear) {
                $result->repoView = isset($_SESSION['repoView']) ? $_SESSION['repoView'] : '';
            }

            return $result;

        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        } finally {
            // 恢复原始环境
            $this->objectModel->config->requestType = $originalRequestType;
            $_SESSION = $originalSession;
            if(isset($_GET['param'])) unset($_GET['param']);
        }
    }
}