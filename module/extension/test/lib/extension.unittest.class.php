<?php
declare(strict_types = 1);
class extensionTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('extension');
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return extensionModel
     */
    public function __constructTest()
    {
        $result = $this->objectModel;
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchAPI method.
     *
     * @param  string $url
     * @access public
     * @return mixed
     */
    public function fetchAPITest($url = '')
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('fetchAPI');
        $method->setAccessible(true);
        
        $result = $method->invokeArgs($this->objectModel, array($url));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}