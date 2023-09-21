<?php
/**
 * 按产品统计的年度新增发布数。
 * Count of annual created release in product.
 *
 * 范围：product
 * 对象：release
 * 目的：scale
 * 度量名称：按产品统计的年度新增发布数
 * 单位：个
 * 描述：按产品统计的年度新增发布数是指在某年度产品中新增加的发布数量。这个度量项可以反映产品团队在该年度内对新功能和改进的发布能力和速度。新增发布数越多，说明产品团队在该年度内推出了更多的新功能和改进。
 * 定义：产品中发布时间为某年的发布个数求和;过滤已删除的发布;过滤已删除的产品;过滤无效时间;
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
class count_of_annual_created_release_in_product extends baseCalc
{
    public $dataset = 'getProductReleases';

    public $fieldList = array('t1.id', 't1.product', 't1.createdDate');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->createdDate)) return null;

        $year = substr($row->createdDate, 0, 4);
        if($year == '0000') return null;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$row->product])) $this->result[$year][$row->product] = 0;
        $this->result[$year][$row->product] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $products)
        {
            foreach($products as $product => $count)
            {
                $records[] = array(
                    'year'    => $year,
                    'product' => $product,
                    'value'   => $count,
                );
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
