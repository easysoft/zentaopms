<?php
/**
 * 按系统统计的月度新增研发需求数。
 * Count of monthly created story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的月度新增研发需求数
 * 单位：个
 * 描述：按系统统计的月度新增的研发需求数量反映了组织每个月内新增的研发需求数量，用于评估组织的研发活动的监测、需求管理、项目规划、绩效评估和决策支持具有重要意义。它提供了一个动态的指标，为组织提供了实时的数据支持，以便更好地管理和优化研发活动。
 * 定义：所有的研发需求个数求和;创建时间为某年某月;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_created_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($data)
    {
        $openedDate = $data->openedDate;

        if(empty($openedDate)) return false;

        $year  = substr($openedDate, 0, 4);
        $month = substr($openedDate, 5, 2);

        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;

        $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $months)
        {
            foreach($months as $month => $value)
            {
                $records[] = array('year' => $year, 'month' => $month, 'value' => $value);
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
