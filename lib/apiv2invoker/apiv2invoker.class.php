<?php
/**
 * 内部调用 APIv2 的开发者封装类。
 * Developer wrapper for invoking APIv2 internally.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     ZenTaoPMS
 * @link        https://www.zentao.net
 */
include_once __DIR__ . '/apiv2state.class.php';

class apiV2Invoker
{
    /**
     * 最近一次请求的模拟 HTTP 状态码。
     * Simulated HTTP status code of the last request.
     *
     * @var int|null
     * @access private
     * @static
     */
    private static $lastStatusCode;

    /**
     * 最近一次请求的原始响应体。
     * Raw response body of the last request.
     *
     * @var string|null
     * @access private
     * @static
     */
    private static $lastRawBody;

    /**
     * 发起一次内部 APIv2 请求。
     * Send one internal APIv2 request.
     *
     * @param  array $request
     * @access public
     * @static
     * @return mixed
     */
    public static function request(array $request)
    {
        $request = self::normalizeRequest($request);
        $result  = self::executeInProcess($request);

        self::$lastStatusCode = (int)$result['status_code'];
        self::$lastRawBody    = (string)$result['body'];

        if(empty($request['jsonDecode'])) return self::$lastRawBody;

        $decoded = json_decode(self::$lastRawBody, true);
        if(json_last_error() === JSON_ERROR_NONE) return $decoded;

        return self::$lastRawBody;
    }

    /**
     * GET 请求快捷方法。
     * Shortcut for GET request.
     *
     * @param  string $path
     * @param  array  $query
     * @param  string $account
     * @param  array  $headers
     * @access public
     * @static
     * @return mixed
     */
    public static function get(string $path, array $query = array(), string $account = '', array $headers = array())
    {
        return self::request(array(
            'method'  => 'GET',
            'path'    => $path,
            'query'   => $query,
            'account' => $account,
            'headers' => $headers,
        ));
    }

    /**
     * POST 请求快捷方法。
     * Shortcut for POST request.
     *
     * @param  string $path
     * @param  mixed  $body
     * @param  string $account
     * @param  array  $query
     * @param  array  $headers
     * @access public
     * @static
     * @return mixed
     */
    public static function post(string $path, $body = array(), string $account = '', array $query = array(), array $headers = array())
    {
        return self::request(array(
            'method'  => 'POST',
            'path'    => $path,
            'query'   => $query,
            'body'    => $body,
            'account' => $account,
            'headers' => $headers,
        ));
    }

    /**
     * PUT 请求快捷方法。
     * Shortcut for PUT request.
     *
     * @param  string $path
     * @param  mixed  $body
     * @param  string $account
     * @param  array  $query
     * @param  array  $headers
     * @access public
     * @static
     * @return mixed
     */
    public static function put(string $path, $body = array(), string $account = '', array $query = array(), array $headers = array())
    {
        return self::request(array(
            'method'  => 'PUT',
            'path'    => $path,
            'query'   => $query,
            'body'    => $body,
            'account' => $account,
            'headers' => $headers,
        ));
    }

    /**
     * DELETE 请求快捷方法。
     * Shortcut for DELETE request.
     *
     * @param  string $path
     * @param  array  $query
     * @param  string $account
     * @param  array  $headers
     * @access public
     * @static
     * @return mixed
     */
    public static function delete(string $path, array $query = array(), string $account = '', array $headers = array())
    {
        return self::request(array(
            'method'  => 'DELETE',
            'path'    => $path,
            'query'   => $query,
            'account' => $account,
            'headers' => $headers,
        ));
    }

    /**
     * 获取最近一次请求的模拟 HTTP 状态码。
     * Get simulated HTTP status code of last request.
     *
     * @access public
     * @static
     * @return int|null
     */
    public static function lastStatusCode(): ?int
    {
        return self::$lastStatusCode;
    }

    /**
     * 获取最近一次请求的原始响应体。
     * Get raw response body of last request.
     *
     * @access public
     * @static
     * @return string|null
     */
    public static function lastRawBody(): ?string
    {
        return self::$lastRawBody;
    }

    /**
     * 规范化请求参数。
     * Normalize request.
     *
     * @param  array $request
     * @access private
     * @static
     * @return array
     */
    private static function normalizeRequest(array $request): array
    {
        $method = strtoupper((string)($request['method'] ?? 'GET'));
        if(!in_array($method, array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'))) throw new InvalidArgumentException("Unsupported request method: $method");

        $path = '/' . ltrim(trim((string)($request['path'] ?? '/')), '/');
        if($path === '') $path = '/';

        $account = trim((string)($request['account'] ?? ''));
        if($account === '') throw new InvalidArgumentException('The account field is required.');

        $query = $request['query'] ?? array();
        if(is_string($query)) parse_str($query, $query);
        if(!is_array($query)) throw new InvalidArgumentException('The query field must be an array or query string.');

        $body    = $request['body'] ?? array();
        $headers = $request['headers'] ?? array();
        $files   = $request['files'] ?? array();

        if(!is_array($headers)) throw new InvalidArgumentException('The headers field must be an array.');
        if(!is_array($files))   throw new InvalidArgumentException('The files field must be an array.');

        return array(
            'method'     => $method,
            'path'       => $path,
            'query'      => $query,
            'body'       => $body,
            'account'    => $account,
            'headers'    => $headers,
            'files'      => $files,
            'jsonDecode' => (bool)($request['jsonDecode'] ?? true),
        );
    }

    /**
     * 同进程执行 APIv2。
     * Execute APIv2 in current process.
     *
     * @param  array $request
     * @access private
     * @static
     * @return array
     */
    private static function executeInProcess(array $request): array
    {
        global $app, $config, $lang;
        if(empty($app)) throw new RuntimeException('Current app is not initialized.');
        if(!class_exists('api'))
        {
            $appRoot = $app->getBasePath();
            include_once $appRoot . '/framework/api/router.class.php';
        }
        if(!class_exists('api')) throw new RuntimeException('API router class not found.');

        $appRoot = rtrim($app->getBasePath(), '/');
        $state   = new apiV2StateManager();
        $state->snapshot($appRoot);

        try
        {
            $apiConfig = unserialize(serialize($config));
            $apiLang   = unserialize(serialize($lang));
            $apiConfig->debug = max(3, (int)($apiConfig->debug ?? 0));

            $apiApp = self::createApiApp($app, $apiConfig, $apiLang, $request);

            $GLOBALS['app']    = $apiApp;
            $GLOBALS['config'] = $apiConfig;
            $GLOBALS['lang']   = $apiLang;

            $apiDao = new dao($apiApp);
            $GLOBALS['dao']   = $apiDao;
            $apiApp->dao      = $apiDao;

            $apiCommon = new commonModel();
            $apiCommon->setCompany();
            $apiCommon->setUser();
            $apiCommon->setApproval();
            $apiCommon->loadConfigFromDB();
            $apiCommon->loadCustomFromDB();
            $apiConfig->debug = max(3, (int)($apiConfig->debug ?? 0));

            self::applyAccount($apiApp, $apiCommon, $request['account']);

            $GLOBALS['common'] = $apiCommon;

            $apiApp->setRequestBody(self::buildBodyString($request['body']));

            $baseObLevel = ob_get_level();
            ob_start();

            try
            {
                $apiApp->parseRequest();

                if($apiApp->apiVersion == 'v2') $apiApp->checkPriv();

                $apiApp->loadModuleForInternal();

                $output = '';
                while(ob_get_level() > $baseObLevel) $output .= ob_get_clean();
                $output = $apiApp->formatData(helper::removeUTF8Bom($output));
            }
            catch(EndResponseException $endResponseException)
            {
                while(ob_get_level() > $baseObLevel) ob_end_clean();
                $output = $endResponseException->getContent();
            }
            catch(\Throwable $throwable)
            {
                error_log('[apiV2Invoker] ' . $throwable->getMessage());
                while(ob_get_level() > $baseObLevel) ob_end_clean();
                $output = json_encode(array('status' => 'fail', 'message' => $throwable->getMessage()));
            }

            $statusCode = http_response_code();
            if(!$statusCode) $statusCode = 200;

            return array('status_code' => (int)$statusCode, 'body' => (string)$output);
        }
        finally
        {
            $state->restore();
            error_clear_last();
        }
    }

    /**
     * 创建临时 api app，不执行构造函数。
     * Create temporary api app without running constructor.
     *
     * @param  object $app
     * @param  object $apiConfig
     * @param  object $apiLang
     * @param  array  $request
     * @access private
     * @static
     * @return object
     */
    private static function createApiApp(object $app, object $apiConfig, object $apiLang, array $request): object
    {
        $reflection = new ReflectionClass('api');
        $apiApp     = $reflection->newInstanceWithoutConstructor();

        foreach(get_object_vars($app) as $name => $value) $apiApp->$name = $value;

        $apiApp->config = $apiConfig;
        $apiApp->lang   = $apiLang;

        $apiApp->apiVersion        = 'v2';
        $apiApp->path              = $request['path'];
        $apiApp->action            = strtolower($request['method']);
        $apiApp->httpMethod        = strtolower($request['method']);
        $apiApp->viewType          = 'json';
        $apiApp->params            = array();
        $apiApp->paramNames        = array();
        $apiApp->entry             = '';
        $apiApp->responseExtractor = '*';
        $apiApp->routeInfo         = array();
        $apiApp->originRouteInfo   = array();
        $apiApp->realRouteInfo     = array();
        $apiApp->rawGet            = array();
        $apiApp->routeData         = array();
        $apiApp->workflowSaveStep  = false;

        self::applySuperglobals($apiApp, $request);

        if(method_exists($apiApp, 'loadApiLang')) $apiApp->loadApiLang();

        return $apiApp;
    }

    /**
     * 设置模拟请求超全局变量。
     * Set simulated superglobals.
     *
     * @param  object $apiApp
     * @param  array  $request
     * @access private
     * @static
     * @return void
     */
    private static function applySuperglobals(object $apiApp, array $request): void
    {
        $_GET    = $request['query'];
        $_POST   = array();
        $_FILES  = array();
        $_COOKIE = $_COOKIE ?? array();

        $_GET['account']  = $request['account'];
        $_POST['account'] = $request['account'];

        $_SERVER['REQUEST_METHOD']  = strtoupper($request['method']);
        $_SERVER['SCRIPT_FILENAME'] = $apiApp->getWwwRoot() . 'api.php';
        $_SERVER['SCRIPT_NAME']     = '/api.php';
        $_SERVER['REQUEST_URI']     = '/api.php/v2' . $request['path'] . ($request['query'] ? '?' . http_build_query($request['query']) : '');
        if(empty($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT'] = 'ZentaoApiV2Invoker/1.0';

        foreach($request['headers'] as $name => $value)
        {
            $name = strtoupper(str_replace('-', '_', $name));
            if($name != 'CONTENT_TYPE' and $name != 'CONTENT_LENGTH') $name = 'HTTP_' . $name;
            $_SERVER[$name] = $value;
        }

        self::applyFiles($request['files']);
    }

    /**
     * 准备模拟上传文件。
     * Prepare simulated upload files.
     *
     * @param  array $files
     * @access private
     * @static
     * @return void
     */
    private static function applyFiles(array $files): void
    {
        foreach($files as $field => $file)
        {
            if(is_array($file) and isset($file['tmp_name']))
            {
                $fileList = array($file);
            }
            elseif(is_array($file))
            {
                $fileList = array_values($file);
            }
            else
            {
                $fileList = array((string)$file);
            }

            foreach($fileList as $index => $item)
            {
                if(is_string($item)) $item = array('tmp_name' => $item);
                if(!is_array($item)) continue;

                $tmpName = (string)($item['tmp_name'] ?? '');
                if(!is_file($tmpName)) continue;

                $upload = array(
                    'name'     => (string)($item['name'] ?? basename($tmpName)),
                    'type'     => (string)($item['type'] ?? ''),
                    'tmp_name' => $tmpName,
                    'error'    => (int)($item['error'] ?? UPLOAD_ERR_OK),
                    'size'     => (int)($item['size'] ?? filesize($tmpName)),
                );

                if(count($fileList) == 1)
                {
                    $_FILES[$field] = $upload;
                }
                else
                {
                    foreach($upload as $key => $value) $_FILES[$field][$key][$index] = $value;
                }
            }
        }
    }

    /**
     * 以目标账号身份执行。
     * Apply target account.
     *
     * @param  object $apiApp
     * @param  object $apiCommon
     * @param  string $account
     * @access private
     * @static
     * @return void
     */
    private static function applyAccount(object $apiApp, object $apiCommon, string $account): void
    {
        if($account == 'guest') return;

        $user = $apiApp->dao->select('*')->from(TABLE_USER)->where('account')->eq($account)->andWhere('deleted')->eq('0')->fetch();
        if(!$user) throw new InvalidArgumentException("User account not found: $account");

        $userModel = $apiCommon->loadModel('user');

        $user->rights = $userModel->authorize($account);
        $user->groups = $userModel->getGroups($account);
        $user->view   = $userModel->grantUserView($account, $user->rights['acls']);
        $user->admin  = strpos($apiApp->company->admins, ",{$account},") !== false;

        $apiApp->session->set('user', $user);
        $apiApp->user = $apiApp->session->user;
    }

    /**
     * 构造请求体字符串。
     * Build request body string.
     *
     * @param  mixed $body
     * @access private
     * @static
     * @return string
     */
    private static function buildBodyString($body): string
    {
        if(is_string($body)) return $body;
        if($body === null) return '';
        return json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if(!function_exists('apiV2_request'))
{
    /**
     * 内部 APIv2 请求函数。
     * Internal APIv2 request function.
     *
     * @param  array $request
     * @access public
     * @return mixed
     */
    function apiV2_request(array $request)
    {
        return apiV2Invoker::request($request);
    }
}
