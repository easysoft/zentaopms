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
        if(empty($query)) return;

        $this->parser          = new PhpMyAdmin\SqlParser\Parser($query);
        $this->statements      = $this->parser->statements;
        $this->statementsCount = count($this->statements);
        $this->statement       = $this->statementsCount > 0 ? current($this->statements) : null;

        $this->isSelect = $this->statement instanceof PhpMyAdmin\SqlParser\Statements\SelectStatement === true;
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
     * Parse statement.
     *
     * @access public
     * @return void
     */
    public function parseStatement()
    {
        if(empty($this->statement)) return;

        $this->columns = $this->parseColumns();
        $this->tables  = $this->parseTables();
    }

    /**
     * Create statement.
     *
     * @access public
     * @return void
     */
    public function createStatement()
    {
        $this->statement = new PhpMyAdmin\SqlParser\Statements\SelectStatement();
    }

    /**
     * Set from.
     *
     * @param  object $from
     * @access public
     * @return void
     */
    public function setFrom($from)
    {
        $this->statement->from = array($from);
    }

    /**
     * Add select.
     *
     * @param  array|object $exprs
     * @access public
     * @return void
     */
    public function addSelect($exprs)
    {
        if(empty($exprs)) return;

        if($exprs instanceof PhpMyAdmin\SqlParser\Components\Expression)
        {
            $this->statement->expr[] = $exprs;
            return;
        }

        foreach($exprs as $expr) $this->addSelect($expr);
    }

    /**
     * Add join.
     *
     * @param  array|object $joins
     * @access public
     * @return void
     */
    public function addJoin($joins)
    {
        if(empty($joins)) return;

        if($joins instanceof PhpMyAdmin\SqlParser\Components\JoinKeyword)
        {
            $this->statement->join[] = $joins;
            return;
        }

        foreach($joins as $join) $this->addJoin($join);
    }

    /**
     * Add where.
     *
     * @param  array|object $wheres
     * @access public
     * @return void
     */
    public function addWhere($wheres)
    {
        if(empty($wheres)) return;

        if($wheres instanceof PhpMyAdmin\SqlParser\Components\Condition)
        {
            $this->statement->where[] = $wheres;
            return;
        }

        foreach($wheres as $where) $this->addWhere($where);
    }

    /**
     * Add group.
     *
     * @param  array|object $groups
     * @access public
     * @return void
     */
    public function addGroup($groups)
    {
        if(empty($groups)) return;

        if($groups instanceof PhpMyAdmin\SqlParser\Components\GroupKeyword)
        {
            $this->statement->group[] = $groups;
            return;
        }

        foreach($groups as $group) $this->addGroup($group);
    }

    /**
     * Get function.
     *
     * @param  string $name
     * @param  mixed  $args
     * @access public
     * @return string
     */
    public function getFunction($name, ...$args)
    {
        $name = strtoupper($name);
        $argStr = implode(', ', $args);
        return "$name($argStr)";
    }

    /**
     * Get expression.
     *
     * @param  string|array $table
     * @param  string $column
     * @param  string $alias
     * @param  string $function
     * @access public
     * @return PhpMyAdmin\SqlParser\Components\Expression
     */
    public function getExpression($table = null, $column = null, $alias = null, $function = null)
    {
        if(is_array($table)) return call_user_func_array(array($this, 'getExpression'), $table);

        $expression = new PhpMyAdmin\SqlParser\Components\Expression();

        if(!empty($function))
        {
            $expression->function = $function;
            $expression->expr     = $this->getFunction($function, $expression->build($this->getExpression($table, $column)));
        }
        else
        {
            if($column === '*')
            {
                $table = $this->trimExpr($table);
                $expression->expr = empty($table) ? '*' : "`$table`.*";
            }
            else
            {
                $expression->table  = $table;
                $expression->column = $column;
            }
        }

        $expression->alias = $alias;

        return $expression;
    }

    /**
     * operatorCondition
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function operatorCondition($type)
    {
        $condition = new PhpMyAdmin\SqlParser\Components\Condition();
        $condition->isOperator = true;
        $condition->expr       = strtoupper($type);

        return $condition;
    }

    /**
     * Get condition.
     *
     * @param  mixed    $tableA
     * @param  mixed    $columnA
     * @param  string   $operator
     * @param  mixed    $tableB
     * @param  mixed    $columnB
     * @param  mixed    $group
     * @access public
     * @return object
     */
    public function getCondition($tableA = null, $columnA = null, $operator = '', $tableB = null, $columnB = null, $group = 1)
    {
        if(is_array($tableA)) return call_user_func_array(array($this, 'getCondition'), $tableA);

        $condition = new PhpMyAdmin\SqlParser\Components\Condition();

        $tableA  = $this->trimExpr($tableA);
        $columnA = $this->trimExpr($columnA);
        $tableB  = $this->trimExpr($tableB);

        /* 如果tableB不为空，那么columnB就是字段，需要trim。*/
        if(!empty($tableB)) $columnA = $this->trimExpr($columnA);

        $exprA = empty($tableA) ? "`$columnA`" : "`$tableA`.`$columnA`";
        $exprB = empty($tableB) ? "$columnB" : "`$tableB`.`$columnB`";

        $operator = strtoupper($operator);

        $condition->expr  = "$exprA $operator $exprB";
        $condition->group = $group;

        return $condition;
    }

    /**
     * Get left join.
     *
     * @param  string  $table
     * @param  string  $alias
     * @param  array   $on
     * @access public
     * @return object
     */
    public function getLeftJoin($table, $alias, $on)
    {
        $left = new PhpMyAdmin\SqlParser\Components\JoinKeyword();

        $left->type = 'LEFT';
        $left->expr = $this->getExpression($table, null, $alias);
        $left->on   = $this->combineConditions($on);

        return $left;
    }

    /**
     * Get group.
     *
     * @param  object $expr
     * @access public
     * @return void
     */
    public function getGroup($expr)
    {
        $group = new PhpMyAdmin\SqlParser\Components\GroupKeyword();
        $group->expr = $expr;

        return $group;
    }

    /**
     * Combine conditions.
     *
     * @param  array|object $conditions
     * @access public
     * @return array
     */
    public function combineConditions($conditions, $quote = false)
    {
        if(empty($conditions)) return array();

        if($conditions instanceof PhpMyAdmin\SqlParser\Components\Condition === true) return $conditions;

        $first = reset($conditions);
        $last  = end($conditions);

        if($quote)
        {
            $first = $this->addQuote($first, 'left');
            $last  = $this->addQuote($last, 'right');
        }

        $quote = true;
        if(is_array($first)) $first = $this->combineConditions($first, $quote);
        if(is_array($last))  $last  = $this->combineConditions($last, $quote);

        $conditions[0] = $first;
        $conditions[count($conditions) - 1] = $last;
        return $conditions;
    }

    public function addQuote($conditions, $side)
    {
        if($conditions instanceof PhpMyAdmin\SqlParser\Components\Condition === true)
        {
            $expr  = $conditions->expr;
            $conditions->expr = $side == 'left' ? '(' . $expr : $expr . ')';
            return $conditions;
        }

        $condition = $side == 'left' ? current($conditions) : end($conditions);
        $index     = $side == 'left' ? 0 : count($conditions) - 1;
        $conditions[$index] = $this->addQuote($condition, $side);
        return $conditions;
    }

    /**
     * Match columns with table.
     *
     * @access public
     * @return array
     */
    public function matchColumnsWithTable()
    {
        if(empty($this->statement)) return array();

        if(count($this->tables) == 1) return $this->combineSingleTable();
        return $this->combineMultipleTable();
    }

    /**
     * Trim expr.
     *
     * @param  string    $expr
     * @access private
     * @return string
     */
    private function trimExpr($expr)
    {
        return trim(trim($expr), '`');
    }

    /**
     * Combine single table to columns.
     *
     * @access private
     * @return array
     */
    private function combineSingleTable()
    {
        $combineColumns = array();
        $fromTable = current($this->tables);
        foreach($this->columns as $columnName => $column)
        {
            $column['table'] = array_merge($fromTable, array('column' => $column['origin']));

            $combineColumns[$columnName] = $column;
        }

        return $combineColumns;
    }

    /**
     * Combine multiple table to columns.
     *
     * @access private
     * @return array
     */
    private function combineMultipleTable()
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
     * @access private
     * @return string|false
     */
    private function searchTables($tableName, $tables, $column)
    {
        /* 如果能使用别名匹配上，那么直接返回。*/
        /* If it can be matched using an alias, then it returns. */
        foreach($tables as $table) if($tableName == $table['alias']) return array_merge($table, array('column' => $column));

        /* 如果匹配不上，则字段没有使用别名进行限制，那么需要通过字段去遍历所有表。*/
        /* If it doesn't match, then the field is not aliased, and you need to iterate over all tables using the field. */
        foreach($tables as $table)
        {
            $isTable     = $table['isTable'];
            $originTable = $table['originTable'];

            /* 如果是原始表，并且列在原始表中存在，那么返回这个表。*/
            /* If it is the original table and the column exists in the original table, then the table is returned. */
            if($isTable && $this->columnExistInOriginTable($originTable, $column)) return array_merge($table, array('column' => $column));
            /* 如果不是原始表，并且列在子句中存在，那么返回子句中这个字段对应的表。*/
            /* If it is not the original table and the column exists in the clause, then the table corresponding to the field in the clause is returned. */
            if(!$isTable && isset($originTable[$column])) return array_merge($originTable[$column]['table'], array('column' => $originTable[$column]['origin']));
        }

        return false;
    }

    /**
     * Parse columns.
     *
     * @access private
     * @return void
     */
    private function parseColumns()
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
     * @access private
     * @return void
     */
    private function parseTables()
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
     * @access private
     * @return void
     */
    private function parseTable($expr, $type)
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
     * @access private
     * @return string|array
     */
    private function getOriginTable($table, $isTable)
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
     * @access private
     * @return bool
     */
    private function columnExistInOriginTable($table, $column)
    {
        $originTable = $this->getOriginTableColumns($table);
        if(!$originTable) return false;

        return isset($originTable[$column]);
    }

    /**
     * Get origin table columns.
     *
     * @param  string    $table
     * @access private
     * @return array|null
     */
    private function getOriginTableColumns($table)
    {
        $originTables = $this->originTables;
        return isset($originTables[$table]) ? $originTables[$table] : null;
    }

    /**
     * Store origin table.
     *
     * @param  string    $table
     * @access private
     * @return void
     */
    private function storeOriginTable($table)
    {
        if(!isset($this->originTables[$table])) $this->originTables[$table] = $this->dao->descTable($table);
    }

    /**
     * Skip line break in sql.
     *
     * @param  string    $sql
     * @access private
     * @return string
     */
    private function skipLineBreak($sql)
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
