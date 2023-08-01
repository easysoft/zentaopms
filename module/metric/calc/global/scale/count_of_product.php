<?php
/**
 * 按全局统计的产品总数。
 * Count of product.
 *
 * 范围：global
 * 对象：product
 * 目的：scale
 * 度量名称：按全局统计的产品总数
 * 单位：个
 * 描述：按全局统计的产品总数表示整个组织中的产品数量。此度量项反映了组织所提供的产品数量，可以用于评估组织的产品组合的规模和多样性。
 * 定义：所有产品的个数求和;过滤已删除的产品;（过滤影子产品）;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_product extends baseCalc
{
    public $dataset = 'getProducts';

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
