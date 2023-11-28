<?php
/**
 * 按产品统计的Bug总数。
 * Count of bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的Bug总数
 * 单位：个
 * 描述：按产品统计的Bug总数是指在产品中发现的所有Bug的数量。这个度量项反映了产品整体Bug质量情况。Bug总数越多可能代表产品的代码质量存在问题，需要进行进一步的解决和改进。
 * 定义：产品中Bug的个数求和;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product');

    public $result = array();

    public function calculate($data)
    {
        $product = $data->product;
        if(!isset($this->result[$product])) $this->result[$product] = 0;

        $this->result[$product] += 1;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $value)
        {
            $records[] = array('product' => $product, 'value' => $value);
        }

        return $this->filterByOptions($records, $options);
    }
}
