<?php
/**
 * 按系统统计的年度研发需求完成率。
 * Rate of annual finished story.
 *
 * 范围：system
 * 对象：story
 * 目的：rate
 * 度量名称：按系统统计的年度研发需求完成率
 * 单位：%
 * 描述：按系统统计的年度研发需求完成率反映了组织在年度研发过程中完成需求的能力和表现，反映了组织对于评估项目目标达成、资源规划和优化、业务决策和战略执行、绩效评估和激励机制，以及持续改进和效率提升具有重要意义。
 * 定义：复用：;按系统统计的年度完成研发需求数;按系统统计的年度有效研发需求数;公式：;按系统统计的年度研发需求完成率=按系统统计的年度完成研发需求数/按系统统计的年度有效研发需求数*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_annual_finished_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.closedDate', 't1.closedReason');

    public $result = array();

    public function calculate($data)
    {
        $closedDate   = $data->closedDate;
        $closedReason = $data->closedReason;

        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();

        if(!isset($this->result[$year]['finished'])) $this->result[$year]['finished'] = 0;
        if(!isset($this->result[$year]['valid']))    $this->result[$year]['valid'] = 0;

        if($closedReason == 'done') $this->result[$year]['finished'] += 1;
        if(in_array($closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel')) === false) $this->result[$year]['valid'] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $finished = $value['finished'];
            $valid    = $value['valid'];

            $ratio = $valid == 0 ? 0 : round($finished / $valid, 2);
            $records[] = array('year' => $year, 'value' => $ratio);
        }
        return $this->filterByOptions($records, $options);
    }
}
