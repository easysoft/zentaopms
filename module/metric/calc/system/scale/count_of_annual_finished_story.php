<?php
/**
 * 按系统统计的年度完成研发需求数。
 * Count of annual finished story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的年度完成研发需求数
 * 单位：个
 * 描述：按系统统计的年度完成的研发需求数量反映了组织在每年完成的研发需求数量，用于评估组织的研发活动的产出、项目管理能力、产品质量和市场竞争力具有重要意义。有助于优化资源规划、提高研发效率，并推动持续改进和创新。
 * 定义：所有的研发需求个数求和;关闭时间为某年;关闭原因为已完成;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_story extends baseCalc
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

        if($year == '0000' || $closedReason != 'done') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $records[] = array('year' => $year, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
