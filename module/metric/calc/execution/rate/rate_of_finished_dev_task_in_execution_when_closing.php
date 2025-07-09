<?php
/**
 * 按执行统计的执行关闭时开发任务完成率
 * Scale of story in execution when starting.
 *
 * 范围：execution
 * 对象：task
 * 目的：rate
 * 度量名称：按执行统计的执行关闭时开发任务完成率
 * 单位：%
 * 描述：按执行统计的开发任务按计划完成率是指执行时已完成的开发任务数与执行开始时计划的开发任务数的比率。该度量项反映了团队能否按期完成规划的开发任务，可以帮助团队识别执行中存在的潜在问题。
 * 复用：按执行统计的执行关闭时已完成的开发任务数、按执行统计的开发任务数，公式：按执行统计的执行关闭时已完成的开发任务数÷按执行统计的开发任务数。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_finished_dev_task_in_execution_when_closing extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
