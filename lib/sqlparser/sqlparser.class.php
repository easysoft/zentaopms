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
