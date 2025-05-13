<?php
/**
 * 按人员统计的已完成任务数。
 * Count of finished task in user.
 *
 * 范围：user
 * 对象：task
 * 目的：scale
 * 度量名称：按人员统计的已完成任务数
 * 单位：个
 * 描述：按人员统计的已完成任务数表示每个人已完成的任务数量之和。
 * 定义：所有任务个数求和;完成者为某人;
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_task_in_user extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if($row->status == 'wait' || $row->status == 'doing') return false;

        if(!isset($this->result[$row->finishedBy])) $this->result[$row->finishedBy] = array();
        $this->result[$row->finishedBy][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $finishedBy => $tasks)
        {
            if(!is_array($tasks)) continue;

            $this->result[$finishedBy] = count($tasks);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
