<?php
/**
 * 禅道API的api类。
 * The api class file of ZenTao API.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
include dirname(dirname(__FILE__)) . '/router.class.php';
class api extends router
{
    /**
     * 请求API的路径
     * The requested path of api.
     *
     * @var string
     * @access public
     */
    public $path;

    /**
     * API版本号
     * The version of API.
     *
     * @var string
     * @access public
     */
    public $version = '';

    /**
     * 请求API的参数，包括键值
     * The requested params of api: key and value.
     *
     * @var array
     * @access public
     */
    public $params = array();

    /**
     * 请求API的参数名
     * The requested param names of api.
     *
     * @var array
     * @access public
     */
    public $paramNames = array();

    /**
     * 请求的资源名称
     * The requested entry point
     *
     * @var string
     * @access public
     */
    public $entry;

    /**
     * API资源的执行方法: get post put delete
     * The action of entry point: get post put delete
     *
     * @var string
     * @access public
     */
    public $action;

    /**
     * 构造方法, 设置请求路径，版本等
     *
     * The construct function.
     * Prepare all the paths, version and so on.
     *
     * @access public
     * @return void
     */
    public function __construct($appName = 'api', $appRoot = '')
    {
        parent::__construct($appName, $appRoot);

        $this->httpMethod  = strtolower($_SERVER['REQUEST_METHOD']);

        if(!empty($_SERVER['PATH_INFO']))
        {
            $this->path = rtrim($_SERVER['PATH_INFO'], '/');
        }
        else
        {
            $this->path = trim((strpos($_SERVER['REQUEST_URI'], '?') > 0 ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI']), '/');
        }

        $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if($dir != '') $this->path = substr($this->path, strlen($dir));

        $subPos = strpos($this->path, '/', 1);

        $this->version = $subPos ? substr($this->path, 1, $subPos - 1) : '';
        $this->path    = $subPos ? substr($this->path, $subPos) : $this->path;
    }

    /**
     * 解析请求路径，找到处理方法
     *
     * Parse request path, find entry and action.
     *
     * @param  array $routes
     * @access private
     * @return void
     */
    public function route($routes)
    {
        foreach($routes as $route => $target)
        {
            $patternAsRegex = preg_replace_callback(
                '#:([\w]+)\+?#',
                array($this, 'matchesCallback'),
                str_replace(')', ')?', $route)
            );
            if(substr($route, -1) === '/') $patternAsRegex .= '?';

            /* Cache URL params' names and values if this route matches the current HTTP request. */
            if(!preg_match('#^' . $patternAsRegex . '$#', $this->path, $paramValues)) continue;

            /* Set module and action */
            $this->entry  = $target;
            $this->action = strtolower($_SERVER['REQUEST_METHOD']);

            /* Set params */
            foreach($this->paramNames as $name)
            {
                if(!isset($paramValues[$name])) continue;

                if(isset($this->paramNamesPath[$name]))
                {
                    $this->params[$name] = explode('/', urldecode($paramValues[$name]));
                }
                else
                {
                    $this->params[$name] = urldecode($paramValues[$name]);
                }
            }
            return;
        }

        $this->entry  = 'error';
        $this->action = 'notFound';
    }

    /**
     * 将路由路径参数转化为正则
     *
     * Parse params of route to regular expression.
     *
     * @param  string $param
     * @access protected
     * @return string
     */
    protected function matchesCallback($m)
    {
        $this->paramNames[] = $m[1];
        return '(?P<' . $m[1] . '>[^/]+)';
    }

    /**
     * 解析访问请求
     *
     * Parse request.
     *
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        /* If version of api don't exists, call parent method. */
        if(!$this->version) return parent::parseRequest();

        $this->route($this->config->routes);
    }

    /**
     * 执行对应模块
     *
     * Load the running module.
     *
     * @access public
     * @return void
     */
    public function loadModule()
    {
        /* If the version of api don't exists, call parent method. */
        if(!$this->version) return parent::loadModule();

        $entry = strtolower($this->entry);
        include($this->appRoot . "api/$this->version/entries/$entry.php");

        $entryName = $this->entry . 'Entry';
        $entry = new $entryName();
        call_user_func_array(array($entry, $this->action), $this->params);
    }

    /**
     * 格式化旧版本API响应数据
     *
     * Format old version data.
     *
     * @param  string
     * @access public
     * @return string
     */
    public function formatData($output)
    {
        /* If the version exists, return output directly. */
        if($this->version) return $output;

        $output = json_decode($output);

        $data = new stdClass();
        $data->status = isset($output->status) ? $output->status : $output->result;
        if(isset($output->message)) $data->message = $output->message;
        if(isset($output->data))    $data->data    = json_decode($output->data);
        if(isset($output->id))      $data->id      = $output->id;
        $output = json_encode($data);

        unset($_SESSION['ENTRY_CODE']);
        unset($_SESSION['VALID_ENTRY']);

        return $output;
    }
}
