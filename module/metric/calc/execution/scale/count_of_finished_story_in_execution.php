<?php
/**
 * 按执行统计的已完成研发需求数。
 * Count of finished story in execution.
 *
 * 范围：execution
 * 对象：story
 * 目的：scale
 * 度量名称：按执行统计的已完成研发需求数
 * 单位：个
 * 描述：按执行统计的已完成研发需求数是指状态为已关闭且关闭原因为已完成的研发需求的数量。这个度量项可以反映执行团队在开发过程中的进展和交付能力。已完成研发需求数越多，说明执行团队在该时间段内取得了更多的开发成果。
 * 定义：执行中研发需求的个数求和;状态为已关闭;关闭原因为已完成;过滤已删除的研发需求;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_story_in_execution extends baseCalc
{
    public $dataset = 'getDevStoriesWithExecution';

    public $fieldList = array('t3.project', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->project;
        if(!isset($this->result[$execution])) $this->result[$execution] = 0;
        if($row->closedReason == 'done') $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
