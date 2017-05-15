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
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-apiRegister.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";
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
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-bindChanzhi.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";
        return $this->postAPI($apiURL, $_POST);
    }

    /**
     * Get secret key.
     * 
     * @access public
     * @return object
     */
    public function getSecretKey()
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-secretKey.json";

        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $this->agent->cookies['lang'] = $this->cookie->lang;
        $this->agent->fetch($apiURL . '?' . http_build_query($params));
        $result = $this->agent->results;
        $result = json_decode($result);
        return $result;
    }

    /**
     * Send code by API.
     * 
     * @param  string    $type 
     * @access public
     * @return string
     */
    public function sendCodeByAPI($type)
    {
        $apiConfig = $this->getApiConfig();
        $module    = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL    = $this->config->admin->apiRoot . "/{$module}-apiSendCode.json";

        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        if(isset($this->config->global->community) and $this->config->global->community != 'na') $this->post->set('account', $this->config->global->community);

        $param = http_build_query($params);
        return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    /**
     * Certify by API.
     * 
     * @param  string    $type 
     * @access public
     * @return string
     */
    public function certifyByAPI($type)
    {
        $apiConfig = $this->getApiConfig();
        $module    = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL    = $this->config->admin->apiRoot . "/{$module}-apiCertify.json";

        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $param = http_build_query($params);
        return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    /**
     * Set company by API.
     * 
     * @access public
     * @return string
     */
    public function setCompanyByAPI()
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-apiSetCompany.json";

        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $param = http_build_query($params);
        return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    /**
     * Get signature.
     * 
     * @param  array    $params 
     * @access public
     * @return string
     */
    public function getSignature($params)
    {
        unset($params['u']);
        $privateKey = $this->config->global->ztPrivateKey;
        return md5(http_build_query($params) . md5($privateKey));
    }

    /**
     * Get api config.
     * 
     * @access public
     * @return object
     */
    public function getApiConfig()
    {
        if(!$this->session->apiConfig or time() - $this->session->apiConfig->serverTime > $this->session->apiConfig->expiredTime)
        {
            $config = file_get_contents($this->config->admin->apiRoot . "?mode=getconfig");
            $config = json_decode($config);
            if(empty($config) or empty($config->sessionID)) return null;
            $this->session->set('apiConfig', $config);
        }
        return $this->session->apiConfig;

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
     * Check weak.
     * 
     * @param  object    $user 
     * @access public
     * @return bool
     */
    public function checkWeak($user)
    {
        $weaks = array();
        foreach(explode(',', $this->config->safe->weak) as $weak)
        {
            $weak = md5(trim($weak));
            $weaks[$weak] = $weak;
        }

        if(isset($weaks[$user->password])) return true;
        if($user->password == md5($user->account)) return true;
        if($user->phone and $user->password == md5($user->phone)) return true;
        if($user->mobile and $user->password == md5($user->mobile)) return true;
        if($user->birthday and $user->password == md5($user->birthday)) return true;
        return false;
    }
}
