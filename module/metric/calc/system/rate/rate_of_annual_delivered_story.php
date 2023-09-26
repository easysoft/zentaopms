<?php
/**
 * 按全局统计的年度研发需求交付率。
 * Rate of annual delivered story.
 *
 * 范围：global
 * 对象：story
 * 目的：rate
 * 度量名称：按全局统计的年度研发需求交付率
 * 单位：%
 * 描述：按全局统计的研发需求交付率表示按全局统计的年度已交付的研发需求规模数相对于按全局统计的有效研发需求数。这个度量项衡量了研发团队按时交付年度需求的能力。交付率越高，代表研发团队能够按时将年度需求交付给其他团队，实现年度目标的实现。
 * 定义：复用：;按产品统计的年度交付研发需求数;按产品统计的年度有效研发需求数;公式：;按产品统计的年度研发需求完成率=按产品统计的年度交付研发需求数/按产品统计的年度有效研发需求数*100%;
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
