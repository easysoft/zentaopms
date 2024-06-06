<?php
/**
 * ZenTaoPHP的DuckDB类。
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
 * DuckDB类。
 * DuckDB, data access object.
 *
 * @package framework
 */
class duckdb
{
    /**
     * 全局变量$baseRoot
     * The global basePath variable.
     *
     * @var object
     * @access public
     */
    public $baseRoot;

    /**
     * 全局变量$tmpRoot
     * The global tmpRoot variable.
     *
     * @var object
     * @access public
     */
    public $tmpRoot;

    /**
     * 全局变量$prefix
     * The global prefix variable.
     *
     * @var object
     * @access public
     */
    public $prefix;

    /**
     * 全局变量$sql
     * The global sql variable.
     *
     * @var object
     * @access public
     */
    public $sql;

    /**
     * 全局变量$binPath
     * The global binPath variable.
     *
     * @var object
     * @access public
     */
    public $binPath;

    /**
     * 全局变量$tmpPath
     * The global tmpPath variable.
     *
     * @var object
     * @access public
     */
    public $tmpPath;

    /**
     * 构造方法。
     * The construct method.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app, $config;
        $this->prefix   = $config->db->prefix;
        $this->baseRoot = $app->getBasePath();
        $this->tmpRoot  = $app->getTmpRoot();

        $this->setBinPath();
        $this->setTmpPath();
    }

    /**
     * 设置 duckdb 可执行文件路径。
     * Set duckdb bin path.
     *
     * @access public
     * @return void.
     */
    public function setBinPath()
    {
        $this->binPath = $this->baseRoot . 'bin' . DS . 'duckdb' . DS . 'duckdb';
    }

    /**
     * 设置 duckdb tmp parquet 文件路径。
     * Set duckdb tmp parquet file path.
     *
     * @access public
     * @return void.
     */
    public function setTmpPath($module = 'bi')
    {
        $this->tmpPath = $this->tmpRoot . 'duckdb' . DS . $module . DS;
    }

    /**
     * Query 方法。
     * Query function.
     *
     * @param  string sql
     * @access public
     * @return this.
     */
    public function query($sql = '')
    {
        $sqlparser = dirname(dirname(__FILE__)) . '/sqlparser/sqlparser.class.php';
        include_once $sqlparser;

        $parser    = new sqlparser($sql);
        $statement = $parser->statements[0]->bodyParser->statements[0];
        $tables = $this->getTables($statement);

        $sql = $this->replaceBackQuote($sql);
        $sql = $this->replaceTable2Parquet($sql, $tables);
        $sql = $this->standLimit($sql);

        $this->sql = $sql;

        return $this;
    }

    /**
     * 将mysql的``替换为duckdb可执行的""。
     * Replace ` to ".
     *
     * @param  string sql
     * @access public
     * @return sql.
     */
    private function replaceBackQuote($sql)
    {
        $sql = trim($sql);
        $sql = trim($sql, ';');
        $sql = str_replace(array('`', '"'), array('', '\"'), $sql);

        return $sql;
    }

    /**
     * 替换sql语句中的表为parquet文件路径。
     * Replace sql table to parquet file path.
     *
     * @param  string sql
     * @access public
     * @return sql.
     */
    private function replaceTable2Parquet($sql, $tables)
    {
        if(empty($tables)) return $sql;

        $replaceTables = array();
        foreach($tables as $table) $replaceTables[] = "{$this->tmpPath}{$table}.parquet";
        $sql = str_replace($tables, $replaceTables, $sql);

        return $sql;
    }

    /**
     * 将LIMIT语句替换为duckdb可执行的格式。
     * Standard LIMIT syntax.
     *
     * @param  string sql
     * @access public
     * @return sql.
     */
    private function standLimit($sql)
    {
        // 匹配 "LIMIT x, y" 并替换为 "LIMIT y OFFSET x"
        $limitpattern = '/LIMIT\s+(\d+)\s*,\s*(\d+)/i';
        $replacement  = 'LIMIT $2 OFFSET $1';

        $sql = preg_replace($limitpattern, $replacement, $sql);

        return $sql;
    }

    /**
     * 替换sql语句中的表为parquet文件路径。
     * Replace sql table to parquet file path.
     *
     * @access public
     * @return this.
     */
    public function getResult()
    {
        $exec   = "$this->binPath :memory: \"$this->sql\" -json 2>&1";
        $output = shell_exec($exec);

        if(empty($output)) $output = '';
        $rows = json_decode($output);
        /* 有内容但是 json 解析失败，说明是报错。*/
        if($output and !$rows)
        {
            return throw new Exception($output);
        }
        else
        {
            return $rows ? $rows : array();
        }
    }

    //-------------------- Fetch相关方法(Fetch related methods) -------------------//

    /**
     * 获取一个记录。
     * Fetch one record.
     *
     * @access public
     * @return object|mixed
     */
    public function fetch()
    {
        $rows = $this->getResult();
        return $rows ? current($rows) : '';
    }

    /**
     * 获取所有记录。
     * Fetch all records.
     *
     * @access public
     * @return array
     */
    public function fetchAll()
    {
        return $this->getResult();
    }
}
