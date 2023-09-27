<?php
/**
 * 按产品统计的月度关闭研发需求数。
 * Count of monthly closed story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的月度关闭研发需求数
 * 单位：个
 * 描述：按产品统计的月度关闭研发需求规模数表示产品在某月度关闭的研发需求数。该度量项反映了产品团队每月因完成、不做或取消等原因关闭的研发需求数，可以用于评估产品团队的研发需求规模管理和调整情况。
 * 定义：产品中关闭时间为某年某月的研发需求的个数求和;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_closed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $closedDate = $data->closedDate;

        if(empty($closedDate)) return false;

        $year  = substr($closedDate, 0, 4);
        $month = substr($closedDate, 5, 2);

        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$month])) $this->result[$product][$year][$month] = 0;

        $this->result[$product][$year][$month] += 1;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $months)
            {
                foreach($months as $month => $value)
                {
                    $records[] = array('product' => $product, 'year' => $year, 'month' => $month, 'value' => $value);
                }
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
