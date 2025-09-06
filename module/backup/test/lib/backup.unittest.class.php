<?php
declare(strict_types = 1);
class backupTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('backup');
    }

    /**
     * Test backSQL method.
     *
     * @param  string $backupFile
     * @access public
     * @return mixed
     */
    public function backSQLTest($backupFile = null)
    {
        $result = $this->objectModel->backSQL($backupFile);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test backFile method.
     *
     * @param  string $backupFile
     * @access public
     * @return mixed
     */
    public function backFileTest($backupFile = null)
    {
        $result = $this->objectModel->backFile($backupFile);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}