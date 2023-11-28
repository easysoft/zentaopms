<?php
/**
 * 按产品统计的研发需求总数。
 * Count of story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的研发需求总数
 * 单位：个
 * 描述：按产品统计的研发需求总数是指产品中创建的所有研发需求的数量。这个度量项可以反映团队需进行研发工作的规模。研发需求总数越多，可能意味着产品规模越大，面临的开发工作越多。
 * 定义：产品中研发需求的个数求和;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
        $this->result[$row->product] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
