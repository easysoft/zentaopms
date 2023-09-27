<?php
/**
 * 按产品统计的未关闭研发需求数。
 * Count of unclosed story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的未关闭研发需求数
 * 单位：个
 * 描述：按产品统计的未关闭研发需求数是指产品中未关闭的研发需求的数量。这个度量项可以反映产品团队研发需求的开发进度。未关闭研发需求数越多，说明产品团队的开发工作还有一定的进行中，并需要进一步跟进和完成。
 * 定义：复用：;按产品统计的研发需求总数;按产品统计的已关闭研发需求数;按产品统计的关闭研发需求总数=按产品统计的研发需求总数-按产品统计的已关闭研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.stage');

    public $result = array();

    public function calculate($row)
    {
        if($row->stage != 'closed')
        {
            if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
            $this->result[$row->product] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
