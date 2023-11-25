<?php
class adminTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('admin');
        $this->user        = $tester->loadModel('user');
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
     * 测试弱口令扫描。
     * Test check weak.
     *
     * @param  string  $account
     * @access public
     * @return bool
     */
    public function checkWeakTest(string $account): bool
    {
        $user   = $this->user->getById($account);
        $result = $this->objectModel->checkWeak($user);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取页面导航索引。
     * Test get menu key.
     *
     * @param string $moduleName
     * @param string $methodName
     * @param array  $params
     * @access public
     * @return array
     */
    public function getMenuKeyTest(string $moduleName, string $methodName, array $params = array()): string
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
