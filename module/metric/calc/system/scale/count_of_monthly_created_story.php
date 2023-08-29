<?php
/**
 * 按全局统计的月度新增研发需求数。
 * Count of monthly created story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的月度新增研发需求数
 * 单位：个
 * 描述：按全局统计的月度新增研发需求数表示每月新增加的研发需求的数量。该度量项反映了组织每月新增加的研发需求数量，可以用于评估组织的研发需求增长趋势和紧迫性。
 * 定义：所有的研发需求个数求和;创建时间为某年某月;过滤已删除的研发需求;
 * 度量库：
 * 收集方式：realtime
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
