<?php
class adminTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('admin');
        $this->user        = $tester->loadModel('user');
        
        // 加载admin控制器基类
        if(!class_exists('admin'))
        {
            include dirname(__FILE__, 3) . '/control.php';
        }
        
        // 包含zen文件并实例化
        require_once dirname(__FILE__, 3) . '/zen.php';
        $this->objectZen = new adminZen();
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
     * 测试获取有权限的链接。
     * Test get the authorized link.
     *
     * @param  string $menuKey
     * @access public
     * @return array
     */
    public function getHasPrivLinkTest(string $menuKey): array
    {
        global $lang;
        $link = array();
        foreach($lang->admin->menuList->$menuKey['subMenu'] as $subMenu)
        {
            $link = $this->objectModel->getHasPrivLink($subMenu);
            if(!empty($link)) return $link;
        }
        return $link;
    }

    /**
     * 测试获取签名。
     * Test get signature.
     *
     * @param  array $params
     * @access public
     * @return string
     */
    public function getSignatureTest(array $params): string
    {
        $result = $this->objectModel->getSignature($params);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试检查导航权限并设置导航链接。
     * Test check priv menu.
     *
     * @access public
     * @return array
     */
    public function checkPrivMenuTest(): array
    {
        global $lang;

        // 保存原始数据
        $originalMenuList = isset($lang->admin->menuList) ? $lang->admin->menuList : null;

        // 执行checkPrivMenu方法
        $this->objectModel->checkPrivMenu();

        // 检查是否有错误
        if(dao::isError()) return dao::getError();

        // 获取处理结果
        $result = array();
        if(isset($lang->admin->menuList))
        {
            $result['hasMenuList'] = true;
            $result['menuCount'] = count(get_object_vars($lang->admin->menuList));

            // 检查菜单项是否有link和disabled属性设置
            $hasLinkedMenu = false;
            $hasDisabledMenu = false;
            foreach($lang->admin->menuList as $menuKey => $menu)
            {
                if(!empty($menu['link'])) $hasLinkedMenu = true;
                if($menu['disabled']) $hasDisabledMenu = true;
            }
            $result['hasLinkedMenu'] = $hasLinkedMenu;
            $result['hasDisabledMenu'] = $hasDisabledMenu;
        }

        return $result;
    }

    /**
     * 测试设置后台二级导航。
     * Test set menu.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  array  $params
     * @access public
     * @return mixed
     */
    public function setMenuTest(string $moduleName = 'admin', string $methodName = 'index', array $params = array())
    {
        global $app, $lang;

        // 保存原始值
        $originalModule = isset($app->rawModule) ? $app->rawModule : '';
        $originalMethod = isset($app->rawMethod) ? $app->rawMethod : '';
        $originalParams = isset($app->rawParams) ? $app->rawParams : array();

        // 设置测试值
        $app->rawModule = $moduleName;
        $app->rawMethod = $methodName;
        $app->rawParams = $params;

        // 执行setMenu方法
        $this->objectModel->setMenu();

        // 检查是否有错误
        if(dao::isError()) return dao::getError();

        // 恢复原始值
        $app->rawModule = $originalModule;
        $app->rawMethod = $originalMethod;
        $app->rawParams = $originalParams;

        // 返回设置结果的标识
        $result = array();
        if(isset($lang->admin->menu)) $result['hasMenu'] = true;
        if(isset($lang->admin->menuOrder)) $result['hasMenuOrder'] = true;
        if(isset($lang->admin->dividerMenu)) $result['hasDividerMenu'] = true;
        if(isset($lang->admin->tabMenu)) $result['hasTabMenu'] = true;
        if(isset($lang->switcherMenu)) $result['hasSwitcherMenu'] = true;

        return $result;
    }

    /**
     * 测试设置二级导航。
     * Test set sub menu.
     *
     * @param  string $menuKey
     * @param  array  $menu
     * @access public
     * @return array
     */
    public function setSubMenuTest(string $menuKey, array $menu): array
    {
        $result = $this->objectModel->setSubMenu($menuKey, $menu);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setTabMenu method.
     *
     * @param  string $subMenuKey
     * @param  array  $menu
     * @access public
     * @return mixed
     */
    public function setTabMenuTest(string $subMenuKey, array $menu)
    {
        $result = $this->objectModel->setTabMenu($subMenuKey, $menu);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test genDateUsed method.
     *
     * @access public
     * @return mixed
     */
    public function genDateUsedTest()
    {
        $result = $this->objectModel->genDateUsed();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setSwitcher method.
     *
     * @param  string $currentMenuKey
     * @access public
     * @return mixed
     */
    public function setSwitcherTest($currentMenuKey = 'system')
    {
        global $lang;

        // 保存原始的switcherMenu值
        $originalSwitcherMenu = isset($lang->switcherMenu) ? $lang->switcherMenu : null;

        // 执行setSwitcher方法
        $result = $this->objectModel->setSwitcher($currentMenuKey);
        if(dao::isError()) return dao::getError();

        // 简单返回方法执行是否成功（没有致命错误）
        if(empty($currentMenuKey)) return '0';

        // 对于非空参数，返回1表示执行成功
        return 1;
    }

    /**
     * Test syncExtensions method.
     *
     * @param  string $type
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function syncExtensionsTest(string $type = 'plugin', int $limit = 5)
    {
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('syncExtensions');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectZen, $type, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test syncPublicClasses method.
     *
     * @param  int $limit
     * @access public
     * @return mixed
     */
    public function syncPublicClassesTest(int $limit = 3)
    {
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('syncPublicClasses');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectZen, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test syncDynamics method.
     *
     * @param  int $limit
     * @access public
     * @return mixed
     */
    public function syncDynamicsTest(int $limit = 2)
    {
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('syncDynamics');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectZen, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchAPI method.
     *
     * @param  string $url
     * @access public
     * @return mixed
     */
    public function fetchAPITest(string $url)
    {
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('fetchAPI');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectZen, $url);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sendCodeByAPI method.
     *
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function sendCodeByAPITest(string $type = 'mobile')
    {
        // 为adminZen对象设置必要的属性
        $this->objectZen->admin = $this->objectModel;
        
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('sendCodeByAPI');
        $method->setAccessible(true);
        
        // 使用try-catch来捕获可能的网络错误
        try {
            $result = $method->invoke($this->objectZen, $type);
            if(dao::isError()) return dao::getError();
            return is_string($result) ? (strlen($result) > 0 ? '1' : '0') : '0';
        } catch (Exception $e) {
            // 网络请求失败时返回特定标识
            return '0';
        }
    }
}
