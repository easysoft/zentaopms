<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class apiRequestMockStream
{
    public static $responseMap = array();

    private string $content = '';
    private int $position   = 0;

    public function stream_open($path, $mode, $options, &$openedPath): bool
    {
        $this->content  = self::$responseMap[$path] ?? '';
        $this->position = 0;
        return isset(self::$responseMap[$path]);
    }

    public function stream_read(int $count): string
    {
        $result = substr($this->content, $this->position, $count);
        $this->position += strlen($result);
        return $result;
    }

    public function stream_eof(): bool
    {
        return $this->position >= strlen($this->content);
    }

    public function stream_stat(): array
    {
        return array();
    }
}

class apiZenTest extends baseTest
{
    protected $moduleName = 'api';
    protected $className  = 'zen';

    /**
     * Test generateLibsDropMenu method.
     *
     * @param  object $lib
     * @param  int    $version
     * @access public
     * @return array|string
     */
    public function generateLibsDropMenuTest($lib, $version = 0)
    {
        $result = $this->invokeArgs('generateLibsDropMenu', [$lib, $version]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test parseDocSpaceParam method.
     *
     * @param  array     $libs
     * @param  int       $libID
     * @param  string    $type
     * @param  int       $objectID
     * @param  int       $moduleID
     * @param  string    $spaceType
     * @param  int       $release
     * @param  string    $cookie
     * @access public
     * @return object|array
     */
    public function parseDocSpaceParamTest(array $libs, int $libID, string $type, int $objectID, int $moduleID, string $spaceType, int $release, string $cookie = '')
    {
        $originalCookie = isset($this->instance->cookie->docSpaceParam) ? (string)$this->instance->cookie->docSpaceParam : '';
        $this->instance->cookie->docSpaceParam = $cookie;

        try
        {
            $this->invokeArgs('parseDocSpaceParam', [$libs, $libID, $type, $objectID, $moduleID, $spaceType, $release]);
        }
        finally
        {
            $this->instance->cookie->docSpaceParam = $originalCookie;
        }

        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test getMethod method.
     *
     * @param  string $filePath
     * @param  string $ext
     * @access public
     * @return object|array
     */
    public function getMethodTest(string $filePath, string $ext = '')
    {
        try
        {
            $result = $this->invokeArgs('getMethod', [$filePath, $ext]);
        }
        catch(Throwable $error)
        {
            return (object)array('error' => $error->getMessage());
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test request method.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $action
     * @param  array  $postData
     * @param  string $mockContent
     * @access public
     * @return array
     */
    public function requestTest(string $moduleName, string $methodName, string $action, array $postData = array(), string $mockContent = 'mock_response_content')
    {
        global $config;

        $originalPost        = $_POST;
        $originalRequestType = $config->requestType;
        $originalSessionVar  = $config->sessionVar;

        $_POST               = $postData;
        $config->requestType = 'PATH_INFO';
        $config->sessionVar  = 'zentaosid';

        $param = '';
        if($action == 'extendModel')
        {
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= ',' . $key . '=' . $value;
                $param = ltrim($param, ',');
            }
            $url = ltrim(inlink('getModel', "moduleName=$moduleName&methodName=$methodName&params=$param", 'json'), '/');
        }
        else
        {
            if(!isset($_POST['noparam']))
            {
                foreach($_POST as $key => $value) $param .= '&' . $key . '=' . $value;
                $param = ltrim($param, '&');
            }
            $url = ltrim(helper::createLink($moduleName, $methodName, $param, 'json'), '/');
        }
        $url .= (strpos($url, '?') === false ? '?' : '&') . $config->sessionVar . '=' . session_id();

        if(file_exists($url)) unlink($url);
        file_put_contents($url, $mockContent);

        $previousHandler = set_error_handler(static function() {return true;});
        try
        {
            $result = $this->invokeArgs('request', [$moduleName, $methodName, $action]);
        }
        finally
        {
            restore_error_handler();
            $_POST               = $originalPost;
            $config->requestType = $originalRequestType;
            $config->sessionVar  = $originalSessionVar;
            if(file_exists($url)) unlink($url);
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }
}
