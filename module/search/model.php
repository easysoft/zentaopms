<?php
/**
 * The model file of search module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
    /* 拼装SQL。*/
    public function buildQuery()
    {
        /* 初始化变量。*/
        $where      = '';
        $groupItems = $this->config->search->groupItems;
        $groupAndOr = strtoupper($this->post->groupAndOr);
        if($groupAndOr != 'AND' and $groupAndOr != 'OR') $groupAndOr = 'AND';

        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* 拼两个分组之间的括号。*/
            if($i == 1) $where .= '( 1  ';
            if($i == $groupItems + 1) $where .= " ) $groupAndOr ( 1 ";

            /* 设定各个变量的名称。*/
            $fieldName    = "field$i";
            $andOrName    = "andOr$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* 对应的value为空，忽略。*/
            if($this->post->$valueName == false) continue; 
            if($this->post->$valueName == 'null') $this->post->$valueName = '';

            /* 设置and, or。*/
            $andOr = strtoupper($this->post->$andOrName);
            if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';
            $where .= " $andOr ";

            /* 字段名。*/
            $where .= '`' . $this->post->$fieldName . '` ';

            /* 操作符。*/
            $value    = $this->post->$valueName;
            $operator = $this->post->$operatorName;
            if(!isset($this->lang->search->operators[$operator])) $operator = '=';
            if($operator == "include")
            {
                $where .= ' LIKE ' . $this->dbh->quote("%$value%");
            }
            else
            {
                $where .= $operator . ' ' . $this->dbh->quote($value) . ' ';
            }
        }

        $where .=" )";

        /* 登记session。*/
        $querySessionName = $this->post->module . 'Query';
        $formSessionName  = $this->post->module . 'Form';
        $this->session->set($querySessionName, $where);
        $this->session->set($formSessionName,  $_POST);
    }

    /* 初始化查询表单的session。*/
    public function initSession($module, $fields, $fieldParams)
    {
        $formSessionName  = $module . 'Form';
        if($this->session->$formSessionName != false) return;
        for($i = 1; $i <= $this->config->search->groupItems * 2; $i ++)
        {
            /* 设定各个变量的名称。*/
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

    /* 设置默认的参数。*/
    public function setDefaultParams($fields, $params)
    {
        $users    = $this->loadModel('user')->getPairs();
        $products = array('' => '') + $this->loadModel('product')->getPairs();
        $projects = array('' => '') + $this->loadModel('project')->getPairs();
        $fields   = array_keys($fields);
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

    /* 获得某一个查询。*/
    public function getQuery($queryID)
    {
        $query = $this->dao->findByID($queryID)->from(TABLE_USERQUERY)->fetch();
        if(!$query) return false;
        $query->form = unserialize($query->form);
        return $query;
    }

    /* 保存查询。*/
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

    /* 获得用户查询对。*/
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

    /* 按照某一个查询条件获取列表。*/
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

    /* 格式化需求显示。*/
    private function formatResults($results, $module)
    {
        /* 重新组织每一个title的展示方式。*/
        $title = ($module == 'story') ? 'title' : 'name';
        $resultPairs = array('' => '');
        foreach($results as $result) $resultPairs[$result->id] = $result->id . ':' . $result->$title;
        return $resultPairs;
    }

}
