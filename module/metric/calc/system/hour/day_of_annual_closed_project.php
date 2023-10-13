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
    public $dataset = null;

    public $fieldList = array();

    public $result = array();

    public function getStatement()
    {
        $task = $this->dao->select('SUM(consumed) as consumed, project')
            ->from(TABLE_TASK)
            ->where('deleted')->eq('0')
            ->andWhere('parent')->ne('-1')
            ->andWhere("NOT FIND_IN_SET('or', vision)")
            ->andWhere("NOT FIND_IN_SET('lite', vision)")
            ->groupBy('project')
            ->get();

        return $this->dao->select('t1.id as project, LEFT(t1.closedDate, 4) as year, t2.consumed')
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin("($task)")->alias('t2')->on('t1.id = t2.project')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.status')->eq('closed')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.closedDate')->notZeroDatetime()
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    public function calculate($data)
    {
        $project  = $data->project;
        $year     = $data->year;
        $consumed = $data->consumed;

        if(!is_numeric($consumed) || empty($consumed)) return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();

        $this->result[$year][$project] = round($consumed, 2);
    }

    public function getResult($options = array())
    {
        $defaultWorkhours = $this->dao->select('value')->from(TABLE_CONFIG)->where('`key`')->eq('defaultWorkhours')->fetch();
        $records = array();
        foreach($this->result as $year => $projects)
        {
            foreach($projects as $project => $value)
            {
                $value = $defaultWorkhours ? round($value / $defaultWorkhours->value, 4) : 0;
                $records[] = array('project' => $project, 'year' => $year, 'value' => $value);
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
