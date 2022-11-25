<?php
require __DIR__ . '/vendor/autoload.php';

class sqlparser
{
    public function __construct($query)
    {
        $this->parser = new PhpMyAdmin\SqlParser\Parser($query);
        $this->statements = $this->parser->statements;
    }
}
