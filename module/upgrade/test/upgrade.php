<?php
declare(strict_types=1);
class upgradeTest
{
    /* upgrade模型实例化变量。 */
    /* upgrade model instantiation variable. */
    private $objectModel;

    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('upgrade');
    }

    /**
     * __call魔术方法，如果比较简单的方法可以直接调用，不需要单独写方法。
     * __call magic method, if the method is simple, you can call it directly, no need to write a method.
     *
     * @param  string $method
     * @param  array  $args
     * @access public
     * @return mixed
     */
    public function __call(string $method, array $args): mixed
    {
        return $this->objectModel->$method($args);
    }
}
