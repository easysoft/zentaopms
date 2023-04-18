<?php
/**
 * The control file of install currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: control.php 4297 2013-01-27 07:51:45Z wwccss $
 * @link        http://www.zentao.net
 */
class install extends control
{
    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        if(!defined('IN_INSTALL')) helper::end();
        parent::__construct();
        $this->app->loadLang('user');
        $this->app->loadLang('admin');
        $this->config->webRoot = getWebRoot();
    }

    /**
     * Index page of install module.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        if(!isset($this->config->installed) or !$this->config->installed) $this->session->set('installing', true);

        $this->view->title = $this->lang->install->welcome;
        if(!isset($this->view->versionName)) $this->view->versionName = $this->config->version; // If the versionName variable has been defined in the max version, it cannot be defined here to avoid being overwritten.
        $this->display();
    }

    /**
     * Checking agree license.
     *
     * @access public
     * @return void
     */
    public function license()
    {
        $this->view->title   = $this->lang->install->welcome;
        $this->view->license = $this->install->getLicense();
        $this->display();
    }

    /**
     * Check the system.
     *
     * @access public
     * @return void
     */
    public function step1()
    {
        $this->view->title          = $this->lang->install->checking;
        $this->view->phpVersion     = $this->install->getPhpVersion();
        $this->view->phpResult      = $this->install->checkPHP();
        $this->view->pdoResult      = $this->install->checkPDO();
        $this->view->pdoMySQLResult = $this->install->checkPDOMySQL();
        $this->view->jsonResult     = $this->install->checkJSON();
        $this->view->opensslResult  = $this->install->checkOpenssl();
        $this->view->mbstringResult = $this->install->checkMbstring();
        $this->view->zlibResult     = $this->install->checkZlib();
        $this->view->curlResult     = $this->install->checkCurl();
        $this->view->filterResult   = $this->install->checkFilter();
        $this->view->iconvResult    = $this->install->checkIconv();
        $this->view->tmpRootInfo    = $this->install->getTmpRoot();
        $this->view->tmpRootResult  = $this->install->checkTmpRoot();
        $this->view->dataRootInfo   = $this->install->getDataRoot();
        $this->view->dataRootResult = $this->install->checkDataRoot();
        $this->view->iniInfo        = $this->install->getIniInfo();

        $checkSession = ini_get('session.save_handler') == 'files';
        $this->view->sessionResult = 'ok';
        $this->view->checkSession  = $checkSession;
        if($checkSession)
        {
            $sessionResult = $this->install->checkSessionSavePath();
            $sessionInfo   = $this->install->getSessionSavePath();
            if($sessionInfo['path'] == '') $sessionResult = 'ok';

            $this->view->sessionResult = $sessionResult;
            $this->view->sessionInfo   = $sessionInfo;
        }

        $notice = '';
        if($this->config->framework->filterCSRF)
        {
            $httpType = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
            if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') $httpType = 'https';
            if(isset($_SERVER['REQUEST_SCHEME']) and strtolower($_SERVER['REQUEST_SCHEME']) == 'https') $httpType = 'https';

            $httpHost = zget($_SERVER, 'HTTP_HOST', '');
            if(empty($httpHost) or strpos($this->server->http_referer, "$httpType://$httpHost") !== 0) $notice = $this->lang->install->CSRFNotice;
        }

        $this->view->notice = $notice;
        $this->display();
    }

    /**
     * Set configs.
     *
     * @access public
     * @return void
     */
    public function step2()
    {
        $dbHost = $dbPort = $dbName = $dbUser = $dbPassword = '';

        /* Get mysql env in docker container. */
        if(getenv('MYSQL_HOST'))     $dbHost     = getenv('MYSQL_HOST');
        if(getenv('MYSQL_PORT'))     $dbPort     = getenv('MYSQL_PORT');
        if(getenv('MYSQL_DB'))       $dbName     = getenv('MYSQL_DB');
        if(getenv('MYSQL_USER'))     $dbUser     = getenv('MYSQL_USER');
        if(getenv('MYSQL_PASSWORD')) $dbPassword = getenv('MYSQL_PASSWORD');

        $this->view->title = $this->lang->install->setConfig;

        $this->view->dbHost     = $dbHost ? $dbHost : '127.0.0.1';
        $this->view->dbPort     = $dbPort ? $dbPort : '3306';
        $this->view->dbName     = $dbName ? $dbName : 'zentao';
        $this->view->dbUser     = $dbUser ? $dbUser : 'root';
        $this->view->dbPassword = $dbPassword ? $dbPassword : '';
        $this->display();
    }

    /**
     * Create the config file.
     *
     * @access public
     * @return void
     */
    public function step3()
    {
        if(!empty($_POST))
        {
            $return = $this->install->checkConfig();
            if($return->result == 'ok')
            {
                /* Set the session save path when the session save path is null. */
                $customSession = false;
                $checkSession  = ini_get('session.save_handler') == 'files';
                if($checkSession)
                {
                    if(!session_save_path())
                    {
                        /* Restart the session because the session save path is null when start the session last time. */
                        session_write_close();

                        $tmpRootInfo     = $this->install->getTmpRoot();
                        $sessionSavePath = $tmpRootInfo['path'] . 'session';
                        if(!is_dir($sessionSavePath)) mkdir($sessionSavePath, 0777, true);

                        session_save_path($sessionSavePath);
                        $customSession = true;

                        $sessionResult = $this->install->checkSessionSavePath();
                        if($sessionResult == 'fail') chmod($sessionSavePath, 0777);

                        session_start();
                        $this->session->set('installing', true);
                    }
                }

                $this->view = (object)$_POST;
                $this->view->app           = $this->app;
                $this->view->lang          = $this->lang;
                $this->view->config        = $this->config;
                $this->view->title         = $this->lang->install->saveConfig;
                $this->view->customSession = $customSession;
                $this->display();
            }
            else
            {
                $this->view->title = $this->lang->install->saveConfig;
                $this->view->error = $return->error;
                $this->display();
            }
        }
        else
        {
            $this->locate($this->createLink('install'));
        }
    }

    /**
     * Set system mode.
     *
     * @access public
     * @return void
     */
    public function step4()
    {
        if(!empty($_POST))
        {
            $this->loadModel('setting')->setItem('system.common.global.mode', $this->post->mode); // Update mode.
            $this->loadModel('custom')->disableFeaturesByMode($this->post->mode);
            return print(js::locate(inlink('step5'), 'parent'));
        }

        if(!isset($this->config->installed) or !$this->config->installed)
        {
            $this->view->error = $this->lang->install->errorNotSaveConfig;
            $this->display();
        }
        else
        {
            $this->app->loadLang('upgrade');

            list($disabledFeatures, $enabledScrumFeatures, $disabledScrumFeatures) = $this->loadModel('custom')->computeFeatures();

            $this->view->title                 = $this->lang->install->selectMode;
            $this->view->edition               = $this->config->edition;
            $this->view->disabledFeatures      = $disabledFeatures;
            $this->view->enabledScrumFeatures  = $enabledScrumFeatures;
            $this->view->disabledScrumFeatures = $disabledScrumFeatures;
            $this->display();
        }
    }

    /**
     * Create company, admin.
     *
     * @access public
     * @return void
     */
    public function step5()
    {
        if(!empty($_POST))
        {
            $this->install->grantPriv();
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->install->updateLang();
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->importDemoData) $this->install->importDemoData();

            $defaultProgram = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=defaultProgram');
            if($this->config->systemMode == 'light' and empty($defaultProgram))
            {
                /* Lean mode create default program. */
                $programID = $this->loadModel('program')->createDefaultProgram();
                /* Set default program config. */
                $this->loadModel('setting')->setItem('system.common.global.defaultProgram', $programID);
            }

            if(dao::isError()) return print(js::alert($this->lang->install->errorImportDemoData));

            $this->loadModel('setting');
            $this->setting->updateVersion($this->config->version);
            $this->setting->setSN();
            $this->setting->setItem('system.common.global.flow', $this->post->flow);
            $this->setting->setItem('system.common.safe.mode', '1');
            $this->setting->setItem('system.common.safe.changeWeak', '1');
            $this->setting->setItem('system.common.global.cron', '1');

            $httpType = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
            if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') $httpType = 'https';
            if(isset($_SERVER['REQUEST_SCHEME']) and strtolower($_SERVER['REQUEST_SCHEME']) == 'https') $httpType = 'https';
            if(strpos($this->app->getClientLang(), 'zh') === 0) $this->loadModel('api')->createDemoData($this->lang->api->zentaoAPI, "{$httpType}://{$_SERVER['HTTP_HOST']}" . $this->app->config->webRoot . 'api.php/v1', '16.0');
            $this->loadModel('upgrade')->createDefaultDimension();
            return print(js::locate(inlink('step6'), 'parent'));
        }

        $this->app->loadLang('upgrade');

        $this->view->title = $this->lang->install->getPriv;
        if(!isset($this->config->installed) or !$this->config->installed)
        {
            $this->view->error = $this->lang->install->errorNotSaveConfig;
            $this->display();
        }
        else
        {
            $this->display();
        }
    }

    /**
     * Join zentao community or login pms.
     *
     * @access public
     * @return void
     */
    public function step6()
    {
        $canDelFile  = is_writable($this->app->getAppRoot() . 'www');
        $installFile = $this->app->getAppRoot() . 'www/install.php';
        $upgradeFile = $this->app->getAppRoot() . 'www/upgrade.php';
        $installFileDeleted = $canDelFile && is_writable($installFile) ? unlink($installFile) : false;

        if($canDelFile and is_writable($upgradeFile)) unlink($upgradeFile);
        unset($_SESSION['installing']);
        session_destroy();

        $this->view->installFileDeleted = $installFileDeleted;
        $this->view->title              = $this->lang->install->success;
        $this->display();
    }
}
