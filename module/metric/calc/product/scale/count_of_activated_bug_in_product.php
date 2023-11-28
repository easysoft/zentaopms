<?php
/**
 * 按产品统计的激活Bug数。
 * Count of activated bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的激活Bug数
 * 单位：个
 * 描述：按产品统计的激活Bug数是指产品中当前状态为激活的Bug数量。这个度量项反映了产品当前存在的待处理问题数量。激活Bug总数越多可能代表产品的稳定性较低，需要加强Bug解决的速度和质量。
 * 定义：产品中激活Bug的个数求和;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_activated_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.status');

    public $result = array();

    public function calculate($data)
    {
        $product = $data->product;
        $status  = $data->status;

        if(!isset($this->result[$product])) $this->result[$product] = 0;
        if($status == 'active') $this->result[$product] += 1;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
