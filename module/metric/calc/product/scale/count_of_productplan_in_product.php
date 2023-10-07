<?php
/**
 * 按产品统计的计划总数。
 * Count of productplan in product.
 *
 * 范围：product
 * 对象：productplan
 * 目的：scale
 * 度量名称：按产品统计的计划总数
 * 单位：个
 * 描述：按产品统计的计划总数是指产品团队创建的所有计划数量。这个度量项可以反映产品团队的规划能力。适当的计划数量可以促进团队高效完成需求。
 * 定义：产品中计划的个数求和;过滤已删除的计划;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_productplan_in_product extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.id', 't1.product');

    public $result = array();

    public function calculate($data)
    {
        if(!isset($this->result[$data->product])) $this->result[$data->product] = 0;
        $this->result[$data->product] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
