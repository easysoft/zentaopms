<?php
declare(strict_types=1);
/**
 * The control file of branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@cnezsoft.com>
 * @package     bi
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class bi extends control
{
    /**
     * 使用 duckDB 生成最新的 parquet 文件。
     * Use duckDB gen lastest parquet.
     *
     * @access public
     * @return void
     */
    public function genParquetFile()
    {
        /* 检查 duckdb 可执行文件是否存在 */
        $duckFile = $this->app->getBasePath() . 'bin' . DS . 'duck' . DS . 'duckdb';
        if(!file_exists($duckFile)) return;
        if(is_executable($duckFile) && chmod($duckFile, 0777)) return;

        /* 检查 duckdb 存放 parquet 文件的文件夹是否存在 */
        $duckTmpPath = $this->app->getTmpRoot() . 'duckdb';
        if(!is_dir($duckTmpPath) && !mkdir($duckTmpPath)) return;

        $tables = $this->dao->query('SHOW TABLES')->fetchAll();
        if($tables) $tables = array_map(function($obj) { return $obj->Tables_in_maxmaster; }, $tables);

        $copySQL  = '';
        foreach($tables as $table)
        {
            $tablePath = $duckTmpPath . DS . $table;
            $copySQL .= "COPY $table TO '$tablePath.parquet';\n";
        }

        $sqlContent = $this->config->bi->duckSQLTemp;
        $dbConfig   = $this->config->db;
        $variables  = array(
            '{DATABASE}' => $dbConfig->name,
            '{USER}'     => $dbConfig->user,
            '{PASSWORD}' => $dbConfig->password,
            '{HOST}'     => $dbConfig->host,
            '{PORT}'     => $dbConfig->port,
            '{COPYSQL}'  => $copySQL
        );

        foreach($variables as $key => $value)
        {
            $sqlContent = str_replace($key, $value, $sqlContent);
        }

        $runSQLPath = $this->app->getTmpRoot() . 'duckdb' . DS . 'run.sql';
        $result = file_put_contents($runSQLPath, $sqlContent);
        if($result === false) return;

        $exec = "$duckFile :memory: < $runSQLPath 2>&1";
        $output = shell_exec($exec);

        if(empty($output))
        {
            a('success');
            return;
        }

        a($output);
    }
}
