<?php
/**
 * 按执行统计的激活Bug数。
 * Count of activated bug in execution.
 *
 * 范围：execution
 * 对象：bug
 * 目的：scale
 * 度量名称：按执行统计的激活Bug数
 * 单位：个
 * 描述：按执行统计的激活Bug数是指当前未解决的Bug数量。这个度量项反映了执行当前存在的待解决问题数量。激活Bug总数越多可能代表执行的稳定性较低，需要加强Bug解决的速度和质量。
 * 定义：执行中Bug个数求和;状态为激活;过滤已删除的Bug;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_activated_bug_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array('t1.status', 't1.execution');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->execution;
        $status    = $row->status;

        if(!isset($this->result[$execution])) $this->result[$execution] = 0;

        if($status == 'active') $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
