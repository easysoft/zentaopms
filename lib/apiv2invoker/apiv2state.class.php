<?php
/**
 * APIv2 同进程执行的有限状态管理器。
 * State manager for in-process APIv2 execution.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     ZenTaoPMS
 * @link        https://www.zentao.net
 */
class apiV2StateManager
{
    /**
     * 全局变量快照。
     * Global variable snapshot.
     *
     * @var array
     * @access private
     */
    private $globals = array();

    /**
     * 超全局变量快照。
     * Superglobal snapshot.
     *
     * @var array
     * @access private
     */
    private $superglobals = array();

    /**
     * 已加载类的静态属性快照。
     * Static property snapshot of loaded classes.
     *
     * @var array
     * @access private
     */
    private $staticProperties = array();

    /**
     * 执行前已经加载的类。
     * Classes loaded before execution.
     *
     * @var array
     * @access private
     */
    private $loadedClasses = array();

    /**
     * 执行前的 output buffer 层级。
     * Output buffer level before execution.
     *
     * @var int
     * @access private
     */
    private $outputLevel = 0;

    /**
     * 应用根目录。
     * App root.
     *
     * @var string
     * @access private
     */
    private $appRoot = '';

    /**
     * 保存状态。
     * Snapshot state.
     *
     * @param  string $appRoot
     * @access public
     * @return void
     */
    public function snapshot(string $appRoot): void
    {
        $this->appRoot = rtrim($appRoot, '/');

        foreach(array('app', 'config', 'lang', 'common', 'dbh', 'dao', 'routes', 'filter', 'loadedModels') as $name)
        {
            if(array_key_exists($name, $GLOBALS)) $this->globals[$name] = $GLOBALS[$name];
        }

        $this->superglobals = array(
            'GET'     => $_GET,
            'POST'    => $_POST,
            'FILES'   => $_FILES,
            'COOKIE'  => $_COOKIE,
            'SERVER'  => $_SERVER,
            'SESSION' => unserialize(serialize($_SESSION ?? array())),
        );

        $this->outputLevel   = ob_get_level();
        $this->loadedClasses = get_declared_classes();
        $this->staticProperties = $this->snapshotStaticProperties($this->loadedClasses);

        foreach($this->loadedClasses as $class)
        {
            if(!class_exists($class)) continue;
            $reflection = new ReflectionClass($class);
            $file       = $reflection->getFileName();
            if($file) helper::$includedFiles[$file] = true;
        }

        baseRouter::$loadedConfigs = array();
        baseRouter::$loadedLangs   = array();
        baseRouter::$loadedTargets = array();
        commonModel::$userPrivs    = array();
        dao::$errors               = array();
        if(array_key_exists('loadedModels', $GLOBALS)) $GLOBALS['loadedModels'] = array();
    }

    /**
     * 恢复状态。
     * Restore state.
     *
     * @access public
     * @return void
     */
    public function restore(): void
    {
        foreach($this->globals as $name => $value) $GLOBALS[$name] = $value;

        $_GET     = $this->superglobals['GET'];
        $_POST    = $this->superglobals['POST'];
        $_FILES   = $this->superglobals['FILES'];
        $_COOKIE  = $this->superglobals['COOKIE'];
        $_SERVER  = $this->superglobals['SERVER'];
        $_SESSION = $this->superglobals['SESSION'];

        $this->restoreStaticProperties();

        while(ob_get_level() > $this->outputLevel) ob_end_clean();
    }

    /**
     * 保存已加载类的静态属性。
     * Snapshot static properties of loaded classes.
     *
     * @param  array $classes
     * @access private
     * @return array
     */
    private function snapshotStaticProperties(array $classes): array
    {
        $snapshot = array();
        foreach($classes as $class)
        {
            if(!$this->isManagedClass($class)) continue;

            $reflection = new ReflectionClass($class);
            $properties = $reflection->getProperties(ReflectionProperty::IS_STATIC);
            if(!$properties) continue;

            foreach($properties as $property)
            {
                $property->setAccessible(true);
                $snapshot[$class][$property->getName()] = $property->getValue();
            }
        }

        return $snapshot;
    }

    /**
     * 恢复类静态属性。
     * Restore static properties.
     *
     * @access private
     * @return void
     */
    private function restoreStaticProperties(): void
    {
        foreach($this->staticProperties as $class => $properties)
        {
            if(!class_exists($class)) continue;

            $reflection = new ReflectionClass($class);
            foreach($properties as $name => $value)
            {
                if(!$reflection->hasProperty($name)) continue;

                $property = $reflection->getProperty($name);
                $property->setAccessible(true);
                if($property->isStatic()) $property->setValue(null, $value);
            }
        }

        $currentClasses = get_declared_classes();
        $newClasses     = array_diff($currentClasses, $this->loadedClasses);
        foreach($newClasses as $class)
        {
            if(!$this->isManagedClass($class)) continue;

            $reflection = new ReflectionClass($class);
            $defaults   = $reflection->getDefaultProperties();
            foreach($reflection->getProperties(ReflectionProperty::IS_STATIC) as $property)
            {
                $name = $property->getName();
                if(!array_key_exists($name, $defaults)) continue;

                $property->setAccessible(true);
                if($property->isStatic()) $property->setValue(null, $defaults[$name]);
            }
        }
    }

    /**
     * 判断类是否需要纳入状态管理。
     * Check whether class should be managed.
     *
     * @param  string $class
     * @access private
     * @return bool
     */
    private function isManagedClass(string $class): bool
    {
        if(str_starts_with($class, 'apiV2Invoker') or str_starts_with($class, 'apiV2StateManager')) return false;
        if(in_array($class, array('helper', 'baseHelper', 'baseControl'))) return false;

        $reflection = new ReflectionClass($class);
        $file       = $reflection->getFileName();
        if(!$file or !str_starts_with($file, $this->appRoot . '/')) return false;

        return true;
    }
}
