<?php
/**
 * 按系统统计的已完成执行中按期完成执行数。
 * Count of undelayed finished execution which finished.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的已完成执行中按期完成执行数
 * 单位：个
 * 描述：按系统统计的已完成执行中按时完成执行数表示在整个系统中按期完成执行的数量，可以用来评估团队的执行能力和效率。
 * 定义：所有的执行个数求和;状态为已关闭;关闭日期<=执行开始时计划截止日期;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_undelayed_finished_execution_which_finished extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.status', 't1.closedDate', 't1.firstEnd');

    public $result = 0;

    public function calculate($row)
    {
        if(empty($row->closedDate) || empty($row->firstEnd)) return false;

        if($row->status == 'closed' && $row->closedDate <= $row->firstEnd) $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
