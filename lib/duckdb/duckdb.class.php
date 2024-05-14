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
     * 全局对象$app
     * The global app object.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 全局对象$config
     * The global config object.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * 全局对象$sql
     * The global config object.
     *
     * @var object
     * @access public
     */
    public $sql;

    /**
     * 全局对象$binPath
     * The global config object.
     *
     * @var object
     * @access public
     */
    public $binPath;

    /**
     * 全局对象$tmpPath
     * The global config object.
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
        $this->app     = $app;
        $this->config  = $config;

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
        $this->binPath = $this->app->getBasePath() . 'bin' . DS . 'duckdb' . DS . 'duckdb';
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
        $this->tmpPath = $this->app->getTmpRoot() . 'duckdb' . DS . $module . DS;
    }

    /**
     * 替换sql语句中的表为 parquet 文件路径。
     * Replace sql table to parquet file path.
     *
     * @access public
     * @return this.
     */
    public function query($sql = '')
    {
        /* $0全量匹配以 prefix 开头的表，替换为对应的 parquet 文件。 */
        $pattern     = "/{$this->config->db->prefix}\S+/";
        $replacement = "'" . $this->tmpPath . "$0" . '.parquet' . "'";

        $this->sql = preg_replace($pattern, $replacement, $sql);

        return $this;
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
