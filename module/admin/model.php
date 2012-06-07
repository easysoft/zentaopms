<?php
/**
 * The model file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class adminModel extends model
{
    /**
     * The api agent(use snoopy).
     * 
     * @var object   
     * @access public
     */
    public $agent;

    /**
     * The api root.
     * 
     * @var string
     * @access public
     */
    public $apiRoot;

    /**
     * The construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAgent();
    }

    /**
     * Set the api agent.
     * 
     * @access public
     * @return void
     */
    public function setAgent()
    {
        $this->agent = $this->app->loadClass('snoopy');
    }

    /**
     * Post data form  API 
     * 
     * @param  string $url 
     * @param  string $formvars 
     * @access public
     * @return void
     */
    public function postAPI($url, $formvars = "")
    {
		$this->agent->cookies['lang'] = $this->cookie->lang;
    	$this->agent->submit($url, $formvars);
		return $this->agent->results;
    }

    /**
     * Get status of zentaopms.
     * 
     * @access public
     * @return void
     */
    public function getStatOfPMS()
    {
        $sql = "SHOW TABLE STATUS";
        $tables = $this->dbh->query($sql)->fetchALL();
    }

    /**
     * Get state of company.
     * 
     * @param  int    $companyID 
     * @access public
     * @return void
     */
    public function getStatOfCompany($companyID)
    {
    }

    /**
     * Get system info.
     * 
     * @access public
     * @return void
     */
    public function getStatOfSys()
    {

    }

	/**
	 * Register zentao by API. 
	 * 
	 * @access public
	 * @return void
	 */
	public function registerByAPI()
	{
		$apiURL = 'http://www.zentao.net/user-register.json';
		return $this->postAPI($apiURL, $_POST);
	}

	/**
	 * Login zentao by API.
	 * 
	 * @access public
	 * @return void
	 */
	public function bindByAPI()
	{
		$apiURL = 'http://www.zentao.net/user-login.json';
		return $this->postAPI($apiURL, $_POST);
	}

	/**
	 * Get register information. 
	 * 
	 * @access public
	 * @return object
	 */
	public function getRegisterInfo()
    {
        $register = new stdclass();
		$register->company = $this->app->company->name;
		$register->email   = $this->app->user->email;
		return $register;
	}

    /**
     * Clear data.
     * 
     * @access public
     * @return void
     */
    public function clearData()
    {
        $result = $this->dbh->query('SHOW TABLES')->fetchAll();
        $showDemoUsers = $this->dao->select('value')->from(TABLE_CONFIG)->where('`key`')->eq('showDemoUsers')->fetch();
        foreach($result as $item) 
        {
            $table = current((array)$item); 
            if(strpos($table, 'company') !== false) continue;
            if(strpos($table, 'group')   !== false) continue;
            if(strpos($table, 'user')    !== false) 
            {
                if($showDemoUsers)
                {
                    $this->dao->delete()->from($table)
                        ->where('account')->in(array('productManager', 'projectManager', 'testManager', 'dev1', 'dev2', 'dev3', 'tester1', 'tester2', 'tester3'))
                        ->exec();
                    if(dao::isError()) return false;
                }
                continue;
            }
            if(strpos($table, 'config')  !== false)
            {
                if($showDemoUsers)
                {
                    $this->dao->delete()->from($table)
                        ->where('`key`')->eq('showDemoUsers')
                        ->exec();
                    if(dao::isError()) return false;
                }
                continue;
            }
            if(!$this->dbh->query("TRUNCATE TABLE `$table`")) return false;
        }
        return true;
    }
}
