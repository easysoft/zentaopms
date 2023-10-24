<?php
/**
 * 按瀑布项目统计的成本偏差率。
 * Cv in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：rate
 * 度量名称：按瀑布项目统计的成本偏差率
 * 单位：%
 * 描述：按瀑布项目统计的成本偏差率用于衡量项目的实际成本与计划成本之间的差异。它通过计算已花费的成本与预计花费的成本之间的差异来评估项目的成本绩效。
 * 定义：复用：;按瀑布项目统计的已完成任务工作的预计;按瀑布项目统计的实际花费工时(AC);公式：;按瀑布项目统计的成本偏差率=(EV-AC)/AC*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class cv_in_waterfall extends baseCalc
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

        $ac = $this->dao->select('t3.id as project, SUM(t1.consumed) as consumed')
            ->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where("NOT FIND_IN_SET('or', t3.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t3.vision)")
            ->groupBy('t3.id')
            ->get();

        return $this->dao->select('t1.id as project, t2.estimate, t2.consumed, t2.`left`, t3.consumed as ac')
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin("($ev)")->alias('t2')->on('t1.id=t2.project')
            ->leftJoin("($ac)")->alias('t3')->on('t1.id=t3.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t1.model')->eq('waterfall')
            ->query();
    }

    public function calculate($row)
    {
        $project = $row->project;
        $ac      = $row->ac;

        $estimate = (float)$row->estimate;
        $consumed = (float)$row->consumed;
        $left     = (float)$row->left;
        $total    = $consumed + $left;

        $ev = $total == 0 ? 0 : round($consumed / $total * $estimate, 2);

        if(!isset($this->result[$project]))
        {
            $this->result[$project]['ev'] = $ev;
            $this->result[$project]['ac'] = $ac;
        }
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $project => $value)
        {
            $ac = (float)$value['ac'];
            $ev = (float)$value['ev'];

            $ratio = $ac == 0 ? 0 : round(($ev - $ac) / $ac, 4);

            $records[] = array('project' => $project, 'value' => $ratio);
        }
        return $this->filterByOptions($records, $options);
    }
}
