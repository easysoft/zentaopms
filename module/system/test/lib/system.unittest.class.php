<?php
declare(strict_types = 1);
class systemTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('system');
    }

    /**
     * Test updateMinioDomain method.
     *
     * @access public
     * @return mixed
     */
    public function updateMinioDomainTest()
    {
        $result = $this->objectModel->updateMinioDomain();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}