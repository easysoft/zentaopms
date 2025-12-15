<?php
/**
 * The model file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: model.php 5082 2013-07-10 01:14:45Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class searchModel extends model
{
    /**
     * 构造搜索表单时调用被搜索模块的方法处理搜索参数。
     * Call the method of the searched module to process search parameters when constructing the search form.
     *
     * @param  string $module
     * @param  bool   $cacheSearchFunc // 是否缓存构造搜索参数的方法。默认不缓存以加载真实值。Wheater to cache the method of constructing search parameters. Default is not to cache to load real values.
     * @access public
     * @return array
     */
    public function processSearchParams(string $module, bool $cacheSearchFunc = false): array
    {
        $cacheKey  = $module . 'SearchFunc';
        $funcModel = $this->session->$cacheKey['funcModel'] ?? '';
        $funcName  = $this->session->$cacheKey['funcName']  ?? '';
        $funcArgs  = $this->session->$cacheKey['funcArgs']  ?? [];
        if(!$funcModel || !$funcName || !$funcArgs) return $_SESSION[$module . 'searchParams'] ?? [];

        $funcArgs['cacheSearchFunc'] = $cacheSearchFunc;
        return $this->loadModel($funcModel)->$funcName(...array_values($funcArgs)); // PHP 8.0以下只能展开索引数组。PHP 8.0 below can only unpack indexed arrays.
    }

    /**
     * 设置搜索参数的session。
     * Set search params to session.
     *
     * @param  array  $searchConfig
     * @access public
     * @return void
     */
    public function setSearchParams(array &$searchConfig)
    {
        $module = $searchConfig['module'];

        if($this->config->edition != 'open') $searchConfig = $this->searchTao->processBuildinFields($module, $searchConfig);

        $searchParams['module']    = $module;
        $searchParams['actionURL'] = $searchConfig['actionURL'];
        $searchParams['style']     = zget($searchConfig, 'style', 'full');
        $searchParams['onMenuBar'] = zget($searchConfig, 'onMenuBar', 'no');
        $searchParams['queryID']   = isset($searchConfig['queryID']) ? $searchConfig['queryID'] : 0;

        if(empty($_SESSION[$module . 'SearchFunc']))
        {
            $searchParams['fields'] = $searchConfig['fields'];
            $searchParams['params'] = $searchConfig['params'];
        }

        $this->session->set($module . 'searchParams', $searchParams);
    }

    /**
     * 设置默认的搜索参数。
     * Set default params for selection.
     *
     * @param  string $module
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function setDefaultParams(string $module, array $fields, array $params): array
    {
        $fields = array_keys($fields);

        list($users, $products, $executions) = $this->getParamValues($module, $fields, $params);

        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');

            if($params[$fieldName]['values'] == 'users')      $params[$fieldName]['values'] = $users;
            if($params[$fieldName]['values'] == 'products')   $params[$fieldName]['values'] = $products;
            if($params[$fieldName]['values'] == 'executions') $params[$fieldName]['values'] = $executions;

            /* 处理数组。*/
            /* Process array value. */
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
     * 构建查询语句。
     * Build the query to execute.
     *
     * @access public
     * @return void
     */
    public function buildQuery()
    {
        /* Init vars. */
        $module       = $this->post->module;
        $searchConfig = $this->processSearchParams($module, true);
        $searchFields = $searchConfig['fields'] ?? [] ;
        $fieldParams  = $searchConfig['params'] ?? [];
        $groupItems   = $this->config->search->groupItems;
        $groupAndOr   = strtoupper($this->post->groupAndOr);
        if($groupAndOr != 'AND' && $groupAndOr != 'OR') $groupAndOr = 'AND';

        $queryForm = $this->searchTao->initSession($module, $searchFields, $fieldParams);

        $scoreNum = 0;
        $where    = '';
        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* The and or between two groups. */
            $formIndex = $i - 1;
            if($i == 1) $where .= '(( 1 = 1 ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 = 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            $field        = $this->post->$fieldName;
            $value        = $this->post->$valueName;
            $fieldControl = $fieldParams[$field]['control'] ?? '';
            if(empty($field) || $value === '' || $value === false) continue; // false means no exist this post item. '' means no search data. ignore it.
            if(!preg_match('/^[a-zA-Z0-9]+$/', $field)) continue; // Fix sql injection.

            /* 如果是输入框，并且输入框的值为'0'，或者 id 的值为'0'，将值设置为zero。*/
            if($fieldControl == 'input' && $value === '0') $this->post->set($valueName, 'ZERO');
            if($field == 'id' && $value === '0') $this->post->set($valueName, 'ZERO');

            /* set queryForm. */
            list($andOr, $operator, $value) = $this->searchTao->processQueryFormDatas($fieldParams, $field, $andOrName, $operatorName, $valueName);
            $queryForm[$formIndex] = array('field' => $field, 'andOr' => strtolower($andOr), 'operator' => $operator, 'value' => $value);

            /* Set where. */
            $where = $this->searchTao->setWhere($where, $field, $operator, $value, $andOr, $fieldControl);

            $scoreNum += 1;
        }

        foreach($queryForm as $index => $queryField)
        {
            if(!empty($queryForm[$index]['groupAndOr'])) $queryForm[$index]['groupAndOr'] = strtolower($groupAndOr);
        }

        $where .=" ))";
        $where  = $this->searchTao->replaceDynamic($where);

        /* Save to session. */
        $querySessionName = $this->post->module . 'Query';
        $formSessionName  = $this->post->module . 'Form';
        $this->session->set($querySessionName, $where);
        $this->session->set($formSessionName,  $queryForm);
        if($scoreNum > 2 && !dao::isError()) $this->loadModel('score')->create('search', 'saveQueryAdvanced');
    }

    /**
     * Build the query to execute.
     *
     * @access public
     * @return void
     */
    public function buildOldQuery()
    {
        /* Init vars. */
        $where        = '';
        $groupItems   = $this->config->search->groupItems;
        $groupAndOr   = strtoupper($this->post->groupAndOr);
        $module       = $this->post->module;
        $searchConfig = $this->processSearchParams($module, true);
        $fieldParams  = $searchConfig['params'] ?? [];
        $scoreNum     = 0;

        if($groupAndOr != 'AND' and $groupAndOr != 'OR') $groupAndOr = 'AND';

        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* The and or between two groups. */
            if($i == 1) $where .= '(( 1 = 1 ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 = 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* Fix bug #2704. */
            $field = $this->post->$fieldName;
            if(!preg_match('/^[a-zA-Z0-9]+$/', $field)) continue; // Fix sql injection.

            $fieldControl = $fieldParams[$field]['control'] ?? '';
            if($fieldControl == 'input' and $this->post->$valueName === '0') $this->post->set($valueName, 'ZERO');
            if($field == 'id' and $this->post->$valueName === '0') $this->post->set($valueName, 'ZERO');

            /* Skip empty values. */
            if($this->post->$valueName == false) continue;
            if($this->post->$valueName == 'ZERO') $this->post->$valueName = 0;   // ZERO is special, stands to 0.
            if($fieldControl == 'select' and $this->post->$valueName === 'null') $this->post->set($valueName, '');   // Null is special, stands to empty if control is select. Fix bug #3279.

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
                    $condition = $fieldControl == 'select' ? " LIKE CONCAT('%,', '{$value}', ',%')" : ' LIKE ' . $this->dbh->quote("%$value%");
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
                    $condition = $fieldControl == 'select' ? " NOT LIKE CONCAT('%,', '{$value}', ',%')" : ' NOT LIKE ' . $this->dbh->quote("%$value%");
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
            elseif(in_array($operator, array('include', 'notinclude')) && $fieldControl == 'select')
            {
                $where .= " $andOr CONCAT(',', `{$this->post->$fieldName}`, ',') {$condition}";
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
     * 转换 queryForm 格式以适应 buildQuery 的检查。
     * Convert queryForm to fit the buildQuery check.
     *
     * @param  array  $queryForm
     * @access public
     * @return array
     */
    public function convertQueryForm(array $queryForm): array
    {
        if(isset($queryForm['field1'])) return $queryForm;

        $convertedForm = array();
        foreach($queryForm as $i => $formItem)
        {
            $i++;
            if(isset($formItem['groupAndOr']))
            {
                $convertedForm['groupAndOr'] = $formItem['groupAndOr'];
            }
            elseif(isset($formItem['field']))
            {
                $convertedForm['field' . $i]    = $formItem['field'];
                $convertedForm['andOr' . $i]    = $formItem['andOr'];
                $convertedForm['operator' . $i] = $formItem['operator'];
                $convertedForm['value' . $i]    = $formItem['value'];
            }
        }
        return $convertedForm;
    }

    /**
     * 获取查询。
     * Get a query.
     *
     * @param  int    $queryID
     * @access public
     * @return object|bool
     */
    public function getQuery(int $queryID): object|bool
    {
        $queryID = (int)$queryID;
        $query   = $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;

        if(in_array($query->module, $this->config->search->oldQuery)) return $this->getOldQuery($queryID);

        /* Decode html encode. */
        $query->form = htmlspecialchars_decode($query->form, ENT_QUOTES);

        /* 如果搜索表单中值有变量，把表单值放到post 表单，重新生成 query。*/
        /* If form has variable, regenerate query. */
        $hasDynamic  = str_contains($query->form, '$');
        $query->form = unserialize($query->form);
        if($hasDynamic)
        {
            $_POST           = $this->convertQueryForm($query->form);
            $_POST['module'] = $query->module;

            $this->buildQuery();
            $querySessionName = $query->module . 'Query';
            $query->sql = $_SESSION[$querySessionName];
        }

        /* 将queryform[filed1] 转换为 queryform[1]['field']。*/
        /* Process queryform[filed1] to queryform[1]['field']. */
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
    public function getOldQuery(int $queryID)
    {
        $query = $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
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
        return $query;
    }


    /**
     * 将搜索条件保存到session中。
     * Save query to session.
     *
     * @param  string $module
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function setQuery(string $module, int $queryID = 0): string
    {
        $querySessionName = $module . 'Query';
        $formSessionName  = $module . 'Form';

        $queryID = (int)$queryID;
        if($queryID)
        {
            $query = $this->getQuery($queryID);
            if($query)
            {
                $this->session->set($querySessionName, $query->sql);
                $this->session->set($formSessionName, $query->form);
            }
            else
            {
                $this->session->set($querySessionName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->$querySessionName == false) $this->session->set($querySessionName, ' 1 = 1');
        }

        return $this->session->$querySessionName;
    }

    /**
     * 获取一个查询。
     * Get a query.
     *
     * @param  int          $queryID
     * @access public
     * @return object|false
     */
    public function getByID(int $queryID): object|false
    {
        return $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
    }

    /**
     * 保存当前的查询。
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
        if(in_array($query->module, array('epic', 'requirement'))) $query->module = 'story'; // 用需业需保存为story

        $this->dao->insert(TABLE_USERQUERY)->data($query)->autoCheck()->check('title', 'notempty')->exec();

        if(dao::isError()) return false;

        $queryID = $this->dao->lastInsertID();
        $this->loadModel('score')->create('search', 'saveQuery', $queryID);
        return $queryID;
    }

    /**
     * 删除保存的查询。
     * Delete current query from db.
     *
     * @param  int    $queryID
     * @access public
     * @return bool
     */
    public function deleteQuery(int $queryID): bool
    {
        $this->dao->delete()->from(TABLE_USERQUERY)->where('id')->eq($queryID)->andWhere('account')->eq($this->app->user->account)->exec();
        return !dao::isError();
    }

    /**
     * 获取id,title 的键值对。
     * Get title => id pairs of a user.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryPairs(string $module): array
    {
        $queries = $this->dao->select('id, title')->from(TABLE_USERQUERY)->where('module')->eq($module)->andWhere('account', true)->eq($this->app->user->account)->orWhere('common')->eq(1)->markRight(1)->orderBy('id_desc')->fetchPairs();

        return array('' => $this->lang->search->myQuery) + $queries;
    }

    /**
     * 获取查询列表。
     * Get query list.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryList(string $module): array
    {
        return $this->dao->select('id, account, title')
            ->from(TABLE_USERQUERY)
            ->where('module')->eq($module)
            ->andWhere('account', true)->eq($this->app->user->account)
            ->orWhere('common')->eq(1)
            ->markRight(1)
            ->orderBy('id_desc')
            ->fetchAll();
    }

    /**
     * 获取可访问的有索引的模块。
     * Get counts of keyword search results.
     *
     * @param  string|array $type
     * @access public
     * @return array
     */
    public function getListCount(array|string $type = 'all'): array
    {
        $allowedObjects = $this->searchTao->getAllowedObjects($type);

        $filterObjects = array();
        foreach($allowedObjects as $index => $object)
        {
            if(strpos(',feedback,ticket,', ",$object,") === false) continue;

            unset($allowedObjects[$index]);
            $filterObjects[] = $object;
        }

        $typeCount = $this->dao->select("objectType, COUNT(1) AS objectCount")->from(TABLE_SEARCHINDEX)
            ->where('((vision')->eq($this->config->vision)
            ->andWhere('objectType')->in($allowedObjects)
            ->markRight(1)
            ->orWhere('(objectType')->in($filterObjects)
            ->markRight(2)
            ->andWhere('addedDate')->le(helper::now())
            ->groupBy('objectType')
            ->fetchPairs();
        arsort($typeCount);
        return $typeCount;
    }

    /**
     * 获取搜索结果。
     * get search results of keywords.
     *
     * @param  string $keywords
     * @param  string $type
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $keywords, array|string $type, ?object $pager = null): array
    {
        $filter   = $this->app->loadClass('sqlfilter');
        $keywords = $filter->getRecommendedFilter($keywords, 'medium');

        list($words, $againstCond, $likeCondition) = $this->searchTao->getSqlParams($keywords);
        $allowedObjects = $this->searchTao->getAllowedObjects($type);

        $filterObjects = array();
        foreach($allowedObjects as $index => $object)
        {
            if(strpos(',feedback,ticket,', ",$object,") === false) continue;

            unset($allowedObjects[$index]);
            $filterObjects[] = $object;
        }

        if(in_array($this->config->db->driver, $this->config->pgsqlDriverList))
        {
            $table = "SELECT *, ts_rank(to_tsvector('pg_catalog.english', coalesce(title, '') || ' ' || coalesce(content, '')), to_tsquery('pg_catalog.english', '{$againstCond}'))  AS score FROM " . TABLE_SEARCHINDEX;
            $score = '0.02';
        }
        else
        {
            $table = "SELECT *, (MATCH(title, content) AGAINST('{$againstCond}' IN BOOLEAN MODE)) AS score FROM " . TABLE_SEARCHINDEX;
            $score = '1';
        }

        $stmt  = $this->dao->select('*')->from("({$table})")->alias('t1')
            ->where("(score >= {$score} {$likeCondition})")
            ->andWhere('((vision')->eq($this->config->vision)
            ->andWhere('objectType')->in($allowedObjects)
            ->markRight(1)
            ->orWhere('(objectType')->in($filterObjects)
            ->markRight(2)
            ->andWhere('addedDate')->le(helper::now())
            ->orderBy('score_desc, editedDate_desc')
            ->query();

        $results     = array();
        $idListGroup = array();
        while($record = $stmt->fetch())
        {
            $results[$record->id] = $record;

            $module = $record->objectType == 'case' ? 'testcase' : $record->objectType;
            $idListGroup[$module][$record->objectID] = $record->objectID;
        }

        $results = $this->searchTao->checkPriv($results, $idListGroup);
        if(empty($results)) return $results;

        /* Reset pager total and get this page data. */
        if($pager) $results = $this->searchTao->setResultsInPage($results, $pager);

        $objectList = $this->searchTao->getobjectList($idListGroup);
        return $this->processResults($results, $objectList, $words);
    }

    /**
     * 保存一个索引项。
     * Save an index item.
     *
     * @param  string $objectType article|blog|page|product|thread|reply|
     * @param  object $object
     * @access public
     * @return bool
     */
    public function saveIndex(string $objectType, object $object): bool
    {
        $fields = $this->config->search->fields->{$objectType};
        if(empty($fields)) return true;

        /* 如果是文档，将文档的内容追加上。*/
        /* If the objectType is doc, append the content of doc. */
        if($objectType == 'doc' && $this->config->edition != 'open') $object = $this->searchTao->appendFiles($object);

        $index = new stdclass();
        $index->objectType = $objectType;
        $index->objectID   = $object->{$fields->id};
        $index->title      = $object->{$fields->title};
        $index->addedDate  = !empty($object->{$fields->addedDate})  ? (!helper::isZeroDate($object->{$fields->addedDate})  ? $object->{$fields->addedDate}  : NULL) : NULL;
        $index->editedDate = !empty($object->{$fields->editedDate}) ? (!helper::isZeroDate($object->{$fields->editedDate}) ? $object->{$fields->editedDate} : NULL) : NULL;
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
        $contentSplited = $spliter->utf8Split(strip_tags($index->content));

        $index->title   = $titleSplited['words'];
        $index->content = $contentSplited['words'];

        $this->saveDict($titleSplited['dict'] + $contentSplited['dict']);
        $this->dao->delete()->from(TABLE_SEARCHINDEX)->where('objectType')->eq($index->objectType)->andWhere('objectID')->eq($index->objectID)->exec();
        $this->dao->insert(TABLE_SEARCHINDEX)->data($index)->exec();

        return !dao::isError();
    }

    /**
     * 保存搜索字典。
     * Save dict info.
     *
     * @param  array  $dict
     * @access public
     * @return bool
     */
    public function saveDict(array $dict): bool
    {
        static $savedDict;
        if(empty($savedDict)) $savedDict = $this->dao->select("`key`")->from(TABLE_SEARCHDICT)->fetchPairs();

        foreach($dict as $key => $value)
        {
            if(!is_numeric($key) || empty($value) || strlen($key) != 5 || $key < 0 || $key > 65535) continue;
            if(isset($savedDict[$key])) continue;

            $this->dao->replace(TABLE_SEARCHDICT)->data(array('key' => $key, 'value' => $value))->exec();
            $savedDict[$key] = $key;
        }

        return !dao::isError();
    }

    /**
     * 构建索引查询。
     * Build index query.
     *
     * @param  string $type
     * @param  bool   $testDelete
     * @access public
     * @return object
     */
    public function buildIndexQuery(string $type, bool $testDeleted = true): object
    {
        $table = $this->config->objectTables[$type];
        if($type == 'story' || $type == 'requirement' || $type == 'epic')
        {
            $query = $this->dao->select('DISTINCT t1.*, t2.spec, t2.verify')->from($table)->alias('t1')
                ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
                ->where('t1.deleted')->eq('0')
                ->andWhere('type')->eq($type)
                ->andWhere('t1.version=t2.version');
        }
        elseif($type == 'doc')
        {
            $query = $this->dao->select('DISTINCT t1.*, t2.content, t2.digest')->from($table)->alias('t1')->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc')->where('t1.deleted')->eq('0')->andWhere('t1.version=t2.version');
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
                ->beginIF(isset($data->deleted))->andWhere('t1.deleted')->eq('0')->fi();
        }
        return $query;
    }

    /**
     * 构建索引。
     * Build all search index.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return array
     */
    public function buildAllIndex(string $type = '', int $lastID = 0): array
    {
        /* 如果类型是空的，获取第一个类型，从第一个类型开始创建索引。*/
        /* If the type is empty, get the first type and create index starting from the first type. */
        if(empty($type))
        {
            $this->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
            $this->dao->delete()->from(TABLE_SEARCHDICT)->exec();
            try
            {
                $this->dbh->exec('ALTER TABLE ' . TABLE_SEARCHINDEX . ' auto_increment=1');
            }
            catch(Exception $e){}
            $type = key((array)$this->config->search->fields);
        }

        $limit      = 100;
        $nextObject = false;
        /* 获取某些字段值并且将其保存到索引。*/
        /* Get some field value and save it to index. */
        foreach($this->config->search->fields as $module => $field)
        {
            if($module != $type && !$nextObject) continue;
            if(!isset($this->config->objectTables[$module])) continue;

            if($module == $type) $nextObject = true;

            while(true)
            {
                $query    = $this->buildIndexQuery($module);
                $dataList = $query->beginIF($lastID)->andWhere('t1.id')->gt($lastID)->fi()->orderBy('t1.id')->limit($limit)->fetchAll('id', false);
                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                $dataList = $this->searchTao->processDataList($module, $field, $dataList);

                foreach($dataList as $data) $this->saveIndex($module, $data);
                return array('type' => $module, 'count' => count($dataList), 'lastID' => max(array_keys($dataList)));
            }
        }
        return array('finished' => true);
    }

    /**
     * 删除索引。
     * Delete index of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function deleteIndex(string $objectType, int $objectID): bool
    {
        $this->dao->delete()->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->exec();
        return !dao::isError();
    }
}
