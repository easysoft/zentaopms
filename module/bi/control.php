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
    public function syncParquetFile()
    {
        $startTime = microtime(true);
        $duckdb = $this->bi->getDuckDBPath();
        if(!$duckdb)
        {
            echo("DuckDB bin path not exists.");
            return;
        }

        $duckdbTmpPath = $this->bi->getDuckDBTmpDir();
        if(!$duckdbTmpPath)
        {
            echo("Create DuckDB tmp dir permission denied.");
            return;
        }

        $copySQL = $this->bi->prepareCopySQL($duckdbTmpPath);
        $command = $this->bi->prepareSyncCommand($duckdb->bin, $duckdb->extension, $copySQL);

        $output = shell_exec($command);
        $endTime = microtime(true);
        $runTime = $endTime - $startTime;
        echo "$runTime \n";

        if(empty($output))
        {
            echo('success');
            return;
        }

        echo($output);
    }
}
