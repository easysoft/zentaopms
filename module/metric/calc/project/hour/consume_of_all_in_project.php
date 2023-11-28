<?php
/**
 * 按项目统计的项目内所有消耗工时数。
 * Consume of all in project.
 *
 * 范围：project
 * 对象：effort
 * 目的：hour
 * 度量名称：按项目统计的项目内所有消耗工时数
 * 单位：小时
 * 描述：按项目统计的项目内所有消耗工时数是指项目实际花费的总工时数。该度量项可以用来评估项目的工时投入情况和对资源的利用效率。较高的消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。
 * 定义：项目中所有日志记录的工时之和;记录时间在某年;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consume_of_all_in_project extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t3.id as project, SUM(t1.consumed) as consumed')
            ->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t3.deleted')->eq('0')
            ->andWhere('t3.type')->eq('project')
            ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t3.vision)")
            ->groupBy('t3.id')
            ->query();
    }

    public function calculate($row)
    {
        $project  = $row->project;
        $consumed = $row->consumed;

        if(!isset($this->result[$project])) $this->result[$project] = $consumed;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
