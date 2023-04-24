<?php
class searchTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('search');
    }

    /**
     * Test get query.
     *
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getQueryTest($queryID)
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
     * @param  sreing $type
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
