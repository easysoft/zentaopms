<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

/**
 * @property gitfoxModel $instance
 */
class gitfoxModelTest extends baseTest
{
    protected $moduleName = 'gitfox';
    protected $className  = 'model';

    /**
     * Seed GitFox entry for real API calls.
     *
     * @access public
     * @return void
     */
    public function seedGitFoxEntry(): void
    {
        $this->clearGitFoxEntry();

        $entry = new stdClass();
        $entry->name       = 'GitFox';
        $entry->account    = 'admin';
        $entry->code       = 'gitfox';
        $entry->key        = 'gitfox';
        $entry->freePasswd = 1;
        $entry->ip         = '*';
        $entry->createdBy  = 'admin';
        $entry->createdDate = helper::now();
        $entry->calledTime = 0;
        $entry->editedBy   = 'admin';
        $entry->editedDate = helper::now();
        $entry->deleted    = '0';

        $this->instance->dao->insert(TABLE_ENTRY)->data($entry)->exec();
    }

    /**
     * Clear GitFox entry.
     *
     * @access public
     * @return void
     */
    public function clearGitFoxEntry(): void
    {
        $this->instance->dao->delete()->from(TABLE_ENTRY)->where('code')->eq('gitfox')->exec();
    }

    /**
     * Dispatch dynamic test helpers.
     *
     * @param  string $methodName
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call(string $methodName, array $arguments)
    {
        if(str_ends_with($methodName, 'Test'))
        {
            $modelMethod = substr($methodName, 0, -4);
            if(method_exists($this->instance, $modelMethod)) return $this->invokeForTest($modelMethod, $arguments);
        }

        foreach(array('ErrorTest', 'TypeTest', 'CountTest', 'Test') as $suffix)
        {
            if(!str_ends_with($methodName, $suffix)) continue;

            $modelMethod = substr($methodName, 0, -strlen($suffix));
            $result      = $this->invokeForTest($modelMethod, $arguments);

            if($suffix == 'ErrorTest') return $this->hasDaoError() ? 1 : 0;
            if($suffix == 'TypeTest')  return $this->normalizeType($result);
            if($suffix == 'CountTest') return $this->normalizeCount($result);

            return $result;
        }

        throw new BadMethodCallException("Undefined method {$methodName}.");
    }

    /**
     * Check if checkHealth returns the same result twice.
     *
     * @access public
     * @return int
     */
    public function checkHealthSameResultTest(): int
    {
        $first  = $this->invokeForTest('checkHealth');
        $second = $this->invokeForTest('checkHealth');
        return $first === $second ? 1 : 0;
    }

    /**
     * Check getApiRoot URL against the active test configuration.
     *
     * @access public
     * @return int
     */
    public function getApiRootURLMatchesConfigTest(): int
    {
        $apiRoot = $this->invokeForTest('getApiRoot');
        if(!is_object($apiRoot)) return 0;

        $expectedURL = rtrim($this->instance->config->devops->gitfoxURL, '/');
        $gitfoxPort  = $this->instance->config->devops->gitfoxPort;
        if($gitfoxPort) $expectedURL .= ':' . $gitfoxPort;
        $expectedURL .= '/api/v2%s';

        return zget($apiRoot, 'url', '') === $expectedURL ? 1 : 0;
    }

    /**
     * Check if page result contains page field.
     *
     * @param  object|null $pager
     * @access public
     * @return int
     */
    public function getPageHasPageTest(?object $pager): int
    {
        $result = $this->invokeForTest('getPage', array($pager));
        return isset($result['page']) ? 1 : 0;
    }

    /**
     * Check if hooks contain the given url.
     *
     * @param  int    $repoID
     * @param  string $url
     * @access public
     * @return int
     */
    public function apiGetHooksContainsUrlTest(int $repoID, string $url): int
    {
        $hooks = $this->invokeForTest('apiGetHooks', array($repoID));
        if(!is_array($hooks)) return 0;

        foreach($hooks as $hook)
        {
            if(isset($hook->url) && $hook->url == $url) return 1;
        }

        return 0;
    }

    /**
     * Check if apiCreateBranch returns an object.
     *
     * @param  int    $repoID
     * @param  object $branch
     * @access public
     * @return string
     */
    public function apiCreateBranchResultTypeTest(int $repoID, object $branch): string
    {
        return $this->normalizeType($this->invokeForTest('apiCreateBranch', array($repoID, $branch)));
    }

    /**
     * Check if apiDeleteBranch returns a bool result.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return string
     */
    public function apiDeleteBranchResultTypeTest(int $repoID, string $branch): string
    {
        return $this->normalizeType($this->invokeForTest('apiDeleteBranch', array($repoID, $branch)));
    }

    /**
     * Check if apiCreateHook returns the expected url.
     *
     * @param  int    $repoID
     * @param  object $hook
     * @access public
     * @return int
     */
    public function apiCreateHookUrlMatchesTest(int $repoID, object $hook): int
    {
        $result = $this->invokeForTest('apiCreateHook', array($repoID, $hook));
        if(!is_object($result) || !isset($result->url)) return 0;

        return $result->url === $hook->url ? 1 : 0;
    }

    /**
     * Check if apiUpdateWebhook returns the expected url.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @param  object $data
     * @access public
     * @return int
     */
    public function apiUpdateWebhookUrlMatchesTest(int $repoID, int $webhookID, object $data): int
    {
        $result = $this->invokeForTest('apiUpdateWebhook', array($repoID, $webhookID, $data));
        if(!is_object($result) || !isset($result->url)) return 0;

        return $result->url === $data->url ? 1 : 0;
    }

    /**
     * Check if apiUpdateWebhook returns the expected id.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @param  object $data
     * @access public
     * @return int
     */
    public function apiUpdateWebhookIDMatchesTest(int $repoID, int $webhookID, object $data): int
    {
        $result = $this->invokeForTest('apiUpdateWebhook', array($repoID, $webhookID, $data));
        if(!is_object($result) || !isset($result->id)) return 0;

        return (string)$result->id === (string)$webhookID ? 1 : 0;
    }

    /**
     * Check if getServer token matches the expected value.
     *
     * @param  string $token
     * @access public
     * @return int
     */
    public function getServerTokenMatchesTest(string $token): int
    {
        $server = $this->invokeForTest('getServer');
        if(!is_object($server) || !isset($server->token)) return 0;

        return $server->token === $token ? 1 : 0;
    }

    /**
     * Check if getServer token is empty.
     *
     * @access public
     * @return int
     */
    public function getServerTokenEmptyTest(): int
    {
        $server = $this->invokeForTest('getServer');
        if(!is_object($server) || !isset($server->token)) return 0;

        return $server->token === '' ? 1 : 0;
    }

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
     * Invoke model method for tests.
     *
     * @param  string $methodName
     * @param  array  $arguments
     * @access protected
     * @return mixed
     */
    protected function invokeForTest(string $methodName, array $arguments = array())
    {
        dao::$errors = array();

        ob_start();
        $result = $this->invokeArgs($methodName, $arguments);
        ob_end_clean();

        return $result;
    }

    /**
     * Check dao error flag and clear captured errors.
     *
     * @access protected
     * @return bool
     */
    protected function hasDaoError(): bool
    {
        $hasError = dao::isError();
        if($hasError) dao::getError();
        dao::$errors = array();
        return $hasError;
    }

    /**
     * Normalize result type for assertions.
     *
     * @param  mixed $result
     * @access protected
     * @return string
     */
    protected function normalizeType($result): string
    {
        if(is_null($result))   return 'null';
        if(is_bool($result))   return 'bool';
        if(is_array($result))  return 'array';
        if(is_object($result)) return 'object';
        if(is_int($result))    return 'int';
        if(is_float($result))  return 'float';
        if(is_string($result)) return 'string';

        return gettype($result);
    }

    /**
     * Normalize result count for assertions.
     *
     * @param  mixed $result
     * @access protected
     * @return int
     */
    protected function normalizeCount($result): int
    {
        if(is_array($result) || is_object($result)) return count((array)$result);
        if(is_string($result)) return strlen($result);
        if(is_bool($result))   return $result ? 1 : 0;
        if(is_null($result))   return 0;
        return (int)$result;
    }
}
