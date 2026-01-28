<?php
declare(strict_types = 1);
/**
 * ZenTaoPHP 的测试基类，通过反射实现对被测试类的调用。
 * The test base class of ZenTaoPHP, which implements the call to the class being tested through reflection.
 *
 * 测试基类的子类可以通过继承 baseTest 类来实现对被测试类的测试。
 * The subclass of the test base class can implement the test of the class being tested by inheriting the baseTest class.
 *
 * 测试基类的子类需要定义受保护的属性 moduleName 和 className 来指定被测试的模块名和类名。
 * The subclass of the test base class needs to define the protected properties moduleName and className to specify the module name and class name being tested.
 *
 * 测试基类的子类可以通过调用 getProperty 方法来获取被测试类的属性值。
 * The subclass of the test base class can call the getProperty method to get the property value of the class being tested.
 *
 * 测试基类的子类可以通过调用 invokeArgs 方法来调用被测试类的方法。
 * The subclass of the test base class can call the invokeArgs method to invoke the method of the class being tested.
 *
 * 例如：
 * For example:
 *
 * class userZenTest extends baseTest
 * {
 *     protected $moduleName = 'user';
 *     protected $className  = 'zen';
 *
 *     public function prepareCustomFieldsTest($method = 'batchCreate', $requiredMethod = 'create')
 *     {
 *         $this->invokeArgs('prepareCustomFields', [$method, $requiredMethod]);
 *         if(dao::isError()) return dao::getError();
 *
 *         return $this->getProperty('view');
 *     }
 * }
 *
 */
class baseTest
{
    /**
     * 被测试的模块名。
     * The name of the module being tested.
     *
     * @var string
     * @access private
     */
    protected $moduleName = '';

    /**
     * 被测试的类名，zen、tao 或 model。
     * The class name being tested, zen, tao or model.
     *
     * @var string
     * @access private
     */
    protected $className = '';

    /**
     * 被测试的类的实例。
     * The instance of the class being tested.
     *
     * @var object
     * @access private
     */
    public $instance = null;

    /**
     * 被测试的类的反射对象。
     * The reflection object of the class being tested.
     *
     * @var ReflectionClass
     * @access private
     */
    protected $reflection = null;

    /**
     * 缓存被测试类的实例。
     * Cache the instances of the classes being tested.
     *
     * @var array
     * @access private
     */
    protected static $instances = [];

    /**
     * 缓存被测试类的反射对象。
     * Cache the reflection objects of the classes being tested.
     *
     * @var array
     * @access private
     */
    protected static $reflections = [];

    /**
     * 构造函数，初始化被测试的类的实例和反射对象。
     * Constructor, initialize the instance and reflection object of the class being tested.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $className = '')
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($className))  $className  = $this->className;
        if(empty($moduleName) || empty($className))
        {
            throw new InvalidArgumentException('Module name and class name cannot be empty.');
        }

        global $app;
        $app->setModuleName($moduleName);

        $this->instance   = $this->getInstance($moduleName, $className);
        $this->reflection = $this->getReflection($moduleName, $className);
    }

    /**
     * 获取被测试的类的实例。
     * Get the instance of the class being tested.
     *
     * @param  string $moduleName 模块名 The module name
     * @param  string $className  类名 The class name
     * @access protected
     * @return object
     */
    protected function getInstance($moduleName, $className)
    {
        if(isset(self::$instances[$className][$moduleName])) return self::$instances[$className][$moduleName];

        global $app;
        if($className == 'zen') require_once $app->getModulePath('', $moduleName) . 'control.php';
        if($className == 'tao') require_once $app->getModulePath('', $moduleName) . 'model.php';
        self::$instances[$className][$moduleName] = $app->loadTarget($moduleName, '', $className);

        return self::$instances[$className][$moduleName];
    }

    /**
     * 获取被测试的类的反射对象。
     * Get the reflection object of the class being tested.
     *
     * @param  string          $moduleName 模块名 The module name
     * @param  string          $className  类名 The class name
     * @param  object|null     $instance   类的实例 The instance of the class
     * @access protected
     * @return ReflectionClass
     */
    protected function getReflection($moduleName, $className, $instance = null)
    {
        if(isset(self::$reflections[$className][$moduleName])) return self::$reflections[$className][$moduleName];

        if(empty($instance)) $instance = $this->getInstance($moduleName, $className);

        self::$reflections[$className][$moduleName] = new ReflectionClass($instance);

        return self::$reflections[$className][$moduleName];
    }

    /**
     * 获取被测试类的属性值。
     * Get the property value of the class being tested.
     *
     * @param  string               $propertyName 属性名 The property name
     * @param  string               $moduleName   模块名 The module name
     * @param  string               $className    类名 The class name
     * @param  object|null          $instance     类的实例 The instance of the class
     * @param  ReflectionClass|null $reflection   类的反射对象 The reflection object of the class
     * @access protected
     * @return mixed
     */
    protected function getProperty($propertyName, $moduleName = '', $className = '', $instance = null, $reflection = null)
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($className))  $className  = $this->className;
        if(empty($instance))   $instance   = $this->getInstance($moduleName, $className);
        if(empty($reflection)) $reflection = $this->getReflection($moduleName, $className, $instance);

        $property = $reflection->getProperty($propertyName);
        if(!$property->isPublic()) $property->setAccessible(true);

        return $property->getValue($instance);
    }

    /**
     * 调用被测试类的方法。
     * Invoke the method of the class being tested.
     *
     * @param  string               $methodName  方法名 The method name
     * @param  array                $args        方法参数 The method arguments
     * @param  string               $moduleName  模块名 The module name
     * @param  string               $className   类名 The class name
     * @param  object|null          $instance    类的实例 The instance of the class
     * @param  ReflectionClass|null $reflection  类的反射对象 The reflection object of the class
     * @access protected
     * @return mixed
     */
    protected function invokeArgs($methodName, $args = [], $moduleName = '', $className = '', $instance = null, $reflection = null)
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($className))  $className  = $this->className;
        if(empty($instance))   $instance   = $this->getInstance($moduleName, $className);
        if(empty($reflection)) $reflection = $this->getReflection($moduleName, $className, $instance);

        $method = $reflection->getMethod($methodName);
        if(!$method->isPublic()) $method->setAccessible(true);

        $object = $method->isStatic() ? null : $instance;
        return $method->invokeArgs($object, $args);
    }
}
