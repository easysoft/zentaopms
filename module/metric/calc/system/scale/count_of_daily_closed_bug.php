<?php
/**
 * 按系统统计的每日关闭Bug数。
 * Count of daily closed bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的每日关闭Bug数
 * 单位：个
 * 描述：按系统统计的每日关闭Bug数是指组织每日被确认并关闭的Bug的数量。该度量项可以帮助我们了解组织对已解决的Bug进行确认与关闭的速度和效率。
 * 定义：所有每日关闭的Bug数求和;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_closed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $closedDate = $row->closedDate;
        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($closedDate, 5, 2);
        $day   = substr($closedDate, 8, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
