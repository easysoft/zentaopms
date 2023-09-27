<?php
/**
 * 按产品统计的月度新增研发需求数。
 * Count of monthly created story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的月度新增研发需求数
 * 单位：个
 * 描述：按产品统计的月度新增研发需求数是指在某月度新增的研发需求数量。这个度量项可以反映产品团队在该月度内需求的增长情况。月度新增研发需求数越多可能表示团队正在不断地推出新功能。
 * 定义：产品中研发需求的个数求和;创建时间在某年某月;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_created_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.openedDate');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $openedDate = $data->openedDate;

        if(empty($openedDate)) return false;

        $year  = substr($openedDate, 0, 4);
        $month = substr($openedDate, 5, 2);

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
