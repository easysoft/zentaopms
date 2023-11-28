<?php
/**
 * 按项目统计的剩余工期。
 * Left period of project.
 *
 * 范围：project
 * 对象：project
 * 目的：time
 * 度量名称：按项目统计的剩余工期
 * 单位：天
 * 描述：按项目统计的剩余工期表示项目在当前时间点上还剩下的工作时间。这个度量项可以帮助团队评估项目的剩余工作量和进度。通过比较剩余工期和剩余工时，可以预测项目是否能够按时完成，并采取适当的措施来调整进度，以确保项目的成功交付。
 * 定义：已关闭的项目;未关闭的项目;计划截止日期-当前日期;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class left_period_of_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.id', 't1.status', 't1.end');

    public $result = array();

    public function calculate($row)
    {
        if($row->status == 'closed')
        {
            $this->result[$row->id] = 0;
        }
        else
        {
            $this->result[$row->id] = strtotime($row->end) - strtotime(date('Y-m-d'));
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
