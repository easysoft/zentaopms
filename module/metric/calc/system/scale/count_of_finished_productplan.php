<?php
/**
 * 按系统统计的已完成计划数。
 * Count of finished productplan.
 *
 * 范围：system
 * 对象：productplan
 * 目的：scale
 * 度量名称：按系统统计的已完成计划数
 * 单位：个
 * 描述：按系统统计的已完成计划数反映了组织在某年度内已经完成的计划数量，用于评估组织的绩效、生产效率和客户满意度，并用于规划和资源优化。
 * 定义：所有计划的个数求和;状态为已完成;过滤已删除的计划;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_productplan extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.status', 't1.closedReason');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'done') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
