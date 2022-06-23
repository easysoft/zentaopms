<?php
/**
 * 禅道API的entry类。
 * The entry class file of ZenTao API.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
class entry extends baseEntry
{
    public function __construct()
    {
        parent::__construct();

        if($this->app->action == 'options') return $this->send(204);
        
        if(!isset($this->app->user) or $this->app->user->account == 'guest') $this->sendError(401, 'Unauthorized');

        $this->dao = $this->loadModel('common')->dao;
    }
}

/**
 * 禅道API的baseEntry类。
 * The baseEntry class file of ZenTao API.
 *
 */
class baseEntry
{
    /**
     * 全局对象 $app。
     * The global $app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 语言项 $lang。
     * The global $app object.
     *
     * @var object
     * @access public
     */
    public $lang;

    /**
     * 提交的POST数据
     * The decoded request body.
     *
     * @var object
     * @access public
     */
    public $requestBody;

    /**
     * 构造方法。
     * The construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app, $config, $lang;

        $this->app    = $app;
        $this->config = $config;
        $this->lang   = $lang;

        $this->parseRequestBody();
    }

    /**
     * 获取请求数据(POST PUT)
     * Get request data(POST or PUT)
     *
     * @param  string $key
     * @param  mixed  $defaultValue
     * @access public
     * @return mixed
     */
    public function request($key, $defaultValue = '')
    {
        if(isset($this->requestBody->$key)) return $this->requestBody->$key;
        return $defaultValue;
    }

    /**
     * 获取请求参数
     * Get request params.
     *
     * @param  string $key
     * @param  string $defaultValue
     * @access public
     * @return mixed
     */
    public function param($key, $defaultValue = '')
    {
        if(isset($_GET[$key])) return $_GET[$key];
        return $defaultValue;
    }

    /**
     * 设置请求参数
     * Set request param.
     *
     * @param  string $key
     * @param  mixed  $value
     * @access public
     * @return mixed
     */
    public function setParam($key, $value)
    {
        $_GET[$key] = $value;
    }

    /**
     * 解析请求数据
     * Parse body of request data.
     *
     * @access public
     * @return void
     */
    private function parseRequestBody()
    {
        $this->requestBody = new stdClass();

        if($this->app->action == 'post' or $this->app->action == 'put')
        {
            $requestBody = file_get_contents("php://input");
            if($requestBody) $this->requestBody = json_decode($requestBody);
        }
    }

    /**
     * HTTP状态码
     * HTTP status code
     *
     * @access public
     */
    public $statusCode = array(
        100 => "100 Continue",
        101 => "101 Switching Protocols",
        102 => "102 Processing",

        200 => "200 OK",
        201 => "201 Created",
        202 => "202 Accepted",
        203 => "203 Non-Authoritative Information",
        204 => "204 No Content",
        205 => "205 Reset Content",
        206 => "206 Partial Content",
        207 => "207 Multi-Status",

        300 => "300 Multiple Choices",
        301 => "301 Moved Permanently",
        302 => "302 Found",
        303 => "303 See Other",
        304 => "304 Not Modified",
        305 => "305 Use Proxy",
        307 => "307 Temporary Redirect",

        400 => "400 Bad Request",
        401 => "401 Authorization Required",
        402 => "402 Payment Required",
        403 => "403 Forbidden",
        404 => "404 Not Found",
        405 => "405 Method Not Allowed",
        406 => "406 Not Acceptable",
        407 => "407 Proxy Authentication Required",
        408 => "408 Request Time-out",
        409 => "409 Conflict",
        410 => "410 Gone",
        411 => "411 Length Required",
        412 => "412 Precondition Failed",
        413 => "413 Request Entity Too Large",
        414 => "414 Request-URI Too Large",
        415 => "415 Unsupported Media Type",
        416 => "416 Requested Range Not Satisfiable",
        417 => "417 Expectation Failed",
        422 => "422 Unprocessable Entity",
        423 => "423 Locked",
        424 => "424 Failed Dependency",
        426 => "426 Upgrade Required",
    );

    /**
     * 发送请求的响应数据
     * Send response data
     *
     * @param  int   $code
     * @param  mixed $data
     * @access public
     * @return void
     */
    public function send($code, $data = '')
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,Authorization,Token,Referer,User-Agent");
        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');
        header("Content-type: application/json");
        header("HTTP/1.1 {$this->statusCode[$code]}");

        if($data) echo json_encode($data, JSON_HEX_TAG);
        exit;
    }

    /**
     * 发送错误信息
     * Send error response
     *
     * @param  int    $code
     * @param  string $msg
     * @access public
     * @return void
     */
    public function sendError($code, $msg)
    {
        $response = new stdclass();
        $response->error = $msg;

        $this->send($code, $response);
    }

    /**
     * 发送成功提示
     * Send success response
     *
     * @param  int    $code
     * @param  string $msg
     * @access public
     * @return void
     */
    public function sendSuccess($code, $msg)
    {
        $response = new stdclass();
        $response->message = $msg;

        $this->send($code, $response);
    }

    /**
     * Send 400 response.
     *
     * @param  string message
     * @access public
     * @return void
     */
    public function send400($message = 'error')
    {
        $this->sendError(400, $message);
    }

    /**
     * Send 404 response.
     *
     * @access public
     * @return void
     */
    public function send404()
    {
        $this->sendError(404, '404 Not found');
    }

    /**
     * 加载禅道的控制器类
     * Load controller of zentaopms
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return object
     */
    public function loadController($moduleName, $methodName)
    {
        ob_start();

        if(!class_exists($moduleName) and !class_exists("my$moduleName"))
        {
            global $app;
            $app->setModuleName($moduleName);
            $app->setMethodName($methodName);
            $app->viewType = 'json';

            /* Check user permission. */
            $this->checkPriv();

            $app->setControlFile();

            /*
             * 引入该模块的control文件。
             * Include the control file of the module.
             **/

            $file2Included = $app->setActionExtFile() ? $app->extActionFile : $app->controlFile;

            $isExt = $app->setActionExtFile();
            if($isExt)
            {
                $controlFile = $app->controlFile;
                spl_autoload_register(function($class) use ($moduleName, $controlFile)
                {
                    if($class == $moduleName) include $controlFile;
                });
            }

            $file2Included = $isExt ? $app->extActionFile : $app->controlFile;
            chdir(dirname($file2Included));
            helper::import($file2Included);
        }

        /*
         * 设置control的类名。
         * Set the class name of the control.
         **/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $app->triggerError("the control $className not found", __FILE__, __LINE__, $exit = true);

        $controller = new $className();
        $controller->viewType = 'json';

        return $controller;
    }

    /**
     * 加载指定模块的model文件。
     * Load the model file of one module.
     *
     * @param   string  $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param   string  $appName    The app name, if empty, use current app's name.
     * @access  public
     * @return  object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadModel($moduleName = '', $appName = '')
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($appName))    $appName    = $this->app->appName;

        global $loadedModels;
        if(isset($loadedModels[$appName][$moduleName]))
        {
            $this->$moduleName = $loadedModels[$appName][$moduleName];
            $this->dao = $this->$moduleName->dao;
            return $this->$moduleName;
        }

        $modelFile = $this->app->setModelFile($moduleName, $appName);

        /**
         * 如果没有model文件，尝试加载config配置信息。
         * If no model file, try load config.
         */
        if(!helper::import($modelFile))
        {
            $this->app->loadModuleConfig($moduleName, $appName);
            $this->app->loadLang($moduleName, $appName);
            $this->dao = new dao();
            return false;
        }

        /**
         * 如果没有扩展文件，model类名是$moduleName + 'model'，如果有扩展，还需要增加ext前缀。
         * If no extension file, model class name is $moduleName + 'model', else with 'ext' as the prefix.
         */
        $modelClass = class_exists('ext' . $appName . $moduleName. 'model') ? 'ext' . $appName . $moduleName . 'model' : $appName . $moduleName . 'model';
        if(!class_exists($modelClass))
        {
            $modelClass = class_exists('ext' . $moduleName. 'model') ? 'ext' . $moduleName . 'model' : $moduleName . 'model';
            if(!class_exists($modelClass)) $this->app->triggerError(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);
        }

        /**
         * 初始化model对象，在control对象中可以通过$this->$moduleName来引用。同时将dao对象赋为control对象的成员变量，方便引用。
         * Init the model object thus you can try $this->$moduleName to access it. Also assign the $dao object as a member of control object.
         */
        $loadedModels[$appName][$moduleName] = new $modelClass($appName);
        $this->$moduleName = $loadedModels[$appName][$moduleName];
        $this->dao = $this->$moduleName->dao;

        return $this->$moduleName;
    }

    /**
     * 获取控制器执行返回的数据，在output缓存中.
     * Get controller data from output.
     *
     * @access  public
     * @return  object.
     */
    public function getData()
    {
        $output = helper::removeUTF8Bom(ob_get_clean());
        $output = json_decode($output);
        if(isset($output->data)) $output->data = json_decode($output->data);

        return $output;
    }

    /**
     * 添加$_POST全局变量.
     * Add data to $_POST.
     *
     * @param  string $key
     * @param  mixed  $value
     * @access public
     * @return void
     */
    public function setPost($key, $value)
    {
        $_POST[$key] = $value;
    }

    /**
     * 批量添加$_POST全局变量.
     * Batch set data to $_POST.
     *
     * @param  string $fields
     * @param  mixed  $object
     * @access public
     * @return void
     */
    public function batchSetPost($fields, $object = '')
    {
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            /*
             * If the field exists in request body, use it.
             * Otherwise set default value from $object.
             */
            if(isset($this->requestBody->$field))
            {
                $value = $this->requestBody->$field;
            }
            else
            {
                if(!$object or !isset($object->$field)) continue;
                $value = $object->$field;
            }

            $this->setPost($field, $value);
        }
    }

    /**
     * 确保字段不能为空.
     * Make sure the fields is not empty.
     *
     * @param  string $fields
     * @param  mixed  $object
     * @access public
     * @return void
     */
    public function requireFields($fields)
    {
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            if(!isset($_POST[$field]))
            {
                $module = $this->app->moduleName;
                $name   = isset($this->app->lang->$module->$field) ? $this->app->lang->$module->$field : $field;
                $this->sendError(400, sprintf($this->app->lang->error->notempty, $name));
            }
        }
    }

    /**
     * 格式化数据的字段类型.
     * Format fields of response data.
     *
     * @param  object|array $data
     * @param  string $fields
     * @access public
     * @return object|array
     */
    public function format($data, $fields)
    {
        if(is_array($data))
        {
            foreach($data as $object) $this->formatFields($object, $fields);
        }
        $this->formatFields($data, $fields);

        return $data;
    }

    /**
     * 格式化对象的字段类型.
     * Format fields of object.
     *
     * @param  object $object
     * @param  string $fields
     * @access public
     * @return object
     */
    private function formatFields(&$object, $fields)
    {
        $fields = explode(',', $fields);

        foreach($fields as $field)
        {
            $field   = explode(':', $field);
            $key     = $field[0];
            $type    = $field[1];
            $isArray = false;

            if(!isset($object->$key)) continue;

            $pos = strpos($type, ']');
            if($pos !== false)
            {
                $isArray = true;
                $type    = substr($type, $pos + 1);
            }
            else if(strpos($type, 'array') !== false)
            {
                $isArray = true;
                $type    = 'object';
            }

            /* Format value. */
            if(!$isArray)
            {
                $object->$key = $this->cast(trim($object->$key, ','), $type);
                continue;
            }

            /* Format array. */
            $value = array();
            if(is_array($object->$key))
            {
                foreach($object->$key as $v) $value[] = $this->cast($v, $type);
            }
            else
            {
                $vs = implode(',', $object->$key);
                foreach($vs as $v)
                {
                    if($v === '') continue;
                    $value[] = $this->cast($v, $type);
                }
            }
            $object->$key = $value;
        }
    }

    /**
     * Filter fields.
     *
     * @param  object $object
     * @param  array  $filters
     * @access public
     * @return object
     */
    public function filterFields($object, $allowable = '')
    {
        if(empty($allowable)) return $object;
        if(is_string($allowable)) $allowable = explode(',', $allowable);

        $filtered = new stdclass();
        foreach($allowable as $field)
        {
            $field = trim($field);
            if(empty($field)) continue;
            if(!isset($object->$field)) continue;
            $filtered->$field = $object->$field;
        }

        return $filtered;
    }

    /**
     * Format user.
     *
     * @param  string    $account
     * @param  array     $users
     * @access public
     * @return array
     */
    public function formatUser($account, $users)
    {
        $user = array();
        $user['account']  = $account;
        $user['realname'] = zget($users, $account);

        return $user;
    }

    /**
     * 类型转换.
     * Typecasting.
     *
     * @param  mixed  $vaule
     * @param  string $type
     * @access public
     * @return mixed
     */
    private function cast($value, $type)
    {
        switch($type)
        {
            case 'time':
                $timeFormat = $this->param('timeFormat', 'utc');
                if($timeFormat == 'utc')
                {
                    if(!$value or $value == '0000-00-00 00:00:00') return null;
                    return gmdate("Y-m-d\TH:i:s\Z", strtotime($value));
                }
                return $value;
            case 'date':
                if(!$value or $value == '0000-00-00') return null;
                return $value;
            case 'bool':
                return !empty($value);
            case 'int':
                return (int) $value;
            case 'idList':
                $values = explode(',', $value);
                if(empty($values)) return array();

                $idList = array();
                foreach($values as $val)
                {
                    if($val !== '') $idList[] = (int) $val;
                }
                return $idList;
            case 'stringList':
                $values = explode(',', $value);
                if(empty($values)) return array();

                $stringList = array();
                foreach($values as $val)
                {
                    if($val !== '') $stringList[] = $val;
                }
                return $stringList;
            case 'array':
                $array = array();
                if(!empty($value)) foreach($value as $v) $array[] = $v;
                return $array;
            case 'user':
                if(empty($value)) return null;
                if(empty($this->users)) $this->users = $this->dao->select('id,account,avatar,realname')->from(TABLE_USER)->fetchAll('account');
                return zget($this->users, $value, null);
            case 'userList':
                $values = explode(',', $value);
                if(empty($values)) return array();

                $userList = array();
                foreach($values as $val)
                {
                    $val = $this->cast($val, 'user');
                    if($val) $userList[] = $val;
                }
                return $userList;
            default:
                return $value;
        }
    }

    /**
     * 获取其他方法的执行结果。
     * Fetch result of other method.
     *
     * @param  string $entry
     * @param  string $method
     * @param  array  $params
     * @access public
     * @return void
     */
    public function fetch($entry, $method, $params = array())
    {
        include($this->app->appRoot . "api/{$this->app->version}/entries/" . strtolower($entry) . ".php");

        $entryName = $entry . 'Entry';
        $entry     = new $entryName();
        return call_user_func_array(array($entry, $method), $params);
    }

    /**
     * Check the user has permission to access this method, if not, return 403.
     *
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();
        if($module and $method and !$this->loadModel('common')->isOpenMethod($module, $method) and !commonModel::hasPriv($module, $method))
        {
            $this->send(403, array('error' => 'Access not allowed'));
        }
    }

    /**
     * Reset open app.
     *
     * @param  string  $tab
     * @access public
     * @return void
     */
    public function resetOpenApp($tab)
    {
        $_COOKIE['tab'] = $tab;
        $this->app->tab = $tab;
        $this->app->session->tab = $tab;
    }
}
