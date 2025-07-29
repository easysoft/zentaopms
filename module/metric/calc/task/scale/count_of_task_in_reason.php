<?php
/**
 * 按关闭原因统计的任务数。
 * Count of task in closed reason.
 *
 * 范围：closedReason
 * 对象：task
 * 目的：scale
 * 度量名称：按关闭原因统计的任务数
 * 单位：个
 * 描述：按关闭原因统计的任务数
 * 定义：按关闭原因统计的任务数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_task_in_reason extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'closed') return;

        if(!isset($this->result[$row->closedReason])) $this->result[$row->closedReason] = array();
        $this->result[$row->closedReason][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $closedReason => $tasks)
        {
            if(!is_array($tasks)) continue;

            $this->result[$closedReason] = count($tasks);
        }

        $records = $this->getRecords(array('reason', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
