<?php
/**
 * 按瀑布项目统计的已完成任务工作的预计工时(EV)。
 * Ev of finished task in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按瀑布项目统计的已完成任务工作的预计工时(EV)
 * 单位：小时
 * 描述：按瀑布项目统计的已完成任务工作的预计工时指的是在瀑布项目管理方法中，已经完成的任务的预计工时。这个度量项用来评估项目进展与实际完成情况的一致性。EV的值越高，代表项目团队在按计划完成任务的工作量方面表现得越好。
 * 定义：复用：;按项目统计的任务进度;按项目统计的任务预计工时数;公式：;按项目统计的已完成任务工作的预计工时(EV)=按项目统计的任务预计工时数*按项目统计的任务进度;要求项目为瀑布项目;过滤已删除的任务;过滤已取消的任务;过滤已删除执行下的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class ev_of_finished_task_in_waterfall extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        $ev = $this->dao->select('project, SUM(estimate) as estimate, SUM(consumed) as consumed, SUM(`left`) as `left`')
            ->from(TABLE_TASK)
            ->where('deleted')->eq('0')
            ->andWhere('parent')->ne('-1')
            ->andWhere("NOT FIND_IN_SET('or', vision)")
            ->andWhere("NOT FIND_IN_SET('lite', vision)")
            ->andWhere('status', true)->in('done,closed')
            ->orWhere('closedReason')->eq('done')
            ->markRight(1)
            ->groupBy('project')
            ->get();

        return $this->dao->select('t1.id as project, t2.estimate, t2.consumed, t2.`left`')
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin("($ev)")->alias('t2')->on('t1.id=t2.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t1.model')->eq('waterfall')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    public function calculate($row)
    {
        $project  = $row->project;
        $estimate = (float)$row->estimate;
        $consumed = (float)$row->consumed;
        $left     = (float)$row->left;
        $total    = $consumed + $left;

        $ev = $total == 0 ? 0 : round($consumed / $total * $estimate, 2);

        if(!isset($this->result[$project])) $this->result[$project] = $ev;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
