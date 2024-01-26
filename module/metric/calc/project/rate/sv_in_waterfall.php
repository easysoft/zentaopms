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
    public $dataset = 'getWaterfallTasks';

    public $fieldList = array('t1.id as project', 't2.estimate', 't2.consumed', 't2.`left`');

    public $result = array();

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
