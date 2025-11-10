<?php
declare(strict_types = 1);

class jenkinsZenTest
{
    /**
     * Test buildTree method.
     *
     * @param  array $tasks
     * @access public
     * @return mixed
     */
    public function buildTreeTest($tasks = array())
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('jenkins'))
        {
            require_once $app->getModulePath('', 'jenkins') . 'control.php';
        }
        if(!class_exists('jenkinsZen'))
        {
            require_once $app->getModulePath('', 'jenkins') . 'zen.php';
        }

        /* 使用反射创建 jenkinsZen 实例,跳过构造函数 */
        $reflection = new ReflectionClass('jenkinsZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();

        /* 初始化必要的属性 */
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 buildTree 方法 */
        $method = $reflection->getMethod('buildTree');
        $method->setAccessible(true);

        /* 调用 zen 方法 */
        $result = $method->invoke($zenInstance, $tasks);

        if(dao::isError()) return dao::getError();

        return $result;
    }
}
