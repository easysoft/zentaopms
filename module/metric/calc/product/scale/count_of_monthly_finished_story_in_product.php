<?php
/**
 * 按产品统计的月度完成研发需求数。
 * Count of monthly finished story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的月度完成研发需求数
 * 单位：个
 * 描述：按产品统计的月度完成研发需求数表示每月完成的研发需求的数量。该度量项反映了产品的月度研发成果，可以用于评估产品团队的研发需求完成情况和效率。
 * 定义：产品中关闭时间为某年某月且关闭原因为已完成的研发需求的个数求和;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_finished_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $product    = $row->product;
        $closedDate = $row->closedDate;

        if(empty($closedDate)) return false;

        $year  = substr($closedDate, 0, 4);
        $month = substr($closedDate, 5, 2);

        if(empty($year) || empty($month)) return false;
        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$month])) $this->result[$product][$year][$month] = 0;

        $this->result[$product][$year][$month] ++;

    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'year', 'month', 'value'));
        return $this->filterByOptions($records, $options);

    }
}
