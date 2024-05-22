<?php
/**
 * 按人员统计的待处理需求池需求数
 * Count of assigned demand in user.
 *
 * 范围：user
 * 对象：demand
 * 目的：scale
 * 度量名称：按人员统计的待处理需求池需求数
 * 单位：个
 * 描述：按人员统计的待处理需求池需求数表示每个人待处理的需求池需求数量之和。反映了每个人员需要处理的需求池需求数量的规模。该数值越大，说明需要投入越多的时间处理需求池需求。
 * 定义：所有需求池需求个数求和，过滤已删除的需求池需求、过滤状态为已关闭的需求池需求
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_demand_in_user extends baseCalc
{
    public $dataset = 'getDemands';

    public $fieldList = array('status', 'assignedTo');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'closed')
        {
            if(!isset($this->result[$row->assignedTo])) $this->result[$row->assignedTo] = 0;
            $this->result[$row->assignedTo] += 1;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}

