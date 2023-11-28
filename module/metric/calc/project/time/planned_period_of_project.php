<?php
/**
 * 按项目统计的计划工期。
 * Planned period of project.
 *
 * 范围：project
 * 对象：project
 * 目的：time
 * 度量名称：按项目统计的计划工期
 * 单位：天
 * 描述：按项目统计的计划工期是基于项目计划和排期制定的预估工期。该度量项通过确定项目开始和结束日期之间的时间间隔来计算。计划工期用于制定项目的时间目标和进度安排，为项目管理提供了基准。与实际工期进行比较，可以评估项目的进展和时间规划的准确性，帮助团队及时调整工作计划。
 * 定义：计划完成日期-计划开始日期;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class planned_period_of_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.id', 't1.begin', 't1.end');

    public $result = array();

    public function calculate($row)
    {
        $this->result[$row->id] = (strtotime($row->end) - strtotime($row->begin))/86400;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
