<?php
/**
 * The model file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        https://www.zentao.net
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
     * 获取模块名数组。
     * Get module names.
     *
     * @param  array  $tables
     * @access public
     * @return array
     */
    public function getModuleNames(array $tables): array
    {
        $moduleNames = array();
        foreach($tables as $table)
        {
            /* 没有带zt_的表忽视掉。 */
            if(strpos($table, $this->config->db->prefix) === false) continue;

            /* 过滤掉zt_后获取到模块名。 */
            $module = str_replace($this->config->db->prefix, '', $table);
            if(!preg_match("/^[a-zA-Z]+$/", $module)) continue;

            /* 某些比较特殊的模块需要单独处理。 */
            if($module == 'case')   $module = 'testcase';
            if($module == 'module') $module = 'tree';

            /* Code for workflow.*/
            if(strpos($module, 'flow_') !== false)
            {
                /* 如果是工作流创建的表，则将工作流的字段名当做语言项初始化。 */
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
                /* 如果不是工作流创建的表，则直接读取这个模块的语言项。 */
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
     * 获取别名数组。
     * Get alias names.
     *
     * @param  object $statement
     * @param  array  $moduleNames
     * @access public
     * @return array
     */
    public function getAliasNames(object $statement, array $moduleNames): array
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
     * 合并字段。
     * Merge fields.
     *
     * @param  array  $dataFields
     * @param  array  $sqlFields
     * @param  array  $moduleNames
     * @param  array  $aliasNames
     * @access public
     * @return array
     */
    public function mergeFields(array $dataFields, array $sqlFields, array $moduleNames, array $aliasNames = array()): array
    {
        $mergeFields   = array();
        $relatedObject = array();
        foreach($dataFields as $field)
        {
            $mergeFields[$field]   = $field;
            $relatedObject[$field] = current($moduleNames);

            /* Such as $sqlFields['id'] = zt_task.id. */
            if(isset($sqlFields[$field]) && strrpos($sqlFields[$field], '.') !== false)
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
     * 获取表的字段类型。
     * Get table data.
     *
     * @param  string $sql
     * @access public
     * @return object
     */
    public function getColumns(string $sql): object
    {
        $columns = $this->dao->getColumns($sql);

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
     * 检查查询结果的唯一性。
     * Check that the column of an sql query is unique.
     *
     * @param  string     $sql
     * @param  bool       $repeat
     * @access public
     * @return bool|array
     */
    public function checkUniColumn($sql, $repeat = false): bool|array
    {
        $columns = $this->dao->getColumns($sql);

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
     * 获取该模块的字段列表。
     * Get type options.
     *
     * @param  string $objectName
     * @access public
     * @return array
     */
    public function getTypeOptions(string $objectName): array
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
    * 替换语言项。
    * Replace title for workflow.
    *
    * @param  string $title
    * @access public
    * @return string
    */
    public function replace4Workflow(string $title): string
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
    * 加载表配置项。
    * Include table.
    *
    * @param  string $table
    * @access public
    * @return object
    */
    public function includeTable(string $table): object
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
     * 组装父子层级结构数据。
     * Gen tree options.
     *
     * @param  object $tree
     * @param  array  $values
     * @param  array  $paths
     * @access public
     * @return void
     */
    public function genTreeOptions(object &$moduleTree, array $values, array $paths)
    {
        $path = $paths[0];
        if(!isset($moduleTree->children)) $moduleTree->children = array();

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
    * 检查按钮是否可以点击。
    * Adjust the action is clickable.
    *
    * @param  object $dataview
    * @param  string $action
    * @static
    * @access public
    * @return bool
    */
    public static function isClickable(object $dataview, string $action): bool
    {
       return true;
    }
}
