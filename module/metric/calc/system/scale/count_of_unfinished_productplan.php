<?php
/**
 * 按全局统计的未完成计划数。
 * Count of unfinished productplan.
 *
 * 范围：global
 * 对象：productplan
 * 目的：scale
 * 度量名称：按全局统计的未完成计划数
 * 单位：个
 * 描述：按全局统计的未完成计划数表示未完成的计划数量。该度量项反映了组织中未完成的计划数量，可以用于评估组织的计划执行进展和挑战。
 * 定义：复用：;按全局统计的已完成计划数;按全局统计的计划总数;公式：;按全局统计的未完成计划数=按全局统计的计划总数-按全局统计的已完成计划数;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unfinished_productplan extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.status', 't1.closedReason');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'done' || ($row->status == 'closed' && $row->closedReason == 'done')) return false;
        $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
