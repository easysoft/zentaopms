<?php
/**
 * The model file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: model.php 5082 2013-07-10 01:14:45Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class searchModel extends model
{

    /**
     * Set search params to session.
     *
     * @param  array    $searchConfig
     * @access public
     * @return void
     */
    public function setSearchParams($searchConfig)
    {
        $module = $searchConfig['module'];

        if($this->config->edition != 'open')
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
                foreach($this->config->workflow->buildin->modules as $appName => $appModules)
                {
                    if(isset($appModules->$flowModule))
                    {
                        $buildin = true;
                        break;
                    }
                }
            }

            if($buildin)
            {
                $fields   = $this->loadModel('workflowfield')->getList($flowModule, 'searchOrder, `order`, id');
                $maxCount = $this->config->maxCount;
                $this->config->maxCount = 0;

                $fieldValues = array();
                $formName    = $module . 'Form';
                if($this->session->$formName)
                {
                    foreach($this->session->$formName as $formKey => $formField)
                    {
                        if(strpos($formKey, 'field') !== false)
                        {
                            $fieldNO      = substr($formKey, 5);
                            $fieldNO      = "value" . $fieldNO;
                            $formNameList = $this->session->$formName;
                            $fieldValue   = zget($formNameList, $fieldNO, '');

                            if($fieldValue) $fieldValues[$formField][$fieldValue] = $fieldValue;
                        }
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
            }
        }

        $searchParams['module']       = $searchConfig['module'];
        $searchParams['searchFields'] = json_encode($searchConfig['fields']);
        $searchParams['fieldParams']  = json_encode($searchConfig['params']);
        $searchParams['actionURL']    = $searchConfig['actionURL'];
        $searchParams['style']        = zget($searchConfig, 'style', 'full');
        $searchParams['onMenuBar']    = zget($searchConfig, 'onMenuBar', 'no');
        $searchParams['queryID']      = isset($searchConfig['queryID']) ? $searchConfig['queryID'] : 0;

        $this->session->set($module . 'searchParams', $searchParams);
    }

    /**
     * Build the query to execute.
     *
     * @access public
     * @return void
     */
    public function buildQuery()
    {
        /* Init vars. */
        $where        = '';
        $groupItems   = $this->config->search->groupItems;
        $groupAndOr   = strtoupper($this->post->groupAndOr);
        $module       = $this->session->searchParams['module'];
        $searchParams = $module . 'searchParams';
        $fieldParams  = json_decode($_SESSION[$searchParams]['fieldParams']);
        $scoreNum     = 0;

        if($groupAndOr != 'AND' and $groupAndOr != 'OR') $groupAndOr = 'AND';

        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* The and or between two groups. */
            if($i == 1) $where .= '(( 1  ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* Fix bug #2704. */
            $field = $this->post->$fieldName;
            if(isset($fieldParams->$field) and $fieldParams->$field->control == 'input' and $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';
            if($field == 'id' and $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';

            /* Skip empty values. */
            if($this->post->$valueName == false) continue;
            if($this->post->$valueName == 'ZERO') $this->post->$valueName = 0;   // ZERO is special, stands to 0.
            if(isset($fieldParams->$field) and $fieldParams->$field->control == 'select' and $this->post->$valueName === 'null') $this->post->$valueName = '';   // Null is special, stands to empty if control is select. Fix bug #3279.

            $scoreNum += 1;

            /* Set and or. */
            $andOr = strtoupper($this->post->$andOrName);
            if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';

            /* Set operator. */
            $value    = addcslashes(trim($this->post->$valueName), '%');
            $operator = $this->post->$operatorName;
            if(!isset($this->lang->search->operators[$operator])) $operator = '=';

            /* Set condition. */
            $condition = '';
            if($operator == "include")
            {
                if($this->post->$fieldName == 'module')
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
                if($this->post->$fieldName == 'module')
                {
                    $allModules = $this->loadModel('tree')->getAllChildId($value);
                    if($allModules) $condition = " NOT " . helper::dbIN($allModules);
                }
                else
                {
                    $condition = ' NOT LIKE ' . $this->dbh->quote("%$value%");
                }
            }
            elseif($operator == 'belong')
            {
                if($this->post->$fieldName == 'module')
                {
                    $allModules = $this->loadModel('tree')->getAllChildId($value);
                    if($allModules) $condition = helper::dbIN($allModules);
                }
                elseif($this->post->$fieldName == 'dept')
                {
                    $allDepts = $this->loadModel('dept')->getAllChildId($value);
                    $condition = helper::dbIN($allDepts);
                }
                elseif($this->post->$fieldName == 'scene')
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

                if($operator == '=' and $this->post->$fieldName == 'id' and preg_match('/^[0-9]+(,[0-9]*)+$/', $value) and !preg_match('/[\x7f-\xff]+/', $value))
                {
                    $values = array_filter(explode(',', trim($this->dbh->quote($value), "'")));
                    foreach($values as $value) $value = "'" . $value . "'";

                    $value     = implode(',', $values);
                    $operator  = 'IN';
                    $condition = $operator . ' (' . $value . ') ';
                }
            }

            /* Processing query criteria. */
            if($operator == '=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $condition  = '`' . $this->post->$fieldName . "` >= '$value' AND `" . $this->post->$fieldName . "` <= '$value 23:59:59'";
                $where     .= " $andOr ($condition)";
            }
            elseif($operator == '!=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $condition  = '`' . $this->post->$fieldName . "` < '$value' OR `" . $this->post->$fieldName . "` > '$value 23:59:59'";
                $where     .= " $andOr ($condition)";
            }
            elseif($operator == '<=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $where .= " $andOr " . '`' . $this->post->$fieldName . "` <= '$value 23:59:59'";
            }
            elseif($operator == '>' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $where .= " $andOr " . '`' . $this->post->$fieldName . "` > '$value 23:59:59'";
            }
            elseif($condition)
            {
                $where .= " $andOr " . '`' . $this->post->$fieldName . '` ' . $condition;
            }
        }

        $where .=" ))";
        $where  = $this->replaceDynamic($where);

        /* Save to session. */
        $querySessionName = $this->post->module . 'Query';
        $formSessionName  = $this->post->module . 'Form';
        $this->session->set($querySessionName, $where);
        $this->session->set($formSessionName,  $_POST);
        if($scoreNum > 2 && !dao::isError()) $this->loadModel('score')->create('search', 'saveQueryAdvanced');
    }

    /**
     * Build the query to execute.
     *
     * @access public
     * @return void
     */
    public function buildZinQuery()
    {
        /* Init vars. */
        $where        = '';
        $groupItems   = $this->config->search->groupItems;
        $groupAndOr   = strtoupper($this->post->groupAndOr);
        $module       = $this->session->searchParams['module'];
        $searchParams = $module . 'searchParams';
        $searchFields = json_decode($_SESSION[$searchParams]['searchFields']);
        $fieldParams  = json_decode($_SESSION[$searchParams]['fieldParams']);
        $scoreNum     = 0;

        if($groupAndOr != 'AND' and $groupAndOr != 'OR') $groupAndOr = 'AND';

        $queryForm    = $this->initZinSession($module, $searchFields, $fieldParams);
        $queryForm[6] = array('groupAndOr' => strtolower($groupAndOr));

        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* The and or between two groups. */
            $formIndex = $i - 1;
            if($i == 1) $where .= '(( 1  ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* Fix bug #2704. */
            $field = $this->post->$fieldName;
            if(empty($field)) continue;
            if(isset($fieldParams->$field) and $fieldParams->$field->control == 'input' and $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';
            if($field == 'id' and $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';
            $queryForm[$formIndex]['field'] = $field;

            /* Set and or. */
            $andOr = strtoupper($this->post->$andOrName);
            if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';
            $queryForm[$formIndex]['andOr'] = strtolower($andOr);

            /* Set operator. */
            $operator = $this->post->$operatorName;
            if(!isset($this->lang->search->operators[$operator])) $operator = '=';
            $queryForm[$formIndex]['operator'] = $operator;

            /* Skip empty values. */
            if($this->post->$valueName == false) continue;
            if($this->post->$valueName == 'ZERO') $this->post->$valueName = 0;   // ZERO is special, stands to 0.
            if(isset($fieldParams->$field) and $fieldParams->$field->control == 'select' and $this->post->$valueName === 'null') $this->post->$valueName = '';   // Null is special, stands to empty if control is select. Fix bug #3279.
            $value = addcslashes(trim($this->post->$valueName), '%');
            $queryForm[$formIndex]['value'] = $value;

            $scoreNum += 1;


            /* Set condition. */
            $condition = '';
            if($operator == "include")
            {
                if($this->post->$fieldName == 'module')
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
                if($this->post->$fieldName == 'module')
                {
                    $allModules = $this->loadModel('tree')->getAllChildId($value);
                    if($allModules) $condition = " NOT " . helper::dbIN($allModules);
                }
                else
                {
                    $condition = ' NOT LIKE ' . $this->dbh->quote("%$value%");
                }
            }
            elseif($operator == 'belong')
            {
                if($this->post->$fieldName == 'module')
                {
                    $allModules = $this->loadModel('tree')->getAllChildId($value);
                    if($allModules) $condition = helper::dbIN($allModules);
                }
                elseif($this->post->$fieldName == 'dept')
                {
                    $allDepts = $this->loadModel('dept')->getAllChildId($value);
                    $condition = helper::dbIN($allDepts);
                }
                elseif($this->post->$fieldName == 'scene')
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

                if($operator == '=' and $this->post->$fieldName == 'id' and preg_match('/^[0-9]+(,[0-9]*)+$/', $value) and !preg_match('/[\x7f-\xff]+/', $value))
                {
                    $values = array_filter(explode(',', trim($this->dbh->quote($value), "'")));
                    foreach($values as $value) $value = "'" . $value . "'";

                    $value     = implode(',', $values);
                    $operator  = 'IN';
                    $condition = $operator . ' (' . $value . ') ';
                }
            }

            /* Processing query criteria. */
            if($operator == '=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $condition  = '`' . $this->post->$fieldName . "` >= '$value' AND `" . $this->post->$fieldName . "` <= '$value 23:59:59'";
                $where     .= " $andOr ($condition)";
            }
            elseif($operator == '!=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $condition  = '`' . $this->post->$fieldName . "` < '$value' OR `" . $this->post->$fieldName . "` > '$value 23:59:59'";
                $where     .= " $andOr ($condition)";
            }
            elseif($operator == '<=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $where .= " $andOr " . '`' . $this->post->$fieldName . "` <= '$value 23:59:59'";
            }
            elseif($operator == '>' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $where .= " $andOr " . '`' . $this->post->$fieldName . "` > '$value 23:59:59'";
            }
            elseif($condition)
            {
                $where .= " $andOr " . '`' . $this->post->$fieldName . '` ' . $condition;
            }
        }

        $where .=" ))";
        $where  = $this->replaceDynamic($where);

        /* Save to session. */
        $querySessionName = $this->post->module . 'Query';
        $formSessionName  = $this->post->module . 'Form';
        $this->session->set($querySessionName, $where);
        $this->session->set($formSessionName,  $queryForm);
        if($scoreNum > 2 && !dao::isError()) $this->loadModel('score')->create('search', 'saveQueryAdvanced');
    }

    /**
     * Init the search session for the first time search.
     *
     * @param  string   $module
     * @param  array    $fields
     * @param  array    $fieldParams
     * @access public
     * @return void
     */
    public function initSession($module, $fields, $fieldParams)
    {
        $formSessionName  = $module . 'Form';
        if(isset($_SESSION[$formSessionName]) and $_SESSION[$formSessionName] != false) return;

        for($i = 1; $i <= $this->config->search->groupItems * 2; $i ++)
        {
            /* Var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            $currentField = key($fields);
            $operator     = isset($fieldParams[$currentField]['operator']) ? $fieldParams[$currentField]['operator'] : '=';

            $queryForm[$fieldName]    = key($fields);
            $queryForm[$andOrName]    = 'and';
            $queryForm[$operatorName] = $operator;
            $queryForm[$valueName]    =  '';

            if(!next($fields)) reset($fields);
        }
        $queryForm['groupAndOr'] = 'and';
        $this->session->set($formSessionName, $queryForm);
    }

    /**
     * Init the search session for the first time search.
     *
     * @param  string   $module
     * @param  array    $fields
     * @param  array    $fieldParams
     * @access public
     * @return array
     */
    public function initZinSession($module, $fields, $fieldParams)
    {
        if(is_object($fields)) $fields = get_object_vars($fields);
        $formSessionName = $module . 'Form';

        $queryForm       = array();
        for($i = 1; $i <= $this->config->search->groupItems * 2; $i ++)
        {
            $currentField  = key($fields);
            $currentParams = zget($fieldParams, $currentField, array());
            $operator      = zget($currentParams, 'operator', '=');
            $queryForm[]   = array('field' => $currentField, 'andOr' => 'and', 'operator' => $operator, 'value' => '');

            if(!next($fields)) reset($fields);
        }
        $queryForm[] = array('groupAndOr' => 'and');
        $this->session->set($formSessionName, $queryForm);

        return $queryForm;
    }

    /**
     * Set default params for selection.
     *
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function setDefaultParams($fields, $params)
    {
        $hasProduct   = false;
        $hasExecution = false;
        $hasUser      = false;

        $appendUsers     = array();
        $module          = $_SESSION['searchParams']['module'];
        $formSessionName = $module . 'Form';
        if(isset($_SESSION[$formSessionName]))
        {
            $_SESSION[$formSessionName] = $this->convertFormFrom20To18($_SESSION[$formSessionName]);
            for($i = 1; $i <= $this->config->search->groupItems; $i ++)
            {
                $fieldName = 'field' . $i;
                $valueName = 'value' . $i;
                $fieldName = $_SESSION[$formSessionName][$fieldName];
                if(isset($params[$fieldName]) and $params[$fieldName]['values'] == 'users')
                {
                    if($_SESSION[$formSessionName][$valueName]) $appendUsers[] = $_SESSION[$formSessionName][$valueName];
                }
            }
        }

        $fields = array_keys($fields);
        foreach($fields as $fieldName)
        {
            if(empty($params[$fieldName])) continue;
            if($params[$fieldName]['values'] == 'products')   $hasProduct   = true;
            if($params[$fieldName]['values'] == 'users')      $hasUser      = true;
            if($params[$fieldName]['values'] == 'executions') $hasExecution = true;
        }

        if($hasUser)
        {
            $users = $this->loadModel('user')->getPairs('realname|noclosed', $appendUsers, $this->config->maxCount);
            $users['$@me'] = $this->lang->search->me;
        }
        if($hasProduct) $products = array('' => '') + $this->loadModel('product')->getPairs('', $this->session->project);
        if($hasExecution) $executions = array('' => '') + $this->loadModel('execution')->getPairs($this->session->project);

        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');
            if($params[$fieldName]['values'] == 'users')
            {
                if(!empty($this->config->user->moreLink)) $this->config->moreLinks["field{$fieldName}"] = $this->config->user->moreLink;
                $params[$fieldName]['values'] = $users;
            }
            if($params[$fieldName]['values'] == 'products')   $params[$fieldName]['values'] = $products;
            if($params[$fieldName]['values'] == 'executions') $params[$fieldName]['values'] = $executions;
            if(is_array($params[$fieldName]['values']))
            {
                /* For build right sql when key is 0 and is not null.  e.g. confirmed field. */
                if(isset($params[$fieldName]['values'][0]) and $params[$fieldName]['values'][0] !== '')
                {
                    $params[$fieldName]['values'] = array('ZERO' => $params[$fieldName]['values'][0]) + $params[$fieldName]['values'];
                    unset($params[$fieldName]['values'][0]);
                }
                elseif(empty($params[$fieldName]['values']))
                {
                    $params[$fieldName]['values'] = array('' => '', 'null' => $this->lang->search->null);
                }
                elseif(empty($params[$fieldName]['nonull']))
                {
                    $params[$fieldName]['values'] = $params[$fieldName]['values'] + array('null' => $this->lang->search->null);
                }
            }
        }
        return $params;
    }

    /**
     * Set default params for selection.
     *
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function setZinDefaultParams($fields, $params)
    {
        $hasProduct   = false;
        $hasExecution = false;
        $hasUser      = false;

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

        $fields = array_keys($fields);
        foreach($fields as $fieldName)
        {
            if(empty($params[$fieldName])) continue;
            if($params[$fieldName]['values'] == 'products')   $hasProduct   = true;
            if($params[$fieldName]['values'] == 'users')      $hasUser      = true;
            if($params[$fieldName]['values'] == 'executions') $hasExecution = true;
        }

        if($hasUser)
        {
            $users = $this->loadModel('user')->getPairs('realname|noclosed', $appendUsers, $this->config->maxCount);
            $users['$@me'] = $this->lang->search->me;
        }
        if($hasProduct) $products = $this->loadModel('product')->getPairs('', $this->session->project);
        if($hasExecution) $executions = $this->loadModel('execution')->getPairs($this->session->project);

        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');
            if($params[$fieldName]['values'] == 'users')
            {
                if(!empty($this->config->user->moreLink)) $this->config->moreLinks["field{$fieldName}"] = $this->config->user->moreLink;
                $params[$fieldName]['values'] = $users;
            }
            if($params[$fieldName]['values'] == 'products')   $params[$fieldName]['values'] = $products;
            if($params[$fieldName]['values'] == 'executions') $params[$fieldName]['values'] = $executions;
            if(is_array($params[$fieldName]['values']))
            {
                /* For build right sql when key is 0 and is not null.  e.g. confirmed field. */
                if(isset($params[$fieldName]['values'][0]) and $params[$fieldName]['values'][0] !== '')
                {
                    $params[$fieldName]['values'] = array('ZERO' => $params[$fieldName]['values'][0]) + $params[$fieldName]['values'];
                    unset($params[$fieldName]['values'][0]);
                }
                elseif(empty($params[$fieldName]['values']))
                {
                    $params[$fieldName]['values'] = array('' => '', 'null' => $this->lang->search->null);
                }
                elseif(empty($params[$fieldName]['nonull']))
                {
                    $params[$fieldName]['values'] = $params[$fieldName]['values'] + array('null' => $this->lang->search->null);
                }
            }
        }
        return $params;
    }

    /**
     * 转换formSession格式。
     * Convert format of formSession from version 18 to version 20 .
     *
     * @param  array  $formSession
     * @access public
     * @return array
     */
    public function convertFormFrom18To20($formSession)
    {
        $queryForm   = array();

        $queryForm = array();
        if(isset($formSession['field1']))
        {
            foreach($formSession as $field => $value)
            {
                $index = substr($field, -1);
                if(is_numeric($index))
                {
                    $field = substr($field, 0, strlen($field) - 1);
                    $queryForm[$index][$field] = $value;
                }
                elseif($field == 'groupAndOr')
                {
                    $queryForm[$field][$field] = $value;
                }
            }
            $formSession = array_values($queryForm);
        }

        return $formSession;
    }

    /**
     * 转换formSession格式。
     * Convert format of formSession from version 20 to version 18 .
     *
     * @param  array  $formSession
     * @access public
     * @return array
     */
    public function convertFormFrom20To18($formSession)
    {
        $queryForm = array();

        if(!isset($formSession['field1']))
        {
            foreach($formSession as $i => $formItem)
            {
                $i++;
                if(isset($formItem['groupAndOr']))
                {
                    $queryForm['groupAndOr'] = $formItem['groupAndOr'];
                }
                elseif(isset($formItem['field']))
                {
                    $queryForm['field' . $i]    = $formItem['field'];
                    $queryForm['andOr' . $i]    = $formItem['andOr'];
                    $queryForm['operator' . $i] = $formItem['operator'];
                    $queryForm['value' . $i]    = $formItem['value'];
                }
            }
            $formSession = $queryForm;
        }

        return $formSession;
    }

    /**
     * Get a query.
     *
     * @param  int    $queryID
     * @access public
     * @return object
     */
    public function getQuery($queryID)
    {
        $query = $this->dao->findByID((int)$queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;

        /* Decode html encode. */
        $query->form = htmlspecialchars_decode($query->form, ENT_QUOTES);
        $query->sql  = htmlspecialchars_decode($query->sql, ENT_QUOTES);

        $hasDynamic  = strpos($query->form, '$') !== false;
        $query->form = unserialize($query->form);
        $query->form = $this->convertFormFrom20To18($query->form);

        if($hasDynamic)
        {
            $_POST = $query->form;
            $this->buildQuery();
            $querySessionName = $query->form['module'] . 'Query';
            $query->sql = $_SESSION[$querySessionName];
        }
        return $query;
    }

    /**
     * Get a query.
     *
     * @param  int    $queryID
     * @access public
     * @return object
     */
    public function getZinQuery($queryID)
    {
        $query = $this->dao->findByID((int)$queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;

        /* Decode html encode. */
        $query->form = htmlspecialchars_decode($query->form, ENT_QUOTES);
        $query->sql  = htmlspecialchars_decode($query->sql, ENT_QUOTES);

        $hasDynamic  = strpos($query->form, '$') !== false;
        $query->form = unserialize($query->form);
        if($hasDynamic)
        {
            $_POST = $query->form;
            $this->buildQuery();
            $querySessionName = $query->form['module'] . 'Query';
            $query->sql = $_SESSION[$querySessionName];
        }

        $queryForm = array();
        if(isset($query->form['field1']))
        {
            foreach($query->form as $field => $value)
            {
                $index = substr($field, -1);
                if(is_numeric($index))
                {
                    $field = substr($field, 0, strlen($field) - 1);
                    $queryForm[$index][$field] = $value;
                }
                elseif($field == 'groupAndOr')
                {
                    $queryForm[$field][$field] = $value;
                }
            }
            $query->form = array_values($queryForm);
        }

        return $query;
    }

    /**
     * Get a query.
     *
     * @param  int    $queryID
     * @access public
     * @return object
     */
    public function getByID($queryID)
    {
        $query = $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;
        return $query;
    }

    /**
     * Save current query to db.
     *
     * @access public
     * @return void
     */
    public function saveQuery()
    {
        $sqlVar  = $this->post->module  . 'Query';
        $formVar = $this->post->module  . 'Form';
        $sql     = $_SESSION[$sqlVar];
        if(!$sql) $sql = ' 1 = 1 ';

        $query = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->add('form', serialize($_SESSION[$formVar]))
            ->add('sql',  $sql)
            ->skipSpecial('sql,form')
            ->remove('onMenuBar')
            ->get();
        if($this->post->onMenuBar) $query->shortcut = '1';
        $this->dao->insert(TABLE_USERQUERY)->data($query)->autoCheck()->check('title', 'notempty')->exec();

        if(!dao::isError())
        {
            $queryID = $this->dao->lastInsertID();
            if(!dao::isError()) $this->loadModel('score')->create('search', 'saveQuery', $queryID);
            return $queryID;
        }
        return false;
    }

    /**
     * Save current query to db of zin ui.
     *
     * @access public
     * @return void
     */
    public function saveZinQuery()
    {
        $sqlVar  = $this->post->module  . 'Query';
        $formVar = $this->post->module  . 'Form';
        $sql     = $_SESSION[$sqlVar];
        if(!$sql) $sql = ' 1 = 1 ';

        $query = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->add('form', serialize($_SESSION[$formVar]))
            ->add('sql',  $sql)
            ->skipSpecial('sql,form')
            ->remove('onMenuBar')
            ->get();
        if($this->post->onMenuBar) $query->shortcut = '1';
        $this->dao->insert(TABLE_USERQUERY)->data($query)->autoCheck()->check('title', 'notempty')->exec();

        if(!dao::isError())
        {
            $queryID = $this->dao->lastInsertID();
            if(!dao::isError()) $this->loadModel('score')->create('search', 'saveQuery', $queryID);
            return $queryID;
        }
        return false;
    }

    /**
     * Delete current query from db.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function deleteQuery($queryID)
    {
        $this->dao->delete()->from(TABLE_USERQUERY)->where('id')->eq($queryID)->andWhere('account')->eq($this->app->user->account)->exec();
    }

    /**
     * Get title => id pairs of a user.
     *
     * @param  string    $module
     * @access public
     * @return array
     */
    public function getQueryPairs($module)
    {
        $queries = $this->dao->select('id, title')
            ->from(TABLE_USERQUERY)
            ->where('module')->eq($module)
            ->andWhere('account', true)->eq($this->app->user->account)
            ->orWhere('common')->eq(1)
            ->markRight(1)
            ->orderBy('id_desc')
            ->fetchPairs();
        if(!$queries) return array('' => $this->lang->search->myQuery);
        $queries = array('' => $this->lang->search->myQuery) + $queries;
        return $queries;
    }

    /**
     * Get query list.
     *
     * @param  string    $module
     * @access public
     * @return array
     */
    public function getQueryList($module)
    {
        $queries = $this->dao->select('id, account, title')
            ->from(TABLE_USERQUERY)
            ->where('module')->eq($module)
            ->andWhere('account', true)->eq($this->app->user->account)
            ->orWhere('common')->eq(1)
            ->markRight(1)
            ->orderBy('id_desc')
            ->fetchAll();
        if(!$queries) return array('' => $this->lang->search->myQuery);
        $queries = array('' => $this->lang->search->myQuery) + $queries;
        return $queries;
    }

    /**
     * Get records by the condition.
     *
     * @param  string    $module
     * @param  string    $moduleIdList
     * @param  string    $conditions
     * @access public
     * @return array
     */
    public function getBySelect($module, $moduleIdList, $conditions)
    {
        if($module == 'story')
        {
            $pairs = 'id,title';
            $table = TABLE_STORY;
        }
        else if($module == 'task')
        {
            $pairs = 'id,name';
            $table = TABLE_TASK;
        }
        $query    = '`' . $conditions['field1'] . '`';
        $operator = $conditions['operator1'];
        $value    = $conditions['value1'];

        if(!isset($this->lang->search->operators[$operator])) $operator = '=';
        if($operator == "include")
        {
            $query .= ' LIKE ' . $this->dbh->quote("%$value%");
        }
        elseif($operator == "notinclude")
        {
            $where .= ' NOT LIKE ' . $this->dbh->quote("%$value%");
        }
        else
        {
            $query .= $operator . ' ' . $this->dbh->quote($value) . ' ';
        }

        foreach($moduleIdList as $id)
        {
            if(!$id) continue;
            $title = $this->dao->select($pairs)
                ->from($table)
                ->where('id')->eq((int)$id)
                ->andWhere($query)
                ->fetch();
            if($title) $results[$id] = $title;
        }
        if(!isset($results)) return array();
        return $this->formatResults($results, $module);
    }

    /**
     * Format the results.
     *
     * @param  array    $results
     * @param  string   $module
     * @access public
     * @return array
     */
    public function formatResults($results, $module)
    {
        /* Get title field. */
        $title = ($module == 'story') ? 'title' : 'name';
        $resultPairs = array('' => '');
        foreach($results as $result) $resultPairs[$result->id] = $result->id . ':' . $result->$title;
        return $resultPairs;
    }

    /**
      Replace dynamic account and date.
     *
     * @param  string $query
     * @access public
     * @return string
     */
    public function replaceDynamic($query)
    {
        $this->app->loadClass('date');
        $lastWeek  = date::getLastWeek();
        $thisWeek  = date::getThisWeek();
        $lastMonth = date::getLastMonth();
        $thisMonth = date::getThisMonth();
        $yesterday = date::yesterday();
        $today     = date(DT_DATE1);
        if(strpos($query, '$') !== false)
        {
            $query = str_replace('$@me', $this->app->user->account, $query);
            $query = str_replace("'\$lastMonth'", "'" . $lastMonth['begin']      . "' and '" . $lastMonth['end']        . "'", $query);
            $query = str_replace("'\$thisMonth'", "'" . $thisMonth['begin']      . "' and '" . $thisMonth['end']        . "'", $query);
            $query = str_replace("'\$lastWeek'",  "'" . $lastWeek['begin']       . "' and '" . $lastWeek['end']         . "'", $query);
            $query = str_replace("'\$thisWeek'",  "'" . $thisWeek['begin']       . "' and '" . $thisWeek['end']         . "'", $query);
            $query = str_replace("'\$yesterday'", "'" . $yesterday . ' 00:00:00' . "' and '" . $yesterday . ' 23:59:59' . "'", $query);
            $query = str_replace("'\$today'",     "'" . $today     . ' 00:00:00' . "' and '" . $today     . ' 23:59:59' . "'", $query);
        }
        return $query;
    }

    /**
     * Get list sql params.
     *
     * @param  string    $keywords
     * @param  string    $type
     * @access protected
     * @return array
     */
    protected function getSqlParams($keywords, $type)
    {
        $spliter = $this->app->loadClass('spliter');
        $words   = explode(' ', self::unify($keywords, ' '));

        $against     = '';
        $againstCond = '';

        foreach($words as $word)
        {
            $splitedWords = $spliter->utf8Split($word);
            $trimedWord   = trim($splitedWords['words']);
            $against     .= '"' . $trimedWord . '" ';
            $againstCond .= '(+"' . $trimedWord . '") ';

            if(is_numeric($word) and strpos($word, '.') === false and strlen($word) == 5) $againstCond .= "(-\" $word \") ";
        }

        $likeCondition = '';
        /* Assisted lookup by like condition when only one word. */
        if(count($words) == 1 and strpos($words[0], ' ') === false and !is_numeric($words[0])) $likeCondition = "OR title like '%{$trimedWord}%' OR content like '%{$trimedWord}%'";

        $words = str_replace('"', '', $against);
        $words = str_pad($words, 5, '_');

        $allowedObject = array();

        if($type != 'all')
        {
            foreach($type as $module) $allowedObject[] = $module;
        }
        else
        {
            if($this->config->systemMode == 'light') unset($this->config->search->fields->program);

            foreach($this->config->search->fields as $objectType => $fields)
            {
                $module = $objectType;
                if($module == 'case') $module = 'testcase';
                if(common::hasPriv($module, 'view')) $allowedObject[] = $objectType;

                if($module == 'caselib'    and common::hasPriv('caselib', 'view'))             $allowedObject[] = $objectType;
                if($module == 'deploystep' and common::haspriv('deploy', 'viewstep'))          $allowedobject[] = $objectType;
                if($module == 'practice'   and common::haspriv('traincourse', 'practiceView')) $allowedobject[] = $objectType;
            }
        }

        return array($words, $againstCond, $likeCondition, $allowedObject);
    }

    /**
     * Get counts of keyword search results.
     *
     * @param  string    $keywords
     * @param  string    $type
     * @access public
     * @return array
     */
    public function getListCount($keywords = '', $type = 'all')
    {
        list($words, $againstCond, $likeCondition, $allowedObject) = $this->getSqlParams($keywords, $type);

        $filterObject = array();
        foreach($allowedObject as $index => $object)
        {
            if(strpos(',feedback,ticket,', ",$object,") !== false)
            {
                unset($allowedObject[$index]);
                $filterObject[] = $object;
            }
        }

        $typeCount = $this->dao->select("objectType, count(*) as objectCount")
            ->from(TABLE_SEARCHINDEX)
            ->where('((vision')->eq($this->config->vision)
            ->andWhere('objectType')->in($allowedObject)
            ->markRight(1)
            ->orWhere('(objectType')->in($filterObject)
            ->markRight(2)
            ->andWhere('addedDate')->le(helper::now())
            ->groupBy('objectType')
            ->fetchPairs('objectType', 'objectCount');
        arsort($typeCount);
        return $typeCount;
    }

    /**
     * get search results of keywords.
     *
     * @param  string    $keywords
     * @param  string    $type
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getList($keywords, $type, $pager = null)
    {
        list($words, $againstCond, $likeCondition, $allowedObject) = $this->getSqlParams($keywords, $type);

        $filterObject = array();
        foreach($allowedObject as $index => $object)
        {
            if(strpos(',feedback,ticket,', ",$object,") !== false)
            {
                unset($allowedObject[$index]);
                $filterObject[] = $object;
            }
        }

        $orderBy        = 'score_desc, editedDate_desc';
        $scoreColumn    = "*, (MATCH(title, content) AGAINST('{$againstCond}' IN BOOLEAN MODE)) as score";
        $whereCondition = "(MATCH(title,content) AGAINST('{$againstCond}' IN BOOLEAN MODE) >= 1 {$likeCondition})";

        if($this->config->db->driver == 'dm')
        {
            $whereCondition = preg_replace("/\b(AND|OR)\b/", "", $likeCondition, 1);
            if(!$whereCondition) $whereCondition = '1=1';

            $scoreColumn    = '*';
            $orderBy        = 'editedDate_desc';
        }

        $stmt = $this->dao->select($scoreColumn)
            ->from(TABLE_SEARCHINDEX)
            ->where("($whereCondition)")
            ->andWhere('((vision')->eq($this->config->vision)
            ->andWhere('objectType')->in($allowedObject)
            ->markRight(1)
            ->orWhere('(objectType')->in($filterObject)
            ->markRight(2)
            ->andWhere('addedDate')->le(helper::now())
            ->orderBy($orderBy)
            ->query();

        $idListGroup = array();
        $results     = array();
        while($record = $stmt->fetch())
        {
            $module = $record->objectType == 'case' ? 'testcase' : $record->objectType;
            $idListGroup[$module][$record->objectID] = $record->objectID;

            $results[$record->id] = $record;
        }

        $results = $this->checkPriv($results, $idListGroup);
        if(empty($results)) return $results;

        /* Reset pager total and get this page data. */
        $pager->setRecTotal(count($results));
        $pager->setPageTotal();
        $pager->setPageID($pager->pageID);
        $results = array_chunk($results, $pager->recPerPage, true);
        $results = $results[$pager->pageID - 1];

        $idListGroup = array();
        foreach($results as $record)
        {
            $module = $record->objectType == 'case' ? 'testcase' : $record->objectType;
            $idListGroup[$module][$record->objectID] = $record->objectID;
        }

        $objectList = array();
        $linkProjectModules = ',task,bug,testcase,build,release,testtask,testsuite,testreport,trainplan,';
        foreach($idListGroup as $module => $idList)
        {
            if(!isset($this->config->objectTables[$module])) continue;
            $table = $this->config->objectTables[$module];

            $fields = '';
            if($module == 'issue') $fields = ($this->config->edition == 'max' or $this->config->edition == 'ipd') ? 'id,project,owner,lib' : 'id,project,owner';
            if($module == 'project') $fields = 'id,model';
            if($module == 'execution')$fields = 'id,type,project';
            if($module == 'story' or $module == 'requirement') $fields = ($this->config->edition == 'max' or $this->config->edition == 'ipd') ? 'id,type,lib' : 'id,type';
            if(($module == 'risk' or $module == 'opportunity') and ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $fields = 'id,lib';
            if($module == 'doc' and ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $fields = 'id,assetLib,assetLibType';
            if(empty($fields)) continue;

            $objectList[$module] = $this->dao->select($fields)->from($table)->where('id')->in($idList)->fetchAll('id');
        }

        foreach($results as $record)
        {
            $record->title   = str_replace('</span> ', '</span>', $this->decode($this->markKeywords($record->title, $words)));
            $record->title   = str_replace('_', '', $record->title);
            $record->summary = str_replace('</span> ', '</span>', $this->getSummary($record->content, $words));
            $record->summary = str_replace('_', '', $record->summary);

            $module = $record->objectType == 'case' ? 'testcase' : $record->objectType;
            $method = 'view';
            if($module == 'deploystep')
            {
                $module = 'deploy';
                $method = 'viewstep';
            }
            elseif($module == 'practice')
            {
                $module = 'traincourse';
                $method = 'practiceview';
            }

            if(strpos($linkProjectModules, ",$module,") !== false)
            {
                if(!isset($this->config->objectTables[$record->objectType])) continue;
                $record->url = helper::createLink($module, $method, "id={$record->objectID}");
            }
            elseif($module == 'issue')
            {
                $issue = $objectList['issue'][$record->objectID];
                if(!empty($issue->lib))
                {
                    $module = 'assetlib';
                    $method = 'issueView';
                }

                $record->url       = helper::createLink($module, $method, "id={$record->objectID}", '', false, $issue->project);
                $record->extraType = empty($issue->owner) ? 'commonIssue' : 'stakeholderIssue';
            }
            elseif($module == 'project')
            {
                $projectModel = $objectList['project'][$record->objectID]->model;
                $method       = $projectModel == 'kanban' ? 'index' : 'view';
                $record->url  = helper::createLink('project', $method, "id={$record->objectID}");
            }
            elseif($module == 'execution')
            {
                $execution         = $objectList['execution'][$record->objectID];
                $method            = $execution->type == 'kanban' ? 'kanban' : $method;
                $record->url       = helper::createLink('execution', $method, "id={$record->objectID}");
                $record->extraType = empty($execution->type) ? '' : $execution->type;
            }
            elseif($module == 'story' or $module == 'requirement')
            {
                $story  = $objectList[$module][$record->objectID];
                $module = 'story';
                if(!empty($story->lib))
                {
                    $module = 'assetlib';
                    $method = 'storyView';
                }

                $record->url = helper::createLink($module, $method, "id={$record->objectID}", '', false, 0, true);

                if($this->config->vision == 'lite') $record->url = helper::createLink('projectstory', $method, "storyID={$record->objectID}", '', false, 0, true);

                $record->extraType = isset($story->type) ? $story->type : '';
            }
            elseif(($module == 'risk' or $module == 'opportunity') and ($this->config->edition == 'max' or $this->config->edition == 'ipd'))
            {
                $object = $objectList[$module][$record->objectID];
                if(!empty($object->lib))
                {
                    $method = $module == 'risk' ? 'riskView' : 'opportunityView';
                    $module = 'assetlib';
                }

                $record->url = helper::createLink($module, $method, "id={$record->objectID}", '', false, 0, true);
            }
            elseif($module == 'doc' and ($this->config->edition == 'max' or $this->config->edition == 'ipd'))
            {
                $doc = $objectList['doc'][$record->objectID];
                if(!empty($doc->assetLib))
                {
                    $module = 'assetlib';
                    $method = $doc->assetLibType == 'practice' ? 'practiceView' : 'componentView';
                }

                $record->url = helper::createLink($module, $method, "id={$record->objectID}", '', false, 0, true);
            }
            else
            {
                $record->url = helper::createLink($module, $method, "id={$record->objectID}");
            }
        }

        return $results;
    }

    /**
     * Save an index item.
     *
     * @param  string    $objectType article|blog|page|product|thread|reply|
     * @param  int       $objectID
     * @access public
     * @return void
     */
    public function saveIndex($objectType, $object)
    {
        $fields = $this->config->search->fields->{$objectType};
        if(empty($fields)) return true;

        if($objectType == 'doc' && $this->config->edition != 'open') $object = $this->appendFiles($object);

        $index = new stdclass();
        $index->objectID   = $object->{$fields->id};
        $index->objectType = $objectType;
        $index->title      = $object->{$fields->title};
        $index->addedDate  = isset($object->{$fields->addedDate}) ? $object->{$fields->addedDate} : '0000-00-00 00:00:00';
        $index->editedDate = isset($object->{$fields->editedDate}) ? $object->{$fields->editedDate} : '0000-00-00 00:00:00';
        $index->vision     = isset($object->vision) ? $object->vision : 'rnd';

        $index->content = '';
        $contentFields  = explode(',', $fields->content . ',comment');
        foreach($contentFields as $field)
        {
            if(empty($field)) continue;
            $index->content .= $object->$field;
        }

        $spliter = $this->app->loadClass('spliter');

        $titleSplited   = $spliter->utf8Split($index->title);
        $index->title   = $titleSplited['words'];
        $contentSplited = $spliter->utf8Split(strip_tags($index->content));
        $index->content = $contentSplited['words'];

        $this->saveDict($titleSplited['dict'] + $contentSplited['dict']);
        $this->dao->replace(TABLE_SEARCHINDEX)->data($index)->exec();
        return true;
    }

    /**
     * Save dict info.
     *
     * @param  array    $words
     * @access public
     * @return void
     */
    public function saveDict($dict)
    {
        static $savedDict;
        if(empty($savedDict)) $savedDict = $this->dao->select("`key`")->from(TABLE_SEARCHDICT)->fetchPairs('key', 'key');
        foreach($dict as $key => $value)
        {
            if(!is_numeric($key) or empty($value) or strlen($key) != 5 or $key < 0 or $key > 65535) continue;
            if(isset($savedDict[$key])) continue;

            $this->dao->insert(TABLE_SEARCHDICT)->data(array('key' => $key, 'value' => $value))->exec();
            $savedDict[$key] = $key;
        }
    }

    /**
     * Transfer unicode to words.
     *
     * @param  string    $string
     * @access public
     * @return void
     */
    public function decode($string)
    {
        static $dict;
        if(empty($dict))
        {
            $dict = $this->dao->select("concat(`key`, ' ') as `key`, value")->from(TABLE_SEARCHDICT)->fetchPairs();
            $dict['|'] = '';
        }
        if(strpos($string, ' ') === false) return zget($dict, $string . ' ');
        return trim(str_replace(array_keys($dict), array_values($dict), $string . ' '));
    }

    /**
     * Get summary of results.
     *
     * @param  string    $content
     * @param  string    $words
     * @access public
     * @return string
     */
    public function getSummary($content, $words)
    {
        $length = $this->config->search->summaryLength;
        if(strlen($content) <= $length) return $this->decode($this->markKeywords($content, $words));

        $content = $this->markKeywords($content, $words);
        preg_match_all("/\<span class='text-danger'\>.*\<\/span\>/U", $content, $matches);

        if(empty($matches[0])) return $this->decode($this->markKeywords(substr($content, 0, $length), $words));

        $matches = $matches[0];
        $score   = 0;
        $needle  = '';
        foreach($matches as $matched)
        {
            if(strlen($matched) > $score)
            {
                $content = str_replace($needle, strip_tags($needle), $content);
                $needle  = $matched;
                $score   = strlen($matched);
            }
        }

        $content = str_replace('<span class', ' <spanclass', $content);
        $content = explode(' ', $content);

        $pos     = array_search(str_replace('<span class', '<spanclass', $needle), $content);

        $start   = max(0, $pos - ($length / 2));
        $summary = join(' ', array_slice($content, $start, $length));
        $summary = str_replace(' <spanclass', '<span class', $summary);

        return $this->decode($summary);
    }

    /**
     * Check product and project priv.
     *
     * @param  array    $results
     * @param  array    $objectPairs
     * @access public
     * @return array
     */
    public function checkPriv($results, $objectPairs = array())
    {
        if($this->app->user->admin) return $results;

        $this->loadModel('doc');
        $products       = $this->app->user->view->products;
        $shadowProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs('id');
        $programs       = $this->app->user->view->programs;
        $projects       = $this->app->user->view->projects;
        $executions     = $this->app->user->view->sprints;

        $objectPairs = array();
        $total       = count($results);
        if(empty($objectPairs))
        {
            foreach($results as $record) $objectPairs[$record->objectType][$record->objectID] = $record->id;
        }

        foreach($objectPairs as $objectType => $objectIdList)
        {
            $objectProducts = array();
            $objectExecutions = array();
            if(!isset($this->config->objectTables[$objectType])) continue;
            $table = $this->config->objectTables[$objectType];
            if(strpos(',bug,case,testcase,productplan,release,story,testtask,', ",$objectType,") !== false)
            {
               $objectProducts = $this->dao->select('id,product')->from($table)->where('id')->in(array_keys($objectIdList))->fetchGroup('product', 'id');
            }
            elseif(strpos(',build,task,testreport,', ",$objectType,") !== false)
            {
               $objectExecutions = $this->dao->select('id,execution')->from($table)->where('id')->in(array_keys($objectIdList))->fetchGroup('execution', 'id');
            }
            elseif($objectType == 'effort')
            {
                $efforts = $this->dao->select('id,product,execution')->from($table)->where('id')->in(array_keys($objectIdList))->fetchAll();
                foreach($efforts as $effort)
                {
                    $objectExecutions[$effort->execution][$effort->id] = $effort;
                    $effortProducts = explode(',', trim($effort->product, ','));
                    foreach($effortProducts as $effortProduct) $objectProducts[$effortProduct][$effort->id] = $effort;
                }
            }
            elseif($objectType == 'product')
            {
                foreach($objectIdList as $productID => $recordID)
                {
                    if(strpos(",$products,", ",$productID,") === false) unset($results[$recordID]);
                    if(in_array($productID, $shadowProducts)) unset($results[$recordID]);
                }
            }
            elseif($objectType == 'program')
            {
                foreach($objectIdList as $programID => $recordID)
                {
                    if(strpos(",$programs,", ",$programID,") === false) unset($results[$recordID]);
                }
            }
            elseif($objectType == 'project')
            {
                foreach($objectIdList as $projectID => $recordID)
                {
                    if(strpos(",$projects,", ",$projectID,") === false) unset($results[$recordID]);
                }
            }
            elseif($objectType == 'execution')
            {
                foreach($objectIdList as $executionID => $recordID)
                {
                    if(strpos(",$executions,", ",$executionID,") === false) unset($results[$recordID]);
                }
            }
            elseif($objectType == 'doc')
            {
                $objectDocs = $this->dao->select('*')->from($table)->where('id')->in(array_keys($objectIdList))
                    ->andWhere('deleted')->eq(0)
                    ->fetchAll('id');
                $privLibs = array();
                foreach($objectIdList as $docID => $recordID)
                {
                    if(!isset($objectDocs[$docID]) or !$this->doc->checkPrivDoc($objectDocs[$docID]))
                    {
                        unset($results[$recordID]);
                        continue;
                    }

                    $objectDoc = $objectDocs[$docID];
                    $privLibs[$objectDoc->lib] = $objectDoc->lib;
                }

                $libs = $this->doc->getLibs('all');
                $objectDocLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('id')->in($privLibs)
                    ->andWhere('id')->in(array_keys($libs))
                    ->andWhere('deleted')->eq(0)
                    ->fetchPairs('id', 'id');
                foreach($objectDocs as $docID => $doc)
                {
                    $libID = $doc->lib;
                    if(!isset($objectDocLibs[$libID]))
                    {
                        $recordID = $objectIdList[$docID];
                        unset($results[$recordID]);
                    }
                }
            }
            elseif($objectType == 'todo')
            {
                $objectTodos = $this->dao->select('id')->from($table)->where('id')->in(array_keys($objectIdList))->andWhere("private")->eq(1)->andWhere('account')->ne($this->app->user->account)->fetchPairs('id', 'id');
                foreach($objectTodos as $todoID)
                {
                    if(isset($objectIdList[$todoID]))
                    {
                        $recordID = $objectIdList[$todoID];
                        unset($results[$recordID]);
                    }
                }
            }
            elseif($objectType == 'testsuite')
            {
                $objectSuites = $this->dao->select('id')->from($table)->where('id')->in(array_keys($objectIdList))
                    ->andWhere("type")->eq('private')
                    ->andWhere('deleted')->eq(0)
                    ->fetchPairs('id', 'id');
                foreach($objectSuites as $suiteID)
                {
                    if(isset($objectIdList[$suiteID]))
                    {
                        $recordID = $objectIdList[$suiteID];
                        unset($results[$recordID]);
                    }
                }
            }
            elseif(strpos(',feedback,ticket,', ",$objectType,") !== false)
            {
                $grantProducts = $this->loadModel('feedback')->getGrantProducts();
                $objects       = $this->dao->select('*')->from($table)->where('id')->in(array_keys($objectIdList))->fetchAll('id');
                foreach($objects as $objectID => $object)
                {
                    if($objectType == 'feedback' and $object->openedBy == $this->app->user->account) continue;
                    if(isset($grantProducts[$object->product])) continue;
                    if(isset($objectIdList[$objectID]))
                    {
                        $recordID = $objectIdList[$objectID];
                        unset($results[$recordID]);
                    }
                }
            }

            foreach($objectProducts as $productID => $idList)
            {
                if(empty($productID)) continue;
                if(strpos(",$products,", ",$productID,") === false)
                {
                    foreach($idList as $object)
                    {
                        $recordID = $objectIdList[$object->id];
                        unset($results[$recordID]);
                    }
                }
            }
            foreach($objectExecutions as $executionID => $idList)
            {
                if(empty($executionID)) continue;
                if(strpos(",$executions,", ",$executionID,") === false)
                {
                    foreach($idList as $object)
                    {
                        $recordID = $objectIdList[$object->id];
                        unset($results[$recordID]);
                    }
                }
            }
        }
        return $results;
    }

    /**
     * Mark keywords in content.
     *
     * @param  string    $content
     * @param  string    $keywords
     * @access public
     * @return void
     */
    public function markKeywords($content, $keywords)
    {
        $words = explode(' ', trim($keywords, ' '));
        $markedWords = array();
        $leftMark  = '|0000';
        $rightMark = '0000|';

        foreach($words as $key => $word)
        {
            if(preg_match('/^\|[0-9]+\|$/', $word))
            {
                $words[$key] = trim($word, '|');
            }
            elseif(is_numeric($word))
            {
                $words[$key] = $word . ' ';
            }
            else
            {
                $words[$key] = strlen($word) == 5 ? str_replace('_', '', $word) : $word;
            }
            $markedWords[] = $leftMark . $this->decode($word) . $rightMark;
        }

        $content = str_replace($words, $markedWords, $content . ' ');
        $content = str_replace(array($leftMark, $rightMark), array("<span class='text-danger'>", "</span > "), $content);
        $content = str_replace("</span > <span class='text-danger'>", '', $content);
        $content = str_replace("</span >", '</span>', $content);

        return $content;
    }

    /**
     * Build index query.
     *
     * @param  string   $type
     * @param  bool     $testDelete
     * @access public
     * @return string
     */
    public function buildIndexQuery($type, $testDeleted = true)
    {
        $table = $this->config->objectTables[$type];
        if($type == 'story' or $type == 'requirement')
        {
            $query = $this->dao->select('DISTINCT t1.*,t2.spec,t2.verify')->from($table)->alias('t1')
                ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
                ->where('t1.deleted')->eq(0)
                ->andWhere('type')->eq($type)
                ->andWhere('t1.version=t2.version');
        }
        elseif($type == 'doc')
        {
            $query = $this->dao->select('DISTINCT t1.*,t2.content,t2.digest')->from($table)->alias('t1')->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc')->where('t1.deleted')->eq(0)->andWhere('t1.version=t2.version');
        }
        else
        {
            $data = '';
            if($testDeleted) $data = $this->dao->select('*')->from($table)->limit(1)->fetch();

            $query = $this->dao->select('t1.*')->from($table)->alias('t1')
                ->where('1=1')
                ->beginIF($type == 'program')->andWhere('type')->eq('program')->fi()
                ->beginIF($type == 'project')->andWhere('type')->eq('project')->fi()
                ->beginIF($type == 'execution')->andWhere('type')->in('stage,sprint,kanban')->fi()
                ->beginIF(isset($data->deleted))->andWhere('t1.deleted')->eq(0)->fi();
        }
        return $query;
    }

    /**
     * Build all search index.
     *
     * @param  string    $type
     * @param  int       $lastID
     * @access public
     * @return bool
     */
    public function buildAllIndex($type = '', $lastID = 0)
    {
        $limit      = 100;
        $nextObject = false;
        if(empty($type))
        {
            $this->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
            $this->dao->delete()->from(TABLE_SEARCHDICT)->exec();
            try
            {
                $this->dbh->exec('ALTER TABLE ' . TABLE_SEARCHINDEX . ' auto_increment=1');
            }
            catch(Exception $e){}
            $type = key($this->config->search->fields);
        }

        foreach($this->config->search->fields as $module => $field)
        {
            if($module != $type and !$nextObject) continue;
            if($module == $type) $nextObject = true;
            if(!isset($this->config->objectTables[$module])) continue;

            while(true)
            {
                $query    = $this->buildIndexQuery($module);
                $dataList = $query->beginIF($lastID)->andWhere('t1.id')->gt($lastID)->fi()->orderBy('t1.id')->limit($limit)->fetchAll('id');
                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                if($module == 'case') $caseStep = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->in(array_keys($dataList))->fetchGroup('case', 'id');
                $actions = $this->dao->select('*')->from(TABLE_ACTION)
                    ->where('objectType')->eq($module)
                    ->andWhere('objectID')->in(array_keys($dataList))
                    ->orderBy('date asc')
                    ->fetchGroup('objectID', 'id');

                $files = $this->dao->select('id,objectID,title,extension')->from(TABLE_FILE)
                    ->where('objectType')->eq($module)
                    ->andWhere('objectID')->in(array_keys($dataList))
                    ->orderBy('id asc')
                    ->fetchGroup('objectID', 'id');

                foreach($dataList as $id => $data)
                {
                    $data->comment = '';
                    if(isset($actions[$id]))
                    {
                        foreach($actions[$id] as $action)
                        {
                            if($action->action == 'opened')$data->{$field->addedDate} = $action->date;
                            $data->{$field->editedDate} = $action->date;
                            if(!empty($action->comment)) $data->comment .= $action->comment . "\n";
                        }
                    }

                    if(isset($files[$id]))
                    {
                        foreach($files[$id] as $file)
                        {
                            if(!empty($file->title)) $data->comment .= $file->title . '.' . $file->extension . "\n";
                        }
                    }

                    if($module == 'case')
                    {
                        $data->desc   = '';
                        $data->expect = '';
                        if(isset($caseStep[$id]))
                        {
                            foreach($caseStep[$id] as $step)
                            {
                                if($step->version != $data->version) continue;
                                $data->desc   .= $step->desc . "\n";
                                $data->expect .= $step->expect . "\n";
                            }
                        }
                    }
                }

                foreach($dataList as $data) $this->saveIndex($module, $data);
                return array('type' => $module, 'count' => count($dataList), 'lastID' => max(array_keys($dataList)));
            }
        }
        return array('finished' => true);
    }

    /**
     * Delete index of an object.
     *
     * @param  string    $objectType
     * @param  int       $objectID
     * @access public
     * @return void
     */
    public function deleteIndex($objectType, $objectID)
    {
        $this->dao->delete()->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->exec();
        return !dao::isError();
    }

    /**
     * Unified processing of search keywords.
     *
     * @param  string    $string
     * @param  string    $to
     * @access public
     * @return void
     */
    public static function unify($string, $to = ',')
    {
        $labels = array('_', '、', ' ', '-', '\n', '?', '@', '&', '%', '~', '`', '+', '*', '/', '\\', '。', '，');
        $string = str_replace($labels, $to, $string);
        return preg_replace("/[{$to}]+/", $to, trim($string, $to));
    }

    /**
     * Append document content to the document.
     *
     * @param  string    $object
     * @access public
     * @return void
     */
    public function appendFiles($object)
    {
        $docFiles = $this->dao->select('files')->from(TABLE_DOCCONTENT)->where('doc')->eq($object->id)->orderBy('version')->limit(1)->fetch('files');
        if(empty($docFiles)) return $object;

        $allDocFiles = $this->loadModel('file')->getByObject('doc', $object->id);
        if(!isset($object->comment)) $object->comment = '';
        foreach($allDocFiles as $file)
        {
            if(strpos(",$docFiles,", ",{$file->id},") === false) continue;
            if(strpos('docx|doc', $file->extension) !== false)
            {
                $convertedFile = $this->file->convertOffice($file, 'txt');
                if($convertedFile) $object->comment .= substr(file_get_contents($convertedFile), 0, $this->config->search->maxFileSize);
            }
            if($file->extension == 'txt')
            {
                $object->comment .= substr(file_get_contents($file->realPath), 0, $this->config->search->maxFileSize);
            }
        }

        return $object;
    }

    /**
     * Build search form options.
     *
     * @param  array $module
     * @param  array $fieldParams
     * @param  array $fieldsMap
     * @param  array $queries
     * @access public
     * @return object
     */
    public function buildSearchFormOptions($module, $fieldParams, $fields, $queries, $actionURL = '')
    {
        $opts = new stdClass();
        $opts->formConfig       = static::buildFormConfig();
        $opts->fields           = static::buildFormFields($fieldParams, $fields);
        $opts->operators        = static::buildFormOperators($this->lang->search->operators);
        $opts->andOr            = static::buildFormAndOrs($this->lang->search->andor);
        $opts->saveSearch       = static::buildFormSaveSearch($module);
        $opts->searchConditions = static::buildFormSavedQuery($queries, $actionURL);

        return $opts;
    }

    /**
     * Form Configuration of buildForm action.
     *
     * @access public
     * @return object
     */
    public static function buildFormConfig()
    {
        $config = new stdClass();
        $config->action = helper::createLink('search', 'buildZinQuery');
        $config->method = 'post';

        return $config;
    }

    /**
     * Fields options of buildForm action.
     *
     * @param  array $fieldParams
     * @param  array $fieldsMap
     * @access public
     * @return array
     */
    public static function buildFormFields($fieldParams, $fieldsMap)
    {
        $fields = array();

        foreach($fieldParams as $name => $param)
        {
            $field = new stdClass();
            $field->label        = isset($fieldsMap[$name]) ? $fieldsMap[$name] : '';
            $field->name         = $name;
            $field->control      = $param['control'];
            $field->operator     = $param['operator'];
            $field->defaultValue = '';
            $field->placeholder  = '';
            $field->values       = $param['values'];

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * Operators of buildForm action.
     *
     * @param  array $operators
     * @access public
     * @return array
     */
    public static function buildFormOperators($operators)
    {
        $ops = array();

        foreach($operators as $val => $title)
        {
            $op = new stdClass();
            $op->value = $val;
            $op->title = $title;

            $ops[] = $op;
        }

        return $ops;
    }

    /**
     * AndOr options of buildForm action.
     *
     * @param  array $andOrs
     * @access public
     * @return array
     */
    public static function buildFormAndOrs($andOrs)
    {
        $result = array();

        foreach($andOrs as $val => $title)
        {
            $item = new stdClass();
            $item->value = $val;
            $item->title = $title;

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Save Search button of buildForm action.
     *
     * @param  array $module
     * @access public
     * @return object
     */
    public static function buildFormSaveSearch($module)
    {
        global $lang;

        $result = new stdClass();
        $result->text     = $lang->search->saveCondition;
        $result->hasPriv  = common::hasPriv('search', 'saveQuery');
        $result->config   = array(
            'data-toggle'    => 'modal',
            'data-type'      => 'ajax',
            'data-data-type' => 'html',
            'data-url'       => helper::createLink('search', 'saveZinQuery', array('module' => $module)),
        );

        return $result;
    }

    /**
     * Saved Queries list of buildForm action.
     *
     * @param  array $queries
     * @param  array $account
     * @access public
     * @return array
     */
    public static function buildFormSavedQuery($queries, $actionURL)
    {
        $result = array();
        if(empty($queries)) return $result;

        global $lang;
        $hasPriv = common::hasPriv('search', 'deleteQuery');
        foreach($queries as $query)
        {
            if(!is_object($query)) continue;

            $item = new stdClass();
            $item->text     = $query->title;
            $item->applyURL = str_replace('myQueryID', $query->id, $actionURL);
            if($hasPriv) $item->deleteProps = array('className' => 'ajax-submit', 'data-confirm' => $lang->search->confirmDelete, 'href' => helper::createLink('search', 'deleteZinQuery', "queryID={$query->id}"));

            $result[] = $item;
        }

        return $result;
    }
}
