<?php
/**
 * 按人员统计任务消耗工时数。
 * Count of consumed task in user.
 *
 * 范围：user
 * 对象：task
 * 目的：scale
 * 度量名称：按人员统计任务消耗工时数
 * 单位：小时
 * 描述：按人员统计任务消耗工时数表示每个人任务消耗工时数量之和。
 * 定义：所有任务消耗工时数求和;
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consume_of_task_in_user extends baseCalc
{
    public $dataset = 'getTaskEfforts';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        $account  = $row->account;
        $consumed = $row->consumed;

        if(!isset($this->result[$account])) $this->result[$account] = 0;
        $this->result[$account] += $consumed;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
