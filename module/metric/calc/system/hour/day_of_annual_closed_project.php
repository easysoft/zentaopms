<?php
/**
 * 按系统统计的年度已关闭项目投入总人天。
 * Day of annual closed project.
 *
 * 范围：system
 * 对象：project
 * 目的：hour
 * 度量名称：按系统统计的年度已关闭项目投入总人天
 * 单位：人天
 * 描述：按系统统计的年度已关闭项目投入总人天是指在某年度关闭项目投入的人天总数。该度量项可以用来评估项目的人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。
 * 定义：复用：;按系统统计的年度关闭项目消耗工时数;公式：;按系统统计的年度关闭项目投入总人天=按系统统计的年度已关闭项目任务的消耗工时数/后台配置的每天可用工时;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class day_of_annual_closed_project extends baseCalc
{
    public $dataset = 'getProjectTasks';

    public $fieldList = array('t1.closedDate', 't2.consumed', 't1.id as project', 't1.status');

    public $result = array();

    public function calculate($data)
    {
        $project  = $data->project;
        $year     = substr($data->closedDate, 0, 4);
        $consumed = $data->consumed;
        $status   = $data->status;
        $defaultHours = $data->defaultHours;

        if($status != 'closed') return false;
        if(empty($year) || $year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$project])) $this->result[$year][$project] = 0;

        $this->result[$year][$project] += $defaultHours ? $consumed / $defaultHours : 0;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $year => $projects)
        {
            foreach($projects as $project => $days)
            {
                $this->result[$year][$project] = round($days, 4);
            }
        }
        $records = getRecords(array('year', 'project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
