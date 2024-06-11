<?php
require __DIR__ . '/vendor/autoload.php';

class sqlparser
{
    /**
     * Parser object of SqlParser.
     *
     * @var object
     * @access public
     */
    public $parser;

    /**
     * Statements of parser.
     *
     * @var array
     * @access public
     */
    public $statements;

    /**
     * First statement.
     *
     * @var object
     * @access public
     */
    public $statement;

    /**
     * Count statements.
     *
     * @var int
     * @access public
     */
    public $statementsCount = 0;

    /**
     * Is first statemnt select type.
     *
     * @var bool
     * @access public
     */
    public $isSelect = false;

    /**
     * DAO
     *
     * @var object
     * @access public
     */
    public $dao = null;

    /**
     * Origin tables.
     *
     * @var array
     * @access public
     */
    public $originTables = array();

    /**
     * Columns.
     *
     * @var array
     * @access public
     */
    public $columns = array();

    /**
     * Tables.
     *
     * @var array
     * @access public
     */
    public $tables = array();

    public function __construct($query)
    {
        $query = $this->skipLineBreak($query);

        $this->parser          = new PhpMyAdmin\SqlParser\Parser($query);
        $this->statements      = $this->parser->statements;
        $this->statement       = $this->statementsCount > 0 ? current($this->statements) : null;
        $this->statementsCount = count($this->statements);

        $this->isSelect = $this->statement instanceof PhpMyAdmin\SqlParser\Statements\SelectStatement === true;
    }

    /**
     * Parse statement.
     *
     * @access public
     * @return void
     */
    public function parseStatement()
    {
        $this->columns = $this->parseColumns();
        $this->tables  = $this->parseTables();
    }

    /**
     * Match columns with table.
     *
     * @access public
     * @return array
     */
    public function matchColumnsWithTable()
    {
        if(count($tables) == 1) return $this->combineSingleTable();
        return $this->combineMultipleTable();
    }

    /**
     * Combine single table to columns.
     *
     * @access public
     * @return array
     */
    public function combineSingleTable()
    {
        $combineColumns = array();
        $fromTable = current($this->tables);
        foreach($this->columns as $columnName => $column)
        {
            $column['table'] = array_merge($fromTable, array('column' => $columnName));

            $combineColumns[$columnName] = $column;
        }

        return $combineColumns;
    }

    /**
     * Combine multiple table to columns.
     *
     * @access public
     * @return array
     */
    public function combineMultipleTable()
    {
        foreach($this->columns as $columnName => $column)
        {
            $column['table'] = $this->searchTables($column['table'], $this->tables, $column['origin']);
            $combineColumns[$columnName] = $column;
        }

        return $combineColumns;
    }

    /**
     * Search table from origin tables.
     *
     * @param  string    $tableName
     * @param  string    $tables
     * @param  string    $column
     * @access public
     * @return string|false
     */
    public function searchTables($tableName, $tables, $column)
    {
        /* 如果能使用别名匹配上，那么直接返回。*/
        /* If it can be matched using an alias, then it returns. */
        foreach($tables as $table) if($tableName == $table['alias']) return array_merge($table, array('column' => $column));

        /* 如果匹配不上，则字段没有使用别名进行限制，那么需要通过字段去遍历所有表。*/
        /* If it doesn't match, then the field is not aliased, and you need to iterate over all tables using the field. */
        foreach($tables as $table)
        {
            /* 如果是原始表，并且列在原始表中存在，那么返回这个表。*/
            /* If it is the original table and the column exists in the original table, then the table is returned. */
            if($table['isTable'] && $this->columnExistInOriginTable($table['originTable'], $column)) return array_merge($table, array('column' => $column));
            /* 如果不是原始表，并且列在子句中存在，那么返回子句中这个字段对应的表。*/
            /* If it is not the original table and the column exists in the clause, then the table corresponding to the field in the clause is returned. */
            if(!$table['isTable'] && isset($table['originTable'][$column])) return array_merge($table['originTable'][$column['table'], array('column' => $table['originTable'][$column['origin']));
        }

        return false;
    }

    /**
     * Parse columns.
     *
     * @access public
     * @return void
     */
    public function parseColumns()
    {
        $fields = array();
        foreach($this->statement->expr as $expr)
        {
            /* 获取查询数据后真正展示出来的列名 */
            $columnName = empty($expr->alias) ? $expr->column : $expr->alias;

            $fields[$columnName] = array('origin' => $expr->column, 'table' => $expr->table);
        }

        return $fields;
    }

    /**
     * Parse tables.
     *
     * @access public
     * @return void
     */
    public function parseTables()
    {
        $from  = current($this->statement->from);
        $joins = $this->statement->join;

        $tables = array();
        $tables[] = $this->parseTable($from, 'from');

        foreach($joins as $join) $tables[] = $this->parseTable($join->expr, 'join');

        return $tables;
    }

    /**
     * Parse table.
     *
     * @param  object    $expr
     * @param  string    $type
     * @access public
     * @return void
     */
    public function parseTable($expr, $type)
    {
        $isTable = empty($expr->subquery);

        $table = array('alias' => $expr->alias, 'isTable' => $isTable, 'type' => $type);
        $table['originTable'] = $this->getOriginTable($expr->expr, $isTable);

        return $table;
    }

    /**
     * Get origin table from table name or expr.
     *
     * @param  string    $table
     * @param  bool    $isTable
     * @access public
     * @return string|array
     */
    public function getOriginTable($table, $isTable)
    {
        if(!$isTable)
        {
            $parser = new sqlparser($table);
            $parser->setDAO($this->dao);
            $parser->parseStatement();
            return $parser->matchColumnsWithTable();
        }

        $this->storeOriginTable($table);

        return $table;
    }

    /**
     * Judge column exist in origin table or not.
     *
     * @param  string    $table
     * @param  string    $column
     * @access public
     * @return bool
     */
    public function columnExistInOriginTable($table, $column)
    {
        $originTable = $this->getOriginTableColumns($table);
        if(!$originTable) return false;

        return isset($originTable[$column]);
    }

    /**
     * Get origin table columns.
     *
     * @param  string    $table
     * @access public
     * @return array|null
     */
    public function getOriginTableColumns($table)
    {
        $originTables = $this->originTables;
        return isset($originTables[$table]) ? $originTables[$table] : null;
    }

    /**
     * Store origin table.
     *
     * @param  string    $table
     * @access public
     * @return void
     */
    public function storeOriginTable($table)
    {
        if(!isset($this->originTables[$table])) $this->originTables[$table] = $this->dao->descTable($table);
    }

    /**
     * Set dao.
     *
     * @param  object    $dao
     * @access public
     * @return void
     */
    public function setDAO($dao)
    {
        $this->dao = $dao;
    }

    /**
     * Skip line break in sql.
     *
     * @param  string    $sql
     * @access public
     * @return string
     */
    public function skipLineBreak($sql)
    {
        $sql = str_replace("\n\t", " ", $sql);
        $sql = str_replace("\t\n", " ", $sql);
        $sql = str_replace("\n\r", " ", $sql);
        $sql = str_replace("\r\n", " ", $sql);
        $sql = str_replace("\r", " ", $sql);
        $sql = str_replace("\n", " ", $sql);

        return $sql;
    }
}
