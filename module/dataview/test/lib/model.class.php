<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class dataviewModelTest extends baseTest
{
    protected $moduleName = 'dataview';
    protected $className  = 'model';

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
        $dataviewModel = $this->instance;

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
            $result = $this->instance->verifySqlWithModify($sql);
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
        $result = $this->instance->processMergeFields($moduleName, $field, $fieldName, $workflowFields);
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
        $result = $this->instance->getObjectFields();
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
        $result = $this->instance->checkUniColumn($sql, $driverName, $repeat, $columns);
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
        $result = $this->instance->getAliasNames($statement, $moduleNames);
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
        $this->instance->genTreeOptions($moduleTree, $values, $paths);
        if(dao::isError()) return dao::getError();

        return $moduleTree;
    }

    /**
     * Test genTreeOptions method with existing nodes.
     *
     * @param  array  $values
     * @param  array  $paths
     * @access public
     * @return mixed
     */
    public function genTreeOptionsTestWithExisting($values, $paths)
    {
        $tree = new stdclass();
        $tree->children = array();
        $existingChild = new stdclass();
        $existingChild->title = 'Existing Node';
        $existingChild->value = 'existing';
        $tree->children[] = $existingChild;

        $this->instance->genTreeOptions($tree, $values, $paths);
        if(dao::isError()) return dao::getError();

        return $tree;
    }

    /**
     * Test genTreeOptions method with multiple siblings.
     *
     * @access public
     * @return array
     */
    public function genTreeOptionsTestMultiple()
    {
        $tree = new stdclass();
        $this->instance->genTreeOptions($tree, array('branch1' => 'Branch 1'), array('branch1'));
        $this->instance->genTreeOptions($tree, array('branch2' => 'Branch 2'), array('branch2'));

        if(dao::isError()) return dao::getError();

        return array('tree' => $tree);
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
        $result = $this->instance->getModuleNames($tables);
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
        $result = $this->instance->getTypeOptions($objectName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test includeTable method.
     *
     * @param  string $table
     * @access public
     * @return mixed
     */
    public function includeTableTest($table)
    {
        $result = $this->instance->includeTable($table);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  object $dataview
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function isClickableTest($dataview, $action)
    {
        $result = dataviewModel::isClickable($dataview, $action);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test replace4Workflow method.
     *
     * @param  string $title
     * @access public
     * @return mixed
     */
    public function replace4WorkflowTest($title)
    {
        $result = $this->instance->replace4Workflow($title);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}