<?php
/**
 * 按项目统计的的工期偏差。
 * Variance of time in project.
 *
 * 范围：project
 * 对象：project
 * 目的：time
 * 度量名称：按项目统计的的工期偏差
 * 单位：天
 * 描述：按项目统计的工期偏差表示实际工期与计划工期之间的差异。工期偏差的正值表示项目进度延迟，负值表示项目进度提前。工期偏差可以帮助团队及时识别项目进度的偏差，并采取相应的调整措施来重新规划资源和工作计划，以确保项目能够按时完成。
 * 定义：复用：;按项目统计的实际工期;按项目统计的计划工期;公式：;按项目统计的工期偏差=按项目统计的实际工期-按项目统计的计划工期;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class variance_of_time_in_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.id', 't1.begin', 't1.end', 't1.realBegan', 't1.realEnd', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project   = $row->id;
        $begin     = $row->begin;
        $end       = $row->end;
        $realBegan = $row->realBegan;
        $realEnd   = $row->realEnd;
        $status    = $row->status;

        $plan = (strtotime($end) - strtotime($begin)) / 86400;

        if($status == 'closed') $actual = (strtotime($realEnd) - strtotime($realBegan)) / 86400;
        if($status != 'closed') $actual = (strtotime(date('Y-m-d')) - strtotime($realBegan)) / 86400;

        $this->result[$project] = $actual - $plan;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
