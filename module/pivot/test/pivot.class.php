<?php
declare(strict_types=1);
class pivotTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('pivot');
        $this->initPivot();
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

    /**
     * 魔术方法，调用objectModel的方法。
     * Magic method, call objectModel method.
     *
     * @param  string $name
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->objectModel, $name], $arguments);
    }

    /**
     * 初始化透视表。
     * Init pivot table.
     *
     * @access public
     * @return void
     */
    public function initPivot()
    {
        global $tester,$app;
        $appPath = $app->getAppRoot();
        $sqlFile = $appPath . 'test/data/pivot.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
    }
}
