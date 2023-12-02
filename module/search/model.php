<?php
/**
 * The model file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: model.php 5082 2013-07-10 01:14:45Z wyd621@gmail.com $
 * @link        http://www.zentao.net
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

        $queryForm = $this->initSession($module, $searchFields, $fieldParams);

        $scoreNum = 0;
        $where    = '';
        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* The and or between two groups. */
            $formIndex = $i - 1;
            if($i == 1) $where .= '(( 1  ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            $field = $this->post->$fieldName;
            if(empty($field)) continue;
            if($this->post->$valueName == false) continue;

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
        $where  = $this->replaceDynamic($where);

        /* Save to session. */
        $querySessionName = $this->post->module . 'Query';
        $formSessionName  = $this->post->module . 'Form';
        $this->session->set($querySessionName, $where);
        $this->session->set($formSessionName,  $queryForm);
        if($scoreNum > 2 && !dao::isError()) $this->loadModel('score')->create('search', 'saveQueryAdvanced');
    }

    /**
     * 初始化搜索表单，并且保存到 session。
     * Init the search session for the first time search.
     *
     * @param  string       $module
     * @param  object|array $fields
     * @param  object|array $fieldParams
     * @access public
     * @return array
     */
    public function initSession(string $module, object|array $fields, object|array $fieldParams): array
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
     * 设置默认的搜索参数。
     * Set default params for selection.
     *
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function setDefaultParams(array $fields, array $params): array
    {
        $fields = array_keys($fields);

        list($users, $products, $executions) = $this->searchTao->getParamValues($fields, $params);

        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');

            if($params[$fieldName]['values'] == 'users')
            {
                if(!empty($this->config->user->moreLink)) $this->config->moreLinks["field{$fieldName}"] = $this->config->user->moreLink;
                $params[$fieldName]['values'] = $users;
            }

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
     * 替换日期和用户变量。
     * Replace dynamic account and date.
     *
     * @param  string $query
     * @access public
     * @return string
     */
    public function replaceDynamic(string $query): string
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
        list($words, $againstCond, $likeCondition) = $this->searchTao->getSqlParams($keywords, $type);
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
            ->orderBy($orderBy)
            ->query();

        $results     = array();
        $idListGroup = array();
        while($record = $stmt->fetch())
        {
            $results[$record->id] = $record;

            $module = $record->objectType == 'case' ? 'testcase' : $record->objectType;
            $idListGroup[$module][$record->objectID] = $record->objectID;
        }

        $results = $this->checkPriv($results, $idListGroup);
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
        if($objectType == 'doc' && $this->config->edition != 'open') $object = $this->appendFiles($object);

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
     * 将 unicode 转换为对应的字。
     * Transfer unicode to words.
     *
     * @param  string $string
     * @access public
     * @return string
     */
    public function decode(string $string): string
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
     * @access public
     * @return string
     */
    public function getSummary(string $content, string $words): string
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
     * 检查查询到的结果的权限。
     * Check product and project priv.
     *
     * @param  array  $results
     * @param  array  $objectPairs
     * @access public
     * @return array
     */
    public function checkPriv(array $results, array $objectPairs = array()): array
    {
        if($this->app->user->admin) return $results;

        $products   = $this->app->user->view->products;
        $executions = $this->app->user->view->sprints;

        if(empty($objectPairs)) foreach($results as $record) $objectPairs[$record->objectType][$record->objectID] = $record->id;
        foreach($objectPairs as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;

            $table = $this->config->objectTables[$objectType];
            $results = $this->searchTao->checkObjectPriv($objectType, $table, $results, $objectIdList, $products, $executions);
            $results = $this->searchTao->checkRelatedObjectPriv($objectType, $table, $results, $objectIdList, $products, $executions);
        }
        return $results;
    }

    /**
     * 在文中标记关键词。
     * Mark keywords in content.
     *
     * @param  string $content
     * @param  string $keywords
     * @access public
     * @return string
     */
    public function markKeywords(string $content, string $keywords): string
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
     * Delete index of an object.
     *
     * @param  string    $objectType
     * @param  int       $objectID
     * @access public
     * @return void
     */
    public function deleteIndex($objectType, $objectID)
    {
        $this->dao->delete()->from(TABLE_SEARCHINDEX)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->exec();
        return !dao::isError();
    }

    /**
     * 将特殊符号替换成统一的符号。
     * Unified processing of search keywords.
     *
     * @param  string $string
     * @param  string $to
     * @access public
     * @return string
     */
    public static function unify(string $string, string $to = ','): string
    {
        $labels = array('_', '、', ' ', '-', '\n', '?', '@', '&', '%', '~', '`', '+', '*', '/', '\\', '。', '，');
        $string = str_replace($labels, $to, $string);
        return preg_replace("/[{$to}]+/", $to, trim($string, $to));
    }

    /**
     * 将文档的内容追加到文档的索引备注中。
     * Append document content to the document.
     *
     * @param  object $object
     * @access public
     * @return object
     */
    public function appendFiles(object $object): object
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

    /**
     * Set search form options.
     *
     * @param  array $fields
     * @param  array $fieldParams
     * @param  array $queries
     * @access public
     * @return object
     */
    public function setOptions($fields, $fieldParams, $queries = array())
    {
        $options = new stdclass();
        $options->operators         = array();
        $options->fields            = array();
        $options->savedQueryTitle   = $this->lang->search->savedQuery;
        $options->andOr             = array();
        $options->groupName         = array($this->lang->search->group1, $this->lang->search->group2);
        $options->searchBtnText     = $this->lang->search->common;
        $options->resetBtnText      = $this->lang->search->reset;
        $options->saveSearchBtnText = $this->lang->search->saveCondition;
        foreach($this->lang->search->andor as $value => $title)
        {
            $andOr = new stdclass();
            $andOr->value = $value;
            $andOr->title = $title;

            $options->andOr[] = $andOr;
        }

        foreach($this->lang->search->operators as $value => $title)
        {
            $operator = new stdclass();
            $operator->value = $value;
            $operator->title = $title;

            $options->operators[] = $operator;
        }

        foreach($fieldParams as $field => $param)
        {
            $data = new stdclass();
            $data->label    = $fields[$field];
            $data->name     = $field;
            $data->control  = $param['control'];
            $data->operator = $param['operator'];

            if($field == 'id') $data->placeholder = $this->lang->search->queryTips;
            if(!empty($param['values']) and is_array($param['values'])) $data->values = $param['values'];

            $options->fields[] = $data;
        }

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
        $options->saveSearch->text = $this->lang->search->saveCondition;

        return $options;
    }

    /**
     * Build search form options.
     *
     * @param  array $module
     * @param  array $fieldParams
     * @param  array $fieldsMap
     * @param  array $queries
     * @param  string $actionURL
     * @access public
     * @return object
     */
    public function buildSearchFormOptions($module, $fieldParams, $fields, $queries, $actionURL = '')
    {
        $opts = new stdClass();
        $opts->formConfig       = static::buildFormConfig();
        $opts->fields           = static::buildFormFields($fieldParams, $fields);
        $opts->operators        = static::buildFormOperators($this->lang->search->operators);
        $opts->andOr            = static::buildFormAndOrs($this->lang->search->andor);
        $opts->saveSearch       = static::buildFormSaveSearch($module);
        $opts->searchConditions = static::buildFormSavedQuery($queries, $actionURL);

        return $opts;
    }

    /**
     * Form Configuration of buildForm action.
     *
     * @access public
     * @return object
     */
    public static function buildFormConfig()
    {
        $config = new stdClass();
        $config->action = helper::createLink('search', 'buildQuery');
        $config->method = 'post';

        return $config;
    }

    /**
     * Fields options of buildForm action.
     *
     * @param  array $fieldParams
     * @param  array $fieldsMap
     * @access public
     * @return array
     */
    public static function buildFormFields($fieldParams, $fieldsMap)
    {
        $fields = array();

        foreach($fieldParams as $name => $param)
        {
            $field = new stdClass();
            $field->label        = isset($fieldsMap[$name]) ? $fieldsMap[$name] : '';
            $field->name         = $name;
            $field->control      = $param['control'];
            $field->operator     = $param['operator'];
            $field->defaultValue = '';
            $field->placeholder  = '';
            $field->values       = $param['values'];

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * Operators of buildForm action.
     *
     * @param  array $operators
     * @access public
     * @return array
     */
    public static function buildFormOperators($operators)
    {
        $ops = array();

        foreach($operators as $val => $title)
        {
            $op = new stdClass();
            $op->value = $val;
            $op->title = $title;

            $ops[] = $op;
        }

        return $ops;
    }

    /**
     * AndOr options of buildForm action.
     *
     * @param  array $andOrs
     * @access public
     * @return array
     */
    public static function buildFormAndOrs($andOrs)
    {
        $result = array();

        foreach($andOrs as $val => $title)
        {
            $item = new stdClass();
            $item->value = $val;
            $item->title = $title;

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Save Search button of buildForm action.
     *
     * @param  array $module
     * @access public
     * @return object
     */
    public static function buildFormSaveSearch($module)
    {
        global $lang;

        $result = new stdClass();
        $result->text     = $lang->search->saveCondition;
        $result->hasPriv  = common::hasPriv('search', 'saveQuery');
        $result->config   = array(
            'data-toggle'    => 'modal',
            'data-type'      => 'ajax',
            'data-data-type' => 'html',
            'data-url'       => helper::createLink('search', 'saveQuery', array('module' => $module)),
        );

        return $result;
    }

    /**
     * Saved Queries list of buildForm action.
     *
     * @param  array $queries
     * @param  array $account
     * @access public
     * @return array
     */
    public static function buildFormSavedQuery($queries, $actionURL)
    {
        $result = array();
        if(empty($queries)) return $result;

        global $lang;
        $hasPriv = common::hasPriv('search', 'deleteQuery');
        foreach($queries as $query)
        {
            if(!is_object($query)) continue;

            $item = new stdClass();
            $item->text     = $query->title;
            $item->applyURL = str_replace('myQueryID', $query->id, $actionURL);
            if($hasPriv) $item->deleteProps = array('className' => 'ajax-submit', 'data-confirm' => $lang->search->confirmDelete, 'href' => helper::createLink('search', 'deleteQuery', "queryID={$query->id}"));

            $result[] = $item;
        }

        return $result;
    }
}
