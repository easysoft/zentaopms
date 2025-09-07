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

    /**
     * Test dbExists method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function dbExistsTest($dbName = null)
    {
        $result = $this->objectModel->dbExists($dbName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test tableExists method.
     *
     * @param  string $table
     * @access public
     * @return mixed
     */
    public function tableExistsTest($table = null)
    {
        $result = $this->objectModel->tableExists($table);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}