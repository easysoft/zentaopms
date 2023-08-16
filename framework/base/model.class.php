<?php
/**
 * ZenTaoPHP的baseModel类。
 * The baseModel class file of ZenTaoPHP framework.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
class baseModel
{
    /**
     * 全局对象$app。
     * The global $app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 应用名称$appName。
     * The global appName.
     *
     * @var string
     * @access public
     */
    public $appName;

    /**
     * 全局对象$config。
     * The global $config object.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * 全局对象$lang。
     * The global $lang object.
     *
     * @var object
     * @access public
     */
    public $lang;

    /**
     * 全局对象$dbh，数据库连接句柄。
     * The global $dbh object, the database connection handler.
     *
     * @var object
     * @access public
     */
    public $dbh;

    /**
     * $dao对象，用于访问或者更新数据库。
     * The $dao object, used to access or update database.
     *
     * @var dao
     * @access public
     */
    public $dao;

    /**
     * $post对象，用于访问$_POST变量。
     * The $post object, used to access the $_POST var.
     *
     * @var object
     * @access public
     */
    public $post;

    /**
     * $get对象，用于访问$_GET变量。
     * The $get object, used to access the $_GET var.
     *
     * @var object
     * @access public
     */
    public $get;

    /**
     * $session对象，用于访问$_SESSION变量。
     * The $session object, used to access the $_SESSION var.
     *
     * @var object
     * @access public
     */
    public $session;

    /**
     * $server对象，用于访问$_SERVER变量。
     * The $server object, used to access the $_SERVER var.
     *
     * @var object
     * @access public
     */
    public $server;

    /**
     * $cookie对象，用于访问$_COOKIE变量。
     * The $cookie object, used to access the $_COOKIE var.
     *
     * @var object
     * @access public
     */
    public $cookie;

    /**
     * $global对象，用于访问$_GLOBAL变量。
     * The $global object, used to access the $_GLOBAL var.
     *
     * @var object
     * @access public
     */
    public $global;

    /**
     * 构造方法。
     * 1. 将全局变量设为model类的成员变量，方便model的派生类调用；
     * 2. 设置$config, $lang, $dbh, $dao。
     *
     * The construct function.
     * 1. global the global vars, refer them by the class member such as $this->app.
     * 2. set the paths, config, lang of current module
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($appName = '')
    {
        global $app, $config, $lang, $dbh;
        $this->app     = $app;
        $this->config  = $config;
        $this->lang    = $lang;
        $this->dbh     = $dbh;
        $this->appName = empty($appName) ? $this->app->getAppName() : $appName;

        $moduleName = $this->getModuleName();
        if($this->config->framework->multiLanguage) $this->app->loadLang($moduleName, $this->appName);
        if($moduleName != 'common') $this->app->loadModuleConfig($moduleName, $this->appName);

        $this->loadDAO();
        $this->setSuperVars();
        $this->loadCache();

        /**
         * 读取当前模块的tao类。
         * Load the tao file auto.
         */
        $taoClass      = $moduleName . 'Tao';
        $selfClass     = get_class($this);
        $parentClasses = class_parents($this);
        if($selfClass != $taoClass && !isset($parentClasses[$taoClass])) $this->loadTao($moduleName, $this->appName);
    }

    /**
     * 获取该model的模块名，而不是用户请求的模块名。
     *
     * 这个方法通过去掉该model类名的'ext'和'model'字符串，来获取当前模块名。
     * 不要使用$app->getModuleName()，因为其返回的是用户请求的模块名。
     * 另一个model可以通过loadModel()加载进来，与请求的模块名不一致。
     *
     * Get the module name of this model. Not the module user visiting.
     *
     * This method replace the 'ext' and 'model' string from the model class name, thus get the module name.
     * Not using $app->getModuleName() because it return the module user is visiting. But one module can be
     * loaded by loadModel() so we must get the module name of this model.
     *
     * @access public
     * @return string the module name.
     */
    public function getModuleName()
    {
        $className     = get_class($this);
        $parentClasses = class_parents($this);
        if(count($parentClasses) > 2) $className = current(array_slice($parentClasses, -3, 1));
        if(strtolower(substr($className, -5)) == 'model') $className = strtolower(substr($className, 0, strlen($className) - 5));
        return $className;
    }

    /**
     * 设置全局超级变量。
     * Set the super vars.
     *
     * @access public
     * @return void
     */
    public function setSuperVars()
    {
        $this->post    = $this->app->post;
        $this->get     = $this->app->get;
        $this->server  = $this->app->server;
        $this->cookie  = $this->app->cookie;
        $this->session = $this->app->session;
    }

    /**
     * 加载一个模块的model对象。加载完成后，使用$this->$moduleName来访问这个model对象。
     * 比如：loadModel('user')引入user模块的model实例对象，可以通过$this->user来访问它。
     *
     * Load the model object of one module. After loaded, can use $this->$moduleName to visit the model object.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadModel($moduleName, $appName = '')
    {
        $model = $this->app->loadTarget($moduleName, $appName);
        if(!$model) return false;

        $this->{$moduleName} = $model;
        return $model;
    }

    /**
     * 加载一个模块的tao对象。加载完成后，使用$this->{$moduleName}Tao来访问这个tao对象。
     * 比如：loadTao('user')引入user模块的tao实例对象，可以通过$this->userTao来访问它。
     *
     * Load the tao object of one module. After loaded, can use $this->{$moduleName}Tao to visit the tao object.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有tao文件，返回false，否则返回tao对象。If no tao file, return false, else return the tao object.
     */
    public function loadTao($moduleName, $appName = '')
    {
        $tao = $this->app->loadTarget($moduleName, $appName, 'tao');
        if(!$tao) return false;

        $taoObjectName = $moduleName . 'Tao';
        $this->{$taoObjectName} = $tao;
        return $tao;
    }

    /**
     * 加载model的class扩展，主要是为了开发加密代码使用。
     * 可以将主要的逻辑存放到$moduleName/ext/model/class/$extensionName.class.php中。
     * 然后在ext/model/$extension.php的扩展里面使用$this->loadExtension()来调用相应的方法。
     * ext/model/class/*.class.php代码可以加密。而ext/model/*.php可以不用加密。
     * 因为框架对model的扩展是采取合并文件的方式，ext/model/*.php文件不能加密。
     *
     * Load extension class of a model thus user can encrypt the code.
     * You can put the main extension logic codes in $moduleName/ext/model/class/$extensionName.class.php.
     * And call them by the ext/model/$extension.php like this: $this->loadExtension('myextension')->method().
     * You can encrypt the code in ext/model/class/*.class.php.
     * Because the framework will merge the extension files in ext/model/*.php to the module/model.php.
     *
     * @param  string $extensionName
     * @param  string $moduleName
     * @access public
     * @return void
     */
    public function loadExtension($extensionName, $moduleName = '')
    {
        if(empty($extensionName)) return false;
        if(empty($moduleName)) $moduleName = $this->getModuleName();

        $moduleName    = strtolower($moduleName);
        $extensionName = strtolower($extensionName);

        $type      = 'model';
        $className = strtolower(get_class($this));
        if($className == $moduleName . 'tao' || $className == 'ext' . $moduleName . 'tao') $type = 'tao';

        /* 设置扩展类的名字。Set the extension class name. */
        $extensionClass = $extensionName . ucfirst($moduleName);
        if($type != 'model') $extensionClass .= ucfirst($type);
        if(isset($this->$extensionClass)) return $this->$extensionClass;

        /* 设置扩展的名字和相应的文件。Set extenson name and extension file. */
        $moduleExtPath = $this->app->getModuleExtPath($moduleName, $type);
        if(!empty($moduleExtPath['site'])) $extensionFile = $moduleExtPath['site'] . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['saas']   . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['custom'] . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['vision'] . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['xuan']   . 'class/' . $extensionName . '.class.php';
        if(!isset($extensionFile) or !file_exists($extensionFile)) $extensionFile = $moduleExtPath['common'] . 'class/' . $extensionName . '.class.php';

        /* 载入父类。Try to import parent model file auto and then import the extension file. */
        if(!class_exists($moduleName . ucfirst($type))) helper::import($this->app->getModulePath($this->appName, $moduleName) . $type . '.php');
        if(!helper::import($extensionFile)) return false;
        if(!class_exists($extensionClass)) return false;

        /* 实例化扩展类。Create an instance of the extension class and return it. */
        $extensionObject = new $extensionClass;
        if($type == 'model') $extensionClass = str_replace(ucfirst($type), '', $extensionClass);
        $this->$extensionClass = $extensionObject;
        return $extensionObject;
    }

    /**
     * 加载DAO。
     * Load DAO.
     *
     * @access public
     * @return void
     */
    public function loadDAO()
    {
        global $config, $dao;
        if(is_object($dao)) return $this->dao = $dao;

        $driver = $config->db->driver;

        if(!class_exists($driver))
        {
            $classFile = $this->app->coreLibRoot . 'dao' . DS . $driver . '.class.php';
            include($classFile);
        }

        $dao = new $driver();
        $this->dao = $dao;
    }

    /**
     * 加载缓存类。
     * Load cache class.
     *
     * @access public
     * @return void
     */
    public function loadCache()
    {
        $this->app->loadClass('cache', $static = true);
        $namespace   = isset($this->session->user->account) ? $this->session->user->account : 'guest';
        $this->cache = cache::create($this->config->cache->driver, $namespace, $this->config->cache->lifetime);
    }

    /**
     * 删除记录。
     * Delete one record.
     *
     * @param  string    $table  the table name
     * @param  string    $id     the id value of the record to be deleted
     * @access public
     * @return void
     */
    public function delete($table, $id)
    {
        $this->dao->delete()->from($table)->where('id')->eq($id)->exec();
    }
}
