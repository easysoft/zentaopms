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

    /**
     * Test tableExistsOfJira method.
     *
     * @param  string $dbName
     * @param  string $table
     * @access public
     * @return mixed
     */
    public function tableExistsOfJiraTest($dbName = null, $table = null)
    {
        $result = $this->objectModel->tableExistsOfJira($dbName, $table);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveState method.
     *
     * @access public
     * @return mixed
     */
    public function saveStateTest()
    {
        try {
            $this->objectModel->saveState();
            if(dao::isError()) return dao::getError();

            global $app;
            $state = $app->session->state;
            return is_array($state) ? 'array' : gettype($state);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        }
    }

    /**
     * Test getJiraData method.
     *
     * @param  string $method
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function getJiraDataTest($method = null, $module = null, $lastID = 0, $limit = 0)
    {
        $result = $this->objectModel->getJiraData($method, $module, $lastID, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}