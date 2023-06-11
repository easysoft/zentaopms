<?php
class sqlite
{
    public $app;
    /**
     * 全局对象$sqlite
     * The global sqlite object.
     *
     * @var object
     * @access public
     */
    public $dbh = null;

    public function __construct()
    {
        global $app;
        $this->app = $app;
    }

    /**
     * 连接sqlite数据库。
     * Connect to sqlite database.
     *
     * @param  string $sqliteFile
     * @access public
     * @return object
     */
    public function connectSqlite(string $sqliteFile = ''): PDO
    {
        $tmpRoot = $this->app->getTmpRoot();
        if(empty($sqliteFile) || !is_file($sqliteFile)) $sqliteFile = $tmpRoot . 'sqlite.db';

        $sqliteFile = realpath($sqliteFile);
        if(strpos($sqliteFile, $tmpRoot) !== 0) helper::end("The sqlite file '$sqliteFile' is not in the tmp root '$tmpRoot'");

        $dbh = new PDO("sqlite:$sqliteFile");
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->dbh = $dbh;

        return $dbh;
    }

    public function exec($sql = '')
    {
        $this->dbh->exec($sql);
    }

    public function processSQL(string $sql): string
    {
        return $sql;
    }
}
