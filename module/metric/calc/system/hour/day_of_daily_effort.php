<?php
/**
 * 按全局统计的每日投入总人天。
 * Day of daily effort.
 *
 * 范围：global
 * 对象：effort
 * 目的：hour
 * 度量名称：按全局统计的每日投入总人天
 * 单位：人天
 * 描述：按全局统计的每日投入总人天是指团队每日投入的工作量。该度量项可以用来评估每日人力资源投入情况。
 * 定义：复用：;按全局统计的每日日志记录的工时总数;公式：;按全局统计的每日投入总人天=按全局统计的每日日志记录的工时总数/后台配置的每日可用工时;
 * 度量库：
 * 收集方式：realtime
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
    public $result = array();

    public function getStatement()
    {
        $defaultHours = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('defaultWorkhours')
            ->fetch('value');

        if(empty($defaultHours)) $defaultHours = 7;

        return $this->dao->select("year(date) as year, month(date) as month, day(date) as day, date, sum(consumed) as consumed, $defaultHours as defaultHours")
            ->from(TABLE_EFFORT)
            ->where('deleted')->eq('0')
            ->andWhere('year(date)')->ne('0000')
            ->groupBy('`year`, `month`, `day`, date')
            ->query();
    }

    public function calculate($row)
    {
        $year         = $row->year;
        $date         = $row->date;
        $month        = substr($date, 5, 2);
        $day          = substr($date, 8, 2);
        $consumed     = $row->consumed;
        $defaultHours = $row->defaultHours;

        $dayPerson = round($consumed / $defaultHours, 2);

        $this->result[$year] = array();
        $this->result[$year][$month] = array();
        $this->result[$year][$month][$day] = $dayPerson;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
