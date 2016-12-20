<?php
/**
 * The model file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: model.php 5148 2013-07-16 01:31:08Z chencongzhi520@gmail.com $
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
		$apiURL = 'http://www.zentao.net/user-register.json?bind=yes&HTTP_X_REQUESTED_WITH=XMLHttpRequest';
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
		$apiURL = 'http://www.zentao.net/user-bindChanzhi.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest';
		return $this->postAPI($apiURL, $_POST);
	}

    public function getSecretKey()
    {
		$apiURL = "http://www.zentao.net/user-secretKey.json";
        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params['k'] = $this->getSignature($params);
		$this->agent->cookies['lang'] = $this->cookie->lang;
    	$this->agent->fetch($apiURL . '?' . http_build_query($params));
		$result = $this->agent->results;
		$result = json_decode($result);
        return $result;
    }

    public function sendCodeByAPI($type)
    {
        $module = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL = "http://www.zentao.net/{$module}-apiSendCode.json";
        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params['k'] = $this->getSignature($params);

        $param = http_build_query($params);
		return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    public function certifyByAPI($type)
    {
        $module = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL = "http://www.zentao.net/{$module}-apiCertify.json";
        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params['k'] = $this->getSignature($params);

        $param = http_build_query($params);
		return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    public function getSignature($params)
    {
        unset($params['u']);
        $privateKey = $this->config->global->ztPrivateKey;
        return md5(http_build_query($params) . md5($privateKey));
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
}
