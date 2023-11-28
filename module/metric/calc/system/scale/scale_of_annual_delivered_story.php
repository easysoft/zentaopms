<?php
/**
 * 按系统统计的年度交付研发需求规模数。
 * Scale of annual delivered story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的年度交付研发需求规模数
 * 单位：个
 * 描述：按系统统计的年度交付的研发需求规模数反映了组织在年度期间交付的研发需求的规模总数，用于评估组织对于项目交付评估、绩效评估、资源规划、风险评估、学习和持续改进具有重要意义。
 * 定义：所有研发需求规模数求和;阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年;过滤父研发需求;过滤已删除的研发需求;
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

    public $fieldList = array('t1.stage', 't1.releasedDate', 't1.closedReason', 't1.closedDate', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($row)
    {
        $parent = $row->parent;
        if($parent == '-1') return false;

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
