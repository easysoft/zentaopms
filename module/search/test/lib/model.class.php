<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class searchModelTest extends baseTest
{
    protected $moduleName = 'search';
    protected $className  = 'model';

    /**
     * Test buildOldQuery method.
     *
     * @access public
     * @return mixed
     */
    public function buildOldQueryTest()
    {
        $result = $this->invokeArgs('buildOldQuery', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOldQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return object|false
     */
    public function getOldQueryTest(int $queryID)
    {
        $result = $this->invokeArgs('getOldQuery', [$queryID]);
        if(dao::isError()) return dao::getError();
        return $result;
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
        $result = $this->instance->buildAllIndex($type, $lastID);
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
            $result = $this->instance->buildIndexQuery($type, $testDeleted);
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
        $this->instance->setSearchParams($searchConfig);

        $module = $searchConfig['module'];
        $_SESSION['searchParams']['module'] = $module;

        $_POST['module'] = $module;

        foreach($postDatas as $postData)
        {
            foreach($postData as $postKey => $postValue) $_POST[$postKey] = $postValue;
        }

        $this->instance->buildQuery();

        if($return == 'form')
        {
            $formSessionName = $module . 'Form';
            return $_SESSION[$formSessionName];
        }

        $querySessionName = $module . 'Query';
        return $_SESSION[$querySessionName];
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
        $result = $this->instance->convertQueryForm($queryForm);
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
        $result = $this->instance->deleteIndex($objectType, $objectID);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        global $tester;
        $count = \$this->instance->dao->select('COUNT(1) AS count')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetch('count');

        return intval($count);
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
        $result = $this->instance->deleteQuery($queryID);
        if(dao::isError()) return dao::getError();

        return $result ? 'true' : 'false';
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
        $query = $this->instance->getByID((int)$queryID);
        if(dao::isError()) return dao::getError();

        return $query;
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
        $objects = $this->instance->getList($keywords, $type, $pager);
        if(dao::isError()) return dao::getError();

        return count($objects);
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
        $result = $this->instance->getQuery($queryID);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $queryList = $this->instance->getQueryList($module);
        if(dao::isError()) return dao::getError();

        return $queryList;
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
        $objects = $this->instance->getQueryPairs($module);
        if(dao::isError()) return dao::getError();

        return $objects;
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

        $queryID = $this->instance->saveQuery();
        if(dao::isError()) return dao::getError();

        return $this->instance->getByID($queryID);
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

        $result = $this->instance->setDefaultParams('bug', $fields, $params);
        $field  = key($result);
        $value  = zget($result[$field], 'values', array());

        $return = implode(',', array_keys($value));

        return str_replace('@', '', $return);
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
        return $this->instance->setQuery($module, $queryID);
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
        $this->instance->setSearchParams($searchConfig);

        $module = $searchConfig['module'];
        $searchParamsName = $module . 'searchParams';

        return $_SESSION[$searchParamsName];
    }
}
