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

    public function __construct($query)
    {
        $query = $this->skipLineBreak($query);

        $this->parser = new PhpMyAdmin\SqlParser\Parser($query);
        $this->statements = $this->parser->statements;
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
