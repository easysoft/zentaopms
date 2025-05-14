<?php
/**
 * 按系统统计的开发任务完成率。
 * Rate of finished task.
 *
 * 范围：system
 * 对象：task
 * 目的：rate
 * 度量名称：按系统统计的任务完成率
 * 单位：%
 * 描述：按系统统计的任务完成率是指已完成的任务占相对于任务数量的比例。
 * 定义：按系统统计的已完成任务数;按系统统计的任务数;公式：已完成任务数÷任务数;
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_devel_finished_task extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array('finished' => 0, 'total' => 0);

    public function calculate($row)
    {
        if($row->type != 'devel') return;

        $this->result['total'] += 1;
        if($row->status == 'done' || $row->closedReason == 'done') $this->result['finished'] += 1;
    }

    public function getResult($options = array())
    {
        $total    = $this->result['total'];
        $finished = $this->result['finished'];
        $rate     = $total == 0 ? 0 : round($finished / $total, 4);

        $records = array(array('value' => $rate));
        return $this->filterByOptions($records, $options);
    }
}
