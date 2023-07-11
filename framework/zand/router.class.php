<?php
declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Jobs\Consumer;
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\Task\ReceivedTaskInterface;
use Spiral\RoadRunner\Worker;

include "vendor/autoload.php";
include 'response.class.php';

/**
 * The zand router class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * router类。
 * The router class.
 *
 * @package framework
 */
include dirname(__DIR__) . '/router.class.php';

class zandRouter extends router
{
    /**
     * 全局变量的快照
     * Snaps for global variables.
     *
     * @static
     * @var array
     * @access public
     */
    static $snaps = array();

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
    public function __construct(string $appName = 'demo', string $appRoot = '')
    {
        $_SERVER['HTTP_USER_AGENT'] = '';
        $_SERVER['SCRIPT_NAME']     = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__, 2) .  '/www/index.php';

        $this->worker   = new zandWorker();
        $this->consumer = new Consumer();

        parent::__construct($appName, $appRoot);

        /* Snap. */
        global $filter;

        self::$snaps['config'] = clone $this->config;
        self::$snaps['lang']   = clone $this->lang;
        self::$snaps['filter'] = clone $filter;
    }

    /**
     * 初始化全局变量和客户端信息。
     * Init global variables and client.
     *
     * @access public
     * @return void
     */
    public function initRequest(): void
    {
        global $config, $lang, $filter, $loadedTargets;

        $config        = clone self::$snaps['config'];
        $lang          = clone self::$snaps['lang'];
        $filter        = clone self::$snaps['filter'];
        $loadedTargets = array();

        $this->config     = $config;
        $this->lang       = $lang;
        $this->moduleName = NULL;
        $this->methodName = NULL;
        $this->rawModule  = NULL;
        $this->rawMethod  = NULL;

        self::$loadedConfigs = array();
        self::$loadedLangs   = array();

        $this->setClient();
    }

    /**
     * 关闭请求会话
     * Close request.
     *
     * @access public
     * @return void
     */
    public function closeRequest()
    {
        $obLevel = ob_get_level();
        for($i = 0; $i < $obLevel; $i++) ob_end_clean();

        session_write_close();
    }

    /**
     * 开启session。
     * Start session.
     *
     * @access public
     * @return void
     */
    public function startSession(): void
    {
        $sessionName = $this->config->sessionVar;

        if(!defined('SESSION_STARTED'))
        {
            global $config;

            $driver = $config->db->driver;
            if(!class_exists($driver))
            {
                $classFile = $this->coreLibRoot . 'dao' . DS . $driver . '.class.php';
                include($classFile);
            }
            $dao = new $driver();

            $ztSessionHandler = new zandSession($dao);
            session_set_save_handler(
                $ztSessionHandler->open(...),
                $ztSessionHandler->close(...),
                $ztSessionHandler->read(...),
                $ztSessionHandler->write(...),
                $ztSessionHandler->destroy(...),
                $ztSessionHandler->gc(...)
            );

            session_name($sessionName);
            session_set_cookie_params(0, $this->config->webRoot, '', $this->config->cookieSecure, true);

            define('SESSION_STARTED', true);
        }
        else
        {
            $this->sessionID = isset($_COOKIE[$sessionName]) ? $_COOKIE[$sessionName] : session_create_id();
            session_id($this->sessionID);
            session_start();

            $this->worker->response->setCookie($sessionName, $this->sessionID, 0);
        }
    }
}

/**
 * 消息队列的消息类型。
 * Message in queue.
 *
 * @package zand
 */
class zandMessage
{
    public $id;
    public $type;
    public $command;
}

/**
 * 消息队列。
 * Message queue.
 *
 * @package zand
 */
class zandQueue
{
    private $mq;

    public function __construct($queueName)
    {
        $jobs = new Jobs(RPC::create('tcp://127.0.0.1:6001'));

        $this->mq = $jobs->connect('crons');
    }

    public function push($message)
    {
        $task = $this->mq->create(zandMessage::class, $message);
        $this->mq->dispatch($task);
    }
}

/**
 * HTTP worker.
 *
 * @package zand
 */
class zandWorker
{
    /**
     * RoadRunner PSR7 worker.
     *
     * @var object
     * @access private
     */
    private $psr7;

    /**
     * response.
     *
     * @var object
     * @access public
     */
    public $response;

    /**
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $worker         = Worker::create();
        $factory        = new Psr17Factory();
        $this->psr7     = new PSR7Worker($worker, $factory, $factory, $factory);
        $this->response = new zandResponse();
    }

    /**
     * Wait request to run.
     *
     * @access public
     * @return void
     */
    public function waitRequest()
    {
        $request = $this->psr7->waitRequest();
        $this->initGlobal($request);
        $this->response = new zandResponse();
    }

    /**
     * Init global variables.
     *
     * @param  object $request
     * @access public
     * @return void
     */
    public function initGlobal($request)
    {
        $_SERVER = $request->getServerParams();
        $_SERVER['REQUEST_TIME']       = time();
        $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        $_SERVER['SERVER_PROTOCOL']    = $request->getUri()->getScheme();
        $_SERVER['REQUEST_METHOD']     = $request->getMethod();
        $_SERVER['SERVER_NAME']        = $request->getUri()->getHost();
        $_SERVER['SERVER_PORT']        = $request->getUri()->getPort();
        $_SERVER['REQUEST_URI']        = $request->getUri()->getPath();
        $_SERVER['SCRIPT_NAME']        = '/index.php';
        $_SERVER['PHP_SELF']           = 'index.php';
        $_SERVER['PATH_TRANSLATED']    = 'index.php';
        $_SERVER['HTTP_HOST']          = $_SERVER['SERVER_NAME'] . (in_array($_SERVER['SERVER_PORT'], array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT']);

        $query = $request->getUri()->getQuery();
        if(!empty($query))
        {
            $_SERVER['REQUEST_URI'] .= '?' . $query;
            $_SERVER['QUERY_STRING'] = $query;
        }

        $_GET    = $request->getQueryParams();
        $_POST   = $request->getParsedBody();
        $_COOKIE = $request->getCookieParams();
        $_FILE   = $request->getUploadedFiles();
    }

    /**
     * Send response.
     *
     * @param  string $body
     * @access public
     * @return void
     */
    public function respond(string $body)
    {
        $this->response->setBody($body);
        $this->psr7->respond($this->response);
    }

    /**
     * Send error.
     *
     * @param  Exception $e
     * @access public
     * @return void
     */
    public function error(Exception $e)
    {
        $this->psr7->getWorker()->error((string)$e);
    }
}

/**
 * MySQL实现的Session管理.
 * Session handler implements by MySQL.
 *
 * @package zand
 */
class zandSession
{
    /**
     * DAO for database.
     *
     * @var    object
     * @access private
     */
    private $dao;

    /**
     * Constructor.
     *
     * @param  object $dao
     * @access public
     * @return void
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * Open session.
     *
     * @param string $savePath
     * @param string $sessionName
     * @access public
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        $this->savePath    = $savePath;
        $this->sessionName = $sessionName;
        return true;
    }

    /**
     * Close session.
     *
     * @access public
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session.
     *
     * @param  string $id
     * @access public
     * @return string
     */
    public function read($id)
    {
        $result = $this->dao->select('data')->from(TABLE_SESSION)->where('id')->eq($id)->fetch();
        return $result ? $result->data : '';
    }

    /**
     * Write session.
     *
     * @param  string $id
     * @param  string $data
     * @access public
     * @return bool
     */
    public function write($id, $data)
    {
        $data = array('id' => $id, 'data' => $data, 'timestamp' => time());
        $this->dao->replace(TABLE_SESSION)->data($data)->exec();
        return true;
    }

    /**
     * Destroy session.
     *
     * @param  string $id
     * @access public
     * @return bool
     */
    public function destroy($id)
    {
        $this->dao->delete()->from(TABLE_SESSION)->where('id')->eq($id)->exec();
        return true;
    }

    /**
     * GC for session.
     *
     * @param  int $maxlifetime
     * @access public
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $this->dao->delete(TABLE_SESSION)->where('timestamp')->lt(time() - intval($maxlifetime))->exec();
        return true;
    }
}
