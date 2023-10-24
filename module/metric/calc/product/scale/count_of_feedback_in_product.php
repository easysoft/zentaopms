<?php
/**
 * 按产品统计的反馈总数。
 * Count of feedback in product.
 *
 * 范围：product
 * 对象：feedback
 * 目的：scale
 * 度量名称：按产品统计的反馈总数
 * 单位：个
 * 描述：产品的反馈总数
 * 定义：产品中反馈的个数求和;过滤已删除的反馈;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_feedback_in_product extends baseCalc
{
    public $dataset = 'getFeedbacks';

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
