<?php
/**
 * 按系统统计的任务剩余工时数。
 * Left of task.
 *
 * 范围：system
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的任务剩余工时数
 * 单位：小时
 * 描述：按系统统计的任务剩余工时数是指任务当前剩余工时的总和，用于完成所有任务。该度量项可以用来评估团队或组织在任务执行过程中剩余的工作量和时间，以及为完成任务所需的资源和计划。较小的任务剩余工时总数可能表示团队即将完成任务。
 * 定义：所有的任务的剩余工时数求和;过滤父任务;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class left_of_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.left', 't1.parent');

    public $result = 0;

    public function calculate($row)
    {
        $parent = $row->parent;
        if($parent == '-1') return false;

        if(empty($row->left)) return false;

        $this->result += $row->left;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
