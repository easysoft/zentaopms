<?php
/**
 * 按项目统计的已投入人天。
 * Day of invested in project.
 *
 * 范围：project
 * 对象：effort
 * 目的：hour
 * 度量名称：按项目统计的已投入人天
 * 单位：人天
 * 描述：按项目统计的已投入人天是指项目总共投入的工作天数。该度量项可以用来评估项目的人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。
 * 定义：复用：;按项目统计的日志记录的工时总数;公式：;按项目统计的已投入人天=按项目统计的项目内所有消耗工时数/后台配置的每日可用工时;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class day_of_invested_in_project extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        $defaultHours = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('execution')
            ->andWhere('key')->eq('defaultWorkhours')
            ->fetch('value');
        if(empty($defaultHours)) $defaultHours = 7;

        return $this->dao->select("t3.id as project, SUM(t1.consumed) as consumed, $defaultHours as defaultHours")
            ->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t3.deleted')->eq('0')
            ->andWhere('t3.type')->eq('project')
            ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
            ->groupBy('t3.id')
            ->query();
    }

    public function calculate($row)
    {
        $project      = $row->project;
        $consumed     = $row->consumed;
        $defaultHours = $row->defaultHours;

        if(!isset($this->result[$project])) $this->result[$project] = $defaultHours ? round($consumed / $defaultHours, 2) : 0;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
