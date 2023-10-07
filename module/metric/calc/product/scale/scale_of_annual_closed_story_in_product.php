<?php
/**
 * 按产品统计的年度关闭研发需求规模数。
 * Scale of annual closed story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度关闭研发需求规模数
 * 单位：工时
 * 描述：按产品统计的年度关闭研发需求规模数表示产品在某年度关闭的研发需求的规模总数。该度量项反映了产品团队每年因完成、不做或取消等原因关闭研发需求数的规模总数，可以用于评估产品的团队研发需求规模管理和调整情况。
 * 定义：产品中研发需求的规模数求和;关闭时间在某年;过滤父研发需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_annual_closed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $closedDate = $data->closedDate;
        $estimate   = $data->estimate;
        $parent     = $data->parent;

        if($parent == '-1') return false;

        if(empty($closedDate)) return false;

        $year  = substr($closedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;

        $this->result[$product][$year] += $estimate;
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
