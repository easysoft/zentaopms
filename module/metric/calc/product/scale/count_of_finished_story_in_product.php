<?php
/**
 * 按产品统计的已完成研发需求数。
 * Count of finished story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的已完成研发需求数
 * 单位：个
 * 描述：产品中所处阶段为已关闭且关闭原因为已完成的研发需求个数求和 过滤已删除的研发需求 过滤已删除的产品
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.stage', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        if($row->stage == 'closed' and $row->closedReason == 'done')
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
