<?php
/**
 * The model file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * Set search params to session. 
     * 
     * @param  array    $searchConfig 
     * @access public
     * @return void
     */
    public function setSearchParams($searchConfig)
    {
        $searchParams['module']       = $searchConfig['module'];
        $searchParams['searchFields'] = json_encode($searchConfig['fields']);
        $searchParams['fieldParams']  = json_encode($searchConfig['params']);
        $searchParams['actionURL']    = $searchConfig['actionURL'];
        $searchParams['style']        = zget($searchConfig, 'style', 'full');
        $searchParams['onMenuBar']    = zget($searchConfig, 'onMenuBar', 'no');
        $searchParams['queryID']      = isset($searchConfig['queryID']) ? $searchConfig['queryID'] : 0;

        $this->session->set('searchParams', $searchParams);
    }

    /**
     * Build the query to execute.
     * 
     * @access public
     * @return void
     */
    public function buildQuery()
    {
        /* Init vars. */
        $where      = '';
        $groupItems = $this->config->search->groupItems;
        $groupAndOr = strtoupper($this->post->groupAndOr);
        if($groupAndOr != 'AND' and $groupAndOr != 'OR') $groupAndOr = 'AND';

        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* The and or between two groups. */
            if($i == 1) $where .= '(( 1  ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* Skip empty values. */
            if($this->post->$fieldName == 'activatedCount' and $this->post->$valueName == '0') $this->post->$valueName = 'ZERO';
            if($this->post->$valueName == false) continue;
            if($this->post->$valueName == 'null') $this->post->$valueName = '';  // Null is special, stands to empty.
            if($this->post->$valueName == 'ZERO') $this->post->$valueName = 0;   // ZERO is special, stands to 0.

            /* Set and or. */
            $andOr = strtoupper($this->post->$andOrName);
            if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';

            /* Set operator. */
            $value    = $this->post->$valueName;
            $operator = $this->post->$operatorName;
            if(!isset($this->lang->search->operators[$operator])) $operator = '=';

            /* Set condition. */
            $condition = '';
            if($operator == "include")
            {
                $condition = ' LIKE ' . $this->dbh->quote("%$value%");
            }
            elseif($operator == "notinclude")
            {
                $condition = ' NOT LIKE ' . $this->dbh->quote("%$value%"); 
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
                $condition = $operator . ' ' . $this->dbh->quote($value) . ' ';
            }

            /* Set filed name. */
            if($operator == '=' and preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $value))
            {
                $condition  = '`' . $this->post->$fieldName . "` >= '$value' AND `" . $this->post->$fieldName . "` <= '$value 23:59:59'";
                $where     .= " $andOr ($condition)";
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
    }

    /**
     * Init the search session for the first time search.
     * 
     * @param  string   $module 
     * @param  array    $fields 
     * @param  array    $fieldParams 
     * @access public
     * @return void
     */
    public function initSession($module, $fields, $fieldParams)
    {
        $formSessionName  = $module . 'Form';
        if($this->session->$formSessionName != false) return;

        for($i = 1; $i <= $this->config->search->groupItems * 2; $i ++)
        {
            /* Var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            $currentField = key($fields);
            $operator     = isset($fieldParams[$currentField]['operator']) ? $fieldParams[$currentField]['operator'] : '=';

            $queryForm[$fieldName]    = key($fields);
            $queryForm[$andOrName]    = 'and';
            $queryForm[$operatorName] = $operator;
            $queryForm[$valueName]    =  '';

            if(!next($fields)) reset($fields);
        }
        $queryForm['groupAndOr'] = 'and';
        $this->session->set($formSessionName, $queryForm);
    }

    /**
     * Set default params for selection.
     * 
     * @param  array  $fields 
     * @param  array  $params 
     * @access public
     * @return array
     */
    public function setDefaultParams($fields, $params)
    {
        $hasProduct = false;
        $hasProject = false;
        $hasUser    = false;

        $fields     = array_keys($fields);
        foreach($fields as $fieldName)
        {
            if(empty($params[$fieldName])) continue;
            if($params[$fieldName]['values'] == 'products') $hasProduct = true;
            if($params[$fieldName]['values'] == 'users')    $hasUser    = true;
            if($params[$fieldName]['values'] == 'projects') $hasProject = true;
        }

        if($hasUser)
        {
            $users        = $this->loadModel('user')->getPairs('realname|noclosed');
            $users['$@me'] = $this->lang->search->me;
        }
        if($hasProduct) $products = array('' => '') + $this->loadModel('product')->getPairs();
        if($hasProject) $projects = array('' => '') + $this->loadModel('project')->getPairs();

        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');
            if($params[$fieldName]['values'] == 'users')    $params[$fieldName]['values']  = $users;
            if($params[$fieldName]['values'] == 'products') $params[$fieldName]['values']  = $products;
            if($params[$fieldName]['values'] == 'projects') $params[$fieldName]['values']  = $projects;
            if(is_array($params[$fieldName]['values']))
            {
                /* For build right sql when key is 0 and is not null.  e.g. confirmed field. */
                if(isset($params[$fieldName]['values'][0]) and $params[$fieldName]['values'][0] !== '')
                {
                    $params[$fieldName]['values'] = array('ZERO' => $params[$fieldName]['values'][0]) + $params[$fieldName]['values'];
                    unset($params[$fieldName]['values'][0]);
                }
                else
                {
                    $params[$fieldName]['values'] = $params[$fieldName]['values'] + array('null' => $this->lang->search->null);
                }
            }
        }
        return $params;
    }

    /**
     * Get a query.
     * 
     * @param  int    $queryID 
     * @access public
     * @return string
     */
    public function getQuery($queryID)
    {
        $query = $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;

        /* Decode html encode. */
        $query->form = htmlspecialchars_decode($query->form, ENT_QUOTES);
        $query->sql  = htmlspecialchars_decode($query->sql, ENT_QUOTES);

        $query->form = unserialize($query->form);
        $query->sql  = $this->replaceDynamic($query->sql);
        return $query;
    }

    /**
     * Save current query to db.
     * 
     * @access public
     * @return void
     */
    public function saveQuery()
    {
        $sqlVar  = $this->post->module  . 'Query';
        $formVar = $this->post->module  . 'Form';
        $sql     = $this->session->$sqlVar;
        if(!$sql) $sql = ' 1 = 1 ';

        $query = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->add('form', serialize($this->session->$formVar))
            ->add('sql',  $sql)
            ->skipSpecial('sql,form')
            ->remove('onMenuBar')
            ->get();
        $this->dao->insert(TABLE_USERQUERY)->data($query)->autoCheck()->check('title', 'notempty')->exec();

        if(!dao::isError())
        {
            $queryID = $this->dao->lastInsertID();
            /* Set this query show on menu bar. */
            if($this->post->onMenuBar)
            {
                $queryModule      = $query->module == 'task' ? 'project' : ($query->module == 'story' ? 'product' : $query->module);
                $featureBarConfig = $this->dao->select('*')->from(TABLE_CONFIG)->where('owner')->eq($this->app->user->account)->andWhere('module')->eq('common')->andWhere('section')->eq('customMenu')->andWhere('`key`')->like("feature_{$queryModule}_%")->fetch();

                $this->app->loadLang($queryModule);
                if(!isset($this->lang->$queryModule->featureBar)) return $queryID;

                $method    = key($this->lang->$queryModule->featureBar);
                $newConfig = array();
                if(isset($featureBarConfig->id)) $newConfig = json_decode($featureBarConfig->value);
                if(empty($newConfig))
                {
                    $order = 1;
                    foreach($this->lang->$queryModule->featureBar[$method] as $menuKey => $menuName)
                    {
                        $menu = new stdclass();
                        $menu->name  = $menuKey;
                        $menu->order = $order;
                        $newConfig[] = $menu;
                        $order++;
                    }
                }

                $menu = new stdclass();
                $menu->name  = 'QUERY' . $queryID;
                $menu->order = $order;
                $newConfig[] = $menu;

                $featureBarConfig = new stdclass();
                $featureBarConfig->owner   = $this->app->user->account;
                $featureBarConfig->module  = 'common';
                $featureBarConfig->section = 'customMenu';
                $featureBarConfig->key     = "feature_{$queryModule}_{$method}";
                $featureBarConfig->value   = json_encode($newConfig);
                $this->dao->replace(TABLE_CONFIG)->data($featureBarConfig)->exec();
            }
            return $queryID;
        }
        return false;
    }

    /**
     * Delete current query from db.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function deleteQuery($queryID)
    {
        $this->dao->delete()->from(TABLE_USERQUERY)->where('id')->eq($queryID)->andWhere('account')->eq($this->app->user->account)->exec();
    }

    /**
     * Get title => id pairs of a user.
     * 
     * @param  string    $module 
     * @access public
     * @return array
     */
    public function getQueryPairs($module)
    {
        $queries = $this->dao->select('id, title')
            ->from(TABLE_USERQUERY)
            ->where('account')->eq($this->app->user->account)
            ->andWhere('module')->eq($module)
            ->orderBy('id_asc')
            ->fetchPairs();
        if(!$queries) return array('' => $this->lang->search->myQuery);
        $queries = array('' => $this->lang->search->myQuery) + $queries;
        return $queries;
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
        else if($module == 'task')
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
        $resultPairs = array('' => '');
        foreach($results as $result) $resultPairs[$result->id] = $result->id . ':' . $result->$title;
        return $resultPairs;
    }

    /**
      Replace dynamic account and date. 
     * 
     * @param  string $query 
     * @access public
     * @return string 
     */
    public function replaceDynamic($query)
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
}
