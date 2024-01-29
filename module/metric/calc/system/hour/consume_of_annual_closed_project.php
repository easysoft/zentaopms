<?php
/**
 * 按系统统计的年度关闭项目的任务消耗工时数。
 * Consume of annual closed project.
 *
 * 范围：system
 * 对象：project
 * 目的：hour
 * 度量名称：按系统统计的年度关闭项目的任务消耗工时数
 * 单位：小时
 * 描述：按系统统计的年度关闭项目的任务消耗工时数是指在某年度关闭的项目中任务消耗的总工时数。该度量项可以用来评估团队或组织在任务执行过程中的工时投入情况和对资源的利用效率。较高的年度关闭项目的任务消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。
 * 定义：所有项目任务的消耗工时数求和;项目状态为已关闭;关闭时间为某年;过滤父任务;过滤已删除的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consume_of_annual_closed_project extends baseCalc
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

        if($status != 'closed') return false;
        if(empty($year) || $year == '0000') return false;

        if(!is_numeric($consumed) || empty($consumed)) return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();

        $this->result[$year][$project] = round($consumed, 2);
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $projects)
        {
            foreach($projects as $project => $value)
            {
                $records[] = array('project' => $project, 'year' => $year, 'value' => $value);
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
