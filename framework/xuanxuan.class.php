<?php
include 'router.class.php';
class xuanxuan extends router
{
    /**
     *  The request params.
     *
     * @var array
     * @access public
     */
    public $params = array();

    /**
     * 构造方法, 设置路径，类，超级变量等。注意：
     * 1.应该使用createApp()方法实例化router类；
     * 2.如果$appRoot为空，框架会根据$appName计算应用路径。
     *
     * The construct function.
     * Prepare all the paths, classes, super objects and so on.
     * Notice:
     * 1. You should use the createApp() method to get an instance of the router.
     * 2. If the $appRoot is empty, the framework will compute the appRoot according the $appName
     *
     * @param string $appName   the name of the app
     * @param string $appRoot   the root path of the app
     * @access public
     * @return void
     */
    public function __construct($appName = 'sys', $appRoot = '')
    {
        parent::__construct($appName, $appRoot);

        $this->setViewType();
        $this->setClientLang('zh-cn');
    }

    /**
     * Set view type.
     *
     * @access public
     * @return void
     */
    public function setViewType()
    {
        $this->viewType = 'json';
    }

    /**
     * 加载common模块。
     *
     *  common模块比较特别，它会执行几乎每次请求都需要执行的操作，例如：
     *  打开session，检查权限等等。
     *  加载完$lang, $config, $dbh后，需要在入口文件(www/index.php)中手动调用该方法。
     *
     * Load the common module
     *
     *  The common module is a special module, which can be used to do some common things. For examle:
     *  start session, check priviledge and so on.
     *  This method should called manually in the router file(www/index.php) after the $lang, $config, $dbh loaded.
     *
     * @access public
     * @return object|bool  the common model object or false if not exits.
     */
    public function loadCommon()
    {
        $result = parent::loadCommon();

        $this->initAES();

        return $result;
    }

    /**
     * Init aes object.
     *
     * @access public
     * @return void
     */
    public function initAES()
    {
        $key = $this->config->xuanxuan->key;
        $iv  = substr($key, 0, 16);
        $this->aes = $this->loadClass('phpaes');
        $this->aes->init($key, $iv);
        if($this->config->debug)
        {
            $this->log("engine: " . $this->aes->getEngine());
        }
    }

    /**
     * Set params.
     *
     * @param  array  $params
     * @access public
     * @return void
     */
    public function setParams($params = array())
    {
        $this->params = $params;
    }

    /**
     * 解析本次请求的入口方法，根据请求的类型(PATH_INFO GET)，调用相应的方法。
     * The entrance of parseing request. According to the requestType, call related methods.
     *
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        $input   = file_get_contents("php://input");
        $input   = $this->decrypt($input);
        $version = !empty($input->v)      ? $input->v : '';
        $userID  = !empty($input->userID) ? $input->userID : '';
        $client  = !empty($input->client) ? $input->client : '';
        $module  = !empty($input->module) ? $input->module : '';
        $method  = !empty($input->method) ? $input->method : '';
        $params  = !empty($input->params) ? $input->params : array();

        $module = strtolower($module);
        $method = strtolower($method);

        if(!isset($this->config->xuanxuan->enabledMethods[$module][$method]))
        {
            $data = new stdclass();
            $data->module = 'chat';
            $data->method = 'kickoff';
            $data->data   = 'Illegal Requset.';
            die($this->encrypt($data));
        }

        if($module == 'chat' && $method == 'login' && is_array($params))
        {
            /* params[0] is the server name. */
            unset($params[0]);
        }
        if(is_array($params))
        {
            $params[] = $userID;
            $params[] = $version;
        }

        $this->session->set('userID', $userID);
        $this->session->set('clientIP', $client);

        $this->setModuleName($module);
        $this->setMethodName($method);
        $this->setParams($params);
        $this->setControlFile();
    }

    /**
     * 加载一个模块：
     * 1. 引入控制器文件或扩展的方法文件；
     * 2. 创建control对象；
     * 3. 解析url，得到请求的参数；
     * 4. 使用call_user_function_array调用相应的方法。
     *
     * Load a module.
     * 1. include the control file or the extension action file.
     * 2. create the control object.
     * 3. set the params passed in through url.
     * 4. call the method by call_user_function_array
     *
     * @access public
     * @return bool|object  if the module object of die.
     */
    public function loadModule()
    {
        $appName    = $this->appName;
        $moduleName = $this->moduleName;
        $methodName = $this->methodName;

        /*
         * 引入该模块的control文件。
         * Include the control file of the module.
         **/
        $file2Included = $this->setActionExtFile() ? $this->extActionFile : $this->controlFile;
        chdir(dirname($file2Included));
        helper::import($file2Included);

        /*
         * 设置control的类名。
         * Set the class name of the control.
         **/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className))
        {
            $this->triggerError("the control $className not found", __FILE__, __LINE__);
            return false;
        }

        /*
         * 创建control类的实例。
         * Create a instance of the control.
         **/
        $module = new $className();
        if(!method_exists($module, $methodName))
        {
            $this->triggerError("the module $moduleName has no $methodName method", __FILE__, __LINE__);
            return false;
        }
        /* If the db server restarted, must reset dbh. */
        $this->control = $module;

        /* include default value for module*/
        $defaultValueFiles = glob($this->getTmpRoot() . "defaultvalue/*.php");
        if($defaultValueFiles) foreach($defaultValueFiles as $file) include $file;

        /*
         * 使用反射机制获取函数参数的默认值。
         * Get the default settings of the method to be called using the reflecting.
         *
         * */
        $defaultParams = array();
        $methodReflect = new reflectionMethod($className, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name = $param->getName();

            $default = '_NOT_SET';
            if(isset($paramDefaultValue[$appName][$className][$methodName][$name]))
            {
                $default = $paramDefaultValue[$appName][$className][$methodName][$name];
            }
            elseif(isset($paramDefaultValue[$className][$methodName][$name]))
            {
                $default = $paramDefaultValue[$className][$methodName][$name];
            }
            elseif($param->isDefaultValueAvailable())
            {
                $default = $param->getDefaultValue();
            }

            $defaultParams[$name] = $default;
        }

        /* Merge params. */
        $params = array();
        if(isset($this->params))
        {
            $params = $this->mergeParams($defaultParams, (array)$this->params);
        }
        else
        {
            $this->triggerError("param error: {$this->request->raw}", __FILE__, __LINE__);
            return false;
        }

        /* Call the method. */
        $this->response = call_user_func_array(array($module, $methodName), $params);
        return true;
    }

    /**
     * 合并请求的参数和默认参数，这样就可以省略已经有默认值的参数了。
     * Merge the params passed in and the default params. Thus the params which have default values needn't pass value, just like a function.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  public
     * @return  array the merged params.
     */
    public function mergeParams($defaultParams, $passedParams)
    {
        /* Remove these two params. */
        unset($passedParams['onlybody']);
        unset($passedParams['HTTP_X_REQUESTED_WITH']);

        /* Check params from URL. */
        foreach($passedParams as $param => $value)
        {
            if(preg_match('/[^a-zA-Z0-9_\.]/', $param)) die('Bad Request!');
        }

        $passedParams = array_values($passedParams);
        $i = 0;
        foreach($defaultParams as $key => $defaultValue)
        {
            if(isset($passedParams[$i]))
            {
                $defaultParams[$key] = $passedParams[$i];
            }
            else
            {
                if($defaultValue === '_NOT_SET') $this->triggerError("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
            }
            $i ++;
        }

        return $defaultParams;
    }

    /**
     * Decrypt an input string.
     *
     * @param  string $input
     * @access public
     * @return object
     */
    public function decrypt($input = '')
    {
        $input = $this->aes->decrypt($input);
        if($this->config->debug)
        {
            $this->log("decrypt: " . $input);
        }
        $input = json_decode($input);
        return $input;
    }

    /**
     * Encrypt an output object.
     *
     * @param  int    $output
     * @access public
     * @return string
     */
    public function encrypt($output = null)
    {
        if(is_object($output)) $output->v = $this->config->xuanxuan->version;

        $output = helper::jsonEncode($output);
        if($this->config->debug)
        {
            $this->log("encrypt: " . $output);
        }
        $output = $this->aes->encrypt($output);
        return helper::removeUTF8Bom($output);
    }

    /**
     * Save a log.
     *
     * @param  string $log
     * @param  string $file
     * @param  string $line
     * @access public
     * @return void
     */
    public function log($message, $file = '', $line = '')
    {
        $log = "\n" . date('H:i:s') . " $message";
        if($file) $log .= " in <strong>$file</strong>";
        if($line) $log .= " on line <strong>$line</strong> ";
        $file = $this->getLogRoot() . 'xuanxuan.log.php';
        if(!is_file($file)) file_put_contents($file, "<?php\n die();\n?>\n");

        $fh = @fopen($file, 'a');
        if($fh) fwrite($fh, $log) && fclose($fh);
    }
}
