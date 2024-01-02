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
 * 描述：按产品统计的年度新增发布数是指某年度产品中新增加的发布数量，该度量项可以反映产品团队在该年度内对产品新功能和改进的发布能力和速度。新增发布数越多，说明产品团队在该年度内推出了更多的新功能和改进。
 * 定义：产品中发布个数求和;发布时间为某年;过滤已删除的发布;过滤已删除的产品;过滤无效时间;
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

    public $fieldList = array('t1.id', 't1.product', 't1.date');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->date)) return null;

        $year = substr($row->date, 0, 4);
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
