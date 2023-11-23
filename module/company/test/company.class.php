<?php
class companyTest
{

    /**
     * __construct loadModel execution
     *
     * @access public
     */
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('company');
    }

    /**
     * 测试更新公司信息的方法。
     * Test update a compnay.
     *
     * @param  string $objectID
     * @param  array  $param
     * @access public
     * @return object|array
     */
    public function updateObject($objectID, $param = array()): object|array
    {
        global $tester;

        $object = $tester->dbh->query("SELECT `name`,`phone`,`fax`,`address`,`zipcode`,`website`,`backyard`,`guest`
             FROM zt_company WHERE id = $objectID")->fetch();

        foreach($param as $key => $value) $object->{$key} = $value;

        $this->objectModel->update($objectID, $object);

        $change = $tester->dbh->query("select * from zt_company WHERE id = $objectID")->fetch();

        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $change;
        }
    }

    /**
     * function getFirst test by company
     *
     * @access public
     * @return array
     */
    public function getFirstTest()
    {
        $object = $this->objectModel->getFirst();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getByID test by company
     *
     * @param  string $companyID
     * @access public
     * @return array
     */
    public function getByIDTest($companyID = '')
    {
        $object = $this->objectModel->getByID($companyID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getUsers test by company
     *
     * @param  string $count
     * @param  string $browseType
     * @param  string $type
     * @param  string $queryID
     * @param  string $deptID
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getUsersTest($count, $browseType, $type, $queryID, $deptID, $sort = '')
    {
        $object = $this->objectModel->getUsers($browseType, $type, $queryID, $deptID, $sort);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getOutsideCompanies test by company
     *
     * @access public
     * @return array
     */
    public function getOutsideCompaniesTest()
    {
        $object = $this->objectModel->getOutsideCompanies();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }
}
