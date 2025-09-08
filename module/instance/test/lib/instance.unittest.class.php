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
}