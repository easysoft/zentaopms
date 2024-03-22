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
     * 全局$app对象。
     * The global app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 全局$config对象。
     * The global config object.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * 全局$mysql对象。
     * The global mysql object.
     *
     * @var object
     * @access public
     */
    public $mysql;

    /**
     * 全局$sqlite对象。
     * The global sqlite object.
     *
     * @var object
     * @access public
     */
    public $dbh = null;

    /**
     * __construct.
     *
     * @access public
     * @return void
     */
    public function __construct(object $params)
    {
        global $app, $config, $dbh;
        $this->app        = $app;
        $this->mysql      = $dbh;
        $this->config     = $config;

        $file = empty($params) || !isset($params->file) ? '' : $params->file;
        $this->connectSqlite($file);
    }

    /**
     * 使用PDO连接SQLite数据库。
     * Connect to sqlite database by PDO.
     *
     * @param  string $sqliteFile
     * @access public
     * @return object
     */
    public function connectSqlite(string $sqliteFile = ''): object
    {
        $tmpRoot = $this->app->getTmpRoot();
        if(empty($sqliteFile) || !is_file($sqliteFile)) $sqliteFile = $tmpRoot . DS . 'sqlite' . DS . 'sqlite.db';

        $sqliteFile = realpath($sqliteFile);
        if(strpos($sqliteFile, $tmpRoot) !== 0) return helper::end("The sqlite file '$sqliteFile' is not in the tmp root '$tmpRoot'");
        if(!is_file($sqliteFile)) return helper::end("The sqlite file '$sqliteFile' is not exists");

        $dbh = new PDO("sqlite:$sqliteFile");
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->dbh = $dbh;

        return $this;
    }

    /**
     * 将MySQL数据类型转化为SQLite数据类型。
     * Convert MySQL attr to SQLite attr.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function formatAttr(string $sql): string
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
     * 处理SQL语句。
     * Format sql.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function formatSQL(string $sql): string
    {
        // $sql = $this->formatAttr($sql);
        return $sql;
    }

    /**
     * 尝试从SQLite中执行查询语句，如果抛出异常，则从MySQL中执行查询语句。
     * Query sql by SQLite, if throw exception, query sql by MySQL.
     *
     * @param  string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function query(string $sql): PDOStatement|false
    {
        try
        {
            return $this->dbh->query($this->formatSQL($sql));
        }
        catch(PDOException $e)
        {
            return $this->mysql->query($sql);
        }
    }

    /**
     * 执行查询语句。
     * Query sql without format.
     *
     * @param  string $sql
     * @access public
     * @return PDOStatement|false
     */
    public function rawQuery($sql): PDOStatement|false
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    /**
     * 执行SQL语句，返回受影响的行数。
     * Execute sql.
     *
     * @param  string $sql
     * @access public
     * @return int|false
     */
    public function exec(string $sql): int|false
    {
        return $this->dbh->exec($sql);
    }

    /**
     * 开始事务。
     * Begin transaction.
     *
     * @access public
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * 回滚事务。
     * Roll back if transaction failed.
     *
     * @access public
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->dbh->rollBack();
    }

    /**
     * 提交事务。
     * Commit transaction.
     *
     * @access public
     * @return bool
     */
    public function commit(): bool
    {
        return $this->dbh->commit();
    }
}
