<?php
/**
 * 按执行统计的开发人员执行外任务消耗工时数
 * Hour of outplan task in execution
 *
 * 范围：execution
 * 对象：effort
 * 目的：hour
 * 度量名称：按执行统计的开发人员执行外任务消耗工时数
 * 单位：小时
 * 描述：按执行统计的执行外任务消耗工时数表示执行中非本期执行任务所消耗的工时，例如执行外的临时会议、帮助同事解决问题等。
 * 定义：执行中任务消耗工时求和，关键词为计划外，创建人的职位为研发，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class hour_of_outplan_task_in_execution extends baseCalc
{
    public $result = array();

    public $dataset = 'getTasks';

    public $fieldList = array('t1.execution', 't1.consumed', 't4.role', 't1.keywords');

    public function calculate($row)
    {
        $isOutPlan = trim($row->keywords) == '计划外';

        if($isOutPlan && $row->role == 'dev')
        {
            if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
            $this->result[$row->execution] += $row->consumed;
        }
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->getRecords(array('execution', 'value')), $options);
    }
}
