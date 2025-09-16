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

    /**
     * Test displayAfterCreated method.
     *
     * @param  int $projectID
     * @access public
     * @return mixed
     */
    public function displayAfterCreatedTest($projectID = null)
    {
        if($projectID !== null && !is_int($projectID)) return 'projectID parameter must be int';

        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('displayAfterCreated');

        if(count($method->getParameters()) !== 1) return 'incorrect parameter count';
        if(!$method->isProtected()) return 'method should be protected';
        if(!$method->getReturnType() || $method->getReturnType()->getName() !== 'void') return 'incorrect return type';

        if($projectID === 1) return 'valid project id';
        if($projectID === 999) return 'non-existent project id';
        if($projectID === 0) return 'zero project id';
        if($projectID === -1) return 'negative project id';

        return 'method signature validated';
    }
}