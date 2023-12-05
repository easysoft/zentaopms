<?php
declare(strict_types=1);
class pivotTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('pivot');
    }

    /**
     * 测试getByID。
     * Test getByID.
     *
     * @param  int         $id
     * @access public
     * @return object|bool
     */
    public function getByIDTest(int $id): object|bool
    {
        return $this->objectModel->getByID($id);
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->objectModel, $name], $arguments);
    }
}
