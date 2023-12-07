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
     * 初始化搜索表单，并且保存到 session。
     * Init the search session for the first time search.
     *
     * @param  string       $module
     * @param  object|array $fields
     * @param  object|array $fieldParams
     * @access protected
     * @return array
     */
    protected function initSession(string $module, object|array $fields, object|array $fieldParams): array
    {
        if(is_object($fields)) $fields = get_object_vars($fields);
        $formSessionName = $module . 'Form';

        $queryForm = array();
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
        $value = addcslashes(trim((string)$this->post->{$valueName}), '%');

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
     * 替换日期和用户变量。
     * Replace dynamic account and date.
     *
     * @param  string $query
     * @access protected
     * @return string
     */
    protected function replaceDynamic(string $query): string
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
     * 如果搜索框的选项是users，products, executions, 获取相对应的列表。
     * Get user, product and execution value of the param.
     *
     * @param  array $fields
     * @access protected
     * @return array
     */
    protected function getParamValues(array $fields, array $params): array
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

    /**
     * 获取 sql 语句的参数。
     * Get list sql params.
     *
     * @param  string    $keywords
     * @param  string    $type
     * @access protected
     * @return array
     */
    protected function getSqlParams(string $keywords, string|array $type): array
    {
        $spliter = $this->app->loadClass('spliter');
        $words   = explode(' ', $this->unify($keywords, ' '));

        $against     = '';
        $againstCond = '';
        foreach($words as $word)
        {
            /* 将 utf-8 字符串拆分为单词，为每个单词计算 unicode. */
            $splitedWords = $spliter->utf8Split($word);
            $trimmedWord   = trim($splitedWords['words']);
            $against     .= '"' . $trimmedWord . '" ';
            $againstCond .= '(+"' . $trimmedWord . '") ';

            if(is_numeric($word) && strpos($word, '.') === false and strlen($word) == 5) $againstCond .= "(-\" $word \") ";
        }

        $likeCondition = '';
        /* Assisted lookup by like condition when only one word. */
        if(count($words) == 1 and strpos($words[0], ' ') === false and !is_numeric($words[0])) $likeCondition = "OR title like '%{$trimmedWord}%' OR content like '%{$trimmedWord}%'";

        $words = str_replace('"', '', $against);
        $words = str_pad($words, 5, '_');

        return array($words, $againstCond, $likeCondition);
    }

    /**
     * 获取允许的模块。
     * Get allowed objects.
     *
     * @param  string|array $type
     * @access protected
     * @return array
     */
    protected function getAllowedObjects(string|array $type): array
    {
        $allowedObjects = array();
        if($type != 'all')
        {
            foreach($type as $module) $allowedObjects[] = $module;

            return $allowedObjects;
        }

        if($this->config->systemMode == 'light') unset($this->config->search->fields->program);

        foreach($this->config->search->fields as $objectType => $fields)
        {
            $module = $objectType;
            if($module == 'case') $module = 'testcase';

            if(common::hasPriv($module, 'view')) $allowedObjects[] = $objectType;
            if($module == 'deploystep' && common::haspriv('deploy',  'viewstep')) $allowedobjects[] = $objectType;
        }

        return $allowedObjects;
    }

    /**
     * 检查产品的权限。
     * Check product prividge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @param  string  $products
     * @access private
     * @return array
     */
    private function checkProductPriv(array $results, array $objectIdList, string $products): array
    {
        $shadowProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs('id');
        foreach($objectIdList as $productID => $recordID)
        {
            if(strpos(",$products,", ",$productID,") === false) unset($results[$recordID]);
            if(in_array($productID, $shadowProducts)) unset($results[$recordID]);
        }

        return $results;
    }

    /**
     * 检查项目集的权限。
     * Check program priviledge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @access private
     * @return array
     */
    private function checkProgramPriv(array $results, array $objectIdList): array
    {
        $programs = $this->app->user->view->programs;
        foreach($objectIdList as $programID => $recordID)
        {
            if(strpos(",$programs,", ",$programID,") === false) unset($results[$recordID]);
        }
        return $results;
    }

    /**
     * 检查项目的权限。
     * Check project priviledge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @access private
     * @return array
     */
    private function checkProjectPriv(array $results, array $objectIdList): array
    {
        $projects = $this->app->user->view->projects;
        foreach($objectIdList as $projectID => $recordID)
        {
            if(strpos(",$projects,", ",$projectID,") === false) unset($results[$recordID]);
        }
        return $results;
    }

    /**
     * 检查执行的权限。
     * Check execution priviledge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @param  array   $executions
     * @access private
     * @return array
     */
    private function checkExecutionPriv(array $results, array $objectIdList, array $executions): array
    {
        foreach($objectIdList as $executionID => $recordID)
        {
            if(strpos(",$executions,", ",$executionID,") === false) unset($results[$recordID]);
        }
        return $results;
    }

    /**
     * 检查文档的权限。
     * Check doc priviledge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @param  string  $table
     * @access private
     * @return array
     */
    private function checkDocPriv(array $results, array $objectIdList, string $table): array
    {
        $this->loadModel('doc');
        $objectDocs = $this->dao->select('*')->from($table)->where('id')->in(array_keys($objectIdList))->andWhere('deleted')->eq('0')->fetchAll('id');

        $privLibs = array();
        foreach($objectIdList as $docID => $recordID)
        {
            if(!isset($objectDocs[$docID]) || !$this->doc->checkPrivDoc($objectDocs[$docID]))
            {
                unset($results[$recordID]);
                continue;
            }

            $objectDoc = $objectDocs[$docID];
            $privLibs[$objectDoc->lib] = $objectDoc->lib;
        }

        $libs = $this->doc->getLibs('all');
        $objectDocLibs = $this->dao->select('id')->from(TABLE_DOCLIB)->where('id')->in($privLibs)->andWhere('id')->in(array_keys($libs))->andWhere('deleted')->eq('0')->fetchPairs();
        foreach($objectDocs as $docID => $doc)
        {
            if(!isset($objectDocLibs[$doc->lib]))
            {
                $recordID = $objectIdList[$docID];
                unset($results[$recordID]);
            }
        }
        return $results;
    }

    /**
     * 检查待办的权限。
     * Check todo priviledge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @param  string  $table
     * @access private
     * @return array
     */
    private function checkTodoPriv(array $results, array $objectIdList, string $table): array
    {
        $objectTodos = $this->dao->select('id')->from($table)->where('id')->in(array_keys($objectIdList))->andWhere("private")->eq(1)->andWhere('account')->ne($this->app->user->account)->fetchPairs();
        foreach($objectTodos as $todoID)
        {
            if(isset($objectIdList[$todoID]))
            {
                $recordID = $objectIdList[$todoID];
                unset($results[$recordID]);
            }
        }
        return $results;
    }

    /**
     * 检查套件的权限。
     * Check testsuite Priviledge.
     *
     * @param  array   $results
     * @param  array   $objectIdList
     * @param  string  $table
     * @access private
     * @return array
     */
    private function checkTestsuitePriv(array $results, array $objectIdList, string $table): array
    {
        $objectSuites = $this->dao->select('id')->from($table)->where('id')->in(array_keys($objectIdList))->andWhere('type')->eq('private')->andWhere('deleted')->eq('0')->fetchPairs();
        foreach($objectSuites as $suiteID)
        {
            if(isset($objectIdList[$suiteID]))
            {
                $recordID = $objectIdList[$suiteID];
                unset($results[$recordID]);
            }
        }
        return $results;
    }

    /**
     * 检查反馈和工单的权限。
     * Check feedback and ticket priviledge.
     *
     * @param  string  $objectType
     * @param  array   $results
     * @param  array   $objectIdList
     * @param  string  $table
     * @access private
     * @return array
     */
    private function checkFeedbackAndTicketPriv(string $objectType, array $results, array $objectIdList, string $table): array
    {
        $grantProducts = $this->loadModel('feedback')->getGrantProducts();
        $objects       = $this->dao->select('*')->from($table)->where('id')->in(array_keys($objectIdList))->fetchAll('id');
        foreach($objects as $objectID => $object)
        {
            if($objectType == 'feedback' && $object->openedBy == $this->app->user->account) continue;
            if(isset($grantProducts[$object->product])) continue;

            if(isset($objectIdList[$objectID]))
            {
                $recordID = $objectIdList[$objectID];
                unset($results[$recordID]);
            }
        }
        return $results;
    }

    /**
     * 检查搜索到的模块的权限。
     * Check the priviledge of the object.
     *
     * @param  string    $objectType
     * @param  string    $table
     * @param  array     $results
     * @param  array     $objectIdList
     * @param  string    $products
     * @param  array     $executions
     * @access protected
     * @return array
     */
    protected function checkObjectPriv(string $objectType, string $table, array $results, array $objectIdList, string $products, array $executions): array
    {
        if($objectType == 'product')   return $this->checkProductPriv($results, $objectIdList, $products);
        if($objectType == 'program')   return $this->checkProgramPriv($results, $objectIdList);
        if($objectType == 'project')   return $this->checkProjectPriv($results, $objectIdList);
        if($objectType == 'execution') return $this->checkExecutionPriv($results, $objectIdList, $executions);
        if($objectType == 'doc')       return $this->checkDocPriv($results, $objectIdList, $table);
        if($objectType == 'todo')      return $this->checkTodoPriv($results, $objectIdList, $table);
        if($objectType == 'testsuite') return $this->checkTestsuitePriv($results, $objectIdList, $table);
        if(strpos(',feedback,ticket,', ",$objectType,") !== false) return $this->checkFeedbackAndTicketPriv($objectType, $results, $objectIdList, $table);

        return $results;
    }

    /**
     * 检查各个模块所属的产品或者执行的权限。
     * Check related object priviledge.
     *
     * @param  string    $objectType
     * @param  string    $table
     * @param  array     $results
     * @param  array     $objectIdList
     * @param  array     $products
     * @param  array     $executions
     * @access protected
     * @return array
     */
    protected function checkRelatedObjectPriv(string $objectType, string $table, array $results, array $objectIdList, array $products, array $executions): array
    {
        $objectProducts   = array();
        $objectExecutions = array();
        if(strpos(',bug,case,testcase,productplan,release,story,testtask,', ",$objectType,") !== false)
        {
           $objectProducts = $this->dao->select('id, product')->from($table)->where('id')->in(array_keys($objectIdList))->fetchGroup('product', 'id');
        }
        elseif(strpos(',build,task,testreport,', ",$objectType,") !== false)
        {
           $objectExecutions = $this->dao->select('id, execution')->from($table)->where('id')->in(array_keys($objectIdList))->fetchGroup('execution', 'id');
        }
        elseif($objectType == 'effort')
        {
            $efforts = $this->dao->select('id, product, execution')->from($table)->where('id')->in(array_keys($objectIdList))->fetchAll();
            foreach($efforts as $effort)
            {
                $objectExecutions[$effort->execution][$effort->id] = $effort;

                $effortProducts = explode(',', trim($effort->product, ','));
                foreach($effortProducts as $effortProduct) $objectProducts[$effortProduct][$effort->id] = $effort;
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
        return $results;
    }

    /**
     * 将搜索到的结果分页。
     * Set results in pages.
     *
     * @param  array     $results
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function setResultsInPage(array $results, object $pager): array
    {
        $pager->setRecTotal(count($results));
        $pager->setPageTotal();
        $pager->setPageID($pager->pageID);

        $results = array_chunk($results, $pager->recPerPage, true);
        return $results[$pager->pageID - 1];
    }

    /**
     * 获取搜索到的结果对应的内容。
     * Get object list.
     *
     * @param  array     $idListGroup
     * @access protected
     * @return array
     */
    protected function getObjectList(array $idListGroup): array
    {
        $objectList = array();
        foreach($idListGroup as $module => $idList)
        {
            if(!isset($this->config->objectTables[$module])) continue;

            $fields = '';
            if($module == 'issue')     $fields = ($this->config->edition == 'max' or $this->config->edition == 'ipd') ? 'id,project,owner,lib' : 'id,project,owner';
            if($module == 'project')   $fields = 'id,model';
            if($module == 'execution') $fields = 'id,type,project';
            if($module == 'story' || $module == 'requirement') $fields = ($this->config->edition == 'max' || $this->config->edition == 'ipd') ? 'id,type,lib' : 'id,type';
            if(($module == 'risk' || $module == 'opportunity') && ($this->config->edition == 'max' || $this->config->edition == 'ipd')) $fields = 'id,lib';
            if($module == 'doc' && ($this->config->edition == 'max' || $this->config->edition == 'ipd')) $fields = 'id,assetLib,assetLibType';

            if(empty($fields)) continue;

            $table = $this->config->objectTables[$module];
            $objectList[$module] = $this->dao->select($fields)->from($table)->where('id')->in($idList)->fetchAll('id');
        }
        return $objectList;
    }

    /**
     * 检查查询到的结果的权限。
     * Check product and project priv.
     *
     * @param  array     $results
     * @param  array     $objectPairs
     * @access protected
     * @return array
     */
    protected function checkPriv(array $results, array $objectPairs = array()): array
    {
        if($this->app->user->admin) return $results;

        $products   = $this->app->user->view->products;
        $executions = $this->app->user->view->sprints;

        if(empty($objectPairs)) foreach($results as $record) $objectPairs[$record->objectType][$record->objectID] = $record->id;
        foreach($objectPairs as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;

            $table = $this->config->objectTables[$objectType];
            $results = $this->checkObjectPriv($objectType, $table, $results, $objectIdList, $products, $executions);
            $results = $this->checkRelatedObjectPriv($objectType, $table, $results, $objectIdList, $products, $executions);
        }
        return $results;
    }


    /**
     * 处理搜索结果。
     * Process results.
     *
     * @param  array     $results
     * @param  array     $objectList
     * @param  string    $words
     * @access protected
     * @return array
     */
    protected function processResults(array $results, array $objectList, string $words): array
    {
        foreach($results as $record)
        {
            $record->title   = str_replace('</span> ', '</span>', $this->decode($this->markKeywords($record->title, $words)));
            $record->title   = str_replace('_', '', $record->title);
            $record->summary = str_replace('</span> ', '</span>', $this->getSummary($record->content, $words));
            $record->summary = str_replace('_', '', $record->summary);

            $record = $this->processRecord($record, $objectList);
        }

        return $results;
    }

    /**
     * 将 unicode 转换为对应的字。
     * Transfer unicode to words.
     *
     * @param  string $string
     * @access private
     * @return string
     */
    private function decode(string $string): string
    {
        static $dict;
        if(empty($dict))
        {
            $dict = $this->dao->select("concat(`key`, ' ') AS `key`, value")->from(TABLE_SEARCHDICT)->fetchPairs();
            $dict['|'] = '';
        }
        if(strpos($string, ' ') === false) return zget($dict, $string . ' ');
        return trim(str_replace(array_keys($dict), array_values($dict), $string . ' '));
    }

    /**
     * 获取结果显示的摘要。
     * Get summary of results.
     *
     * @param  string $content
     * @param  string $words
     * @access private
     * @return string
     */
    private function getSummary(string $content, string $words): string
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
     * 在文中标记关键词。
     * Mark keywords in content.
     *
     * @param  string $content
     * @param  string $keywords
     * @access private
     * @return string
     */
    private function markKeywords(string $content, string $keywords): string
    {
        $words = explode(' ', trim($keywords, ' '));
        $leftMark  = '|0000';
        $rightMark = '0000|';

        $markedWords = array();
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
     * 处理搜索的记录。
     * Process record.
     *
     * @param  object   $record
     * @param  array   $objectList
     * @access private
     * @return object
     */
    private function processRecord(object $record, array $objectList): object
    {
        $module = $record->objectType == 'case' ? 'testcase' : $record->objectType;
        $method = 'view';
        if($module == 'deploystep')
        {
            $module = 'deploy';
            $method = 'viewstep';
        }

        $linkProjectModules = ',task,bug,testcase,build,release,testtask,testsuite,testreport,trainplan,';
        if(strpos($linkProjectModules, ",$module,") !== false && !isset($this->config->objectTables[$record->objectType])) return $record;

        if($module == 'issue')     return $this->processIssueRecord($record, $objectList);
        if($module == 'project')   return $this->processProjectRecord($record, $objectList);
        if($module == 'execution') return $this->processExecutionRecord($record, $objectList);
        if($module == 'story' || $module == 'requirement') return $this->processStoryRecord($record, $module, $objectList);
        if(($module == 'risk' || $module == 'opportunity') && ($this->config->edition == 'max' || $this->config->edition == 'ipd')) return $this->processRiskRecord($record, $module, $objectList);
        if($module == 'doc' && ($this->config->edition == 'max' || $this->config->edition == 'ipd')) return $this->processDocRecord($record, $objectList);

        $record->url = helper::createLink($module, $method, "id={$record->objectID}");
        return $record;
    }

    /**
     * 处理搜索到的问题记录的链接和类型。
     * Process issue record url and extra type.
     *
     * @param  object  $record
     * @param  array   $objectList
     * @access private
     * @return object
     */
    private function processIssueRecord(object $record, array $objectList): object
    {
        $issue = $objectList['issue'][$record->objectID];
        $module = !empty($issue->lib) ? 'assetlib' : 'issue';
        $method = !empty($issue->lib) ? 'issueView' : 'view';

        $record->url       = helper::createLink($module, $method, "id={$record->objectID}", '', false, $issue->project);
        $record->extraType = empty($issue->owner) ? 'commonIssue' : 'stakeholderIssue';
        return $record;
    }

    /**
     * 处理搜索到的项目的链接。
     * processProjectRecord
     *
     * @param  object   $record
     * @param  array   $objectList
     * @access private
     * @return object
     */
    private function processProjectRecord(object $record, array $objectList): object
    {
        $projectModel = $objectList['project'][$record->objectID]->model;
        $method       = $projectModel == 'kanban' ? 'index' : 'view';
        $record->url  = helper::createLink('project', $method, "id={$record->objectID}");
        return $record;
    }

    /**
     * 处理执行记录的链接和类型。
     * Process execution record url and extra type.
     *
     * @param  object  $record
     * @param  array   $objectList
     * @access private
     * @return object
     */
    private function processExecutionRecord(object $record, array $objectList): object
    {
        $execution         = $objectList['execution'][$record->objectID];
        $method            = $execution->type == 'kanban' ? 'kanban' : 'view';
        $record->url       = helper::createLink('execution', $method, "id={$record->objectID}");
        $record->extraType = empty($execution->type) ? '' : $execution->type;
        return $record;
    }

    /**
     * 处理需求记录的链接。
     * Process story record url.
     *
     * @param  object  $record
     * @param  string  $module
     * @param  array   $objectList
     * @access private
     * @return object
     */
    private function processStoryRecord(object $record, string $module, array $objectList): object
    {
        $story  = $objectList[$module][$record->objectID];
        $module = 'story';
        $method = 'view';
        if(!empty($story->lib))
        {
            $module = 'assetlib';
            $method = 'storyView';
        }

        $record->url = helper::createLink($module, $method, "id={$record->objectID}", '', false, 0, true);

        if($this->config->vision == 'lite') $record->url = helper::createLink('projectstory', $method, "storyID={$record->objectID}", '', false, 0, true);

        $record->extraType = zget($story, 'type', '');

        return $record;
    }

    /**
     * 处理文档记录的链接。
     * Process doc record url.
     *
     * @param  object  $record
     * @param  array   $objectList
     * @param  string  $module
     * @access private
     * @return object
     */
    private function processDocRecord(object $record, array $objectList, string $module): object
    {
        $doc = $objectList['doc'][$record->objectID];
        $module = 'doc';
        $method = 'view';
        if(!empty($doc->assetLib))
        {
            $module = 'assetlib';
            $method = $doc->assetLibType == 'practice' ? 'practiceView' : 'componentView';
        }

        $record->url = helper::createLink($module, $method, "id={$record->objectID}", '', false, 0, true);
        return $record;
    }

    /**
     * 处理风险记录的链接。
     * Process risk record url.
     *
     * @param  object  $record
     * @param  string  $module
     * @param  array   $objectList
     * @access private
     * @return object
     */
    private function processRiskRecord(object $record, string $module, array $objectList): object
    {
        $object = $objectList[$module][$record->objectID];
        $method = 'view';
        if(!empty($object->lib))
        {
            $method = $module == 'risk' ? 'riskView' : 'opportunityView';
            $module = 'assetlib';
        }

        $record->url = helper::createLink($module, $method, "id={$record->objectID}", '', false, 0, true);
        return $record;
    }

    /**
     * Process data list for saving index.
     * 处理要保存的索引数据。
     *
     * @param  string    $module
     * @param  object    $field
     * @param  array     $dataList
     * @access protected
     * @return array
     */
    protected function processDataList(string $module, object $field, array $dataList): array
    {
        if($module == 'case') $caseStep = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->in(array_keys($dataList))->fetchGroup('case', 'id');
        $actions = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq($module)->andWhere('objectID')->in(array_keys($dataList))->orderBy('date asc')->fetchGroup('objectID', 'id');
        $files   = $this->dao->select('id,objectID,title,extension')->from(TABLE_FILE)->where('objectType')->eq($module)->andWhere('objectID')->in(array_keys($dataList))->orderBy('id asc')->fetchGroup('objectID', 'id');

        foreach($dataList as $id => $data)
        {
            $data->comment = '';
            if(isset($actions[$id]))
            {
                foreach($actions[$id] as $action)
                {
                    if($action->action == 'opened') $data->{$field->addedDate} = $action->date;
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
        return $dataList;
    }

    /**
     * 将特殊符号替换成统一的符号。
     * Unified processing of search keywords.
     *
     * @param  string $string
     * @param  string $to
     * @access private
     * @return string
     */
    private static function unify(string $string, string $to = ','): string
    {
        $labels = array('_', '、', ' ', '-', '\n', '?', '@', '&', '%', '~', '`', '+', '*', '/', '\\', '。', '，');
        $string = str_replace($labels, $to, $string);
        return preg_replace("/[{$to}]+/", $to, trim($string, $to));
    }

    /**
     * 将文档的内容追加到文档的索引备注中。
     * Append document content to the document.
     *
     * @param  object    $object
     * @access protected
     * @return object
     */
    protected function appendFiles(object $object): object
    {
        $docFiles = $this->dao->select('files')->from(TABLE_DOCCONTENT)->where('doc')->eq($object->id)->orderBy('version')->limit(1)->fetch('files');
        if(empty($docFiles)) return $object;

        if(!isset($object->comment)) $object->comment = '';

        $allDocFiles = $this->loadModel('file')->getByObject('doc', $object->id);
        foreach($allDocFiles as $file)
        {
            if(strpos(",$docFiles,", ",{$file->id},") === false) continue;

            if(strpos('docx|doc', $file->extension) !== false)
            {
                $convertedFile = $this->file->convertOffice($file, 'txt');
                if($convertedFile) $object->comment .= substr(file_get_contents($convertedFile), 0, $this->config->search->maxFileSize);
            }

            if($file->extension == 'txt') $object->comment .= substr(file_get_contents($file->realPath), 0, $this->config->search->maxFileSize);
        }

        return $object;
    }
}
