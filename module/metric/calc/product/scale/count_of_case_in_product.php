<?php
/**
 * 按产品统计的用例总数。
 * Count of case in product.
 *
 * 范围：product
 * 对象：case
 * 目的：scale
 * 度量名称：按产品统计的用例总数
 * 单位：个
 * 描述：按产品统计的用例总数是指系统或项目中的测试用例总数量。用例是用来验证系统功能和性能的测试场景。统计用例总数可以帮助评估测试覆盖的广度和深度。用例总数越高可能意味着项目进行了全面和充分的测试。
 * 定义：产品中用例的个数求和;过滤已删除的用例;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_case_in_product extends baseCalc
{
    public $dataset = 'getCases';

    public $fieldList = array('t1.product');

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
