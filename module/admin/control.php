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
		$user = $this->dao->select('value')->from(TABLE_CONFIG)
			->where('owner')->eq($this->app->user->account)
			->andWhere('`key`')->eq('account')
			->fetch('', false);
		if($user)
		{
			$this->view->login   = true;
			$this->view->account = $user->value;
		}
		else
		{
			$this->view->login   = false;
			$this->view->account = '';
		}
		if($this->cookie->notice == 'ignore')
		{
			$this->view->ignore = true;
		}
		else
		{
			$this->view->ignore = false;	
		}
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
		setcookie('notice', 'ignore');	
		die(js::locate(inlink('index')));
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
			if($response == 'success') die(js::locate(inlink('index'), 'parent'));
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
			$response = $this->admin->loginByAPI();	
			if($response == 'success') die(js::locate(inlink('index'), 'parent'));
		}
		$this->view->sn = $this->admin->getSN();
		$this->display();
	}
}
