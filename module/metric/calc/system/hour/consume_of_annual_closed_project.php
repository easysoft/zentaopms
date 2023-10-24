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
