<?php
/**
 * 按系统统计的年度投入总人天。
 * Day of annual effort.
 *
 * 范围：system
 * 对象：effort
 * 目的：hour
 * 度量名称：按系统统计的年度投入总人天
 * 单位：人天
 * 描述：按系统统计的年度投入总人天是指团队总共投入的工作天数。该度量项可以用来评估人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。
 * 定义：复用：;按系统统计的年度日志记录的工时总数;公式：;按系统统计的年度投入总人天=按系统统计的年度日志记录的工时总数/后台配置的每日可用工时;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class day_of_annual_effort extends baseCalc
{
    public $dataset = 'getEfforts';

    public $fieldList = array('t1.date', 't1.consumed');

    public $result = array();

    public function calculate($row)
    {
        $year = $this->getYear($row->date);
        if(!$year) return false;

        $consumed     = $row->consumed;
        $defaultHours = $row->defaultHours;

        if(!isset($this->result[$year])) $this->result[$year] = array('consumed' => 0, 'defaultHours' => $defaultHours);

        $this->result[$year]['consumed'] += $consumed;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $consumed     = $value['consumed'];
            $defaultHours = $value['defaultHours'];

            $dayPerson = round($consumed / $defaultHours, 2);
            $records[] = array('year' => $year, 'value' => $dayPerson);
        }
        return $this->filterByOptions($records, $options);
    }
}
