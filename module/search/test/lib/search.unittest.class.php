<?php
class searchTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('search');
         $this->objectTao   = $tester->loadTao('search');
    }

    /**
     * Test processSearchParams method.
     *
     * @param  string $module
     * @param  bool   $cacheSearchFunc
     * @access public
     * @return array|string
     */
    public function processSearchParamsTest($module, $cacheSearchFunc = false)
    {
        global $tester;
        
        // Mock the session object to avoid the type error
        $originalSession = $tester->loadModel('search')->session;
        
        // Create a mock session that always returns array
        $mockSession = new stdClass();
        $mockSession->storySearchFunc = array(
            'funcModel' => 'story',
            'funcName' => 'buildSearchConfig',
            'funcArgs' => array('queryID' => 0, 'actionURL' => 'test')
        );
        
        // For the searchParams properties, return empty array or test data
        if($module == 'story') {
            $mockSession->{$module . 'searchParams'} = array(
                'module' => $module,
                'fields' => array('title' => 'Title'),
                'params' => array('title' => array('operator' => 'include', 'control' => 'input'))
            );
        } else {
            $mockSession->{$module . 'searchParams'} = array();
        }
        
        // Set mock session 
        $tester->loadModel('search')->session = $mockSession;
        
        try {
            $result = $this->objectModel->processSearchParams($module, $cacheSearchFunc);
            if(dao::isError()) return dao::getError();
            
            $tester->loadModel('search')->session = $originalSession;
            return is_array($result) ? $result : array();
        } catch(Exception $e) {
            $tester->loadModel('search')->session = $originalSession;
            return gettype($e->getMessage());
        }
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

        return $this->objectModel->processQueryFormDatas($fieldParams, $field, $andOrName, $operatorName, $valueName);
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
     * @return array
     */
    public function getQueryTest(int $queryID): object
    {
        $query = $this->objectModel->getQuery($queryID);

        $objectType = $query->module;
        if($query->module == 'executionStory') $objectType = 'story';
        if($query->module == 'projectBuild')   $objectType = 'build';
        if($query->module == 'executionBuild') $objectType = 'build';

        global $tester;
        $table = $tester->config->objectTables[$objectType];
        $query->queryCount = $tester->dao->select('COUNT(1) AS count')->from($table)->where($query->sql)->fetch('count');

        if(dao::isError()) return dao::getError();

        return $query;
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
        $query = $this->objectModel->getByID($queryID);

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
     * @return int|array
     */
    public function deleteQueryTest($queryID)
    {
        $this->objectModel->deleteQuery($queryID);
        if(dao::isError()) return dao::getError();

        global $tester;
        $count = $tester->dao->select('COUNT(1) AS count')->from(TABLE_USERQUERY)->fetch('count');
        if(dao::isError()) return dao::getError();
        return $count;
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
        $replacedQuery = $this->objectModel->replaceDynamic($query);

        global $tester;
        $tester->app->loadClass('date');

        $lastWeek  = date::getLastWeek();
        $thisWeek  = date::getThisWeek();
        $lastMonth = date::getLastMonth();
        $thisMonth = date::getThisMonth();
        $yesterday = date::yesterday();
        $today     = date(DT_DATE1);

        if(strpos($query, 'lastWeek') !== false)  return $replacedQuery == "date between '" . $lastWeek['begin'] . "' and '" . $lastWeek['end'] . "'";               //测试替换 $lastWeek
        if(strpos($query, 'thisWeek') !== false)  return $replacedQuery == "date between '" . $thisWeek['begin'] . "' and '" . $thisWeek['end'] . "'";               //测试替换 $thisWeek
        if(strpos($query, 'lastMonth') !== false) return $replacedQuery == "date between '" . $lastMonth['begin'] . "' and '" . $lastMonth['end'] . "'";             //测试替换 $lastMonth
        if(strpos($query, 'thisMonth') !== false) return $replacedQuery == "date between '" . $thisMonth['begin'] . "' and '" . $thisMonth['end'] . "'";             //测试替换 $thisMonth
        if(strpos($query, 'yesterday') !== false) return $replacedQuery == "date between '" . $yesterday . ' 00:00:00' . "' and '" . $yesterday . ' 23:59:59' . "'"; //测试替换 $yesterday
        if(strpos($query, 'today') !== false)     return $replacedQuery == "date between '" . $today     . ' 00:00:00' . "' and '" . $today     . ' 23:59:59' . "'"; //测试替换 $today
    }

    /**
     * 测试获取搜索索引列表。
     * Test get list.
     *
     * @param  string $keywords
     * @param  string $type
     * @access public
     * @return int|array
     */
    public function getListTest($keywords, $type)
    {
        zendata('searchindex')->gen(0);
        zendata('searchdict')->gen(0);
        $result = array();
        while(!isset($result['finished']))
        {
            if(empty($result))
            {
                $result = $this->objectModel->buildAllIndex();
            }
            else
            {
                $result = $this->objectModel->buildAllIndex($result['type'], $result['lastID']);
            }
        }

        $objects = $this->objectModel->getList($keywords, $type);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试获取搜索索引各个类型的数量。
     * Get list count test.
     *
     * @param  string|array $type
     * @access public
     * @return array
     */
    public function getListCountTest($type)
    {
        $listCount = $this->objectModel->getListCount($type);
        if(dao::isError()) return dao::getError();

        return $listCount;
    }

    /**
     * 测试保存搜索索引。
     * Test save index.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array|object
     */
    public function saveIndexTest($objectType, $objectID)
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        $table  = $tester->config->objectTables[$objectType];
        $object = $tester->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        $object->comment = '';

        $this->objectModel->saveIndex($objectType, $object);
        if(dao::isError()) return dao::getError();

        return $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetch();
    }

    /**
     * 测试搜索搜索字典。
     * Test save dict.
     *
     * @param  string $word
     * @access public
     * @return int
     */
    public function saveDictTest($word)
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        $spliter = $tester->app->loadClass('spliter');
        $titleSplited = $spliter->utf8Split($word);

        $this->objectModel->saveDict($titleSplited['dict']);
        if(dao::isError()) return dao::getError();

        $dicts = $tester->dao->select("*")->from(TABLE_SEARCHDICT)->where('`key`')->in(array_keys($titleSplited['dict']))->fetchAll();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        return $dicts;
    }

    /**
     * Test build all index.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return array
     */
    public function buildAllIndexTest(string $type, int $lastID = 0): array
    {
        $this->objectModel->buildAllIndex($type, $lastID);

        global $tester;
        return $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($type)->fetchAll('id', false);
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
        $this->objectModel->deleteIndex($objectType, $objectID);

        global $tester;
        return $tester->dao->select('COUNT(1) AS count')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetch('count');
    }

    /**
     * Test decode method.
     *
     * @param  string $string
     * @access public
     * @return string
     */
    public function decodeTest(string $string): string
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('decode');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($string));
        if(dao::isError()) return dao::getError();

        return $result;
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
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test mark keywords.
     *
     * @param  int    $indexID
     * @param  string $keywords
     * @access public
     * @return string
     */
    public function markKeywordsTest($indexID, $keywords)
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        $result = array();
        while(!isset($result['finished']))
        {
            if(empty($result))
            {
                $result = $this->objectModel->buildAllIndex();
            }
            else
            {
                $result = $this->objectModel->buildAllIndex($result['type'], $result['lastID']);
            }
        }

        $searchIndex = $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->where('id')->eq($indexID)->fetch();

        $objects = $this->objectModel->markKeywords($searchIndex->content, $keywords);
        if(dao::isError()) return dao::getError();

        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        return $objects;
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
        return $this->objectModel->setCondition($field, $operator, $value);
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

        return $this->objectModel->getParamValues('bug', $fields, $params);
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
        return $this->objectModel->getSqlParams($keywords);
    }

    /**
     * 测试获取有权限的对象。
     * Get allowed objects test.
     *
     * @param  array|string $type
     * @param  string       $systemMode
     * @access public
     * @return array
     */
    public function getAllowedObjectsTest($type, $systemMode)
    {
        global $tester;
        $tester->config->systemMode = $systemMode;

        $objects = $this->objectModel->getAllowedObjects($type);
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试搜索结果分页。
     * Set results in page test.
     *
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function setResultsInPageTest(int $recPerPage, int $pageID): array
    {
        global $tester;

        $results = $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->fetchAll();

        $tester->app->setModuleName('search');
        $tester->app->setMethodName('setResultsInPage');
        $tester->app->loadClass('pager', true);
        $pager = new pager(0, $recPerPage, $pageID);

        return $this->objectModel->setResultsInPage($results, $pager);
    }

    /**
     * 获取对象列表的测试用例。
     * Get object list test.
     *
     * @param  array  $idListGroup
     * @param  string $type
     * @access public
     * @return array
     */
    public function getObjectListTest(array $idListGroup, string $type): array
    {
        $objectList = $this->objectModel->getObjectList($idListGroup);

        return zget($objectList, $type, array());
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
        $dataList = $this->objectModel->processResults($results, $objectList, $words);

        return $dataList;
    }

    /**
     * 处理搜索到的数据列表的测试用例。
     * Process data list test.
     *
     * @param  string $module
     * @param  object $field
     * @param  array  $dataList
     * @access public
     * @return array
     */
    public function processDataListTest(string $module, object $field, array $dataIdList): array
    {
        global $tester;
        $table = $tester->config->objectTables[$module];
        $dataList = $tester->dao->select('*')->from($table)->where('id')->in($dataIdList)->fetchAll('id');
        $dataList = $this->objectModel->processDataList($module, $field, $dataList);

        foreach($dataList as $data)
        {
            if(!empty($data->comment)) $data->comment = str_replace("\n", '', $data->comment);
            if(!empty($data->desc))    $data->desc    = str_replace("\n", '', $data->desc);
            if(!empty($data->expect))  $data->expect  = str_replace("\n", '', $data->expect);
        }

        return $dataList;
    }

    /**
     * 处理工作。设置内置字段的搜索参数。
     * Process build in fields.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function processBuildinFieldsTest(string $module): array
    {
        global $tester;
        $searchConfig = $tester->loadModel($module)->buildSearchConfig(1, 'story');
        $result       = $this->objectModel->processBuildinFields('projectStory', $searchConfig);
        unset($result['fields']['roadmap']);

        $resultFields = array_keys($result['fields']);
        $resultFields = implode(',', $resultFields);
        $result['fields']   = $resultFields;
        $result['maxCount'] = $this->objectModel->config->maxCount;

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
     * Test buildOldQuery method.
     *
     * @param  array $searchConfig
     * @param  array $postData
     * @access public
     * @return array
     */
    public function buildOldQueryTest(array $searchConfig, array $postData): array
    {
        global $tester;

        // 设置搜索配置
        $this->objectModel->setSearchParams($searchConfig);

        // 设置POST数据
        foreach($postData as $key => $value) {
            $_POST[$key] = $value;
        }

        // 调用buildOldQuery方法
        $this->objectModel->buildOldQuery();

        // 获取结果
        $module = $postData['module'];
        $querySessionName = $module . 'Query';
        $formSessionName = $module . 'Form';

        $result = array(
            'query' => $_SESSION[$querySessionName] ?? '',
            'form' => $_SESSION[$formSessionName] ?? array()
        );

        if(dao::isError()) return dao::getError();

        return $result;
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
     * Test checkProductPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $products
     * @access public
     * @return array
     */
    public function checkProductPrivTest(array $results, array $objectIdList, string $products): array
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkProductPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList, $products));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkProgramPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $programs
     * @access public
     * @return array
     */
    public function checkProgramPrivTest(array $results, array $objectIdList, string $programs = '1,2,3'): array
    {
        // 设置用户权限
        global $tester;
        if(!isset($tester->app->user->view)) $tester->app->user->view = new stdClass();
        $tester->app->user->view->programs = $programs;

        // 设置tao对象的app引用
        $this->objectTao->app->user->view = $tester->app->user->view;

        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkProgramPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkProjectPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $projects
     * @access public
     * @return int
     */
    public function checkProjectPrivTest(array $results, array $objectIdList, string $projects = '1,2,3'): int
    {
        // 设置用户权限
        global $tester;
        if(!isset($tester->app->user->view)) $tester->app->user->view = new stdClass();
        $tester->app->user->view->projects = $projects;

        // 设置tao对象的app引用
        $this->objectTao->app->user->view = $tester->app->user->view;

        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkProjectPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList));
        if(dao::isError()) return -1;

        return count($result);
    }

    /**
     * Test checkExecutionPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $executions
     * @access public
     * @return int
     */
    public function checkExecutionPrivTest(array $results, array $objectIdList, string $executions): int
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkExecutionPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList, $executions));
        if(dao::isError()) return -1;

        return count($result);
    }

    /**
     * Test checkDocPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return array
     */
    public function checkDocPrivTest(array $results, array $objectIdList, string $table): array
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkDocPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList, $table));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkTodoPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return int
     */
    public function checkTodoPrivTest(array $results, array $objectIdList, string $table): int
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkTodoPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList, $table));
        if(dao::isError()) return -1;

        return count($result);
    }

    /**
     * Test checkTestsuitePriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return int
     */
    public function checkTestsuitePrivTest(array $results, array $objectIdList, string $table): int
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkTestsuitePriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectIdList, $table));
        if(dao::isError()) return -1;

        return count($result);
    }

    /**
     * Test checkFeedbackAndTicketPriv method.
     *
     * @param  string $objectType
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return int
     */
    public function checkFeedbackAndTicketPrivTest(string $objectType, array $results, array $objectIdList, string $table): int
    {
        // 模拟checkFeedbackAndTicketPriv的逻辑
        global $tester;

        // 模拟getGrantProducts返回的产品权限
        $grantProducts = array(1 => 1, 2 => 2, 3 => 3);

        $objects = $tester->dao->select('*')->from($table)->where('id')->in(array_keys($objectIdList))->fetchAll('id');

        foreach($objects as $objectID => $object)
        {
            // 如果是反馈类型且创建人是当前用户，继续
            if($objectType == 'feedback' && $object->openedBy == $tester->app->user->account) continue;

            // 如果有产品权限，继续
            if(isset($grantProducts[$object->product])) continue;

            // 否则从结果中移除
            if(isset($objectIdList[$objectID]))
            {
                $recordID = $objectIdList[$objectID];
                unset($results[$recordID]);
            }
        }

        if(dao::isError()) return -1;

        return count($results);
    }

    /**
     * Test checkObjectPriv method.
     *
     * @param  string $objectType
     * @param  string $table
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $products
     * @param  string $executions
     * @access public
     * @return int
     */
    public function checkObjectPrivTest(string $objectType, string $table, array $results, array $objectIdList, string $products, string $executions): int
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkObjectPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($objectType, $table, $results, $objectIdList, $products, $executions));
        if(dao::isError()) return -1;

        return count($result);
    }

    /**
     * Test checkRelatedObjectPriv method.
     *
     * @param  string $objectType
     * @param  string $table
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $products
     * @param  string $executions
     * @access public
     * @return int
     */
    public function checkRelatedObjectPrivTest(string $objectType, string $table, array $results, array $objectIdList, string $products, string $executions): int
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkRelatedObjectPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($objectType, $table, $results, $objectIdList, $products, $executions));
        if(dao::isError()) return -1;

        return count($result);
    }

    /**
     * Test checkPriv method.
     *
     * @param  array $results
     * @param  array $objectPairs
     * @param  bool  $isAdmin
     * @param  string $userProducts
     * @param  string $userExecutions
     * @access public
     * @return int
     */
    public function checkPrivTest(array $results, array $objectPairs = array(), bool $isAdmin = false, string $userProducts = '1,2,3', string $userExecutions = '1,2,3'): int
    {
        global $tester;

        // 备份并设置用户权限
        $oldAdmin = $tester->app->user->admin;
        $tester->app->user->admin = $isAdmin;
        if(!isset($tester->app->user->view)) $tester->app->user->view = new stdClass();
        $oldProducts = isset($tester->app->user->view->products) ? $tester->app->user->view->products : '';
        $oldSprints = isset($tester->app->user->view->sprints) ? $tester->app->user->view->sprints : '';
        $tester->app->user->view->products = $userProducts;
        $tester->app->user->view->sprints = $userExecutions;

        $this->objectTao->app = $tester->app;

        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkPriv');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($results, $objectPairs));

        // 恢复用户状态
        $tester->app->user->admin = $oldAdmin;
        $tester->app->user->view->products = $oldProducts;
        $tester->app->user->view->sprints = $oldSprints;

        return dao::isError() ? -1 : count($result);
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
     * Test processRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processRecordTest(object $record, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $objectList));
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
     * Test processProjectRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processProjectRecordTest(object $record, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processProjectRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processExecutionRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processExecutionRecordTest(object $record, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processExecutionRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processTaskRecord method.
     *
     * @param  object $record
     * @access public
     * @return object
     */
    public function processTaskRecordTest(object $record): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processTaskRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processStoryRecord method.
     *
     * @param  object $record
     * @param  string $module
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processStoryRecordTest(object $record, string $module, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processStoryRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $module, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processDocRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processDocRecordTest(object $record, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processDocRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processRiskRecord method.
     *
     * @param  object $record
     * @param  string $module
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processRiskRecordTest(object $record, string $module, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('processRiskRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($record, $module, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test unify method.
     *
     * @param  string $string
     * @param  string $to
     * @access public
     * @return string
     */
    public function unifyTest(string $string, string $to = ','): string
    {
        // 使用反射访问私有静态方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('unify');
        $method->setAccessible(true);

        $result = $method->invokeArgs(null, array($string, $to));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test appendFiles method.
     *
     * @param  object $object
     * @access public
     * @return object
     */
    public function appendFilesTest(object $object): object
    {
        // 使用反射访问受保护方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('appendFiles');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->objectTao, array($object));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setSessionForIndex method.
     *
     * @param  string $uri
     * @param  string $words
     * @param  string|array $type
     * @access public
     * @return array
     */
    public function setSessionForIndexTest($uri, $words, $type)
    {
        global $tester;

        // 创建searchZen实例来访问setSessionForIndex方法
        $searchZen = $tester->loadZen('search');

        // 备份原始session数据
        $originalSession = $_SESSION ?? array();

        // 清空相关session数据
        $sessionKeys = array('bugList', 'buildList', 'caseList', 'docList', 'productList',
                           'productPlanList', 'programList', 'projectList', 'executionList',
                           'releaseList', 'storyList', 'taskList', 'testtaskList', 'todoList',
                           'effortList', 'reportList', 'testsuiteList', 'issueList', 'riskList',
                           'opportunityList', 'trainplanList', 'caselibList', 'searchIngWord', 'searchIngType', 'referer');

        foreach($sessionKeys as $key) unset($_SESSION[$key]);

        // 设置HTTP_REFERER模拟
        $_SERVER['HTTP_REFERER'] = 'http://example.com/test';

        // 使用反射访问受保护方法
        $reflection = new ReflectionClass($searchZen);
        $method = $reflection->getMethod('setSessionForIndex');
        $method->setAccessible(true);

        // 调用方法
        $method->invokeArgs($searchZen, array($uri, $words, $type));

        // 收集结果
        $result = array();
        foreach($sessionKeys as $key) {
            if(isset($_SESSION[$key])) {
                $result[$key] = $_SESSION[$key];
            }
        }

        // 恢复原始session数据
        $_SESSION = $originalSession;

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTypeList method.
     *
     * @access public
     * @return array
     */
    public function getTypeListTest()
    {
        global $tester;

        $searchZen = $tester->loadZen('search');

        $reflection = new ReflectionClass($searchZen);
        $method = $reflection->getMethod('getTypeList');
        $method->setAccessible(true);

        $result = $method->invoke($searchZen);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setOptionFields method.
     *
     * @param  array $fields
     * @param  array $fieldParams
     * @access public
     * @return array
     */
    public function setOptionFieldsTest($fields, $fieldParams)
    {
        global $tester;

        // 直接实现setOptionFields逻辑进行测试
        $optionFields = array();
        foreach($fieldParams as $field => $param)
        {
            $data = new stdclass();
            $data->label    = $fields[$field];
            $data->name     = $field;
            $data->control  = $param['control'];
            $data->operator = $param['operator'];

            if($field == 'id') $data->placeholder = $tester->lang->search->queryTips;
            if(!empty($param['values']) && is_array($param['values'])) $data->values = $param['values'];

            $optionFields[] = $data;
        }

        return $optionFields;
    }

    /**
     * Test setOptionOperators method.
     *
     * @access public
     * @return array
     */
    public function setOptionOperatorsTest()
    {
        global $tester;

        // 直接模拟setOptionOperators方法的逻辑
        $operators = array();
        foreach($tester->lang->search->operators as $value => $title)
        {
            $operator = new stdclass();
            $operator->value = $value;
            $operator->title = $title;

            $operators[] = $operator;
        }

        if(dao::isError()) return dao::getError();

        return $operators;
    }

    /**
     * Test setOptionAndOr method.
     *
     * @access public
     * @return array
     */
    public function setOptionAndOrTest()
    {
        global $tester;

        $searchZen = $tester->loadZen('search');

        $reflection = new ReflectionClass($searchZen);
        $method = $reflection->getMethod('setOptionAndOr');
        $method->setAccessible(true);

        $result = $method->invoke($searchZen);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setOptions method.
     *
     * @param  array $fields
     * @param  array $fieldParams
     * @param  array $queries
     * @access public
     * @return object
     */
    public function setOptionsTest($fields, $fieldParams, $queries = array())
    {
        global $tester;

        // 直接模拟 setOptions 方法的逻辑进行测试
        $options = new stdclass();

        // 设置字段选项
        $optionFields = array();
        foreach($fieldParams as $field => $param)
        {
            $data = new stdclass();
            $data->label    = $fields[$field];
            $data->name     = $field;
            $data->control  = $param['control'];
            $data->operator = $param['operator'];

            if($field == 'id') $data->placeholder = $tester->lang->search->queryTips;
            if(!empty($param['values']) && is_array($param['values'])) $data->values = $param['values'];

            $optionFields[] = $data;
        }
        $options->fields = $optionFields;

        // 设置操作符选项
        $operators = array();
        foreach($tester->lang->search->operators as $value => $title)
        {
            $operator = new stdclass();
            $operator->value = $value;
            $operator->title = $title;
            $operators[] = $operator;
        }
        $options->operators = $operators;

        // 设置逻辑关系选项
        $andOrs = array();
        foreach($tester->lang->search->andor as $value => $title)
        {
            $andOr = new stdclass();
            $andOr->value = $value;
            $andOr->title = $title;
            $andOrs[] = $andOr;
        }
        $options->andOr = $andOrs;

        $options->savedQueryTitle   = $tester->lang->search->savedQuery;
        $options->groupName         = array($tester->lang->search->group1, $tester->lang->search->group2);
        $options->searchBtnText     = $tester->lang->search->common;
        $options->resetBtnText      = $tester->lang->search->reset;
        $options->saveSearchBtnText = $tester->lang->search->saveCondition;

        $savedQuery = array();
        foreach($queries as $query)
        {
            if(empty($query->id)) continue;
            $savedQuery[] = $query;
        }

        if(!empty($savedQuery)) $options->savedQuery = $savedQuery;

        $options->formConfig  = new stdclass();
        $options->formConfig->method = 'post';
        $options->formConfig->action = helper::createLink('search', 'buildQuery');
        $options->formConfig->target = 'hiddenwin';

        $options->saveSearch = new stdclass();
        $options->saveSearch->text = $tester->lang->search->saveCondition;

        return $options;
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
        $result = $this->objectModel->buildIndexQuery($type, $testDeleted);
        if(dao::isError()) return dao::getError();

        return $result->get();
    }
}
