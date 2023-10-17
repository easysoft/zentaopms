<?php
/**
 * 按瀑布项目统计的实际花费工时(AC)。
 * Ac of all in waterfall.
 *
 * 范围：project
 * 对象：effort
 * 目的：hour
 * 度量名称：按瀑布项目统计的实际花费工时(AC)
 * 单位：小时
 * 描述：按瀑布项目统计的实际花费工时指的是在瀑布项目管理方法中，实际花费的工时总数。这个度量项用于评估实际工作量和预计工作量之间的差异，有助于估计项目的真实进展情况。AC的值越接近EV，代表项目团队在任务执行方面表现得越好。
 * 定义：瀑布项目中所有日志记录的工时之和;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class ac_of_all_in_waterfall extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t3.id as project, SUM(t1.consumed) as ac')
            ->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t3.deleted')->eq('0')
            ->andWhere('t3.type')->eq('project')
            ->andWhere('t3.model')->eq('waterfall')
            ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t3.vision)")
            ->groupBy('t3.id')
            ->query();
    }

    public function calculate($row)
    {
        $project = $row->project;
        $ac      = $row->ac;

        if(!isset($this->result[$project])) $this->result[$project] = $ac;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
