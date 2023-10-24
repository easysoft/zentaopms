<?php
/**
 * 按产品统计的每日新增Bug数。
 * Count of daily created bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的每日新增Bug数
 * 单位：个
 * 描述：按产品统计的每日新增Bug数是指在每天的产品开发过程中新发现并记录的Bug数量。该度量项可以体现产品开发过程中Bug的发现速度和趋势，较高的新增Bug数可能意味着存在较多的问题需要解决，同时也可以帮助识别产品开发过程中的瓶颈和潜在的质量风险。
 * 定义：产品中Bug数求和;创建时间为某日;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_created_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->openedDate)) return false;

        $date = substr($row->openedDate, 0, 10);
        list($year, $month, $day) = explode('-', $date);
        if($year == '0000') return false;

        if(!isset($this->result[$row->product]))                      $this->result[$row->product] = array();
        if(!isset($this->result[$row->product][$year]))               $this->result[$row->product][$year] = array();
        if(!isset($this->result[$row->product][$year][$month]))       $this->result[$row->product][$year][$month] = array();
        if(!isset($this->result[$row->product][$year][$month][$day])) $this->result[$row->product][$year][$month][$day] = 0;

        $this->result[$row->product][$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $months)
            {
                foreach($months as $month => $days)
                {
                    foreach($days as $day => $value)
                    {
                        $records[] = array('product' => $product, 'year' => $year, 'month' => $month, 'day' => $day, 'value' => $value);
                    }
                }
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
