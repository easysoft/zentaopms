<?php
/**
 * ZenTaoPHP的dao和sql类。
 * The dao and sql class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/dao/dao.class.php');
/**
 * DAO类。
 * DAO, data access object.
 *
 * @package framework
 */
class dao extends baseDAO
{
    public function exec($sql = '')
    {
        if(isset($_SESSION['tutorialMode']) and $_SESSION['tutorialMode']) return true;
        return parent::exec($sql);
    }

    /**
     * 设置需要更新或插入的数据。
     * Set the data to update or insert.
     *
     * @param  object $data  the data object or array
     * @access public
     * @return object the dao object self.
     */
    public function data($data, $skipFields = '')
    {
        global $app, $config;

        if(!is_object($data)) $data = (object)$data;

        if(isset($config->bizVersion))
        {
            $app->loadLang('workflow');
            $app->loadConfig('workflow');

            /* Check current module is buildin workflow. */
            if(isset($config->workflow->buildin->modules))
            {
                $currentModule = $app->fetchModule ? $app->fetchModule : $app->rawModule;
                foreach($config->workflow->buildin->modules as $appModules)
                {
                    if(!empty($appModules->$currentModule))
                    {
                        $currentMainTable = zget($appModules->$currentModule, 'table', '');
                        break;
                    }
                }

                if(isset($currentMainTable))
                {
                    if($currentMainTable == $this->table)
                    {
                        $data = $this->processData($data);
                    }
                    else
                    {
                        $workflowFields = array();
                        $stmt = $this->dbh->query("SELECT `field`,`type` FROM " . TABLE_WORKFLOWFIELD . " WHERE `module` = '{$currentModule}' AND `buildin` = '0'");
                        while($row = $stmt->fetch())
                        {
                            $workflowFields[$row->field] = $row->type;
                        }

                        $fields = $this->getFieldsType();
                        foreach($data as $field => $value)
                        {
                            if(!isset($fields[$field]) && isset($workflowFields[$field])) unset($data->$field);
                        }
                    }
                }
            }
        }

        $skipFields .= ',uid';
        return parent::data($data, $skipFields);
    }

    /**
     * Process workflow data
     *
     * @param  object  $data
     * @access public
     * @return object
     */
    public function processData($data)
    {
        global $app, $config;

        if(!isset($config->bizVersion)) return $this;

        $module = $app->getModuleName();
        $method = $app->getMethodName();

        $action = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '{$module}' AND `action` = '{$method}' AND `buildin` = '1' AND `vision` = '{$config->vision}' AND `extensionType` = 'extend'")->fetch(PDO::FETCH_OBJ);
        if(!$action) return $data;

        $fields = $this->dbh->query("SELECT t2.name,t2.rules,t2.control,t2.field,t2.type,t2.options,t1.layoutRules FROM " . TABLE_WORKFLOWLAYOUT . " AS t1 LEFT JOIN " . TABLE_WORKFLOWFIELD . " AS t2 ON t1.module = t2.module WHERE t1.field = t2.field AND t1.module = '{$module}' AND t1.action = '{$method}' AND `vision` = '{$config->vision}' AND t1.readonly = '0'")->fetchAll();
        if(!$fields) return $data;

        $app->loadLang('flow');
        $app->loadLang('workflowfield');
        $app->loadConfig('flow');
        $app->loadConfig('workflowfield');

        foreach($fields as $field)
        {
            if(isset($data->{$field->field}))
            {
                if($field->options && is_string($field->options) && strpos(',user,dept,', ",$field->options,") !== false)
                {
                    if(!is_array($data->{$field->field})) $data->{$field->field} = explode(',', $data->{$field->field});
                    foreach($data->{$field->field} as $key => $value)
                    {
                        $data->{$field->field}[$key] = $this->getParamRealValue($value);
                    }
                }

                /* If data value is array, implode by comma. */
                if(is_array($data->{$field->field}))
                {
                    $dataValue = array_values(array_unique(array_filter($data->{$field->field})));
                    asort($dataValue);
                    $data->{$field->field} = implode(',', $dataValue);
                }
            }
            else
            {
                if(strpos(',radio,checkbox,multi-select,', ",$field->control,") !== false) $data->{$field->field} = '';
            }
        }

        foreach($data as $field => $value)
        {
            if(in_array($value, $config->flow->variables)) $data->$field = $this->getParamRealValue($value);
        }

        return $data;
    }

    /**
     * Get param real value
     *
     * @param  string $param
     * @access public
     * @return string
     */
    public function getParamRealValue($param)
    {
        global $app;

        if(empty($this->deptManager))
        {
            $dept    = zget($app->user, 'dept', '');
            $manager = $this->dbh->query("SELECT manager FROM " . TABLE_DEPT . " WHERE `id` = '{$dept}'")->fetch(PDO::FETCH_OBJ);
            $this->deptManager = $manager ? trim($manager->manager, ',') : '';
        }

        switch((string)$param)
        {
            case 'today'       : return date('Y-m-d');
            case 'now'         :
            case 'currentTime' : return date('Y-m-d H:i:s');
            case 'actor'       :
            case 'currentUser' : return $app->user->account;
            case 'currentDept' : return $app->user->dept ? $app->user->dept : $param;
            case 'deptManager' : return $this->deptManager ? $this->deptManager : $param;
            default            : return $param;
        }
    }

    /**
     * Check workFlow field rule.
     *
     * @access public
     * @return object the dao object self.
     */
    public function checkFlow()
    {
        global $app, $config, $lang;

        if(!isset($config->bizVersion)) return $this;

        $module = $app->getModuleName();
        $method = $app->getMethodName();

        $flowAction = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '{$module}' AND `action` = '{$method}' AND `buildin` = '1' AND `extensionType` = 'extend' AND `vision` = '{$config->vision}'")->fetch(PDO::FETCH_OBJ);
        if(!$flowAction) return $this;

        $flowFields = $this->dbh->query("SELECT t2.name,t2.rules,t2.control,t2.field,t1.layoutRules FROM " . TABLE_WORKFLOWLAYOUT . " AS t1 LEFT JOIN " . TABLE_WORKFLOWFIELD . " AS t2 ON t1.module = t2.module AND t1.field = t2.field WHERE t1.module = '{$module}' AND t1.action = '{$method}' AND t1.readonly = '0' AND t1.vision = '{$config->vision}'")->fetchAll();
        if(!$flowFields) return $this;

        $rules    = array();
        $rawRules = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWRULE)->fetchAll();
        foreach($rawRules as $rule) $rules[$rule->id] = $rule;

        foreach($flowFields as $key => $field)
        {
            if(!$field)
            {
                unset($flowFields[$key]);
                continue;
            }

            $ruleIDs = explode(',', trim($field->rules, ',') . ',' . trim($field->layoutRules, ','));
            $ruleIDs = array_unique($ruleIDs);

            $fieldRules = array();
            foreach($ruleIDs as $ruleID)
            {
                if(!$ruleID || !isset($rules[$ruleID])) continue;

                $fieldRules[] = $rules[$ruleID];
            }

            $field->ruleData = $fieldRules;
            $field->rules    = join(',', $ruleIDs);
        }

        return $this->checkExtend($flowFields);
    }

    /**
     * 检查工作流扩展字段
     * check workflow extend field
     *
     * @param  array $fields
     * @access public
     * @return object the dao object self
     */
    public function checkExtend($fields)
    {
        global $lang;

        if(!$fields) return $this;
        foreach($fields as $field)
        {
            /* If the field don't have rule, don't check it. */
            if(empty($field->rules)) continue;

            if($field->control == 'file')
            {
                foreach($field->ruleData as $rule)
                {
                    if(empty($rule)) continue;
                    if($rule->type != 'system' || $rule->rule != 'notempty') continue;

                    $files = !empty($_FILES[$field->field]) ? $_FILES[$field->field] : '';
                    if(empty($files)) dao::$errors[$field->field][] = sprintf($lang->error->notempty, $field->name);
                    break;
                }
                continue;
            }

            /* Check rules of fields. */
            foreach($field->ruleData as $rule)
            {
                if(empty($rule)) continue;
                if(!isset($this->sqlobj->data->{$field->field})) continue;

                if($rule->type == 'system')
                {
                    if($rule->rule == 'unique')
                    {
                        if(empty($this->sqlobj->data->{$field->field})) continue;
                        $condition = isset($this->sqlobj->data->id) ? '`id` != ' . $this->sqlobj->data->id : '';
                        $this->check($field->field, $rule->rule, $condition);
                    }
                    else
                    {
                        if(is_numeric($this->sqlobj->data->{$field->field}) && $this->sqlobj->data->{$field->field} == 0 && $rule->rule == 'notempty' && !in_array($field->control, array('select', 'multi-select'))) continue;
                        $this->check($field->field, $rule->rule);
                    }
                }
                elseif($rule->type == 'regex')
                {
                    $this->check($field->field, 'reg', $rule->rule);
                }
            }
        }

        return $this;
    }
}

/**
 * SQL类。
 * The SQL class.
 *
 * @package framework
 */
class sql extends baseSQL
{
    /**
     * 创建GROUP BY部分。
     * Create the groupby part.
     *
     * @param  string $groupBy
     * @access public
     * @return object the sql object.
     */
    public function groupBy($groupBy)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if(!preg_match('/^[a-zA-Z0-9_`\.,\s\"]+$/', $groupBy))
        {
            $groupBy = htmlspecialchars($groupBy);
            die("Group is bad query, The group is $groupBy");
        }
        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }
}
