<?php
class adminTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('admin');
        $this->user = $tester->loadModel('user');
    }

    /**
     * 测试获取配置信息是否成功。
     * Test get api config.
     *
     * @access public
     * @return string
     */
    public function getApiConfigTest(): string
    {
        $objects = $this->objectModel->getApiConfig();
        if(empty($objects)) return 'Fail';

        return 'Success';
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
