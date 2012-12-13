<?php
/**
 * The model file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id$
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
            if($i == 1) $where .= '( 1  ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* Set var names. */
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* Skip empty values. */
            if($this->post->$valueName == false) continue; 
            if($this->post->$valueName == 'null') $this->post->$valueName = '';  // Null is special, stands to empty.

            /* Set and or. */
            $andOr = strtoupper($this->post->$andOrName);
            if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';
            $where .= " $andOr ";

            /* Set filed name. */
            $where .= '`' . $this->post->$fieldName . '` ';

            /* Set operator. */
            $value    = $this->post->$valueName;
            $operator = $this->post->$operatorName;
            if(!isset($this->lang->search->operators[$operator])) $operator = '=';
            if($operator == "include")
            {
                $where .= ' LIKE ' . $this->dbh->quote("%$value%");
            }
            elseif($operator == "notinclude")
            {
                $where .= ' NOT LIKE ' . $this->dbh->quote("%$value%"); 
            }
            elseif($operator == 'belong')
            {
                if($this->post->$fieldName == 'module')
                {
                    $allModules = $this->loadModel('tree')->getAllChildId($value);
                    $where .= helper::dbIN($allModules);
                }
                elseif($this->post->$fieldName == 'dept')
                {
                    $allDepts = $this->loadModel('dept')->getAllChildId($value);
                    $where .= helper::dbIN($allDepts);
                }
                else
                {
                    $where .= ' = ' . $this->dbh->quote($value) . ' ';
                }
            }
            else
            {
                $where .= $operator . ' ' . $this->dbh->quote($value) . ' ';
            }
        }

        $where .=" )";

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

        if($hasUser)    $users    = $this->loadModel('user')->getPairs();
        if($hasProduct) $products = array('' => '') + $this->loadModel('product')->getPairs();
        if($hasProject) $projects = array('' => '') + $this->loadModel('project')->getPairs();

        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');
            if($params[$fieldName]['values'] == 'users')    $params[$fieldName]['values']  = $users;
            if($params[$fieldName]['values'] == 'products') $params[$fieldName]['values']  = $products;
            if($params[$fieldName]['values'] == 'projects') $params[$fieldName]['values']  = $projects;
            if(is_array($params[$fieldName]['values'])) $params[$fieldName]['values']  = $params[$fieldName]['values'] + array('null' => $this->lang->search->null);
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
        $query->form = unserialize($query->form);
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
            ->specialChars('title')
            ->add('account', $this->app->user->account)
            ->add('form', serialize($this->session->$formVar))
            ->add('sql',  $sql)
            ->get();
        $this->dao->insert(TABLE_USERQUERY)->data($query)->autoCheck()->check('title', 'notempty')->exec();
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
     * Get records by the conditon.
     * 
     * @param  string    $module 
     * @param  string    $moduleIds 
     * @param  string    $conditions 
     * @access public
     * @return array
     */
    public function getBySelect($module, $moduleIds, $conditions)
    {
        if($module == 'story')
        {
            $pairs = 'id,title';
            $table = 'zt_story';
        }
        else if($module == 'task')
        {
            $pairs = 'id,name';
            $table = 'zt_task';
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
        
        foreach($moduleIds as $id)
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

}
