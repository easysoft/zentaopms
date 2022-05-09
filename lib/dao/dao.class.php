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

    public function data($data, $skipFields = '')
    {
        $skipFields .= ',uid';
        return parent::data($data, $skipFields);
    }

    /**
     * check workflow field rule. 
     * 
     * @access public
     * @return object the dao object self.
     */
    public function checkflow()
    {
        global $app, $config;

        if(!isset($config->bizVersion)) return $this;

        $module = $app->getmodulename();
        $method = $app->getmethodname();

        $flowaction = $this->dbh->query("select * from " . TABLE_WORKFLOWACTION . " where `module` = '{$module}' and `action` = '{$method}' and `buildin` = '1' and `extensiontype` = 'extend'")->fetch(PDO::FETCH_OBJ);
        if(!$flowaction) return $this;

        $flowfields = $this->dbh->query("select t2.name,t2.rules,t2.control,t2.field,t1.layoutrules from " . TABLE_WORKFLOWLAYOUT . " as t1 left join " . TABLE_WORKFLOWFIELD . " as t2 on t1.module = t2.module and t1.field = t2.field where t1.module = '{$module}' and t1.action = '{$method}' and t1.readonly = '0'")->fetchall();
        if(!$flowfields) return $this;

        $rules    = array();
        $rawrules = $this->dbh->query("select * from " . TABLE_WORKFLOWRULE)->fetchall();
        foreach($rawrules as $rule) $rules[$rule->id] = $rule;

        foreach($flowfields as $key => $field)
        {
            if(!$field)
            {
                unset($flowfields[$key]);
                continue;
            }

            $ruleids = explode(',', trim($field->rules, ',') . ',' . trim($field->layoutrules, ','));
            $ruleids = array_unique($ruleids);

            $fieldrules = array();
            foreach($ruleids as $ruleid)
            {
                if(!$ruleid || !isset($rules[$ruleid])) continue;

                $fieldrules[] = $rules[$ruleid];
            }

            $field->ruledata = $fieldrules;
            $field->rules    = join(',', $ruleids);
        }

        return $this->checkextend($flowfields);
    }

    /**
     * 检查工作流扩展字段 
     * check workflow extend field 
     * 
     * @param  array $fields 
     * @access public
     * @return object the dao object self
     */
    public function checkextend($fields)
    {
        global $lang;

        if(!$fields) return $this;
        foreach($fields as $field)
        {
            /* if the field don't have rule, don't check it. */
            if(empty($field->rules)) continue;

            if($field->control == 'file')
            {
                foreach($field->ruledata as $rule)
                {
                    if(empty($rule)) continue;
                    if($rule->type != 'system' || $rule->rule != 'notempty') continue;

                    $files = !empty($_files[$field->field]) ? $_files[$field->field] : '';
                    if(empty($files)) dao::$errors[$field->field][] = sprintf($lang->error->notempty, $field->name);
                    break;
                }
                continue;
            }

            if(!isset($this->sqlobj->data->{$field->field})) $this->sqlobj->data->{$field->field} = false;

            /* check rules of fields. */
            foreach($field->ruledata as $rule)
            {
                if(empty($rule)) continue;

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
     * Get param real value
     *
     * @param  int    $param
     * @access public
     * @return string
     */
    public function getParamRealValue($param)
    {
        if(empty($this->deptManager))
        {
            $dept    = zget($this->app->user, 'dept', '');
            $manager = $this->dbh->query("SELECT moderators FROM " . TABLE_CATEGORY . " WHERE `type` = 'dept' AND `id` = '{$dept}'")->fetch(PDO::FETCH_OBJ);
            $this->deptManager = $manager ? trim($manager->moderators, ',') : '';
        }

        switch((string)$param)
        {
            case 'today'       : return date('Y-m-d');
            case 'now'         :
            case 'currentTime' : return date('Y-m-d H:i:s');
            case 'actor'       :
            case 'currentUser' : return $this->app->user->account;
            case 'currentDept' : return $this->app->user->dept ? $this->app->user->dept : $param;
            case 'deptManager' : return $this->deptManager ? $this->deptManager : $param;
            default            : return $param;
        }
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
        if(!preg_match('/^[a-zA-Z0-9_`\.,\s]+$/', $groupBy))
        {
            $groupBy = htmlspecialchars($groupBy);
            die("Group is bad query, The group is $groupBy");
        }
        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }
}
