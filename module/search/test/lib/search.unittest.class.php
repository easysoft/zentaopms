<?php
declare(strict_types = 1);
class searchTest
{
    private $objectModel;
    private $objectTao;

    public function __construct()
    {
        global $tester;

        $this->objectModel = $tester->loadModel('search');
        $this->objectTao   = $tester->loadTao('search');
    }

    /**
     * 设置搜索参数的测试用例。
     * Test set search params.
     *
     * @param  array $searchConfig
     * @access public
     * @return array
     */
    public function setSearchParams(array $searchConfig): array
    {
        $this->objectModel->setSearchParams($searchConfig);

        $module = $searchConfig['module'];
        $searchParamsName = $module . 'searchParams';

        return $_SESSION[$searchParamsName];
    }

    /**
     * 测试生成查询表单和查询语句。
     * Test build query.
     *
     * @param  array  $searchConfig
     * @param  array  $postDatas
     * @param  string $return
     * @access public
     * @return array|string
     */
    public function buildQueryTest($searchConfig, $postDatas, $return = 'form')
    {
        $this->objectModel->setSearchParams($searchConfig);

        $module = $searchConfig['module'];
        $_SESSION['searchParams']['module'] = $module;

        $_POST['module'] = $module;

        foreach($postDatas as $postData)
        {
            foreach($postData as $postKey => $postValue) $_POST[$postKey] = $postValue;
        }

        $this->objectModel->buildQuery();

        if($return == 'form')
        {
            $formSessionName = $module . 'Form';
            return $_SESSION[$formSessionName];
        }

        $querySessionName = $module . 'Query';
        return $_SESSION[$querySessionName];
    }

    /**
     * 测试生成查询表单数据。
     * Process query form datas test.
     *
     * @param  array  $fieldParams
     * @param  string $field
     * @param  string $andOrName
     * @param  string $operatorName
     * @param  string $valueName
     * @access public
     * @return object
     */
    public function processQueryFormDatasTest(array $fieldParams, string $field, string $andOrName, string $operatorName, string $valueName): array
    {
        $_POST = $fieldParams;

        return $this->objectTao->processQueryFormDatas($fieldParams, $field, $andOrName, $operatorName, $valueName);
    }

    /**
     * 测试初始化 session。
     * Test init session function.
     *
     * @param  string $module
     * @param  array  $fields
     * @param  array  $fieldParams
     * @access public
     * @return array
     */
    public function initSessionTest(string $module, array $fields, array $fieldParams): array
    {
        return $this->objectModel->initSession($module, $fields, $fieldParams);
    }

    /**
     * 测试获取查询。
     * Test get query.
     *
     * @param  int    $queryID
     * @access public
     * @return mixed
     */
    public function getQueryTest(int $queryID)
    {
        $result = $this->objectModel->getQuery($queryID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试设置查询语句。
     * Set query test.
     *
     * @param  string $module
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function setQueryTest(string $module, int $queryID): string
    {
        return $this->objectModel->setQuery($module, $queryID);
    }

    /**
     * 测试根据 ID 获取查询。
     * Test get by ID.
     *
     * @param  int    $queryID
     * @access public
     * @return array|object
     */
    public function getByIDTest($queryID)
    {
        $query = $this->objectModel->getByID((int)$queryID);
        if(dao::isError()) return dao::getError();

        return $query;
    }

    /**
     * 保存查询的测试。
     * Test save query.
     *
     * @param  string  $module
     * @param  string  $title
     * @param  string  $where
     * @param  array  $queryForm
     * @access public
     * @return object|array
     */
    public function saveQueryTest($module, $title, $where, $queryForm)
    {
        $_POST['module'] = $module;
        $_POST['title']  = $title;
        $_SESSION[$module . 'Query'] = $where;
        $_SESSION[$module . 'Form']  = $queryForm;

        $queryID = $this->objectModel->saveQuery();
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($queryID);
    }


    /**
     * 测试删除搜索查询。
     * Test delete query.
     *
     * @param  int    $queryID
     * @access public
     * @return bool|string
     */
    public function deleteQueryTest($queryID)
    {
        $result = $this->objectModel->deleteQuery($queryID);
        if(dao::isError()) return dao::getError();

        return $result ? 'true' : 'false';
    }

    /**
     * 测试获取查询键值对。
     * Test get query pairs.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryPairsTest(string $module): array
    {
        $objects = $this->objectModel->getQueryPairs($module);
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取查询列表。
     * Test get query list.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryListTest(string $module): array
    {
        $queryList = $this->objectModel->getQueryList($module);
        if(dao::isError()) return dao::getError();

        return $queryList;
    }

    /**
     * 测试替换日期和用户变量。
     * Replace dynamic test.
     *
     * @param  string $query
     * @access public
     * @return string
     */
    public function replaceDynamicTest(string $query): string
    {
        $replacedQuery = $this->objectTao->replaceDynamic($query);
        if(dao::isError()) return dao::getError();

        global $tester;
        $tester->app->loadClass('date');

        $lastWeek  = date::getLastWeek();
        $thisWeek  = date::getThisWeek();
        $lastMonth = date::getLastMonth();
        $thisMonth = date::getThisMonth();
        $yesterday = date::yesterday();
        $today     = date(DT_DATE1);

        if(strpos($query, 'lastWeek') !== false)  return ($replacedQuery == "date between '" . $lastWeek['begin'] . "' and '" . $lastWeek['end'] . "'") ? '1' : '0';
        if(strpos($query, 'thisWeek') !== false)  return ($replacedQuery == "date between '" . $thisWeek['begin'] . "' and '" . $thisWeek['end'] . "'") ? '1' : '0';
        if(strpos($query, 'lastMonth') !== false) return ($replacedQuery == "date between '" . $lastMonth['begin'] . "' and '" . $lastMonth['end'] . "'") ? '1' : '0';
        if(strpos($query, 'thisMonth') !== false) return ($replacedQuery == "date between '" . $thisMonth['begin'] . "' and '" . $thisMonth['end'] . "'") ? '1' : '0';
        if(strpos($query, 'yesterday') !== false) return ($replacedQuery == "date between '" . $yesterday . ' 00:00:00' . "' and '" . $yesterday . ' 23:59:59' . "'") ? '1' : '0';
        if(strpos($query, 'today') !== false)     return ($replacedQuery == "date between '" . $today     . ' 00:00:00' . "' and '" . $today     . ' 23:59:59' . "'") ? '1' : '0';
        if(strpos($query, '$@me') !== false)      return str_replace('$@me', $tester->app->user->account, $query);

        return $replacedQuery;
    }

    /**
     * 测试获取搜索索引列表。
     * Test get list.
     *
     * @param  string $keywords
     * @param  array|string $type
     * @param  object|null $pager
     * @access public
     * @return int|array
     */
    public function getListTest($keywords, $type, $pager = null)
    {
        $objects = $this->objectModel->getList($keywords, $type, $pager);
        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test build all index.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return mixed
     */
    public function buildAllIndexTest(string $type = '', int $lastID = 0)
    {
        $result = $this->objectModel->buildAllIndex($type, $lastID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试删除搜索索引。
     * Delete index test.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function deleteIndexTest(string $objectType, int $objectID): int
    {
        // 缓冲输出，避免zenData输出干扰测试结果
        ob_start();
        $result = $this->objectModel->deleteIndex($objectType, $objectID);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        global $tester;
        $count = $tester->dao->select('COUNT(1) AS count')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetch('count');

        return intval($count);
    }

    /**
     * Test getSummary method.
     *
     * @param  string $content
     * @param  string $words
     * @access public
     * @return string
     */
    public function getSummaryTest(string $content, string $words): string
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getSummary');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($content, $words));
        if(function_exists('dao') && dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试设置搜索条件。
     * Set condition test.
     *
     * @param  string     $field
     * @param  string     $operator
     * @param  string|int $value
     * @access public
     * @return string
     */
    public function setConditionTest($field, $operator, $value)
    {
        // setCondition method is in tao layer and is protected, use reflection to access it
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('setCondition');
        $method->setAccessible(true);
        return $method->invoke($this->objectTao, $field, $operator, $value);
    }

    /**
     * 测试设置搜索条件。
     * Set where test.
     *
     * @access public
     * @return string
     */
    public function setWhereTest(string $field, string $operator, string $value, string $andOr): string
    {
        $where = '';
        return $this->objectModel->setWhere($where, $field, $operator, $value, $andOr);
    }

    /**
     * 获取变量参数数据的测试用例。
     * Get param values test.
     *
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function getParamValuesTest(array $fields, array $params): array
    {
        $_SESSION['project'] = 0;

        return $this->objectTao->getParamValues('bug', $fields, $params);
    }

    /**
     * 获取查询语句的参数的测试用例。
     * Get sql params test.
     *
     * @param  string $keywords
     * @access public
     * @return array
     */
    public function getSqlParamsTest(string $keywords): array
    {
        return $this->objectTao->getSqlParams($keywords);
    }

    /**
     * 测试处理搜索结果。
     * Process results test.
     *
     * @param  array  $results
     * @param  array  $objectList
     * @param  string $words
     * @access public
     * @return array
     */
    public function processResultsTest(array $results, array $objectList, string $words): array
    {
        $result = $this->objectModel->processResults($results, $objectList, $words);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 设置默认搜索参数配置。
     * Test: set default search params.
     *
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function setDefaultParamsTest(array $fields, array $params): string
    {
        $_SESSION['project'] = 0;

        $result = $this->objectModel->setDefaultParams('bug', $fields, $params);
        $field  = key($result);
        $value  = zget($result[$field], 'values', array());

        $return = implode(',', array_keys($value));

        return str_replace('@', '', $return);
    }

    /**
     * Test convertQueryForm method.
     *
     * @param  array $queryForm
     * @access public
     * @return array
     */
    public function convertQueryFormTest(array $queryForm): array
    {
        $result = $this->objectModel->convertQueryForm($queryForm);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getOldQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return mixed
     */
    public function getOldQueryTest(int $queryID)
    {
        $result = $this->objectModel->getOldQuery($queryID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initOldSession method.
     *
     * @param  string $module
     * @param  array  $fields
     * @param  array  $fieldParams
     * @param  bool   $clearSession
     * @access public
     * @return array
     */
    public function initOldSessionTest(string $module, array $fields, array $fieldParams, bool $clearSession = true): array
    {
        $formSessionName = $module . 'Form';

        // 根据参数决定是否清理session数据
        if($clearSession) {
            unset($_SESSION[$formSessionName]);
        }

        $this->objectTao->initOldSession($module, $fields, $fieldParams);

        if(dao::isError()) return dao::getError();

        return $_SESSION[$formSessionName] ?? array();
    }

    /**
     * Test markKeywords method directly.
     *
     * @param  string $content
     * @param  string $keywords
     * @access public
     * @return string
     */
    public function markKeywordsDirectTest(string $content, string $keywords): string
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('markKeywords');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($content, $keywords));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processIssueRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processIssueRecordTest(object $record, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processIssueRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildIndexQuery method.
     *
     * @param  string $type
     * @param  bool   $testDeleted
     * @access public
     * @return string
     */
    public function buildIndexQueryTest(string $type, bool $testDeleted = true): string
    {
        try {
            $result = $this->objectModel->buildIndexQuery($type, $testDeleted);
            if(dao::isError()) return dao::getError();

            // 获取SQL查询字符串
            $sql = $result->get();

            // 如果返回的是array，可能是ZenData错误信息，直接返回错误
            if(is_array($sql)) {
                return 'Error: ' . implode(' ', $sql);
            }

            return $sql;
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage();
        }
    }
}
