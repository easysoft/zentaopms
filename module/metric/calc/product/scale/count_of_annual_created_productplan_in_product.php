<?php
/**
 * 按产品统计的年度新增计划数。
 * Count of annual created productplan in product.
 *
 * 范围：product
 * 对象：productplan
 * 目的：scale
 * 度量名称：按产品统计的年度新增计划数
 * 单位：个
 * 描述：按产品统计的年度新增计划数是指某年度产品团队新创建的计划数量。这个度量项可以反映产品团队对于新需求的接收能力和规模的扩展。新增计划数越多，说明产品团队在该年度内面临着更多的新挑战和需求。
 * 定义：产品中创建时间为某年的计划个数求和;过滤已删除的计划;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_productplan_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t1.id,t1.product,year(t1.createdDate) as year')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->query();
    }

    public function calculate($row)
    {
        if(empty(trim($row->year))) return false;
        if($row->year == '0000')    return false;

        if(!isset($this->result[$row->year]))                $this->result[$row->year] = array();
        if(!isset($this->result[$row->year][$row->product])) $this->result[$row->year][$row->product] = 0;
        $this->result[$row->year][$row->product] ++;
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
