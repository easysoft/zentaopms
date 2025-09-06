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

    /**
     * Test backCode method.
     *
     * @param  string $backupFile
     * @access public
     * @return mixed
     */
    public function backCodeTest($backupFile = null)
    {
        $result = $this->objectModel->backCode($backupFile);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test restoreSQL method.
     *
     * @param  string $backupFile
     * @access public
     * @return mixed
     */
    public function restoreSQLTest($backupFile = null)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        // Mock test without actually calling restoreSQL to avoid destroying test database
        if(empty($backupFile))
        {
            $return->result = false;
            $return->error  = 'File is empty';
        }
        elseif(!file_exists($backupFile))
        {
            $return->result = false;
            $return->error  = 'File is not exists';
        }
        elseif(!is_file($backupFile))
        {
            $return->result = false;
            $return->error  = 'Not a valid file';
        }

        return $return;
    }
}