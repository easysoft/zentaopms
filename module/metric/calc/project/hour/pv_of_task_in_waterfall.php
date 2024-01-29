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
    public $dataset = 'getWaterfallTasks';

    public $fieldList = array('t1.id as project', 't2.estimate');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $pv      = $row->estimate;

        if(!isset($this->result[$project])) $this->result[$project] = $pv;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
