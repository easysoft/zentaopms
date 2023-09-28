<?php
/**
 * 按系统统计的年度研发需求交付率。
 * Rate of annual delivered story.
 *
 * 范围：system
 * 对象：story
 * 目的：rate
 * 度量名称：按系统统计的年度研发需求交付率
 * 单位：%
 * 描述：按系统统计的年度研发需求交付率反映了组织在年度研发过程中按时交付需求的能力和表现，用于评估组织对于评估项目交付能力、客户满意度和信任建立、项目进度管理和风险控制、绩效评估和激励机制，以及持续改进和效率提升具有重要意义。
 * 定义：复用：;按系统统计的年度交付研发需求数;按系统统计的年度有效研发需求数;公式：;按系统统计的年度研发需求完成率=按系统统计的年度交付研发需求数/按系统统计的年度有效研发需求数*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_annual_delivered_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.stage', 't1.releasedDate', 't1.closedReason', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $stage        = $row->stage;
        $closedReason = $row->closedReason;
        $releasedDate = $row->releasedDate;
        $closedDate   = $row->closedDate;

        $date = null;
        if($closedReason == 'done') $date = $closedDate;
        if($stage == 'released' && !empty($closedDate)) $date = $releasedDate;

        if(empty($date)) return false;

        $year = substr($date, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array('finished' => 0, 'valid' => 0);
        if($stage == 'released' || $closedReason == 'done') $this->result[$year]['finished'] += 1;
        if(!in_array($closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result[$year]['valid'] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $storyInfo)
        {
            $deliveredRate = $storyInfo['valid'] ? round($storyInfo['finished'] / $storyInfo['valid'], 4) : 0;
            $records[] = array('year' => $year, 'value' => $deliveredRate);
        }
        return $this->filterByOptions($records, $options);
    }
}
