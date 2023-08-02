<?php
/**
 * 按全局统计的正常的产品数。
 * Count of normal product.
 *
 * 范围：global
 * 对象：product
 * 目的：scale
 * 度量名称：按全局统计的正常的产品数
 * 单位：个
 * 描述：按全局统计的正常的产品数表示处于正常状态的产品数量。此度量项反映了组织中处于正常研发和运营状态的产品数量，可以用于评估组织的研发能力和运营能力。
 * 定义：所有产品的个数求和;状态为正常;过滤已删除的产品;（过滤影子产品）;
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
class count_of_normal_product extends baseCalc
{
    public $dataset = 'getProducts';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'normal') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
