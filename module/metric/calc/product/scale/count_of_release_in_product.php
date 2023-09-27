<?php
/**
 * 按产品统计的发布总数。
 * Count of release in product.
 *
 * 范围：product
 * 对象：release
 * 目的：scale
 * 度量名称：按产品统计的发布总数
 * 单位：个
 * 描述：按产品统计的发布总数是指产品中所有发布的数量。这个度量项可以反映产品团队对产品发布的频率和稳定性的掌控程度。发布总数越多，说明产品团队有更多的迭代和产品版本更新。
 * 定义：产品中发布的个数求和;过滤已删除的发布;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_release_in_product extends baseCalc
{
    public $dataset = 'getProductReleases';

    public $fieldList = array('t1.id','t1.product');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
        $this->result[$row->product] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
