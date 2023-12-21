<?php
/**
 * ZenTaoPHP的dao和sql类。
 * The dao and sql class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * DAO类。
 * DAO, data access object.
 *
 * @package framework
 */
class baseDAO
{
    /* Use these strang strings to avoid conflicting with these keywords in the sql body. */
    const WHERE   = 'wHeRe';
    const GROUPBY = 'gRoUp bY';
    const HAVING  = 'hAvInG';
    const ORDERBY = 'oRdEr bY';
    const LIMIT   = 'lImiT';

    /**
     * 全局对象$app
     * The global app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 全局对象$config
     * The global config object.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * 全局对象$lang
     * The global lang object.
     *
     * @var object
     * @access public
     */
    public $lang;

    /**
     * 全局对象$dbh
     * The global dbh(database handler) object.
     *
     * @var object
     * @access public
     */
    public $dbh;

    /**
     * 全局对象$slaveDBH。
     * The global slaveDBH(database handler) object.
     *
     * @var object
     * @access public
     */
    public $slaveDBH;

    /**
     * sql对象，用于生成sql语句。
     * The sql object, used to create the query sql.
     *
     * @var object
     * @access public
     */
    public $sqlobj;

    /**
     * 正在使用的表。
     * The table of current query.
     *
     * @var string
     * @access public
     */
    public $table;

    /**
     * $this->table的别名。
     * The alias of $this->table.
     *
     * @var string
     * @access public
     */
    public $alias;

    /**
     * 查询的字段。
     * The fields will be returned.
     *
     * @var string
     * @access public
     */
    public $fields;

    /**
     * 查询模式，raw模式用于正常的select update等sql拼接操作，magic模式用于findByXXX等魔术方法。
     * The query mode, raw or magic.
     *
     * This var is used to diff dao::from() with sql::from().
     *
     * @var string
     * @access public
     */
    public $mode;

    /**
     * 执行方式：insert, select, update, delete, replace。
     * The query method: insert, select, update, delete, replace.
     *
     * @var string
     * @access public
     */
    public $method;

    /**
     * 是否自动增加lang条件。
     * If auto add lang statement.
     *
     * @var bool
     * @access public
     */
    public $autoLang;

    /**
     * 执行的请求，所有的查询都保存在该数组。
     * The queries executed. Every query will be saved in this array.
     *
     * @var array
     * @access public
     */
    static public $querys = array();

    /**
     * 存放错误的数组。
     * The errors.
     *
     * @var array
     * @access public
     */
    static public $errors = array();

    /**
     * 缓存。
     * The cache.
     *
     * @var array
     * @access public
     */
    static public $cache = array();

    /**
     * 实时记录日志设置，并设置记录文件。
     * Open real time log and set real time file.
     *
     * @var array
     * @access public
     */
    static public $realTimeLog  = false;
    static public $realTimeFile = '';

    /**
     * 构造方法。
     * The construct method.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app, $config, $lang, $dbh, $slaveDBH;
        $this->app      = $app;
        $this->config   = $config;
        $this->lang     = $lang;
        $this->dbh      = $dbh;
        $this->slaveDBH = $slaveDBH ? $slaveDBH : false;

        $this->reset();
    }

    /**
     * 设置$table属性。
     * Set the $table property.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * 设置$alias属性。
     * Set the $alias property.
     *
     * @param  string $alias
     * @access public
     * @return void
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * 设置$fields属性。
     * Set the $fields property.
     *
     * @param  string $fields
     * @access public
     * @return void
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * 设置autoLang项。
     * Set autoLang item.
     *
     * @param  bool    $autoLang
     * @access public
     * @return void
     */
    public function setAutoLang($autoLang)
    {
        $this->autoLang = $autoLang;
        return $this;
    }

    /**
     * 重置属性。
     * Reset the vars.
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        $this->setFields('');
        $this->setTable('');
        $this->setAlias('');
        $this->setMode('');
        $this->setMethod('');
        $this->setAutoLang(isset($this->config->framework->autoLang) and $this->config->framework->autoLang);
    }

    //-----根据请求的方式，调用sql类相应的方法(Call according method of sql class by query method. -----//

    /**
     * 设置请求模式。像findByxxx之类的方法，使用的是magic模式；其他方法使用的是raw模式。
     * Set the query mode. If the method if like findByxxx, the mode is magic. Else, the mode is raw.
     *
     * @param  string $mode     magic|raw
     * @access public
     * @return void
     */
    public function setMode($mode = '')
    {
        $this->mode = $mode;
    }

    /**
     * 设置请求方法：select|update|insert|delete|replace 。
     * Set the query method: select|update|insert|delete|replace
     *
     * @param  string $method
     * @access public
     * @return void
     */
    public function setMethod($method = '')
    {
        $this->method = $method;
    }

    /**
     * 开始事务。
     * Begin Transaction
     *
     * @access public
     * @return void
     */
    public function begin()
    {
        $this->dbh->beginTransaction();
    }

    /**
     * 检查是否在事务内。
     * Check in transaction.
     *
     * @access public
     * @return bool
     */
    public function inTransaction()
    {
        return $this->dbh->inTransaction();
    }

    /**
     * 事务回滚。
     * Roll back
     *
     * @access public
     * @return void
     */
    public function rollBack()
    {
        $this->dbh->rollBack();
    }

    /**
     * 提交事务。
     * Commits a transaction.
     *
     * @access public
     * @return void
     */
    public function commit()
    {
        $this->dbh->commit();
    }

    /**
     * Show tables.
     *
     * @access public
     * @return array
     */
    public function showTables()
    {
        return $this->query("SHOW TABLES")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get table engines.
     *
     * @access public
     * @return array
     */
    public function getTableEngines()
    {
        $tables = $this->query("SHOW TABLE STATUS WHERE `Engine` is not null")->fetchAll();
        $tableEngines = array();
        foreach($tables as $table) $tableEngines[$table->Name] = $table->Engine;

        return $tableEngines;
    }

    /**
     * Desc table, show fields.
     *
     * @param  string $tableName
     * @access public
     * @return array
     */
    public function descTable($tableName)
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $fields = $this->query("DESC $tableName")->fetchAll();
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        return $fields;
    }

    /**
     * select方法，调用sql::select()。
     * The select method, call sql::select().
     *
     * @param  string $fields
     * @access public
     * @return static|sql|baseDAO the dao object self.
     */
    public function select($fields = '*')
    {
        $this->setMode('raw');
        $this->setMethod('select');
        $this->sqlobj = sql::select($fields);
        return $this;
    }

    /**
     * 获取查询记录条数。
     * The count method, call sql::select() and from().
     * use as $this->dao->select()->from(TABLE_BUG)->where()->count();
     *
     * @param  string $distinctField
     * @access public
     * @return int
     */
    public function count($distinctField = '')
    {
        /* 获得SELECT，FROM的位置，使用count(*)替换其字段。 */
        /* Get the SELECT, FROM position, thus get the fields, replace it by count(*). */
        $sql        = $this->get();
        $selectPOS  = strpos($sql, 'SELECT') + strlen('SELECT');
        $fromPOS    = strpos($sql, 'FROM');
        $fields     = substr($sql, $selectPOS, $fromPOS - $selectPOS);
        $countField = $distinctField ? 'distinct ' . $distinctField : '*';
        $sql        = str_replace($fields, " COUNT($countField) AS recTotal ", substr($sql, 0, $fromPOS)) . substr($sql, $fromPOS);

        /*
         * 去掉SQL语句中order和limit之后的部分。
         * Remove the part after order and limit.
         **/
        $subLength = strlen($sql);
        $groupPOS  = strripos($sql, 'group by');
        $orderPOS  = strripos($sql, 'order by');
        $limitPOS  = strripos($sql, 'limit');
        if($limitPOS) $subLength = $limitPOS;
        if($orderPOS) $subLength = $orderPOS;
        if($groupPOS) $subLength = $groupPOS;
        $sql = substr($sql, 0, $subLength);

        /*
         * 获取记录数。
         * Get the records count.
         **/
        try
        {
            $dbh = $this->slaveDBH ? $this->slaveDBH : $this->dbh;
            $row = $dbh->rawQuery($sql)->fetch(PDO::FETCH_OBJ);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }

        return is_object($row) ? $row->recTotal : 0;
    }

    /**
     * update方法，调用sql::update()。
     * The update method, call sql::update().
     *
     * @param  string $table
     * @access public
     * @return static|sql the dao object self.
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
     * delete方法，调用sql::delete()。
     * The delete method, call sql::delete().
     *
     * @access public
     * @return static|sql the dao object self.
     */
    public function delete()
    {
        $this->setMode('raw');
        $this->setMethod('delete');
        $this->sqlobj = sql::delete();
        return $this;
    }

    /**
     * insert方法，调用sql::insert()。
     * The insert method, call sql::insert().
     *
     * @param  string $table
     * @access public
     * @return static|sql the dao object self.
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
     * replace方法，调用sql::replace()。
     * The replace method, call sql::replace().
     *
     * @param  string $table
     * @access public
     * @return static|sql the dao object self.
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
     * 设置要操作的表。
     * Set the from table.
     *
     * @param  string $table
     * @access public
     * @return static|sql the dao object self.
     */
    public function from($table)
    {
        $this->setTable($table);
        if($this->mode == 'raw') $this->sqlobj->from($table);
        return $this;
    }

    /**
     * 设置字段。
     * Set the fields.
     *
     * @param  string $fields
     * @access public
     * @return static|sql the dao object self.
     */
    public function fields($fields)
    {
        $this->setFields($fields);
        return $this;
    }

    /**
     * 表别名，相当于sql里的AS。（as是php的关键词，使用alias代替）
     * Alias a table, equal the AS keyword. (Don't use AS, because it's a php keyword.)
     *
     * @param  string $alias
     * @access public
     * @return static|sql the dao object self.
     */
    public function alias($alias)
    {
        if(empty($this->alias)) $this->setAlias($alias);
        $this->sqlobj->alias($alias);
        return $this;
    }

    /**
     * 设置需要更新或插入的数据。
     * Set the data to update or insert.
     *
     * @param  object $data  the data object or array
     * @param  string $skipFields the  skip fields
     * @access public
     * @return static|sql the dao object self.
     */
    public function data($data, $skipFields = '')
    {
        if(!is_object($data)) $data = (object)$data;
        if($this->autoLang and !isset($data->lang))
        {
            $data->lang = $this->app->getClientLang();
            if(isset($this->app->config->cn2tw) and $this->app->config->cn2tw and $data->lang == 'zh-tw') $data->lang = 'zh-cn';
            if(defined('RUN_MODE') and RUN_MODE == 'front' and !empty($this->app->config->cn2tw)) $data->lang = str_replace('zh-tw', 'zh-cn', $data->lang);
        }

        $this->sqlobj->data($data, $skipFields);
        return $this;
    }

    //-------------------- sql相关的方法(The sql related method) --------------------//

    /**
     * 获取sql字符串。
     * Get the sql string.
     *
     * @access public
     * @return string the sql string after process.
     */
    public function get()
    {
        return self::processKeywords($this->processSQL());
    }

    /**
     * 打印sql字符串。
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
     * 解析SQL语句。
     * Explain sql.
     *
     * @param  string $sql
     * @access public
     * @return void
     */
    public function explain($sql = '')
    {
        $sql    = empty($sql) ? $this->processSQL() : $sql;
        $result = $this->dbh->rawQuery('explain ' . $sql)->fetch();
        a($result);
    }

    /**
     * 处理sql语句，替换表和字段。
     * Process the sql, replace the table, fields.
     *
     * @access public
     * @return string the sql string after process.
     */
    public function processSQL()
    {
        $sql = $this->sqlobj->get();

        /* INSERT INTO table VALUES(...) */
        if($this->method == 'insert' and !empty($this->sqlobj->data))
        {
            $skipFields = $this->sqlobj->skipFields;
            $fields = '(';
            $values = 'VALUES(';
            foreach($this->sqlobj->data as $field => $value)
            {
                if(strpos($skipFields, ",$field,") !== false) continue;
                $fields .= "`{$field}`,";
                if(is_string($value) or $value === null) $value = $this->sqlobj->quote($value);
                $values .= $value . ',';
            }
            $fields = substr($fields, 0, -1);
            $values = substr($values, 0, -1);
            $fields .= ')';
            $values .= ')';
            $sql .= $fields . ' ' . $values;
        }

        /**
         * 如果是magic模式，处理表和字段。
         * If the mode is magic, process the $fields and $table.
         **/
        if($this->mode == 'magic')
        {
            if($this->fields == '') $this->fields = '*';
            if($this->table == '')  $this->app->triggerError('Must set the table name', __FILE__, __LINE__, $exit = true);
            $sql = sprintf($this->sqlobj->get(), $this->fields, $this->table);
        }

        /* If the method is select, update or delete, set the lang condition. */
        if($this->autoLang and $this->table != '' and $this->method != 'insert' and $this->method != 'replace')
        {
            $lang = $this->app->getClientLang();

            /* Get the position to insert lang = ?. */
            $wherePOS  = strrpos($sql, DAO::WHERE);             // The position of WHERE keyword.
            $groupPOS  = strrpos($sql, DAO::GROUPBY);           // The position of GROUP BY keyword.
            $havingPOS = strrpos($sql, DAO::HAVING);            // The position of HAVING keyword.
            $orderPOS  = strrpos($sql, DAO::ORDERBY);           // The position of ORDERBY keyword.
            $limitPOS  = strrpos($sql, DAO::LIMIT);             // The position of LIMIT keyword.
            $splitPOS  = $orderPOS  ? $orderPOS  : $limitPOS;   // If $orderPOS, use it instead of $limitPOS.
            $splitPOS  = $havingPOS ? $havingPOS : $splitPOS;   // If $havingPOS, use it instead of $orderPOS.
            $splitPOS  = $groupPOS  ? $groupPOS  : $splitPOS;   // If $groupPOS, use it instead of $havingPOS.

            /* Set the condition to be appended. */
            $tableName = !empty($this->alias) ? $this->alias : $this->table;

            if(!empty($this->app->config->cn2tw)) $lang = str_replace('zh-tw', 'zh-cn', $lang);

            $langCondition = " $tableName.lang in('{$lang}', 'all') ";

            /* If $splitPOS > 0, split the sql at $splitPOS. */
            if($splitPOS)
            {
                $firstPart = substr($sql, 0, $splitPOS);
                $lastPart  = substr($sql, $splitPOS);
                if($wherePOS)
                {
                    $sql = $firstPart . " AND $langCondition " . $lastPart;
                }
                else
                {
                    $sql = $firstPart . " WHERE $langCondition " . $lastPart;
                }
            }
            else
            {
                $sql .= $wherePOS ? " AND $langCondition" : " WHERE $langCondition";
            }
        }

        return $sql;
    }

    /**
     * 替换sql常量关键字。
     * Process the sql keywords, replace the constants to normal.
     *
     * @param  string $sql
     * @access public
     * @return string the sql string.
     */
    static public function processKeywords($sql)
    {
        return str_replace(array(DAO::WHERE, DAO::GROUPBY, DAO::HAVING, DAO::ORDERBY, DAO::LIMIT), array('WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'), $sql);
    }

    //-------------------- 查询相关方法(Query related methods) --------------------//

    /**
     * 设置$dbh，数据库连接句柄。
     * Set the dbh.
     *
     * You can use like this: $this->dao->dbh($dbh), thus you can handle two database.
     *
     * @param  object $dbh
     * @access public
     * @return static|sql the dao object self.
     */
    public function dbh($dbh)
    {
        $this->dbh = $dbh;
        return $this;
    }

    /**
     * 执行SQL语句，返回PDOStatement结果集。
     * Query the sql, return the statement object.
     *
     * @access public
     * @return static|sql   the PDOStatement object.
     */
    public function query($sql = '')
    {
        /* 如果有错误，返回一个空的PDOStatement对象，确保后续方法能够执行。*/
        /* If any error, return an empty statement object to make sure the remain method to execute. */
        if(!empty(dao::$errors)) return new PDOStatement();

        if($sql)
        {
            $sql       = $this->dbh->formatSQL($sql);
            $sqlMethod = strtolower(substr($sql, 0, strpos($sql, ' ')));
            $this->setMethod($sqlMethod);
            $this->sqlobj = new sql();
            $this->sqlobj->sql = $sql;
        }
        else
        {
            $sql = $this->dbh->formatSQL($this->processSQL());
        }

        try
        {
            /* Real-time save log. */
            if(dao::$realTimeLog && dao::$realTimeFile) file_put_contents(dao::$realTimeFile, $sql . "\n", FILE_APPEND);

            $method = $this->method;
            $this->reset();

            if($this->slaveDBH and in_array($method, array('select', 'desc')))
            {
                return $this->slaveDBH->rawQuery($sql);
            }
            else
            {
                /* Force to query from master db, if db has been changed. */
                $this->slaveDBH = false;

                return $this->dbh->rawQuery($sql);
            }
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }
    }

    /**
     * 返回SQL结果的所有字段信息。
     * Return the fields meta of PDOStatement.
     *
     * @param  string|PDOStatement $stmt
     * @access public
     * @return array
     */
    public function getColumns($stmt)
    {
        /* 如果$stmt是SQL查询语句，先执行查询获得 PDO stmt. */
        /* If $stmt is a SQL string, query to get PDO stmt. */
        if(is_string($stmt)) $stmt = $this->query($stmt);

        try
        {
            $columns = array();
            for($columnIndex = 0; $columnIndex < $stmt->columnCount(); $columnIndex++)
            {
                $columns[] = $stmt->getColumnMeta($columnIndex);
            }

            return $columns;
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }
    }

    /**
     * 将记录进行分页，自动设置limit语句。
     * Page the records, set the limit part auto.
     *
     * @param  object $pager
     * @param  string $distinctField
     * @access public
     * @return static|sql the dao object self.
     */
    public function page($pager, $distinctField = '')
    {
        if(!is_object($pager)) return $this;

        /*
         * 重新计算分页数据，并判断是否需要返回上一页。
         * Calculate pagination to determine whether to return to the previous page.
         */
        $originalPageID = $pager->pageID;
        $recTotal       = $this->count($distinctField);

        $pager->setRecTotal($recTotal);
        $pager->setPageTotal();
        if($originalPageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);

        $this->sqlobj->limit($pager->limit());
        return $this;
    }

    /**
     * 执行SQL。query()会返回stmt对象，该方法只返回更改或删除的记录数。
     * Execute the sql. It's different with query(), which return the stmt object. But this not.
     *
     * @param  string $sql
     * @access public
     * @return int the modified or deleted records. 更改或删除的记录数。
     */
    public function exec($sql = '')
    {
        if(!empty(dao::$errors)) return new PDOStatement();   // If any error, return an empty statement object to make sure the remain method to execute.

        if($sql)
        {
            $this->sqlobj = new sql();
            $this->sqlobj->sql = $sql;
        }
        else
        {
            $sql = $this->processSQL();
        }

        try
        {
            /* Real-time save log. */
            if(dao::$realTimeLog && dao::$realTimeFile) file_put_contents(dao::$realTimeFile, $sql . "\n", FILE_APPEND);

            if($this->table) unset(dao::$cache[$this->table]);
            $this->reset();

            /* Force to query from master db, if db has been changed. */
            $this->slaveDBH = false;

            return $this->dbh->exec($sql);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }
    }

    //-------------------- Fetch相关方法(Fetch related methods) -------------------//

    /**
     * 获取一个记录。
     * Fetch one record.
     *
     * @param  string $field        如果已经设置获取的字段，则只返回这个字段的值，否则返回这个记录。
     *                              if the field is set, only return the value of this field, else return this record
     * @access public
     * @return object|mixed
     */
    public function fetch($field = '')
    {
        $sql   = $this->processSQL();
        $table = $this->table;
        $key   = 'fetch-' . md5($sql . $field);
        if(isset(dao::$cache[$table][$key]))
        {
            if(empty($field)) return $this->getRow(dao::$cache[$table][$key]);

            $result = dao::$cache[$table][$key];
            return $result ? $result->$field : '';
        }

        if(empty($field))
        {
            $data = $this->query($sql)->fetch();
            dao::$cache[$table][$key] = $data;
            return $this->getRow($data);
        }

        $this->setFields($field);
        $result = $this->query($sql)->fetch(PDO::FETCH_OBJ);
        dao::$cache[$table][$key] = $this->getRow($result);
        return $result ? $result->$field : '';
    }

    /**
     * 获取所有记录。
     * Fetch all records.
     *
     * @param  string $keyField     返回以该字段做键的记录
     *                              the key field, thus the return records is keyed by this field
     * @access public
     * @return array the records
     */
    public function fetchAll($keyField = '')
    {
        $sql   = $this->processSQL();
        $table = $this->table;
        $key   = 'fetchAll-' . md5($sql . $keyField);
        if(isset(dao::$cache[$table][$key]))
        {
            $rows   = dao::$cache[$table][$key];
            $result = array();
            foreach($rows as $i => $row) $result[$i] = $this->getRow($row);
            return $result;
        }

        $stmt = $this->query($sql);
        dao::$cache[$table][$key] = array();
        if(empty($keyField))
        {
            $rows   = $stmt->fetchAll();
            $result = array();
            if(!$rows) $rows = array();
            dao::$cache[$table][$key] = $rows;
            foreach($rows as $i => $row) $result[$i] = $this->getRow($row);
            return $result;
        }

        $rows = array();
        while($row = $stmt->fetch())
        {
            dao::$cache[$table][$key][$row->$keyField] = $row;
            $rows[$row->$keyField] = $this->getRow($row);
        }

        return $rows;
    }

    /**
     * 获取所有记录并将按照字段分组。
     * Fetch all records and group them by one field.
     *
     * @param  string $groupField  分组的字段   the field to group by
     * @param  string $keyField    键字段       the field of key
     * @access public
     * @return array the records.
     */
    public function fetchGroup($groupField, $keyField = '')
    {
        $sql   = $this->processSQL();
        $table = $this->table;
        $key   = 'fetchGroup-' . md5($sql . $groupField . $keyField);
        if(isset(dao::$cache[$table][$key]))
        {
            $result    = array();
            $groupRows = dao::$cache[$table][$key];
            foreach($groupRows as $groupField => $rows)
            {
                foreach($rows as $keyField => $row) $result[$groupField][$keyField] = $this->getRow($row);
            }
            return $result;
        }

        $stmt = $this->query($sql);
        $rows = array();
        while($row = $stmt->fetch())
        {
            empty($keyField) ? $rows[$row->$groupField][] = $row : $rows[$row->$groupField][$row->$keyField] = $this->getRow($row);
        }
        dao::$cache[$table][$key] = $rows;
        return $rows;
    }

    /**
     * 获取的记录是以关联数组的形式
     * Fetch array like key=>value.
     *
     * 如果没有设置参数，用首末两键作为参数。
     * If the keyFiled and valueField not set, use the first and last in the record.
     *
     * @param  string $keyField
     * @param  string $valueField
     * @access public
     * @return array
     */
    public function fetchPairs($keyField = '', $valueField = '')
    {
        $keyField   = trim($keyField, '`');
        $valueField = trim($valueField, '`');

        $sql   = $this->processSQL();
        $table = $this->table;
        $key   = 'fetchPairs-' . md5($sql . $keyField . $valueField);
        if(isset(dao::$cache[$table][$key])) return dao::$cache[$table][$key];

        $pairs = array();
        $ready = false;
        $stmt  = $this->query($sql);
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

        dao::$cache[$table][$key] = $pairs;
        return $pairs;
    }

    /**
     * 返回最后插入的ID。
     * Return the last insert ID.
     *
     * @access public
     * @return int
     */
    public function lastInsertID()
    {
        return $this->dbh->lastInsertID();
    }

    /**
     * 重新生成数据。
     * Get row by data.
     *
     * @param  array/object    $data
     * @access public
     * @return array/object
     */
    public function getRow($data)
    {
        if(!is_object($data)) return $data;
        return clone $data;
    }

    //-------------------- 魔术方法(Magic methods) --------------------//

    /**
     * 解析dao的方法名，处理魔术方法。
     * Use it to do some convenient queries.
     *
     * @param  string $funcName  the function name to be called
     * @param  array  $funcArgs  the params
     * @access public
     * @return static|sql the dao object self.
     */
    public function __call($funcName, $funcArgs)
    {
        $funcName = strtolower($funcName);

        /*
         * 如果是findByxxx，转换为where条件语句。
         * findByxxx, xxx as will be in the where.
         **/
        if(strpos($funcName, 'findby') !== false)
        {
            $this->setMode('magic');
            $this->setFields('');
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
        /*
         * 获取指定个数的记录：fetch10 获取10条记录。
         * Fetch10.
         **/
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
        /*
         * 其他的方法，转到sqlobj对象执行。
         * Others, call the method in sql class.
         **/
        else
        {
            /*
             * 使用$arg0, $arg1... 生成调用的参数。
             * Create the max counts of sql class methods, and then create $arg0, $arg1...
             **/
            for($i = 0; $i < SQL::MAX_ARGS; $i ++)
            {
                ${"arg$i"} = isset($funcArgs[$i]) ? $funcArgs[$i] : null;
            }
            $this->sqlobj->$funcName($arg0, $arg1, $arg2);
            return $this;
        }
    }

    //-------------------- 条件检查( Data Checking)--------------------//

    /**
     * 检查字段是否满足条件。
     * Check a filed is satisfied with the check rule.
     *
     * @param  string $fieldName    the field to check
     * @param  string $funcName     the check rule
     * @param  string $condition     the condition
     * @access public
     * @return static|sql the dao object self.
     */
    public function check($fieldName, $funcName, $condition = '')
    {
        /*
         * 如果没数据中没有该字段，直接返回。
         * If no this field in the data, return.
         **/
        if(!isset($this->sqlobj->data->$fieldName)) return $this;

        /* 设置字段值。 */
        /* Set the field label and value. */
        global $lang, $config, $app;
        if(isset($config->db->prefix))
        {
            $table = strtolower(str_replace(array($config->db->prefix, '`'), '', $this->table));
        }
        elseif(strpos($this->table, '_') !== false)
        {
            $table = strtolower(substr($this->table, strpos($this->table, '_') + 1));
            $table = str_replace('`', '', $table);
        }
        else
        {
            $table = strtolower($this->table);
        }
        $fieldLabel = isset($lang->$table->$fieldName)       ? $lang->$table->$fieldName       : $fieldName;
        $value      = isset($this->sqlobj->data->$fieldName) ? $this->sqlobj->data->$fieldName : null;

        /*
         * 检查唯一性。
         * Check unique.
         **/
        if($funcName == 'unique')
        {
            $args = func_get_args();
            $sql  = "SELECT COUNT(*) AS `count` FROM $this->table WHERE `$fieldName` = " . $this->sqlobj->quote($value);
            if($condition) $sql .= ' AND ' . $condition;
            try
            {
                $row = $this->dbh->query($sql)->fetch();
                if($row->count != 0) $this->logError($funcName, $fieldName, $fieldLabel, array($value));
            }
            catch (PDOException $e)
            {
                $this->sqlError($e);
            }
        }
        else
        {
            /*
             * 创建参数。
             * Create the params.
             **/
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
     * 检查一个字段是否满足条件。
     * Check a field, if satisfied with the condition.
     *
     * @param  string $condition
     * @param  string $fieldName
     * @param  string $funcName
     * @access public
     * @return static|sql the dao object self.
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
     * 批量检查字段。
     * Batch check some fileds.
     *
     * @param  string $fields       the fields to check, join with ,
     * @param  string $funcName
     * @access public
     * @return static|sql the dao object self.
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
     * 批量检查字段是否满足条件。
     * Batch check fields on the condition is true.
     *
     * @param  string $condition
     * @param  string $fields
     * @param  string $funcName
     * @access public
     * @return static|sql the dao object self.
     */
    public function batchCheckIF($condition, $fields, $funcName)
    {
        if(!$condition) return $this;
        $fields = explode(',', str_replace(' ', '', $fields));
        $funcArgs = func_get_args();
        for($i = 0; $i < VALIDATER::MAX_ARGS; $i ++)
        {
            ${"arg$i"} = isset($funcArgs[$i + 3]) ? $funcArgs[$i + 3] : null;
        }
        foreach($fields as $fieldName) $this->check($fieldName, $funcName, $arg0, $arg1, $arg2);
        return $this;
    }

    /**
     * 根据数据库结构检查字段。
     * Check the fields according the the database schema.
     *
     * @param  string $skipFields   fields to skip checking
     * @access public
     * @return static|sql the dao object self.
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
     * 记录错误到日志。
     * Log the error.
     *
     * module/common/lang中定义了错误提示信息。
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

        /*
         * 如果$error错误信息是一个字符串，进行替换。
         * Just a string, cycle the $replaces.
         **/
        if(!is_array($error))
        {
            foreach($replaces as $replace)
            {
                $pos = strpos($error, '%s');
                if($pos === false) break;
                $error = substr($error, 0, $pos) . $replace . substr($error, $pos + 2);
            }
        }
        /*
         * 如果error错误信息是一个数组，选择一个%s满足替换个数的进行替换。
         * If the error define is an array, select the one which %s counts match the $replaces.
         **/
        else
        {
            /*
             * 去掉空值项。
             * Remove the empty items.
             **/
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
     * 判断是否有错误。
     * Judge any error or not.
     *
     * @access public
     * @return bool
     */
    public static function isError()
    {
        return !empty(dao::$errors);
    }

    /**
     * 获取错误。
     * Get the errors.
     *
     * @access public
     * @return array
     */
    public static function getError($join = false)
    {
        $errors = dao::$errors;
        dao::$errors = array();     // 清除dao的错误信息(Must clear errors)

        if(!$join) return $errors;

        if(is_array($errors))
        {
            $message = '';
            foreach($errors as $item)
            {
                is_array($item) ? $message .= join('\n', $item) . '\n' : $message .= $item . '\n';
            }
            return $message;
        }
    }

    /**
     * 获取表的字段类型。
     * Get the defination of fields of the table.
     *
     * @access public
     * @return array
     */
    public function getFieldsType()
    {
        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC $this->table";
            $rawFields = $this->dbh->rawQuery($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }

        $fields = array();
        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny', 'var'), '', $type);
            $field    = array();

            if($type == 'enum' or $type == 'set')
            {
                $rangeBegin  = $firstPOS + 2;                       // 移除开始的引用符  Remove the first quote.
                $rangeEnd    = strrpos($rawField->type, ')') - 1;   // 移除结束的引用符  Remove the last quote.
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
            elseif($type == 'datetime')
            {
                $field['rule'] = 'datetime';
            }
            else
            {
                $field['rule'] = 'skip';
            }
            $fields[$rawField->field] = $field;
        }
        return $fields;
    }

    /**
     * Process SQL error by code.
     *
     * @param  object    $exception
     * @access public
     * @return void
     */
    public function sqlError($exception)
    {
        $message  = $exception->getMessage();
        $message .= ' ' . helper::checkDB2Repair($exception);

        $sql = $this->sqlobj->get();
        $message .= "<p>The sql is: $sql</p>";

        /*
         * 如果开启了将sql错误作为异常抛出，那么拦截sql错误，不触发错误。
         * If throwing sql errors as exceptions is enabled, sql errors are intercepted and not triggered.
         */
        if($this->app->throwError)
        {
            throw new Exception($message);
            return;
        }
        $this->app->triggerError($message, __FILE__, __LINE__, $exit = true);
    }
}

/**
 * SQL类。
 * The SQL class.
 *
 * @package framework
 */
class baseSQL
{
    /**
     * 所有方法的最大参数个数。
     * The max count of params of all methods.
     *
     */
    const MAX_ARGS = 3;

    /**
     * SQL字符串。
     * The sql string.
     *
     * @var string
     * @access public
     */
    public $sql = '';

    /**
     * 全局变量$dbh。
     * The global $dbh.
     *
     * @var object
     * @access public
     */
    public $dbh;

    /**
     * 更新或插入的数据。
     * The data to update or insert.
     *
     * @var mix
     * @access public
     */
    public $data;

    /**
     * 不需要拼接SQL的字段
     * skipFields
     *
     * @var mixed
     * @access public
     */
    public $skipFields;

    /**
     * SQL 方法, insert, update, delete ...
     * SQL method, insert, update, delete ...
     *
     * @var mixed
     * @access public
     */
    public $method;

    /**
     * setField
     *
     * @var mixed
     * @access public
     */
    public $setField;

    /**
     * 是否是第一次设置。
     * Is the first time to call set.
     *
     * @var bool
     * @access public;
     */
    public $isFirstSet = true;

    /**
     * 是否是在条件语句中。
     * If in the logic of judge condition or not.
     *
     * @var bool
     * @access public;
     */
    public $inCondition = false;

    /**
     * 条件是否为真。
     * The condition is true or not.
     *
     * @var bool
     * @access public;
     */
    public $conditionIsTrue = false;

    /**
     * 条件层级。
     * The condition level.
     *
     * @var bool
     * @access public;
     */
    public $conditionLevel = 0;

    /**
     * 条件结果，beginIF 中表达式的结果会存储到这个数组中。
     * Store the result of the expression.
     *
     * @var bool
     * @access public;
     */
    public $conditionResults = array();

    /**
     * WHERE条件嵌套小括号标记。
     * If in mark or not.
     *
     * @var bool
     * @access public;
     */
    public $inMark = false;


    /**
     * 是否开启特殊字符转义。
     * Magic quote or not.
     *
     * @var bool
     * @access public
     */
    public $magicQuote;

    /**
     * 构造方法。
     * The construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($table = '')
    {
        global $dbh;
        $this->dbh        = $dbh;
        $this->data       = new stdclass();
        $this->magicQuote = (version_compare(phpversion(), '5.4', '<') and function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc());
    }

    /**
     * 工厂方法。
     * The factory method.
     *
     * @param  string $table
     * @access public
     * @return object the sql object.
     */
    public static function factory($table = '')
    {
        return new sql($table);
    }

    /**
     * 设置SQL的方法。
     * Set SQL method.
     *
     * @param string $method
     * @access public
     * @return void
     */
    public function setMethod($method = '')
    {
        $this->method = $method;
    }

    /**
     * select语句。
     * The sql is select.
     *
     * @param  string $field
     * @access public
     * @return object the sql object.
     */
    public static function select($field = '*')
    {
        $sqlobj = self::factory();
        $sqlobj->setMethod('select');
        $sqlobj->sql = "SELECT $field ";
        return $sqlobj;
    }

    /**
     * update语句。
     * The sql is update.
     *
     * @param  string $table
     * @access public
     * @return object the sql object.
     */
    public static function update($table)
    {
        $sqlobj = self::factory();
        $sqlobj->setMethod('update');
        $sqlobj->sql = "UPDATE $table SET ";
        return $sqlobj;
    }

    /**
     * insert语句。
     * The sql is insert.
     *
     * @param  string $table
     * @access public
     * @return object the sql object.
     */
    public static function insert($table)
    {
        $sqlobj = self::factory();
        $sqlobj->setMethod('insert');
        $sqlobj->sql = "INSERT INTO $table ";
        return $sqlobj;
    }

    /**
     * replace语句。
     * The sql is replace.
     *
     * @param  string $table
     * @access public
     * @return object the sql object.
     */
    public static function replace($table)
    {
        $sqlobj = self::factory();
        $sqlobj->setMethod('replace');
        $sqlobj->sql = "REPLACE $table SET ";
        return $sqlobj;
    }

    /**
     * delete语句。
     * The sql is delete.
     *
     * @access public
     * @return object the sql object.
     */
    public static function delete()
    {
        $sqlobj = self::factory();
        $sqlobj->setMethod('delete');
        $sqlobj->sql = "DELETE ";
        return $sqlobj;
    }

    /**
     * 将关联数组转换为sql语句中 `key` = value 的形式。
     * Join the data items by key = value.
     *
     * @param  object $data
     * @param  string $skipFields   the fields to skip.
     * @access public
     * @return object the sql object.
     */
    public function data($data, $skipFields = '')
    {
        $data = (object) $data;
        if($skipFields) $this->skipFields = ',' . str_replace(' ', '', $skipFields) . ',';

        if($this->method != 'insert')
        {
            foreach($data as $field => $value)
            {
                if(!preg_match('|^\w+$|', $field))
                {
                    unset($data->$field);
                    continue;
                }
                if(strpos($this->skipFields, ",$field,") !== false) continue;
                if($field == 'id' and $this->method == 'update') continue;     // primary key not allowed in dmdb.

                $this->sql .= "`$field` = " . $this->quote($value) . ',';
            }
        }

        $this->data = $data;
        $this->sql  = rtrim($this->sql, ',');    // Remove the last ','.
        return $this;
    }

    /**
     * 在左边添加'('。
     * Add an '(' at left.
     *
     * @param  int    $count
     * @access public
     * @return static|sql the sql object.
     */
    public function markLeft($count = 1)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= str_repeat('(', $count);
        $this->inMark = true;
        return $this;
    }

    /**
     * 在右边增加')'。
     * Add an ')' at right.
     *
     * @param  int    $count
     * @access public
     * @return static|sql the sql object.
     */
    public function markRight($count = 1)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= str_repeat(')', $count);
        $this->inMark = false;
        return $this;
    }

    /**
     * SET部分。
     * The set part.
     *
     * @param  string $set
     * @access public
     * @return static|sql the sql object.
     */
    public function set($set)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;

        /* DMDB replace will use $this->data. */
        if($this->method == 'insert' or $this->method == 'replace')
        {
            $this->setField = $set;
            $this->data->$set = '';
        }

        if($this->method != 'insert')
        {
            /* Add ` to avoid keywords of mysql. */
            if(strpos($set, '=') ===  false)
            {
                $set = str_replace(',', '', $set);
                $set = '`' . str_replace('`', '', $set) . '`';
            }

            $this->sql .= $this->isFirstSet ? " $set" : ", $set";
            if($this->isFirstSet) $this->isFirstSet = false;
        }

        return $this;
    }

    /**
     * 创建From部分。
     * Create the from part.
     *
     * @param  string $table
     * @access public
     * @return static|sql the sql object.
     */
    public function from($table)
    {
        $this->sql .= "FROM $table";
        return $this;
    }

    /**
     * 创建Alias部分，Alias转为AS。
     * Create the Alias part.
     *
     * @param  string $alias
     * @access public
     * @return static|sql the sql object.
     */
    public function alias($alias)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " AS $alias ";
        return $this;
    }

    /**
     * 创建LEFT JOIN部分。
     * Create the left join part.
     *
     * @param  string $table
     * @access public
     * @return static|sql the sql object.
     */
    public function leftJoin($table)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " LEFT JOIN $table";
        return $this;
    }

    /**
     * 创建ON部分。
     * Create the on part.
     *
     * @param  string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function on($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " ON $condition ";
        return $this;
    }

    /**
     * 开始条件判断。
     * Begin condition judge.
     *
     * @param  bool $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function beginIF($condition)
    {
        $this->inCondition = true;
        $this->conditionLevel += 1;
        $this->conditionResults[$this->conditionLevel] = $condition;
        $this->conditionIsTrue = !in_array(false, $this->conditionResults);
        return $this;
    }

    /**
     * 结束条件判断。
     * End the condition judge.
     *
     * @access public
     * @return static|sql the sql object.
     */
    public function fi()
    {
        unset($this->conditionResults[$this->conditionLevel]);
        $this->conditionLevel -= 1;
        if($this->conditionLevel > 0)
        {
            $this->conditionIsTrue = !in_array(false, $this->conditionResults);
            return $this;
        }

        $this->inCondition = false;
        $this->conditionIsTrue = false;
        return $this;
    }

    /**
     * 创建WHERE部分。
     * Create the where part.
     *
     * @param  string $arg1     the field name
     * @param  string $arg2     the operator
     * @param  string $arg3     the value
     * @access public
     * @return static|sql the sql object.
     */
    public function where($arg1 = '', $arg2 = null, $arg3 = null)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if(!$arg1)
        {
            $condition = '';
        }
        elseif($arg3 !== null)
        {
            $value     = $this->quote($arg3);
            $condition = "`$arg1` $arg2 " . $this->quote($arg3);
        }
        else
        {
            $condition = (is_string($arg1) && ctype_alnum($arg1)) ? '`' . $arg1 . '`' : $arg1;
        }

        if(!$this->inMark) $this->sql .= ' ' . DAO::WHERE ." $condition ";
        if($this->inMark)  $this->sql .= " $condition ";
        return $this;
    }

    /**
     * 创建AND部分。
     * Create the AND part.
     *
     * @param  string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function andWhere($condition, $addMark = false)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if(is_string($condition) && ctype_alnum($condition)) $condition = '`' . $condition . '`';

        $mark = $addMark ? '(' : '';
        $this->sql .= " AND {$mark} $condition ";
        return $this;
    }

    /**
     * 创建OR部分。
     * Create the OR part.
     *
     * @param  bool  $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function orWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if(is_string($condition) && ctype_alnum($condition)) $condition = '`' . $condition . '`';

        $this->sql .= " OR $condition ";
        return $this;
    }

    /**
     * 创建'='部分。
     * Create the '='.
     *
     * @param  string $value
     * @access public
     * @return static|sql the sql object.
     */
    public function eq($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;

        if($this->method == 'insert' or $this->method == 'replace')
        {
            $field = $this->setField;
            $this->data->$field = $value;
        }

        if($this->method != 'insert')
        {
            $this->sql .= " = " . $this->quote($value);
        }

        return $this;
    }

    /**
     * 创建'!='。
     * Create '!='.
     *
     * @param  string $value
     * @access public
     * @return static|sql the sql object.
     */
    public function ne($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " != " . $this->quote($value);
        return $this;
    }

    /**
     * 创建'>'。
     * Create '>'.
     *
     * @param  string $value
     * @access public
     * @return static|sql the sql object.
     */
    public function gt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " > " . $this->quote($value);
        return $this;
    }

    /**
     * 创建'>='
     * Create '>='.
     *
     * @param  string $value
     * @access public
     * @return static|sql the sql object.
     */
    public function ge($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " >= " . $this->quote($value);
        return $this;
    }

    /**
     * 创建'<'。
     * Create '<'.
     *
     * @param  mixed  $value
     * @access public
     * @return static|sql the sql object.
     */
    public function lt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " < " . $this->quote($value);
        return $this;
    }

    /**
     * 创建 '<='。
     * Create '<='.
     *
     * @param  mixed  $value
     * @access public
     * @return static|sql the sql object.
     */
    public function le($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " <= " . $this->quote($value);
        return $this;
    }

    /**
     * 创建"between and"。
     * Create "between and"
     *
     * @param  string $min
     * @param  string $max
     * @access public
     * @return static|sql the sql object.
     */
    public function between($min, $max)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $min = $this->quote($min);
        $max = $this->quote($max);
        $this->sql .= " BETWEEN $min AND $max ";
        return $this;
    }

    /**
     * 创建IN部分。
     * Create in part.
     *
     * @param  string|array $ids   ','分割的字符串或者数组  list string by ',' or an array
     * @access public
     * @return static|sql the sql object.
     */
    public function in($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= $ids === NULL ? ' IS NULL' : helper::dbIN($ids);
        return $this;
    }

    /**
     * 创建'NOT IN'部分。
     * Create not in part.
     *
     * @param  string|array $ids   list string by ',' or an array
     * @access public
     * @return static|sql the sql object.
     */
    public function notin($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= ' NOT ' . helper::dbIN($ids);
        return $this;
    }

    /**
     * 创建LIKE部分。
     * Create the like by part.
     *
     * @param  string $string
     * @access public
     * @return static|sql the sql object.
     */
    public function like($string)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " LIKE " . $this->quote($string);
        return $this;
    }

    /**
     * 创建NOT LIKE部分。
     * Create the not like by part.
     *
     * @param  string $string
     * @access public
     * @return static|sql the sql object.
     */
    public function notLike($string)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= "NOT LIKE " . $this->quote($string);
        return $this;
    }

    /**
     * 不为空日期
     * Create not zero date.
     *
     * @access public
     * @return static|sql the sql object.
     */
    public function notZeroDate()
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " > '1970-01-01' ";
        return $this;
    }

    /**
     * 不为空时间
     * Create not zero datetime.
     *
     * @access public
     * @return static|sql the sql object.
     */
    public function notZeroDatetime()
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " > '1970-01-01 00:00:01' ";
        return $this;
    }

    /**
     * 创建ORDER BY部分。
     * Create the order by part.
     *
     * @param  string $order
     * @access public
     * @return static|sql the sql object.
     */
    public function orderBy($order)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;

        $order = str_replace(array('|', '', '_'), ' ', $order);

        /* Add "`" in order string. */
        /* When order has limit string. */
        $pos    = stripos($order, 'limit');
        $orders = $pos ? substr($order, 0, $pos) : $order;
        $limit  = $pos ? substr($order, $pos) : '';
        if(!empty($limit))
        {
            $trimedLimit = trim(str_replace('limit', '', $limit));
            if(!preg_match('/^[0-9]+ *(, *[0-9]+)?$/', $trimedLimit)) die("Limit is bad query, The limit is " . htmlspecialchars($limit));
        }

        $orders = trim($orders);
        if(empty($orders)) return $this;
        if(!preg_match('/^(\w+\.)?(`\w+`|\w+)( +(desc|asc))?( *(, *(\w+\.)?(`\w+`|\w+)( +(desc|asc))?)?)*$/i', $orders)) die("Order is bad request, The order is " . htmlspecialchars($orders));

        $orders = explode(',', $orders);
        foreach($orders as $i => $order)
        {
            $orderParse = explode(' ', trim($order));
            foreach($orderParse as $key => $value)
            {
                $value = trim($value);
                if(empty($value) or strtolower($value) == 'desc' or strtolower($value) == 'asc') continue;

                $field = $value;
                /* such as t1.id field. */
                if(strpos($value, '.') !== false) list($table, $field) = explode('.', $field);
                if(strpos($field, '`') === false) $field = "`$field`";

                $orderParse[$key] = isset($table) ? $table . '.' . $field :  $field;
                unset($table);
            }
            $orders[$i] = join(' ', $orderParse);
            if(empty($orders[$i])) unset($orders[$i]);
        }
        $order = join(',', $orders) . ' ' . $limit;

        $this->sql .= ' ' . DAO::ORDERBY . " $order";
        return $this;
    }

    /**
     * 创建LIMIT部分。
     * Create the limit part.
     *
     * @param  string $limit
     * @access public
     * @return static|sql the sql object.
     */
    public function limit($limit)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if(empty($limit)) return $this;

        /* filter limit. */
        $limit = trim(str_ireplace('limit', '', $limit));
        if(!preg_match('/^[0-9]+ *(, *[0-9]+)?$/', $limit))
        {
            $limit = htmlspecialchars($limit);
            die("Limit is bad query, The limit is $limit");
        }
        $this->sql .= ' ' . DAO::LIMIT . " $limit ";
        return $this;
    }

    /**
     * 创建GROUP BY部分。
     * Create the groupby part.
     *
     * @param  string $groupBy
     * @access public
     * @return static|sql the sql object.
     */
    public function groupBy($groupBy)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if(!preg_match('/^\w+[a-zA-Z0-9_`.]+$/', $groupBy))
        {
            $groupBy = htmlspecialchars($groupBy);
            die("Group is bad query, The group is $groupBy");
        }
        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }

    /**
     * 创建HAVING部分。
     * Create the having part.
     *
     * @param  string $having
     * @access public
     * @return static|sql the sql object.
     */
    public function having($having)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= ' ' . DAO::HAVING . " $having";
        return $this;
    }

    /**
     * 获取SQL字符串。
     * Get the sql string.
     *
     * @access public
     * @return static|sql
     */
    public function get()
    {
        return $this->sql;
    }

    /**
     * 对字段加转义。
     * Quote a var.
     *
     * @param  mixed  $value
     * @access public
     * @return mixed
     */
    public function quote($value)
    {
        if($value === null) return 'NULL';
        if($this->magicQuote) $value = stripslashes($value);
        return $this->dbh->quote((string)$value);
    }
}
