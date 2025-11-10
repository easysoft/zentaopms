<?php
declare(strict_types = 1);
class programTest
{
    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('program');
        $tester->loadModel('program');

        $this->objectZen = initReference('program');
    }

    /**
     * Test getPMListByPrograms method.
     *
     * @param  array $programs
     * @access public
     * @return mixed
     */
    public function getPMListByProgramsTest($programs = array())
    {
        $method = $this->objectZen->getMethod('getPMListByPrograms');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($programs));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildProgramForCreate method.
     *
     * @access public
     * @return object|array
     */
    public function buildProgramForCreateTest()
    {
        $method = $this->objectZen->getMethod('buildProgramForCreate');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array());
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildProgramForEdit method.
     *
     * @param  int $programID
     * @access public
     * @return object|array
     */
    public function buildProgramForEditTest(int $programID)
    {
        $method = $this->objectZen->getMethod('buildProgramForEdit');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($programID));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}