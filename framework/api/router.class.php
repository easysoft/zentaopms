<?php declare(strict_types=1);
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
include dirname(__FILE__, 2) . '/router.class.php';
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
    public function __construct(string $appName = 'api', string $appRoot = '')
    {
        parent::__construct($appName, $appRoot);

        $this->httpMethod  = strtolower((string) $_SERVER['REQUEST_METHOD']);

        /*
        $documentRoot = zget($_SERVER, 'CONTEXT_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
        $fileName     = ltrim(substr($_SERVER['SCRIPT_FILENAME'], strlen($documentRoot)), '/');
        $webRoot      = ltrim($this->config->webRoot, '/');
        $this->path   = substr(ltrim($_SERVER['REQUEST_URI'], '/'), strlen($webRoot . $fileName) + 1);

        if(strpos($this->path, '?') > 0) $this->path = strstr($this->path, '?', true);
         */

        $this->path = trim(substr((string) $_SERVER['REQUEST_URI'], strpos((string) $_SERVER['REQUEST_URI'], 'api.php') + 7), '/');
        if(strpos($this->path, '?') > 0) $this->path = strstr($this->path, '?', true);

        $subPos = $this->path ? strpos($this->path, '/') : false;
        $this->version = $subPos !== false ? substr($this->path, 0, $subPos) : '';
        $this->path    = $subPos !== false ? substr($this->path, $subPos) : '';

        $this->loadApiLang();
    }

    /**
     * 解析请求路径，找到处理方法
     *
     * Parse request path, find entry and action.
     *
     * @param  array $routes
     * @access public
     * @return void
     */
    public function route(array $routes)
    {
        foreach($routes as $route => $target)
        {
            $patternAsRegex = preg_replace_callback(
                '#:([\w]+)\+?#',
                $this->matchesCallback(...),
                str_replace(')', ')?', $route)
            );
            if(str_ends_with($route, '/')) $patternAsRegex .= '?';

            /* Cache URL params' names and values if this route matches the current HTTP request. */
            if(!preg_match('#^' . $patternAsRegex . '$#', $this->path, $paramValues)) continue;

            /* Set module and action */
            $this->entry  = $target;
            $this->action = strtolower((string) $_SERVER['REQUEST_METHOD']);

            /* Set params */
            foreach($this->paramNames as $name)
            {
                if(!isset($paramValues[$name])) continue;

                $this->params[$name] = urldecode($paramValues[$name]);
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
     * @param  array     $m
     * @access protected
     * @return string
     */
    protected function matchesCallback(array $m)
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
        try
        {
            /* If the version of api don't exists, call parent method. */
            if(!$this->version)
            {
                global $app;
                $app->setParams();
                return parent::loadModule();
            }

            $entry    = strtolower($this->entry);
            $filename = $this->appRoot . "api/$this->version/entries/$entry.php";

            if(file_exists($filename)) include($filename);

            $entryName = $this->entry . 'Entry';

            if($entry == 'error' && !class_exists($entryName)) include($this->appRoot . "api/v1/entries/$entry.php");

            $entry = new $entryName();

            if($this->action == 'options') throw EndResponseException::create($entry->send(204));

            echo call_user_func_array(array($entry, $this->action), array_values($this->params));
        }
        catch(EndResponseException $endResponseException)
        {
            echo $endResponseException->getContent();
        }
    }

    /**
     * 加载配置文件
     *
     * Load config file of api.
     *
     * @param  string $configPath
     * @access public
     * @return void
     */
    public function loadApiConfig(string $configPath)
    {
        global $config;
        include($this->appRoot . "api/$this->version/config/$configPath.php");
    }

    /**
     * 加载语言文件
     *
     * Load lang file of api.
     *
     * @access public
     * @return void
     */
    public function loadApiLang()
    {
        global $lang;
        $filename = $this->appRoot . "api/$this->version/lang/$this->clientLang.php";
        if($this->version && file_exists($filename)) include($filename);
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
    public function formatData(string $output)
    {
        /* If the version exists, return output directly. */
        if($this->version) return $output;

        $output = json_decode((string) $output);

        $data = new stdClass();
        $data->status = $output->status ?? $output->result;
        if(isset($output->message)) $data->message = $output->message;
        if(isset($output->data))    $data->data    = json_decode((string) $output->data);
        if(isset($output->id))      $data->id      = $output->id;
        $output = json_encode($data);

        unset($_SESSION['ENTRY_CODE']);
        unset($_SESSION['VALID_ENTRY']);

        return $output;
    }

    /**
     * 设置vision。
     * set Debug.
     *
     * @access public
     * @return void
     */
    public function setVision()
    {
        $account = isset($_SESSION['user']) ? $_SESSION['user']->account : '';
        if(empty($account) and isset($_POST['account'])) $account = $_POST['account'];
        if(empty($account) and isset($_GET['account']))  $account = $_GET['account'];

        $vision = 'rnd';
        if($this->config->installed and validater::checkAccount($account))
        {
            $sql     = new sql();
            $account = $sql->quote($account);

            $user = $this->dbh->query("SELECT * FROM " . TABLE_USER . " WHERE account = $account AND deleted = '0' LIMIT 1")->fetch();
            if(!empty($user->visions))
            {
                $userVisions = explode(',', $user->visions);
                if(!in_array($vision, $userVisions)) $vision = '';
                if(empty($vision)) list($vision) = $userVisions;
            }
        }

        list($defaultVision) = explode(',', trim($this->config->visions, ','));
        if($vision and strpos($this->config->visions, ",{$vision},") === false) $vision = $defaultVision;

        $this->config->vision = $vision ? $vision : $defaultVision;
    }
}
