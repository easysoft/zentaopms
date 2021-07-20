<?php
/**
 * 禅道API的entry类。
 * The entry class file of ZenTao API.
 */
class entry extends baseEntry
{
    public function __construct()
    {
        parent::__construct();

        if(!isset($this->app->user)) $this->sendError(401, 'Unauthorized');
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
        global $app;
        $this->app = $app;

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
     * @access public
     * @return void
     */
    public function send($code, $data)
    {
        header("Content-type: application/json");
        header("HTTP/1.1 {$this->statusCode[$code]}");
        echo json_encode($data);
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
        global $app;
        $app->setModuleName($moduleName);
        $app->setMethodName($methodName);
        $app->setControlFile();

        /*
         * 引入该模块的control文件。
         * Include the control file of the module.
         **/
        $file2Included = $app->setActionExtFile() ? $app->extActionFile : $app->controlFile;
        chdir(dirname($file2Included));
        helper::import($file2Included);

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
        $output = ob_get_clean();
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
}
