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
     * @return void
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
     * @return void
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
     * @return void
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
     * @param  int    $module
     * @access public
     * @return void
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
     * @param  int    $keywords
     * @param  int    $type
     * @access public
     * @return void
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
     * @param  int    $objectType
     * @param  int    $objectID
     * @access public
     * @return void
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
     * @return void
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
     * @param  int    $string
     * @access public
     * @return void
     */
    public function decodeTest($string)
    {
        $objects = $this->objectModel->decode($string);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSummaryTest($content, $words)
    {
        $objects = $this->objectModel->getSummary($content, $words);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkPrivTest($results)
    {
        $objects = $this->objectModel->checkPriv($results);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function markKeywordsTest($content, $keywords)
    {
        $objects = $this->objectModel->markKeywords($content, $keywords);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildAllIndexTest($type = '', $lastID = 0)
    {
        $objects = $this->objectModel->buildAllIndex($type = '', $lastID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteIndexTest($objectType, $objectID)
    {
        $objects = $this->objectModel->deleteIndex($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function appendFilesTest($object)
    {
        $objects = $this->objectModel->appendFiles($object);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
