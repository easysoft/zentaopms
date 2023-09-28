<?php
/**
 * 按系统统计的计划总数。
 * Count of productplan.
 *
 * 范围：system
 * 对象：productplan
 * 目的：scale
 * 度量名称：按系统统计的计划总数
 * 单位：个
 * 描述：按系统统计的计划总数反映了组织中进行中和已完成的计划数量，用于评估组织的规划效率、预测资源需求、优化项目组织与协调，并用于绩效评估和目标设定。
 * 定义：所有的计划的个数求和;过滤已删除的计划;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_productplan extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($data)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
