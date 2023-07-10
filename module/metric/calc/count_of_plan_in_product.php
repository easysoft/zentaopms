<?php
/**
 * 按产品统计的计划总数。
 * Count of plan in product.
 *
 * 范围：产品
 * 对象：计划
 * 目的：规模
 * 度量名称：按产品统计的计划总数
 * 单位：个
 * 描述：产品中计划的个数求和，过滤已删除的计划啊，过滤已删除的产品。
 * 度量库：产品度量库
 * 收集方式：实时
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_plan_in_product extends baseMetric
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.id', 't1.product');

    public $result = array();

    public function calculate($data)
    {
        if(!isset($this->result[$data->product])) $this->result[$data->product] = 0;
        $this->result[$data->product] += 1;
    }

    public function getResult()
    {
        $records = array();
        foreach($this->result as $product => $value)
        {
            $records[] = array('product' => $product, 'value' => $value);
        }

        return $records;
    }
}
