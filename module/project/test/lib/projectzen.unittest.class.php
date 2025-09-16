<?php
declare(strict_types = 1);
class projectzenTest
{
    public function __construct()
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('project');
        $this->objectTao   = $tester->loadTao('project');

        // 直接创建 zen 实例
        include_once dirname(__FILE__, 3) . '/control.php';
        include_once dirname(__FILE__, 3) . '/zen.php';
        $this->objectZen = new projectZen();
    }

    /**
     * Test checkProductNameUnqiue method.
     *
     * @param  object $project
     * @param  object $rawdata
     * @access public
     * @return mixed
     */
    public function checkProductNameUnqiueTest($project = null, $rawdata = null)
    {
        try
        {
            $reflection = new ReflectionClass($this->objectZen);
            $method = $reflection->getMethod('checkProductNameUnqiue');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $project, $rawdata);

            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
}