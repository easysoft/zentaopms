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

helper::import(dirname(__FILE__, 2) . '/base/dao/dao.class.php');
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
        if(!is_object($data)) $data = (object)$data;

        if(get_class($data) == 'form') $data = $data->data;

        if(isset($this->config->bizVersion))
        {
            $this->app->loadLang('workflow');
            $this->app->loadConfig('workflow');

            /* Check current module is buildin workflow. */
            if(isset($this->config->workflow->buildin->modules))
            {
                $currentModule = $this->app->fetchModule ?: $this->app->rawModule;
                foreach($this->config->workflow->buildin->modules as $appModules)
                {
                    if(!empty($appModules->$currentModule))
                    {
                        $currentMainTable = zget($appModules->$currentModule, 'table', '');
                        break;
                    }
                }

                if(isset($currentMainTable))
                {
                    if(trim($currentMainTable, '`') == $this->table)
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
        if(!isset($this->config->bizVersion)) return $this;

        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();

        $action = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '{$module}' AND `action` = '{$method}' AND `buildin` = '1' AND `vision` = '{$this->config->vision}' AND `extensionType` = 'extend'")->fetch(PDO::FETCH_OBJ);
        if(!$action) return $data;

        $fields = $this->dbh->query("SELECT t2.name,t2.rules,t2.control,t2.field,t2.type,t2.options,t1.layoutRules FROM " . TABLE_WORKFLOWLAYOUT . " AS t1 LEFT JOIN " . TABLE_WORKFLOWFIELD . " AS t2 ON t1.module = t2.module WHERE t1.field = t2.field AND t1.module = '{$module}' AND t1.action = '{$method}' AND `vision` = '{$this->config->vision}' AND t1.readonly = '0'")->fetchAll();
        if(!$fields) return $data;

        $this->app->loadLang('flow');
        $this->app->loadLang('workflowfield');
        $this->app->loadConfig('flow');
        $this->app->loadConfig('workflowfield');
        $this->app->loadConfig('workflowlinkage');

        $linkages     = !empty($action->linkages) ? json_decode($action->linkages) : array();
        $hiddenFields = array();
        foreach($linkages as $key => $linkage)
        {
            $sources = zget($linkage, 'sources', array());
            $targets = zget($linkage, 'targets', array());
            if(!$linkage or !$sources or !$targets) continue;

            $source = reset($linkage->sources);
            if(isset($data->{$source->field}))
            {
                $operator = zget($this->config->workflowlinkage->operatorList, $source->operator);
                if(helper::checkCondition($data->{$source->field}, $source->value, $operator))
                {
                    foreach($targets as $target)
                    {
                        if($target->status == 'show')
                        {
                            unset($hiddenFields[$target->field]);
                        }
                        else
                        {
                            $hiddenFields[$target->field] = $target->field;
                        }
                    }
                }
            }
        }

        foreach($fields as $field)
        {
            if(isset($hiddenFields[$field->field]))
            {
                unset($data->{$field->field});
                continue;
            }

            if(isset($data->{$field->field}))
            {
                if($field->options && is_string($field->options) && str_contains(',user,dept,', ",$field->options,"))
                {
                    if(!is_array($data->{$field->field})) $data->{$field->field} = explode(',', (string) $data->{$field->field});
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
                if(str_contains(',radio,checkbox,multi-select,', ",$field->control,") && $this->method != 'update') $data->{$field->field} = '';
            }
        }

        foreach($data as $field => $value)
        {
            if(in_array($value, $this->config->flow->variables)) $data->$field = $this->getParamRealValue($value);
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
        if(empty($this->deptManager))
        {
            $dept    = zget($this->app->user, 'dept', '');
            $manager = $this->dbh->query("SELECT manager FROM " . TABLE_DEPT . " WHERE `id` = '{$dept}'")->fetch(PDO::FETCH_OBJ);
            $this->deptManager = $manager ? trim((string) $manager->manager, ',') : '';
        }

        return match ((string)$param) {
            'today' => date('Y-m-d'),
            'now', 'currentTime' => date('Y-m-d H:i:s'),
            'actor', 'currentUser' => $this->app->user->account,
            'currentDept' => $this->app->user->dept ?: $param,
            'deptManager' => $this->deptManager ?: $param,
            default => $param,
        };
    }

    /**
     * Check workFlow field rule.
     *
     * @param  bool   $skip true|false
     * @access public
     * @return object the dao object self.
     */
    public function checkFlow($skip = false)
    {
        if($skip) return $this;
        if(!isset($this->config->bizVersion)) return $this;

        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();
        if($module == 'story' && $this->app->rawModule == 'requirement') $module = 'requirement';
        if($module == 'story' && $this->app->rawModule == 'epic')        $module = 'epic';
        if($module == 'project' && $method == 'createtemplate')          $method = 'create';

        $linkProductModules = array('productplan', 'release', 'story', 'requirement', 'epic', 'bug', 'testcase', 'testtask', 'feedback', 'ticket');
        $linkProjectModules = array('execution', 'build', 'task');

        $groupID = 0;
        if(in_array($module, array('project', 'product')))
        {
            $groupID = empty($_POST['workflowGroup']) ? 0 : $_POST['workflowGroup'];
        }
        elseif(in_array($module, $linkProductModules))
        {
            $productVar = in_array($module, array('feedback', 'ticket')) ? "{$module}Product" : 'product';
            if(!empty($_SESSION[$productVar]) && is_numeric($_SESSION[$productVar]))
            {
                $productID = $_SESSION[$productVar];
                $result    = $this->dbh->query('SELECT `workflowGroup`, `shadow` FROM ' . TABLE_PRODUCT . " WHERE `id` = '" . $productID . "'")->fetch(PDO::FETCH_OBJ);
                $groupID   = !empty($result->workflowGroup) ? $result->workflowGroup : 0;
                if(!empty($result->shadow))
                {
                    $result  = $this->dbh->query("SELECT t2.`workflowGroup` FROM " . TABLE_PROJECTPRODUCT . " AS t1 LEFT JOIN " . TABLE_PROJECT . " AS t2 ON t1.project = t2.id WHERE t1.product = '{$productID}'")->fetch(PDO::FETCH_OBJ);
                    $groupID = !empty($result->workflowGroup) ? $result->workflowGroup : 0;
                }
            }
        }
        elseif(!empty($_SESSION['project']) && in_array($module, $linkProjectModules))
        {
            $result  = $this->dbh->query('SELECT `workflowGroup` FROM ' . TABLE_PROJECT . " WHERE `id` = '" . $_SESSION['project'] . "'")->fetch(PDO::FETCH_OBJ);
            $groupID = !empty($result->workflowGroup) ? $result->workflowGroup : 0;
        }

        $flowAction = $this->dbh->query("SELECT * FROM " . TABLE_WORKFLOWACTION . " WHERE `module` = '{$module}' AND `action` = '{$method}' AND `buildin` = '1' AND `extensionType` = 'extend' AND `vision` = '{$this->config->vision}' AND `group` = '{$groupID}'")->fetch(PDO::FETCH_OBJ);
        if(!$flowAction) return $this;

        $flowFields = $this->dbh->query("SELECT t2.name,t2.rules,t2.control,t2.field,t1.layoutRules FROM " . TABLE_WORKFLOWLAYOUT . " AS t1 LEFT JOIN " . TABLE_WORKFLOWFIELD . " AS t2 ON t1.module = t2.module AND t1.field = t2.field WHERE t1.module = '{$module}' AND t1.action = '{$method}' AND t1.readonly = '0' AND t1.vision = '{$this->config->vision}' AND t1.`group` = '{$groupID}'")->fetchAll();
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

            $ruleIDs = explode(',', trim((string) $field->rules, ',') . ',' . trim((string) $field->layoutRules, ','));
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
                    if(empty($files['size'][0])) dao::$errors[$field->field][] = sprintf($lang->error->notempty, $field->name);
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

    /**
     * 将记录进行分页，自动设置limit语句。
     * Page the records, set the limit part auto.
     *
     * @param  object $pager
     * @param  string $distinctField
     * @access public
     * @return static|sql the dao object self.
     */
    public function page($pager, $distinctField = '')
    {
        if(!is_object($pager)) return $this;

        /*
         * 重新计算分页数据，并判断是否需要返回上一页。
         * Calculate pagination to determine whether to return to the previous page.
         */
        $originalPageID = $pager->pageID;
        $recTotal       = $this->count($distinctField);

        $pager->setRecTotal($recTotal);
        $pager->setPageTotal();
        if($originalPageID > ceil(($pager->recTotal - $pager->offset) / $pager->recPerPage)) $pager->setPageID($pager->pageTotal);

        $this->sqlobj->limit($pager->limit());
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

        //The dm database cannot use alias for group by
        /*
        if(!preg_match('/^[a-zA-Z0-9_`\.,\s\"]+$/', $groupBy))
        {
            $groupBy = htmlspecialchars($groupBy);
            die("Group is bad query, The group is $groupBy");
        }
         */

        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }
}
