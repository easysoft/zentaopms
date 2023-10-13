<?php
/**
 * 按瀑布项目统计的进度偏差率。
 * Sv in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：rate
 * 度量名称：按瀑布项目统计的进度偏差率
 * 单位：%
 * 描述：按瀑布项目统计的进度偏差率是用来衡量项目当前的进度与计划进度之间的差异。它通过计算已完成的工作量与计划工作量之间的差异来评估项目的进展情况。
 * 定义：复用：;按瀑布项目统计的已完成任务工作的预计工时(EV);按瀑布项目统计的任务的计划完成工时(PV);公式：;按瀑布项目统计的进度偏差率=(EV-PV)/PV*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class sv_in_waterfall extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        $task = $this->dao->select('project, SUM(estimate) as estimate, SUM(consumed) as consumed, SUM(`left`) as `left`')
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
            ->leftJoin("($task)")->alias('t2')->on('t1.id=t2.project')
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
        $sv = $estimate == 0 ? 0 : round(($ev - $estimate) / $estimate, 4);

        if(!isset($this->result[$project])) $this->result[$project] = $sv;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
