<?php
class adminTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('admin');
        $this->user = $tester->loadModel('user');
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
