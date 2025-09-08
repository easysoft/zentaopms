<?php
declare(strict_types = 1);
class hostTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('host');
    }

    /**
     * Test getPairs method.
     *
     * @param  string $moduleIdList
     * @param  string $status
     * @access public
     * @return array
     */
    public function getPairsTest($moduleIdList = '', $status = '')
    {
        $result = $this->objectModel->getPairs($moduleIdList, $status);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}