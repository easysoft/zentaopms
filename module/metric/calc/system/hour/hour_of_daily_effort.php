<?php
/**
 * 按系统统计的每日日志记录的工时总数。
 * Hour of daily effort.
 *
 * 范围：system
 * 对象：effort
 * 目的：hour
 * 度量名称：按系统统计的每日日志记录的工时总数
 * 单位：小时
 * 描述：按系统统计的每日日志记录的工时总数是指组织每日实际花费的总工时数。该度量项可以用来评估组织的工时投入情况和对资源的利用效率。较高的消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。
 * 定义：所有日志记录的工时之和;记录时间在某日;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class hour_of_daily_effort extends baseCalc
{
    public $dataset = 'getEfforts';

    public $fieldList = array('t1.date', 't1.consumed');

    public $result = array();

    public function calculate($row)
    {
        $date = $row->date;
        $year = $this->getYear($date);
        if(!$year) return false;

        $month    = substr($date, 5, 2);
        $day      = substr($date, 8, 2);
        $consumed = $row->consumed;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += $consumed;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
