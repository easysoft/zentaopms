<?php
declare(strict_types = 1);
class instanceTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('instance');
        $this->objectTao   = $tester->loadTao('instance');
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return mixed
     */
    public function __constructTest()
    {
        $result = new stdClass();
        $result->cneLoaded = property_exists($this->objectModel, 'cne') && is_object($this->objectModel->cne);
        $result->actionLoaded = property_exists($this->objectModel, 'action') && is_object($this->objectModel->action);
        $result->parentCalled = property_exists($this->objectModel, 'dao') && is_object($this->objectModel->dao);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateInstancesDomain method.
     *
     * @access public
     * @return mixed
     */
    public function updateInstancesDomainTest()
    {
        $result = $this->objectModel->updateInstancesDomain();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateCpuSize method.
     *
     * @param  object     $instance
     * @param  int|string $size
     * @access public
     * @return mixed
     */
    public function updateCpuSizeTest(object $instance, int|string $size)
    {
        $result = $this->objectModel->updateCpuSize($instance, $size);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateVolSize method.
     *
     * @param  object     $instance
     * @param  int|string $size
     * @param  string     $name
     * @access public
     * @return mixed
     */
    public function updateVolSizeTest(object $instance, int|string $size, string $name)
    {
        $result = $this->objectModel->updateVolSize($instance, $size, $name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test url method.
     *
     * @param  object $instance
     * @access public
     * @return string
     */
    public function urlTest(object $instance): string
    {
        $result = $this->objectModel->url($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}