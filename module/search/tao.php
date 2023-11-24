<?php 
declare(strict_types=1);
class searchTao extends searchModel
{
    protected function processBuildinFields(string $module, array $searchConfig): array
    {
        $flowModule = $module;
        if($module == 'projectStory' || $module == 'executionStory') $flowModule = 'story';
        if($module == 'projectBuild' || $module == 'executionBuild') $flowModule = 'build';
        if($module == 'projectBug') $flowModule = 'bug';

        $buildin = false;
        $this->app->loadLang('workflow');
        $this->app->loadConfig('workflow');
        if(!empty($this->config->workflow->buildin))
        {
            foreach($this->config->workflow->buildin->modules as $appModules)
            {
                if(isset($appModules->$flowModule))
                {
                    $buildin = true;
                    break;
                }
            }
        }

        if(!$buildin) return $searchConfig;

        $fields   = $this->loadModel('workflowfield')->getList($flowModule, 'searchOrder, `order`, id');
        $maxCount = $this->config->maxCount;
        $this->config->maxCount = 0;

        $fieldValues = array();
        $formName    = $module . 'Form';
        if($this->session->$formName)
        {
            foreach($this->session->$formName as $formField)
            {
                $field = zget($formField, 'field', '');
                $value = zget($formField, 'value', '');

                if(empty($field)) continue;
                if($value) $fieldValues[$formField->field][$value] = $value;
            }
        }

        foreach($fields as $field)
        {
            if($field->canSearch == 0 || $field->buildin) continue;

            if(in_array($field->control, $this->config->workflowfield->optionControls))
            {
                $field->options = $this->workflowfield->getFieldOptions($field, true, zget($fieldValues, $field->field, ''), '', $this->config->flowLimit);
            }

            $searchConfig['fields'][$field->field] = $field->name;
            $searchConfig['params'][$field->field] = $this->loadModel('flow', 'sys')->processSearchParams($field->control, $field->options);
        }
        $this->config->maxCount = $maxCount;

        return $searchConfig;
    }

    /**
     * 处理查询表单的相关数据。
     * Process query form datas.
     *
     * @param  object    $fieldParams
     * @param  string    $field
     * @param  string    $andOrName
     * @param  string    $operatorName
     * @param  string    $valueName
     * @access protected
     * @return array
     */
    protected function processQueryFormDatas(object $fieldParams, string $field, string $andOrName, string $operatorName, string $valueName): array
    {
        /* 设置分组之间的逻辑关系。*/
        /* Set and or. */
        $andOr = strtoupper($this->post->$andOrName);
        if($andOr != 'AND' && $andOr != 'OR') $andOr = 'AND';

        /* 设置操作符。*/
        /* Set operator. */
        $operator = $this->post->$operatorName;
        if(!isset($this->lang->search->operators[$operator])) $operator = '=';

        /* 设置字段的值。*/
        /* Skip empty values. */
        if($this->post->$valueName == 'ZERO') $this->post->$valueName = 0;   // ZERO is special, stands to 0.
        if(isset($fieldParams->$field) && $fieldParams->$field->control == 'select' && $this->post->$valueName === 'null') $this->post->$valueName = '';   // Null is special, stands to empty if control is select. Fix bug #3279.
        $value = addcslashes(trim($this->post->$valueName), '%');

        return array($andOr, $operator, $value);
    }

    /**
     * 根据字段，操作符和值设置查询条件。
     * Set condition by field, operator and value.
     *
     * @param  string    $field
     * @param  string    $operator
     * @param  string    $value
     * @access protected
     * @return string
     */
    protected function setCondition(string $field, string $operator, string $value): string
    {
        $condition = '';
        if($operator == 'include')
        {
            if($field == 'module')
            {
                $allModules = $this->loadModel('tree')->getAllChildId($value);
                if($allModules) $condition = helper::dbIN($allModules);
            }
            else
            {
                $condition = ' LIKE ' . $this->dbh->quote("%$value%");
            }
        }
        elseif($operator == "notinclude")
        {
            if($field == 'module')
            {
                $allModules = $this->loadModel('tree')->getAllChildId($value);
                if($allModules)
                {
                    if(count($allModules) > 1) $condition = " NOT " . helper::dbIN($allModules);
                    else $condition = " !" . helper::dbIN($allModules);
                }
            }
            else
            {
                $condition = ' NOT LIKE ' . $this->dbh->quote("%$value%");
            }
        }
        elseif($operator == 'belong')
        {
            if($field == 'module')
            {
                $allModules = $this->loadModel('tree')->getAllChildId($value);
                if($allModules) $condition = helper::dbIN($allModules);
            }
            elseif($field == 'dept')
            {
                $allDepts = $this->loadModel('dept')->getAllChildId($value);
                $condition = helper::dbIN($allDepts);
            }
            elseif($field == 'scene')
            {
                $allScenes = $value === '0' ? array() : ($value === '' ? array(0) : $this->loadModel('testcase')->getAllChildId($value));
                if(count($allScenes)) $condition = helper::dbIN($allScenes);
            }
            else
            {
                $condition = ' = ' . $this->dbh->quote($value) . ' ';
            }
        }
        else
        {
            if($operator == 'between' and !isset($this->config->search->dynamic[$value])) $operator = '=';
            $condition = $operator . ' ' . $this->dbh->quote($value) . ' ';

            if($operator == '=' and $field == 'id' and preg_match('/^[0-9]+(,[0-9]*)+$/', $value) and !preg_match('/[\x7f-\xff]+/', $value))
            {
                $values = array_filter(explode(',', trim($this->dbh->quote($value), "'")));
                foreach($values as $value) $value = "'" . $value . "'";

                $value     = implode(',', $values);
                $operator  = 'IN';
                $condition = $operator . ' (' . $value . ') ';
            }
        }
        return $condition;
    }

    /**
     * 设置 where 查询条件。
     * Set where condition.
     *
     * @param  string    $where
     * @param  string    $field
     * @param  string    $operator
     * @param  string    $value
     * @param  string    $andOr
     * @access protected
     * @return string
     */
    protected function setWhere(string $where, string $field, string $operator, string $value, string $andOr): string
    {
        $condition = $this->setCondition($field, $operator, $value);
        if($operator == '=' && preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
        {
            $condition  = '`' . $field . "` >= '$value' AND `" . $field . "` <= '$value 23:59:59'";
            $where     .= " $andOr ($condition)";
        }
        elseif($operator == '!=' && preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
        {
            $condition  = '`' . $field . "` < '$value' OR `" . $field . "` > '$value 23:59:59'";
            $where     .= " $andOr ($condition)";
        }
        elseif($operator == '<=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
        {
            $where .= " $andOr " . '`' . $field . "` <= '$value 23:59:59'";
        }
        elseif($operator == '>' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
        {
            $where .= " $andOr " . '`' . $field . "` > '$value 23:59:59'";
        }
        elseif($condition)
        {
            $where .= " $andOr " . '`' . $field . '` ' . $condition;
        }
        return $where;
    }

    /**
     * 如果搜索框的选项是users，products, executions, 获取相对应的列表。
     * Get user, product and execution value of the param.
     *
     * @param  array $fields
     * @access public
     * @return array
     */
    public function getParamValues(array $fields, array $params): array
    {
        $hasProduct   = false;
        $hasExecution = false;
        $hasUser      = false;
        foreach($fields as $fieldName)
        {
            if(empty($params[$fieldName])) continue;
            if($params[$fieldName]['values'] == 'products')   $hasProduct   = true;
            if($params[$fieldName]['values'] == 'users')      $hasUser      = true;
            if($params[$fieldName]['values'] == 'executions') $hasExecution = true;
        }

        /* 将用户的值追加到获取到的用户列表。*/
        $appendUsers     = array();
        $module          = $_SESSION['searchParams']['module'];
        $formSessionName = $module . 'Form';
        if(isset($_SESSION[$formSessionName]))
        {
            for($i = 1; $i <= $this->config->search->groupItems; $i ++)
            {
                if(!isset($_SESSION[$formSessionName][$i - 1])) continue;

                $fieldName = $_SESSION[$formSessionName][$i - 1]['field'];
                if(isset($params[$fieldName]) and $params[$fieldName]['values'] == 'users')
                {
                    if($_SESSION[$formSessionName][$i - 1]['value']) $appendUsers[] = $_SESSION[$formSessionName][$i - 1]['value'];
                }
            }
        }

        $users = $products = $executions = array();
        if($hasUser)
        {
            $users = $this->loadModel('user')->getPairs('realname|noclosed', $appendUsers, $this->config->maxCount);
            $users['$@me'] = $this->lang->search->me;
        }

        if($hasProduct)   $products   = $this->loadModel('product')->getPairs('', $this->session->project);
        if($hasExecution) $executions = $this->loadModel('execution')->getPairs($this->session->project);

        return array($users, $products, $executions);
    }
}
