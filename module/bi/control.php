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
        $result    = $this->bi->generateParquetFile();
        $endTime   = microtime(true);
        $runTime   = $endTime - $startTime;
        echo "$runTime \n";

        if($result !== true)
        {
            echo $result;
            return;
        }
        echo 'success';
    }

    /**
     * Ajax: get object options.
     *
     * @access public
     * @return void
     */
    public function ajaxGetScopeOptions($type)
    {
        $scopeOptions = $this->bi->getScopeOptions($type);

        $items = array();
        foreach($scopeOptions as $key => $option) $items[] = array('text' => $option, 'value' => $key, 'keys' => $option);

        return print(json_encode($items));
    }

    /**
     * 安装DuckDB引擎。
     * AJAX: Install duckdb.
     *
     * @access public
     * @return void
     */
    public function ajaxInstallDuckdb()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        session_write_close();
        $this->bi->downloadDuckdb();
        echo 'success';
    }

    /**
     * 检查duckdb文件是否下载完成。
     * AJAX: Check duckdb.
     *
     * @access public
     * @return void
     */
    public function ajaxCheckDuckdb()
    {
        $check = $this->bi->checkDuckdbInstall();
        echo(json_encode($check));
    }

    /**
     * AJAX: Get menu of table fields.
     *
     * @access public
     * @return void
     */
    public function ajaxGetTableFieldsMenu()
    {
        $menu = $this->bi->getTableFieldsMenu();
        echo json_encode($menu);
    }
}
