<?php
/**
 * 按产品统计的每周新增工单数。
 * Count of weekly created ticket.
 *
 * 范围：product
 * 对象：ticket
 * 目的：scale
 * 度量名称：按产品统计的每周新增工单数
 * 单位：个
 * 描述：按产品统计的每周新增工单数表示产品中每周新创建的工单数量之和。较高的每周新增工单数可能暗示着产品近期发布的功能存在较多问题，需要及时处理。 定义：所有的发布个数求和;发布时间为某周;过滤已删除的发布;过滤已删除的产品;
 * 计算规则：产品中所有工单个数求和，创建时间为某周，过滤已删除的工单，过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Tingting Dai <daitingting@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_weekly_created_ticket_in_product extends baseCalc
{
    public $dataset = 'getTickets';

    public $fieldList = array('t1.product', 't1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $product = $row->product;
        $year    = $this->getYear($row->openedDate);
        $week    = $this->getWeek($row->openedDate);

        if(!$year) return false;

        if(!isset($this->result[$product]))               $this->result[$product] = array();
        if(!isset($this->result[$product][$year]))        $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$week])) $this->result[$product][$year][$week] = 0;
        $this->result[$product][$year][$week] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'year', 'week', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
