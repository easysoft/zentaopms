<?php
/**
 * The control file of install currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id$
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
		$this->loadModel('admin');
		$this->loadModel('user');
        $this->config->webRoot = $this->install->getWebRoot();
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

        $this->view->header->title = $this->lang->install->welcome;

        if($release = $this->install->getLatestRelease()) $this->view->latestRelease = $release;

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
        $this->view->header->title  = $this->lang->install->checking;
        $this->view->phpVersion     = $this->install->getPhpVersion();
        $this->view->phpResult      = $this->install->checkPHP();
        $this->view->pdoResult      = $this->install->checkPDO();
        $this->view->pdoMySQLResult = $this->install->checkPDOMySQL();
        $this->view->jsonResult     = $this->install->checkJSON();
        $this->view->tmpRootInfo    = $this->install->getTmpRoot();
        $this->view->tmpRootResult  = $this->install->checkTmpRoot();
        $this->view->dataRootInfo   = $this->install->getDataRoot();
        $this->view->dataRootResult = $this->install->checkDataRoot();
        $this->view->iniInfo        = $this->install->getIniInfo();
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
        $this->view->header->title = $this->lang->install->setConfig;
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
                $this->view = (object)$_POST;
                $this->view->app    = $this->app;
                $this->view->lang   = $this->lang;
                $this->view->config = $this->config;
                $this->view->domain = $this->server->HTTP_HOST;
                $this->view->header->title = $this->lang->install->saveConfig;
                $this->display();
            }
            else
            {
                $this->view->header->title = $this->lang->install->saveConfig;
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
			$this->session->set('account', $this->post->account);
            $this->install->grantPriv();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('setting')->updateVersion($this->config->version);
            $this->setting->setSN();
			die(js::locate(inlink('step5'), 'parent'));
        }

        $this->view->header->title = $this->lang->install->getPriv;
        if(!isset($this->config->installed) or !$this->config->installed)
        {
            $this->view->error = $this->lang->install->errorNotSaveConfig;
            $this->display();
        }
        else
        {
            $this->view->pmsDomain = $this->server->HTTP_HOST;
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
		$this->display();	
	}

	/**
	 * Register zentao.
	 * 
	 * @access public
	 * @return void
	 */
	public function register()
	{
		if($_POST)
		{
			$this->app->user->account = $this->session->account;
			$response = $this->admin->registerByAPI();	
			if($response == 'success')
			{
				unset($_SESSION['installing']);
				session_destroy();
				die(js::locate('index.php', 'parent'));
			}
		}
		$this->view->sn = $this->admin->getSN();
		$this->display();	
	}

	/**
	 * Login zentao.
	 * 
	 * @access public
	 * @return void
	 */
	public function login()
	{
		if($_POST)	
		{
			$this->app->user->account = $this->session->account;
			$response = $this->admin->loginByAPI();	
			if($response == 'success')
			{
				unset($_SESSION['installing']);
				session_destroy();
				die(js::locate('index.php', 'parent'));
			}
		}
		$this->view->sn = $this->admin->getSN();
		$this->display();
	}
}
