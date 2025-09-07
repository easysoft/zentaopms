<?php
declare(strict_types = 1);
class convertTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('convert');
    }

    /**
     * Test connectDB method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function connectDBTest($dbName = null)
    {
        $result = $this->objectModel->connectDB($dbName);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}