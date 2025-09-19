<?php
declare(strict_types = 1);
class dataviewTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('dataview');
        $this->objectTao   = $tester->loadTao('dataview');
    }

    /**
     * Test __construct method.
     *
     * @param  string $testType
     * @access public
     * @return object
     */
    public function __constructTest(string $testType = 'normal'): object
    {
        $result = new stdClass();
        $dataviewModel = $this->objectModel;
        
        switch($testType) {
            case 'biModel':
                $result->result = property_exists($dataviewModel, 'bi') && !empty($dataviewModel->bi);
                break;
            case 'parentConstructor':
                $result->result = property_exists($dataviewModel, 'app') && !empty($dataviewModel->app);
                break;
            case 'dao':
                $result->result = property_exists($dataviewModel, 'dao') && !empty($dataviewModel->dao);
                break;
            case 'modelInstance':
                $result->result = $dataviewModel instanceof dataviewModel;
                break;
            case 'modelExists':
                $result->result = !empty($dataviewModel);
                break;
            case 'className':
                $result->result = get_class($dataviewModel);
                break;
            default:
                $result->result = 'normal';
                break;
        }
        
        return $result;
    }

    /**
     * Test verifySqlWithModify method.
     *
     * @param  string $sql
     * @access public
     * @return mixed
     */
    public function verifySqlWithModifyTest($sql)
    {
        try {
            $result = $this->objectModel->verifySqlWithModify($sql);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return array('result' => 'fail', 'message' => $e->getMessage());
        } catch(Error $e) {
            return array('result' => 'fail', 'message' => $e->getMessage());
        }
    }

    /**
     * Test processMergeFields method.
     *
     * @param  string $moduleName
     * @param  string $field
     * @param  string $fieldName
     * @param  array  $workflowFields
     * @access public
     * @return mixed
     */
    public function processMergeFieldsTest($moduleName, $field, $fieldName, $workflowFields = array())
    {
        $result = $this->objectModel->processMergeFields($moduleName, $field, $fieldName, $workflowFields);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getObjectFields method.
     *
     * @access public
     * @return mixed
     */
    public function getObjectFieldsTest()
    {
        $result = $this->objectModel->getObjectFields();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkUniColumn method.
     *
     * @param  string $sql
     * @param  string $driverName
     * @param  bool   $repeat
     * @param  array  $columns
     * @access public
     * @return mixed
     */
    public function checkUniColumnTest($sql, $driverName = 'mysql', $repeat = false, $columns = array())
    {
        $result = $this->objectModel->checkUniColumn($sql, $driverName, $repeat, $columns);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAliasNames method.
     *
     * @param  object $statement
     * @param  array  $moduleNames
     * @access public
     * @return mixed
     */
    public function getAliasNamesTest($statement, $moduleNames)
    {
        $result = $this->objectModel->getAliasNames($statement, $moduleNames);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test genTreeOptions method.
     *
     * @param  object $moduleTree
     * @param  array  $values
     * @param  array  $paths
     * @access public
     * @return mixed
     */
    public function genTreeOptionsTest(&$moduleTree, $values, $paths)
    {
        $this->objectModel->genTreeOptions($moduleTree, $values, $paths);
        if(dao::isError()) return dao::getError();

        return $moduleTree;
    }

    /**
     * Test getModuleNames method.
     *
     * @param  array $tables
     * @access public
     * @return mixed
     */
    public function getModuleNamesTest($tables)
    {
        $result = $this->objectModel->getModuleNames($tables);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTypeOptions method.
     *
     * @param  string $objectName
     * @access public
     * @return mixed
     */
    public function getTypeOptionsTest($objectName)
    {
        $result = $this->objectModel->getTypeOptions($objectName);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}