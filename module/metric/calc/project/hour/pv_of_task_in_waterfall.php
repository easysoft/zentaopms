<?php
/**
 * 按瀑布项目统计的任务的计划完成工时(PV)。
 * Pv of task in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按瀑布项目统计的任务的计划完成工时(PV)
 * 单位：小时
 * 描述：按瀑布项目统计的任务的计划完成工时指的是在瀑布项目管理方法中，按计划需要完成的任务的总预计工时。这个度量项用于评估任务的预期工作量，可用作与实际花费工时和已完成任务的预计工时进行比较。
 * 定义：瀑布项目中所有任务的预计工时之和;过滤已删除的任务;过滤已取消的任务;过滤已删除的执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class pv_of_task_in_waterfall extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        $pv = $this->dao->select('project, SUM(estimate) as estimate')
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

        return $this->dao->select('t1.id as project, t2.estimate as pv')
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin("($pv)")->alias('t2')->on('t1.id=t2.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t1.model')->eq('waterfall')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t1.vision)")
            ->query();
    }

    public function calculate($row)
    {
        $project = $row->project;
        $pv      = $row->pv;

        if(!isset($this->result[$project])) $this->result[$project] = $pv;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
