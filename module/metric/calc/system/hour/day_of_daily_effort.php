<?php
/**
 * 按系统统计的每日投入总人天。
 * Day of daily effort.
 *
 * 范围：system
 * 对象：effort
 * 目的：hour
 * 度量名称：按系统统计的每日投入总人天
 * 单位：人天
 * 描述：按系统统计的每日投入总人天是指团队每日投入的工作量。该度量项可以用来评估每日人力资源投入情况。
 * 定义：复用：;按系统统计的每日日志记录的工时总数;公式：;按系统统计的每日投入总人天=按系统统计的每日日志记录的工时总数/后台配置的每日可用工时;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class day_of_daily_effort extends baseCalc
{
    public $dataset = 'getEfforts';

    public $fieldList = array('t1.date', 't1.consumed');

    public $result = array();

    public function calculate($row)
    {
        $date = $row->date;
        if(empty($date)) return false;

        $year = substr($date, 0, 4);
        if($year == '0000') return false;

        $month        = substr($date, 5, 2);
        $day          = substr($date, 8, 2);
        $consumed     = $row->consumed;
        $defaultHours = $row->defaultHours;

        $dayPerson = round($consumed / $defaultHours, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] += $dayPerson;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
