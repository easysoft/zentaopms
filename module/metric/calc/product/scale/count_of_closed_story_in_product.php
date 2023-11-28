<?php
/**
 * 按产品统计的已关闭研发需求数。
 * Count of closed story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的已关闭研发需求数
 * 单位：个
 * 描述：按产品统计的已关闭研发需求数是指产品中已经关闭的研发需求的数量。该度量项反映了产品研发的进展，可以用于评估产品的研发需求管理绩效和成果。较高的已关闭研发需求数可能代表团队取得了越多的研发成果。
 * 定义：产品中研发需求的个数求和;阶段为已关闭;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.stage');

    public $result = array();

    public function calculate($row)
    {
        if($row->stage == 'closed')
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
