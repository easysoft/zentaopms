<?php
/**
 * The control file of sqlite class of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liuyongkai <liuyongkai@easycorp.ltd>
 * @package     sqlite
 * @link        http://www.zentao.net
 */
class sqlite
{
    /**
     * The global app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * The global sqlite object.
     *
     * @var object
     * @access public
     */
    public $dbh = null;

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app;
        $this->app = $app;
    }

    /**
     * Connect to sqlite database.
     *
     * @param  string $sqliteFile
     * @access public
     * @return object
     */
    public function connectSqlite(string $sqliteFile = ''): object
    {
        $tmpRoot = $this->app->getTmpRoot();
        if(empty($sqliteFile) || !is_file($sqliteFile)) $sqliteFile = $tmpRoot . 'sqlite.db';

        $sqliteFile = realpath($sqliteFile);
        if(strpos($sqliteFile, $tmpRoot) !== 0) helper::end("The sqlite file '$sqliteFile' is not in the tmp root '$tmpRoot'");

        $dbh = new PDO("sqlite:$sqliteFile");
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->dbh = $dbh;

        return $this;
    }

    /**
     * Convert mysql sql to sqlite sql.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function processSQL(string $sql)
    {
        $sql = str_replace('`', '', $sql);
        $sql = preg_replace('/\s*int\s*\(\d+\)\s*NOT NULL AUTO_INCREMENT/i', ' INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        $sql = preg_replace('/\s*int\s*\(\d+\)\s*AUTO_INCREMENT/i', ' INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        $sql = preg_replace('/\s*tinyint\(\d+\)/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*smallint\(\d+\)/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*mediumint\(\d+\)/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*int\(\d+\)/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*bigint\(\d+\)/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*float/i', ' REAL', $sql);
        $sql = preg_replace('/\s*double/i', ' REAL', $sql);
        $sql = preg_replace('/\s*decimal/i', ' REAL', $sql);
        $sql = preg_replace('/\s*datetime/i', ' TEXT', $sql);
        $sql = preg_replace('/\s*timestamp/i', ' TEXT', $sql);
        $sql = preg_replace('/\s*time/i', ' TEXT', $sql);
        $sql = preg_replace('/\s*date/i', ' TEXT', $sql);
        $sql = preg_replace('/\s*enum\([^)]*\)/i', ' TEXT', $sql);
        $sql = preg_replace('/\s*set\([^)]*\)/i', ' TEXT', $sql);
        $sql = preg_replace('/\s*year/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*bit/i', ' INTEGER', $sql);
        $sql = preg_replace('/\s*UNSIGNED/i', '', $sql);
        $sql = preg_replace('/\s*DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP/i', '', $sql);

        return $sql;
    }

    /**
     * Execute sql.
     *
     * @param  string $sql
     * @access public
     * @return void
     */
    public function exec(string $sql): void
    {
        $this->dbh->exec($this->processSQL($sql));
    }
}
