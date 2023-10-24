<?php
/**
 * 按项目统计的的实际工期。
 * Count of actual time in project.
 *
 * 范围：project
 * 对象：project
 * 目的：time
 * 度量名称：按项目统计的的实际工期
 * 单位：天
 * 描述：按项目统计的实际工期反映了项目在执行过程中实际花费的时间。该度量项通过统计项目实际的开始和完成日期来计算。实际工期的准确记录能够帮助团队评估项目的执行效率和时间管理能力。较短的实际工期可能意味着项目按计划进行，团队高效执行，而较长的实际工期可能表明项目存在一些延迟和挑战。
 * 定义：已关闭的项目：;实际完成日期-实际开始日期;未关闭的项目：;当前日期-实际开始日期;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_actual_time_in_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.id', 't1.realBegan', 't1.realEnd', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project   = $row->id;
        $realBegan = $row->realBegan;
        $realEnd   = $row->realEnd;
        $status    = $row->status;

        if($status == 'closed') $actual = (strtotime($realEnd) - strtotime($realBegan)) / 86400;
        if($status != 'closed') $actual = (strtotime(date('Y-m-d')) - strtotime($realBegan)) / 86400;

        $this->result[$project] = $actual;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
