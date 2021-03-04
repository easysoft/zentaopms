<?php
/**
 * The control file of install currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        if(!defined('IN_INSTALL')) die();
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
        $this->view->title = $this->lang->install->setConfig;
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
     * Create company, admin.
     *
     * @access public
     * @return void
     */
    public function step4()
    {
        if(!empty($_POST))
        {
            $this->install->grantPriv();
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->importDemoData) $this->install->importDemoData();
            if(dao::isError()) echo js::alert($this->lang->install->errorImportDemoData);

            $this->loadModel('setting')->updateVersion($this->config->version);
            $this->loadModel('setting')->setItem('system.common.global.flow', $this->post->flow);
            $this->loadModel('setting')->setItem('system.common.safe.mode', '1');
            $this->loadModel('setting')->setItem('system.common.safe.changeWeak', '1');
            $this->loadModel('setting')->setItem('system.common.global.cron', 1);
            die(js::locate(inlink('step5'), 'parent'));
        }

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
    public function step5()
    {
        $this->view->title = $this->lang->install->success;
        $this->display();

        unlink($this->app->getAppRoot() . 'www/install.php');
        unlink($this->app->getAppRoot() . 'www/upgrade.php');
        unset($_SESSION['installing']);
        session_destroy();
    }
}
