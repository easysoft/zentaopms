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
        $reflection = new ReflectionClass('commonModel');
        $method = $reflection->getMethod('apiError');
        $method->setAccessible(true);

        $result = $method->invoke(null, $result);
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
     * Test printBack method metadata.
     *
     * @param  string $checkType
     * @access public
     * @return mixed
     */
    public function printBackMetaTest(string $checkType)
    {
        if($checkType == 'exists')
        {
            return method_exists('commonModel', 'printBack') ? '1' : '0';
        }

        $reflection = new ReflectionMethod('commonModel', 'printBack');

        if($checkType == 'static')
        {
            return $reflection->isStatic() ? '1' : '0';
        }

        if($checkType == 'public')
        {
            return $reflection->isPublic() ? '1' : '0';
        }

        if($checkType == 'paramCount')
        {
            return (string)$reflection->getNumberOfParameters();
        }

        return '0';
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
