<?php
/**
 * The dao and sql class file of ZenTaoPHP.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: dao.class.php 134 2010-09-11 07:24:27Z wwccss $
 * @link        http://www.zentao.net
 */

/**
 * DAO类。提供各种便利的数据库操作方法。
 * 
 * @package ZenTaoPHP
 */
class dao
{
    /* 解决autoCompany带来的sql关键字冲突的问题。*/
    const WHERE   = 'wHeRe';
    const GROUPBY = 'gRoUp bY';
    const HAVING  = 'hAvInG';
    const ORDERBY = 'oRdEr bY';
    const LIMIT   = 'lImiT';

    /**
     * 全局的$app对象。
     * 
     * @var object
     * @access protected
     */
    protected $app;

    /**
     * 全局的$config对象。 
     * 
     * @var object
     * @access protected
     */
    protected $config;

    /**
     * 全局的$lang对象。
     * 
     * @var object
     * @access protected
     */
    protected $lang;

    /**
     * 全局的$dbh（数据库访问句柄）对象。
     * 
     * @var object
     * @access protected
     */
    protected $dbh;

    /**
     * 当前查询所对应的主表。
     * 
     * @var string
     * @access public
     */
    public $table;

    /**
     * 主表所对应的alias
     * 
     * @var string
     * @access public
     */
    public $alias;

    /**
     * 当前查询所返回的字段列表。
     * 
     * @var string
     * @access public
     */
    public $fields;

    /**
     * 查询的模式，现在支持两种，一种是通过魔术方法，一种是直接拼写sql查询。
     * 
     * 主要用来区分dao::from()方法和sql::from()方法。
     *
     * @var string
     * @access public
     */
    public $mode;

    /**
     * 查询的方法: insert, select, update, delete, replace
     *
     * @var string
     * @access public
     */
    public $method;

    /**
     * 执行的sql查询列表。
     * 
     * 用来记录当前页面所有的sql查询。
     *
     * @var array
     * @access public
     */
    static public $querys = array();

    /**
     * 数据检查结果。
     * 
     * @var array
     * @access public
     */
    static public $errors = array();

    /**
     * 构造函数。
     * 
     * 设置当前model对应的表名，并引用全局的变量。
     *
     * @param string $table   表名。
     * @access public
     * @return void
     */
    public function __construct($table = '')
    {
        global $app, $config, $lang, $dbh;
        $this->app    = $app;
        $this->config = $config;
        $this->lang   = $lang;
        $this->dbh    = $dbh;

        $this->reset();
    }

    /**
     * 设置数据表。
     * 
     * @param string $table   表名。
     * @access private
     * @return void
     */
    private function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * 设置当前查询主表的alias。 
     * 
     * @param string $alias     别名。
     * @access private
     * @return void
     */
    private function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * 设置返回的字段列表。
     * 
     * @param string $fields   字段列表。
     * @access private
     * @return void
     */
    private function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * 重新设置table, field, mode。
     * 
     * @access private
     * @return void
     */
    private function reset()
    {
        $this->setFields('');
        $this->setTable('');
        $this->setAlias('');
        $this->setMode('');
        $this->setMethod('');
    }

    //-------------------- 根据查询方式的不同，调用SQL类的对应方法。--------------------//

    /**
     * 设置查询模式。magic是通过findby之类的魔术方法进行查询的，而raw则直接拼装sql进行查询。
     * 
     * @param string mode   查询模式： empty|magic|raw
     * @access private
     * @return void
     */
    private function setMode($mode = '')
    {
        $this->mode = $mode;
    }

    /* 设置查询的方法。select|update|insert|delete|replace */
    private function setMethod($method = '')
    {
        $this->method = $method;
    }

    /* select：调用SQL类的select方法。*/
    public function select($fields = '*')
    {
        $this->setMode('raw');
        $this->setMethod('select');
        $this->sqlobj = sql::select($fields);
        return $this;
    }

    /* update：调用SQL类的update方法。*/
    public function update($table)
    {
        $this->setMode('raw');
        $this->setMethod('update');
        $this->sqlobj = sql::update($table);
        $this->setTable($table);
        return $this;
    }

    /* delete：调用SQL类的delete方法。*/
    public function delete()
    {
        $this->setMode('raw');
        $this->setMethod('delete');
        $this->sqlobj = sql::delete();
        return $this;
    }

    /* insert：调用SQL类的insert方法。*/
    public function insert($table)
    {
        $this->setMode('raw');
        $this->setMethod('insert');
        $this->sqlobj = sql::insert($table);
        $this->setTable($table);
        return $this;
    }

    /* replace：调用SQL类的replace方法。*/
    public function replace($table)
    {
        $this->setMode('raw');
        $this->setMethod('replace');
        $this->sqlobj = sql::replace($table);
        $this->setTable($table);
        return $this;
    }

    /* from: 设定要查询的table name。*/
    public function from($table) 
    {
        $this->setTable($table);
        if($this->mode == 'raw') $this->sqlobj->from($table);
        return $this;
    }

    /* fields方法：设置要查询的字段列表。*/
    public function fields($fields)
    {
        $this->setFields($fields);
        return $this;
    }

    /* alias方法。*/
    public function alias($alias)
    {
        if(empty($this->alias)) $this->setAlias($alias);
        $this->sqlobj->alias($alias);
        return $this;
    }

    /* data方法。*/
    public function data($data, $autoCompany = true)
    {
        /* 如果当前模块不是company，都追加company字段。*/
        if(!is_object($data)) $data = (object)$data;
        if($autoCompany and isset($this->app->company) and $this->table != TABLE_COMPANY and !isset($data->company)) $data->company = $this->app->company->id;
        $this->sqlobj->data($data);
        return $this;
    }

    //-------------------- 拼装之后的SQL相关处理方法。--------------------//

    /* 返回SQL语句。*/
    public function get()
    {
        return $this->processKeywords($this->processSQL());
    }

    /* 打印SQL语句。*/
    public function printSQL()
    {
        echo $this->processSQL();
    }

    /* 处理SQL，将table和fields字段替换成对应的值。*/
    private function processSQL($autoCompany = true)
    {
        $sql = $this->sqlobj->get();

        /* 如果查询模式是magic，处理fields和table两个变量。*/
        if($this->mode == 'magic')
        {
            if($this->fields == '') $this->fields = '*';
            if($this->table == '')  $this->app->error('Must set the table name', __FILE__, __LINE__, $exit = true);
            $sql = sprintf($this->sqlobj->get(), $this->fields, $this->table);
        }

        /* 如果处理的不是company表，并且查询方法不是insert和replace， 追加company的查询条件。*/
        if(isset($this->app->company) and $autoCompany and $this->table != '' and $this->table != TABLE_COMPANY and $this->method != 'insert' and $this->method != 'replace')
        {
            /* 获得where 和 order by的位置。*/
            $wherePOS  = strrpos($sql, DAO::WHERE);
            $groupPOS  = strrpos($sql, DAO::GROUPBY);           // group by的位置。
            $havingPOS = strrpos($sql, DAO::HAVING);            // having的位置。
            $orderPOS  = strrpos($sql, DAO::ORDERBY);           // order by的位置。
            $limitPOS  = strrpos($sql, DAO::LIMIT);             // limit的位置。
            $splitPOS  = $orderPOS ? $orderPOS : $limitPOS;     // order比limit靠前。
            $splitPOS  = $havingPOS? $havingPOS: $splitPOS;     // having比orer靠前。
            $splitPOS  = $groupPOS ? $groupPOS : $splitPOS;     // group比having靠前。

            /* 要追加的条件语句。*/
            $tableName = !empty($this->alias) ? $this->alias : $this->table;
            $companyCondition = " $tableName.company = '{$this->app->company->id}' ";

            /* SQL语句中有order by。*/
            if($splitPOS)
            {
                $firstPart = substr($sql, 0, $splitPOS);
                $lastPart  = substr($sql, $splitPOS);
                if($wherePOS)
                {
                    $sql = $firstPart . " AND $companyCondition " . $lastPart;
                }
                else
                {
                    $sql = $firstPart . " WHERE $companyCondition " . $lastPart;
                }
            }
            else
            {
                $sql .= $wherePOS ? " AND $companyCondition" : " WHERE $companyCondition";
            }
        }
        self::$querys[] = $this->processKeywords($sql);
        return $sql;
    }

    /* 处理SQL关键字。*/
    private function processKeywords($sql)
    {
        return str_replace(array(DAO::WHERE, DAO::GROUPBY, DAO::HAVING, DAO::ORDERBY, DAO::LIMIT), array('WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'), $sql);
    }

    //-------------------- SQL查询相关的方法。--------------------//
    
    /* 设置数据库访问句柄。*/
    public function dbh($dbh)
    {
        $this->dbh = $dbh;
        return $this;
    }

    /* 执行sql查询，返回stmt对象。autoComapny设定是否自动追加company的查询条件。*/
    public function query($autoCompany = true)
    {
        /* 如果dao::$errors不为空，返回一个空的stmt对象，这样后续的方法调用还可以继续。*/
        if(!empty(dao::$errors)) return new PDOStatement();

        /* 处理一下SQL语句。*/
        $sql = $this->processSQL($autoCompany);
        try
        {
            $this->reset();
            return $this->dbh->query($sql);
        }
        catch (PDOException $e) 
        {
            $this->app->error($e->getMessage() . "<p>The sql is: $sql</p>", __FILE__, __LINE__, $exit = true);
        }
    }

    /* 执行分页。*/
    public function page($pager)
    {
        if(!is_object($pager)) return $this;

        /* 没有传递recTotal，则自己进行计算。*/
        if($pager->recTotal == 0)
        {
            /* 获得SELECT和FROM的位置，据此算出查询的字段，然后将其替换为count(*)。*/
            $sql       = $this->get();
            $selectPOS = strpos($sql, 'SELECT') + strlen('SELECT');
            $fromPOS   = strpos($sql, 'FROM');
            $fields    = substr($sql, $selectPOS, $fromPOS - $selectPOS );
            $sql       = str_replace($fields, ' COUNT(*) AS recTotal ', $sql);

            /* 取得order 或者limit的位置，将后面的去掉。*/
            $subLength = strlen($sql);
            $orderPOS  = strripos($sql, 'order');
            $limitPOS  = strripos($sql , 'limit');
            if($limitPOS) $subLength = $limitPOS;
            if($orderPOS) $subLength = $orderPOS;
            $sql = substr($sql, 0, $subLength);
            self::$querys[] = $sql;

            /* 获得记录总数。*/
            try
            {
                $row = $this->dbh->query($sql)->fetch(PDO::FETCH_OBJ);
            }
            catch (PDOException $e) 
            {
                $this->app->error($e->getMessage() . "<p>The sql is: $sql</p>", __FILE__, __LINE__, $exit = true);
            }

            $pager->setRecTotal($row->recTotal);
            $pager->setPageTotal();
        }
        $this->sqlobj->limit($pager->limit());
        return $this;
    }

    /* 执行sql查询，返回受影响的记录数。autoComapny设定是否自动追加company的查询条件。*/
    public function exec($autoCompany = true)
    {
        /* 如果dao::$errors不为空，返回一个空的stmt对象，这样后续的方法调用还可以继续。*/
        if(!empty(dao::$errors)) return new PDOStatement();

        /* 处理一下SQL语句。*/
        $sql = $this->processSQL($autoCompany);
        try
        {
            $this->reset();
            return $this->dbh->exec($sql);
        }
        catch (PDOException $e) 
        {
            $this->app->error($e->getMessage() . "<p>The sql is: $sql</p>", __FILE__, __LINE__, $exit = true);
        }
    }

    //-------------------- 数据获取相关的方法。--------------------//

    /* 返回一条记录，如果指定了$field字段, 则直接返回该字段对应的值。*/
    public function fetch($field = '', $autoCompany = true)
    {
        if(empty($field)) return $this->query($autoCompany)->fetch();
        $this->setFields($field);
        $result = $this->query($autoCompany)->fetch(PDO::FETCH_OBJ);
        if($result) return $result->$field;
    }

    /* 返回全部的结果。如果指定了$keyField，则以keyField的值作为key。*/
    public function fetchAll($keyField = '', $autoCompany = true)
    {
        $stmt = $this->query($autoCompany);
        if(empty($keyField)) return $stmt->fetchAll();
        $rows = array();
        while($row = $stmt->fetch()) $rows[$row->$keyField] = $row;
        return $rows;
    }

    /* 返回结果并按照某个字段进行分组。*/
    public function fetchGroup($groupField, $keyField = '', $autoCompany = true)
    {
        $stmt = $this->query($autoCompany);
        $rows = array();
        while($row = $stmt->fetch())
        {
            empty($keyField) ?  $rows[$row->$groupField][] = $row : $rows[$row->$groupField][$row->$keyField] = $row;
        }
        return $rows;
    }

    /* fetchPairs方法：如果没有指定key和value字段，则取行字段里面的第一个作为key，最后一个作为value。*/
    public function fetchPairs($keyField = '', $valueField = '', $autoCompany = true)
    {
        $pairs = array();
        $ready = false;
        $stmt  = $this->query($autoCompany);
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if(!$ready)
            {
                if(empty($keyField)) $keyField = key($row);
                if(empty($valueField)) 
                {
                    end($row);
                    $valueField = key($row);
                }
                $ready = true;
            }

            $pairs[$row[$keyField]] = $row[$valueField];
        }
        return $pairs;
    }

    /* 获取最后插入的id。*/
    public function lastInsertID()
    {
        return $this->dbh->lastInsertID();
    }

    //-------------------- 各种魔术方法。--------------------//

    /**
     * 魔术方法，籍此提供各种便利的查询方法。
     * 
     * @param string $funcName  被调用的方法名。
     * @param array  $funcArgs  传入的参数列表。
     * @access public
     * @return void
     */
    public function __call($funcName, $funcArgs)
    {
        /* 将funcName转为小写。*/
        $funcName = strtolower($funcName);

        /* findBy类的方法。*/
        if(strpos($funcName, 'findby') !== false)
        {
            $this->setMode('magic');
            $field = str_replace('findby', '', $funcName);
            if(count($funcArgs) == 1)
            {
                $operator = '=';
                $value    = $funcArgs[0];
            }
            else
            {
                $operator = $funcArgs[0];
                $value    = $funcArgs[1];
            }
            $this->sqlobj = sql::select('%s')->from('%s')->where($field, $operator, $value);    // 使用占位符，执行查询之前替换为相应的字段和表名。
            return $this;
        }
        /* fetch10方法，真正的数据查询。*/
        elseif(strpos($funcName, 'fetch') !== false)
        {
            $max  = str_replace('fetch', '', $funcName);
            $stmt = $this->query();

            /* 设定了key字段。 */
            $rows = array();
            $key  = isset($funcArgs[0]) ? $funcArgs[0] : '';
            $i    = 0;
            while($row = $stmt->fetch())
            {
                $key ? $rows[$row->$key] = $row : $rows[] = $row;
                $i ++;
                if($i == $max) break;
            }
            return $rows;
        }
        /* 其余的都直接调用sql类里面的方法。*/
        else
        {
            /* 取SQL类方法中参数个数最大值，生成一个最大集合的参数列表。。*/
            for($i = 0; $i < SQL::MAX_ARGS; $i ++)
            {
                ${"arg$i"} = isset($funcArgs[$i]) ? $funcArgs[$i] : null;
            }
            $this->sqlobj->$funcName($arg0, $arg1, $arg2);
            return $this;
        }
    }

    //-------------------- 数据检查相关的方法。--------------------//
    
    /* 按照某个规则检查值是否符合要求。*/
    public function check($fieldName, $funcName)
    {
        /* 如果data变量里面没有这个字段，直接返回。*/
        if(!isset($this->sqlobj->data->$fieldName)) return $this;

        /* 引用全局的config, lang。*/
        global $lang, $config, $app;
        $table = strtolower(str_replace($config->db->prefix, '', $this->table));
        $fieldLabel = isset($lang->$table->$fieldName) ? $lang->$table->$fieldName : $fieldName;
        $value = $this->sqlobj->data->$fieldName;
        
        if($funcName == 'unique')
        {
            $args  = func_get_args();
            $sql = "SELECT COUNT(*) AS count FROM $this->table WHERE `$fieldName` = " . $this->sqlobj->quote($value); 
            if($this->table != TABLE_COMPANY) $sql .= " AND company = {$this->app->company->id} ";
            if(isset($args[2])) $sql .= ' AND ' . $args[2];
            try
            {
                 $row = $this->dbh->query($sql)->fetch();
                 if($row->count != 0) $this->logError($funcName, $fieldName, $fieldLabel, array($value));
            }
            catch (PDOException $e) 
            {
                $this->app->error($e->getMessage() . "<p>The sql is: $sql</p>", __FILE__, __LINE__, $exit = true);
            }
        }
        else
        {
            /* 取validate类方法中参数个数最大值，生成一个最大集合的参数列表。。*/
            $funcArgs = func_get_args();
            unset($funcArgs[0]);
            unset($funcArgs[1]);

            for($i = 0; $i < VALIDATER::MAX_ARGS; $i ++)
            {
                ${"arg$i"} = isset($funcArgs[$i + 2]) ? $funcArgs[$i + 2] : null;
            }
            $checkFunc = 'check' . $funcName;
            if(validater::$checkFunc($value, $arg0, $arg1, $arg2) === false)
            {
                $this->logError($funcName, $fieldName, $fieldLabel, $funcArgs);
            }
        }

        return $this;
    }

    /* 如果满足某一个条件，按照某个规则检查值是否符合要求。*/
    public function checkIF($condition, $fieldName, $funcName)
    {
        if(!$condition) return $this;
        $funcArgs = func_get_args();
        for($i = 0; $i < VALIDATER::MAX_ARGS; $i ++)
        {
            ${"arg$i"} = isset($funcArgs[$i + 3]) ? $funcArgs[$i + 3] : null;
        }
        $this->check($fieldName, $funcName, $arg0, $arg1, $arg2);
        return $this;
    }

    /* 批量检查。*/
    public function batchCheck($fields, $funcName)
    {
        $fields = explode(',', str_replace(' ', '', $fields));
        $funcArgs = func_get_args();
        for($i = 0; $i < VALIDATER::MAX_ARGS; $i ++)
        {
            ${"arg$i"} = isset($funcArgs[$i + 2]) ? $funcArgs[$i + 2] : null;
        }
        foreach($fields as $fieldName) $this->check($fieldName, $funcName, $arg0, $arg1, $arg2);
        return $this;
    }

    /* 批量条件检查。*/
    public function batchCheckIF($condition, $fields, $funcName)
    {
        if(!$condition) return $this;
        $fields = explode(',', str_replace(' ', '', $fields));
        $funcArgs = func_get_args();
        for($i = 0; $i < VALIDATER::MAX_ARGS; $i ++)
        {
            ${"arg$i"} = isset($funcArgs[$i + 2]) ? $funcArgs[$i + 2] : null;
        }
        foreach($fields as $fieldName) $this->check($fieldName, $funcName, $arg0, $arg1, $arg2);
        return $this;
    }

    /* 自动根据数据库中表的字段格式进行检查。*/
    public function autoCheck($skipFields = '')
    {
        $fields     = $this->getFieldsType();
        $skipFields = ",$skipFields,";

        foreach($fields as $fieldName => $validater)
        {
            if(strpos($skipFields, $fieldName) !== false) continue;    // 忽略。
            if(!isset($this->sqlobj->data->$fieldName)) continue;
            if($validater['rule'] == 'skip') continue;
            $options = array();
            if(isset($validater['options'])) $options = array_values($validater['options']);
            for($i = 0; $i < VALIDATER::MAX_ARGS; $i ++)
            {
                ${"arg$i"} = isset($options[$i]) ? $options[$i] : null;
            }
            $this->check($fieldName, $validater['rule'], $arg0, $arg1, $arg2);
        }
        return $this;
    }

    /* 记录错误。*/
    public function logError($checkType, $fieldName, $fieldLabel, $funcArgs = array())
    {
        global $lang;
        $error    = $lang->error->$checkType;
        $replaces = array_merge(array($fieldLabel), $funcArgs);

        /* 如果error不是数组，只是字符串，则循环replace，依次替换%s。*/
        if(!is_array($error))
        {
            foreach($replaces as $replace)
            {
                $pos = strpos($error, '%s');
                if($pos === false) break;
                $error = substr($error, 0, $pos) . $replace . substr($error, $pos +2);
            }
        }
        /* 如果error是一个数组，则从数组中挑选%s个数与replace元素个数相同的。*/
        else
        {
            /* 去掉replace中空白的元素。*/
            foreach($replaces as $key => $value) if(is_null($value)) unset($replaces[$key]);
            $replacesCount = count($replaces);
            foreach($error as $errorString)
            {
                if(substr_count($errorString, '%s') == $replacesCount)
                {
                    $error = vsprintf($errorString, $replaces);
                }
            }
        }
        dao::$errors[$fieldName][] = $error;
    }

    /* 判断这次查询是否有错误。*/
    public function isError()
    {
        return !empty(dao::$errors);
    }

    /* 返回error。*/
    public function getError()
    {
        $errors = dao::$errors;
        dao::$errors = array();
        return $errors;
    }

    /* 获得某一个表的字段类型。*/
    private function getFieldsType()
    {
        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC $this->table";
            $rawFields = $this->dbh->query($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e) 
        {
            $this->app->error($e->getMessage() . "<p>The sql is: $sql</p>", __FILE__, __LINE__, $exit = true);
        }

        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny', 'var'), '', $type);
            $field    = array();

            if($type == 'enum' or $type == 'set')
            {
                $rangeBegin  = $firstPOS + 2;  // 将第一个引号去掉。
                $rangeEnd    = strrpos($rawField->type, ')') - 1; // 将最后一个引号去掉。
                $range       = substr($rawField->type, $rangeBegin, $rangeEnd - $rangeBegin);
                $field['rule'] = 'reg';
                $field['options']['reg']  = '/' . str_replace("','", '|', $range) . '/';
            }
            elseif($type == 'char')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['rule']   = 'length';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'int')
            {
                $field['rule'] = 'int';
            }
            elseif($type == 'float' or $type == 'double')
            {
                $field['rule'] = 'float';
            }
            elseif($type == 'date')
            {
                $field['rule'] = 'date';
            }
            else
            {
                $field['rule'] = 'skip';
            }
            $fields[$rawField->field] = $field;
        }
        return $fields;
    }
}

/**
 * SQL查询封装类。
 * 
 * @package ZenTaoPHP
 */
class sql
{
    /**
     * 所有方法的参数个数最大值。
     * 
     */
    const MAX_ARGS = 3;

    /**
     * SQL语句。
     * 
     * @var string
     * @access private
     */
    private $sql = '';

    /**
     * 全局的$dbh（数据库访问句柄）对象。
     * 
     * @var object
     * @access protected
     */
    protected $dbh;

    /**
     * INSERT或者UPDATE时赋给的数据。
     * 
     * @var mix
     * @access protected
     */
    public $data;

    /**
     * 是否是首次调用set。
     * 
     * @var bool    
     * @access private;
     */
    private $isFirstSet = true;

    /**
     * 是否在条件判断中。
     * 
     * @var bool
     * @access private;
     */
    private $inCondition = false;

    /**
     * 判断条件是否为ture。
     * 
     * @var bool
     * @access private;
     */
    private $conditionIsTrue = false;

    /**
     * 是否自动magic quote。
     * 
     * @var bool
     * @access public
     */
     public $magicQuote; 

    /* 构造函数。*/
    private function __construct($table = '')
    {
        global $dbh;
        $this->dbh        = $dbh;
        $this->magicQuote = get_magic_quotes_gpc();
    }

    /* 实例化方法，通过该方法实例对象。*/
    public function factory($table = '')
    {
        return new sql($table);
    }

    /* 查询语句开始。*/
    public function select($field = '*')
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "SELECT $field ";
        return $sqlobj;
    }

    /* 更新语句开始。*/
    public function update($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "UPDATE $table SET ";
        return $sqlobj;
    }

    /* 插入语句开始。*/
    public function insert($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "INSERT INTO $table SET ";
        return $sqlobj;
    }

    /* 替换语句开始。*/
    public function replace($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "REPLACE $table SET ";
        return $sqlobj;
    }

    /* 删除语句开始。*/
    public function delete()
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "DELETE ";
        return $sqlobj;
    }

    /* 给定一个key=>value结构的数组或者对象，拼装成key = value的形式。*/
    public function data($data)
    {
        $this->data = $data;
        foreach($data as $field => $value) $this->sql .= "`$field` = " . $this->quote($value) . ',';
        $this->sql = rtrim($this->sql, ',');    // 去掉最后面的逗号。
        return $this;
    }

    /* 加左边的括弧。*/
    public function markLeft($count = 1)
    {
        $this->sql .= str_repeat('(', $count);
        return $this;
    }

    /* 加右边的括弧。*/
    public function markRight($count = 1)
    {
        $this->sql .= str_repeat(')', $count);
        return $this;
    }

    /* SET key=value。*/
    public function set($set)
    {
        if($this->isFirstSet)
        {
            $this->sql .= " $set ";
            $this->isFirstSet = false;
        }
        else
        {
            $this->sql .= ", $set";
        }
        return $this;
    }

    /* 设定要查询的表名。*/
    public function from($table)
    {
        $this->sql .= "FROM $table";
        return $this;
    }

    /* 设置别名。*/
    public function alias($alias)
    {
        $this->sql .= " AS $alias ";
    }

    /* 设定LEFT JOIN语句。*/
    public function leftJoin($table)
    {
        $this->sql .= " LEFT JOIN $table";
        return $this;
    }

    /* 设定ON条件。*/
    public function on($condition)
    {
        $this->sql .= " ON $condition ";
        return $this;
    }

    /* 条件判断开始。*/
    public function beginIF($condition)
    {
        $this->inCondition = true;
        $this->conditionIsTrue = $condition;
    }

    /* 条件判断结束。*/
    public function fi()
    {
        $this->inCondition = false;
        $this->conditionIsTrue = false;
    }

    /* WHERE语句部分开始。*/
    public function where($arg1, $arg2 = null, $arg3 = null)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if($arg3 !== null)
        {
            $value     = $this->quote($arg3);
            $condition = "`$arg1` $arg2 " . $this->quote($arg3);
        }
        else
        {
            $condition = $arg1;
        }

        $this->sql .= ' ' . DAO::WHERE ." $condition ";
        return $this;
    } 

    /* 追加AND条件。*/
    public function andWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " AND $condition ";
        return $this;
    }

    /* 追加OR条件。*/
    public function orWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " OR $condition ";
        return $this;
    }

    /* 等于。*/
    public function eq($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " = " . $this->quote($value);
        return $this;
    }

    /* 不等于。*/
    public function ne($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " != " . $this->quote($value);
        return $this;
    }

    /* 大于。*/
    public function gt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " > " . $this->quote($value);
        return $this;
    }

    /* 小于。*/
    public function lt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " < " . $this->quote($value);
        return $this;
    }

    /* 生成between语句。*/
    public function between($min, $max)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " BETWEEN $min AND $max ";
        return $this;
    }

    /* 生成 IN部分语句。*/
    public function in($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= helper::dbIN($ids);
        return $this;
    }

    /* 生成 NOTIN部分语句。*/
    public function notin($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= ' NOT ' . helper::dbIN($ids);
        return $this;
    }

    /* 生成LIKE部分语句。*/
    public function like($string)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " LIKE " . $this->quote($string);
        return $this;
    }

    /* 设定ORDER BY。*/
    public function orderBy($order)
    {
        $order = str_replace(array('|', '', '_'), ' ', $order);
        $order = str_replace('left', '`left`', $order); // 处理left关键字。
        $this->sql .= ' ' . DAO::ORDERBY . " $order";
        return $this;
    }

    /* 设定LIMIT。*/
    public function limit($limit)
    {
        if(empty($limit)) return $this;
        stripos($limit, 'limit') !== false ? $this->sql .= " $limit " : $this->sql .= ' ' . DAO::LIMIT . " $limit ";
        return $this;
    }

    /* 设定GROUP BY。*/
    public function groupBy($groupBy)
    {
        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }

    /* 设定having。*/
    public function having($having)
    {
        $this->sql .= ' ' . DAO::HAVING . " $having";
        return $this;
    }

    /* 返回拼装好的语句。*/
    public function get()
    {
        return $this->sql;
    }

    /* 转义。*/
    public function quote($value)
    {
        if($this->magicQuote) $value = stripslashes($value);
        return $this->dbh->quote($value);
    }
}
