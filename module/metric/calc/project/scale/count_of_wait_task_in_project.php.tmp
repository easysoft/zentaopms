<?php
/**
 * 按项目统计的未开始任务数。
 * count_of_wait_task_in_project.
 *
 * 范围：project
 * 对象：task
 * 目的：scale
 * 度量名称：按项目统计的未开始任务数
 * 单位：个
 * 描述：按项目统计的未开始任务数指的是在项目执行过程中未开始进行的任务数量。这个度量项帮助团队了解项目进展的一部分，即有多少任务未启动。通过统计未开始任务数，团队可以评估项目的准备状况、资源分配以及可能存在的延迟因素。
 * 定义：项目中任务个数求和;状态为未开始;过滤已删除的任务;过滤已删除执行的任务;过滤已删除的项目
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
class count_of_wait_task_in_project extends baseCalc
{
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($row)
    {
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}