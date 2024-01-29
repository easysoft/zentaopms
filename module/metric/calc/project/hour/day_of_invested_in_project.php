<?php
/**
 * 按项目统计的已投入人天。
 * Day of invested in project.
 *
 * 范围：project
 * 对象：effort
 * 目的：hour
 * 度量名称：按项目统计的已投入人天
 * 单位：人天
 * 描述：按项目统计的已投入人天是指项目总共投入的工作天数。该度量项可以用来评估项目的人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。
 * 定义：复用：;按项目统计的日志记录的工时总数;公式：;按项目统计的已投入人天=按项目统计的项目内所有消耗工时数/后台配置的每日可用工时;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class day_of_invested_in_project extends baseCalc
{
    public $dataset = 'getProjectEfforts';

    public $fieldList = array('t3.id as project', 't1.consumed');

    public $result = array();

    public function calculate($row)
    {
        $project      = $row->project;
        $consumed     = $row->consumed;
        $defaultHours = $row->defaultHours;

        if(!isset($this->result[$project])) $this->result[$project] = 0;

        $this->result[$project] += $defaultHours ? $consumed / $defaultHours : 0;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $project => $days)
        {
            $this->result[$project] = round($days, 2);
        }
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
