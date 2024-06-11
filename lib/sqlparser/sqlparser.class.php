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
