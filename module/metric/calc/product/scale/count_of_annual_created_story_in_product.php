<?php
/**
 * 按产品统计的年度新增研发需求数。
 * Count of annual created story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度新增研发需求数
 * 单位：个
 * 描述：按产品统计的年度新增研发需求数是指产品在某年度新增的研发需求数量。这个度量项可以反映产品团队在该年度内需求的增长或变化情况。
 * 定义：产品中研发需求的个数求和;创建时间为某年;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $product    = $row->product;
        $openedDate = $row->openedDate;

        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);

        if(empty($year) || $year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;
        $this->result[$product][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array(
                    'product' => $product,
                    'year'    => $year,
                    'value'   => $value,
                );
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
