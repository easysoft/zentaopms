<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

/**
 * 用于在单元测试中替代 curl 的 HTTP 桩。
 * Stub HTTP client used in unit tests to replace real curl calls.
 */
class gitfoxStubHttpClient
{
    public $responses = array();
    public $calls     = array();

    public function setResponse(string $key, $response): void
    {
        $this->responses[$key] = $response;
    }

    public function request(string $url, $data = null, array $options = array(), array $headers = array(), string $dataType = 'data', string $method = 'POST', int $timeout = 30, bool $httpCode = false, bool $log = true)
    {
        $this->calls[] = array('url' => $url, 'method' => $method, 'data' => $data);
        foreach($this->responses as $key => $value)
        {
            if(strpos($url, $key) !== false) return $value;
        }
        return '';
    }
}

class gitfoxModelTest extends baseTest
{
    protected $moduleName = 'gitfox';
    protected $className  = 'model';

    /**
     * 通过对象属性灌入仓库缓存。
     * Inject repo cache into the model instance.
     *
     * @param  int|string $repoID
     * @param  object     $repo
     * @access public
     * @return void
     */
    public function setRepoCache($repoID, object $repo): void
    {
        $reflection = new ReflectionObject($this->instance);
        $property   = $reflection->getProperty('repos');
        $property->setAccessible(true);
        $cache              = $property->getValue($this->instance);
        $cache[$repoID]     = $repo;
        $property->setValue($this->instance, $cache);
    }

    /**
     * 重置 HTTP 桩，避免不同步骤之间相互污染。
     * Reset stub HTTP client between steps.
     *
     * @access public
     * @return gitfoxStubHttpClient
     */
    public function resetHttpClient(): gitfoxStubHttpClient
    {
        $client = new gitfoxStubHttpClient();
        common::$httpClient = $client;
        return $client;
    }

    /**
     * 还原 HTTP 客户端为真实实现。
     * Restore the real HTTP client.
     *
     * @access public
     * @return void
     */
    public function restoreHttpClient(): void
    {
        common::$httpClient = null;
    }

    /**
     * Test apiGetSingleRepo method.
     *
     * @param  int|string  $repoID
     * @param  string|null $apiResponse 模拟的 HTTP 响应；为 null 则走缓存。
     * @access public
     * @return mixed
     */
    public function apiGetSingleRepoTest($repoID, ?string $apiResponse = null)
    {
        if($apiResponse === null)
        {
            $this->restoreHttpClient();
            dao::$errors = array();
            $result = $this->invokeArgs('apiGetSingleRepo', array($repoID));
            return $result;
        }

        $client = $this->resetHttpClient();
        $client->setResponse("/repos/$repoID", $apiResponse);
        dao::$errors = array();

        $result = $this->invokeArgs('apiGetSingleRepo', array($repoID));
        $this->restoreHttpClient();
        return $result;
    }

    /**
     * Test apiGetMirrorSyncProgress method.
     *
     * @param  int         $repoID
     * @param  string|null $apiResponse
     * @access public
     * @return mixed
     */
    public function apiGetMirrorSyncProgressTest(int $repoID, ?string $apiResponse = null)
    {
        if($apiResponse === null)
        {
            $this->restoreHttpClient();
            dao::$errors = array();
            $result = $this->invokeArgs('apiGetMirrorSyncProgress', array($repoID));
            return $result;
        }

        $client = $this->resetHttpClient();
        $client->setResponse('/repos/mirror-sync-progress', $apiResponse);
        dao::$errors = array();

        $result = $this->invokeArgs('apiGetMirrorSyncProgress', array($repoID));
        $this->restoreHttpClient();
        return $result;
    }

    /**
     * Test apiMirrorSync method.
     *
     * @param  int         $repoID
     * @param  string|null $apiResponse
     * @access public
     * @return mixed
     */
    public function apiMirrorSyncTest(int $repoID, ?string $apiResponse = null)
    {
        if($apiResponse === null)
        {
            $this->restoreHttpClient();
            dao::$errors = array();
            $result = $this->invokeArgs('apiMirrorSync', array($repoID));
            return $result;
        }

        $client = $this->resetHttpClient();
        $client->setResponse('/repos/mirror-sync', $apiResponse);
        dao::$errors = array();

        $result = $this->invokeArgs('apiMirrorSync', array($repoID));
        $this->restoreHttpClient();
        return $result;
    }

    /**
     * Test getCommits method.
     *
     * @param  object      $repo
     * @param  string      $entry
     * @param  object|null $pager
     * @param  string      $begin
     * @param  string      $end
     * @param  object|null $query
     * @param  string|null $apiResponse
     * @access public
     * @return mixed
     */
    public function getCommitsTest(object $repo, string $entry = '', ?object $pager = null, string $begin = '', string $end = '', ?object $query = null, ?string $apiResponse = null)
    {
        if($apiResponse !== null)
        {
            $client = $this->resetHttpClient();
            $client->setResponse('commits/list', $apiResponse);
        }
        else
        {
            $this->restoreHttpClient();
        }
        dao::$errors = array();

        $result = $this->invokeArgs('getCommits', array($repo, $entry, $pager, $begin, $end, $query));
        $this->restoreHttpClient();
        return $result;
    }

    /**
     * Test __call method.
     *
     * 该方法将 funcName 中的 project 替换为 repo，再尝试调用同名方法。
     *
     * @param  string      $funcName
     * @param  array       $arguments
     * @param  string|null $apiResponse
     * @access public
     * @return mixed
     */
    public function __callTest(string $funcName, array $arguments = array(), ?string $apiResponse = null)
    {
        if($apiResponse !== null)
        {
            $client = $this->resetHttpClient();
            $client->setResponse('/repos/', $apiResponse);
        }
        else
        {
            $this->restoreHttpClient();
        }
        dao::$errors = array();

        $result = $this->instance->{$funcName}(...$arguments);
        $this->restoreHttpClient();
        return $result;
    }
}
