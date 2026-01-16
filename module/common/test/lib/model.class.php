<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class commonModelTest extends baseTest
{
    protected $moduleName = 'common';
    protected $className  = 'model';

    /**
     * Test apiError method.
     *
     * @param  object|null $result
     * @access public
     * @return object
     */
    public function apiErrorTest($result = null)
    {
        $result = $this->invokeArgs('apiError', [$result]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test formConfig method.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function formConfigTest(string $module, string $method, int $objectID = 0)
    {
        $result = commonModel::formConfig($module, $method, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test canOperateEffort method.
     *
     * @param  object $effort
     * @access public
     * @return mixed
     */
    public function canOperateEffortTest($effort = null)
    {
        $result = $this->instance->canOperateEffort($effort);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkIP method.
     *
     * @param  string $ipWhiteList
     * @access public
     * @return mixed
     */
    public function checkIPTest($ipWhiteList = '')
    {
        $result = $this->instance->checkIP($ipWhiteList);
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
    }

    /**
     * Test checkPrivByObject method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function checkPrivByObjectTest(string $objectType, int $objectID): bool
    {
        $result = $this->instance->checkPrivByObject($objectType, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 检查详情页操作按钮的权限。
     * Check the privilege of the operate action.
     *
     * @param  string     $moduleName
     * @param  object     $data
     * @param  object     $menu
     * @access protected
     * @return array|bool
     */
    public function checkPrivForOperateActionTest(string $moduleName, string $action, array $actionData)
    {
        $object = $this->instance->dao->select('*')->from($this->instance->config->objectTables[$moduleName])->where('id')->eq(1)->fetch();
        $result = $this->instance->checkPrivForOperateAction($actionData, $action, $moduleName, $object, 'mainActions');
        return is_array($result) ? !empty($result['url']) : $result;
    }

    /**
     * Test createMenuLink method.
     *
     * @param  object $menuItem
     * @access public
     * @return mixed
     */
    public function createMenuLinkTest($menuItem)
    {
        $result = $this->instance->createMenuLink($menuItem);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDotStyle method.
     *
     * @param  bool $showCount
     * @param  int  $unreadCount
     * @access public
     * @return array
     */
    public function getDotStyleTest(bool $showCount, int $unreadCount): array
    {
        $result = commonModel::getDotStyle($showCount, $unreadCount);
        return $result;
    }

    /**
     * Test getMainNavList method.
     *
     * @param  string $moduleName
     * @param  bool   $useDefault
     * @access public
     * @return mixed
     */
    public function getMainNavListTest($moduleName, $useDefault = false)
    {
        $result = commonModel::getMainNavList($moduleName, $useDefault);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isOpenMethod method.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return bool
     */
    public function isOpenMethodTest($module, $method)
    {
        $result = $this->objectModel->isOpenMethod($module, $method);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isTutorialMode method.
     *
     * @access public
     * @return mixed
     */
    public function isTutorialModeTest()
    {
        $result = commonModel::isTutorialMode();
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
    }

    /**
     * Test setUser method.
     *
     * @param  string $mode 测试模式：'guest'测试访客，'session'测试已登录用户
     * @access public
     * @return mixed
     */
    public function setUserTest($mode = '')
    {
        // 如果指定测试guest模式，先清除session
        if($mode === 'guest') {
            if(isset($_SESSION['user'])) unset($_SESSION['user']);
            if(isset($this->instance->app->user)) unset($app->user);
        }

        // 执行setUser方法
        $this->instance->setUser();
        if(dao::isError()) return dao::getError();

        // 返回结果
        return $this->instance->app->user;
    }

    /**
     * Test strEndsWith method.
     *
     * @param  string $haystack
     * @param  string $needle
     * @access public
     * @return bool
     */
    public function strEndsWithTest($haystack, $needle)
    {
        $result = commonModel::strEndsWith($haystack, $needle);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test printBack method.
     *
     * @param  string $backLink
     * @param  string $class
     * @param  string $misc
     * @param  bool   $onlyBody
     * @access public
     * @return mixed
     */
    public function printBackTest(string $backLink, string $class = '', string $misc = '', bool $onlyBody = false)
    {
        if($onlyBody)
        {
            $_GET['onlybody'] = 'yes';
        }
        else
        {
            unset($_GET['onlybody']);
        }

        ob_start();
        $result = commonModel::printBack($backLink, $class, $misc);
        $output = ob_get_clean();

        if(dao::isError()) return dao::getError();

        // Return different data based on what we're testing
        if($onlyBody) return $result;
        if(!empty($output)) return $output;
        return $result;
    }

    /**
     * Test printDuration method.
     *
     * @param  int    $seconds
     * @param  string $format
     * @access public
     * @return string
     */
    public function printDurationTest(int $seconds, string $format = 'y-m-d-h-i-s'): string
    {
        $result = commonModel::printDuration($seconds, $format);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test printPreAndNext method.
     *
     * @param  mixed  $preAndNext
     * @param  string $linkTemplate
     * @param  bool   $onlyBody
     * @access public
     * @return mixed
     */
    public function printPreAndNextTest($preAndNext = '', string $linkTemplate = '', bool $onlyBody = false)
    {
        global $app, $lang;

        // 保存原始值
        $originalApp = $app ?? null;
        $originalLang = $lang ?? null;
        $originalGet = $_GET ?? array();

        // 设置onlybody模式
        if($onlyBody)
        {
            $_GET['onlybody'] = 'yes';
        }
        else
        {
            unset($_GET['onlybody']);
        }

        // 模拟完整的app对象
        $app = new class {
            public $tab = 'my';
            public $apiVersion = '';
            private $moduleName = 'test';
            private $methodName = 'view';
            private $appName = 'sys';
            private $viewType = '';
            public function getModuleName() { return $this->moduleName; }
            public function getMethodName() { return $this->methodName; }
            public function getAppName() { return $this->appName; }
            public function getViewType() { return $this->viewType; }
            public function setModuleName($name) { $this->moduleName = $name; }
            public function setMethodName($name) { $this->methodName = $name; }
        };

        // 设置语言
        if(!isset($lang))
        {
            $lang = new stdClass();
        }
        if(!isset($lang->preShortcutKey)) $lang->preShortcutKey = '(←)';
        if(!isset($lang->nextShortcutKey)) $lang->nextShortcutKey = '(→)';

        ob_start();
        $result = commonModel::printPreAndNext($preAndNext, $linkTemplate);
        $output = ob_get_clean();

        // 恢复原始值
        if($originalApp !== null) $app = $originalApp;
        if($originalLang !== null) $lang = $originalLang;
        $_GET = $originalGet;

        if(dao::isError()) return dao::getError();

        // 如果是onlybody模式，返回false
        if($onlyBody) return $result === false ? '0' : '1';

        // 否则返回输出内容
        return $output;
    }

    /**
     * Test processMarkdown method.
     *
     * @param  string $markdown
     * @access public
     * @return string|bool
     */
    public function processMarkdownTest(string $markdown)
    {
        $result = commonModel::processMarkdown($markdown);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
