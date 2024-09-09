<?php
/**
 * 按项目统计的已关闭用户需求数。
 * Count of closed requirement in project.
 *
 * 范围：project
 * 对象：requirement
 * 目的：scale
 * 度量名称：按项目统计的已关闭用户需求数
 * 单位：个
 * 描述：按项目统计的已关闭用户需求数是指项目中状态为已关闭的用户需求的数量，反映了项目团队在满足用户期望和需求方面的已完成任务和计划。已关闭用户需求数量的增加表示项目团队已经成功完成了一定数量的用户需求工作，并取得了一定的成果。
 * 定义：项目中用户需求个数求和;过滤已删除的用户需求;状态为已关闭;过滤已删除的项目
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    TingtingDai <daitingting@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_requirement_in_project extends baseCalc
{
    public $dataset = 'getRequirementsWithProject';

    public $fieldList = array('t3.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status == 'closed') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
