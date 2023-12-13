<?php
class searchTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('search');
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
    public function buildQueryTest(array $searchConfig, array $postDatas, string $return = 'form'): array|string
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
     * @param  object $fieldParams
     * @param  string $field
     * @param  string $andOrName
     * @param  string $operatorName
     * @param  string $valueName
     * @access public
     * @return object
     */
    public function processQueryFormDatasTest(object $postData, string $field, string $andOrName, string $operatorName, string $valueName): object
    {
        $_POST = (array)$postData;

        global $tester;
        $tester->loadModel('bug');

        $fieldParams = json_decode(json_encode($tester->config->bug->search['params']));

        list($andOr, $operator, $value) = $this->objectModel->processQueryFormDatas($fieldParams, $field, $andOrName, $operatorName, $valueName);

        $queryFormData = new stdclass();
        $queryFormData->andOr    = $andOr;
        $queryFormData->operator = $operator;
        $queryFormData->value    = $value;

        return $queryFormData;
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
        $query->queryCount = $tester->dao->select('count(*) AS count')->from($table)->where($query->sql)->fetch('count');

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
    public function getByIDTest(int $queryID): array|object
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
    public function saveQueryTest(string $module, string $title, string $where, array $queryForm): object|array
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
    public function deleteQueryTest(int $queryID): int|array
    {
        $this->objectModel->deleteQuery($queryID);
        if(dao::isError()) return dao::getError();

        global $tester;
        $count = $tester->dao->select('count(*) AS count')->from(TABLE_USERQUERY)->fetch('count');
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
    public function getListTest(string $keywords, string|array $type): int|array
    {
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

        global $tester;
        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

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
    public function getListCountTest(string|array $type): array
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
    public function saveIndexTest(string $objectType, int $objectID): array|object
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
        return $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($type)->fetchAll();
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
        return $tester->dao->select('count(*) AS count')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetch('count');
    }

    /**
     * Test decode.
     *
     * @param  int    $key
     * @access public
     * @return string
     */
    public function decodeTest($key)
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

        $objects = $this->objectModel->decode($key);
        if(dao::isError()) return dao::getError();

        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        return $objects;
    }

    /**
     * Test get summary.
     *
     * @param  int    $indexID
     * @param  string $words
     * @access public
     * @return array
     */
    public function getSummaryTest($indexID, $words)
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

        $objects = $this->objectModel->getSummary($searchIndex->content, $words);
        if(dao::isError()) return dao::getError();

        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        return $objects;
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
    public function setConditionTest(string $field, string $operator, string|int $value): string
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
        $_SESSION['searchParams']['module'] = 'bug';

        return $this->objectModel->getParamValues($fields, $params);
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
    public function getAllowedObjectsTest(array|string $type, string $systemMode): array
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
            $data->comment = str_replace("\n", '', $data->comment);
            $data->desc    = str_replace("\n", '', $data->desc);
            $data->expect  = str_replace("\n", '', $data->expect);
        }

        return $dataList;
    }
}
