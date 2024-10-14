<?php
declare(strict_types=1);
/**
 * The zen file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     compile
 * @link        https://www.zentao.net
 */
class compileZen extends compile
{
    /**
     * 构造搜索表单数据。
     * Build search form data.
     *
     * @param  int       $repoID
     * @param  int       $jobID
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildSearchForm(int $repoID = 0, int $jobID = 0, int $queryID = 0)
    {
    }
}
