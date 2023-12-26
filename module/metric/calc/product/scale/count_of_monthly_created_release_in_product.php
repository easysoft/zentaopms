<?php
/**
 * 按产品统计的月度新增发布数。
 * Count of monthly created release in product.
 *
 * 范围：product
 * 对象：release
 * 目的：scale
 * 度量名称：按产品统计的月度新增发布数
 * 单位：个
 * 描述：按产品统计的月度新增发布数是指在某月产品中新增加的发布数量。这个度量项可以反映产品团队在该月内对新功能和改进的发布能力和速度。新增发布数越多，说明产品团队在该月内推出了更多的新功能和改进。
 * 定义：产品中发布时间为某年某月的发布个数求和;过滤已删除的发布;过滤已删除的产品;过滤无效时间;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_created_release_in_product extends baseCalc
{
    public $dataset = 'getProductReleases';

    public $fieldList = array('t1.id', 't1.product', 't1.date');

    public $result = array();

    public function calculate($row)
    {
        $product     = $row->product;
        $createdDate = $row->date;

        if(empty($createdDate)) return false;

        $year = substr($createdDate, 0, 4);
        if($year == '0000') return false;

        $month = substr($createdDate, 5, 2);

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$month])) $this->result[$product][$year][$month] = 0;

        $this->result[$product][$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
