<?php
/**
 * 按产品统计的年度新增用例数。
 * Count of annual created case in product.
 *
 * 范围：product
 * 对象：case
 * 目的：scale
 * 度量名称：按产品统计的年度新增用例数
 * 单位：个
 * 描述：按产品统计的年度新增用例数是指产品在某年度新增的测试用例数量。统计年度新增用例数可以帮助评估系统或项目在不同阶段的测试覆盖和测试深度。年度新增用例数的增加可能意味着对新功能和需求进行了充分的测试。
 * 定义：产品中用例的个数求和;创建时间为某年;过滤已删除的用例;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_case_in_product extends baseCalc
{
    public $dataset = 'getCases';

    public $fieldList = array('t1.product', 't1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $product    = $row->product;
        $openedDate = $row->openedDate;

        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;

        $this->result[$product][$year] += 1;

    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array('product' => $product, 'year' => $year, 'value' => $value);
            }
        }

        return $this->filterByOptions($records, $options);

    }
}
