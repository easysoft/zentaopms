<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class jenkinsZenTest extends baseTest
{
    protected $moduleName = 'jenkins';
    protected $className  = 'zen';
    /**
     * Create jenkinsZen instance.
     *
     * @access private
     * @return object
     */
    private function createZenInstance()
    {
        global $app;
        if(!class_exists('jenkins')) require_once $app->getModulePath('', 'jenkins') . 'control.php';
        if(!class_exists('jenkinsZen')) require_once $app->getModulePath('', 'jenkins') . 'zen.php';
        $app->loadLang('jenkins');

        $reflection = new ReflectionClass('jenkinsZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');
        return $zenInstance;
    }

    /**
     * Test buildTree method.
     *
     * @param  array $tasks
     * @access public
     * @return mixed
     */
    public function buildTreeTest($tasks = array())
    {
        $zenInstance = $this->createZenInstance();
        $reflection = new ReflectionClass('jenkinsZen');
        $method = $reflection->getMethod('buildTree');
        $method->setAccessible(true);
        $result = $method->invoke($zenInstance, $tasks);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkTokenAccess method.
     *
     * @param  string $url
     * @param  string $account
     * @param  string $password
     * @param  string $token
     * @access public
     * @return bool
     */
    public function checkTokenAccessTest($url = '', $account = '', $password = '', $token = '')
    {
        $zenInstance = $this->createZenInstance();
        $reflection = new ReflectionClass('jenkinsZen');
        $method = $reflection->getMethod('checkTokenAccess');
        $method->setAccessible(true);
        dao::$errors = array();
        $result = $method->invoke($zenInstance, $url, $account, $password, $token);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
