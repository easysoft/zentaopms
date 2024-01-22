<?php
/**
 * 按人员统计的延期任务数。
 * Count of delayed task in user.
 *
 * 范围：user
 * 对象：task
 * 目的：scale
 * 度量名称：按人员统计的延期任务数
 * 单位：个
 * 描述：按人员统计的延期任务数是指每个人延期任务总量。该度量项可以用来评估每个人的工作效率和完成能力。
 * 定义：截止当前时间;统计每个人延期任务数的求和;过滤已删除的任务;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delayed_task_in_user extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.assignedTo', 't1.deadline');

    public $result = array();

    public function calculate($row)
    {
        $nowDate    = helper::now();
        $deadline   = $row->deadline;
        $assignedTo = $row->assignedTo;

        if(empty($deadline) || substr($deadline, 0 ,4) == '0000') return false;
        if(strtotime($nowDate) <= strtotime($deadline)) return false;

        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = 0;
        $this->result[$assignedTo] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
