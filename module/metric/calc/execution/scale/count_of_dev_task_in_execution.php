<?php
/**
 * 按执行统计的开发任务数
 * Scale of dev task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：scale
 * 度量名称：按执行统计的开发任务数
 * 单位：count
 * 描述：按执行统计的开发任务数是指执行中任务类型为开发的任务数求和。该度量项反映了执行中开发的工作量，可以帮助团队进行开发资源调配。
 * 复用：执行中满足以下条件的任务个数求和，条件是：任务类型为开发，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@zentao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_dev_task_in_execution extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.type', 't1.execution');

    public $result = array();

    public function calculate($row)
    {
        if($row->type == 'devel')
        {
            if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
            $this->result[$row->execution] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
