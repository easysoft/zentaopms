<?php
/**
 * The model file of search module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.cn
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
            $queryForm[$valueName]    = '';
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
        $fields   = array_keys($fields);
        foreach($fields as $fieldName)
        {
            if(!isset($params[$fieldName])) $params[$fieldName] = array('operator' => '=', 'control' => 'input', 'values' => '');
            if($params[$fieldName]['values'] == 'users')    $params[$fieldName]['values'] = $users;
            if($params[$fieldName]['values'] == 'products') $params[$fieldName]['values'] = $products;
        }
        return $params;
    }
}
