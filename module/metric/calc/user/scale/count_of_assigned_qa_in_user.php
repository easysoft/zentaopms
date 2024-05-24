<?php
/**
 * 按人员统计的被指派的QA数
 * Count of assigned qa in user.
 *
 * 范围：user
 * 对象：QA
 * 目的：scale
 * 度量名称：按人员统计的被指派的QA数
 * 单位：个
 * 描述：按人员统计的被指派的QA数表示每个人被指派的质量保证问题之和。反映了每个人员需要处理的质量保证问题的规模。该数值越大，说明需要处理的质量保证问题越多。
 * 定义：所有待处理的QA个数求和（包含：待处理质量保证计划、待处理不符合项） 指派给为某人 质量保证计划状态为待检查、不符合项状态为待解决 过滤已删除的质量保证计划和不符合项 过滤已删除项目的质量保证计划和不符合项
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_qa_in_user extends baseCalc
{
    public $dataset = 'getQAs';

    public $fieldList = array('assignedTo');

    public $result = array();

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;

        if(empty($assignedTo) || $assignedTo == 'closed') return false;

        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = 0;
        $this->result[$assignedTo] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
