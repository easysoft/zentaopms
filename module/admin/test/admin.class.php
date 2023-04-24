<?php
class adminTest
{
    public function __construct()
    {
        global $tester;
        global $config;
        global $app;
        $this->objectModel = $tester->loadModel('admin');
        $this->user = $tester->loadModel('user');
        $this->communityConfig = $app->loadConfig('community');
        $this->adminConfig = $config->admin;
    }

    /**
     * Post data form  API.
     *
     * @param  string $url
     * @param  string $formvars
     * @access public
     * @return void
     */
    public function postAPITest()
    {
        $apiConfig = $this->objectModel->getApiConfig();
        $apiURL    = $this->adminConfig->apiRoot . "/user-apiRegister.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";

        $objects = $this->objectModel->postAPI($apiURL);

        if(dao::isError()) return dao::getError();

        return $objects;
    }


    /**
     * Get status of zentaopms.
     * @access public
     * @return void
     */
    public function getStatOfPMSTest()
    {
        $objects = $this->objectModel->getStatOfPMS();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStatOfCompanyTest($companyID)
    {
        $objects = $this->objectModel->getStatOfCompany($companyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStatOfSysTest()
    {
        $objects = $this->objectModel->getStatOfSys();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function registerByAPITest()
    {
        $objects = $this->objectModel->registerByAPI();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Login zentao by API.
     *
     * @access public
     * @return void
     */
    public function bindByAPITest($param)
    {
        $_POST = $param;
        $objects = $this->objectModel->bindByAPI();
        $objects = json_decode($objects);
        unset($_POST);
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSecretKeyTest()
    {
        $objects = $this->objectModel->getSecretKey();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sendCodeByAPITest($type)
    {
        $objects = $this->objectModel->sendCodeByAPI($type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function certifyByAPITest($type)
    {
        $objects = $this->objectModel->certifyByAPI($type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setCompanyByAPITest()
    {
        $objects = $this->objectModel->setCompanyByAPI();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get signature.
     *
     * @param  array    $params
     * @access public
     * @return string
     */
    public function getSignatureTest()
    {
        $params['u'] = $this->communityConfig;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequet';

        $apiConfig = $this->objectModel->getApiConfig();
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;

        $objects = $this->objectModel->getSignature($params);

        if(dao::isError()) return dao::getError();

        if($objects){
            return true;
        }else{
            return false;
        }
    }


    public function getApiConfigTest()
    {
        $objects = $this->objectModel->getApiConfig();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get register information.
     *
     * @access public
     * @return object
     */
    public function getRegisterInfoTest()
    {
        $objects = $this->objectModel->getRegisterInfo();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Check weak.
     *
     * @param  object    $user
     * @access public
     * @return bool
     */
    public function checkWeakTest($account)
    {
        $user = $this->user->getById($account);
        $objects = $this->objectModel->checkWeak($user);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getMenuKeyTest($moduleName, $methodName, $params = array())
    {
        global $app;
        $app->rawModule = $moduleName;
        $app->rawMethod = $methodName;
        $app->rawParams = $params;

        $menuKey = $this->objectModel->getMenuKey();
        return empty($menuKey) ? 'null' : $menuKey;
    }

    /**
     * Get the authorized link
     *
     * @param mixed $menuKey
     * @access public
     * @return array
     */
    public function getHasPrivLinkTest($menuKey)
    {
        global $lang;
        $subMenuList = $lang->admin->menuList->$menuKey['subMenu'];
        $link        = array();
        foreach($subMenuList as $subMenu)
        {
            $link = $this->objectModel->getHasPrivLink($subMenu);
            if(!empty($link)) return $link;
        }
        return $link;
    }
}
