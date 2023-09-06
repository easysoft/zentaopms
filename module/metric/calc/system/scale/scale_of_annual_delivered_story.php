<?php
/**
 * 按全局统计的年度交付研发需求规模数。
 * Scale of annual delivered story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的年度交付研发需求规模数
 * 单位：个
 * 描述：按全局统计的年度交付研发需求规模数表示某年度交付的研发需求的规模总数。该度量项反映了组织每年交付给其他团队或部门的研发需求的规模总数，可以用于评估组织的研发需求交付管理和效果。
 * 定义：所有的所处阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年的研发需求规模数求和;过滤已删除的研发需求;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_annual_delivered_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.stage', 't1.releasedDate', 't1.closedReason', 't1.closedDate', 't1.estimate');

    public function calculate($row)
    {
        $date = null;
        if($row->closedReason == 'done') $date = $row->closedDate;
        if($row->stage == 'released' && !empty($row->closedDate)) $date = $row->releasedDate;

        if($date === null) return false;

        $year = $this->getYear($date);
        if(!$year) return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += $row->estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
