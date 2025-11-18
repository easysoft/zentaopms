<?php
require_once dirname(__FILE__, 3) . '/vendor/autoload.php';
/**
 * ZenTaoPHP 的第三方类库代理类，将方法调用委托给实例或静态类。
 * ZenTaoPHP's third-party library proxy class delegates method calls to an instance or a static class.
 *
 * 代理类的子类可以通过继承 baseDelegate 类来实现对第三方类库的调用。
 * Subclasses of the proxy class can call third-party libraries by inheriting the baseDelegate class.
 *
 * 代理类的子类需要定义一个静态属性 className 来指定要调用的第三方类库的类名。
 * The subclass of the proxy class needs to define a static property className to specify the class name of the third-party library to be called.
 *
 * 代理类的子类还需要在构造函数中初始化一个实例属性 instance 来保存第三方类库的实例。
 * The subclass of the proxy class also needs to initialize an instance property instance in the constructor to save the instance of the third-party library.
 *
 * 例如：
 * For example:
 * class myLib extends baseDelegate
 * {
 *     protected static $className = 'ThirdPartyClass';
 *     public function __construct()
 *     {
 *         $this->instance = new static::$className();
 *     }
 * }
 *
 * 这样就可以通过 myLib 类来调用 ThirdPartyClass 类的方法了。
 * In this way, you can call the methods of the ThirdPartyClass class through the myLib class.
 */
class baseDelegate
{
    /**
     * 第三方类库的实例
     * Instance of the third-party library
     *
     * @var object
     * @access protected
     */
    protected $instance = null;

    /**
     * 代理对实例属性的读取。
     * 当访问一个不存在的属性时，尝试从实例中获取。
     *
     * @param string $name 属性名
     * @return mixed
     * @throws Error 如果属性不存在
     */
    public function __get($name)
    {
        // 如果有实例，尝试从实例中获取属性
        if (!is_null($this->instance) && property_exists($this->instance, $name)) {
            return $this->instance->$name;
        }

        // 如果找不到，抛出错误
        $calledClass = get_called_class();
        throw new Error("Access to undeclared property: {$calledClass}::\${$name}");
    }

    /**
     * 代理对实例属性的写入。
     * 当写入一个不存在的属性时，尝试写入实例中。
     *
     * @param string $name 属性名
     * @param mixed $value 值
     * @return void
     * @throws Error 如果属性不存在
     */
    public function __set($name, $value)
    {
        // 如果有实例，尝试写入实例属性
        if (!is_null($this->instance) && property_exists($this->instance, $name)) {
            $this->instance->$name = $value;
            return;
        }

        // 如果找不到，抛出错误
        $calledClass = get_called_class();
        throw new Error("Cannot set undeclared property: {$calledClass}::\${$name}");
    }

    /**
     * 调用实例方法
     * Call instance methods
     *
     * @param string $name 方法名 Method name
     * @param array  $arguments   方法参数 Method arguments
     * @return mixed
     * @throws BadMethodCallException 如果实例未初始化或方法不存在 If the instance is not initialized or the method does not exist
     */
    public function __call(string $name, array $arguments)
    {
        if (is_null($this->instance)) {
            throw new BadMethodCallException('The instance is not initialized.');
        }

        if (is_callable([$this->instance, $name])) {
            return call_user_func_array([$this->instance, $name], $arguments);
        }

        $calledClass = get_called_class();
        throw new BadMethodCallException("The method {$name} does not exist in the class {$calledClass}.");
    }

    /**
     * 调用静态方法
     * Call static methods
     *
     * @param string $name 方法名 Method name
     * @param array  $arguments   方法参数 Method arguments
     * @return mixed
     * @throws BadMethodCallException 如果类名未设置或类不存在或方法不存在 If the class name is not set or the class does not exist or the method does not exist
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $calledClass = get_called_class();
        if (!property_exists($calledClass, 'className')) {
            throw new BadMethodCallException("The static property className does not exist in the class {$calledClass}.");
        }

        $className = $calledClass::$className;
        if (empty($className)) {
            throw new BadMethodCallException("The static property className is not set in the class {$calledClass}.");
        }

        if (!class_exists($className)) {
            throw new BadMethodCallException("The class {$className} does not exist.");
        }

        if (is_callable([$className, $name])) {
            return call_user_func_array([$className, $name], $arguments);
        }

        if (property_exists($className, $name) && count($arguments) === 1) {
            // 支持静态属性的直接赋值
            $className::$$name = $arguments[0];
            return;
        }

        if (property_exists($className, $name) && count($arguments) === 0) {
            // 支持静态属性的直接读取
            return $className::$$name;
        }

        if (strpos($name, 'set') === 0 && property_exists($className, lcfirst(substr($name, 3))) && count($arguments) === 1) {
            // 支持静态属性的 set 方法
            $property = lcfirst(substr($name, 3));
            $className::$$property = $arguments[0];
            return;
        }

        if (strpos($name, 'get') === 0 && property_exists($className, lcfirst(substr($name, 3))) && count($arguments) === 0) {
            // 支持静态属性的 get 方法
            $property = lcfirst(substr($name, 3));
            return $className::$$property;
        }

        throw new BadMethodCallException("The method {$name} does not exist in the class {$calledClass}.");
    }
}
