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
     * 设置搜索参数的session。
     * Set search params to session.
     *
     * @param  array  $searchConfig
     * @access public
     * @return void
     */
    public function setSearchParams(array $searchConfig): void
    {
        $module = $searchConfig['module'];

        if($this->config->edition != 'open') $searchConfig = $this->searchTao->processBuildinFields($module);

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
     * 构建查询语句。
     * Build the query to execute.
     *
     * @access public
     * @return void
     */
    public function buildQuery()
    {
        /* Init vars. */
        $module       = $this->session->searchParams['module'];
        $searchParams = $module . 'searchParams';
        $searchFields = json_decode($_SESSION[$searchParams]['searchFields']);
        $fieldParams  = json_decode($_SESSION[$searchParams]['fieldParams']);
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
            if($i == 1) $where .= '(( 1 ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            $field = $this->post->$fieldName;
            if(empty($field) || $this->post->$valueName == false) continue;

            /* 如果是输入框，并且输入框的值为'0'，或者 id 的值为'0'，将值设置为zero。*/
            if(isset($fieldParams->$field) && $fieldParams->$field->control == 'input' && $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';
            if($field == 'id' && $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';

            /* set queryForm. */
            list($andOr, $operator, $value) = $this->searchTao->processQueryFormDatas($fieldParams, $field, $andOrName, $operatorName, $valueName);
            $queryForm[$formIndex] = array('field' => $field, 'andOr' => strtolower($andOr), 'operator' => $operator, 'value' => $value);

            /* Set where. */
            $where = $this->searchTao->setWhere($where, $field, $operator, $value, $andOr);

            $scoreNum += 1;
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
     * 获取查询。
     * Get a query.
     *
     * @param  int    $queryID
     * @access public
     * @return object|bool
     */
    public function getQuery(int $queryID): object|bool
    {
        $query = $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;

        /* Decode html encode. */
        $query->form = htmlspecialchars_decode($query->form, ENT_QUOTES);
        $query->sql  = htmlspecialchars_decode($query->sql, ENT_QUOTES);

        /* 如果搜索表单中值有变量，把表单值放到post 表单，重新生成 query。*/
        /* If form has variable, regenerate query. */
        $hasDynamic  = str_contains($query->form, '$');
        $query->form = unserialize($query->form);
        if($hasDynamic)
        {
            $_POST = $query->form;

            $this->buildQuery();
            $querySessionName = $query->form['module'] . 'Query';
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
        elseif($module == 'task')
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
        $resultPairs = array();
        foreach($results as $result) $resultPairs[$result->id] = $result->id . ':' . $result->$title;
        return $resultPairs;
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
            if(strpos(',feedback,ticket,', ",$object,") !== false)
            {
                unset($allowedObjects[$index]);
                $filterObjects[] = $object;
            }
        }

        $typeCount = $this->dao->select("objectType, count(*) AS objectCount")->from(TABLE_SEARCHINDEX)
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
    public function getList(string $keywords, array|string $type, object $pager = null): array
    {
        list($words, $againstCond, $likeCondition) = $this->searchTao->getSqlParams($keywords);
        $allowedObjects = $this->searchTao->getAllowedObjects($type);

        $filterObjects = array();
        foreach($allowedObjects as $index => $object)
        {
            if(strpos(',feedback,ticket,', ",$object,") === false) continue;

            unset($allowedObjects[$index]);
            $filterObjects[] = $object;
        }

        $scoreColumn = "(MATCH(title, content) AGAINST('{$againstCond}' IN BOOLEAN MODE))";
        $stmt = $this->dao->select("*, {$scoreColumn} as score")->from(TABLE_SEARCHINDEX)
            ->where("(MATCH(title,content) AGAINST('{$againstCond}' IN BOOLEAN MODE) >= 1 {$likeCondition})")
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
        if($pager) $this->searchTao->setResultsInPage($results, $pager);

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
        $contentSplited = $spliter->utf8Split(strip_tags($index->content));

        $index->title   = $titleSplited['words'];
        $index->content = $contentSplited['words'];

        $this->saveDict($titleSplited['dict'] + $contentSplited['dict']);
        $this->dao->replace(TABLE_SEARCHINDEX)->data($index)->exec();

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

            $this->dao->insert(TABLE_SEARCHDICT)->data(array('key' => $key, 'value' => $value))->exec();
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
        if($type == 'story' || $type == 'requirement')
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
                $dataList = $query->beginIF($lastID)->andWhere('t1.id')->gt($lastID)->fi()->orderBy('t1.id')->limit($limit)->fetchAll('id');
                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                $dataList = $this->processDataList($module, $field, $dataList);

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
