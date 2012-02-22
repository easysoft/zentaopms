<?php
/**
 * The control file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class admin extends control
{
    /**
     * Index page.
     * @access public
     * @return void
     */
    public function index()
    {
		$user = $this->loadModel('setting')->getItem('system', 'global', 'community');
		if($user != '' and $user != 'na')
		{
			$this->view->login   = true;
			$this->view->account = $user;
		}
		else
		{
			$this->view->login   = false;
			$this->view->account = '';
		}
		if($this->loadModel('setting')->getItem('system', 'global', 'community') != '')
		{
			$this->view->ignore = true;
		}
		else
		{
			$this->view->ignore = false;
		}
		$this->view->latestRelease = $this->loadModel('install')->getLatestRelease();
		$this->display();
    }

	/**
	 * Ignore notice of register and login.
	 * 
	 * @access public
	 * @return void
	 */
	public function ignoreNotice()
	{
		$this->loadModel('setting')->setItem('system', 'global', 'community', 'na');
		die(js::locate(inlink('index'), 'parent'));
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
			$response = $this->admin->registerByAPI();
			if($response == 'success') 
			{
				$this->loadModel('setting')->setItem('system', 'global', 'community', $this->post->account);
				echo js::alert($this->lang->admin->register->success);
				die(js::locate(inlink('index'), 'parent'));
			}
			die($response);
		}
		$this->view->reg = $this->admin->getRegisterInfo();
		$this->view->sn  = $this->loadModel('setting')->getItem('system', 'global', 'sn');
		$this->display();
	}

	/**
	 * Bind zentao.
	 * 
	 * @access public
	 * @return void
	 */
	public function bind()
	{
		if($_POST)
		{
			$response = $this->admin->bindByAPI();	
			if($response == 'success') 
			{
				$this->loadModel('setting')->setItem('system', 'global', 'community', $this->post->account);
				echo js::alert($this->lang->admin->login->success);
				die(js::locate(inlink('index'), 'parent'));
			}
			die($response);
		}
		$this->view->sn = $this->loadModel('setting')->getItem('system', 'global', 'sn');
		$this->display();
	}
}
