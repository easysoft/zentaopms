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
    public function getQueryTest(int $queryID)
    {
        $objects = $this->objectModel->getQuery($queryID);

        global $tester;
        $objectType = $objects->module;
        if($objects->module == 'executionStory') $objectType = 'story';
        if($objects->module == 'projectBuild')   $objectType = 'build';
        if($objects->module == 'executionBuild') $objectType = 'build';

        $table = $tester->config->objectTables[$objectType];
        $objects->queryCount = $tester->dao->select('count(*) as count')->from($table)->where($objects->sql)->fetch('count');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get by ID.
     *
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getByIDTest($queryID)
    {
        $objects = $this->objectModel->getByID($queryID);

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
     * Test delete query.
     *
     * @param  int    $queryID
     * @access public
     * @return int
     */
    public function deleteQueryTest($queryID)
    {
        global $tester;
        $this->objectModel->deleteQuery($queryID);
        if(dao::isError()) return dao::getError();

        $count = $tester->dao->select('count(*) as count')->from(TABLE_USERQUERY)->fetch('count');
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * Test get query pairs.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryPairsTest($module)
    {
        $objects = $this->objectModel->getQueryPairs($module);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get list.
     *
     * @param  string $keywords
     * @param  string $type
     * @access public
     * @return int
     */
    public function getListTest($keywords, $type)
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
     * Test save index.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function saveIndexTest($objectType, $objectID)
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        $table  = $tester->config->objectTables[$objectType];
        $object = $tester->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        $object->comment = '';

        $objects = $this->objectModel->saveIndex($objectType, $object);
        if(dao::isError()) return dao::getError();

        $insertedIndex = $tester->dao->select('*')->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetch();

        $tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();

        if($insertedIndex) return true;
    }

    /**
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

        $objects = $this->objectModel->saveDict($titleSplited['dict']);
        if(dao::isError()) return dao::getError();

        $count = $tester->dao->select("count('*') as count")->from(TABLE_SEARCHDICT)->fetch('count');
        $tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();
        return $count;
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
}
