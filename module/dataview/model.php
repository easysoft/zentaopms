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
     * Get a dataview by id.
     *
     * @param  int    $dataviewID
     * @access public
     * @return bool | object
     */
    public function getByID($dataviewID)
    {
        $dataview = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('id')->eq($dataviewID)->fetch();
        if(!$dataview) return false;

        $dataview->vars = $this->getVars($dataview->sql);
        $dataview->used = $this->checkUsed($dataview);

        if(!empty($dataview->fields))
        {
            $dataview->fieldSettings = json_decode($dataview->fields);
            $dataview->fields        = array();

            foreach($dataview->fieldSettings as $field => $settings) $dataview->fields[] = $field;
        }
        return $dataview;
    }

    /**
     * Get a dataview by view.
     *
     * @param  string    $view
     * @access public
     * @return bool | object
     */
    public function getByView($view)
    {
        return $this->dao->select('*')->from(TABLE_DATAVIEW)->where('view')->eq($view)->fetch();
    }

    /**
     * Get a origin table name.
     *
     * @param  string    $table
     * @access public
     * @return bool | object
     */
    public function getTableName($table)
    {
        $tableName = '';

        if(empty($this->config->db->prefix) or strpos($table, $this->config->db->prefix) !== false)
        {
            if(strpos($table, $this->config->db->prefix . 'flow_') !== 0)
            {
                $subTable = substr($table, strpos($table, '_') + 1);
                $tableName = zget($this->lang->dev->tableList, $subTable, '');
            }
        }

        return $tableName;
    }

    /**
     * Strip vars.
     *
     * @param string $sql
     * @access public
     * @return string
     */
    public function stripVars($sql)
    {
        $vars = array();
        if(preg_match_all("/(where|and|or)(:?.(?!(where|and|or)))+?[\$]+[a-z.A-Z]+/i", $sql, $out))
        {
            foreach($out[0] as $match)
            {
                if(strpos($match, '($')) $match .= ')';
                $begin = substr($match, 0, 1);
                switch($begin)
                {
                case 'A':
                case 'a':
                    $sql = str_ireplace($match, 'AND 1', $sql);
                    break;
                case 'O':
                case 'o':
                    $sql = str_ireplace($match, 'OR 1', $sql);
                    break;
                case 'W':
                case 'w':
                    $sql = str_ireplace($match, 'WHERE 1', $sql);
                    break;
                }
            }
        }

        return $sql;
    }

    /**
     * Get vars.
     *
     * @param string $sql
     * @access private
     * @return void
     */
    private function getVars($sql)
    {
        $vars = array();
        if(preg_match_all("/[\$]+[a-z.A-Z]+/", $sql, $out))
        {
            foreach($out[0] as $match)
            {
                $var = explode('.', $match);
                if(count($var) != 2) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->varError));
                $vars[] = substr($match, 1);
            }
        }
        return $vars;
    }

    /**
     * Get table info.
     *
     * @param  string $key
     * @access public
     * @return object
     */
    public function getTableInfo($key)
    {
        $this->app->loadLang('chart');

        if(strpos($key, 'custom_') === 0)
        {
            $key = substr($key, 7);
            return $this->getDatasetInfo($key);
        }

        $tableInfo = $this->lang->dataview->tables[$key];

        $table = new stdclass();
        $table->key    = $key;
        $table->name   = $tableInfo['name'];
        $table->desc   = $tableInfo['desc'];
        $table->schema = $this->includeTable($key);

        return $table;
    }

    /**
     * Get dataset info.
     *
     * @param  int    $id
     * @access private
     * @return void
     */
    private function getDatasetInfo($id)
    {
        $dataview = $this->dao->select('id AS `key`, name, fields, `sql` AS `desc`')->from(TABLE_DATAVIEW)->where('id')->eq($id)->fetch();
        $dataview->schema = new stdclass();
        $dataview->schema->sql          = $dataview->desc;
        $dataview->schema->primaryTable = 'custom_';
        $dataview->schema->fields       = json_decode($dataview->fields, true);

        return $dataview;
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
     * Merge fields.
     *
     * @param  int    $dataFields
     * @param  int    $sqlFields
     * @param  int    $moduleNames
     * @access public
     * @return void
     */
    public function mergeFields($dataFields, $sqlFields, $moduleNames)
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
    public function getColumns($sql)
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
     * Get table data.
     *
     * @param  string $table
     * @param  string $type
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getTableData($table, $type, $limit = 25)
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        if($type == 'table') return $this->dao->select('*')->from($table)->limit($limit)->fetchAll();
        if($type == 'view' and is_numeric($table) !== false)
        {
            $table = $this->getByID($table);
            return $this->dao->select('*')->from($table->view)->limit($limit)->fetchAll();
        }
    }

    /**
     * Print cell of data.
     *
     * @param mixed $data
     * @param mixed $field
     * @param mixed $info
     * @access public
     * @return void
     */
    public function printCell($data, $field, $info)
    {
        $attr  = $info['type'] == 'object' ? str_replace('.', '_', $info['show']) : $field;
        $value = strip_tags($data->$attr);
        switch($info['type'])
        {
            case 'date':
                if(strpos($value, '0000-00-00') === 0) $value = '';
                break;
            case 'option':
                $value = zget($info['options'], $value, $value);
                break;
        }
        echo $value;
    }

    /**
     * Get filters.
     *
     * @param array $dataviews
     * @access public
     * @return array
     */
    public function getFilters($dataviews)
    {
        $objects = array('product', 'productline', 'project', 'program', 'execution', 'build', 'caselib', 'casemodule');
        $optionFields = array();
        $dateFields   = array();
        foreach($dataviews as $dataview)
        {
            if(!$dataview or strpos($dataview, 'custom_') === 0) continue;

            $schema = $this->includeTable($dataview);
            $table = $schema->primaryTable;
            if(in_array($table, $objects)) $optionFields[$table . '.id'] = array('name' => $this->lang->dataview->objects[$table], 'options' => array(), 'type' => $table);

            foreach($schema->fields as $key => $field)
            {
                if($field['type'] == 'object')
                {
                    $object = $field['object'];
                    if(in_array($object, $objects)) $optionFields[$object . '.id'] = array('name' => $this->lang->dataview->objects[$object], 'options' => array(), 'type' => $object);
                    foreach($schema->objects[$object] as $subKey => $subField)
                    {
                        if($subField['type'] == 'option')
                        {
                            $optionFields[$object . '.' . $subKey] = array('name' => $this->lang->dataview->objects[$object] . '.' . $subField['name'], 'options' => $subField['options'], 'type' => 'option');
                        }
                        else if($subField['type'] == 'user')
                        {
                            $optionFields[$object . '.' . $subKey] = array('name' => $this->lang->dataview->objects[$object] . '.' . $subField['name'], 'options' => array(), 'type' => 'user');
                        }
                        else if(in_array($subField['type'], array('date', 'time', 'datetime')))
                        {
                            $dateFields[$object . '.' . $subKey] = array('name' => $this->lang->dataview->objects[$object] . '.' . $subField['name']);
                        }
                    }
                }
                else if($field['type'] == 'option')
                {
                    $optionFields[$table . '.' . $key] = array('name' => $this->lang->dataview->objects[$table] . '.' . $field['name'], 'options' => $field['options'], 'type' => 'option');
                }
                else if($field['type'] == 'user')
                {
                    $optionFields[$table . '.' . $key] = array('name' => $this->lang->dataview->objects[$table] . '.' . $field['name'], 'options' => array(), 'type' => 'user');
                }
                else if(in_array($field['type'], array('date', 'time', 'datetime')))
                {
                    $dateFields[$table . '.' . $key] = array('name' => $this->lang->dataview->objects[$table] . '.' . $field['name']);
                }
            }
            unset($schema);
        }

        return array('option' => $optionFields, 'date' => $dateFields);
    }

    /**
     * Get options of system.
     *
     * @param array $sysOptions
     * @access public
     * @return array
     */
    public function getSysOptions($sysOptions)
    {
        $sysOptions['user'] = array(); // All options must have user.

        $defaults = array();
        foreach($sysOptions as $type => $option)
        {
            $options = array();
            switch($type)
            {
                case 'user':
                    $users = $this->loadModel('user')->getPairs();
                    foreach($users as $key => $user) $options[] = array('value' => $key, 'label' => $user);
                    break;
                case 'product':
                    $products = $this->loadModel('product')->getPairs();
                    foreach($products as $key => $product) $options[] = array('value' => (string)$key, 'label' => $product);
                    $defaults['product'] = (int)$this->product->saveState(0, $products);
                    break;
                case 'project':
                    $projects = $this->loadModel('project')->getPairsByProgram();
                    foreach($projects as $key => $project) $options[] = array('value' => (string)$key, 'label' => $project);
                    $defaults['project'] = (string)$this->project->saveState(0, $projects);
                    break;
                case 'execution':
                    $executions = $this->loadModel('execution')->getPairs($defaults['project']);
                    foreach($executions as $key => $execution) $options[] = array('value' => (string)$key, 'label' => $execution);
                    $defaults['execution'] = (string)$this->execution->saveState(0, $executions);
                    break;
                case 'build':
                    $builds = $this->loadModel('build')->getExecutionBuilds($defaults['execution']);
                    foreach($builds as $build) $options[] = array('value' => (string)$build->id, 'label' => $build->name);
                    $defaults['build'] = (string)key($builds);
                    break;
                case 'caselib':
                    $libs = $this->loadModel('caselib')->getLibraries();
                    foreach($libs as $key => $lib) $options[] = array('value' => (string)$key, 'label' => $lib);
                    $defaults['caselib'] = (string)key($libs);
                    break;
                case 'casemodule':
                    if(isset($defaults['build']))
                    {
                        $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($defaults['build'])->fetchPairs('id');
                    }
                    else if(isset($defaults['execution']))
                    {
                        $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('execution')->eq($defaults['execution'])->fetchPairs('id');
                    }
                    else if(isset($defaults['project']))
                    {
                        $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('project')->eq($defaults['project'])->fetchPairs('id');
                    }
                    else
                    {
                        $defaults['casemodule'] = '';
                        break;
                    }
                    if(empty($testtasks))
                    {
                        $defaults['casemodule'] = '';
                        break;
                    }

                    $moduleIdList = $this->dao->select('distinct module')->from(TABLE_CASE)->alias('t1')
                        ->leftJoin(TABLE_TESTRUN)->alias('t2')
                        ->on('t1.id = t2.case')
                        ->where('t2.task')->in($testtasks)
                        ->fetchPairs();

                    $modules    = $this->dao->select('id, name, path, branch')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
                    $allModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');
                    $moduleTree = new stdclass();
                    foreach($modules as $module)
                    {
                        $paths = explode(',', trim($module->path, ','));
                        $this->genTreeOptions($moduleTree, $allModules, $paths);
                    }

                    $options = isset($moduleTree->children) ? $moduleTree->children : array();
                    $defaults['casemodule'] = '';
                    break;
            }

            $sysOptions[$type] = $options;
        }

        return array($sysOptions, $defaults);
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
     * Get dataviews.
     *
     * @param string $type
     * @access public
     * @return void
     */
    public function getList($type)
    {
        if($type == 'internal')
        {
            $dataviews = array();
            foreach($this->lang->dataview->tables as $code => $table)
            {
                $table['id']   = 0;
                $table['code'] = $code;
                $dataviews[]    = $table;
            }
            return $dataviews;
        }

        $result = array();
        $dataviews = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('deleted')->eq(0)->fetchAll();
        foreach($dataviews as $dataview)
        {
            $result[] = array('id' => $dataview->id, 'name' => $dataview->name, 'code' => 'custom_' . $dataview->id, 'desc' => $dataview->sql);
        }

        return $result;
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
     * Create a dataview.
     *
     * @access public
     * @return bool | int
     */
    public function create()
    {
        $dataView = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('view', 'ztv_' . $this->post->code)
            ->setDefault('sql', '')
            ->skipSpecial('fields,objects,sql,langs')
            ->get();

        $this->existView($dataView->view);
        if(dao::isError()) return false;

        $this->dao->insert(TABLE_DATAVIEW)->data($dataView)
            ->batchCheck($this->config->dataview->create->requiredFields, 'notempty')
            ->batchCheck('name,code', 'unique')
            ->check('code', 'code')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $viewID = $this->dao->lastInsertID();

        if(!empty($dataView->view) and !empty($dataView->sql)) $this->createViewInDB($viewID, $dataView->view, $dataView->sql);
        return $viewID;
    }

    /**
     * Save query a dataview.
     *
     * @param int $viewID
     * @access public
     * @return bool | int
     */
    public function querySave($viewID)
    {
        $post = fixer::input('post')->skipSpecial('fields,objects,sql,langs')->get();

        $this->dao->update(TABLE_DATAVIEW)->data($post)->where('id')->eq($viewID)->exec();
        if(dao::isError()) return false;

        $dataView = $this->dao->select('view,`sql`')->from(TABLE_DATAVIEW)->where('id')->eq($viewID)->fetch();

        $this->createViewInDB($viewID, $dataView->view, $dataView->sql);
        if(dao::isError()) return false;
    }

    /**
     * Create or replace a view in database.
     *
     * @param  int    $viewID
     * @param  string $viewName
     * @param  string $sql
     * @param  object $oldView
     * @access public
     * @return void
     */
    public function createViewInDB($viewID, $viewName, $sql, $oldView = null)
    {
        try
        {
            $this->dbh->query("CREATE OR REPLACE VIEW $viewName AS $sql");
        }
        catch(PDOException $exception)
        {
            if($oldView) $this->dao->update(TABLE_DATAVIEW)->data($oldView)->where('id')->eq($viewID)->exec();
            if(!$oldView) $this->dao->delete()->from(TABLE_DATAVIEW)->where('id')->eq($viewID)->exec();

            dao::$errors['sql'][] = $exception->getMessage();
        }
    }

    /**
     * Delete a view in database.
     *
     * @param  string $viewName
     * @access public
     * @return void
     */
    public function deleteViewInDB($viewName)
    {
        try
        {
            $this->dbh->query("DROP VIEW IF EXISTS $viewName");
        }
        catch(PDOException $exception)
        {
            dao::$errors['sql'][] = $exception->getMessage();
        }
    }



    /**
     * Check view exist.
     *
     * @param string @viewName
     * @access public
     * @return void
     */
    public function existView($viewName)
    {
        $existView = $this->dbh->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_NAME like 'ztv_%' AND TABLE_NAME = '$viewName'")->fetchAll();
        if(count($existView) > 0) dao::$errors['view'][] = $this->lang->dataview->existView;
    }

    /**
     * Update a dataview.
     *
     * @param  $dataViewID
     * @access public
     * @return bool | array
     */
    public function update($dataViewID)
    {
        $oldDataView = $this->getByID($dataViewID);

        $dataView = fixer::input('post')->get();

        $this->dao->update(TABLE_DATAVIEW)->data($dataView)
            ->batchCheck($this->config->dataview->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($dataViewID)
            ->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldDataView, $dataView);
    }

    /**
     * Check usage of a dataview.
     *
     * @param  object $dataview
     * @access public
     * @return bool
     */
    public function checkUsed($dataview)
    {
        $views = $this->dao->select('id, name')->from(TABLE_DATAVIEW)->where('deleted')->eq('0')->andWhere('id')->ne($dataview->id)->andWhere('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        if(!empty($views)) return true;

        $pivots = $this->dao->select('id, name')->from(TABLE_PIVOT)->where('deleted')->eq('0')->andWhere('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        if(!empty($pivots)) return true;

        $charts = $this->dao->select('id, name')->from(TABLE_CHART)->where('deleted')->eq('0')->andWhere('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        if(!empty($charts)) return true;

        $reports = $this->dao->select('id, name')->from(TABLE_REPORT)->where('`sql`')->like("%{$dataview->view}%")->fetchPairs();
        return !empty($reports);
    }

    /**
     * Get filters from sql vars.
     *
     * @param  array $vars
     * @access public
     * @return array
     */
    public function getVarFilters($vars)
    {
        $filters = array();
        foreach($vars as $var)
        {
            $filter = new stdclass();
            $filter->multiple = false;
            $filter->var      = $var;
            $filter->options  = array();
            $vars = explode('.', $var);

            if($vars[1] == 'id')
            {
                switch($vars[0])
                {
                case 'user':
                    $filter->options = $this->loadModel('user')->getPairs();
                    break;
                case 'product':
                    $filter->options = $this->loadModel('product')->getPairs();
                    break;
                case 'project':
                    $filter->options = $this->loadModel('project')->getPairsByProgram();
                    break;
                case 'execution':
                    $filter->options = $this->loadModel('execution')->getPairs();
                    break;
                case 'caselib':
                    $filter->options = $this->loadModel('caselib')->getLibraries();
                    break;
                case 'casemodule':
                    $filter->options = array();
                    break;
                }
            }
            else
            {
                $schema = $this->includeTable($vars[0]);
                $filter->options = $schema->fields[$vars[1]]->options;
            }
            $filters[] = $filter;
        }

        return $filters;
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
     * Get fields of dataview.
     *
     * @param  int    $table
     * @access public
     * @return array
     */
    public function getFields($table)
    {
        $table = $this->dao->select('*')->from(TABLE_DATAVIEW)->beginIF(!empty($table))->where('id')->eq($table)->fi()->fetch();
        $existView = $this->dbh->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_NAME like 'ztv_%' AND TABLE_NAME = '$table->view'")->fetchAll();
        if(empty($table->view) or empty($table) or empty($existView)) return array();

        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC `$table->view`";
            $rawFields = $this->dbh->query($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            dao::$errors[] = $e->getMessage();
            return false;
        }

        $this->loadModel('dev');
        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny'), '', $type);
            $field    = array();

            $field['null']            = $rawField->null;
            $fields[$rawField->field] = $this->dev->setField($field, $rawField, $type, $firstPOS);
        }
        return $fields;
    }

    /**
     * Build origin tree menu.
     *
     * @param  string $selectedTable
     * @access public
     * @return string
     */
    public function getOriginTreeMenu($selectedTable = '')
    {
        $treeMenu = '';
        $tables   = $this->loadModel('dev')->getTables();
        $tables   = $this->processTable($tables);

        foreach($this->lang->dataview->groupList as $group => $groupName)
        {
            if(isset($tables[$group]))
            {
                $groupMenu = '';
                foreach($tables[$group] as $subTable => $table)
                {
                    if(is_array($table))
                    {
                        $groupMenu .= $this->buildThirdMenu($subTable, $table, $selectedTable);
                        continue;
                    }

                    $active    = ($table == $selectedTable) ? 'active' : '';
                    $tableName = zget($this->lang->dev->tableList, $subTable, '');
                    $link      = html::a(helper::createLink('dataview', 'browse', "type=table&table=$table"), $tableName, '_self', "class='$active' id='module{$table}' title='{$tableName}'");

                    if(!empty($tableName)) $groupMenu .= "<li>{$link}</li>";
                }

                $treeMenu .= "<li class='closed' title='{$groupName}'><a>{$groupName}</a><ul>{$groupMenu}</ul>";
            }
        }

        $lastMenu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-table'>$treeMenu</ul>\n";
        return $lastMenu;
    }

    /**
     * Build third tree menu.
     *
     * @param  string $secondName
     * @param  array  $tables
     * @param  string $selectedTable
     * @access public
     * @return string
     */
    public function buildThirdMenu($secondName, $tables, $selectedTable)
    {
        $groupName = zget($this->lang->dataview->secondGroup, $secondName, '');
        $groupMenu = '';

        foreach($tables as $subTable => $table)
        {
            $active    = ($table == $selectedTable) ? 'active' : '';
            $tableName = zget($this->lang->dev->tableList, $subTable, '');
            $link      = html::a(helper::createLink('dataview', 'browse', "type=table&table=$table"), $tableName, '_self', "class='$active' id='module{$table}' title='{$tableName}'");

            if(!empty($tableName)) $groupMenu .= "<li>{$link}</li>";
        }

        $thirdMenu = "<li class='closed'><a><strong>{$groupName}</strong></a><ul>{$groupMenu}</ul>";

        return $thirdMenu;
    }

    /**
     * Process table.
     *
     * @param  array $tables
     * @access public
     * @return array
     */
    public function processTable($tables)
    {
        foreach($tables as $group => $tableList)
        {
            if(!isset($this->config->dataview->groupChild[$group])) continue;

            foreach($this->config->dataview->groupChild[$group] as $secondName => $thirdTables)
            {
                $secondGroup = array();
                $thirdTables = explode(',', $thirdTables);

                foreach($thirdTables as $tableName)
                {
                    if(!isset($tableList[$tableName])) continue;

                    $secondGroup[$tableName] = $tableList[$tableName];
                    unset($tables[$group][$tableName]);
                }
                if($secondGroup) $tables[$group] = array($secondName => $secondGroup) + $tables[$group];
            }
        }

        return $tables;
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
