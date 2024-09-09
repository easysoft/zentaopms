<?php
/**
 * 按项目统计的未关闭用户需求数
 * Count of unclosed requirement in project.
 *
 * 范围：project
 * 对象：requirement
 * 目的：scale
 * 度量名称：按项目统计的未关闭用户需求数
 * 单位：个
 * 描述：按项目统计的未关闭用户需求数是指项目中尚未满足或处理的用户需求的数量，反映了项目团队在满足用户期望和需求方面的进行中任务和计划。未关闭用户需求数量的增加表示项目团队尚未完成的用户需求工作较多，需要进一步跟进和处理，以确保项目能够满足用户的期望。
 * 定义：复用： 按项目统计的用户需求总数 按项目统计的已关闭用户需求数 公式： 按项目统计的未关闭用户需求数=按项目统计的用户需求总数-按项目统计的已关闭用户需求数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_requirement_in_project extends baseCalc
{
    public $dataset = 'getRequirementsWithProject';

    public $fieldList = array('t3.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if($status != 'closed')
        {
            if(!isset($this->result[$project])) $this->result[$project] = 0;
            $this->result[$project] += 1;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
