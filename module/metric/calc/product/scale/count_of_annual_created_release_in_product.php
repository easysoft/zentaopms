<?php
/**
 * 按产品统计的年度新增发布数。
 * .
 *
 * 范围：product
 * 对象：release
 * 目的：scale
 * 度量名称：按产品统计的年度新增发布数
 * 单位：个
 * 描述：产品中发布时间为某年的发布个数求和 过滤已删除的发布 过滤已删除的产品 过滤无效时间
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
        $year = substr($row->createdDate, 0, 4);

        if($year == '0000') return null;
        if(empty($year))    return null;

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
                $records[] = (object)array(
                    'year'    => $year,
                    'product' => $product,
                    'value'   => $count,
                );
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
