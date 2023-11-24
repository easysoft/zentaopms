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

    public function getSecretKeyTest()
    {
        $objects = $this->objectModel->getSecretKey();

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
