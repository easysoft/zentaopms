<?php
/**
 * 按项目统计的已完成业务需求数
 * Count of finished epic in project.
 *
 * 范围：project
 * 对象：epic
 * 目的：scale
 * 度量名称：按项目统计的已完成业务需求数
 * 单位：个
 * 描述：按项目统计的已完成业务需求数是指状态为已关闭且关闭原因为已完成的业务需求的数量。反映了项目团队在满足组织业务目标和需求方面的已经实现的任务和计划。已完成业务需求数量的增加表示项目团队已经成功完成了一定数量的业务需求工作，并取得了一定的成果。
 * 定义：项目中业务需求的个数求和 状态为已关闭 关闭原因为已完成 过滤已删除的业务需求 过滤已删除的项目
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_epic_in_project extends baseCalc
{
    public $dataset = 'getEpicWithProject';

    public $fieldList = array('t2.project', 't1.status', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        if($row->status == 'closed' && $row->closedReason = 'done')
        {
            if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
            $this->result[$row->project] += 1;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
