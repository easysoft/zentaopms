<?php
/**
 * 按代码库统计的日代码新增行数。
 * Count of daily add rows in codebase.
 *
 * 范围：repo
 * 对象：commit
 * 目的：rate
 * 度量名称：按代码库统计的每日代码提交次数
 * 单位：行
 * 描述：按代码库统计的的日代码新增行数是指代码库在某日的代码提交新增的代码行数量。这个度量项可以反映代码库单日开发活动频率和代码更新情况。
 * 定义：代码库中代码提交新增行数求和，提交时间为某日。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yang Li<liyang@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_add_rows_in_codebase extends baseCalc
{
    public $useSCM = true;

    public $result = array();

    /**
     * 通过API获取提交次数。
     * Get commits by API.
     *
     * @param  object $repo
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return object
     */
    public function getCommitCount($repo, $begin, $end)
    {
    }
}
