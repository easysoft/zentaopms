<?php
/**
 * The dao and sql class file of ZenTaoPHP.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: dao.class.php 134 2010-09-11 07:24:27Z wwccss $
 * @link        http://www.zentao.net
 */

/**
 * DAO, data access object.
 * 
 * @package ZenTaoPHP
 */
class dao
{
    /* Use these strang strings to avoid conflicting with these keywords in the sql body. */
    const WHERE   = 'wHeRe';
    const GROUPBY = 'gRoUp bY';
    const HAVING  = 'hAvInG';
    const ORDERBY = 'oRdEr bY';
    const LIMIT   = 'lImiT';

    /**
     * The global app object.
     * 
     * @var object
     * @access protected
     */
    protected $app;

    /**
     * The global config object.
     * 
     * @var object
     * @access protected
     */
    protected $config;

    /**
     * The global lang object.
     * 
     * @var object
     * @access protected
     */
    protected $lang;

    /**
     * The global dbh(database handler) object.
     * 
     * @var object
     * @access protected
     */
    protected $dbh;

    /**
     * The sql object, used to creat the query sql.
     * 
     * @var object
     * @access protected
     */
    public $sqlobj;

    /**
     * The table of current query.
     * 
     * @var string
     * @access public
     */
    public $table;

    /**
     * The alias of $this->table.
     * 
     * @var string
     * @access public
     */
    public $alias;

    /**
     * The fields will be returned.
     * 
     * @var string
     * @access public
     */
    public $fields;

    /**
     * The query mode, raw or magic.
     * 
     * This var is used to diff dao::from() with sql::from().
     *
     * @var string
     * @access public
     */
    public $mode;

    /**
     * The query method: insert, select, update, delete, replace.
     *
     * @var string
     * @access public
     */
    public $method;

    /**
     * The queries executed. Every query will be saved in this array.
     * 
     * @var array
     * @access public
     */
    static public $querys = array();

    /**
     * The errors.
     * 
     * @var array
     * @access public
     */
    static public $errors = array();

    /**
     * The construct method.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app, $config, $lang, $dbh;
        $this->app    = $app;
        $this->config = $config;
        $this->lang   = $lang;
        $this->dbh    = $dbh;

        $this->reset();
    }

    /**
     * Set the $table property.
     * 
     * @param  string $table 
     * @access private
     * @return void
     */
    private function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Set the $alias property.
     * 
     * @param  string $alias 
     * @access private
     * @return void
     */
    private function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Set the $fields property.
     * 
     * @param  string $fields 
     * @access private
     * @return void
     */
    private function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Reset the vars.
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

    //-------------------- According to the query method, call according method of sql class. --------------------//

    /**
     * Set the query mode. If the method if like findByxxx, the mode is magic. Else, the mode is raw.
     * 
     * @param  string $mode     magic|raw
     * @access private
     * @return void
     */
    private function setMode($mode = '')
    {
        $this->mode = $mode;
    }

    /**
     * Set the query method: select|update|insert|delete|replace 
     * 
     * @param  string $method 
     * @access private
     * @return void
     */
    private function setMethod($method = '')
    {
        $this->method = $method;
    }

    /**
     * The select method, call sql::select().
     * 
     * @param  string $fields 
     * @access public
     * @return object the dao object self.
     */
    public function select($fields = '*')
    {
        $this->setMode('raw');
        $this->setMethod('select');
        $this->sqlobj = sql::select($fields);
        return $this;
    }

    /**
     * The select method, call sql::update().
     * 
     * @param  string $table 
     * @access public
     * @return object the dao object self.
     */
    public function update($table)
    {
        $this->setMode('raw');
        $this->setMethod('update');
        $this->sqlobj = sql::update($table);
        $this->setTable($table);
        return $this;
    }

    /**
     * The delete method, call sql::delete().
     * 
     * @access public
     * @return object
     */
    public function delete()
    {
        $this->setMode('raw');
        $this->setMethod('delete');
        $this->sqlobj = sql::delete();
        return $this;
    }

    /**
     * The insert method, call sql::insert().
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function insert($table)
    {
        $this->setMode('raw');
        $this->setMethod('insert');
        $this->sqlobj = sql::insert($table);
        $this->setTable($table);
        return $this;
    }

    /**
     * The replace method, call sql::replace().
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function replace($table)
    {
        $this->setMode('raw');
        $this->setMethod('replace');
        $this->sqlobj = sql::replace($table);
        $this->setTable($table);
        return $this;
    }

    /**
     * Set the from table.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function from($table) 
    {
        $this->setTable($table);
        if($this->mode == 'raw') $this->sqlobj->from($table);
        return $this;
    }

    /**
     * Set the fields.
     * 
     * @param  string $fields 
     * @access public
     * @return object
     */
    public function fields($fields)
    {
        $this->setFields($fields);
        return $this;
    }

    /**
     * Alias a table, equal the AS keyword. (Don't use AS, because it's a php keyword.)
     * 
     * @param  string $alias 
     * @access public
     * @return object
     */
    public function alias($alias)
    {
        if(empty($this->alias)) $this->setAlias($alias);
        $this->sqlobj->alias($alias);
        return $this;
    }

    /**
     * Set the data to update or insert.
     * 
     * @param  object $data         the data object or array
     * @param  bool   $autoCompany  auto append company field or not
     * @access public
     * @return object
     */
    public function data($data, $autoCompany = true)
    {
        if(!is_object($data)) $data = (object)$data;
        if($autoCompany and isset($this->app->company) and $this->table != TABLE_COMPANY and !isset($data->company)) $data->company = $this->app->company->id;
        $this->sqlobj->data($data);
        return $this;
    }

    //-------------------- The sql related method. --------------------//

    /**
     * Get the sql string.
     * 
     * @access public
     * @return string
     */
    public function get()
    {
        return $this->processKeywords($this->processSQL());
    }

    /**
     * Print the sql string.
     * 
     * @access public
     * @return void
     */
    public function printSQL()
    {
        echo $this->processSQL();
    }

    /**
     * Process the sql, replace the table, fields and add the company condition.
     * 
     * @param  bool     $autoCompany 
     * @access private
     * @return string
     */
    private function processSQL($autoCompany = true)
    {
        $sql = $this->sqlobj->get();

        /* If the mode is magic, process the $fields and $table. */
        if($this->mode == 'magic')
        {
            if($this->fields == '') $this->fields = '*';
            if($this->table == '')  $this->app->error('Must set the table name', __FILE__, __LINE__, $exit = true);
            $sql = sprintf($this->sqlobj->get(), $this->fields, $this->table);
        }

        /* If the method if select, update or delete, set the comapny condition. */
        if(isset($this->app->company) and $autoCompany and $this->table != '' and $this->table != TABLE_COMPANY and $this->method != 'insert' and $this->method != 'replace')
        {
            /* Get the position to insert company = ?. */
            $wherePOS  = strrpos($sql, DAO::WHERE);             // The position of WHERE keyword.
            $groupPOS  = strrpos($sql, DAO::GROUPBY);           // The position of GROUP BY keyword.
            $havingPOS = strrpos($sql, DAO::HAVING);            // The position of HAVING keyword.
            $orderPOS  = strrpos($sql, DAO::ORDERBY);           // The position of ORDERBY keyword.
            $limitPOS  = strrpos($sql, DAO::LIMIT);             // The position of LIMIT keyword.
            $splitPOS  = $orderPOS ? $orderPOS : $limitPOS;     // If $orderPOS, use it instead of $limitPOS.
            $splitPOS  = $havingPOS? $havingPOS: $splitPOS;     // If $havingPOS, use it instead of $orderPOS.
            $splitPOS  = $groupPOS ? $groupPOS : $splitPOS;     // If $groupPOS, use it instead of $havingPOS.

            /* Set the conditon to be appened. */
            $tableName = !empty($this->alias) ? $this->alias : $this->table;
            $companyCondition = " $tableName.company = '{$this->app->company->id}' ";

            /* If $spliPOS > 0, split the sql at $splitPOS. */
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

    /**
     * Process the sql keywords, replace the constants to normal.
     * 
     * @param  string $sql 
     * @access private
     * @return string
     */
    private function processKeywords($sql)
    {
        return str_replace(array(DAO::WHERE, DAO::GROUPBY, DAO::HAVING, DAO::ORDERBY, DAO::LIMIT), array('WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'), $sql);
    }

    //-------------------- Query related methods. --------------------//
    
    /**
     * Set the dbh. 
     * 
     * You can use like this: $this->dao->dbh($dbh), thus you can handle two database.
     *
     * @param  object $dbh 
     * @access public
     * @return object
     */
    public function dbh($dbh)
    {
        $this->dbh = $dbh;
        return $this;
    }

    /**
     * Query the sql, return the statement object.
     * 
     * @param  bool     $autoCompany 
     * @access public
     * @return object   the PDOStatement object.
     */
    public function query($autoCompany = true)
    {
        if(!empty(dao::$errors)) return new PDOStatement();   // If any error, return an empty statement object to make sure the remain method to execute.

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

    /**
     * Page the records, set the limit part auto.
     * 
     * @param  object $pager 
     * @access public
     * @return object
     */
    public function page($pager)
    {
        if(!is_object($pager)) return $this;

        /* If the record total is 0, compute it. */
        if($pager->recTotal == 0)
        {
            /* Get the SELECT, FROM position, thus get the fields, replace it by count(*). */
            $sql       = $this->get();
            $selectPOS = strpos($sql, 'SELECT') + strlen('SELECT');
            $fromPOS   = strpos($sql, 'FROM');
            $fields    = substr($sql, $selectPOS, $fromPOS - $selectPOS );
            $sql       = str_replace($fields, ' COUNT(*) AS recTotal ', $sql);

            /* Remove the part after order and limit. */
            $subLength = strlen($sql);
            $orderPOS  = strripos($sql, 'order');
            $limitPOS  = strripos($sql , 'limit');
            if($limitPOS) $subLength = $limitPOS;
            if($orderPOS) $subLength = $orderPOS;
            $sql = substr($sql, 0, $subLength);
            self::$querys[] = $sql;

            /* Get the records count. */
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

    /**
    /* Execute the sql. It's different with query(), which return the stmt object. But this not.
     * 
     * @param  bool     $autoCompany 
     * @access public
     * @return int      the modified or deleted records.
     */
    public function exec($autoCompany = true)
    {
        if(!empty(dao::$errors)) return new PDOStatement();   // If any error, return an empty statement object to make sure the remain method to execute.

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

    /**
     * Fetch one record.
     * 
     * @param  string $field        if the field is set, only return the value of this field, else return this record
     * @param  bool   $autoCompany 
     * @access public
     * @return misc
     */
    public function fetch($field = '', $autoCompany = true)
    {
        if(empty($field)) return $this->query($autoCompany)->fetch();
        $this->setFields($field);
        $result = $this->query($autoCompany)->fetch(PDO::FETCH_OBJ);
        if($result) return $result->$field;
    }

    /**
     * Fetch all records.
     * 
     * @param  string $keyField     the key field, thus the return records is keyed by this field
     * @param  bool   $autoCompany 
     * @access public
     * @return array
     */
    public function fetchAll($keyField = '', $autoCompany = true)
    {
        $stmt = $this->query($autoCompany);
        if(empty($keyField)) return $stmt->fetchAll();
        $rows = array();
        while($row = $stmt->fetch()) $rows[$row->$keyField] = $row;
        return $rows;
    }

    /**
     * Fetch all records and group them by one field.
     * 
     * @param  string $groupField   the field to group by
     * @param  string $keyField     the field of key
     * @param  bool   $autoCompany 
     * @access public
     * @return array
     */
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

    /**
     * Fetch array like key=>value.
     *
     * If the keyFiled and valueField not set, use the first and last in the record.
     * 
     * @param  string $keyField 
     * @param  string $valueField 
     * @param  bool   $autoCompany 
     * @access public
     * @return array
     */
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

    /**
     * Return the last insert ID.
     * 
     * @access public
     * @return int
     */
    public function lastInsertID()
    {
        return $this->dbh->lastInsertID();
    }

    //-------------------- Magic methods.--------------------//

    /**
     * Use it to do some convenient queries.
     * 
     * @param  string $funcName  the function name to be called
     * @param  array  $funcArgs  the params
     * @access public
     * @return void
     */
    public function __call($funcName, $funcArgs)
    {
        $funcName = strtolower($funcName);

        /* findByxxx, xxx as will be in the where. */
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
            $this->sqlobj = sql::select('%s')->from('%s')->where($field, $operator, $value);
            return $this;
        }
        /* Fetch10. */
        elseif(strpos($funcName, 'fetch') !== false)
        {
            $max  = str_replace('fetch', '', $funcName);
            $stmt = $this->query();

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
        /* Others, call the method in sql class. */
        else
        {
            /* Create the max counts of sql class methods, and then create $arg0, $arg1... */
            for($i = 0; $i < SQL::MAX_ARGS; $i ++)
            {
                ${"arg$i"} = isset($funcArgs[$i]) ? $funcArgs[$i] : null;
            }
            $this->sqlobj->$funcName($arg0, $arg1, $arg2);
            return $this;
        }
    }

    //-------------------- Checking.--------------------//
    
    /**
     * Check a filed is satisfied with the check rule.
     * 
     * @param  string $fieldName    the field to check
     * @param  string $funcName     the check rule
     * @access public
     * @return object
     */
    public function check($fieldName, $funcName)
    {
        /* If no this field in the data, reuturn. */
        if(!isset($this->sqlobj->data->$fieldName)) return $this;

        /* Set the field label and value. */
        global $lang, $config, $app;
        $table      = strtolower(str_replace($config->db->prefix, '', $this->table));
        $fieldLabel = isset($lang->$table->$fieldName) ? $lang->$table->$fieldName : $fieldName;
        $value = $this->sqlobj->data->$fieldName;
        
        /* Check unique. */
        if($funcName == 'unique')
        {
            $args = func_get_args();
            $sql  = "SELECT COUNT(*) AS count FROM $this->table WHERE `$fieldName` = " . $this->sqlobj->quote($value); 
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
            /* Create the params. */
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

    /**
     * Check a field, if satisfied with the condition.
     * 
     * @param  string $condition 
     * @param  string $fieldName 
     * @param  string $funcName 
     * @access public
     * @return object
     */
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

    /**
     * Batch check some fileds.
     * 
     * @param  string $fields       the fields to check, join with ,
     * @param  string $funcName 
     * @access public
     * @return object
     */
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

    /**
     * Batch check fields on the condition is true.
     * 
     * @param  string $condition 
     * @param  string $fields 
     * @param  string $funcName 
     * @access public
     * @return object
     */
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

    /**
     * Check the fields according the the database schema.
     * 
     * @param  string $skipFields   fields to skip checking
     * @access public
     * @return object
     */
    public function autoCheck($skipFields = '')
    {
        $fields     = $this->getFieldsType();
        $skipFields = ",$skipFields,";

        foreach($fields as $fieldName => $validater)
        {
            if(strpos($skipFields, $fieldName) !== false) continue; // skip it.
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

    /**
     * Log the error.
     * 
     * For the error notice, see module/common/lang.
     *
     * @param  string $checkType    the check rule
     * @param  string $fieldName    the field name
     * @param  string $fieldLabel   the field label
     * @param  array  $funcArgs     the args
     * @access public
     * @return void
     */
    public function logError($checkType, $fieldName, $fieldLabel, $funcArgs = array())
    {
        global $lang;
        $error    = $lang->error->$checkType;
        $replaces = array_merge(array($fieldLabel), $funcArgs);     // the replace values.

        /* Just a string, cycle the $replaces. */
        if(!is_array($error))
        {
            foreach($replaces as $replace)
            {
                $pos = strpos($error, '%s');
                if($pos === false) break;
                $error = substr($error, 0, $pos) . $replace . substr($error, $pos + 2);
            }
        }
        /* If the error define is an array, select the one which %s counts match the $replaces.  */
        else
        {
            /* Remove the empty items. */
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

    /**
     * Judge any error or not.
     * 
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty(dao::$errors);
    }

    /**
     * Get the errors.
     * 
     * @access public
     * @return array
     */
    public function getError()
    {
        $errors = dao::$errors;
        dao::$errors = array();     // Must clear it.
        return $errors;
    }

    /**
     * Get the defination of fields of the table.
     * 
     * @access private
     * @return array
     */
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
 * The SQL class.
 * 
 * @package ZenTaoPHP
 */
class sql
{
    /**
     * The max count of params of all methods.
     * 
     */
    const MAX_ARGS = 3;

    /**
     * The sql string.
     * 
     * @var string
     * @access private
     */
    private $sql = '';

    /**
     * The global $dbh.
     * 
     * @var object
     * @access protected
     */
    protected $dbh;

    /**
     * The data to update or insert.
     * 
     * @var mix
     * @access protected
     */
    public $data;

    /**
     * Is the first time to  call set.
     * 
     * @var bool    
     * @access private;
     */
    private $isFirstSet = true;

    /**
     * If in the logic of judge condition or not.
     * 
     * @var bool
     * @access private;
     */
    private $inCondition = false;

    /**
     * The condition is true or not.
     * 
     * @var bool
     * @access private;
     */
    private $conditionIsTrue = false;

    /**
     * Magic quote or not.
     * 
     * @var bool
     * @access public
     */
     public $magicQuote; 

    /**
     * The construct function. user factory() to instance it.
     * 
     * @param  string $table 
     * @access private
     * @return void
     */
    private function __construct($table = '')
    {
        global $dbh;
        $this->dbh        = $dbh;
        $this->magicQuote = get_magic_quotes_gpc();
    }

    /**
     * The factory method.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function factory($table = '')
    {
        return new sql($table);
    }

    /**
     * The sql is select.
     * 
     * @param  string $field 
     * @access public
     * @return object
     */
    public function select($field = '*')
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "SELECT $field ";
        return $sqlobj;
    }

    /**
     * The sql is update.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function update($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "UPDATE $table SET ";
        return $sqlobj;
    }

    /**
     * The sql is insert.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function insert($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "INSERT INTO $table SET ";
        return $sqlobj;
    }

    /**
     * The sql is replace.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function replace($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "REPLACE $table SET ";
        return $sqlobj;
    }

    /**
     * The sql is delete.
     * 
     * @access public
     * @return object
     */
    public function delete()
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "DELETE ";
        return $sqlobj;
    }

    /**
     * Join the data items by key = value.
     * 
     * @param  object $data 
     * @access public
     * @return object
     */
    public function data($data)
    {
        $this->data = $data;
        foreach($data as $field => $value) $this->sql .= "`$field` = " . $this->quote($value) . ',';
        $this->sql = rtrim($this->sql, ',');    // Remove the last ','.
        return $this;
    }

    /**
     * Aadd an '(' at left.
     * 
     * @param  int    $count 
     * @access public
     * @return ojbect
     */
    public function markLeft($count = 1)
    {
        $this->sql .= str_repeat('(', $count);
        return $this;
    }

    /**
     * Add an ')' ad right.
     * 
     * @param  int    $count 
     * @access public
     * @return object
     */
    public function markRight($count = 1)
    {
        $this->sql .= str_repeat(')', $count);
        return $this;
    }

    /**
     * The set part.
     * 
     * @param  string $set 
     * @access public
     * @return object
     */
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

    /**
     * Create the from part.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function from($table)
    {
        $this->sql .= "FROM $table";
        return $this;
    }

    /**
     * Create the Alias part.
     * 
     * @param  string $alias 
     * @access public
     * @return object
     */
    public function alias($alias)
    {
        $this->sql .= " AS $alias ";
        return $this;
    }

    /**
     * Create the left join part.
     * 
     * @param  string $table 
     * @access public
     * @return object
     */
    public function leftJoin($table)
    {
        $this->sql .= " LEFT JOIN $table";
        return $this;
    }

    /**
     * Create the on part.
     * 
     * @param  string $condition 
     * @access public
     * @return object
     */
    public function on($condition)
    {
        $this->sql .= " ON $condition ";
        return $this;
    }

    /**
     * Begin condition judge.
     * 
     * @param  bool $condition 
     * @access public
     * @return object
     */
    public function beginIF($condition)
    {
        $this->inCondition = true;
        $this->conditionIsTrue = $condition;
        return $this;
    }

    /**
     * End the condition judge.
     * 
     * @access public
     * @return object
     */
    public function fi()
    {
        $this->inCondition = false;
        $this->conditionIsTrue = false;
        return $this;
    }

    /**
     * Create the where part.
     * 
     * @param  string $arg1     the field name
     * @param  string $arg2     the operator
     * @param  string $arg3     the value
     * @access public
     * @return object
     */
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

    /**
     * Create the AND part.
     * 
     * @param  string $condition 
     * @access public
     * @return object
     */
    public function andWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " AND $condition ";
        return $this;
    }

    /**
     * Create the OR part.
     * 
     * @param  bool  $condition 
     * @access public
     * @return object
     */
    public function orWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " OR $condition ";
        return $this;
    }

    /**
     * Create the '='.
     * 
     * @param  string $value 
     * @access public
     * @return object
     */
    public function eq($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " = " . $this->quote($value);
        return $this;
    }

    /**
     * Create '!='.
     * 
     * @param  string $value 
     * @access public
     * @return void
     */
    public function ne($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " != " . $this->quote($value);
        return $this;
    }

    /**
     * Create '>'.
     * 
     * @param  string $value 
     * @access public
     * @return object
     */
    public function gt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " > " . $this->quote($value);
        return $this;
    }

    /**
     * Create '<'.
     * 
     * @param  mixed  $value 
     * @access public
     * @return object
     */
    public function lt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " < " . $this->quote($value);
        return $this;
    }

    /**
     * Create "between and"
     * 
     * @param  string $min 
     * @param  string $max 
     * @access public
     * @return object
     */
    public function between($min, $max)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " BETWEEN $min AND $max ";
        return $this;
    }

    /**
     * Create in part.
     * 
     * @param  mixed $ids   list string by ',' or an array
     * @access public
     * @return object
     */
    public function in($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= helper::dbIN($ids);
        return $this;
    }

    /**
     * Create not in part.
     * 
     * @param  mixed $ids   list string by ',' or an array
     * @access public
     * @return object
     */
    public function notin($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= ' NOT ' . helper::dbIN($ids);
        return $this;
    }

    /**
     * Create the like by part.
     * 
     * @param  string $string 
     * @access public
     * @return object
     */
    public function like($string)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " LIKE " . $this->quote($string);
        return $this;
    }

    /**
     * Create the order by part.
     * 
     * @param  string $order 
     * @access public
     * @return object
     */
    public function orderBy($order)
    {
        $order = str_replace(array('|', '', '_'), ' ', $order);
        $order = str_replace('left', '`left`', $order); // process the left to `left`.
        $this->sql .= ' ' . DAO::ORDERBY . " $order";
        return $this;
    }

    /**
     * Create the limit part.
     * 
     * @param  string $limit 
     * @access public
     * @return object
     */
    public function limit($limit)
    {
        if(empty($limit)) return $this;
        stripos($limit, 'limit') !== false ? $this->sql .= " $limit " : $this->sql .= ' ' . DAO::LIMIT . " $limit ";
        return $this;
    }

    /**
     * Create the groupby part.
     * 
     * @param  string $groupBy 
     * @access public
     * @return object
     */
    public function groupBy($groupBy)
    {
        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }

    /**
     * Create the having part.
     * 
     * @param  string $having 
     * @access public
     * @return object
     */
    public function having($having)
    {
        $this->sql .= ' ' . DAO::HAVING . " $having";
        return $this;
    }

    /**
     * Get the sql string.
     * 
     * @access public
     * @return string
     */
    public function get()
    {
        return $this->sql;
    }

    /**
     * Uuote a var.
     * 
     * @param  mixed  $value 
     * @access public
     * @return mixed
     */
    public function quote($value)
    {
        if($this->magicQuote) $value = stripslashes($value);
        return $this->dbh->quote($value);
    }
}
