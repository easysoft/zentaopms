<?php
/**
 * 按系统统计的年度完成执行中延期完成执行数。
 * Count of delayed finished execution which annual finished.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的年度完成执行中延期完成执行数
 * 单位：个
 * 描述：按系统统计的年度完成执行中延期完成执行数是指在某年度关闭的执行中，超过预定计划时间关闭的执行数量。这个度量项可以用来衡量团队在某年度的按时完成能力，并识别延期原因并采取适当措施。较高的延期关闭执行数可能需要团队关注执行计划和资源安排的问题。
 * 定义：所有的关闭时间为某年的执行个数求和;关闭日期>执行开始时计划截止日期;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delayed_finished_execution_which_annual_finished extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.status', 't1.closedDate', 't1.firstEnd');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->closedDate) || empty($row->firstEnd)) return false;

        $year = substr($row->closedDate, 0, 4);
        if($year == '0000') return false;

        if($row->status == 'closed' and $row->closedDate > $row->firstEnd)
        {
            if(!isset($this->result[$year])) $this->result[$year] = 0;
            $this->result[$year] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
