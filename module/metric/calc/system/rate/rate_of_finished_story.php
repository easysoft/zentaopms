<?php
/**
 * 按系统统计的研发需求完成率。
 * Rate of finished story.
 *
 * 范围：system
 * 对象：story
 * 目的：rate
 * 度量名称：按系统统计的研发需求完成率
 * 单位：%
 * 描述：按系统统计的研发需求完成率反映了组织按系统统计的已完成研发需求数和按系统统计的有效研发需求数之间的比率，用于评估组织对于进度控制、绩效评估、风险评估、资源规划和利用，以及持续改进和效率提升具有重要意义。
 * 定义：复用：;按系统统计的完成研发需求数;按系统统计的有效研发需求数;公式：;按系统统计的研发需求完成率=按系统统计的已完成研发需求数/按系统统计的有效研发需求数*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_finished_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.status', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result['finished'])) $this->result['finished'] = 0;
        if(!isset($this->result['valid']))    $this->result['valid'] = 0;

        if($row->status == 'closed' and $row->closedReason == 'done')                            $this->result['finished'] ++;
        if(!in_array($row->closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result['valid'] ++;
    }

    public function getResult($options = array())
    {
        $this->result = $this->result['valid'] ? round($this->result['finished'] / $this->result['valid'], 4) : 0;
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);

    }
}
