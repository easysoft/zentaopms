<?php
/**
 * 按执行统计的任务进度。
 * Progress of task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：rate
 * 度量名称：按执行统计的任务进度
 * 单位：%
 * 描述：按执行统计的任务进度是指执行团队按已消耗的工时数与已消耗和剩余的工时数的比率。该度量项反映了任务的执行进展情况，可以帮助团队评估任务是否按计划进行并做出相应调整。
 * 定义：复用：;按执行统计的任务消耗工时数;按执行统计的任务剩余工时数;公式：;按执行统计的任务进度=按执行统计的任务消耗工时数/（按执行统计的任务消耗工时数+按执行统计的任务剩余工时数）;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class progress_of_task_in_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.id', 't1.progress');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->id;
        $progress  = $row->progress;

        $this->result[$execution] = (float)$progress / 100;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
