<?php
/**
 * The model file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class dataviewModel extends model
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadBIDAO();
    }

    /**
     * Get module names.
     *
     * @param  string    $tables
     * @access public
     * @return array
     */
    public function getModuleNames($tables)
    {
        $moduleNames = array();
        foreach($tables as $table)
        {
            if(strpos($table, $this->config->db->prefix) === false) continue;

            $module = str_replace($this->config->db->prefix, '', $table);
            if(!preg_match("/^[a-zA-Z]+$/", $module)) continue;

            if($module == 'case')   $module = 'testcase';
            if($module == 'module') $module = 'tree';

            /* Code for workflow.*/
            if(strpos($module, 'flow_') !== false)
            {
                $moduleName = substr($module, 5);

                $flowFields = $this->loadModel('workflowfield')->getFieldPairs($moduleName);
                $this->lang->$moduleName = new stdclass();
                foreach($flowFields as $flowField => $fieldName)
                {
                    if(!$flowField) continue;
                    $this->lang->$moduleName->$flowField = $fieldName;
                }

                $moduleNames[$table] = $module;
            }
            else
            {
                if($this->app->loadLang($module))
                {
                    if($module == 'project') $this->lang->project->statusList += $this->lang->dataview->projectStatusList;
                    $moduleNames[$table] = $module;
                }
            }
        }

        return $moduleNames;
    }

    /**
     * Get alias names.
     *
     * @param  object $statement
     * @param  array  $moduleNames
     * @access public
     * @return array
     */
    public function getAliasNames($statement, $moduleNames)
    {
        $aliasNames = array();
        if(isset($statement->from))
        {
            foreach($statement->from as $from)
            {
                if(isset($moduleNames[$from->table]))
                {
                    $aliasNames[$from->alias] = $from->table;
                }
            }
        }
        if(isset($statement->join))
        {
            foreach($statement->join as $join)
            {
                if(isset($moduleNames[$join->expr->table]))
                {
                    $aliasNames[$join->expr->alias] = $join->expr->table;
                }
            }
        }

        return $aliasNames;
    }

    /**
     * Merge fields.
     *
     * @param  array  $dataFields
     * @param  array  $sqlFields
     * @param  array  $moduleNames
     * @param  array  $aliasNames
     * @access public
     * @return void
     */
    public function mergeFields($dataFields, $sqlFields, $moduleNames, $aliasNames = array())
    {
        $mergeFields   = array();
        $relatedObject = array();
        foreach($dataFields as $field)
        {
            $mergeFields[$field]   = $field;
            $relatedObject[$field] = current($moduleNames);

            /* Such as $sqlFields['id'] = zt_task.id. */
            if(isset($sqlFields[$field]) and strrpos($sqlFields[$field], '.') !== false)
            {
                $sqlField  = $sqlFields[$field];
                $table     = substr($sqlField, 0, strrpos($sqlField, '.'));
                $fieldName = substr($sqlField, strrpos($sqlField, '.') + 1);

                if(isset($moduleNames[$table]))
                {
                    $moduleName = $moduleNames[$table];
                    if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                    $mergeFields[$field]   = isset($this->lang->$moduleName->$fieldName) ? $this->lang->$moduleName->$fieldName : $field;
                    $relatedObject[$field] = $moduleName;
                    continue;
                }
                elseif(isset($aliasNames[$table]))
                {
                    $moduleName = $aliasNames[$table];
                    if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                    $mergeFields[$field]   = isset($this->lang->$moduleName->$fieldName) ? $this->lang->$moduleName->$fieldName : $field;
                    $relatedObject[$field] = $moduleName;
                    continue;
                }
            }

            if(strpos(join(',', $sqlFields), '.*') !== false)
            {
                /* Such as $sqlFields['zt_task.*'] = zt_task.*. */
                $existField = false;
                foreach($sqlFields as $sqlField)
                {
                    if(strrpos($sqlField, '.*') !== false)
                    {
                        $table = substr($sqlField, 0, strrpos($sqlField, '.'));
                        if(isset($moduleNames[$table]))
                        {
                            $moduleName = $moduleNames[$table];
                            if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                            $mergeFields[$field]   = isset($this->lang->$moduleName->$field) ? $this->lang->$moduleName->$field : $field;
                            $relatedObject[$field] = $moduleName;
                            $existField = true;
                            break;
                        }
                    }
                }
                if($existField) continue;
            }

            foreach($moduleNames as $table => $moduleName)
            {
                if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);

                if(isset($this->lang->$moduleName) and isset($this->lang->$moduleName->$field))
                {
                    $mergeFields[$field]   = $this->lang->$moduleName->$field;
                    $relatedObject[$field] = $moduleName;
                    break;
                }
                $mergeFields[$field]   = $field;
                $relatedObject[$field] = $moduleName;
            }
        }

        foreach($mergeFields as $fieldName => $fieldValue)
        {
            if(empty($fieldValue)) $mergeFields[$fieldName] = $fieldName;
        }

        foreach($mergeFields as $field => $name) $mergeFields[$field] = $this->replace4Workflow($name);
        return array($mergeFields, $relatedObject);
    }

    /**
     * Get table data.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getColumns($sql, $columns = null)
    {
        if(empty($columns)) $columns = $this->dao->getColumns($sql);

        $columnTypes = new stdclass();
        foreach($columns as $column)
        {
            $field      = $column['name'];
            $nativeType = $column['native_type'];
            $type       = $this->config->dataview->columnTypes->$nativeType;

            if(isset($columnTypes->$field)) $field = $column['table'] . $field;
            $columnTypes->$field = $type;
        }

        return $columnTypes;
    }

    /**
     * Check that the column of an sql query is unique.
     *
     * @param  string $sql
     * @param  bool   $repeat
     * @access public
     * @return bool
     */
    public function checkUniColumn($sql, $repeat = false, $columns = null)
    {
        if(empty($columns)) $columns = $this->dao->getColumns($sql);

        $isUnique     = true;
        $columnList   = array();
        $repeatColumn = array();
        foreach($columns as $column)
        {
            $field = $column['name'];
            if(isset($columnTypes[$field]))
            {
                $isUnique = false;
                $repeatColumn[$field] = $field;
            }

            $columnTypes[$field] = $field;
        }

        if($repeat) return array($isUnique, $repeatColumn);
        return $isUnique;
    }

    /**
     * Get type options
     *
     * @param string $objectName
     * @access public
     * @return array
     */
    public function getTypeOptions($objectName)
    {
        $schema  = $this->includeTable($objectName);
        if(empty($schema)) return array();

        $options = array();
        foreach($schema->fields as $key => $field)
        {
            //if($field['type'] == 'object') continue;
            $options[$key] = $field;
        }
        return $options;
    }

    /**
    * Replace title for workflow.
    *
    * @param  string $title
    * @access public
    * @return string
    */
    public function replace4Workflow($title)
    {
        $clientLang = $this->app->getClientLang();

        $productCommonList = isset($this->config->productCommonList[$clientLang]) ? $this->config->productCommonList[$clientLang] : $this->config->productCommonList['en'];
        $projectCommonList = isset($this->config->projectCommonList[$clientLang]) ? $this->config->projectCommonList[$clientLang] : $this->config->projectCommonList['en'];

        $productCommon = $productCommonList[0];
        $projectCommon = $projectCommonList[0];

        if(strpos($title, strtolower($productCommon)) !== false) $title = str_replace(strtolower($productCommon), strtolower($this->lang->productCommon), $title);
        if(strpos($title, $productCommon) !== false)             $title = str_replace($productCommon, $this->lang->productCommon, $title);

        return $title;
    }

    /**
    * Include table
    *
    * @param  string    $table
    * @access public
    * @return void
    */
    public function includeTable($table)
    {
        $path = __DIR__ . DS . 'table' . DS . "$table.php";
        if(file_exists($path))
        {
            include $path;
            return $schema;
        }

        $path = $this->app->getExtensionRoot() . 'custom' . DS . 'dataview' . DS . 'table' . DS . "$table.php";
        if(file_exists($path))
        {
            include $path;
            return $schema;
        }
    }

    /**
     * Gen tree options.
     *
     * @param object $tree
     * @param array  $values
     * @param array  $paths
     * @access public
     * @return void
     */
    public function genTreeOptions(&$moduleTree, $values, $paths)
    {
        $path = $paths[0];
        if(!isset($moduleTree->children))$moduleTree->children = array();

        foreach($moduleTree->children as $child)
        {
            if($child->value == $path)
            {
                if(count($paths) > 1) return $this->genTreeOptions($child, $values, array_slice($paths, 1));
                return;
            }
        }

        $child = new stdclass();
        $child->title = $values[$path];
        $child->value = $path;
        $moduleTree->children[] = $child;
        if(count($paths) > 1) return $this->genTreeOptions($child, $values, array_slice($paths, 1));
    }

    /**
    * Adjust the action is clickable.
    *
    * @param  object $dataview
    * @param  string $action
    * @static
    * @access public
    * @return bool
    */
    public static function isClickable($dataview, $action)
    {
       return true;
    }
}
