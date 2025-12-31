<?php declare(strict_types=1);
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
#[AllowDynamicProperties]
class entry extends baseEntry
{
    public function __construct()
    {
        parent::__construct();

        if($this->app->action == 'options') throw EndResponseException::create($this->send(204));

        if(!$this->loadModel('user')->isLogon()) throw EndResponseException::create($this->sendError(401, 'Unauthorized'));

        $this->dao = $this->loadModel('common')->dao;
    }
}

/**
 * 禅道API的baseEntry类。
 * The baseEntry class file of ZenTao API.
 *
 */
#[AllowDynamicProperties]
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
     * @access public
     * @return mixed
     */
    public function request(string $key, $defaultValue = '')
    {
        if(isset($this->requestBody->$key)) return $this->requestBody->$key;
        return $defaultValue;
    }

    /**
     * 获取请求参数
     * Get request params.
     *
     * @param  string $key
     * @access public
     * @return mixed
     */
    public function param(string $key, $defaultValue = '')
    {
        if(isset($_GET[$key])) return $_GET[$key];
        return $defaultValue;
    }

    /**
     * 设置请求参数
     * Set request param.
     *
     * @param  string|array  $key   if is array, set params by its key-value pairs.
     * @access public
     * @return void
     */
    public function setParam(string|array $key, $value = null)
    {
        if(is_array($key))
        {
            foreach($key as $k => $v) $_GET[$k] = $v;
            return;
        }
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
     * 发送请求的响应数据
     * Send response data
     *
     * @param  int   $code
     * @param  mixed $data
     * @access public
     * @return string
     */
    public function send(int $code, mixed $data = '')
    {
        return helper::response($data, $code);
    }

    /**
     * 发送错误信息
     * Send error response
     *
     * @param  int    $code
     * @param  string|object|array $msg
     * @access public
     * @return string
     */
    public function sendError(int $code, string|object|array $msg)
    {
        $response = new stdclass();
        $response->error = $msg;

        return $this->send($code, $response);
    }

    /**
     * 发送成功提示
     * Send success response
     *
     * @param  int    $code
     * @param  string $msg
     * @access public
     * @return string
     */
    public function sendSuccess(int $code, string $msg)
    {
        $response = new stdclass();
        $response->message = $msg;

        return $this->send($code, $response);
    }

    /**
     * Send 400 response.
     *
     * @param  string message
     * @access public
     * @return string
     */
    public function send400(string $message = 'error')
    {
        return $this->sendError(400, $message);
    }

    /**
     * Send 404 response.
     *
     * @access public
     * @return string
     */
    public function send404()
    {
        return $this->sendError(404, '404 Not found');
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
    public function loadController(string $moduleName, string $methodName)
    {
        ob_start();

        global $app;
        if(!class_exists($moduleName) and !class_exists("my$moduleName"))
        {
            $app->setModuleName($moduleName);
            $app->setMethodName($methodName);
            $app->viewType = 'json';

            /* Check user permission. */
            $this->checkPriv();

            $app->setControlFile();
            $app->importControlFile();
        }

        /*
         * 设置control的类名。
         * Set the class name of the control.
         **/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $app->triggerError("the control $className not found", __FILE__, __LINE__, true);

        $controller = new $className();
        $controller->viewType = 'json';
        $app->control = $controller;

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
    public function loadModel(string $moduleName = '', string $appName = '')
    {
        if(empty($moduleName)) $moduleName = $this->app->moduleName;
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
            $this->dao = new dao($this->app);
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
            if(!class_exists($modelClass)) $this->app->triggerError(" The model $modelClass not found", __FILE__, __LINE__, true);
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
        $output = json_decode((string) $output);
        if(isset($output->data) && !is_object($output->data)) $output->data   = json_decode((string) $output->data);
        if(!isset($output->status) && isset($output->result)) $output->status = $output->result;
        if(isset($output->load->alert))
        {
            $output->code    = 400;
            $output->status  = 'fail';
            $output->message = $output->load->alert;
            unset($output->load);
        }

        return $output;
    }

    /**
     * 添加$_POST全局变量.
     * Add data to $_POST.
     *
     * @param  string $key
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
     * @access public
     * @return void
     */
    public function batchSetPost(string $fields, $object = '')
    {
        $fields = explode(',', $fields);

        /* Append flow fields to post. */
        if($this->config->edition != 'open')
        {
            $fieldList = $this->loadModel('workflowaction')->getPageFields($this->app->rawModule, $this->app->rawMethod, true, null, 0, 0);
            if(!empty($fieldList))
            {
                foreach($fieldList as $field)
                {
                    if(!in_array($field->field, $fields)) $fields[] = $field->field;
                }
            }
        }

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
     * @access public
     * @return void
     */
    public function requireFields(string $fields)
    {
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            if(!isset($_POST[$field]))
            {
                $module = $this->app->moduleName;
                $name   = $this->app->lang->$module->$field ?? $field;
                throw EndResponseException::create($this->sendError(400, sprintf($this->app->lang->error->notempty, $name)));
            }
        }
    }

    /**
     * 检查是否在后台启用了代号.
     * Check whether config->setCode are used in product,project,execution.
     *
     * @access public
     * @return bool
     */
    public function checkCodeUsed()
    {
        return isset($this->config->setCode) ? $this->config->setCode : 0;
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
    public function format(object|array $data, string $fields)
    {
        if(is_array($data))
        {
            foreach($data as $object) $this->formatFields($object, $fields);
        }
        else
        {
            $this->formatFields($data, $fields);
        }

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
    private function formatFields(object &$object, string $fields)
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
            else if(str_contains($type, 'array'))
            {
                $isArray = true;
                $type    = 'object';
            }

            /* Format value. */
            if(!$isArray)
            {
                $object->$key = $this->cast(trim((string) $object->$key, ','), $type);
                continue;
            }

            /* Format array. */
            $value = array();
            if(is_array($object->$key) or is_object($object->$key))
            {
                foreach($object->$key as $v) $value[] = $this->cast($v, $type);
            }
            else
            {
                $vs = explode(',', $object->$key);
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
     * @param  string $allowable
     * @access public
     * @return object
     */
    public function filterFields(object $object, string $allowable = '')
    {
        if(empty($allowable)) return $object;
        if(is_string($allowable)) $allowable = explode(',', $allowable);

        $filtered = new stdclass();
        foreach($allowable as $field)
        {
            $field = trim((string) $field);
            if(empty($field)) continue;
            if(!isset($object->$field)) continue;
            $filtered->$field = $object->$field;
        }

        return $filtered;
    }

    /**
     * Format user.
     *
     * @param  string       $account
     * @param  array|object $users
     * @access public
     * @return array
     */
    public function formatUser(string $account, array|object $users)
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
    private function cast($value, string $type)
    {
        switch($type)
        {
            case 'time':
                $timeFormat = $this->param('timeFormat', 'utc');
                if($timeFormat == 'utc')
                {
                    if(!$value or $value == '0000-00-00 00:00:00') return null;
                    return gmdate("Y-m-d\TH:i:s\Z", strtotime((string) $value));
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
                $values = explode(',', (string) $value);
                if(empty($values)) return array();

                $idList = array();
                foreach($values as $val)
                {
                    if($val !== '') $idList[] = (int) $val;
                }
                return $idList;
            case 'stringList':
                $values = explode(',', (string) $value);
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
                $values = explode(',', (string) $value);
                if(empty($values)) return array();

                $userList = array();
                foreach($values as $val)
                {
                    $val = $this->cast($val, 'user');
                    if($val) $userList[] = $val;
                }
                return $userList;
            case 'decodeHtml':
                return htmlspecialchars_decode((string) $value);
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
     * @return mixed
     */
    public function fetch(string $entry, string $method, array $params = array())
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
     * @return void|string
     */
    public function checkPriv()
    {
        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();
        if($module and $method and !$this->loadModel('common')->isOpenMethod($module, $method) and !commonModel::hasPriv($module, $method))
        {
            die($this->send(403, array('error' => 'Access not allowed')));
        }
    }

    /**
     * Reset open app.
     *
     * @param  string  $tab
     * @access public
     * @return void
     */
    public function resetOpenApp(string $tab)
    {
        $_COOKIE['tab'] = $tab;
        $this->app->tab = $tab;
        $this->app->session->tab = $tab;
    }
}
