<?php
/**
 * 按产品统计的年度完成研发需求规模数。
 * Scale of annual finished story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度完成研发需求规模数
 * 单位：工时
 * 描述：按产品统计的年度完成研发需求规模数是指产品在某年度已关闭且关闭原因为已完成研发需求的总规模数。这个度量项可以反映产品团队在一年时间内的开发效率和成果。完成研发需求规模数的增加说明产品团队在该年度内取得了更多的开发成果和交付物。
 * 定义：产品中研发需求的规模数求和;关闭时间在某年;关闭原因为已完成;过滤父研发需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_annual_finished_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate', 't1.closedReason', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($data)
    {
        $product      = $data->product;
        $closedDate   = $data->closedDate;
        $closedReason = $data->closedReason;
        $estimate     = $data->estimate;
        $parent       = $data->parent;

        if($parent == '-1') return false;

        if(empty($closedDate)) return false;

        $year  = substr($closedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;

        if($closedReason == 'done') $this->result[$product][$year] += $estimate;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array('product' => $product, 'year' => $year, 'value' => $value);
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
